<?php 

App::uses('AppController', 'Controller');
class UtilityController extends AppController {

    public $components = array('Paginator', 'Session', 'RequestHandler', 'Util', 'SocietyBill');



    public $helpers = array('Js');



    public $societyloginID = '';



    public $societyBillingFrequencyId = 0;







    //public $uses = array('Society');







    public function beforeFilter() {



        parent::beforeFilter();



        $this->layout = 'society_dashboard';



        $this->societyloginID = $this->Session->read('Auth.User.id');



        $this->set("utilObj", $this->Util);



        $this->set("societyObj", $this->SocietyBill);



        $this->loadModel('User');



        $this->loadModel('Member');



        $this->loadModel('Wing');



        $this->loadModel('Tenant');



        $this->loadModel('Society');



        $this->loadModel('MemberPayment');



        $this->loadModel('CashWithdraw');



        $this->loadModel('SocietyPayment');



        $this->loadModel('MemberBillSummary');



        $this->loadModel('SocietyParameter');



        $this->loadModel('MemberBillGenerate');



        $this->loadModel('SocietyOtherIncome');



        $this->loadModel('SocietyLedgerHeads');



        $this->loadModel('SocietyHeadSubCategory');



        $this->loadModel('AccountHead');



        $this->loadModel('JournalVoucher');



        $this->loadModel('MemberBillSummarySettlement');



        $this->loadModel('MemberBillSettlement');



        $this->loadAllNavigationModals();



    }







    function loadAllNavigationModals() {



        $this->SocietyBill->getSocietyBuildingLists();



        $this->SocietyBill->getSocietyLedgerHeadsDetails();



        $this->SocietyBill->navigateSocietyMemberDetails();



        $this->SocietyBill->loadAllNavigationModalsDropdowns();



        if ($this->societyloginID) {



            $billingFrequencyData = $this->Society->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $this->societyloginID)));



            if (isset($billingFrequencyData) && count($billingFrequencyData) > 0) {



                $this->societyBillingFrequencyId = $billingFrequencyData['SocietyParameter']['billing_frequency_id'];



            }



        }



    }

    
    public function update_bill_date(){
        $financialYearId = $this->Util->getFinancialYear();
        $societyLoginID = $this->societyloginID;
        $options['AND'] = array('MemberBillSummary.society_id' => $societyLoginID);
        $options['AND'] = array('MemberBillSummary.financial_year_id' => $financialYearId);
        $societyMemberbillData = $this->MemberBillSummary->find('all', array('conditions' => $options, 'fields' => array('MemberBillSummary.month', 'MemberBillSummary.member_id','MemberBillSummary.bill_frequency_id'),'order' => array('MemberBillSummary.bill_no')));
        $societyMemberbillDataWithMonthName = [];
        $billingFrequenciesArray = $this->billFrequencyMonthDetails();
        if(!empty($societyMemberbillData)){
            $billMonthId = 0;
            $i = 0;
            foreach($societyMemberbillData as $index => $billData){
                $billFreqId = $billData['MemberBillSummary']['bill_frequency_id'];
                $billMonth = $billData['MemberBillSummary']['month'];
                if($billMonthId != $billMonth) {
                    $societyMemberbillDataWithMonthName[$i] = [
                        'bill_month_id' => $billMonth,
                        'bill_month' => $billingFrequenciesArray[$billFreqId][$billMonth],
                        'bill_frequency_id'=>$billFreqId,
                        ];
                    $i++;
                }
                $billMonthId = $billMonth;
            }
        }
         
        if ($this->request->is(array('post'))) {
            $postData = $this->request->data;
            if(isset($postData['billMonthData']['new_bill_date']) || isset($postData['billMonthData']['new_due_date'])){
                $newBillDate = $postData['billMonthData']['new_bill_date'];
                $newBillDueDate = $postData['billMonthData']['new_due_date'];
                $existingBillDate = $postData['billMonthData']['existing_bill_date'];
                $existingBillDueDate = $postData['billMonthData']['existing_bill_due_date'];
                $billMonthNoWithFreqId = $this->request->data['billMonthData']['bill_month'];
                if(!empty($billMonthNoWithFreqId)){
                    $exploadedBillMonthNoWithFreqId = explode('-',$billMonthNoWithFreqId);
                    $monthId = $exploadedBillMonthNoWithFreqId[0];
                    $billFreqId = $exploadedBillMonthNoWithFreqId[1];
                    $aData = array('MemberBillSummary.society_id' => $societyLoginID,'MemberBillSummary.bill_frequency_id' => $billFreqId,
                    'MemberBillSummary.month' => $monthId,'MemberBillSummary.financial_year_id' => $financialYearId);            //$this->MemberBillSummary->save($aData)
                    
                    $aDataToUpdate['MemberBillSummary.bill_due_date'] =  !empty($newBillDueDate) ? "'$newBillDueDate'" : "'$existingBillDueDate'";
                    $aDataToUpdate['MemberBillSummary.bill_generated_date'] = !empty($newBillDate) ? "'$newBillDate'" : "'$existingBillDate'";
                    
                    
                    if($this->MemberBillSummary->updateAll($aDataToUpdate,$aData)){
                        $this->Session->setFlash(__('Bill date has been updated successfully.'),'success');    
                    }
                    else{
                        $this->Session->setFlash(__('Failed to update bill dates.'),'error');    
                    }
                }
            }
        }
        
        $this->set(compact('societyMemberbillDataWithMonthName'));
    }
    

    
    public function billFrequencyMonthDetails(){
	//1-Monthly, 2-BiMonthly, 3-Quarterly, 4-Quadruple,5-Half Yearly,6-Yearly
      $billingFrequenciesArray = array(
    	1=>array("4" => "April","5" => "May", "6" => "June", "7" => "July", "8" => "August","9" => "September", "10" => "October", "11" => "November", "12" => "December","1" => "January", "2" => "February", "3" => "March"),
    	2=>array("4" => "Apr-May","6" => "Jun-Jul", "8" => "Aug-Sep", "10" => "Oct-Nov", "12" => "Dec-Jan","2" => "Feb-Mar"),
    	3=>array("4" => "Apr-May-Jun","7" => "Jul-Aug-Sep", "10" => "Oct-Nov-Dec", "1" => "Jan-Feb-Mar"),
    	4=>array(),
    	5=>array("4" => "Apr-Sep","10" => "Oct-Mar"),
    	6=>array("4" => "April-March"),	
      );          
      
      return $billingFrequenciesArray;
    }
    
}

?>