<?php







/*



 * To change this license header, choose License Headers in Project Properties.



 * To change this template file, choose Tools | Templates



 * and open the template in the editor.



 */







class SocietyBillsController extends AppController {
    public $components = array('Session', 'RequestHandler', 'Util', 'SocietyBill');
    public $helpers = array('Js');
    public $societyloginID = '';
    public $fincancialYearDates = [];
    public $financialYearId = '';
    public $memberClosingbalance = [];
    public $appliedParmaOnBill = [];
    public $memberTransferInCurrentYear = false;


    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'society_dashboard';
        $this->set("utilObj", $this->Util);
        $this->set("societyObj", $this->SocietyBill);
        $this->loadModel('Member');
        $this->loadModel('MemberPayment');
        $this->loadModel('MemberBillGenerate');
        $this->loadModel('Society');
        $this->loadModel('MemberBillSummary');
        $this->loadModel('MemberBillSummarySettlement');
        $this->loadModel('MemberBillSettlement');
        $this->loadModel('SocietyLedgerHeads');
        $this->loadModel('SocietyParameter');
        $this->loadModel('SocietyTariffOrder');

        $this->loadAllNavigationModals();
        App::import('Vendor', 'dompdf', array('file' => 'dompdf' . DS . 'dompdf_config.inc.php'));
        
        $this->societyloginID = $this->Session->read('Auth.User.id');
        // $this->financialYearId = $this->Session->read('Auth.year_id');
        $this->financialYearId = $this->Util->getFinancialYear();
                        
    }







    function loadAllNavigationModals(){



        $this->SocietyBill->getSocietyBuildingLists();



        $this->SocietyBill->getSocietyLedgerHeadsDetails();



        $this->SocietyBill->navigateSocietyMemberDetails();



        $this->SocietyBill->loadAllNavigationModalsDropdowns();



    }







    public function memberBillGenerate() {
        ini_set('max_execution_time', 2700);

        $this->layout = false;
        $response = array();
        $response['error'] = 1;
        $response['error_message'] = 'Member Tariff has not been set.';
        $this->loadModel('YearWiseClosingBalance');
        $societyId = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Util->getFinancialYear();

        if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            $this->Member->Behaviors->attach('Containable');
     
            if (isset($this->request->data['MemberBillGenerate']['bill_generated_date']) && !empty($this->request->data['MemberBillGenerate']['bill_generated_date'])) {
                
                $societyWithMemberTransfer = 0;
                #commendted to get all the member related data rather than just the Member Id
                //$societyMemberLists = $this->Member->find('all', array('fields' => array('Member.id', 'Member.building_id', 'Member.wing_id', 'Member.unit_type', 'Member.area', 'Member.flat_no', 'Member.op_principal','Member.member_transfer', 'Member.op_interest', 'Member.op_tax', 'Member.op_bill_due_date', 'Member.op_bill_date'), 'conditions' => array('Member.society_id' => $societyId, 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'asc'), 'recursive' => -1));

                # get all the details including the tariff details ( type - unit_no )
                $societyMemberLists = $this->Member->find('all', array('conditions' => array('Member.society_id' => $societyId, 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'asc'),'contain'=>array('MemberTariff')));

                $billLedgerHeadSettings = $this->memberRegularBillLedgerHeadSettings(); //check if tax is applicable

                //Based on society Parameters get tariffs for member
                $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('conditions'=>array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id'))));

                $multiplyBillingValue = $this->SocietyBill->multiplyTariffAmountByBillingFrequency($societyTariffParameterCheck);


                $billEndDate = $this->getBillEndDate($societyTariffParameterCheck,$this->request->data['MemberBillGenerate']['bill_generated_date']);
 
                $memberListWithTransferStatus = $this->SocietyBill->memberTransferList($societyId);

                if (!empty($memberListWithTransferStatus)){
                        $societyWithMemberTransfer = 1;
                }
                $this->memberClosingbalance = $this->SocietyBill->getMemberslastClosingBalance();


                if (count($societyMemberLists) > 0) {
                    foreach ($societyMemberLists as $details) {
                        $memberDetails = $details['Member'];                        
                        $memberId = $memberDetails['id'];
 
                         $member_transfer  = $memberDetails['member_transfer'];
                        if (!empty($memberListWithTransferStatus)){
                               
                        
                                $memberTransferData = isset($memberListWithTransferStatus[$memberId]) ? $memberListWithTransferStatus[$memberId] : [];

                                $transferInCurrentyear = (!empty($memberTransferData)) ? true : false;         
                        }   

                        #commented the code which is being executed for every member and got the details above with the member details only
                       // $memberTariffInfo = $this->getMemberTariffFromDiffrentTariffTypes($societyTariffParameterCheck, $memberDetails);

      
                        $billNo = $this->SocietyBill->memberBillsUniqueNumber($this->request->data['MemberBillGenerate']['bill_starting_no']);

                        if (isset($details['MemberTariff']) && !empty($details['MemberTariff'])) {
                        //  Monthly Billing Frequency:
                            
                           $responseArray = $this->calculateMembersRegularBillByTariff($memberId, $details, $billNo, $this->request->data, $billLedgerHeadSettings, $memberDetails,$member_transfer,$transferInCurrentyear,$societyTariffParameterCheck,$societyId,$financialYearId,$multiplyBillingValue,$billEndDate);
                        
                            if (!empty($responseArray)) {
                                $response = $responseArray;
                            }
                        }
                       // echo $memberId;
                      //  print_r($responseArray);
                       // die;
                    }

                    if(!empty($this->appliedParmaOnBill)){
                        $this->loadModel('MemberBillAppliedParameter');
                        $this->MemberBillAppliedParameter->saveAll($this->appliedParmaOnBill);
                    }
                }
            }
        }

        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));
    }
    
    public function memberBillGenerate_test() {
        ini_set('max_execution_time', 2700);

        $this->layout = false;
        $response = array();
        $response['error'] = 1;
        $response['error_message'] = 'Member Tariff has not been set.';
        $this->loadModel('YearWiseClosingBalance');
        $societyId = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Util->getFinancialYear();

        if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            $this->Member->Behaviors->attach('Containable');
     
            if (isset($this->request->data['MemberBillGenerate']['bill_generated_date']) && !empty($this->request->data['MemberBillGenerate']['bill_generated_date'])) {
                
                $societyWithMemberTransfer = 0;
                #commendted to get all the member related data rather than just the Member Id
                //$societyMemberLists = $this->Member->find('all', array('fields' => array('Member.id', 'Member.building_id', 'Member.wing_id', 'Member.unit_type', 'Member.area', 'Member.flat_no', 'Member.op_principal','Member.member_transfer', 'Member.op_interest', 'Member.op_tax', 'Member.op_bill_due_date', 'Member.op_bill_date'), 'conditions' => array('Member.society_id' => $societyId, 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'asc'), 'recursive' => -1));

                # get all the details including the tariff details ( type - unit_no )
                $societyMemberLists = $this->Member->find('all', array('conditions' => array('Member.society_id' => $societyId, 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'asc'),'contain'=>array('MemberTariff')));

                $billLedgerHeadSettings = $this->memberRegularBillLedgerHeadSettings(); //check if tax is applicable

                //Based on society Parameters get tariffs for member
                $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('conditions'=>array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id'))));

                $multiplyBillingValue = $this->SocietyBill->multiplyTariffAmountByBillingFrequency($societyTariffParameterCheck);


                $billEndDate = $this->getBillEndDate($societyTariffParameterCheck,$this->request->data['MemberBillGenerate']['bill_generated_date']);
 
                $memberListWithTransferStatus = $this->SocietyBill->memberTransferList($societyId);

                if (!empty($memberListWithTransferStatus)){
                        $societyWithMemberTransfer = 1;
                }
                $this->memberClosingbalance = $this->SocietyBill->getMemberslastClosingBalance();


                if (count($societyMemberLists) > 0) {
                    foreach ($societyMemberLists as $details) {
                        $memberDetails = $details['Member'];                        
                        $memberId = $memberDetails['id'];
 
                         $member_transfer  = $memberDetails['member_transfer'];
                        if (!empty($memberListWithTransferStatus)){
                               
                        
                                $memberTransferData = isset($memberListWithTransferStatus[$memberId]) ? $memberListWithTransferStatus[$memberId] : [];

                                $transferInCurrentyear = (!empty($memberTransferData)) ? true : false;         
                        }   

                        #commented the code which is being executed for every member and got the details above with the member details only
                       // $memberTariffInfo = $this->getMemberTariffFromDiffrentTariffTypes($societyTariffParameterCheck, $memberDetails);

      
                        $billNo = $this->SocietyBill->memberBillsUniqueNumber($this->request->data['MemberBillGenerate']['bill_starting_no']);

                        if (isset($details['MemberTariff']) && !empty($details['MemberTariff'])) {
                        //  Monthly Billing Frequency:
                            
                           $responseArray = $this->calculateMembersRegularBillByTariff($memberId, $details, $billNo, $this->request->data, $billLedgerHeadSettings, $memberDetails,$member_transfer,$transferInCurrentyear,$societyTariffParameterCheck,$societyId,$financialYearId,$multiplyBillingValue,$billEndDate);
                        
                            if (!empty($responseArray)) {
                                $response = $responseArray;
                            }
                        }
                       // echo $memberId;
                      //  print_r($responseArray);
                       // die;
                    }

                    if(!empty($this->appliedParmaOnBill)){
                        $this->loadModel('MemberBillAppliedParameter');
                        $this->MemberBillAppliedParameter->saveAll($this->appliedParmaOnBill);
                    }
                }
            }
        }

        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));
    }

    public function memberBillGenerate_bk() {
          ini_set('max_execution_time', 1800);

        $this->layout = false;
        $response = array();
        $response['error'] = 1;
        $response['error_message'] = 'Member Tariff has not been set.';
        $this->loadModel('YearWiseClosingBalance');
        $societyId = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Util->getFinancialYear();

        if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            $this->Member->Behaviors->attach('Containable');
     
            if (isset($this->request->data['MemberBillGenerate']['bill_generated_date']) && !empty($this->request->data['MemberBillGenerate']['bill_generated_date'])) {
                $societyMemberLists = $this->Member->find('all', array('fields' => array('Member.id', 'Member.building_id', 'Member.wing_id', 'Member.unit_type', 'Member.area', 'Member.flat_no', 'Member.op_principal','Member.member_transfer', 'Member.op_interest', 'Member.op_tax', 'Member.op_bill_due_date', 'Member.op_bill_date'), 'conditions' => array('Member.society_id' => $societyId, 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'asc'), 'recursive' => -1));

                $billLedgerHeadSettings = $this->memberRegularBillLedgerHeadSettings(); //check if tax is applicable


                //Based on society Parameters get tariffs for member
                $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('conditions'=>array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id'))));
     
                # get transfer list
                $memberListWithTransferStatus = $this->SocietyBill->memberTransferList($societyId);
                
                #get closing balance
                $this->memberClosingbalance = $this->SocietyBill->getMemberslastClosingBalance();


                if (count($societyMemberLists) > 0) {
                    foreach ($societyMemberLists as $details) {
                        $memberDetails = $details['Member'];                        
                        $memberId = $memberDetails['id'];
                        $member_transfer  = $memberDetails['member_transfer'];
                        
                        $memberTransferData = isset($memberListWithTransferStatus[$memberId]) ? $memberListWithTransferStatus[$memberId] : [];

                        $transferInCurrentyear = (!empty($memberTransferData)) ? true : false;                        
                        $memberTariffInfo = $this->getMemberTariffFromDiffrentTariffTypes($societyTariffParameterCheck, $memberDetails);

                        print_r(memberTariffInfo);

                        $billNo = $this->SocietyBill->memberBillsUniqueNumber($this->request->data['MemberBillGenerate']['bill_starting_no']);

                        if (isset($memberTariffInfo['MemberTariff']) && !empty($memberTariffInfo['MemberTariff'])) {
                        //  Monthly Billing Frequency:
                           $responseArray = $this->calculateMembersRegularBillByTariff($memberId, $memberTariffInfo, $billNo, $this->request->data, $billLedgerHeadSettings, $memberDetails,$member_transfer,$transferInCurrentyear);
                        
                            if (!empty($responseArray)) {
                                $response = $responseArray;
                            }
                        }
                    }

                    if(!empty($this->appliedParmaOnBill)){
                        $this->loadModel('MemberBillAppliedParameter');
                        $this->MemberBillAppliedParameter->saveAll($this->appliedParmaOnBill);
                    }
                }
            }
        }

        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));
    }



    public function getMemberTariffFromDiffrentTariffTypes($societyTariffParameterCheck, $memberDetails) {
        switch ($societyTariffParameterCheck['SocietyParameter']['tariff_id']) {



            case Configure::read('TariffType.Building') :



                $this->loadModel('SocietyTariffBuildings');



            case Configure::read('TariffType.Wing') :



                $this->loadModel('SocietyTariffWings');



            case Configure::read('TariffType.UnitType') :



                $this->loadModel('SocietyTariffUnitTypes');



            case Configure::read('TariffType.TotalArea') :



                $this->loadModel('SocietyTariffTotalAreas');



            case Configure::read('TariffType.PerUnitArea') :



                $this->loadModel('SocietyTariffPerAreas');



                break;



        }



        $memberId = $memberDetails['id'];



        $memberTariffInfo = array();



        $memberTariffInfo['Member']['flat_no'] = $memberDetails['flat_no'];



        if ($societyTariffParameterCheck['SocietyParameter']['tariff_id'] == Configure::read('TariffType.Building')) {



            //Building

            $buildingTariffInfo = $this->SocietyTariffBuildings->find('all', array('conditions' => array('SocietyTariffBuildings.society_id' => $this->Session->read('Auth.User.id'), 'SocietyTariffBuildings.building_id' => $memberDetails['building_id'])));



            if (isset($buildingTariffInfo) && !empty($buildingTariffInfo)) {



                foreach ($buildingTariffInfo as $tariffDetails) {



                    $memberTariffInfo['MemberTariff'][] = array('society_id' => $tariffDetails['SocietyTariffBuildings']['society_id'],



                        'member_id' => $memberId,



                        'ledger_head_id' => $tariffDetails['SocietyTariffBuildings']['ledger_head_id'],



                        'amount' => $tariffDetails['SocietyTariffBuildings']['amount'],



                        'tariff_serial' => $tariffDetails['SocietyTariffBuildings']['tariff_serial'],);



                }



            }



        } else if ($societyTariffParameterCheck['SocietyParameter']['tariff_id'] == Configure::read('TariffType.Wing')) {


            //Wing



            $wingTariffInfo = $this->SocietyTariffWings->find('all', array('conditions' => array('SocietyTariffWings.society_id' => $this->Session->read('Auth.User.id'), 'SocietyTariffWings.building_id' => $memberDetails['building_id'], 'SocietyTariffWings.wing_id' => $memberDetails['wing_id'])));



            if (isset($wingTariffInfo) && !empty($wingTariffInfo)) {



                foreach ($wingTariffInfo as $tariffDetails) {



                    $memberTariffInfo['MemberTariff'][] = array('society_id' => $tariffDetails['SocietyTariffWings']['society_id'],



                        'member_id' => $memberId,



                        'ledger_head_id' => $tariffDetails['SocietyTariffWings']['ledger_head_id'],



                        'amount' => $tariffDetails['SocietyTariffWings']['amount'],



                        'tariff_serial' => $tariffDetails['SocietyTariffWings']['tariff_serial'],);



                }



            }



        } else if ($societyTariffParameterCheck['SocietyParameter']['tariff_id'] == Configure::read('TariffType.UnitNo') || $societyTariffParameterCheck['SocietyParameter']['tariff_id'] == Configure::read('TariffType.PerPerson')) {



            //Per Person, Unit No



            $this->Member->Behaviors->attach('Containable');


           $memberTariffInfo = $this->Member->find('first', array('conditions' => array('Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.id' => $memberDetails['id']),'contain'=>array('MemberTariff'))); 


        //    $memberTariffInfo = $this->Member->find('all', array('conditions' => array('Member.society_id' => $this->Session->read('Auth.User.id'))));   

            // $memberTariffInfo = $this->Member->find('first', array('conditions' => array('Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.id' => $memberDetails['id']), 'contain' => array('MemberTariff' => array('conditions' => array('MemberTariff.amount >' => 0)))));



        } else if ($societyTariffParameterCheck['SocietyParameter']['tariff_id'] == Configure::read('TariffType.UnitType')) {


            //Unit Type



            $unitType = '';



            if ($memberDetails['unit_type'] == 'Residencial' || $memberDetails['unit_type'] == 'R' || $memberDetails['unit_type'] == 1) {



                $unitType = 'R';



            } else if ($memberDetails['unit_type'] == 'Commercial' || $memberDetails['unit_type'] == 'C' || $memberDetails['unit_type'] == 2) {



                $unitType = 'C';



            } else if ($memberDetails['unit_type'] == 'Both' || $memberDetails['unit_type'] == 'B' || $memberDetails['unit_type'] == 3) {



                $unitType = 'B';



            }



            $unitTypeTariffInfo = $this->SocietyTariffUnitTypes->find('all', array('conditions' => array('SocietyTariffUnitTypes.society_id' => $this->Session->read('Auth.User.id'), 'SocietyTariffUnitTypes.unit_type' => "$unitType")));



            if (!empty($unitTypeTariffInfo)) {



                foreach ($unitTypeTariffInfo as $tariffDetails) {



                    $memberTariffInfo['MemberTariff'][] = array('society_id' => $tariffDetails['SocietyTariffUnitTypes']['society_id'],



                        'member_id' => $memberId,



                        'ledger_head_id' => $tariffDetails['SocietyTariffUnitTypes']['ledger_head_id'],



                        'amount' => $tariffDetails['SocietyTariffUnitTypes']['amount'],



                        'tariff_serial' => $tariffDetails['SocietyTariffUnitTypes']['tariff_serial'],);



                }



            }



        } else if ($societyTariffParameterCheck['SocietyParameter']['tariff_id'] == Configure::read('TariffType.TotalArea')) {



            //Total Area



            $totalAreaTariffInfo = $this->SocietyTariffTotalAreas->find('all', array('conditions' => array('SocietyTariffTotalAreas.society_id' => $this->Session->read('Auth.User.id'))));



            if (!empty($totalAreaTariffInfo)) {



                foreach ($totalAreaTariffInfo as $tariffDetails) {



                    $memberTariffInfo['MemberTariff'][] = array('society_id' => $tariffDetails['SocietyTariffTotalAreas']['society_id'],



                        'member_id' => $memberId,



                        'ledger_head_id' => $tariffDetails['SocietyTariffTotalAreas']['ledger_head_id'],



                        'amount' => $tariffDetails['SocietyTariffTotalAreas']['amount'],



                        'tariff_serial' => $tariffDetails['SocietyTariffTotalAreas']['tariff_serial'],);



                }



            }



        } else if ($societyTariffParameterCheck['SocietyParameter']['tariff_id'] == Configure::read('TariffType.PerUnitArea')) {


            //Per Area



            $totalAreaTariffInfo = $this->SocietyTariffPerAreas->find('all', array('conditions' => array('SocietyTariffPerAreas.society_id' => $this->Session->read('Auth.User.id'))));



            if (!empty($totalAreaTariffInfo)) {



                foreach ($totalAreaTariffInfo as $tariffDetails) {



                    $memberTariffInfo['MemberTariff'][] = array('society_id' => $tariffDetails['SocietyTariffPerAreas']['society_id'],



                        'member_id' => $memberId,



                        'ledger_head_id' => $tariffDetails['SocietyTariffPerAreas']['ledger_head_id'],



                        'amount' => ($tariffDetails['SocietyTariffPerAreas']['amount'] * $memberDetails['area']),



                        'tariff_serial' => $tariffDetails['SocietyTariffPerAreas']['tariff_serial'],);



                }



            }



        }



        return $memberTariffInfo;



    }







    public function societyMemberBillExists($societyMemberId, $billGeneratedMonths, $billType = '') {
        $financialYearId = $this->Util->getFinancialYear();
        return  $this->MemberBillGenerate->find('first', array('conditions' => array('MemberBillGenerate.member_id' => $societyMemberId, 'MemberBillGenerate.month' => $billGeneratedMonths, 'MemberBillGenerate.bill_type' => $billType,'MemberBillGenerate.financial_year_id'=> $financialYearId)));
    }


    public function getTotalMemberTarrif($memberId,$billMonthNo){
        $amount = 0;
        $conditions = array('MemberBillGenerate.member_id'=> $memberId,'MemberBillGenerate.month'=> $billMonthNo,'SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.is_tax_applicable' => 1);
        $data = $this->MemberBillGenerate->find('all',array(
            'joins' => array(
                array(
                    'table' => 'society_ledger_heads',
                    'alias' => 'slds',
                    'type' => 'INNER',
                    'conditions' => array(
                        'MemberBillGenerate.ledger_head_id = slds.id',
                        )
                    )
                ),
                'conditions' => $conditions,
                'fields'=> array('sum(MemberBillGenerate.amount) as amount_paid')
               
            ));
            

            if(!empty($data)) $amount = $data[0][0]['amount_paid'];
            
       return $amount;
    }

 public function calculateMembersRegularBillByTariff($memberId, $memberTariffInfo, $paymentBillNo, $postData, $billLedgerHeadSettings, $memberDetails,$member_transfer,$transferInCurrentyear,$societyTariffParameterCheck='',$societyId=0,$financialYearId=0, $multiplyTariffAmountValue=1,$billEndDate='') {
        /*$financialYearId = $this->Util->getFinancialYear();
        $societyId = $this->Session->read('Auth.User.id');
        $this->loadModel('JournalVoucher');
*/
        $appliedParams = [];
  
        $tariffCounter = 0;
        $billGenerateArr = array();
        $responseError = array();
        $billType = $appliedParams['bill_type'] = Configure::read('BillType.Regular');
        $appliedParams['member_id'] = $memberId;
        $appliedParams['society_id'] = $societyId;
        
        //$societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $societyId)));

        //$multiplyTariffAmountValue = $this->SocietyBill->multiplyTariffAmountByBillingFrequency();

        $billGeneratedMonths = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : date('m');


        $societyMemberBillExists = $this->societyMemberBillExists($memberId, $billGeneratedMonths, $billType); //check same bill allready exists in system 
       // print_r ( $societyMemberBillExists);die;
        $memberData = [
            'member_id' => $memberId,
            'society_id' =>  $societyId
            ];
        if (empty($societyMemberBillExists)) {

            $lastMonthBillDetails = [];
            #combine with the max bill Id
            #commented for interest on due amount
            #$memberAllBills = $this->MemberBillSummary->find('all', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType,'MemberBillSummary.member_transfer'=>$member_transfer,'MemberBillSummary.financial_year_id' => $financialYearId ),'order'=>array('MemberBillSummary.id'=>'asc'),'recursive' => -1));
            
            $memberAllBills = $this->MemberBillSummary->find('all', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType,'MemberBillSummary.member_transfer'=>$member_transfer),'order'=>array('MemberBillSummary.id'=>'asc'),'recursive' => -1));

           
            if(!empty($memberAllBills)){
                $lastIndex = count($memberAllBills)-1;
                $lastMonthBillDetails =  $memberAllBills[$lastIndex];
            }
            
            $jvAdjustment = 0;
            /* jv code commented by Shobha
            if (isset($lastMonthBillDetails) && !empty($lastMonthBillDetails)) {
                # comebine into one

                $journalVoucherCreditData = $this->JournalVoucher->find('all', array('conditions' => array('JournalVoucher.financial_year_id' => $financialYearId,'JournalVoucher.society_id' => $this->societyloginID,'JournalVoucher.voucher_date > '=> $lastMonthBillDetails['MemberBillSummary']['bill_generated_date'],'JournalVoucher.voucher_date <= '=> $lastMonthBillDetails['MemberBillSummary']['bill_due_date'],'JournalVoucher.jv_credit_member_head_id' => $memberId
                )));

                 $journalVoucherDebitData = $this->JournalVoucher->find('all', array('conditions' => array('JournalVoucher.financial_year_id' => $financialYearId,'JournalVoucher.society_id' => $this->societyloginID,'JournalVoucher.voucher_date > '=> $lastMonthBillDetails['MemberBillSummary']['bill_generated_date'],'JournalVoucher.voucher_date <= '=> $lastMonthBillDetails['MemberBillSummary']['bill_due_date'],'JournalVoucher.jv_credit_member_head_id' => $memberId
                )));
            
                foreach ($journalVoucherCreditData  as  $jVCreditData){
                    if (isset($jVCreditData['JournalVoucher']['jv_credit_member_head_id']) && !empty($jVCreditData['JournalVoucher']['jv_credit_member_head_id'])) {
                        $jvAdjustment = $jvAdjustment - $jVCreditData['JournalVoucher']['jv_amount_credited'];
                    }
                }
                   
                foreach ($journalVoucherDebitData  as  $jVDebitData){
                    if (isset($jVDebitData['JournalVoucher']['jv_debit_member_head_id']) && !empty($jVDebitData['JournalVoucher']['jv_debit_member_head_id'])) {
                        $jvAdjustment = $jvAdjustment + $jVDebitData['JournalVoucher']['jv_amount_debited'];
                    }
                }
            }*/

            $interestTypeId =  $appliedParams['interest_type_id'] = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];
            $appliedParams['method_id'] = $societyTariffParameterCheck['SocietyParameter']['method_id'];

            if (isset($lastMonthBillDetails) && !empty($lastMonthBillDetails)) {
                
                $lastMonthBillDetails = $lastMonthBillDetails['MemberBillSummary'];                
                $balanceAmount = $lastMonthBillDetails['balance_amount'];                
                $memberBillSummaryData['op_principal_arrears_original'] = $lastMonthBillDetails['principal_balance'];
                $memberBillSummaryData['jv_adjustment'] = $jvAdjustment;
                
                $memberBillSummaryData['op_principal_arrears'] = $memberBillSummaryData['op_principal_arrears_original'] + $jvAdjustment;
                $opDueAmount = $memberBillSummaryData['op_due_amount'] = $lastMonthBillDetails['balance_amount']+$jvAdjustment;

                $memberBillSummaryData['op_interest_arrears'] = $lastMonthBillDetails['interest_balance'];
                $memberBillSummaryData['op_tax_arrears'] =  $lastMonthBillDetails['tax_balance'];
                $memberBillSummaryData['tax_balance'] = $lastMonthBillDetails['tax_balance'];
                
                $memberBillSummaryData['interest_on_due_amount'] = 0;

                
                // only calculate intereset if it not in advance payment 
                        
                    if($opDueAmount > 0 &&  $interestTypeId != 4){ //  4 - manually 
                        $memberBillSummaryData['interest_on_due_amount'] = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData,$memberData,$memberAllBills,$billType,$member_transfer);
                    }
               }
               else {
                if (isset($memberDetails) && !empty($memberDetails)) {

                    if($transferInCurrentyear){  // if member is transfered in smae year
                        $memberDetails['op_principal'] = 0;
                        $memberDetails['op_tax'] = 0;
                        $memberDetails['op_interest'] = 0;
                    }
                    else{                        
                        if(!empty($this->memberClosingbalance[$memberId])){ // if member have last closing balace
                                $previoutClosingBal  = $this->memberClosingbalance[$memberId];
                                if( $member_transfer == $previoutClosingBal['member_transfer']){ 
                                    $memberDetails['op_principal'] = $previoutClosingBal['principal_balance'];
                                    $memberDetails['op_tax'] = $previoutClosingBal['tax_balance'];
                                    $memberDetails['op_interest'] = $previoutClosingBal['interest_balance'];                                    
                                }                                
                        }

                        // otherwise take members first time entered opening amount  
                    }                    

                    $lastMonthBillDetails['principal_balance'] = isset($memberDetails['op_principal']) ? $memberDetails['op_principal'] : 0.00;
                    $lastMonthBillDetails['balance_amount'] = $memberDetails['op_principal'] + $memberDetails['op_interest'] + $memberDetails['op_tax'];
                    $lastMonthBillDetails['bill_due_date'] = isset($memberDetails['op_bill_due_date']) ? $memberDetails['op_bill_due_date'] : '';
                    $lastMonthBillDetails['bill_generated_date'] = isset($memberDetails['op_bill_date']) ? $memberDetails['op_bill_date'] : '';
                    $lastMonthBillDetails['interest_free_amount'] = 0;
                    
                    $memberBillSummaryData['op_principal_arrears_original'] = $memberBillSummaryData['op_principal_arrears'] = isset($memberDetails['op_principal']) ? $memberDetails['op_principal'] : 0.00;
                    $memberBillSummaryData['jv_adjustment'] = $jvAdjustment;
                    $memberBillSummaryData['op_principal_arrears'] = $memberBillSummaryData['op_principal_arrears_original'] + $jvAdjustment;
                    $memberBillSummaryData['op_interest_arrears'] = isset($memberDetails['op_interest']) ? $memberDetails['op_interest'] : 0.00;
                    $memberBillSummaryData['op_tax_arrears'] = isset($memberDetails['op_tax']) ? $memberDetails['op_tax'] : 0.00;
                    $memberBillSummaryData['op_due_amount'] = $memberDetails['op_principal'] + $memberDetails['op_interest'] + $memberDetails['op_tax'];
                    $memberBillSummaryData['interest_on_due_amount'] = 0;
                    
                    $balaceAmount = $memberBillSummaryData['op_due_amount'];
                    $memberBillSummaryData['tax_balance'] =$memberBillSummaryData['op_tax_arrears']; 
                    
                    if (isset($lastMonthBillDetails['bill_due_date']) && $lastMonthBillDetails['bill_due_date'] != '0000-00-00' && $memberBillSummaryData['op_due_amount'] > 0) {
                        if($interestTypeId != 4){ //  4 - manually   
                            $principalAmt = !empty($memberDetails['op_principal']) ?  $memberDetails['op_principal'] : 0;
                            $intererstAmt = !empty($memberDetails['op_interest']) ?  $memberDetails['op_interest'] : 0;
                            $taxAmount = !empty($memberDetails['op_tax']) ?  $memberDetails['op_tax'] : 0;
                            $billDueDate = $lastBillGeneratedDate = $lastMonthBillDetails['bill_generated_date'];
                            $billGeneratedDate = $this->request->data['MemberBillGenerate']['bill_generated_date'];
                            $paymentDate = $lastMonthBillDetails['bill_due_date'];
                            
                            $memberBillSummaryData['interest_on_due_amount'] = $this->getInterestOnDueAmountOnFirstBill($principalAmt,$intererstAmt,$taxAmount,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                        }
                    }

                } else {
                    $memberBillSummaryData['op_principal_arrears'] = 0;
                    $memberBillSummaryData['op_interest_arrears'] = 0;
                    $memberBillSummaryData['op_due_amount'] = 0;
                    $memberBillSummaryData['interest_on_due_amount'] = 0;
                    $memberBillSummaryData['op_tax_arrears'] = 0;
                    $memberBillSummaryData['tax_balance'] = 0;
                    $memberBillSummaryData['tax_total'] = 0;
                }
            }

            $appliedParams['bill_no'] = $paymentBillNo;

            $memberBillSummaryData['bill_no'] = $paymentBillNo;
            $memberBillSummaryData['month'] = $billGeneratedMonths;
            $memberBillSummaryData['member_id'] = $memberId;
            $memberBillSummaryData['bill_type'] = $billType;
            $memberBillSummaryData['society_id'] = $this->societyloginID;
            $memberBillSummaryData['flat_no'] = isset($memberTariffInfo['Member']['flat_no']) ? $memberTariffInfo['Member']['flat_no'] : '';
            $memberBillSummaryData['monthly_amount'] = 0; //monthly member maintenance value
            $memberBillSummaryData['monthly_principal_amount'] = 0; //monthly_amount - discount;
            $memberBillSummaryData['discount'] = 0;
            $memberBillSummaryData['monthly_bill_amount'] = 0; //monthly_principal_amount + interest_on_due_amount;
            $memberBillSummaryData['amount_payable'] = 0; //op_due_amount + monthly_bill_amount;
            $memberBillSummaryData['principal_paid'] = 0;
            $memberBillSummaryData['interest_paid'] = 0;
            $memberBillSummaryData['principal_adjusted'] = 0;

            $memberBillSummaryData['interest_adjusted'] = 0;
            $memberBillSummaryData['principal_balance'] = 0; //op_principal_arrears + monthly_principal_amount - principal_paid;
            $memberBillSummaryData['interest_balance'] = 0; //op_interest_arrears + interest_on_due_amount - interest_paid;
            $memberBillSummaryData['balance_amount'] = 0; //principal_balance + interest_balance;
            $memberBillSummaryData['interest_adjusted'] = 0;
            $memberBillSummaryData['igst_total'] = 0;
            $memberBillSummaryData['cgst_total'] = 0;
            $memberBillSummaryData['sgst_total'] = 0;
            $memberBillSummaryData['tax_total'] = 0;
            $memberBillSummaryData['tax_paid'] = 0;
            $memberBillSummaryData['tax_adjusted'] = 0;
            $memberBillSummaryData['interest_free_amount'] = 0;
            $memberBillSummaryData['bill_tariff_type'] = $societyTariffParameterCheck['SocietyParameter']['tariff_id'];

            $memberBillSummaryData['bill_generated_date'] = $billGeneretedDate = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : '';
            $memberBillSummaryData['bill_due_date'] = (isset($postData['MemberBillGenerate']['bill_due_date']) && !empty($postData['MemberBillGenerate']['bill_due_date'])) ? trim($postData['MemberBillGenerate']['bill_due_date']) : NULL;
            $memberBillSummaryData['cdate'] = $this->Util->getDateTime();
            $memberBillSummaryData['udate'] = $this->Util->getDateTime();

            $memberBillSummaryData['bill_frequency_id'] = $appliedParams['bill_frequency_id'] = $societyTariffParameterCheck['SocietyParameter']['billing_frequency_id'];
            $memberBillSummaryData['bill_end_date'] = $billEndDate;//$this->getBillEndDate($societyTariffParameterCheck,$billGeneretedDate);
            $memberBillSummaryData['financial_year_id'] = $financialYearId;

            $billMonthNo = $memberBillSummaryData['month'];
            $memberTarrifAmount = $this->getTotalMemberTarrif($memberId,$billMonthNo);
            $gstLimit = isset($societyTariffParameterCheck['SocietyParameter']['gst_limit']) ? $societyTariffParameterCheck['SocietyParameter']['gst_limit'] : 0;

            // do not consider tax on tarrif if in_tax_bill amount is less than gst limit 
            if($gstLimit > 0 && $memberTarrifAmount < $gstLimit){
                    $appliedParams['gst_limit'] = $gstLimit; 
                    $societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] = 0;
                    $societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] = 0;
                    $societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] = 0;                   
            }                             

            foreach ($memberTariffInfo['MemberTariff'] as $memberTariffData) {
                $billGenerateArr[$tariffCounter]['member_transfer'] = $member_transfer;
                $billGenerateArr[$tariffCounter]['member_id'] = !empty($memberTariffData['member_id']) ? $memberTariffData['member_id'] : $memberId;
                $billGenerateArr[$tariffCounter]['bill_generated_date'] = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : NULL;
                $billGeneratedMonths = $billGenerateArr[$tariffCounter]['month'] = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : NULL;

                $billGenerateArr[$tariffCounter]['bill_number'] = isset($paymentBillNo) ? $paymentBillNo : 0;
                $billGenerateArr[$tariffCounter]['amount'] = !empty($memberTariffData['amount']) ? $memberTariffData['amount']*$multiplyTariffAmountValue : 0;
                $billGenerateArr[$tariffCounter]['ledger_head_id'] = !empty($memberTariffData['ledger_head_id']) ? $memberTariffData['ledger_head_id'] : 0;
                $billGenerateArr[$tariffCounter]['society_id'] = $this->societyloginID;
                $billGenerateArr[$tariffCounter]['bill_type'] = $billType;
                $billGenerateArr[$tariffCounter]['cdate'] = $this->Util->getDateTime();
                $billGenerateArr[$tariffCounter]['financial_year_id'] = $financialYearId;
                $memberBillSummaryData['monthly_amount'] += !empty($memberTariffData['amount']) ? $memberTariffData['amount']*$multiplyTariffAmountValue : 0;

                //If taxes applicable
                if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['tax']) && in_array($memberTariffData['ledger_head_id'], $billLedgerHeadSettings['tax'])) {
                    $taxAmount = 0;
                    if ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] > 0) {
                        $igstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] / 100);
                        $memberBillSummaryData['igst_total'] = $memberBillSummaryData['igst_total'] + $igstTaxAmount;
                        $billGenerateArr[$tariffCounter]['igst_total'] = $igstTaxAmount;
                        $taxAmount += $igstTaxAmount;
                    }

 

                    if ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] > 0) {
                        $cgstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] / 100);
                        $memberBillSummaryData['cgst_total'] = $memberBillSummaryData['cgst_total'] + $cgstTaxAmount;
                        $billGenerateArr[$tariffCounter]['cgst_total'] = $cgstTaxAmount;
                        $taxAmount += $cgstTaxAmount;
                    }

                    if ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] > 0) {
                        $sgstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] / 100);
                        $memberBillSummaryData['sgst_total'] = $memberBillSummaryData['sgst_total'] + $sgstTaxAmount;
                        $billGenerateArr[$tariffCounter]['sgst_total'] = $sgstTaxAmount;
                        $taxAmount += $sgstTaxAmount;
                    }

                    $memberBillSummaryData['tax_total'] = $memberBillSummaryData['tax_total'] + $taxAmount;
                    $billGenerateArr[$tariffCounter]['tax_total'] = $taxAmount;                    
                    $memberBillSummaryData['tax_balance'] += $taxAmount;
                }


                if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['interestFree']) && in_array($memberTariffData['ledger_head_id'], $billLedgerHeadSettings['interestFree'])) {
                    $memberBillSummaryData['interest_free_amount'] += $memberTariffData['amount']*$multiplyTariffAmountValue;
                }
                $tariffCounter++;
            }

            if (isset($billGenerateArr) && !empty($billGenerateArr)) {                                
                $interestArreasgst = $societyTariffParameterCheck['SocietyParameter']['gst_interest_arreas'];
                if($interestArreasgst == 'Y' && !empty($memberBillSummaryData['op_interest_arrears'])){
                    $appliedParams['gst_interest_arreas'] = $interestArreasgst;
                    $appliedParams['op_interest_arrears'] = $memberBillSummaryData['op_interest_arrears'];
                    $cgstOnInteresetAmount = ($memberBillSummaryData['op_interest_arrears'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] / 100);
                    $memberBillSummaryData['cgst_total'] = $memberBillSummaryData['cgst_total'] + $cgstOnInteresetAmount; 
                    $sgstOnInteresetAmount = ($memberBillSummaryData['op_interest_arrears'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] / 100);
                    $memberBillSummaryData['sgst_total'] = $memberBillSummaryData['sgst_total'] + $sgstOnInteresetAmount;                     
                    $memberBillSummaryData['tax_total'] = $memberBillSummaryData['tax_total']+$cgstOnInteresetAmount+$sgstOnInteresetAmount;
                    $memberBillSummaryData['tax_balance'] = $memberBillSummaryData['tax_balance']+$cgstOnInteresetAmount+$sgstOnInteresetAmount;                   
                    $memberBillSummaryData['gst_on_interest_arrease'] = $cgstOnInteresetAmount+$sgstOnInteresetAmount;                    
                }                

                
                $currentInterestgst = $societyTariffParameterCheck['SocietyParameter']['gst_interest'];                
                if($currentInterestgst == 'Y' && !empty($memberBillSummaryData['interest_on_due_amount'])){
                    $appliedParams['gst_interest'] = $currentInterestgst;
                    $appliedParams['interest_on_due_amount'] = $memberBillSummaryData['interest_on_due_amount'];
                    $cgstOnInteresetAmount = ($memberBillSummaryData['interest_on_due_amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] / 100);
                    $memberBillSummaryData['cgst_total'] = $memberBillSummaryData['cgst_total'] + $cgstOnInteresetAmount; 
                    $sgstOnInteresetAmount = ($memberBillSummaryData['interest_on_due_amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] / 100);
                    $memberBillSummaryData['sgst_total'] = $memberBillSummaryData['sgst_total'] + $sgstOnInteresetAmount;                     
                    $memberBillSummaryData['tax_total'] = $memberBillSummaryData['tax_total']+$cgstOnInteresetAmount+$sgstOnInteresetAmount; 
                    $memberBillSummaryData['tax_balance'] = $memberBillSummaryData['tax_balance']+$cgstOnInteresetAmount+$sgstOnInteresetAmount;            
                    $memberBillSummaryData['gst_on_current_interest'] = $cgstOnInteresetAmount+$sgstOnInteresetAmount;                    
                }


                $memberBillSummaryData['monthly_principal_amount'] = $memberBillSummaryData['monthly_amount'] - $memberBillSummaryData['discount'];
                $memberBillSummaryData['monthly_bill_amount'] = $memberBillSummaryData['monthly_principal_amount'] + $memberBillSummaryData['tax_total'] + $memberBillSummaryData['interest_on_due_amount'];
                
                $memberBillSummaryData['amount_payable'] = $memberBillSummaryData['op_due_amount'] + $memberBillSummaryData['monthly_bill_amount'];
                                

                
                if($memberBillSummaryData['op_due_amount'] < 0 ){ // in case of advance payment
                    $memberBillSummaryData['principal_balance'] = $memberBillSummaryData['monthly_principal_amount'] - $memberBillSummaryData['principal_paid'] ;
                    $memberBillSummaryData['interest_balance'] =  $memberBillSummaryData['interest_on_due_amount'] - $memberBillSummaryData['interest_paid'];
                    $memberBillSummaryData['op_principal_arrears'] = $memberBillSummaryData['op_due_amount'];
                    $tempDueAmount = abs($memberBillSummaryData['op_due_amount']);
                    $this->updateGeneratedBillBalanceAmountInAdvance($memberBillSummaryData,$tempDueAmount);
                }
                else{
                    $memberBillSummaryData['principal_balance'] = ($memberBillSummaryData['op_principal_arrears'] + $memberBillSummaryData['monthly_principal_amount']) - $memberBillSummaryData['principal_paid'] ;
                    $memberBillSummaryData['interest_balance'] = ($memberBillSummaryData['op_interest_arrears'] + $memberBillSummaryData['interest_on_due_amount']) - $memberBillSummaryData['interest_paid'];
                }

        
                $memberBillSummaryData['balance_amount'] = $memberBillSummaryData['principal_balance'] + $memberBillSummaryData['interest_balance'] + $memberBillSummaryData['tax_balance'];
                $memberBillSummaryData['member_transfer'] = $member_transfer;

                if($this->MemberBillGenerate->saveAll($billGenerateArr)){        
                    $this->MemberBillSummary->create();
                    if ($this->MemberBillSummary->save($memberBillSummaryData)) {
                        array_push($this->appliedParmaOnBill,$appliedParams);
                        
                        $responseError['error'] = 0;
                        $responseError['error_message'] = 'Member bill has been generated successfully.';

                    } else {
                        $responseError['error'] = 1;
                        $responseError['error_message'] = 'Member society bill not generated.';
                    }
                }
                else {
                        $responseError['error'] = 1;
                        $responseError['error_message'] = 'Member society bill not generated.';
                }
            }
        }
        else {
            $responseError['error'] = 1;
            $responseError['error_message'] = 'Members Bill already exists in the system with the same month .';
        }
        return $responseError;
    }
    
    
public function updateGeneratedBillBalanceAmountInAdvance(&$memberBillSummaryData,$dueAmount){
    if($dueAmount > 0){
        $dueAmount = $dueAmount - $memberBillSummaryData['tax_balance'];
        if($dueAmount <= 0){
            $memberBillSummaryData['tax_balance'] = abs($dueAmount);
            $dueAmount = 0;
        }
        else{
            $memberBillSummaryData['tax_balance'] = 0;
        }
    }
    
    if($dueAmount > 0){
        $dueAmount = $dueAmount - $memberBillSummaryData['interest_balance'];
        if($dueAmount <= 0){
            $memberBillSummaryData['interest_balance'] = abs($dueAmount);
            $dueAmount = 0;
        }
        else{
            $memberBillSummaryData['interest_balance'] = 0;
        }
    }
    
    if($dueAmount > 0){
        $dueAmount = $dueAmount - $memberBillSummaryData['principal_balance'];
        if($dueAmount <= 0){
            $memberBillSummaryData['principal_balance'] = abs($dueAmount);
            $dueAmount = 0;
        }
        else{
            $memberBillSummaryData['principal_balance'] = -$dueAmount;
        }
    }
}
    
public function getBillEndDate($societyTariffParameterCheck,$billGeneratedDate){
        $billFreqId = $societyTariffParameterCheck['SocietyParameter']['billing_frequency_id'];
        $finalDate = '';
        if($billFreqId == 1){
            $monthToAdd = 0;
            $billEndDate = date('d-m-Y',strtotime("+$monthToAdd months",strtotime($billGeneratedDate)));
            $year = date("Y",strtotime($billEndDate));
            $days = date("d",strtotime($billEndDate));
            $month = date("m",strtotime($billEndDate));
            $noOfDays = cal_days_in_month(CAL_GREGORIAN,$month,$year);
            $dateString = "$year-$month-$noOfDays";
            $finalDate= date("Y-m-d",strtotime($dateString));
        }
        else if($billFreqId == 2){
            $monthToAdd = 1;
            $billEndDate = date('Y-m-d',strtotime("+$monthToAdd months",strtotime($billGeneratedDate)));
            $year = date("Y",strtotime($billEndDate));
            $days = date("d",strtotime($billEndDate));
            $month = date("m",strtotime($billEndDate));
            $noOfDays = cal_days_in_month(CAL_GREGORIAN,$month,$year);
            $dateString = "$year-$month-$noOfDays";
            $finalDate= date("Y-m-d",strtotime($dateString));            
        }
        else if($billFreqId == 3){
            $monthToAdd = 2;
            $billEndDate = date('d-m-Y',strtotime("+$monthToAdd months",strtotime($billGeneratedDate)));
            $year = date("Y",strtotime($billEndDate));
            $days = date("d",strtotime($billEndDate));
            $month = date("m",strtotime($billEndDate));
            $noOfDays = cal_days_in_month(CAL_GREGORIAN,$month,$year);
            $dateString = "$year-$month-$noOfDays";
            $finalDate= date("Y-m-d",strtotime($dateString));
        }
        else if($billFreqId == 4){
            $monthToAdd = 3;
            $billEndDate = date('d-m-Y',strtotime("+$monthToAdd months",strtotime($billGeneratedDate)));
            $year = date("Y",strtotime($billEndDate));
            $days = date("d",strtotime($billEndDate));
            $month = date("m",strtotime($billEndDate));
            $noOfDays = cal_days_in_month(CAL_GREGORIAN,$month,$year);
            $dateString = "$year-$month-$noOfDays";
            $finalDate= date("Y-m-d",strtotime($dateString));            
        }
        else if($billFreqId == 5){
            $monthToAdd = 5;
            $billEndDate = date('d-m-Y',strtotime("+$monthToAdd months",strtotime($billGeneratedDate)));
            $year = date("Y",strtotime($billEndDate));
            $days = date("d",strtotime($billEndDate));
            $month = date("m",strtotime($billEndDate));
            $noOfDays = cal_days_in_month(CAL_GREGORIAN,$month,$year);
            $dateString = "$year-$month-$noOfDays";
            $finalDate= date("Y-m-d",strtotime($dateString));
        }
        else if($billFreqId == 6){
            $monthToAdd = 11;
            $billEndDate = date('d-m-Y',strtotime("+$monthToAdd months",strtotime($billGeneratedDate)));
            $year = date("Y",strtotime($billEndDate));
            $days = date("d",strtotime($billEndDate));
            $month = date("m",strtotime($billEndDate));
            $noOfDays = cal_days_in_month(CAL_GREGORIAN,$month,$year);
            $dateString = "$year-$month-$noOfDays";
            $finalDate= date("Y-m-d",strtotime($dateString));            
        }        
        
        return $finalDate;
        
    }
    


 public function calculateMembersRegularBillByTariff_bkOM($memberId, $memberTariffInfo, $paymentBillNo, $postData, $billLedgerHeadSettings, $memberDetails,$member_transfer) {



        $this->loadModel('JournalVoucher');



        



        $checkGenerateMonth     =  $postData['MemberBillGenerate']['month']-1;       







        $memberPaymentDetails = $this->Member->MemberPayment->find('first',  array('fields'=>array('MemberPayment.id','MemberPayment.bill_month'),'conditions' => array('MemberPayment.member_id' =>$memberId),'order'=>array('MemberPayment.id'=>'desc')));



        $check_LastPaymentMonth =  $memberPaymentDetails['MemberPayment']['bill_month'];







        $tariffCounter = 0;



        $billGenerateArr = array();



        $responseError = array();



        $billType = Configure::read('BillType.Regular');



        $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id'))));



        //$societyTariffParemeterSet = isset($societyTariffParameterCheck['SocietyParameter']['tariff_id']) ? $societyTariffParameterCheck['SocietyParameter']['tariff_id'] : 0;



        $multiplyTariffAmountValue = $this->SocietyBill->multiplyTariffAmountByBillingFrequency();



        



        $billGeneratedMonths = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : date('m');



        $societyMemberBillExists = $this->societyMemberBillExists($memberId, $billGeneratedMonths, $billType); //check same bill allready exists in system 



        if (empty($societyMemberBillExists)) {



            //$this->MemberBillGenerate->deleteAll(array('MemberBillGenerate.member_id' => $memberId, 'MemberBillGenerate.month' => $billGeneratedMonths,'MemberBillGenerate.bill_type'=>$billType));



            if ($this->MemberBillSummary->hasAny(array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.month' => $billGeneratedMonths, 'MemberBillSummary.bill_type' => $billType))) {



                //$this->MemberBillSummary->deleteAll(array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.month' => $billGeneratedMonths,'MemberBillSummary.bill_type'=>$billType));



            }



            



            



            //Check if last months bill details available



            /*$lastMonthBillDetails = $this->MemberBillSummarySettlement->find('first', array('conditions' => array('MemberBillSummarySettlement.member_id' => $memberId, 'MemberBillSummarySettlement.society_id' => $this->societyloginID, 'MemberBillSummarySettlement.bill_type' => $billType), 'order' => array('MemberBillSummarySettlement.id' => 'desc')));



            



            if(empty($lastMonthBillDetails)) {



                $lastMonthBillDetails = $this->MemberBillSummary->find('first', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType), 'order' => array('MemberBillSummary.id' => 'desc')));



            } else {



                $lastMonthBillDetails['MemberBillSummary'] = $lastMonthBillDetails['MemberBillSummarySettlement'];



                unset($lastMonthBillDetails['MemberBillSummarySettlement']);



            }*/



            



            $lastMonthBillDetails = $this->MemberBillSummary->find('first', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType), 'order' => array('MemberBillSummary.id' => 'desc')));



                        



            $jvAdjustment = 0;



 



            if (isset($lastMonthBillDetails) && !empty($lastMonthBillDetails)) {



                



                $journalVoucherData = $this->JournalVoucher->find('all', array('conditions' => array('JournalVoucher.society_id' => $this->societyloginID,'JournalVoucher.voucher_date > '=> $lastMonthBillDetails['MemberBillSummary']['bill_generated_date'],'JournalVoucher.voucher_date <= '=> $lastMonthBillDetails['MemberBillSummary']['bill_due_date'],"OR" => array(



                "JournalVoucher.jv_debit_member_head_id" => $memberId,



                "JournalVoucher.jv_credit_member_head_id" => $memberId)



                )));



            }



            else{



                



                $journalVoucherData = array();



            }











            foreach ($journalVoucherData as $counter => $jVData){



                if (isset($jVData['JournalVoucher']['jv_credit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_credit_member_head_id'])) {



                            $jvAdjustment = $jvAdjustment - $jVData['JournalVoucher']['jv_amount_credited'];



                    }



                elseif (isset($jVData['JournalVoucher']['jv_debit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_debit_member_head_id'])) {



                            $jvAdjustment = $jvAdjustment + $jVData['JournalVoucher']['jv_amount_debited'];



                    }



            }



            if (isset($lastMonthBillDetails) && !empty($lastMonthBillDetails)) {



                $lastMonthBillDetails = $lastMonthBillDetails['MemberBillSummary'];



                



                $memberBillSummaryData['op_principal_arrears_original'] = $memberBillSummaryData['op_principal_arrears'] = $lastMonthBillDetails['principal_balance'];







                $memberBillSummaryData['jv_adjustment'] = $jvAdjustment;



                // adjustment  in case jv has been between after the last bill generation date



                $memberBillSummaryData['op_principal_arrears'] = $memberBillSummaryData['op_principal_arrears_original'] + $jvAdjustment;



                



                $memberBillSummaryData['op_due_amount'] = $lastMonthBillDetails['balance_amount']+$jvAdjustment;



                $memberBillSummaryData['tax_total'] = $lastMonthBillDetails['tax_total'];



          











                if($checkGenerateMonth == $check_LastPaymentMonth){



                $memberBillSummaryData['op_interest_arrears'] =0;



                $memberBillSummaryData['op_tax_arrears'] = 0; 



                } else {



                $memberBillSummaryData['op_interest_arrears'] = $lastMonthBillDetails['interest_balance'];



                $memberBillSummaryData['op_tax_arrears'] = $lastMonthBillDetails['op_tax_arrears'] + $lastMonthBillDetails['tax_total'];



                }



//testing pravin



$memberBillSummaryData['op_interest_arrears'] = $lastMonthBillDetails['interest_balance'];



//month of 5 gene....  passing value tax_bal.. op_tax_arr.....

$memberBillSummaryData['op_tax_arrears'] =  $lastMonthBillDetails['tax_balance'];







                $memberBillSummaryData['tax_balance'] = $lastMonthBillDetails['tax_balance'];



                $memberBillSummaryData['interest_on_due_amount'] = 0;



                



                $lastMonthBillDetails['paymentDetails'] = array();



                if($memberBillSummaryData['op_due_amount'] > 0){



                    $memberBillSummaryData['interest_on_due_amount'] = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                }



 



                



                // Check if last month bill has payment and if yes then check if paid before due date



                    $billPaymentDetails = $this->MemberBillSettlement->find('first', array('conditions' => array('MemberBillSettlement.member_id' => $memberId, 'MemberBillSettlement.bill_summary_id' => $lastMonthBillDetails['id'])));



                    $billPaymentDetailsArray = array();



                    if(!empty($billPaymentDetails)) {



                        $billPaymentDetailsArray['principal_paid'] = $billPaymentDetails['MemberBillSettlement']['principal_paid'];



                        $billPaymentDetailsArray['interest_paid'] = $billPaymentDetails['MemberBillSettlement']['interest_paid'];



                        $billPaymentDetailsArray['tax_paid'] = $billPaymentDetails['MemberBillSettlement']['tax_paid'];



                        $billPaymentDetailsArray['total_paid'] = $billPaymentDetailsArray['principal_paid']+$billPaymentDetailsArray['interest_paid']+$billPaymentDetailsArray['tax_paid'];



                        $billPaymentDetailsArray['payment_date'] = $billPaymentDetails['MemberPayment']['payment_date'];



                    }



                    



                    if(!empty($billPaymentDetailsArray)) {



                        $lastMonthBillDetails['paymentDetails'] = $billPaymentDetailsArray;



                        $memberBillSummaryData['interest_on_due_amount'] += $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                    }



               } else {



                if (isset($memberDetails) && !empty($memberDetails)) {



                    $lastMonthBillDetails['principal_balance'] = isset($memberDetails['op_principal']) ? $memberDetails['op_principal'] : 0.00;



                    $lastMonthBillDetails['balance_amount'] = $memberDetails['op_principal'] + $memberDetails['op_interest'] + $memberDetails['op_tax'];



                    $lastMonthBillDetails['bill_due_date'] = isset($memberDetails['op_bill_due_date']) ? $memberDetails['op_bill_due_date'] : '';



                    $lastMonthBillDetails['bill_generated_date'] = isset($memberDetails['op_bill_date']) ? $memberDetails['op_bill_date'] : '';



                    $lastMonthBillDetails['interest_free_amount'] = 0;



                    $memberBillSummaryData['op_principal_arrears_original'] = $memberBillSummaryData['op_principal_arrears'] = isset($memberDetails['op_principal']) ? $memberDetails['op_principal'] : 0.00;







                    $memberBillSummaryData['jv_adjustment'] = $jvAdjustment;







                    $memberBillSummaryData['op_principal_arrears'] = $memberBillSummaryData['op_principal_arrears_original'] + $jvAdjustment;



                    $memberBillSummaryData['op_interest_arrears'] = isset($memberDetails['op_interest']) ? $memberDetails['op_interest'] : 0.00;



                    $memberBillSummaryData['op_tax_arrears'] = isset($memberDetails['op_tax']) ? $memberDetails['op_tax'] : 0.00;



                    $memberBillSummaryData['op_due_amount'] = $memberDetails['op_principal'] + $memberDetails['op_interest'] + $memberDetails['op_tax'];



                    $memberBillSummaryData['interest_on_due_amount'] = 0;



                    if (isset($lastMonthBillDetails['bill_due_date']) && $lastMonthBillDetails['bill_due_date'] != '0000-00-00' && $memberBillSummaryData['op_due_amount'] > 0) {



                        $int = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                        if ($int > 0){



                            $memberBillSummaryData['interest_on_due_amount'] = $int;



                        }



                    }



                    $memberBillSummaryData['tax_balance'] = 0;



                    //$memberBillSummaryData['op_tax_arrears'] = 0;



                    $memberBillSummaryData['tax_total'] = 0;



                } else {



                    $memberBillSummaryData['op_principal_arrears'] = 0;



                    $memberBillSummaryData['op_interest_arrears'] = 0;



                    $memberBillSummaryData['op_due_amount'] = 0;



                    $memberBillSummaryData['interest_on_due_amount'] = 0;



                    $memberBillSummaryData['op_tax_arrears'] = 0;



                    $memberBillSummaryData['tax_balance'] = 0;



                    $memberBillSummaryData['tax_total'] = 0;



                }



            }







            $memberBillSummaryData['bill_no'] = $paymentBillNo;



            $memberBillSummaryData['month'] = $billGeneratedMonths;



            $memberBillSummaryData['member_id'] = $memberId;



            $memberBillSummaryData['bill_type'] = $billType;



            $memberBillSummaryData['society_id'] = $this->societyloginID;



            $memberBillSummaryData['flat_no'] = isset($memberTariffInfo['Member']['flat_no']) ? $memberTariffInfo['Member']['flat_no'] : '';



            $memberBillSummaryData['monthly_amount'] = 0; //monthly member maintenance value



            $memberBillSummaryData['monthly_principal_amount'] = 0; //monthly_amount - discount;



            $memberBillSummaryData['discount'] = 0;



            $memberBillSummaryData['monthly_bill_amount'] = 0; //monthly_principal_amount + interest_on_due_amount;



            $memberBillSummaryData['amount_payable'] = 0; //op_due_amount + monthly_bill_amount;



            $memberBillSummaryData['principal_paid'] = 0;



            $memberBillSummaryData['interest_paid'] = 0;



            $memberBillSummaryData['principal_adjusted'] = 0;



            $memberBillSummaryData['interest_adjusted'] = 0;



            $memberBillSummaryData['principal_balance'] = 0; //op_principal_arrears + monthly_principal_amount - principal_paid;



            $memberBillSummaryData['interest_balance'] = 0; //op_interest_arrears + interest_on_due_amount - interest_paid;



            $memberBillSummaryData['balance_amount'] = 0; //principal_balance + interest_balance;



            $memberBillSummaryData['interest_adjusted'] = 0;



            $memberBillSummaryData['igst_total'] = 0;



            $memberBillSummaryData['cgst_total'] = 0;



            $memberBillSummaryData['sgst_total'] = 0;



            $memberBillSummaryData['tax_total'] = 0;



            $memberBillSummaryData['tax_paid'] = 0;



            $memberBillSummaryData['tax_adjusted'] = 0;



            $memberBillSummaryData['interest_free_amount'] = 0;



            $memberBillSummaryData['bill_tariff_type'] = $societyTariffParameterCheck['SocietyParameter']['tariff_id'];



            $memberBillSummaryData['bill_generated_date'] = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : '';



            $memberBillSummaryData['bill_due_date'] = (isset($postData['MemberBillGenerate']['bill_due_date']) && !empty($postData['MemberBillGenerate']['bill_due_date'])) ? trim($postData['MemberBillGenerate']['bill_due_date']) : NULL;



            $memberBillSummaryData['cdate'] = $this->Util->getDateTime();



            $memberBillSummaryData['udate'] = $this->Util->getDateTime();



            //print_r($billLedgerHeadSettings);die;











                  if($memberBillSummaryData['op_due_amount'] < 0){



                        $memberBillSummaryData['interest_on_due_amount']=0; 



                     }







            



            foreach ($memberTariffInfo['MemberTariff'] as $memberTariffData) {



                $billGenerateArr[$tariffCounter]['member_transfer'] = isset($memberTariffData['member_transfer']) ? $member_transfer : $member_transfer;



                $billGenerateArr[$tariffCounter]['member_id'] = isset($memberTariffData['member_id']) ? $memberTariffData['member_id'] : $memberId;

                $billGenerateArr[$tariffCounter]['bill_generated_date'] = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : NULL;



                $billGeneratedMonths = $billGenerateArr[$tariffCounter]['month'] = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : NULL;



                $billGenerateArr[$tariffCounter]['bill_number'] = isset($paymentBillNo) ? $paymentBillNo : 0;



                $billGenerateArr[$tariffCounter]['amount'] = isset($memberTariffData['amount']) ? $memberTariffData['amount']*$multiplyTariffAmountValue : 0;



                $billGenerateArr[$tariffCounter]['ledger_head_id'] = isset($memberTariffData['ledger_head_id']) ? $memberTariffData['ledger_head_id'] : 0;



                $billGenerateArr[$tariffCounter]['society_id'] = $this->societyloginID;



                $billGenerateArr[$tariffCounter]['bill_type'] = $billType;



                $billGenerateArr[$tariffCounter]['cdate'] = $this->Util->getDateTime();







                $memberBillSummaryData['monthly_amount'] += isset($memberTariffData['amount']) ? $memberTariffData['amount']*$multiplyTariffAmountValue : 0;



                //If taxes applicable



                if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['tax']) && in_array($memberTariffData['ledger_head_id'], $billLedgerHeadSettings['tax'])) {



                    $taxAmount = 0;



                    if ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] > 0) {



                        $igstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] / 100);



                        $memberBillSummaryData['igst_total'] = $memberBillSummaryData['igst_total'] + $igstTaxAmount;



                        $billGenerateArr[$tariffCounter]['igst_total'] = $igstTaxAmount;



                        $taxAmount += $igstTaxAmount;



                    }

 

                    if ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] > 0) {



                        $cgstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] / 100);



                        $memberBillSummaryData['cgst_total'] = $memberBillSummaryData['cgst_total'] + $cgstTaxAmount;



                        $billGenerateArr[$tariffCounter]['cgst_total'] = $cgstTaxAmount;



                        $taxAmount += $cgstTaxAmount;



                    }



                    if ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] > 0) {



                        $sgstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] / 100);



                        $memberBillSummaryData['sgst_total'] = $memberBillSummaryData['sgst_total'] + $sgstTaxAmount;



                        $billGenerateArr[$tariffCounter]['sgst_total'] = $sgstTaxAmount;



                        $taxAmount += $sgstTaxAmount;



                    }



                    $memberBillSummaryData['tax_total'] = $memberBillSummaryData['tax_total'] + $taxAmount;



                    $billGenerateArr[$tariffCounter]['tax_total'] = $taxAmount;



                    $memberBillSummaryData['monthly_amount'] += $taxAmount;



                    $memberBillSummaryData['tax_balance'] += $taxAmount;



                }





                if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['interestFree']) && in_array($memberTariffData['ledger_head_id'], $billLedgerHeadSettings['interestFree'])) {



                    $memberBillSummaryData['interest_free_amount'] += $memberTariffData['amount']*$multiplyTariffAmountValue;



                }



                $tariffCounter++;



            }



            if (isset($billGenerateArr) && count($billGenerateArr) > 0) {



                $memberBillSummaryData['monthly_principal_amount'] = $memberBillSummaryData['monthly_amount'] - $memberBillSummaryData['discount'];



                $memberBillSummaryData['monthly_bill_amount'] = $memberBillSummaryData['monthly_principal_amount'] + $memberBillSummaryData['interest_on_due_amount'];



                $memberBillSummaryData['amount_payable'] = $memberBillSummaryData['op_due_amount'] + $memberBillSummaryData['monthly_bill_amount'];



                if($memberBillSummaryData['amount_payable'] < 0){

                   $memberBillSummaryData['tax_total']=0;

                  $memberBillSummaryData['tax_balance']=0;

                    $memberBillSummaryData['op_tax_arrears'] =0;

                }

                

//testing pravin

if($billGeneratedMonths==4){

 $memberBillSummaryData['tax_balance'] +=$memberBillSummaryData['op_tax_arrears']; 

 }





                $memberBillSummaryData['principal_balance'] = ($memberBillSummaryData['op_principal_arrears'] + $memberBillSummaryData['monthly_principal_amount']) - $memberBillSummaryData['principal_paid'] - $memberBillSummaryData['tax_total'];



                $memberBillSummaryData['interest_balance'] = ($memberBillSummaryData['op_interest_arrears'] + $memberBillSummaryData['interest_on_due_amount']) - $memberBillSummaryData['interest_paid'];



                $memberBillSummaryData['balance_amount'] = $memberBillSummaryData['principal_balance'] + $memberBillSummaryData['interest_balance'] + $memberBillSummaryData['tax_balance'];



               // $memberBillSummaryData['op_tax_arrears'] = ($memberBillSummaryData['op_tax_arrears'] + $memberBillSummaryData['tax_total']);



                   $memberBillSummaryData['member_transfer'] = $member_transfer;

  



                $memberBillSummaryData['member_transfer'] = $member_transfer;









                $this->MemberBillGenerate->saveAll($billGenerateArr);



                $this->MemberBillSummary->create();



                if ($this->MemberBillSummary->save($memberBillSummaryData)) {



                    //Insert in bill summary



                    if ($this->MemberBillSummarySettlement->deleteAll(array('MemberBillSummarySettlement.member_id' => $memberId), false)){



                    }



                    if ($this->MemberBillSummarySettlement->save($memberBillSummaryData)) {                    



                    }



                    $responseError['error'] = 0;



                    $responseError['error_message'] = 'Member bill has been generated successfully.';



                } else {



                    $responseError['error'] = 1;



                    $responseError['error_message'] = 'Member society bill not generated.';



                }



            }



        } else {



            $responseError['error'] = 1;



            $responseError['error_message'] = 'Members Bill already exists in the system with the same month .';



        }



        return $responseError;



    }








    public function calculateMembersRegularBillByTariffQuarterly($memberId, $memberTariffInfo, $paymentBillNo, $postData, $billLedgerHeadSettings, $memberDetails,$member_transfer) {



        $this->loadModel('JournalVoucher');



    

        $checkGenerateMonth     =  $postData['MemberBillGenerate']['month']-1;       







        $memberPaymentDetails = $this->Member->MemberPayment->find('first',  array('fields'=>array('MemberPayment.id','MemberPayment.bill_month'),'conditions' => array('MemberPayment.member_id' =>$memberId),'order'=>array('MemberPayment.id'=>'desc')));



        $check_LastPaymentMonth =  $memberPaymentDetails['MemberPayment']['bill_month'];







        $tariffCounter = 0;



        $billGenerateArr = array();



        $responseError = array();



        $billType = Configure::read('BillType.Regular');



        $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id'))));



        //$societyTariffParemeterSet = isset($societyTariffParameterCheck['SocietyParameter']['tariff_id']) ? $societyTariffParameterCheck['SocietyParameter']['tariff_id'] : 0;



        $multiplyTariffAmountValue = $this->SocietyBill->multiplyTariffAmountByBillingFrequency();



        



        $billGeneratedMonths = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : date('m');



        $societyMemberBillExists = $this->societyMemberBillExists($memberId, $billGeneratedMonths, $billType); //check same bill allready exists in system 
        $memberData = [
            'member_id' => $memberId,
            'society_id' => $this->Session->read('Auth.User.id')
            ];        



        if (empty($societyMemberBillExists)) {



            //$this->MemberBillGenerate->deleteAll(array('MemberBillGenerate.member_id' => $memberId, 'MemberBillGenerate.month' => $billGeneratedMonths,'MemberBillGenerate.bill_type'=>$billType));



            if ($this->MemberBillSummary->hasAny(array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.month' => $billGeneratedMonths, 'MemberBillSummary.bill_type' => $billType))) {



                //$this->MemberBillSummary->deleteAll(array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.month' => $billGeneratedMonths,'MemberBillSummary.bill_type'=>$billType));



            }



            



            



            //Check if last months bill details available



            /*$lastMonthBillDetails = $this->MemberBillSummarySettlement->find('first', array('conditions' => array('MemberBillSummarySettlement.member_id' => $memberId, 'MemberBillSummarySettlement.society_id' => $this->societyloginID, 'MemberBillSummarySettlement.bill_type' => $billType), 'order' => array('MemberBillSummarySettlement.id' => 'desc')));



            



            if(empty($lastMonthBillDetails)) {



                $lastMonthBillDetails = $this->MemberBillSummary->find('first', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType), 'order' => array('MemberBillSummary.id' => 'desc')));



            } else {



                $lastMonthBillDetails['MemberBillSummary'] = $lastMonthBillDetails['MemberBillSummarySettlement'];



                unset($lastMonthBillDetails['MemberBillSummarySettlement']);



            }*/



            



            // $lastMonthBillDetails = $this->MemberBillSummary->find('first', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType), 'order' => array('MemberBillSummary.id' => 'desc')));
            // $latestTransferNo = $this->getLatestTranferredId($memberId);        
            $lastMonthBillDetails = [];
            $memberAllBills = $this->MemberBillSummary->find('all', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType,'MemberBillSummary.member_transfer'=>$member_transfer),'order'=>array('MemberBillSummary.id'=>'asc')));

            if(!empty($memberAllBills)){
                $lastIndex = count($memberAllBills)-1;
                $lastMonthBillDetails =  $memberAllBills[$lastIndex];
            }


            $jvAdjustment = 0;



 



            if (isset($lastMonthBillDetails) && !empty($lastMonthBillDetails)) {



                



                $journalVoucherData = $this->JournalVoucher->find('all', array('conditions' => array('JournalVoucher.society_id' => $this->societyloginID,'JournalVoucher.voucher_date > '=> $lastMonthBillDetails['MemberBillSummary']['bill_generated_date'],'JournalVoucher.voucher_date <= '=> $lastMonthBillDetails['MemberBillSummary']['bill_due_date'],"OR" => array(



                "JournalVoucher.jv_debit_member_head_id" => $memberId,



                "JournalVoucher.jv_credit_member_head_id" => $memberId)



                )));



            }



            else{



                



                $journalVoucherData = array();



            }











            foreach ($journalVoucherData as $counter => $jVData){



                if (isset($jVData['JournalVoucher']['jv_credit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_credit_member_head_id'])) {



                            $jvAdjustment = $jvAdjustment - $jVData['JournalVoucher']['jv_amount_credited'];



                    }



                elseif (isset($jVData['JournalVoucher']['jv_debit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_debit_member_head_id'])) {



                            $jvAdjustment = $jvAdjustment + $jVData['JournalVoucher']['jv_amount_debited'];



                    }



            }



            if (isset($lastMonthBillDetails) && !empty($lastMonthBillDetails)) {



                $lastMonthBillDetails = $lastMonthBillDetails['MemberBillSummary'];



                



                $memberBillSummaryData['op_principal_arrears_original'] = $memberBillSummaryData['op_principal_arrears'] = $lastMonthBillDetails['principal_balance'];







                $memberBillSummaryData['jv_adjustment'] = $jvAdjustment;



                // adjustment  in case jv has been between after the last bill generation date



                $memberBillSummaryData['op_principal_arrears'] = $memberBillSummaryData['op_principal_arrears_original'] + $jvAdjustment;



                



                $memberBillSummaryData['op_due_amount'] = $lastMonthBillDetails['balance_amount']+$jvAdjustment;



                $memberBillSummaryData['tax_total'] = $lastMonthBillDetails['tax_total'];



          











                if($checkGenerateMonth == $check_LastPaymentMonth){



                $memberBillSummaryData['op_interest_arrears'] =0;



                $memberBillSummaryData['op_tax_arrears'] = 0; 



                } else {



                $memberBillSummaryData['op_interest_arrears'] = $lastMonthBillDetails['interest_balance'];



                $memberBillSummaryData['op_tax_arrears'] = $lastMonthBillDetails['op_tax_arrears'] + $lastMonthBillDetails['tax_balance'];



                }







                $memberBillSummaryData['tax_balance'] = $lastMonthBillDetails['tax_balance'];



                $memberBillSummaryData['interest_on_due_amount'] = 0;



                



                $lastMonthBillDetails['paymentDetails'] = array();



/*                 if($memberBillSummaryData['op_due_amount'] > 0){



                    $memberBillSummaryData['interest_on_due_amount'] = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                }*/



 



                



                // Check if last month bill has payment and if yes then check if paid before due date


/* 
                    $billPaymentDetails = $this->MemberBillSettlement->find('first', array('conditions' => array('MemberBillSettlement.member_id' => $memberId, 'MemberBillSettlement.bill_summary_id' => $lastMonthBillDetails['id'])));



                    $billPaymentDetailsArray = array();



                    if(!empty($billPaymentDetails)) {



                        $billPaymentDetailsArray['principal_paid'] = $billPaymentDetails['MemberBillSettlement']['principal_paid'];



                        $billPaymentDetailsArray['interest_paid'] = $billPaymentDetails['MemberBillSettlement']['interest_paid'];



                        $billPaymentDetailsArray['tax_paid'] = $billPaymentDetails['MemberBillSettlement']['tax_paid'];



                        $billPaymentDetailsArray['total_paid'] = $billPaymentDetailsArray['principal_paid']+$billPaymentDetailsArray['interest_paid']+$billPaymentDetailsArray['tax_paid'];



                        $billPaymentDetailsArray['payment_date'] = $billPaymentDetails['MemberPayment']['payment_date'];



                    }



                    



                    if(!empty($billPaymentDetailsArray)) {



                        $lastMonthBillDetails['paymentDetails'] = $billPaymentDetailsArray;



                        $memberBillSummaryData['interest_on_due_amount'] += $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                    }*/
                    $interestTypeId = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];
                    if($interestTypeId != 4){ //  4 - manually                    
                        $memberBillSummaryData['interest_on_due_amount'] = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData,$memberData,$memberAllBills,$billType,$member_transfer);
                    }

               } else {



                if (isset($memberDetails) && !empty($memberDetails)) {
                    if(!empty($latestTransferNo)){
                        $memberDetails['op_principal'] = 0;
                        $memberDetails['op_tax'] = 0;
                        $memberDetails['op_interest'] = 0;
                    }


                    $lastMonthBillDetails['principal_balance'] = isset($memberDetails['op_principal']) ? $memberDetails['op_principal'] : 0.00;



                    $lastMonthBillDetails['balance_amount'] = $memberDetails['op_principal'] + $memberDetails['op_interest'] + $memberDetails['op_tax'];



                    $lastMonthBillDetails['bill_due_date'] = isset($memberDetails['op_bill_due_date']) ? $memberDetails['op_bill_due_date'] : '';



                    $lastMonthBillDetails['bill_generated_date'] = isset($memberDetails['op_bill_date']) ? $memberDetails['op_bill_date'] : '';



                    $lastMonthBillDetails['interest_free_amount'] = 0;

                    $memberBillSummaryData['op_principal_arrears_original'] = $memberBillSummaryData['op_principal_arrears'] = isset($memberDetails['op_principal']) ? $memberDetails['op_principal'] : 0.00;

                    $memberBillSummaryData['jv_adjustment'] = $jvAdjustment;





                    $memberBillSummaryData['op_principal_arrears'] = $memberBillSummaryData['op_principal_arrears_original'] + $jvAdjustment;



                    $memberBillSummaryData['op_interest_arrears'] = isset($memberDetails['op_interest']) ? $memberDetails['op_interest'] : 0.00;



                    $memberBillSummaryData['op_tax_arrears'] = isset($memberDetails['op_tax']) ? $memberDetails['op_tax'] : 0.00;



                    $memberBillSummaryData['op_due_amount'] = $memberDetails['op_principal'] + $memberDetails['op_interest'] + $memberDetails['op_tax'];






                    if (isset($lastMonthBillDetails['bill_due_date']) && $lastMonthBillDetails['bill_due_date'] != '0000-00-00' && $memberBillSummaryData['op_due_amount'] > 0) {
                        $interestTypeId = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];
                        if($interestTypeId != 4){ //  4 - manually                    
                            $memberBillSummaryData['interest_on_due_amount'] = 0;
                            $principalAmt = !empty($memberDetails['op_principal']) ?  $memberDetails['op_principal'] : 0;
                            $intererstAmt = !empty($memberDetails['op_interest']) ?  $memberDetails['op_interest'] : 0;
                            $taxAmount = !empty($memberDetails['op_tax']) ?  $memberDetails['op_tax'] : 0;
                            $billDueDate = $lastBillGeneratedDate = $lastMonthBillDetails['bill_generated_date'];
                            $paymentDate = $billGeneratedDate = $lastMonthBillDetails['bill_due_date'];
    
                            $memberBillSummaryData['interest_on_due_amount'] = $this->getInterestOnDueAmountOnFirstBill($principalAmt,$intererstAmt,$taxAmount,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                        }

                        /*  $int = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);
                        if ($int > 0){
                            $memberBillSummaryData['interest_on_due_amount'] = $int;
                        }*/



                    }



                    $memberBillSummaryData['tax_balance'] = 0;



                    //$memberBillSummaryData['op_tax_arrears'] = 0;



                    $memberBillSummaryData['tax_total'] = 0;



                } else {



                    $memberBillSummaryData['op_principal_arrears'] = 0;



                    $memberBillSummaryData['op_interest_arrears'] = 0;



                    $memberBillSummaryData['op_due_amount'] = 0;



                    $memberBillSummaryData['interest_on_due_amount'] = 0;



                    $memberBillSummaryData['op_tax_arrears'] = 0;



                    $memberBillSummaryData['tax_balance'] = 0;



                    $memberBillSummaryData['tax_total'] = 0;



                }



            }







            $memberBillSummaryData['bill_no'] = $paymentBillNo;



            $memberBillSummaryData['month'] = $billGeneratedMonths;



            $memberBillSummaryData['member_id'] = $memberId;



            $memberBillSummaryData['bill_type'] = $billType;



            $memberBillSummaryData['society_id'] = $this->societyloginID;



            $memberBillSummaryData['flat_no'] = isset($memberTariffInfo['Member']['flat_no']) ? $memberTariffInfo['Member']['flat_no'] : '';



            $memberBillSummaryData['monthly_amount'] = 0; //monthly member maintenance value



            $memberBillSummaryData['monthly_principal_amount'] = 0; //monthly_amount - discount;



            $memberBillSummaryData['discount'] = 0;



            $memberBillSummaryData['monthly_bill_amount'] = 0; //monthly_principal_amount + interest_on_due_amount;



            $memberBillSummaryData['amount_payable'] = 0; //op_due_amount + monthly_bill_amount;



            $memberBillSummaryData['principal_paid'] = 0;



            $memberBillSummaryData['interest_paid'] = 0;



            $memberBillSummaryData['principal_adjusted'] = 0;



            $memberBillSummaryData['interest_adjusted'] = 0;



            $memberBillSummaryData['principal_balance'] = 0; //op_principal_arrears + monthly_principal_amount - principal_paid;



            $memberBillSummaryData['interest_balance'] = 0; //op_interest_arrears + interest_on_due_amount - interest_paid;



            $memberBillSummaryData['balance_amount'] = 0; //principal_balance + interest_balance;



            $memberBillSummaryData['interest_adjusted'] = 0;



            $memberBillSummaryData['igst_total'] = 0;



            $memberBillSummaryData['cgst_total'] = 0;



            $memberBillSummaryData['sgst_total'] = 0;



            $memberBillSummaryData['tax_total'] = 0;



            $memberBillSummaryData['tax_paid'] = 0;



            $memberBillSummaryData['tax_adjusted'] = 0;



            $memberBillSummaryData['interest_free_amount'] = 0;



            $memberBillSummaryData['bill_tariff_type'] = $societyTariffParameterCheck['SocietyParameter']['tariff_id'];



            $memberBillSummaryData['bill_generated_date'] = $billGeneretedDate = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : '';



            $memberBillSummaryData['bill_due_date'] = (isset($postData['MemberBillGenerate']['bill_due_date']) && !empty($postData['MemberBillGenerate']['bill_due_date'])) ? trim($postData['MemberBillGenerate']['bill_due_date']) : NULL;
            $memberBillSummaryData['bill_end_date'] = date("Y-m-d",strtotime("+3 months",strtotime($billGeneretedDate)));


            $memberBillSummaryData['cdate'] = $this->Util->getDateTime();



            $memberBillSummaryData['udate'] = $this->Util->getDateTime();



            //print_r($billLedgerHeadSettings);die;











                  if($memberBillSummaryData['op_due_amount'] < 0){



                        $memberBillSummaryData['interest_on_due_amount']=0; 



                     }







            



            foreach ($memberTariffInfo['MemberTariff'] as $memberTariffData) {



                $billGenerateArr[$tariffCounter]['member_transfer'] = isset($memberTariffData['member_transfer']) ? $member_transfer : $member_transfer;



                $billGenerateArr[$tariffCounter]['member_id'] = isset($memberTariffData['member_id']) ? $memberTariffData['member_id'] : $memberId;

                $billGenerateArr[$tariffCounter]['bill_generated_date'] = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : NULL;



                $billGeneratedMonths = $billGenerateArr[$tariffCounter]['month'] = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : NULL;



                $billGenerateArr[$tariffCounter]['bill_number'] = isset($paymentBillNo) ? $paymentBillNo : 0;



                $billGenerateArr[$tariffCounter]['amount'] = isset($memberTariffData['amount']) ? $memberTariffData['amount']*$multiplyTariffAmountValue : 0;



                $billGenerateArr[$tariffCounter]['ledger_head_id'] = isset($memberTariffData['ledger_head_id']) ? $memberTariffData['ledger_head_id'] : 0;



                $billGenerateArr[$tariffCounter]['society_id'] = $this->societyloginID;



                $billGenerateArr[$tariffCounter]['bill_type'] = $billType;



                $billGenerateArr[$tariffCounter]['cdate'] = $this->Util->getDateTime();







                $memberBillSummaryData['monthly_amount'] += isset($memberTariffData['amount']) ? $memberTariffData['amount']*$multiplyTariffAmountValue : 0;



                //If taxes applicable



                if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['tax']) && in_array($memberTariffData['ledger_head_id'], $billLedgerHeadSettings['tax'])) {



                    $taxAmount = 0;



                    if ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] > 0) {



                        $igstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] / 100);



                        $memberBillSummaryData['igst_total'] = $memberBillSummaryData['igst_total'] + $igstTaxAmount;



                        $billGenerateArr[$tariffCounter]['igst_total'] = $igstTaxAmount;



                        $taxAmount += $igstTaxAmount;



                    }



                    if ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] > 0) {



                        $cgstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] / 100);



                        $memberBillSummaryData['cgst_total'] = $memberBillSummaryData['cgst_total'] + $cgstTaxAmount;



                        $billGenerateArr[$tariffCounter]['cgst_total'] = $cgstTaxAmount;



                        $taxAmount += $cgstTaxAmount;



                    }



                    if ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] > 0) {



                        $sgstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] / 100);



                        $memberBillSummaryData['sgst_total'] = $memberBillSummaryData['sgst_total'] + $sgstTaxAmount;



                        $billGenerateArr[$tariffCounter]['sgst_total'] = $sgstTaxAmount;



                        $taxAmount += $sgstTaxAmount;



                    }



                    $memberBillSummaryData['tax_total'] = $memberBillSummaryData['tax_total'] + $taxAmount;



                    $billGenerateArr[$tariffCounter]['tax_total'] = $taxAmount;



                    $memberBillSummaryData['monthly_amount'] += $taxAmount;



                    $memberBillSummaryData['tax_balance'] += $taxAmount;



                }

                //  $memberBillSummaryData['tax_balance'] += $memberBillSummaryData['op_tax_arrears'];



                if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['interestFree']) && in_array($memberTariffData['ledger_head_id'], $billLedgerHeadSettings['interestFree'])) {



                    $memberBillSummaryData['interest_free_amount'] += $memberTariffData['amount']*$multiplyTariffAmountValue;



                }



                $tariffCounter++;



            }







            if (isset($billGenerateArr) && count($billGenerateArr) > 0) {
                $memberBillSummaryData['tax_balance'] = $memberBillSummaryData['tax_balance']+$memberBillSummaryData['op_tax_arrears'];
                $memberBillSummaryData['monthly_principal_amount'] = $memberBillSummaryData['monthly_amount'] - $memberBillSummaryData['discount'];
                $memberBillSummaryData['monthly_bill_amount'] = $memberBillSummaryData['monthly_principal_amount'] + $memberBillSummaryData['interest_on_due_amount'];
                $memberBillSummaryData['amount_payable'] = $memberBillSummaryData['op_due_amount'] + $memberBillSummaryData['monthly_bill_amount'];

                 if($memberBillSummaryData['amount_payable'] < 0){
                    $memberBillSummaryData['op_tax_arrears'] =0;
                    $memberBillSummaryData['tax_balance'] 	 =0;
                     $memberBillSummaryData['principal_balance'] = ($memberBillSummaryData['op_principal_arrears'] + $memberBillSummaryData['monthly_principal_amount']) - $memberBillSummaryData['principal_paid'] - $memberBillSummaryData['tax_balance'];
                }else {
                 $memberBillSummaryData['principal_balance'] = ($memberBillSummaryData['op_principal_arrears'] + $memberBillSummaryData['monthly_principal_amount']) - $memberBillSummaryData['principal_paid'] - $memberBillSummaryData['tax_total'];
                }



                





                $memberBillSummaryData['interest_balance'] = ($memberBillSummaryData['op_interest_arrears'] + $memberBillSummaryData['interest_on_due_amount']) - $memberBillSummaryData['interest_paid'];



                $memberBillSummaryData['balance_amount'] = $memberBillSummaryData['principal_balance'] + $memberBillSummaryData['interest_balance'] + $memberBillSummaryData['tax_balance'];





                   $memberBillSummaryData['member_transfer'] = $member_transfer;

  



  



    if($societyTariffParameterCheck['SocietyParameter']['billing_frequency_id']==3 && isset($lastMonthBillDetails['tax_balance'])){
       $memberBillSummaryData['op_tax_arrears'] = ($lastMonthBillDetails['tax_balance'])? $lastMonthBillDetails['tax_balance']:0;
       $memberBillSummaryData['tax_balance'] = $memberBillSummaryData['op_tax_arrears'] + $memberBillSummaryData['tax_total'];
       $memberBillSummaryData['balance_amount'] = $memberBillSummaryData['principal_balance'] + $memberBillSummaryData['interest_balance'] + $memberBillSummaryData['tax_balance'];   
    } 







                $memberBillSummaryData['member_transfer'] = $member_transfer;







                $this->MemberBillGenerate->saveAll($billGenerateArr);



                $this->MemberBillSummary->create();



                if ($this->MemberBillSummary->save($memberBillSummaryData)) {



                    //Insert in bill summary



                    if ($this->MemberBillSummarySettlement->deleteAll(array('MemberBillSummarySettlement.member_id' => $memberId), false)){



                    }



                    if ($this->MemberBillSummarySettlement->save($memberBillSummaryData)) {                    



                    }



                    $responseError['error'] = 0;



                    $responseError['error_message'] = 'Member bill has been generated successfully.';



                } else {



                    $responseError['error'] = 1;



                    $responseError['error_message'] = 'Member society bill not generated.';



                }



            }



        } else {



            $responseError['error'] = 1;



            $responseError['error_message'] = 'Members Bill already exists in the system with the same month .';



        }



        return $responseError;



    }



    public function calculateMembersRegularBillByTariffQuarterly_bkOM($memberId, $memberTariffInfo, $paymentBillNo, $postData, $billLedgerHeadSettings, $memberDetails,$member_transfer) {



        $this->loadModel('JournalVoucher');



    

        $checkGenerateMonth     =  $postData['MemberBillGenerate']['month']-1;       







        $memberPaymentDetails = $this->Member->MemberPayment->find('first',  array('fields'=>array('MemberPayment.id','MemberPayment.bill_month'),'conditions' => array('MemberPayment.member_id' =>$memberId),'order'=>array('MemberPayment.id'=>'desc')));



        $check_LastPaymentMonth =  $memberPaymentDetails['MemberPayment']['bill_month'];







        $tariffCounter = 0;



        $billGenerateArr = array();



        $responseError = array();



        $billType = Configure::read('BillType.Regular');



        $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id'))));



        //$societyTariffParemeterSet = isset($societyTariffParameterCheck['SocietyParameter']['tariff_id']) ? $societyTariffParameterCheck['SocietyParameter']['tariff_id'] : 0;



        $multiplyTariffAmountValue = $this->SocietyBill->multiplyTariffAmountByBillingFrequency();



        



        $billGeneratedMonths = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : date('m');



        $societyMemberBillExists = $this->societyMemberBillExists($memberId, $billGeneratedMonths, $billType); //check same bill allready exists in system 



        if (empty($societyMemberBillExists)) {



            //$this->MemberBillGenerate->deleteAll(array('MemberBillGenerate.member_id' => $memberId, 'MemberBillGenerate.month' => $billGeneratedMonths,'MemberBillGenerate.bill_type'=>$billType));



            if ($this->MemberBillSummary->hasAny(array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.month' => $billGeneratedMonths, 'MemberBillSummary.bill_type' => $billType))) {



                //$this->MemberBillSummary->deleteAll(array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.month' => $billGeneratedMonths,'MemberBillSummary.bill_type'=>$billType));



            }



            



            



            //Check if last months bill details available



            /*$lastMonthBillDetails = $this->MemberBillSummarySettlement->find('first', array('conditions' => array('MemberBillSummarySettlement.member_id' => $memberId, 'MemberBillSummarySettlement.society_id' => $this->societyloginID, 'MemberBillSummarySettlement.bill_type' => $billType), 'order' => array('MemberBillSummarySettlement.id' => 'desc')));



            



            if(empty($lastMonthBillDetails)) {



                $lastMonthBillDetails = $this->MemberBillSummary->find('first', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType), 'order' => array('MemberBillSummary.id' => 'desc')));



            } else {



                $lastMonthBillDetails['MemberBillSummary'] = $lastMonthBillDetails['MemberBillSummarySettlement'];



                unset($lastMonthBillDetails['MemberBillSummarySettlement']);



            }*/



            



            $lastMonthBillDetails = $this->MemberBillSummary->find('first', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType), 'order' => array('MemberBillSummary.id' => 'desc')));

      



            $jvAdjustment = 0;



 



            if (isset($lastMonthBillDetails) && !empty($lastMonthBillDetails)) {



                



                $journalVoucherData = $this->JournalVoucher->find('all', array('conditions' => array('JournalVoucher.society_id' => $this->societyloginID,'JournalVoucher.voucher_date > '=> $lastMonthBillDetails['MemberBillSummary']['bill_generated_date'],'JournalVoucher.voucher_date <= '=> $lastMonthBillDetails['MemberBillSummary']['bill_due_date'],"OR" => array(



                "JournalVoucher.jv_debit_member_head_id" => $memberId,



                "JournalVoucher.jv_credit_member_head_id" => $memberId)



                )));



            }



            else{



                



                $journalVoucherData = array();



            }











            foreach ($journalVoucherData as $counter => $jVData){



                if (isset($jVData['JournalVoucher']['jv_credit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_credit_member_head_id'])) {



                            $jvAdjustment = $jvAdjustment - $jVData['JournalVoucher']['jv_amount_credited'];



                    }



                elseif (isset($jVData['JournalVoucher']['jv_debit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_debit_member_head_id'])) {



                            $jvAdjustment = $jvAdjustment + $jVData['JournalVoucher']['jv_amount_debited'];



                    }



            }



            if (isset($lastMonthBillDetails) && !empty($lastMonthBillDetails)) {



                $lastMonthBillDetails = $lastMonthBillDetails['MemberBillSummary'];



                



                $memberBillSummaryData['op_principal_arrears_original'] = $memberBillSummaryData['op_principal_arrears'] = $lastMonthBillDetails['principal_balance'];







                $memberBillSummaryData['jv_adjustment'] = $jvAdjustment;



                // adjustment  in case jv has been between after the last bill generation date



                $memberBillSummaryData['op_principal_arrears'] = $memberBillSummaryData['op_principal_arrears_original'] + $jvAdjustment;



                



                $memberBillSummaryData['op_due_amount'] = $lastMonthBillDetails['balance_amount']+$jvAdjustment;



                $memberBillSummaryData['tax_total'] = $lastMonthBillDetails['tax_total'];



          











                if($checkGenerateMonth == $check_LastPaymentMonth){



                $memberBillSummaryData['op_interest_arrears'] =0;



                $memberBillSummaryData['op_tax_arrears'] = 0; 



                } else {



                $memberBillSummaryData['op_interest_arrears'] = $lastMonthBillDetails['interest_balance'];



                $memberBillSummaryData['op_tax_arrears'] = $lastMonthBillDetails['op_tax_arrears'] + $lastMonthBillDetails['tax_balance'];



                }







                $memberBillSummaryData['tax_balance'] = $lastMonthBillDetails['tax_balance'];



                $memberBillSummaryData['interest_on_due_amount'] = 0;



                



                $lastMonthBillDetails['paymentDetails'] = array();



                if($memberBillSummaryData['op_due_amount'] > 0){



                    $memberBillSummaryData['interest_on_due_amount'] = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                }



 



                



                // Check if last month bill has payment and if yes then check if paid before due date



                    $billPaymentDetails = $this->MemberBillSettlement->find('first', array('conditions' => array('MemberBillSettlement.member_id' => $memberId, 'MemberBillSettlement.bill_summary_id' => $lastMonthBillDetails['id'])));



                    $billPaymentDetailsArray = array();



                    if(!empty($billPaymentDetails)) {



                        $billPaymentDetailsArray['principal_paid'] = $billPaymentDetails['MemberBillSettlement']['principal_paid'];



                        $billPaymentDetailsArray['interest_paid'] = $billPaymentDetails['MemberBillSettlement']['interest_paid'];



                        $billPaymentDetailsArray['tax_paid'] = $billPaymentDetails['MemberBillSettlement']['tax_paid'];



                        $billPaymentDetailsArray['total_paid'] = $billPaymentDetailsArray['principal_paid']+$billPaymentDetailsArray['interest_paid']+$billPaymentDetailsArray['tax_paid'];



                        $billPaymentDetailsArray['payment_date'] = $billPaymentDetails['MemberPayment']['payment_date'];



                    }



                    



                    if(!empty($billPaymentDetailsArray)) {



                        $lastMonthBillDetails['paymentDetails'] = $billPaymentDetailsArray;



                        $memberBillSummaryData['interest_on_due_amount'] += $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                    }



               } else {



                if (isset($memberDetails) && !empty($memberDetails)) {



                    $lastMonthBillDetails['principal_balance'] = isset($memberDetails['op_principal']) ? $memberDetails['op_principal'] : 0.00;



                    $lastMonthBillDetails['balance_amount'] = $memberDetails['op_principal'] + $memberDetails['op_interest'] + $memberDetails['op_tax'];



                    $lastMonthBillDetails['bill_due_date'] = isset($memberDetails['op_bill_due_date']) ? $memberDetails['op_bill_due_date'] : '';



                    $lastMonthBillDetails['bill_generated_date'] = isset($memberDetails['op_bill_date']) ? $memberDetails['op_bill_date'] : '';



                    $lastMonthBillDetails['interest_free_amount'] = 0;



                    $memberBillSummaryData['op_principal_arrears_original'] = $memberBillSummaryData['op_principal_arrears'] = isset($memberDetails['op_principal']) ? $memberDetails['op_principal'] : 0.00;







                    $memberBillSummaryData['jv_adjustment'] = $jvAdjustment;





                    $memberBillSummaryData['op_principal_arrears'] = $memberBillSummaryData['op_principal_arrears_original'] + $jvAdjustment;



                    $memberBillSummaryData['op_interest_arrears'] = isset($memberDetails['op_interest']) ? $memberDetails['op_interest'] : 0.00;



                    $memberBillSummaryData['op_tax_arrears'] = isset($memberDetails['op_tax']) ? $memberDetails['op_tax'] : 0.00;



                    $memberBillSummaryData['op_due_amount'] = $memberDetails['op_principal'] + $memberDetails['op_interest'] + $memberDetails['op_tax'];



                    $memberBillSummaryData['interest_on_due_amount'] = 0;



                    if (isset($lastMonthBillDetails['bill_due_date']) && $lastMonthBillDetails['bill_due_date'] != '0000-00-00' && $memberBillSummaryData['op_due_amount'] > 0) {



                        $int = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                        if ($int > 0){



                            $memberBillSummaryData['interest_on_due_amount'] = $int;



                        }



                    }



                    $memberBillSummaryData['tax_balance'] = 0;



                    //$memberBillSummaryData['op_tax_arrears'] = 0;



                    $memberBillSummaryData['tax_total'] = 0;



                } else {



                    $memberBillSummaryData['op_principal_arrears'] = 0;



                    $memberBillSummaryData['op_interest_arrears'] = 0;



                    $memberBillSummaryData['op_due_amount'] = 0;



                    $memberBillSummaryData['interest_on_due_amount'] = 0;



                    $memberBillSummaryData['op_tax_arrears'] = 0;



                    $memberBillSummaryData['tax_balance'] = 0;



                    $memberBillSummaryData['tax_total'] = 0;



                }



            }







            $memberBillSummaryData['bill_no'] = $paymentBillNo;



            $memberBillSummaryData['month'] = $billGeneratedMonths;



            $memberBillSummaryData['member_id'] = $memberId;



            $memberBillSummaryData['bill_type'] = $billType;



            $memberBillSummaryData['society_id'] = $this->societyloginID;



            $memberBillSummaryData['flat_no'] = isset($memberTariffInfo['Member']['flat_no']) ? $memberTariffInfo['Member']['flat_no'] : '';



            $memberBillSummaryData['monthly_amount'] = 0; //monthly member maintenance value



            $memberBillSummaryData['monthly_principal_amount'] = 0; //monthly_amount - discount;



            $memberBillSummaryData['discount'] = 0;



            $memberBillSummaryData['monthly_bill_amount'] = 0; //monthly_principal_amount + interest_on_due_amount;



            $memberBillSummaryData['amount_payable'] = 0; //op_due_amount + monthly_bill_amount;



            $memberBillSummaryData['principal_paid'] = 0;



            $memberBillSummaryData['interest_paid'] = 0;



            $memberBillSummaryData['principal_adjusted'] = 0;



            $memberBillSummaryData['interest_adjusted'] = 0;



            $memberBillSummaryData['principal_balance'] = 0; //op_principal_arrears + monthly_principal_amount - principal_paid;



            $memberBillSummaryData['interest_balance'] = 0; //op_interest_arrears + interest_on_due_amount - interest_paid;



            $memberBillSummaryData['balance_amount'] = 0; //principal_balance + interest_balance;



            $memberBillSummaryData['interest_adjusted'] = 0;



            $memberBillSummaryData['igst_total'] = 0;



            $memberBillSummaryData['cgst_total'] = 0;



            $memberBillSummaryData['sgst_total'] = 0;



            $memberBillSummaryData['tax_total'] = 0;



            $memberBillSummaryData['tax_paid'] = 0;



            $memberBillSummaryData['tax_adjusted'] = 0;



            $memberBillSummaryData['interest_free_amount'] = 0;



            $memberBillSummaryData['bill_tariff_type'] = $societyTariffParameterCheck['SocietyParameter']['tariff_id'];



            $memberBillSummaryData['bill_generated_date'] = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : '';



            $memberBillSummaryData['bill_due_date'] = (isset($postData['MemberBillGenerate']['bill_due_date']) && !empty($postData['MemberBillGenerate']['bill_due_date'])) ? trim($postData['MemberBillGenerate']['bill_due_date']) : NULL;



            $memberBillSummaryData['cdate'] = $this->Util->getDateTime();



            $memberBillSummaryData['udate'] = $this->Util->getDateTime();



            //print_r($billLedgerHeadSettings);die;











                  if($memberBillSummaryData['op_due_amount'] < 0){



                        $memberBillSummaryData['interest_on_due_amount']=0; 



                     }







            



            foreach ($memberTariffInfo['MemberTariff'] as $memberTariffData) {



                $billGenerateArr[$tariffCounter]['member_transfer'] = isset($memberTariffData['member_transfer']) ? $member_transfer : $member_transfer;



                $billGenerateArr[$tariffCounter]['member_id'] = isset($memberTariffData['member_id']) ? $memberTariffData['member_id'] : $memberId;

                $billGenerateArr[$tariffCounter]['bill_generated_date'] = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : NULL;



                $billGeneratedMonths = $billGenerateArr[$tariffCounter]['month'] = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : NULL;



                $billGenerateArr[$tariffCounter]['bill_number'] = isset($paymentBillNo) ? $paymentBillNo : 0;



                $billGenerateArr[$tariffCounter]['amount'] = isset($memberTariffData['amount']) ? $memberTariffData['amount']*$multiplyTariffAmountValue : 0;



                $billGenerateArr[$tariffCounter]['ledger_head_id'] = isset($memberTariffData['ledger_head_id']) ? $memberTariffData['ledger_head_id'] : 0;



                $billGenerateArr[$tariffCounter]['society_id'] = $this->societyloginID;



                $billGenerateArr[$tariffCounter]['bill_type'] = $billType;



                $billGenerateArr[$tariffCounter]['cdate'] = $this->Util->getDateTime();







                $memberBillSummaryData['monthly_amount'] += isset($memberTariffData['amount']) ? $memberTariffData['amount']*$multiplyTariffAmountValue : 0;



                //If taxes applicable



                if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['tax']) && in_array($memberTariffData['ledger_head_id'], $billLedgerHeadSettings['tax'])) {



                    $taxAmount = 0;



                    if ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] > 0) {



                        $igstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] / 100);



                        $memberBillSummaryData['igst_total'] = $memberBillSummaryData['igst_total'] + $igstTaxAmount;



                        $billGenerateArr[$tariffCounter]['igst_total'] = $igstTaxAmount;



                        $taxAmount += $igstTaxAmount;



                    }



                    if ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] > 0) {



                        $cgstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] / 100);



                        $memberBillSummaryData['cgst_total'] = $memberBillSummaryData['cgst_total'] + $cgstTaxAmount;



                        $billGenerateArr[$tariffCounter]['cgst_total'] = $cgstTaxAmount;



                        $taxAmount += $cgstTaxAmount;



                    }



                    if ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] > 0) {



                        $sgstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] / 100);



                        $memberBillSummaryData['sgst_total'] = $memberBillSummaryData['sgst_total'] + $sgstTaxAmount;



                        $billGenerateArr[$tariffCounter]['sgst_total'] = $sgstTaxAmount;



                        $taxAmount += $sgstTaxAmount;



                    }



                    $memberBillSummaryData['tax_total'] = $memberBillSummaryData['tax_total'] + $taxAmount;



                    $billGenerateArr[$tariffCounter]['tax_total'] = $taxAmount;



                    $memberBillSummaryData['monthly_amount'] += $taxAmount;



                    $memberBillSummaryData['tax_balance'] += $taxAmount;



                }

                //  $memberBillSummaryData['tax_balance'] += $memberBillSummaryData['op_tax_arrears'];



                if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['interestFree']) && in_array($memberTariffData['ledger_head_id'], $billLedgerHeadSettings['interestFree'])) {



                    $memberBillSummaryData['interest_free_amount'] += $memberTariffData['amount']*$multiplyTariffAmountValue;



                }



                $tariffCounter++;



            }







            if (isset($billGenerateArr) && count($billGenerateArr) > 0) {



                $memberBillSummaryData['tax_balance'] = $memberBillSummaryData['tax_balance']+$memberBillSummaryData['op_tax_arrears'];



                $memberBillSummaryData['monthly_principal_amount'] = $memberBillSummaryData['monthly_amount'] - $memberBillSummaryData['discount'];



                $memberBillSummaryData['monthly_bill_amount'] = $memberBillSummaryData['monthly_principal_amount'] + $memberBillSummaryData['interest_on_due_amount'];



                $memberBillSummaryData['amount_payable'] = $memberBillSummaryData['op_due_amount'] + $memberBillSummaryData['monthly_bill_amount'];



                 if($memberBillSummaryData['amount_payable'] < 0){

                    $memberBillSummaryData['op_tax_arrears'] =0;

                    $memberBillSummaryData['tax_balance'] 	 =0;

                

                 $memberBillSummaryData['principal_balance'] = ($memberBillSummaryData['op_principal_arrears'] + $memberBillSummaryData['monthly_principal_amount']) - $memberBillSummaryData['principal_paid'] - $memberBillSummaryData['tax_balance'];

                                    

                }else {

                 $memberBillSummaryData['principal_balance'] = ($memberBillSummaryData['op_principal_arrears'] + $memberBillSummaryData['monthly_principal_amount']) - $memberBillSummaryData['principal_paid'] - $memberBillSummaryData['tax_total'];

                }



                





                $memberBillSummaryData['interest_balance'] = ($memberBillSummaryData['op_interest_arrears'] + $memberBillSummaryData['interest_on_due_amount']) - $memberBillSummaryData['interest_paid'];



                $memberBillSummaryData['balance_amount'] = $memberBillSummaryData['principal_balance'] + $memberBillSummaryData['interest_balance'] + $memberBillSummaryData['tax_balance'];





                   $memberBillSummaryData['member_transfer'] = $member_transfer;

  



  



    if($societyTariffParameterCheck['SocietyParameter']['billing_frequency_id']==3 && isset($lastMonthBillDetails['tax_balance'])){



        $memberBillSummaryData['op_tax_arrears'] = ($lastMonthBillDetails['tax_balance'])? $lastMonthBillDetails['tax_balance']:0;

   $memberBillSummaryData['tax_balance'] = $memberBillSummaryData['op_tax_arrears'] + $memberBillSummaryData['tax_total'];

     

   $memberBillSummaryData['balance_amount'] = $memberBillSummaryData['principal_balance'] + $memberBillSummaryData['interest_balance'] + $memberBillSummaryData['tax_balance'];   

    

    } 



//print_r($lastMonthBillDetails);









                $memberBillSummaryData['member_transfer'] = $member_transfer;







                $this->MemberBillGenerate->saveAll($billGenerateArr);



                $this->MemberBillSummary->create();



                if ($this->MemberBillSummary->save($memberBillSummaryData)) {



                    //Insert in bill summary



                    if ($this->MemberBillSummarySettlement->deleteAll(array('MemberBillSummarySettlement.member_id' => $memberId), false)){



                    }



                    if ($this->MemberBillSummarySettlement->save($memberBillSummaryData)) {                    



                    }



                    $responseError['error'] = 0;



                    $responseError['error_message'] = 'Member bill has been generated successfully.';



                } else {



                    $responseError['error'] = 1;



                    $responseError['error_message'] = 'Member society bill not generated.';



                }



            }



        } else {



            $responseError['error'] = 1;



            $responseError['error_message'] = 'Members Bill already exists in the system with the same month .';



        }



        return $responseError;



    }







    public function calculateMembersRegularBillByTariff_olds($memberId, $memberTariffInfo, $paymentBillNo, $postData, $billLedgerHeadSettings, $memberDetails,$member_transfer) {



        $this->loadModel('JournalVoucher');



        



        $checkGenerateMonth     =  $postData['MemberBillGenerate']['month']-1;       







        $memberPaymentDetails = $this->Member->MemberPayment->find('first',  array('fields'=>array('MemberPayment.id','MemberPayment.bill_month'),'conditions' => array('MemberPayment.member_id' =>$memberId),'order'=>array('MemberPayment.id'=>'desc')));



        $check_LastPaymentMonth =  $memberPaymentDetails['MemberPayment']['bill_month'];







        $tariffCounter = 0;



        $billGenerateArr = array();



        $responseError = array();



        $billType = Configure::read('BillType.Regular');



        $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id'))));



        //$societyTariffParemeterSet = isset($societyTariffParameterCheck['SocietyParameter']['tariff_id']) ? $societyTariffParameterCheck['SocietyParameter']['tariff_id'] : 0;



        $multiplyTariffAmountValue = $this->SocietyBill->multiplyTariffAmountByBillingFrequency();



        



        $billGeneratedMonths = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : date('m');



        $societyMemberBillExists = $this->societyMemberBillExists($memberId, $billGeneratedMonths, $billType); //check same bill allready exists in system 



        if (empty($societyMemberBillExists)) {



            //$this->MemberBillGenerate->deleteAll(array('MemberBillGenerate.member_id' => $memberId, 'MemberBillGenerate.month' => $billGeneratedMonths,'MemberBillGenerate.bill_type'=>$billType));



            if ($this->MemberBillSummary->hasAny(array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.month' => $billGeneratedMonths, 'MemberBillSummary.bill_type' => $billType))) {



                //$this->MemberBillSummary->deleteAll(array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.month' => $billGeneratedMonths,'MemberBillSummary.bill_type'=>$billType));



            }



            



            



            //Check if last months bill details available



            /*$lastMonthBillDetails = $this->MemberBillSummarySettlement->find('first', array('conditions' => array('MemberBillSummarySettlement.member_id' => $memberId, 'MemberBillSummarySettlement.society_id' => $this->societyloginID, 'MemberBillSummarySettlement.bill_type' => $billType), 'order' => array('MemberBillSummarySettlement.id' => 'desc')));



            



            if(empty($lastMonthBillDetails)) {



                $lastMonthBillDetails = $this->MemberBillSummary->find('first', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType), 'order' => array('MemberBillSummary.id' => 'desc')));



            } else {



                $lastMonthBillDetails['MemberBillSummary'] = $lastMonthBillDetails['MemberBillSummarySettlement'];



                unset($lastMonthBillDetails['MemberBillSummarySettlement']);



            }*/



			



			$lastMonthBillDetails = $this->MemberBillSummary->find('first', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType), 'order' => array('MemberBillSummary.id' => 'desc')));



                        



            $jvAdjustment = 0;



 



            if (isset($lastMonthBillDetails) && !empty($lastMonthBillDetails)) {



                



                $journalVoucherData = $this->JournalVoucher->find('all', array('conditions' => array('JournalVoucher.society_id' => $this->societyloginID,'JournalVoucher.voucher_date > '=> $lastMonthBillDetails['MemberBillSummary']['bill_generated_date'],'JournalVoucher.voucher_date <= '=> $lastMonthBillDetails['MemberBillSummary']['bill_due_date'],"OR" => array(



                "JournalVoucher.jv_debit_member_head_id" => $memberId,



                "JournalVoucher.jv_credit_member_head_id" => $memberId)



                )));



            }



            else{



                



                $journalVoucherData = array();



            }











            foreach ($journalVoucherData as $counter => $jVData){



                if (isset($jVData['JournalVoucher']['jv_credit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_credit_member_head_id'])) {



                            $jvAdjustment = $jvAdjustment - $jVData['JournalVoucher']['jv_amount_credited'];



                    }



                elseif (isset($jVData['JournalVoucher']['jv_debit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_debit_member_head_id'])) {



                            $jvAdjustment = $jvAdjustment + $jVData['JournalVoucher']['jv_amount_debited'];



                    }



            }



            if (isset($lastMonthBillDetails) && !empty($lastMonthBillDetails)) {



                $lastMonthBillDetails = $lastMonthBillDetails['MemberBillSummary'];



                



                $memberBillSummaryData['op_principal_arrears_original'] = $memberBillSummaryData['op_principal_arrears'] = $lastMonthBillDetails['principal_balance'];







                $memberBillSummaryData['jv_adjustment'] = $jvAdjustment;



                // adjustment  in case jv has been between after the last bill generation date



                $memberBillSummaryData['op_principal_arrears'] = $memberBillSummaryData['op_principal_arrears_original'] + $jvAdjustment;



                



                $memberBillSummaryData['op_due_amount'] = $lastMonthBillDetails['balance_amount']+$jvAdjustment;



                $memberBillSummaryData['tax_total'] = $lastMonthBillDetails['tax_total'];



          











                if($checkGenerateMonth == $check_LastPaymentMonth){



                $memberBillSummaryData['op_interest_arrears'] =0;



                $memberBillSummaryData['op_tax_arrears'] = 0; 



                } else {



                $memberBillSummaryData['op_interest_arrears'] = $lastMonthBillDetails['interest_balance'];



                $memberBillSummaryData['op_tax_arrears'] = $lastMonthBillDetails['op_tax_arrears'] + $lastMonthBillDetails['tax_total'];



                }







                $memberBillSummaryData['tax_balance'] = $lastMonthBillDetails['tax_balance'];



                $memberBillSummaryData['interest_on_due_amount'] = 0;



                



                $lastMonthBillDetails['paymentDetails'] = array();



                if($memberBillSummaryData['op_due_amount'] > 0){



                    $memberBillSummaryData['interest_on_due_amount'] = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                }



 



                



                // Check if last month bill has payment and if yes then check if paid before due date



                    $billPaymentDetails = $this->MemberBillSettlement->find('first', array('conditions' => array('MemberBillSettlement.member_id' => $memberId, 'MemberBillSettlement.bill_summary_id' => $lastMonthBillDetails['id'])));



                    $billPaymentDetailsArray = array();



                    if(!empty($billPaymentDetails)) {



                        $billPaymentDetailsArray['principal_paid'] = $billPaymentDetails['MemberBillSettlement']['principal_paid'];



                        $billPaymentDetailsArray['interest_paid'] = $billPaymentDetails['MemberBillSettlement']['interest_paid'];



                        $billPaymentDetailsArray['tax_paid'] = $billPaymentDetails['MemberBillSettlement']['tax_paid'];



                        $billPaymentDetailsArray['total_paid'] = $billPaymentDetailsArray['principal_paid']+$billPaymentDetailsArray['interest_paid']+$billPaymentDetailsArray['tax_paid'];



                        $billPaymentDetailsArray['payment_date'] = $billPaymentDetails['MemberPayment']['payment_date'];



                    }



                    



                    if(!empty($billPaymentDetailsArray)) {



                        $lastMonthBillDetails['paymentDetails'] = $billPaymentDetailsArray;



                        $memberBillSummaryData['interest_on_due_amount'] += $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                    }



               } else {



                if (isset($memberDetails) && !empty($memberDetails)) {



                    $lastMonthBillDetails['principal_balance'] = isset($memberDetails['op_principal']) ? $memberDetails['op_principal'] : 0.00;



                    $lastMonthBillDetails['balance_amount'] = $memberDetails['op_principal'] + $memberDetails['op_interest'] + $memberDetails['op_tax'];



                    $lastMonthBillDetails['bill_due_date'] = isset($memberDetails['op_bill_due_date']) ? $memberDetails['op_bill_due_date'] : '';



                    $lastMonthBillDetails['bill_generated_date'] = isset($memberDetails['op_bill_date']) ? $memberDetails['op_bill_date'] : '';



                    $lastMonthBillDetails['interest_free_amount'] = 0;



                    $memberBillSummaryData['op_principal_arrears_original'] = $memberBillSummaryData['op_principal_arrears'] = isset($memberDetails['op_principal']) ? $memberDetails['op_principal'] : 0.00;







                    $memberBillSummaryData['jv_adjustment'] = $jvAdjustment;







                    $memberBillSummaryData['op_principal_arrears'] = $memberBillSummaryData['op_principal_arrears_original'] + $jvAdjustment;



                    $memberBillSummaryData['op_interest_arrears'] = isset($memberDetails['op_interest']) ? $memberDetails['op_interest'] : 0.00;



                    $memberBillSummaryData['op_tax_arrears'] = isset($memberDetails['op_tax']) ? $memberDetails['op_tax'] : 0.00;



                    $memberBillSummaryData['op_due_amount'] = $memberDetails['op_principal'] + $memberDetails['op_interest'] + $memberDetails['op_tax'];



                    $memberBillSummaryData['interest_on_due_amount'] = 0;



                    if (isset($lastMonthBillDetails['bill_due_date']) && $lastMonthBillDetails['bill_due_date'] != '0000-00-00' && $memberBillSummaryData['op_due_amount'] > 0) {



                        $int = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                        if ($int > 0){



                            $memberBillSummaryData['interest_on_due_amount'] = $int;



                        }



                    }



                    $memberBillSummaryData['tax_balance'] = 0;



                    //$memberBillSummaryData['op_tax_arrears'] = 0;



                    $memberBillSummaryData['tax_total'] = 0;



                } else {



                    $memberBillSummaryData['op_principal_arrears'] = 0;



                    $memberBillSummaryData['op_interest_arrears'] = 0;



                    $memberBillSummaryData['op_due_amount'] = 0;



                    $memberBillSummaryData['interest_on_due_amount'] = 0;



                    $memberBillSummaryData['op_tax_arrears'] = 0;



                    $memberBillSummaryData['tax_balance'] = 0;



                    $memberBillSummaryData['tax_total'] = 0;



                }



            }







            $memberBillSummaryData['bill_no'] = $paymentBillNo;



            $memberBillSummaryData['month'] = $billGeneratedMonths;



            $memberBillSummaryData['member_id'] = $memberId;



            $memberBillSummaryData['bill_type'] = $billType;



            $memberBillSummaryData['society_id'] = $this->societyloginID;



            $memberBillSummaryData['flat_no'] = isset($memberTariffInfo['Member']['flat_no']) ? $memberTariffInfo['Member']['flat_no'] : '';



            $memberBillSummaryData['monthly_amount'] = 0; //monthly member maintenance value



            $memberBillSummaryData['monthly_principal_amount'] = 0; //monthly_amount - discount;



            $memberBillSummaryData['discount'] = 0;



            $memberBillSummaryData['monthly_bill_amount'] = 0; //monthly_principal_amount + interest_on_due_amount;



            $memberBillSummaryData['amount_payable'] = 0; //op_due_amount + monthly_bill_amount;



            $memberBillSummaryData['principal_paid'] = 0;



            $memberBillSummaryData['interest_paid'] = 0;



            $memberBillSummaryData['principal_adjusted'] = 0;



            $memberBillSummaryData['interest_adjusted'] = 0;



            $memberBillSummaryData['principal_balance'] = 0; //op_principal_arrears + monthly_principal_amount - principal_paid;



            $memberBillSummaryData['interest_balance'] = 0; //op_interest_arrears + interest_on_due_amount - interest_paid;



            $memberBillSummaryData['balance_amount'] = 0; //principal_balance + interest_balance;



            $memberBillSummaryData['interest_adjusted'] = 0;



            $memberBillSummaryData['igst_total'] = 0;



            $memberBillSummaryData['cgst_total'] = 0;



            $memberBillSummaryData['sgst_total'] = 0;



            $memberBillSummaryData['tax_total'] = 0;



            $memberBillSummaryData['tax_paid'] = 0;



            $memberBillSummaryData['tax_adjusted'] = 0;



            $memberBillSummaryData['interest_free_amount'] = 0;



            $memberBillSummaryData['bill_tariff_type'] = $societyTariffParameterCheck['SocietyParameter']['tariff_id'];



            $memberBillSummaryData['bill_generated_date'] = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : '';



            $memberBillSummaryData['bill_due_date'] = (isset($postData['MemberBillGenerate']['bill_due_date']) && !empty($postData['MemberBillGenerate']['bill_due_date'])) ? trim($postData['MemberBillGenerate']['bill_due_date']) : NULL;



            $memberBillSummaryData['cdate'] = $this->Util->getDateTime();



            $memberBillSummaryData['udate'] = $this->Util->getDateTime();



            //print_r($billLedgerHeadSettings);die;











                  if($memberBillSummaryData['op_due_amount'] < 0){



                        $memberBillSummaryData['interest_on_due_amount']=0; 



                     }







            



            foreach ($memberTariffInfo['MemberTariff'] as $memberTariffData) {



                $billGenerateArr[$tariffCounter]['member_transfer'] = isset($memberTariffData['member_transfer']) ? $member_transfer : $member_transfer;



                $billGenerateArr[$tariffCounter]['member_id'] = isset($memberTariffData['member_id']) ? $memberTariffData['member_id'] : $memberId;

                $billGenerateArr[$tariffCounter]['bill_generated_date'] = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : NULL;



                $billGeneratedMonths = $billGenerateArr[$tariffCounter]['month'] = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : NULL;



                $billGenerateArr[$tariffCounter]['bill_number'] = isset($paymentBillNo) ? $paymentBillNo : 0;



                $billGenerateArr[$tariffCounter]['amount'] = isset($memberTariffData['amount']) ? $memberTariffData['amount']*$multiplyTariffAmountValue : 0;



                $billGenerateArr[$tariffCounter]['ledger_head_id'] = isset($memberTariffData['ledger_head_id']) ? $memberTariffData['ledger_head_id'] : 0;



                $billGenerateArr[$tariffCounter]['society_id'] = $this->societyloginID;



                $billGenerateArr[$tariffCounter]['bill_type'] = $billType;



                $billGenerateArr[$tariffCounter]['cdate'] = $this->Util->getDateTime();







                $memberBillSummaryData['monthly_amount'] += isset($memberTariffData['amount']) ? $memberTariffData['amount']*$multiplyTariffAmountValue : 0;



                //If taxes applicable



                if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['tax']) && in_array($memberTariffData['ledger_head_id'], $billLedgerHeadSettings['tax'])) {



                    $taxAmount = 0;



                    if ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] > 0) {



                        $igstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] / 100);



                        $memberBillSummaryData['igst_total'] = $memberBillSummaryData['igst_total'] + $igstTaxAmount;



                        $billGenerateArr[$tariffCounter]['igst_total'] = $igstTaxAmount;



                        $taxAmount += $igstTaxAmount;



                    }



                    if ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] > 0) {



                        $cgstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] / 100);



                        $memberBillSummaryData['cgst_total'] = $memberBillSummaryData['cgst_total'] + $cgstTaxAmount;



                        $billGenerateArr[$tariffCounter]['cgst_total'] = $cgstTaxAmount;



                        $taxAmount += $cgstTaxAmount;



                    }



                    if ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] > 0) {



                        $sgstTaxAmount = ($memberTariffData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] / 100);



                        $memberBillSummaryData['sgst_total'] = $memberBillSummaryData['sgst_total'] + $sgstTaxAmount;



                        $billGenerateArr[$tariffCounter]['sgst_total'] = $sgstTaxAmount;



                        $taxAmount += $sgstTaxAmount;



                    }



                    $memberBillSummaryData['tax_total'] = $memberBillSummaryData['tax_total'] + $taxAmount;



                    $billGenerateArr[$tariffCounter]['tax_total'] = $taxAmount;



                    $memberBillSummaryData['monthly_amount'] += $taxAmount;



                    $memberBillSummaryData['tax_balance'] += $taxAmount;



                }



                if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['interestFree']) && in_array($memberTariffData['ledger_head_id'], $billLedgerHeadSettings['interestFree'])) {



                    $memberBillSummaryData['interest_free_amount'] += $memberTariffData['amount']*$multiplyTariffAmountValue;



                }



                $tariffCounter++;



            }



            if (isset($billGenerateArr) && count($billGenerateArr) > 0) {



                $memberBillSummaryData['monthly_principal_amount'] = $memberBillSummaryData['monthly_amount'] - $memberBillSummaryData['discount'];



                $memberBillSummaryData['monthly_bill_amount'] = $memberBillSummaryData['monthly_principal_amount'] + $memberBillSummaryData['interest_on_due_amount'];



                $memberBillSummaryData['amount_payable'] = $memberBillSummaryData['op_due_amount'] + $memberBillSummaryData['monthly_bill_amount'];



                if($memberBillSummaryData['amount_payable'] < 0){

                   $memberBillSummaryData['tax_total']=0;

                  $memberBillSummaryData['tax_balance']=0;

                    $memberBillSummaryData['op_tax_arrears'] =0;



                }

                

                $memberBillSummaryData['principal_balance'] = ($memberBillSummaryData['op_principal_arrears'] + $memberBillSummaryData['monthly_principal_amount']) - $memberBillSummaryData['principal_paid'] - $memberBillSummaryData['tax_total'];



                $memberBillSummaryData['interest_balance'] = ($memberBillSummaryData['op_interest_arrears'] + $memberBillSummaryData['interest_on_due_amount']) - $memberBillSummaryData['interest_paid'];



                $memberBillSummaryData['balance_amount'] = $memberBillSummaryData['principal_balance'] + $memberBillSummaryData['interest_balance'] + $memberBillSummaryData['tax_balance'];



               // $memberBillSummaryData['op_tax_arrears'] = ($memberBillSummaryData['op_tax_arrears'] + $memberBillSummaryData['tax_total']);



                   $memberBillSummaryData['member_transfer'] = $member_transfer;

  











                $memberBillSummaryData['member_transfer'] = $member_transfer;



                $this->MemberBillGenerate->saveAll($billGenerateArr);



                $this->MemberBillSummary->create();



                if ($this->MemberBillSummary->save($memberBillSummaryData)) {



                    //Insert in bill summary



                    if ($this->MemberBillSummarySettlement->deleteAll(array('MemberBillSummarySettlement.member_id' => $memberId), false)){



                    }



                    if ($this->MemberBillSummarySettlement->save($memberBillSummaryData)) {                    



                    }



                    $responseError['error'] = 0;



                    $responseError['error_message'] = 'Member bill has been generated successfully.';



                } else {



                    $responseError['error'] = 1;



                    $responseError['error_message'] = 'Member society bill not generated.';



                }



            }



        } else {



            $responseError['error'] = 1;



            $responseError['error_message'] = 'Members Bill already exists in the system with the same month .';



        }



        return $responseError;



    }

    
    public function getInterestOnDueAmountOnFirstBill($principalAmt,$intererstAmt,$taxAmount,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate){
        $interestAmt = 0;
        // print_r($societyTariffParameterCheck);
        // echo "$principalAmt,$intererstAmt,$taxAmount,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate";
        $menthodId = $societyTariffParameterCheck['SocietyParameter']['method_id'];
        $totalBal = $principalAmt+$intererstAmt+$taxAmount;
        if($menthodId == 5){
            $postData['MemberBillGenerate']['bill_generated_date'] = $billGeneratedDate;
            $lastMonthBillDetails['bill_generated_date'] =  $lastBillGeneratedDate;
            $lastMonthBillDetails['principal_balance'] = $principalAmt;
            $lastMonthBillDetails['balance_amount'] = $totalBal;
            $interestAmt = $this->completeMonths($societyTariffParameterCheck, $lastMonthBillDetails,$postData);
        }   
        $days = 0; // set only to pass as parameter 
        if($menthodId == Configure::read('InterestMethod.DelayDays')){
            $interestAmt = $interestAmt + $this->delayDays($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate,$days);
        }
        else if($menthodId == Configure::read('InterestMethod.DelayMonths')){
            $interestAmt = $interestAmt + $this->delayMonths($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate,$days);
        }
        else if($menthodId == Configure::read('InterestMethod.CompleteCycleDays')){
            $interestAmt = $interestAmt + $this->completeCycleDays($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate,$days);
        }
        else if($menthodId == Configure::read('InterestMethod.CompleteCycleMonthly')){
            $interestAmt = $interestAmt + $this->completeCycleMonthly($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate,$days);
        }   
        return ($interestAmt < 0) ? 0 : $interestAmt;
    }

    public function calculatAmountAvailable($memberId,$societyId,$chqRetPayId,$lastBillGeneratedDate,$lastBillId,$monthlyBills,&$principalAmt,&$intererstAmt,&$taxAmount,$billType,$latestTransferNo){
        // echo $billGeneratedDate;
        $paymentCondition = array('MemberPayment.member_id' => $memberId,'MemberPayment.payment_date < ' => $lastBillGeneratedDate, 'MemberPayment.society_id' => $societyId,'MemberPayment.bill_type'=> $billType,'MemberPayment.member_transfer' => $latestTransferNo,'NOT' => array('MemberPayment.id' => $chqRetPayId));
        $memberPaymentData = $this->MemberPayment->find('all', array('conditions' => $paymentCondition,'order'=>array('MemberPayment.payment_date'=>'asc')));          
        if($billType == 'reg'){
            $memberPaymentData = $this->getCreditedJvData($lastBillGeneratedDate,$memberId,$societyId,$memberPaymentData,$latestTransferNo);
        }
        // print_r($memberPaymentData);
        $principalAmt = 0;
        $intererstAmt = 0;
        $taxAmount = 0;
        $paymentIdUsed = [];
        $amountPaid = 0;
        // echo "<pre>";
        $tempSummary =[];                
        if(!empty($monthlyBills)){
            foreach($monthlyBills as $index => $billData){
            //      echo "hii - $lastBillGeneratedDate ";
                $monthlyprincipalAmt = $billData['MemberBillSummary']['monthly_amount'] - $billData['MemberBillSummary']['discount'];
                $monthlyBillAmt = $billData['MemberBillSummary']['monthly_principal_amount'] + $billData['MemberBillSummary']['interest_on_due_amount'];

                $jvDebitedAmt = $this->getDebitedJvAmount($currentBillMonthGeneratedDate,$currentBillEndDate,$memberId,$societyId,$latestTransferNo);
                $tempPrincipal = $monthlyprincipalAmt + $jvDebitedAmt - $billData['MemberBillSummary']['tax_total'] - $billData['MemberBillSummary']['principal_adjusted'];
                // echo "<br>$tempPrincipal <br>";
                $principalAmt = $principalAmt + $tempPrincipal;
                
                $tempInterest =  ($billData['MemberBillSummary']['interest_on_due_amount']) - $billData['MemberBillSummary']['interest_adjusted'];
                $intererstAmt = $intererstAmt + $tempInterest;               
                // echo "$tempInterest <br>";
                $tempTax =  $billData['MemberBillSummary']['tax_total'] - $billData['MemberBillSummary']['tax_adjusted'];
                $taxAmount = $taxAmount + $tempTax;
                // echo "$taxAmount <br>";
                
                if($index == 0){
                    $principalAmt = $principalAmt + $billData['MemberBillSummary']['op_principal_arrears'];
                    $taxAmount = $taxAmount + $billData['MemberBillSummary']['op_tax_arrears'];
                    $intererstAmt = $intererstAmt + $billData['MemberBillSummary']['op_interest_arrears'];
                }
                $billId = $billData['MemberBillSummary']['id'];
         //         echo "<br> inside of nre $amountPaid $lastBillGeneratedDate  $principalAmt $intererstAmt $taxAmount <br>";
                
                if(!empty($memberPaymentData)){
                    foreach($memberPaymentData as $paymentData){
                        $paymentId = $paymentData['MemberPayment']['id'];
                        if(!in_array($paymentId,$paymentIdUsed) &&  $amountPaid <= 0 && ($principalAmt > 0 || $intererstAmt || $taxAmount > 0) ){
                            $amountPaid = $amountPaid +$paymentData['MemberPayment']['amount_paid'];
                            array_push($paymentIdUsed,$paymentId);
                        }
                        # here
                        $this->calculation($amountPaid,$principalAmt,$intererstAmt,$taxAmount);                    
                    }
                }
                $tempSummary[$index]['principal'] = $principalAmt;
                $tempSummary[$index]['interest'] = $intererstAmt;
                $tempSummary[$index]['tax'] = $taxAmount;
                // print_r($paymentIdUsed);
                // echo "<br>$billId == $lastBillId<br>";
                if($billId == $lastBillId) break;
            }
        }
        return $tempSummary;
    }
    
    public function deductAndUpdate(&$amountPaid,&$billData){
        // testbyom
        
//         echo $amountPaid;
        $cnt = count($billData);
        foreach($billData as $index => $data){
            if($amountPaid > 0){
                if($data['tax'] > 0){
                    $tempTaxPaid = $amountPaid - $data['tax'];
                    if($tempTaxPaid > 0){
                        $taxPaid = $data['tax'];
                        $billData[$index]['tax'] = 0; 
                        $amountPaid = $tempTaxPaid;
                    }
                    else{
                        $billData[$index]['tax'] = abs($tempTaxPaid);
                        $taxPaid = abs($amountPaid);
                        $amountPaid  = 0;
                    }
                    
                    for($t = ($index + 1); $t < $cnt ; $t++ ){
                        $billData[$t]['tax'] = $billData[$t]['tax'] - $taxPaid;
                    }
                }
            }
            
            if($amountPaid > 0){
                if($data['interest'] > 0){
                    $tempInterestPaid = $amountPaid - $data['interest'];
                    if($tempInterestPaid > 0){
                        $interestPaid = $data['interest'];
                        $billData[$index]['interest'] = 0;    
                        $amountPaid = $tempInterestPaid;
                    }
                    else{
                        $billData[$index]['interest'] = abs($tempInterestPaid);
                        $interestPaid = abs($amountPaid);
                        $amountPaid = 0;
                    }
                    
                    for($i = ($index + 1); $i < $cnt ; $i++ ){
                        $billData[$i]['interest'] = $billData[$i]['interest'] - $interestPaid;
                    }
                }
            }
             // echo " amout $amountPaid";
            if($amountPaid > 0){
                if($data['principal'] > 0){
                    $tempPrincipalPaid = $amountPaid - $data['principal'];
                    if($tempPrincipalPaid > 0){
                        $principalPaid = $data['principal'];
                        $billData[$index]['principal'] = 0;    
                        $amountPaid = $tempPrincipalPaid;
                    }
                    else{
                        $billData[$index]['principal'] = abs($tempPrincipalPaid);
                        $principalPaid = abs($amountPaid);
                        $amountPaid = 0;
                    }
                    
                    for($p = ($index + 1); $p < $cnt ; $p++ ){
                        $billData[$p]['principal'] = $billData[$p]['principal'] - $principalPaid;
                    }
                }
            }  
            
            
        }
         // print_r($billData);
    }

    public function getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData,$memberData = [],$memberBillSummary = [],$billType,$latestTransferNo) {
        $totalDays = 0;
        $interestAmt = 0;
        $menthodId = $societyTariffParameterCheck['SocietyParameter']['method_id'];
        if($menthodId == 5){
            $interestAmt = $this->completeMonths($societyTariffParameterCheck, $lastMonthBillDetails,$postData);
        }
        else {

            $memberId = $memberData['member_id'];
            $societyId = $memberData['society_id'];
            $chqRetPayId = $this->getChequeRetPaymentId($memberId,$societyId,$latestTransferNo);        
            $principalAmt= $intererstAmt= $taxAmount =0;
            $billGeneratedDate = $postData['MemberBillGenerate']['bill_generated_date'];
            // if($reqFromUpdate){
            if(isset($lastMonthBillDetails['MemberBillSummary'])){
                $lastBillId = $lastMonthBillDetails['MemberBillSummary']['id'];
                $lastBillGeneratedDate = $lastMonthBillDetails['MemberBillSummary']['bill_generated_date'];
                $billDueDate = $lastMonthBillDetails['MemberBillSummary']['bill_due_date'];
            }
            else{
                $lastBillId = $lastMonthBillDetails['id'];
                $lastBillGeneratedDate = $lastMonthBillDetails['bill_generated_date'];
                $billDueDate = $lastMonthBillDetails['bill_due_date'];
            }
#here
            $calcualtedBillSummay = $this->calculatAmountAvailable($memberId,$societyId,$chqRetPayId,$lastBillGeneratedDate,$lastBillId,$memberBillSummary,$principalAmt,$intererstAmt,$taxAmount,$billType,$latestTransferNo);

            #check if this data can be prefetched
            if (!empty($billDueDate) && !empty($billGeneratedDate)) {
                $paymentCondition = array('MemberPayment.member_id' => $memberId,'MemberPayment.payment_date < ' => $billGeneratedDate,'MemberPayment.payment_date >= ' => $lastBillGeneratedDate, 'MemberPayment.society_id' => $societyId,'MemberPayment.bill_type' => $billType,'MemberPayment.member_transfer' => $latestTransferNo,'NOT' => array('MemberPayment.id' => $chqRetPayId));
                    // The array above used to repeat 'MemberPayment.member_transfer', the
                    // second one assigning a financial year id. PHP keeps only the last
                    // value for a duplicate key, so the transfer filter was silently
                    // replaced by a year id, matched almost nothing, and interest was
                    // charged on a principal that payments had never been deducted from.
                    // The payment window is already bounded by the two payment_date
                    // conditions, so no year filter is needed here.
                
                $memberPaymentData = $this->MemberPayment->find('all', array('conditions' => $paymentCondition,'order'=>array('MemberPayment.payment_date'=>'asc')));            

                $menthodId = $societyTariffParameterCheck['SocietyParameter']['method_id'];
                $billGeneratedDate = date("Y-m-d",strtotime('-1 day',strtotime($billGeneratedDate)));

                if(!empty($memberPaymentData)){
                    $paymentCount = count($memberPaymentData);
                    $byMonth = 0;
                    foreach($memberPaymentData as $index => $paymentData){
                        $paymentDate = $paymentData['MemberPayment']['payment_date'];
                        $amountPaid = $paymentData['MemberPayment']['amount_paid'];
                        if(strtotime($paymentDate) <= strtotime($billDueDate)){
                           $this->deductAndUpdate($amountPaid,$calcualtedBillSummay);
                        }
                        else{
                            $totalBal = $principalAmt+$intererstAmt+$taxAmount;
                            if($menthodId == Configure::read('InterestMethod.DelayDays')){
                                $this->setData($principalAmt,$totalBal,$calcualtedBillSummay);                            
                                $interestAmt = $interestAmt + $this->delayDays($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate,$totalDays);
                                $this->deductAndUpdate($amountPaid,$calcualtedBillSummay);
                                $billDueDate = $paymentDate;
                            }
                            else if($menthodId == Configure::read('InterestMethod.DelayMonths')){
                                if(empty($byMonth))
                                    $billDueDate = $lastBillGeneratedDate; // in delay month we have to calculate from last billgenerated date for first payment made
                                $this->setData($principalAmt,$totalBal,$calcualtedBillSummay); 
                                $interestAmt = $interestAmt + $this->delayMonths($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                                $this->deductAndUpdate($amountPaid,$calcualtedBillSummay);

                                $billDueDate = $paymentDate;
                                $byMonth = 1; // processed by one time
                            }
                            else if($menthodId == Configure::read('InterestMethod.CompleteCycleDays')){
                                $this->setData($principalAmt,$totalBal,$calcualtedBillSummay); 
                                $interestAmt = $interestAmt + $this->completeCycleDays($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                                break;
                            }
                            else if($menthodId == Configure::read('InterestMethod.CompleteCycleMonthly')){
                                $this->setData($principalAmt,$totalBal,$calcualtedBillSummay); 
                             // echo "$principalAmt - $interestAmt";
                                $interestAmt = $interestAmt + $this->completeCycleMonthly($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                                break;
                            }
                            else{
                                
                            }
                        }
                        
                         //  echo "<br> $menthodId tyyt $index ".($paymentCount-1) ."<br>";
                        if($menthodId == Configure::read('InterestMethod.DelayDays') && $index == ($paymentCount-1)){
                            $paymentDate = $billGeneratedDate;  
                            $this->setData($principalAmt,$totalBal,$calcualtedBillSummay); 
                            $interestAmt = $interestAmt + $this->delayDays($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate,$totalDays);
                        }
                        
                        if($menthodId == Configure::read('InterestMethod.DelayMonths') && $index == ($paymentCount-1)){
                            if(empty($byMonth)){
                                $billDueDate = $lastBillGeneratedDate; 
                                $byMonth = 1;
                            }
                            $paymentDate = $billGeneratedDate;
                            $this->setData($principalAmt,$totalBal,$calcualtedBillSummay); 
                            $interestAmt = $interestAmt + $this->delayMonths($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                        }        
                        if($menthodId == Configure::read('InterestMethod.CompleteCycleMonthly') && $index == ($paymentCount-1)){
                            $this->setData($principalAmt,$totalBal,$calcualtedBillSummay); 
                            $interestAmt = $interestAmt + $this->completeCycleMonthly($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                        }
                        
                        if($menthodId == Configure::read('InterestMethod.CompleteCycleDays') && $index == ($paymentCount-1)){
                            $this->setData($principalAmt,$totalBal,$calcualtedBillSummay); 
                             // echo "$principalAmt - $interestAmt";
                            $interestAmt = $interestAmt + $this->completeCycleDays($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                             // echo " after calcualtion $principalAmt - $interestAmt";
                            
                        }                    
                    }
                }
                else{
                    $this->setData($principalAmt,$totalBal,$calcualtedBillSummay); 
                    if($menthodId == Configure::read('InterestMethod.DelayDays')){
                        $paymentDate = $billGeneratedDate;
                        $interestAmt = $interestAmt + $this->delayDays($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate,$totalDays);
                    }
                    else if($menthodId == Configure::read('InterestMethod.DelayMonths')){
                        $billDueDate = $lastBillGeneratedDate;
                        $paymentDate = $billGeneratedDate;                    
                        $interestAmt = $interestAmt + $this->delayMonths($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                    }
                    else if($menthodId == Configure::read('InterestMethod.CompleteCycleDays')){
                        $interestAmt = $interestAmt + $this->completeCycleDays($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                    }
                    else if($menthodId == Configure::read('InterestMethod.CompleteCycleMonthly')){
    
                        $interestAmt = $interestAmt + $this->completeCycleMonthly($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                    }
                    else{
                        
                    }                
                }
            }
        }
        return ($interestAmt < 0) ? 0 : $interestAmt;
    }
    
    public function getMonthNoFromBillFreq($billFreq){
        $monthCnt = 0;
        if($billFreq == 1) // monthly
            $monthCnt = 1;
        else if($billFreq == 2) // biMonthly
            $monthCnt = 2;
        else if($billFreq == 3) // quraterly
            $monthCnt = 3;
        else if($billFreq == 4) // quadraple
            $monthCnt = 4;
        else if($billFreq == 5) // half year
            $monthCnt = 6;
        else
            $monthCnt = 12;  // yearly
            
            return  $monthCnt;
    }

    public function completeMonths($societyTariffParameterCheck, $lastMonthBillDetails, $postData){
        $interestOnDue = 0;
        
        if(isset($lastMonthBillDetails['MemberBillSummary'])){
            $lastMonthBillDetails = $lastMonthBillDetails['MemberBillSummary'];    
        }
        else{
            $lastMonthBillDetails = $lastMonthBillDetails;
        }
        
        if (!empty($lastMonthBillDetails['bill_generated_date']) && !empty($postData['MemberBillGenerate']['bill_generated_date'])) {
            if(!empty($lastMonthBillDetails['bill_frequency_id'])){
                $delayMonth = $this->getMonthNoFromBillFreq($lastMonthBillDetails['bill_frequency_id']);    
            }
            else{
                $date1 = date_create($postData['MemberBillGenerate']['bill_generated_date']);
                $date2 = date_create($lastMonthBillDetails['bill_generated_date']);
                $diff = date_diff($date1, $date2);
                $delayMonth = $diff->format("%m%");
                
            }
            
            
            
            //Check which interest type is applied 1-No, 2-Simple, 3-Compound
            if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.No')) {
                // No interest
                $interestOnDue = 0;
            } 
            else if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.Simple')) {
                // Only on Pricipal amount
                $lastMonthBillprincipalAmount = $lastMonthBillDetails['principal_balance'] - $lastMonthBillDetails['interest_free_amount'];
                //echo $lastMonthBillprincipalAmount.'/'.$lastMonthBillDetails['principal_balance'];die;
                $interestPerMonth = $lastMonthBillprincipalAmount * (($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 12) / 100);
                $interestOnDue = round($interestPerMonth * $delayMonth);
            } 
            else if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.Compound')) {
                // On balance amount princicpal + interest
                $lastMonthBillprincipalAmount = $lastMonthBillDetails['balance_amount'] - $lastMonthBillDetails['interest_free_amount'];
                $interestPerMonth = $lastMonthBillprincipalAmount * (($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 12) / 100);
                $interestOnDue = round($interestPerMonth * $delayMonth);
            }
        }
        
        return $interestOnDue;
    }    
    
    public function setData(&$principalAmt,&$totalBal,$calcualtedBillSummaryData){
        if(!empty($calcualtedBillSummaryData)){
            // echo "<pre>";
            $countIndex = count($calcualtedBillSummaryData)-1;
            $totalPrincipal = $calcualtedBillSummaryData[$countIndex]['principal'];// array_column($calcualtedBillSummaryData,'principal');
            $totalTax = $calcualtedBillSummaryData[$countIndex]['tax']; // array_column($calcualtedBillSummaryData,'tax');
            $totalInterest = $calcualtedBillSummaryData[$countIndex]['interest'];  // array_column($calcualtedBillSummaryData,'interest');
            // echo "$ $countIndex $tempPrincipal $tempTax $tempInterest";            print_r($calcualtedBillSummaryData);// exit;
            
            /* $totalPrincipal = array_sum($tempPrincipal);
            $totalTax = array_sum($tempTax);
            $totalInterest = array_sum($tempInterest); */
            
            $principalAmt = $totalPrincipal;
            $totalBal = $totalPrincipal + $totalTax + $totalInterest;
        }
        
    }
    
    
    public function delayDays($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate,&$totalDays){
        $interestOnDue = 0;
        $date1 = date_create($billDueDate);
        $date2 = date_create(date("Y-m-d",strtotime("+1 days",strtotime($paymentDate)))); // added +1 because date created ignoring 1 day        
        // $date2 = date_create($paymentDate);
        $diff = date_diff($date1, $date2);
        $delayDays = $diff->format("%a%");
        $interestRate = $societyTariffParameterCheck['SocietyParameter']['interest_rate'];
        $interestTypeId = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];
        
        
        //Check which interest type is applied 1-No, 2-Simple, 3-Compound
        if ($interestTypeId == Configure::read('InterestType.No')) {
            // No interest
            $interestOnDue = 0;
        } else if ($interestTypeId == Configure::read('InterestType.Simple')) {
        
            $totalDays = $totalDays + $delayDays;
            // Only on Pricipal amount
		    if($principalAmt > 0) {
				$interestForYear = $principalAmt * ($interestRate / 100);
				$interestOnDue = round(($interestForYear / 365) * $delayDays);
			} else {
				$interestOnDue = 0;
			}
 			 // echo "<br>$billDueDate $paymentDate principal $principalAmt delay days  $delayDays  interest $interestOnDue  $interestRate  $interestForYear <br>";

        } else if ($interestTypeId == Configure::read('InterestType.Compound')) {
            // On balance amount princicpal + interest
            if($totalBal > 0) {
				$interestForYear = $totalBal * ($interestRate / 100);
				$interestOnDue = round(($interestForYear / 365) * $delayDays);
			} else {
				$interestOnDue = 0;
			}
        }
        return $interestOnDue;
    }
    
    public function delayMonths($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate){
        $interestOnDue = 0;
        $date1 = date_create($billDueDate);
        $date2 = date_create(date("Y-m-d",strtotime("+1 days",strtotime($paymentDate)))); // added +1 because date created ignoring 1 day
        // $date2 = date_create($paymentDate);
        $diff = date_diff($date1, $date2);
        $delayDays = $diff->format("%a%");
        // echo  " days - $delayDays";
        // $delayMonth = $diff->format("%m%");
        $delayMonth = isset($delayMonth) && $delayMonth != 0 ? $delayMonth : 0; 
        //Check which interest type is applied 1-No, 2-Simple, 3-Compound
        $interestRate = $societyTariffParameterCheck['SocietyParameter']['interest_rate'];
        $interestTypeId = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];            

        if ($interestTypeId == Configure::read('InterestType.No')) {
            // No interest
            $interestOnDue = 0;
        } 
        else if ($interestTypeId == Configure::read('InterestType.Simple')) {
             // echo "$principalAmt - $interestRate $delayDays  $date1 $date2 bill date $billDueDate  paymentdate $paymentDate";// exit;
            // Only on Pricipal amount
			if($principalAmt > 0) {
				$interestForYear = $principalAmt * ($interestRate / 100);
				$interestOnDue = round(($interestForYear / 365) * $delayDays);
				// echo $interestOnDue;
			} else {
				$interestOnDue = 0;
			}
			//  echo "<br>$billDueDate $paymentDate  interest $interestOnDue $principalAmt<br>";
        }
        else if ($interestTypeId == Configure::read('InterestType.Compound')) {
            // On balance amount princicpal + interest
			if($totalBal > 0) {
				$interestForYear = $principalAmt * ($interestRate / 100);
				$interestOnDue = round(($interestForYear / 365) * $delayDays);
			} else {
				$interestOnDue = 0;
			}
        }
        
        return $interestOnDue;
    }
    
    public function completeCycleDays($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate){
        $interestOnDue = 0;
        // Check how many days delayed  Due date from last bill to current months bill date
        if (!empty($lastBillGeneratedDate) && !empty($billGeneratedDate)) {
            $date1 = date_create(date("Y-m-d",strtotime("+1 days",strtotime($billGeneratedDate))));
            $date2 = date_create($lastBillGeneratedDate);
            $diff = date_diff($date1, $date2);
            $delayDays = $diff->format("%a%");
            $interestRate = $societyTariffParameterCheck['SocietyParameter']['interest_rate'];
            $interestTypeId = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];                        

            //Check which interest type is applied 1-No, 2-Simple, 3-Compound
            if ($interestTypeId == Configure::read('InterestType.No')) {
                // No interest
                $interestOnDue = 0;
            } else if ($interestTypeId == Configure::read('InterestType.Simple')) {
                // Only on Pricipal amount
                $interestForYear = $principalAmt * ($interestRate / 100);
                $interestOnDue = round(($interestForYear / 365) * $delayDays);
                // echo "<br>$principalAmt - $interestRate delaymonth $delayDays  last bill date $lastBillGeneratedDate  new bill date $billGeneratedDate  $interestOnDue";// exit;                
            } else if ($interestTypeId == Configure::read('InterestType.Compound')) {
                // On balance amount princicpal + interest
                $interestForYear = $totalBal * ($interestRate / 100);
                $interestOnDue = round(($interestForYear / 365) * $delayDays);
            }
        }       
        
        return $interestOnDue;
    }
    
    public function completeCycleMonthly($principalAmt,$totalBal,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate){
        $interestOnDue = 0;
        // Check how many months delayed  Due date from last bill to current months bill date
        if (!empty($lastBillGeneratedDate) && !empty($billGeneratedDate)) {
            $date1 = date_create(date("Y-m-d",strtotime("+1 days",strtotime($billGeneratedDate)))); // needed to add patch due to php issue 
            $date2 = date_create($lastBillGeneratedDate);
            $diff = date_diff($date2, $date1);
            $delayMonth = $diff->format("%m%");
            // $delayDays = $diff->format("%a%");
            $interestRate = $societyTariffParameterCheck['SocietyParameter']['interest_rate'];
            $interestTypeId = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];                 
            //Check which interest type is applied 1-No, 2-Simple, 3-Compound
            if ($interestTypeId == Configure::read('InterestType.No')) {
                // No interest
                $interestOnDue = 0;
            } 
            else if ($interestTypeId == Configure::read('InterestType.Simple')) {
                // Only on Pricipal amount
                $interestPerMonth = $principalAmt * (($interestRate / 12) / 100);
                $interestOnDue = round($interestPerMonth * $delayMonth);
                
                 //  echo "<br>$principalAmt - $interestRate delaymonth $delayMonth  last bill date $lastBillGeneratedDate  new bill date $billGeneratedDate  $interestOnDue";// exit;
            } 
            else if ($interestTypeId == Configure::read('InterestType.Compound')) {
                // On balance amount princicpal + interest
                $interestPerMonth = $totalBal * (($interestRate / 12) / 100);
                $interestOnDue = round($interestPerMonth * $delayMonth);
            }
        }
        return $interestOnDue;
    }
    
    

    public function calculation(&$amountPaid,&$principalAmt,&$intererstAmt,&$taxAmount){
       //  echo "----- <br>$amountPaid,$principalAmt,$intererstAmt,$taxAmount";
        if($taxAmount > 0 && $amountPaid > 0){
            $amountPaid = $amountPaid-$taxAmount;
            if($amountPaid > 0){
                $taxAmount = 0;
            }
            else{
                $taxAmount = abs($amountPaid);
                $amountPaid = 0;
            }
        }
        
        // echo "after tax remaove $amountPaid,$taxAmount";
        if($intererstAmt > 0 && $amountPaid > 0){
            $amountPaid = $amountPaid-$intererstAmt;
            if($amountPaid > 0){
                $intererstAmt = 0;
            }
            else{
                $intererstAmt = abs($amountPaid);
                $amountPaid = 0;
            }
        }
        
        // echo "<br>after interest remaove $amountPaid,$taxAmount";
        
        if($principalAmt > 0 && $amountPaid > 0){
            $amountPaid = $amountPaid-$principalAmt;
            if($amountPaid > 0){
                $principalAmt = 0;
            }
            else{
                $principalAmt = abs($amountPaid);
                $amountPaid = 0;
            }
        }         
        // echo "<br> after principla remaove $amountPaid,$taxAmount<br>";
        
    }







    public function getInterestOnDueAmount_bkOM($societyTariffParameterCheck, $lastMonthBillDetails, $postData) {



        // 1-Delay Days, 2-Delay Months, 3-Complete Cycle Days, Complete Cycle Monthly 



        $interestOnDue = 0;



        $lastMonthBillprincipalAmount = 0;



        switch ($societyTariffParameterCheck['SocietyParameter']['method_id']) {



        case Configure::read('InterestMethod.DelayDays') :



                // Check how many days delayed  Due date from last bill to current months bill date



                if (!empty($lastMonthBillDetails['bill_due_date']) && !empty($postData['MemberBillGenerate']['bill_generated_date'])) {



                    if(!empty($lastMonthBillDetails['paymentDetails'])) {



                     



                        if(strtotime($lastMonthBillDetails['paymentDetails']['payment_date']) > strtotime($lastMonthBillDetails['bill_due_date'])) {



                            $date1 = date_create($lastMonthBillDetails['bill_due_date']);



                            $date2 = date_create($lastMonthBillDetails['paymentDetails']['payment_date']);



                            $diff = date_diff($date1, $date2);



                            $delayDays = $diff->format("%a%");



                        }



                    } else {



                            $date1 = date_create($postData['MemberBillGenerate']['bill_generated_date']);



                            $date2 = date_create($lastMonthBillDetails['bill_due_date']);



                            $diff = date_diff($date1, $date2);



                            $delayDays = $diff->format("%a%");



                    }





                    //Check which interest type is applied 1-No, 2-Simple, 3-Compound



                    if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.No')) {



                        // No interest



                        $interestOnDue = 0;



                    } else if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.Simple')) {



                        // Only on Pricipal amount



                        if(!empty($lastMonthBillDetails['paymentDetails'])) {



                            $lastMonthBillprincipalAmount = $lastMonthBillDetails['paymentDetails']['principal_paid'];



                        } else {



                            $lastMonthBillprincipalAmount = $lastMonthBillDetails['principal_balance'] - $lastMonthBillDetails['interest_free_amount'];



                        }



					





						if($lastMonthBillprincipalAmount > 0) {



							$interestForYear = $lastMonthBillprincipalAmount * ($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 100);



							$interestOnDue = round(($interestForYear / 365) * $delayDays);



						} else {



							$interestOnDue = 0;



						}



                        



                    } else if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.Compound')) {



                        // On balance amount princicpal + interest



                        if(!empty($lastMonthBillDetails['paymentDetails'])) {



                            $lastMonthBillprincipalAmount = $lastMonthBillDetails['paymentDetails']['total_paid'];



                        } else {



                            $lastMonthBillprincipalAmount = $lastMonthBillDetails['balance_amount'] - $lastMonthBillDetails['interest_free_amount'];



                        }



						



                        if($lastMonthBillprincipalAmount > 0) {



							$interestForYear = $lastMonthBillprincipalAmount * ($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 100);



							$interestOnDue = round(($interestForYear / 365) * $delayDays);



						} else {



							$interestOnDue = 0;



						}



                    }



                }



                break;



           case Configure::read('InterestMethod.DelayMonths') :



                // Check how many months delayed  Due date from last bill to current months bill date



                if (!empty($lastMonthBillDetails['bill_due_date']) && !empty($postData['MemberBillGenerate']['bill_generated_date'])) {



                    $date1 = date_create($postData['MemberBillGenerate']['bill_generated_date']);



                    $date2 = date_create($lastMonthBillDetails['bill_due_date']);



                    $diff = date_diff($date1, $date2);



                    $delayMonth = $diff->format("%m%");



                    $delayMonth = isset($delayMonth) && $delayMonth != 0 ? $delayMonth : 0; 



                    //Check which interest type is applied 1-No, 2-Simple, 3-Compound



                    if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.No')) {



                        // No interest



                        $interestOnDue = 0;



                    } else if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.Simple')) {



                        // Only on Pricipal amount



                        $lastMonthBillprincipalAmount = $lastMonthBillDetails['principal_balance'] - $lastMonthBillDetails['interest_free_amount'];



						



						if($lastMonthBillprincipalAmount > 0) {



							$interestPerMonth = $lastMonthBillprincipalAmount * (($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 12) / 100);



							$interestOnDue = round($interestPerMonth * $delayMonth);



						} else {



							$interestOnDue = 0;



						}



                    } else if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.Compound')) {



                        // On balance amount princicpal + interest



                        $lastMonthBillprincipalAmount = $lastMonthBillDetails['balance_amount'] - $lastMonthBillDetails['interest_free_amount'];



                        



						if($lastMonthBillprincipalAmount > 0) {



							$interestPerMonth = $lastMonthBillprincipalAmount * (($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 12) / 100);



							$interestOnDue = round($interestPerMonth * $delayMonth);



						} else {



							$interestOnDue = 0;



						}



                    }



                }



                break;



        case Configure::read('InterestMethod.CompleteCycleDays') :



                // Check how many days delayed  Due date from last bill to current months bill date



                if (!empty($lastMonthBillDetails['bill_generated_date']) && !empty($postData['MemberBillGenerate']['bill_generated_date'])) {



                    $date1 = date_create($postData['MemberBillGenerate']['bill_generated_date']);



                    $date2 = date_create($lastMonthBillDetails['bill_generated_date']);



                    $diff = date_diff($date1, $date2);



                    $delayDays = $diff->format("%a%");







                    //Check which interest type is applied 1-No, 2-Simple, 3-Compound



                    if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.No')) {



                        // No interest



                        $interestOnDue = 0;



                    } else if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.Simple')) {



                        // Only on Pricipal amount



                        $lastMonthBillprincipalAmount = $lastMonthBillDetails['principal_balance'] - $lastMonthBillDetails['interest_free_amount'];







                        $interestForYear = $lastMonthBillprincipalAmount * ($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 100);



                        $interestOnDue = round(($interestForYear / 365) * $delayDays);



                    } else if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.Compound')) {



                        // On balance amount princicpal + interest



                        $lastMonthBillprincipalAmount = $lastMonthBillDetails['balance_amount'] - $lastMonthBillDetails['interest_free_amount'];



                        $interestForYear = $lastMonthBillprincipalAmount * ($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 100);



                        $interestOnDue = round(($interestForYear / 365) * $delayDays);



                    }



                }



                break;



            case Configure::read('InterestMethod.CompleteCycleMonthly') :



                // Check how many months delayed  Due date from last bill to current months bill date



                if (!empty($lastMonthBillDetails['bill_generated_date']) && !empty($postData['MemberBillGenerate']['bill_generated_date'])) {



                    $date1 = date_create($postData['MemberBillGenerate']['bill_generated_date']);



                    $date2 = date_create($lastMonthBillDetails['bill_generated_date']);



                    $diff = date_diff($date1, $date2);



                    $delayMonth = $diff->format("%m%");







                    //Check which interest type is applied 1-No, 2-Simple, 3-Compound



                    if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.No')) {



                        // No interest



                        $interestOnDue = 0;



                    } else if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.Simple')) {



                        // Only on Pricipal amount



                        $lastMonthBillprincipalAmount = $lastMonthBillDetails['principal_balance'] - $lastMonthBillDetails['interest_free_amount'];



                        //echo $lastMonthBillprincipalAmount.'/'.$lastMonthBillDetails['principal_balance'];die;



                        $interestPerMonth = $lastMonthBillprincipalAmount * (($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 12) / 100);



                        $interestOnDue = round($interestPerMonth * $delayMonth);



                    } else if ($societyTariffParameterCheck['SocietyParameter']['interest_type_id'] == Configure::read('InterestType.Compound')) {



                        // On balance amount princicpal + interest



                        $lastMonthBillprincipalAmount = $lastMonthBillDetails['balance_amount'] - $lastMonthBillDetails['interest_free_amount'];



                        $interestPerMonth = $lastMonthBillprincipalAmount * (($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 12) / 100);



                        $interestOnDue = round($interestPerMonth * $delayMonth);



                    }



                }



                break;



        }



        return $interestOnDue;



    }







    public function print_member_bills() {
        // print_r($this->request->query);exit;
        $societyId = $this->societyloginID;
        $financialYearId = $this->Util->getFinancialYear();
        $printBillDetails = array();
        $ledgerHeadDetails = array();
    
    	$banks = array();
        $billMonth = (isset($this->request->query['month']) && !empty($this->request->query['month'])) ? $this->request->query['month'] : 1;
        $billNumberFrom = (isset($this->request->query['bill_from']) && !empty($this->request->query['bill_from'])) ? $this->request->query['bill_from'] : '';
        $billNumberTo = (isset($this->request->query['bill_to']) && !empty($this->request->query['bill_to'])) ? $this->request->query['bill_to'] : '';
        $billType = (isset($this->request->query['bill_type']) && !empty($this->request->query['bill_type'])) ? $this->request->query['bill_type'] : 'reg';
        // Current / Old owner selection. A transferred unit keeps one member id, so the
        // generation is carried on MemberBillSummary.member_transfer: the current owner's
        // bills carry Member.member_transfer, every earlier owner a lower value.
        $memberRecordType = (isset($this->request->query['member_record']) && $this->request->query['member_record'] != '') ? $this->request->query['member_record'] : 'Current';
        $memberTransferList = $this->Member->find('list', array('conditions' => array('Member.society_id' => $societyId), 'fields' => array('Member.id', 'Member.member_transfer')));

	    $banksList = $this->SocietyBill->getAllBankList();

    	foreach ($banksList as $key => $data){

    		$banks[$data['Bank']['id']] = $data['Bank']['bank_name']; 
	    }


        $societyParameters = $this->SocietyBill->getSocietyParameters();
        $optionsQuery[] = array('AND' => array('MemberBillSummary.society_id' => $societyId,'MemberBillSummary.month' => $billMonth,'MemberBillSummary.bill_type'=>$billType));
        // The old owner's bills sit in the year(s) before the transfer, which may not be
        // the year currently selected, so only the Current view is scoped to it.
        if ($memberRecordType != 'Old') {
            $optionsQuery[0]['AND']['MemberBillSummary.financial_year_id'] = $financialYearId;
        }

        /*Society bill taiffs*/
        $societyLedgerHeadTitleList = array();
        $societyLedgerHeads = $this->SocietyTariffOrder->find('all', array('fields'=>array('SocietyLedgerHeads.id','SocietyLedgerHeads.title'),'conditions' => array('SocietyLedgerHeads.society_id'=>$societyId),'order'=>array('SocietyTariffOrder.tariff_serial' => 'ASC')));

        foreach($societyLedgerHeads as $head) {
            $societyLedgerHeadTitleList[$head['SocietyLedgerHeads']['id']] = $head['SocietyLedgerHeads']['title'];
        }

        if (isset($this->request->query['flat_no']) && !empty($this->request->query['flat_no'])) {
            $optionsQuery['OR']['MemberBillSummary.flat_no'] = (isset($this->request->query['flat_no']) && !empty($this->request->query['flat_no'])) ? $this->request->query['flat_no'] : '';
        } else if (isset($this->request->query['bill_generated_date']) && !empty($this->request->query['bill_generated_date'])) {
            $optionsQuery['OR']['MemberBillSummary.bill_generated_date'] = (isset($this->request->query['bill_generated_date']) && !empty($this->request->query['bill_generated_date'])) ? $this->request->query['bill_generated_date'] : '';
        }
        
        if (isset($billNumberFrom) && !empty($billNumberFrom) && !empty($billNumberTo)) {
            $optionsQuery['OR']['MemberBillSummary.bill_no BETWEEN ? and ?'] = array($billNumberFrom,$billNumberTo);
        } else if (isset($billNumberTo) && !empty($billNumberTo)) {
            $optionsQuery['AND']['MemberBillSummary.bill_no'] = $billNumberTo;
        } else if (isset($billNumberFrom) && !empty($billNumberFrom)) {
            $optionsQuery['AND']['MemberBillSummary.bill_no'] = $billNumberFrom;
        }
		if ($memberRecordType != 'Old') {
			$optionsQuery['AND']['MemberBillSummary.financial_year_id'] = $financialYearId;
		}
        //Society ledger head list which are part of bill
        if ($this->SocietyTariffOrder->hasAny(array('SocietyTariffOrder.society_id' => $societyId))) {
            $societyLedgerHeadList = $this->SocietyTariffOrder->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyId), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyTariffOrder.id' => 'asc')));
        } else {
            $societyLedgerHeadList = $this->SocietyLedgerHeads->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyId, 'SocietyLedgerHeads.is_in_bill_charges' => 1), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyLedgerHeads.title' => 'asc'), 'recursive' => -1));
        }

		//echo "<pre>";
		//print_r( $societyLedgerHeadList);

        foreach ($societyLedgerHeadList as $key => $ledgerDetails) {
            $ledgerHeadDetails[$ledgerDetails['SocietyLedgerHeads']['id']] = $ledgerDetails['SocietyLedgerHeads']['title'];
        }

        // $billType  = isset($this->request->query['supplementary_check']) ? 'sup' : 'reg';
        $optionsQuery['AND']['MemberBillSummary.bill_type'] = $billType;
        $monthlyBills = $this->MemberBillSummary->find('all', array('conditions' => $optionsQuery, 'order' => array('MemberBillSummary.bill_no' => 'asc')));
        
        

        if ($monthlyBills) {
            $oldOwnerNames = array();
            if ($memberRecordType == 'Old') {
                $this->loadModel('MemberIdentification');
                // Ascending, so a unit transferred more than once keeps the most recent
                // previous owner.
                $identifications = $this->MemberIdentification->find('all', array('conditions' => array('MemberIdentification.society_id' => $societyId), 'fields' => array('MemberIdentification.member_id', 'MemberIdentification.third_member'), 'order' => array('MemberIdentification.id' => 'asc'), 'recursive' => -1));
                foreach ($identifications as $identification) {
                    if (!empty($identification['MemberIdentification']['third_member'])) {
                        $oldOwnerNames[$identification['MemberIdentification']['member_id']] = $identification['MemberIdentification']['third_member'];
                    }
                }
            }

            foreach ($monthlyBills as $key => $bill) {
                $billMemberId = $bill['MemberBillSummary']['member_id'];
                $billTransfer = (int)$bill['MemberBillSummary']['member_transfer'];
                $currentTransfer = isset($memberTransferList[$billMemberId]) ? (int)$memberTransferList[$billMemberId] : 0;
                // The two views partition the unit's bills, so nothing becomes unreachable:
                // the generation the member row still carries is the current owner's, every
                // other generation belonged to someone before them.
                if ($memberRecordType == 'Old') {
                    if ($billTransfer == $currentTransfer) {
                        continue;
                    }
                } else if ($billTransfer != $currentTransfer) {
                    continue;
                }

                // Keyed by bill so an old owner with bills in more than one year keeps them all.
                $billKey = $bill['MemberBillSummary']['id'];
                $billFinancialYearId = $bill['MemberBillSummary']['financial_year_id'];
                $printBillDetails[$billKey] = $bill;
                $tariffDetails = $this->MemberBillGenerate->find('all', array('conditions' => array('MemberBillGenerate.society_id' => $societyId,'MemberBillGenerate.financial_year_id' => $billFinancialYearId, 'MemberBillGenerate.month' => $billMonth, 'MemberBillGenerate.member_id' => $billMemberId,'MemberBillGenerate.bill_type'=>$billType)));
                $printBillDetails[$billKey]['tariff'] = $tariffDetails;
                $memberDetails = $this->Member->find('first', array('conditions' => array('Member.id' => $billMemberId)));
                $printBillDetails[$billKey]['memberDetails'] = $memberDetails;

                // The member row always holds the current owner, so an old owner's bill has
                // to be re-labelled with the previous owner recorded on the transfer.
                if ($memberRecordType == 'Old' && isset($oldOwnerNames[$billMemberId])) {
                    $printBillDetails[$billKey]['Member']['member_name'] = $oldOwnerNames[$billMemberId];
                    $printBillDetails[$billKey]['Member']['member_prefix'] = '';
                    $printBillDetails[$billKey]['Member']['joint_member_name'] = '';
                }

                // If April bill is printing then we need receipt after last bills generated dates
                if($bill['MemberBillSummary']['month'] == 4) {

                    // Get receipts from last years database
                    // $memberPaymentDetails = $this->getLastYearsMarchReceipts($societyId,$bill['MemberBillSummary']['member_id'],$billType);
                        $receiptFromDate = isset($this->request->query['receipt_from']) ? $this->request->query['receipt_from'] : date('Y').'-03-01';
                        $receiptToDate = isset($this->request->query['receipt_to']) ? $this->request->query['receipt_to'] : date('Y').'-03-31';

//                        $memberPaymentDetails = $this->Member->MemberPayment->find('all',
//                        array('conditions' => array('MemberPayment.member_id' => $bill['MemberBillSummary']['member_id'],'MemberPayment.bill_type'=>$billType
//                        ,'MemberPayment.financial_year_id' => $financialYearId-1,'MemberPayment.payment_date >=' => $receiptFromDate,'MemberPayment.payment_date <=' => $receiptToDate),'order'=>array('MemberPayment.id'=>'desc')));

                        $memberPaymentDetails = $this->Member->MemberPayment->find('all',
                        array('conditions' => array('MemberPayment.member_id' => $billMemberId,'MemberPayment.bill_type'=>$billType,'MemberPayment.member_transfer' => $billTransfer
                        ,'MemberPayment.payment_date >=' => $receiptFromDate,'MemberPayment.payment_date <=' => $receiptToDate),'order'=>array('MemberPayment.id'=>'desc')));

                } else {
                    if (isset($this->request->query['receipt_from']) && !empty($this->request->query['receipt_from']) && isset($this->request->query['receipt_to']) && !empty($this->request->query['receipt_to'])) {
                        $memberPaymentDetails = $this->Member->MemberPayment->find('all', array('conditions' => array('MemberPayment.member_id' => $billMemberId,'MemberPayment.financial_year_id' => $billFinancialYearId,'MemberPayment.member_transfer' => $billTransfer,'MemberPayment.payment_date >=' => $this->request->query['receipt_from'],'MemberPayment.payment_date <=' => $this->request->query['receipt_to'],'MemberPayment.bill_type'=>$billType),'order'=>array('MemberPayment.id'=>'desc')));
                    } else {
                        $memberPaymentDetails = $this->Member->MemberPayment->find('all', array('conditions' => array('MemberPayment.member_id' => $billMemberId,'MemberPayment.financial_year_id' => $billFinancialYearId,'MemberPayment.member_transfer' => $billTransfer,'MemberPayment.bill_type'=>$billType),'order'=>array('MemberPayment.id'=>'desc')));
                    }
                }

                $printBillDetails[$billKey]['receipts'] = $memberPaymentDetails;
            }
        }

        $receiptPeriod = array('From'=>$this->request->query['receipt_from'],'To'=>$this->request->query['receipt_to']);
        $wordsFunction  = ($this->request->query['words_type'] == "English")?"numberTowordsEnglish":"numberTowordsMarathi";

        $this->set(compact('ledgerHeadDetails', 'printBillDetails','societyParameters','societyLedgerHeadTitleList','banks','receiptPeriod','wordsFunction'));
    }



    



    public function bill_by_billid() {



        $billId = $this->request->query['billId'];



        $billMonth = $this->request->query['month'];



        $societyId = $this->Session->read('Auth.User.id');



        $societyMemberId = $this->Session->read('Auth.member.id');



        



        $banksList = $this->SocietyBill->getAllBankList();







	foreach ($banksList as $key => $data){



		$banks[$data['Bank']['id']] = $data['Bank']['bank_name']; 



	}







        $societyParameters = $this->SocietyBill->getSocietyParameters();



        $optionsQuery[] = array('AND' => array('MemberBillSummary.society_id' => $societyId, 'MemberBillSummary.month' => $billMonth, 'MemberBillSummary.member_id' => $societyMemberId, 'MemberBillSummary.id' => $billId));



        



        /*Society bill taiffs*/



        $societyLedgerHeadTitleList = array();



        $societyLedgerHeads = $this->SocietyTariffOrder->find('all', array('fields'=>array('SocietyLedgerHeads.id','SocietyLedgerHeads.title'),'conditions' => array('SocietyLedgerHeads.society_id'=>$societyId),'order'=>array('SocietyTariffOrder.tariff_serial' => 'ASC')));



        foreach($societyLedgerHeads as $head) {



            $societyLedgerHeadTitleList[$head['SocietyLedgerHeads']['id']] = $head['SocietyLedgerHeads']['title'];



        }



        



        //Society ledger head list which are part of bill



        if ($this->SocietyTariffOrder->hasAny(array('SocietyTariffOrder.society_id' => $societyId))) {



            $societyLedgerHeadList = $this->SocietyTariffOrder->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyId), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyTariffOrder.id' => 'asc')));



        } else {



            $societyLedgerHeadList = $this->SocietyLedgerHeads->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyId, 'SocietyLedgerHeads.is_in_bill_charges' => 1), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyLedgerHeads.title' => 'asc'), 'recursive' => -1));



        }







        foreach ($societyLedgerHeadList as $key => $ledgerDetails) {



            $ledgerHeadDetails[$ledgerDetails['SocietyLedgerHeads']['id']] = $ledgerDetails['SocietyLedgerHeads']['title'];



        }







        $monthlyBills = $this->MemberBillSummary->find('all', array('conditions' => $optionsQuery, 'order' => array('MemberBillSummary.bill_no' => 'asc')));







        if ($monthlyBills) {



            foreach ($monthlyBills as $key => $bill) {                



                $printBillDetails[$bill['MemberBillSummary']['member_id']] = $bill;







                $tariffDetails = $this->MemberBillGenerate->find('all', array('conditions' => array('MemberBillGenerate.society_id' => $societyId, 'MemberBillGenerate.month' => $billMonth, 'MemberBillGenerate.member_id' => $bill['MemberBillSummary']['member_id'])));



                $printBillDetails[$bill['MemberBillSummary']['member_id']]['tariff'] = $tariffDetails;







                $memberDetails = $this->Member->find('first', array('conditions' => array('Member.id' => $bill['MemberBillSummary']['member_id'])));



                $printBillDetails[$bill['MemberBillSummary']['member_id']]['memberDetails'] = $memberDetails;







                



            }



        }



        



        



        $this->set(compact('ledgerHeadDetails', 'printBillDetails','societyParameters','societyLedgerHeadTitleList','banks','receiptPeriod'));



        



    }



    



    public function receipt_by_payment() {



        $banksList = $this->SocietyBill->getAllBankList();



        $banks=array();



	foreach ($banksList as $key => $data){



		$banks[$data['Bank']['id']] = $data['Bank']['bank_name']; 



	}



        $memberPaymentDetails = $this->Member->MemberPayment->find('all', array('conditions' => array('MemberPayment.id' => $this->request->query['payment_id']),'order'=>array('MemberPayment.id'=>'desc')));



        $this->set(compact('banks','memberPaymentDetails'));



    }

    public function getLastYearsMarchReceipts($societyId,$memberIds,$billType='',$fromDate='',$toDate='') {
           //$financialYearId =   $this->Util->getFinancialYear();
         
            if(!is_array($memberIds))  $memberIds = [$memberIds];
            $memberIdStr = implode($memberIds,',');
            if(!empty($fromDate) && !empty($toDate)){
                $fromDate = date('Y-m-d',strtotime($fromDate));
                $toDate = date('Y-m-d',strtotime($toDate));
                // (select bill_generated_date from member_bill_summaries where member_id =$memberId and society_id= $societyId and  bill_type = '$billType' order by id desc limit 1)
                $query = "select * from member_payments as MemberPayment where 
                financial_year_id = $this->financialYearId-1 and
                member_id in (".$memberIdStr.") and bill_type = '$billType' 
                and payment_date between '$fromDate' and '$toDate' ";
            }
            else{
                $query = "select * from member_payments as MemberPayment where  
                member_id in (".$memberIdStr.") and bill_type = '$billType'
                and financial_year_id = $this->financialYearId-1
                and society_id= $societyId and  bill_type = '$billType' order by id desc limit 1";                
            }

            $dbConnection = connectionManager::getDataSource('default');
            $paymentDetailsArray = $dbConnection->query($query);
            return $paymentDetailsArray;


    }



    



    public function society_ledger_heads() {



        $this->loadModel('SocietyLedgerHeads');



    }







    public function checkMemberBillGeneratedExistsByMonth(){


        $this->layout = false;

        $financialYearId = $this->Util->getFinancialYear();


        $response = array();



        $societyLoginId = $this->societyloginID;



        $response['error'] = 0;



        $billType = Configure::read('BillType.Regular');



        if ($this->RequestHandler->isAjax()) {



            /*$responseArr = $this->checkBillDateAndMonth($this->request->data);



            $response = $responseArr;*/



            if (isset($responseArr['error']) && $responseArr['error'] == 1) {



                $this->autoRender = false;



                $billGeneratedMonths = (isset($this->request->data['MemberBillGenerate']['month']) && !empty($this->request->data['MemberBillGenerate']['month'])) ? $this->request->data['MemberBillGenerate']['month'] : date('m');



                $conditionArry = array('MemberBillGenerate.society_id' => $societyLoginId, 'MemberBillGenerate.month' => $billGeneratedMonths,'MemberBillGenerate.financial_year_id' => $financialYearId ,'MemberBillGenerate.bill_type'=>trim($billType));



                if ($this->MemberBillGenerate->hasAny($conditionArry)) {



                    $response['error'] = 1;



                }



            }



        }



        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));



    }



    



    public function checkMemberSupplementaryBillByMonth(){



        $this->layout = false;



        $response = array();



        $societyLoginId = $this->societyloginID;



        $response['error'] = 0;



        $billType = Configure::read('BillType.Supplementary');



        if ($this->RequestHandler->isAjax()) {



            if (isset($responseArr['error']) && $responseArr['error'] == 1) {



                $this->autoRender = false;



                $billGeneratedMonths = (isset($this->request->data['MemberBillGenerate']['month']) && !empty($this->request->data['MemberBillGenerate']['month'])) ? $this->request->data['MemberBillGenerate']['month'] : date('m');



                $conditionArry = array('MemberBillGenerate.society_id' => $societyLoginId, 'MemberBillGenerate.month' => $billGeneratedMonths,'MemberBillGenerate.bill_type'=>trim($billType));



                if ($this->MemberBillGenerate->hasAny($conditionArry)) {



                    $response['error'] = 1;



                }



            }



        }



        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));



    }







    public function checkBillDateAndMonth($postData = array()) {



        $response = array();



        $postData['MemberBillGenerate']['month'];



        if (isset($postData) && !empty($postData)) {



            $billGenerateMonth = (int) date('m', strtotime($postData['MemberBillGenerate']['bill_generated_date']));



            if ($postData['MemberBillGenerate']['month'] != $billGenerateMonth) {



                $response['error'] = 1;



                $response['error_message'] = 'Please select correct bill date.';



            } else {



                $response['error'] = 0;



            }



            return $response;



        }



    }







    public function getAllMembersBillSummaryDetails() {

        $financialYearId = $this->Util->getFinancialYear();

        $this->layout = false;



        $response = array();



        $societyLoginId = $this->societyloginID;



        $response['error'] = 1;



        $response['ifDelete'] = 0;



        $response['ifSettlement'] = 0;



        if ($this->RequestHandler->isAjax()) {



            $this->autoRender = false;



            $billGeneratedMonths = (isset($this->request->data['MemberBillSummary']['month']) && !empty($this->request->data['MemberBillSummary']['month'])) ? $this->request->data['MemberBillSummary']['month'] : '';



            $options = array();



            $loptions = array();



            $options['AND'][] = array('MemberBillSummary.society_id' => $societyLoginId);

            $loptions['AND'][] = array('MemberBillSummary.society_id' => $societyLoginId);

            if (isset($billGeneratedMonths) && $billGeneratedMonths != '') {
                $options['AND'][] = array('MemberBillSummary.month' => $billGeneratedMonths);
            }

            if (isset($this->request->data['MemberBillSummary']['id']) && !empty($this->request->data['MemberBillSummary']['id'])) {
                $options['AND'][] = array('MemberBillSummary.id' => $this->request->data['MemberBillSummary']['id']);
                $options['AND'][] = array('MemberBillSummary.financial_year_id' => $financialYearId);                
            }



            if (isset($this->request->data['MemberBillSummary']['bill_no']) && !empty($this->request->data['MemberBillSummary']['bill_no'])) {
                $options['AND'][] = array('MemberBillSummary.bill_no' => $this->request->data['MemberBillSummary']['bill_no']);
            }



            if (isset($this->request->data['MemberBillSummary']['bill_generated_date']) && !empty($this->request->data['MemberBillSummary']['bill_generated_date'])) {
                $options['AND'][] = array('MemberBillSummary.bill_generated_date' => $this->request->data['MemberBillSummary']['bill_generated_date']);
            }







            if (isset($this->request->data['MemberBillSummary']['member_id']) && !empty($this->request->data['MemberBillSummary']['member_id'])) {

                $options['AND'][] = array('MemberBillSummary.member_id' => $this->request->data['MemberBillSummary']['member_id']);
            }

            $memberBillsDetails = $this->MemberBillSummary->find('first', array('conditions' => $options, 'order' => array('MemberBillSummary.id' => 'asc')));

            $loptions['AND'][] = array('MemberBillSummary.member_id' => $memberBillsDetails['MemberBillSummary']['member_id']);







            if (isset($memberBillsDetails) && count($memberBillsDetails) > 0) {



                $paymentMadeBillNo = isset($memberBillsDetails['MemberBillSummary']['bill_no']) ? $memberBillsDetails['MemberBillSummary']['bill_no'] : '';



                $billMonth = isset($memberBillsDetails['MemberBillSummary']['month']) ? $memberBillsDetails['MemberBillSummary']['month'] : '';



                $societyMemberId = isset($memberBillsDetails['MemberBillSummary']['member_id']) ? $memberBillsDetails['MemberBillSummary']['member_id'] : '';



                $conditionPayment = array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'), 'MemberPayment.member_id' => $societyMemberId, 'MemberPayment.bill_generated_id' => $paymentMadeBillNo,'MemberPayment.financial_year_id' => $financialYearId);

                $memberBillTariff = $this->MemberBillGenerate->find('all', array('conditions' => array('MemberBillGenerate.society_id' => $this->Session->read('Auth.User.id'), 'MemberBillGenerate.month' => $billMonth, 'MemberBillGenerate.member_id' => $societyMemberId,'MemberBillGenerate.bill_number' => $paymentMadeBillNo,'MemberBillGenerate.financial_year_id' => $financialYearId)));







                if (isset($memberBillTariff) && count($memberBillTariff) > 0) {



                    $tariffCount = 0;



                    $tariffSr = 1;



                    foreach ($memberBillTariff as $tariffData) {



                        $memberTariffData[$tariffCount]['tariff_serial'] = isset($tariffData['MemberBillGenerate']['tariff_serial']) ? $tariffData['MemberBillGenerate']['tariff_serial'] : $tariffSr;



                        $memberTariffData[$tariffCount]['ledger_head_id'] = isset($tariffData['MemberBillGenerate']['ledger_head_id']) ? $tariffData['MemberBillGenerate']['ledger_head_id'] : '';



                        $memberTariffData[$tariffCount]['amount'] = isset($tariffData['MemberBillGenerate']['amount']) ? $tariffData['MemberBillGenerate']['amount'] : '';



                        $memberTariffData[$tariffCount]['title'] = isset($tariffData['SocietyLedgerHeads']['title']) ? $tariffData['SocietyLedgerHeads']['title'] : '';



                        $tariffCount++;



                        $tariffSr++;



                    }



                    $memberBillsDetails['MemberTariff'] = $memberTariffData;



                }







                //Next previous records



                $societyMemberBillNextId = $this->MemberBillSummary->find('first', array('fields' => array('MemberBillSummary.id', 'MemberBillSummary.month'), 'conditions' => array('MemberBillSummary.id >' => $memberBillsDetails['MemberBillSummary']['id'], 'MemberBillSummary.society_id' => $this->Session->read('Auth.User.id')), 'order' => array('MemberBillSummary.id' => 'asc')));



                $societyMemberBillPreviousId = $this->MemberBillSummary->find('first', array('fields' => array('MemberBillSummary.id', 'MemberBillSummary.month'), 'conditions' => array('MemberBillSummary.id <' => $memberBillsDetails['MemberBillSummary']['id'], 'MemberBillSummary.society_id' => $this->Session->read('Auth.User.id')), 'order' => array('MemberBillSummary.id' => 'desc')));



                $memberBillsDetails['MemberBillSummary']['next'] = 0;



                $memberBillsDetails['MemberBillSummary']['previous'] = 0;



                $memberBillsDetails['MemberBillSummary']['next_month'] = 0;



                $memberBillsDetails['MemberBillSummary']['previous_month'] = 0;



                $memberBillsDetails['MemberBillSummary']['next_id'] = 0;



                $memberBillsDetails['MemberBillSummary']['previous_id'] = 0;



                if (!empty($societyMemberBillNextId)) {



                    $memberBillsDetails['MemberBillSummary']['next'] = 1;



                    $memberBillsDetails['MemberBillSummary']['next_id'] = (isset($societyMemberBillNextId['MemberBillSummary']['id']) && $societyMemberBillNextId['MemberBillSummary']['id'] != '') ? $societyMemberBillNextId['MemberBillSummary']['id'] : 0;



                    $memberBillsDetails['MemberBillSummary']['next_month'] = (isset($societyMemberBillNextId['MemberBillSummary']['month']) && $societyMemberBillNextId['MemberBillSummary']['month'] != '') ? $societyMemberBillNextId['MemberBillSummary']['month'] : 0;



                }



                if (!empty($societyMemberBillPreviousId)) {



                    $memberBillsDetails['MemberBillSummary']['previous'] = 1;



                    $memberBillsDetails['MemberBillSummary']['previous_id'] = (isset($societyMemberBillPreviousId['MemberBillSummary']['id']) && $societyMemberBillPreviousId['MemberBillSummary']['id'] != '') ? $societyMemberBillPreviousId['MemberBillSummary']['id'] : 0;



                    $memberBillsDetails['MemberBillSummary']['previous_month'] = (isset($societyMemberBillPreviousId['MemberBillSummary']['month']) && $societyMemberBillPreviousId['MemberBillSummary']['month'] != '') ? $societyMemberBillPreviousId['MemberBillSummary']['month'] : 0;



                }







                if ($this->Member->MemberPayment->hasAny($conditionPayment)) {



                    $memberPaymentDetails = $this->Member->MemberPayment->find('all', array('conditions' => $conditionPayment));



                    $memberBillsDetails['MemberPayment']['amount_paid'] = 00;



                    if (isset($memberPaymentDetails) && count($memberPaymentDetails) > 0) {



                        foreach ($memberPaymentDetails as $memberData) {



                            $memberBillsDetails['MemberPayment']['amount_paid'] += $memberData['MemberPayment']['amount_paid'];



                        }



                    }



                }



            }











            if (isset($memberBillsDetails) && !empty($memberBillsDetails)) {



                $response['error'] = 0;



                $response = array_merge($memberBillsDetails, $response);



            }



           



            $lastBillData = $this->MemberBillSummary->find('first', array('conditions' => $loptions, 'fields' => array('MemberBillSummary.month', 'MemberBillSummary.id', 'MemberBillSummary.bill_generated_date'), 'order' => array('MemberBillSummary.bill_generated_date' => 'desc')));



            if ($lastBillData['MemberBillSummary']['month'] == $memberBillsDetails['MemberBillSummary']['month'] && $lastBillData['MemberBillSummary']['id'] == $memberBillsDetails['MemberBillSummary']['id']){



                $this->loadModel('MemberBillSettlement');



                $conditionsArr = array('MemberBillSettlement.bill_summary_id' => $lastBillData['MemberBillSummary']['id']);



                $billSettlements = $this->MemberBillSettlement->find('first', array('conditions' => $conditionsArr, 'fields' => array('MemberBillSettlement.id')));



                $response['ifDelete'] = 1;



                $response['ifSettlement'] = 0;



                if (count($billSettlements)){



                    $response['ifSettlement'] = 1;



        }



            }



        }



        $response['memberrole'] = $this->Session->read('Auth.User.role');



        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));



    }







    public function deleteMemberBillSummaryById() {



        $this->layout = false;



        $response = array();



        $societyLoginId = $this->societyloginID;



        $response['error'] = 1;



        $responseArr['error_message'] = 'Bill summary could not be deleted.';



        if ($this->RequestHandler->isAjax()) {



            $this->autoRender = false;



            $billSummaryId = (isset($this->request->data['MemberBillSummary']['id']) && !empty($this->request->data['MemberBillSummary']['id'])) ? $this->request->data['MemberBillSummary']['id'] : '';



            



            



            $this->loadModel('MemberBillSettlement');



            $conditionsArr = array('MemberBillSettlement.bill_summary_id' => $billSummaryId);



            $billSettlements = $this->MemberBillSettlement->find('first', array('conditions' => $conditionsArr, 'fields' => array('MemberBillSettlement.id', 'MemberBillSettlement.payment_id')));



            if (count($billSettlements)){



                $memberPaymentID = $billSettlements['MemberBillSettlement']['payment_id'];



                App::import('Controller', 'SocietysMembers');



                $societyMemberObj = new SocietysMembersController;



                $stIds = $societyMemberObj->deleteMemberBillDetails($memberPaymentID, $societyLoginId);



               // print_r($stIds);exit;



                $this->loadModel('MemberPayment');



                $this->MemberPayment->id = $memberPaymentID;



                if ($this->MemberPayment->delete()){



                    for ($k=0;$k<count($stIds);$k++){



                        $this->MemberBillSettlement->id = $stIds[$k];



                        $this->MemberBillSettlement->delete();



                    }



                }



            }



            



            $conditionsArr1 = array('MemberBillSummary.society_id' => $societyLoginId, 'MemberBillSummary.id' => $billSummaryId);



            $billSummary = $this->MemberBillSummary->find('first', array('conditions' => $conditionsArr1, 'fields' => array('MemberBillSummary.month','MemberBillSummary.member_id')));



            



            $this->MemberBillSummary->id = $billSummaryId;



            if ($this->MemberBillSummary->delete()){            



                $billMonth = $billSummary['MemberBillSummary']['month'];



                $memberId = $billSummary['MemberBillSummary']['member_id'];



                $conditionsArr2 = array('MemberBillGenerate.society_id' => $societyLoginId, 'MemberBillGenerate.month' => $billMonth, 'MemberBillGenerate.member_id' => $memberId);



                $billGeneratedData = $this->MemberBillGenerate->find('all', array('conditions' => $conditionsArr2, 'fields' => array('MemberBillGenerate.id')));



                



                $cnt = count($billGeneratedData);



                for ($i=0;$i<$cnt;$i++) {



                    $this->MemberBillGenerate->id = $billGeneratedData[$i]['MemberBillGenerate']['id'];



                    $this->MemberBillGenerate->delete();



                }



                



                $responseArr['error'] = 0;



                $responseArr['error_message'] = 'Bill summary deleted successfully';



            }



            



        }



                



        return new CakeResponse(array('body' => json_encode($responseArr), 'status' => 200));



    }
    
    function deleteSettlementData($settlementData){
        $dataDeletedFailCnt = 0;
        $this->loadModel('MemberBillSettlement');
        if(!empty($settlementData)){
            foreach($settlementData as $paymentId => $data){
                $deleteSuccess = $this->MemberBillSettlement->deleteAll(array('payment_id' => $paymentId));
                if(!$deleteSuccess)
                    $dataDeletedFailCnt++;
            }
        }
         return ($dataDeletedFailCnt > 0) ? false : true;
    }    
    
    public function saveSettlement($settlementData){
        $insertionFailCnt = 0;
        if(!empty($settlementData)){
            foreach($settlementData as $paymentId => $data){
                if(!is_numeric($paymentId)) continue;
                foreach($data as $billId => $settleData){
                    if(isset($settleData['principal_paid']) || isset($settleData['interest_paid']) || isset($settleData['tax_paid'])){
                        $principalAmt = isset($settleData['principal_paid']) ? $settleData['principal_paid'] : 0 ;
                        $taxPaid = isset($settleData['tax_paid']) ? $settleData['tax_paid'] : 0 ;
                        $interestPaid = isset($settleData['interest_paid']) ? $settleData['interest_paid'] : 0 ;
                        $payableAmount = $principalAmt + $taxPaid + $interestPaid;
                        $memberBillSettlement = array(
                                'member_id' => $settleData['member_id'],
                                'payment_id'=>$paymentId,
                                'bill_summary_id'=>$settleData['bill_summary_id'],
                                'bill_no' => $settleData['bill_no'],
                                'bill_type'=> $settleData['bill_type'],
                                'bill_month'=> $settleData['bill_month'],
                                'principal_paid'=> $principalAmt ,
                                'tax_paid'=> $taxPaid ,
                                'interest_paid'=> $interestPaid ,
                                'payable_amount'=>$payableAmount,
                                'cdate'=> date('Y-m-d H:i:s'), //  $this->Util->getDateTime(),
                                'udate'=> date('Y-m-d H:i:s') // $this->Util->getDateTime()
                                );
                        
                        $this->MemberBillSettlement->create();
                        $settlementAdded = $this->MemberBillSettlement->save($memberBillSettlement);
                        if(!$settlementAdded){
                            $insertionFailCnt++;                                                
                        }
                    }
                }
            }
        }
        
        return $insertionFailCnt > 0 ? false : true;
    }    
        

    public function totalPaymentForMonth($billMonth,$memberId,$societyLoginId,$chqRetData,$nextBillAvailable = false,$nextBillMonthGeneratedDate = ''){
        $totalAmt = 0;
        if($nextBillAvailable){
            $paymentCondition = array('MemberPayment.member_id' => $memberId,'MemberPayment.payment_date <' => $nextBillMonthGeneratedDate, 'MemberPayment.society_id' => $societyLoginId,'NOT' => array('MemberPayment.id' => $chqRetData ));
        }
        else{
            $paymentCondition = array('MemberPayment.member_id' => $memberId,'MemberPayment.payment_date >=' => $billMonth, 'MemberPayment.society_id' => $societyLoginId,'NOT' => array('MemberPayment.id' => $chqRetData ));
        }
        $memberPaymentData = $this->MemberPayment->find('all', array('conditions' => $paymentCondition,'fields' => array('SUM(amount_paid) as amount_paid')));
        $totalAmt = !empty($memberPaymentData[0][0]['amount_paid']) ? $memberPaymentData[0][0]['amount_paid'] : 0;
        return ceil($totalAmt) ;
    }
    
    public function getChequeRetPaymentId($memberId,$societyLoginId,$latestTransferNo){
        $cheRetPayId = [];
        $this->loadModel('ChequeReturnData');
        $chequeRetData = $this->ChequeReturnData->find('all',array('conditions' => array('member_id'=>$memberId,'society_id'=> $societyLoginId,'member_transfer'=> $latestTransferNo,'financial_year_id' => $this->financialYearId )));
        if(!empty($chequeRetData)){
            foreach($chequeRetData as $chqData){
                $paymentId = $chqData['ChequeReturnData']['payment_id'];
                if($paymentId > 0)
                    array_push($cheRetPayId,$paymentId);
            }
        }
        
        return $cheRetPayId;
    }

    public function paymentNotConsideredOfMonth($billMonthEndDate,$memberId,$societyLoginId,$paymentIdUsed,$chqRetData,$nextBillAvailable,$nextBillMonthGeneratedDate,$billType,$latestTransferNo){
        $this->loadModel('JournalVoucher');
        $totalAmt = 0;
        $jvNo = [];

        if(!empty($paymentIdUsed)){
            foreach($paymentIdUsed as $jvId){
                if(strpos($jvId,'JV') !== false){
                   $exploadedJvId = explode("-",$jvId) ;
                   array_push($jvNo,$exploadedJvId[1]);
                }
                
            }
        }      

        if($nextBillAvailable){
            $conditions = array('JournalVoucher.voucher_date <= ' => $nextBillMonthGeneratedDate,'JournalVoucher.jv_credit_member_head_id'=>$memberId,
            'JournalVoucher.society_id'=>$societyLoginId,'JournalVoucher.financial_year_id' => $this->financialYearId,
            'JournalVoucher.member_transfer'=> $latestTransferNo,"NOT" => array('JournalVoucher.id' =>$jvNo));
            $paymentCondition = array('MemberPayment.member_id' => $memberId,'MemberPayment.payment_date <' => $nextBillMonthGeneratedDate,
            'MemberPayment.society_id' => $societyLoginId,'MemberPayment.member_transfer'=> $latestTransferNo,'MemberPayment.bill_type'=> $billType,
            'MemberPayment.financial_year_id' => $this->financialYearId,"NOT" => array('MemberPayment.id' =>array_merge($paymentIdUsed,$chqRetData)));
        }
        else{
            $conditions = array('JournalVoucher.voucher_date <= ' => $billMonthEndDate,'JournalVoucher.jv_credit_member_head_id'=>$memberId,
            'JournalVoucher.society_id'=>$societyLoginId,'JournalVoucher.member_transfer'=> $latestTransferNo,
            "NOT" => array('JournalVoucher.id' =>$jvNo,'JournalVoucher.financial_year_id' => $this->financialYearId));

            $paymentCondition = array('MemberPayment.member_id' => $memberId,'MemberPayment.payment_date <=' => $billMonthEndDate,
            'MemberPayment.society_id' => $societyLoginId,'MemberPayment.member_transfer'=> $latestTransferNo,'MemberPayment.bill_type'=> $billType,
            'MemberPayment.financial_year_id' => $this->financialYearId,"NOT" => array('MemberPayment.id' =>array_merge($paymentIdUsed,$chqRetData)));
        }
        
        if($billType != 'sup'){
            $memberJvCreditedData = $this->JournalVoucher->find('all', array('conditions' => $conditions,'fields' => array('sum(jv_amount_credited) as amount_paid'))); 
            $totalAmt = !empty($memberJvCreditedData[0][0]['amount_paid']) ? $totalAmt + $memberJvCreditedData[0][0]['amount_paid'] : 0;
        }
        
        $memberPaymentData = $this->MemberPayment->find('all', array('conditions' => $paymentCondition,'fields' => array('SUM(amount_paid) as amount_paid')));
        $totalAmt = !empty($memberPaymentData[0][0]['amount_paid']) ? $totalAmt + $memberPaymentData[0][0]['amount_paid'] : $totalAmt;
        return ceil($totalAmt) ;
    }     

    public function getDebitedJvAmount($fromDate,$toDate,$memberId,$societyId,$latestTransferNo){
        $jvDebitedAmt = 0;
        $this->loadModel('JournalVoucher');
        $conditions = array('JournalVoucher.voucher_date >= ' => $fromDate,'JournalVoucher.voucher_date <= ' => $toDate,
        'JournalVoucher.jv_debit_member_head_id'=>$memberId,
        'JournalVoucher.society_id'=>$societyId,
        'JournalVoucher.member_transfer' => $latestTransferNo,'JournalVoucher.financial_year_id' => $this->financialYearId);

        $memberJvDebitedData = $this->JournalVoucher->find('all', array('conditions' => $conditions,'fields' => array('SUM(jv_amount_debited) as jv_amount_debited')));        
        if(!empty($memberJvDebitedData)){
            $jvDebitedAmt = $memberJvDebitedData[0][0]['jv_amount_debited'];
        }
        return $jvDebitedAmt;
    }
    
    public function sortMultiDimentionalPaymentsArray($a,$b){
          return (strtotime($a['MemberPayment']["payment_date"]) <= strtotime($b['MemberPayment']["payment_date"])) ? -1 : 1;
    }    
    
    public function getCreditedJvData($endDate,$memberId,$societyId,$paymentData,$latestTransferNo){
        $newPaymentData = [];
        $this->loadModel('JournalVoucher');
        $conditions = array('JournalVoucher.voucher_date <= ' => $endDate,'JournalVoucher.jv_credit_member_head_id'=>$memberId,'JournalVoucher.society_id'=>$societyId,'JournalVoucher.financial_year_id' => $this->financialYearId,'JournalVoucher.member_transfer' => $latestTransferNo);
        $memberJvCreditedData = $this->JournalVoucher->find('all', array('conditions' => $conditions,'fields' => array('id','jv_amount_credited','voucher_date'))); 

        $creditData = [];
        if(!empty($memberJvCreditedData)){
            foreach($memberJvCreditedData as $key => $jvData){
                $creaditData[$key]['MemberPayment']['id'] = "JV-".($jvData['JournalVoucher']['id']);   
                $creaditData[$key]['MemberPayment']['amount_paid'] = $jvData['JournalVoucher']['jv_amount_credited'];
                $creaditData[$key]['MemberPayment']['payment_date'] = $jvData['JournalVoucher']['voucher_date'];
            }
            
            $newPaymentData = array_merge($paymentData,$creaditData);
            usort($newPaymentData,array($this,"sortMultiDimentionalPaymentsArray"));
        }
        else{
            $newPaymentData = $paymentData;
        }

        return $newPaymentData;
    }

    function getBillType($postData){
        $billType = '';
        if(!empty($postData)){

            $billNo = isset($postData['bill_no']) ? $postData['bill_no'] : '';
            $memberId = isset($postData['member_id']) ? $postData['member_id'] : '';
            $conditionsArr = array('MemberBillSummary.bill_no' => $billNo,'MemberBillSummary.member_id' => $memberId,'MemberBillSummary.financial_year_id' => $this->financialYearId);
            $memberBillSummaryDetails = $this->MemberBillSummary->find('all', array('conditions' => $conditionsArr));
            if(!empty($memberBillSummaryDetails)){
                $billType = $memberBillSummaryDetails[0]['MemberBillSummary']['bill_type'];         
            }
        }
        
        return $billType;
        
    }
    
    public function getMemberAllBillAppliedParameter($memberId,$billType,$transferStatusId){
        $result = [];
        $societyId = $this->societyloginID;
        $this->loadModel('BillAppliedParameter');
        $appliedParamWithBill =  $this->BillAppliedParameter->find('all',array('conditions' => array('BillAppliedParameter.bill_type' => $billType,
        'BillAppliedParameter.member_id' => $memberId,'BillAppliedParameter.society_id' => $societyId
        // ,'BillAppliedParameter.member_transfer' => $transferStatusId
        )));
        
        if(!empty($appliedParamWithBill)) {
            foreach($appliedParamWithBill as $params){
                $result[$params['BillAppliedParameter']['bill_no']] = $params['BillAppliedParameter'];
            }
        }

        return $result;
    }

    public function getIndivisualBillAppliedParams($paramsOnBill,$billNo){
        $params = [];
        if(!empty($paramsOnBill) && !empty($paramsOnBill[$billNo]))
            $params =  $paramsOnBill[$billNo];
        return $params;
    }

    public function getMemberAllBillNo($memberBillSummaryDetails){
        $billList = [];
        if(!empty($memberBillSummaryDetails)){
            foreach($memberBillSummaryDetails as $summary){
                array_push($billList,$summary['MemberBillSummary']['bill_no']);
            }
        }
        return $billList;
    }
    
    
    public function updateManualInt(){

        $responseArr['error'] = 0;
        $responseArr['id'] = $postData['id'];
        $responseArr['month'] = $postData['month'];
        $responseArr['error_message'] = 'Bill summary updated  successfully';                        
        $billUpdatedAfterEditDelete = true;

       // Capture all form data (everything from your AJAX post)
        $data = $this->request->data;

        // Example: Log everything (for debugging)
        // CakeLog::write('debug', print_r($data, true));

        // Now you can access each part:
        $summary = isset($data['MemberBillSummary']) ? $data['MemberBillSummary'] : [];
        $tariffs = isset($data['MemberTariff']) ? $data['MemberTariff'] : [];

        $interest_on_due_amount = isset($summary['interest_on_due_amount']) ? $summary['interest_on_due_amount'] : 0;
        $principal = isset($summary['monthly_principal_amount']) ? $summary['monthly_principal_amount'] : 0;
        $op_principal_arrears = isset($summary['op_principal_arrears']) ? $summary['op_principal_arrears'] : 0;
        
        $monthly_bill_amount = ($interest_on_due_amount + $principal);
        $amount_payable = $monthly_bill_amount+$op_principal_arrears;
        
        $pay_amount = $principal+$interest_on_due_amount+$op_principal_arrears;
        
        $set_interest = isset($summary['set_interest']) ? $summary['set_interest'] : 0;

        $final_interest = $interest_on_due_amount + $set_interest;
        
        $saveData = array(
            'id' => isset($summary['id']) ? $summary['id'] : null,
            'interest_on_due_amount' => $interest_on_due_amount,
            'monthly_bill_amount' => $monthly_bill_amount,
            'amount_payable' => $amount_payable,
            'interest_balance' => $final_interest,
            'balance_amount' => $pay_amount
        );

        // Load model (if not already available)
        $this->loadModel('MemberBillSummary');

        $validate = false;
        $this->MemberBillSummary->save($saveData, $validate);

        $responseArr = [
            'error' => 0,
            'id' => isset($summary['id']) ? $summary['id'] : null,
            'month' => isset($summary['month']) ? $summary['month'] : null,
            'tariff_count' => count($tariffs),
            'data' => $saveData,
            'error_message' => 'Bill summary updated successfully'
        ];
 
        return new CakeResponse(array('body' => json_encode($responseArr), 'status' => 200));
    }



    public function updateMemberBillSummaryById($processAfterEditAddDeletePayment = false,$memberId=NULL,$societyLoginId=NULL,$billType=NULL,$financialYearId=null,$openigUpdating = false){
        if(!is_null($financialYearId)){
            $this->financialYearId = $financialYearId;
        }
        else{
            $this->financialYearId =  $this->Util->getFinancialYear();
        }

        $this->layout = false;
        $responseArr = array();
        $this->loadModel('ChequeReturnData');
        $this->loadModel('MemberBillSummary');
        $this->loadModel('SocietyParameter');
        $this->loadModel('Society');
        $this->loadModel('MemberPayment');
        $this->loadModel('MemberBillSettlement');
        $responseArr['error'] = 1;
        $responseArr['error_message'] = 'Bill summary could not update';

        // if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            $postData['id'] = isset($this->request->data['MemberBillSummary']['id']) ? $this->request->data['MemberBillSummary']['id'] : '';
            $postData['discount'] = isset($this->request->data['MemberBillSummary']['discount']) ? $this->request->data['MemberBillSummary']['discount'] : '';
            $postData['principal_adjusted'] = isset($this->request->data['MemberBillSummary']['principal_adjusted']) ? $this->request->data['MemberBillSummary']['principal_adjusted'] : 0.00;
            $postData['interest_adjusted'] = isset($this->request->data['MemberBillSummary']['interest_adjusted']) ? $this->request->data['MemberBillSummary']['interest_adjusted'] : 0.00;
            $postData['member_id'] = isset($this->request->data['MemberBillSummary']['member_id']) ? $this->request->data['MemberBillSummary']['member_id'] : '';
            $postData['month'] = isset($this->request->data['MemberBillSummary']['month']) ? $this->request->data['MemberBillSummary']['month'] : isset($this->request->data['society_member_bill_next_month']) ? $this->request->data['society_member_bill_next_month'] : '';
            $postData['bill_no'] = isset($this->request->data['MemberBillSummary']['bill_no']) ? $this->request->data['MemberBillSummary']['bill_no'] : '';
            $postData['interest_on_due_amount'] = isset($this->request->data['MemberBillSummary']['interest_on_due_amount']) ? $this->request->data['MemberBillSummary']['interest_on_due_amount'] : 0.00;
            $memberId = ($processAfterEditAddDeletePayment) ? $memberId: $postData['member_id'];
            $billType =  ($processAfterEditAddDeletePayment) ? $billType : $this->getBillType($postData);

            $societyLoginId = ($processAfterEditAddDeletePayment) ? $societyLoginId: $this->societyloginID;
            $billUpdated = false;

            

             if(!$processAfterEditAddDeletePayment)
                 $billUpdated = $this->updateMemberTarrifDetails($this->request->data,$billType);

            if($billUpdated || $processAfterEditAddDeletePayment){

                $latestTransferNo = $this->getLatestTranferredId($memberId);
                $memberTransfered = $this->checkMemberTransferred($memberId);       
         
                $conditionsArr = array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $societyLoginId,'MemberBillSummary.bill_type' =>$billType ,'MemberBillSummary.member_transfer' => $latestTransferNo,'MemberBillSummary.financial_year_id' => $this->financialYearId);
                
                $memberBillSummaryDetails = $this->MemberBillSummary->find('all', array('conditions' => $conditionsArr, 'recursive' => -1, 'order' => array('MemberBillSummary.id' => 'asc')));
                $societyTariffParameterCheck = $this->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $societyLoginId)));

                if($openigUpdating){ 
                    $allBillNo = $this->getMemberAllBillNo($memberBillSummaryDetails);
                    if(!empty($allBillNo)){
                        $this->updateAppliedParameterOnBill($allBillNo,$billType,$societyTariffParameterCheck);
                    }
                }

                
                $chqRetData = $this->getChequeRetPaymentId($memberId,$societyLoginId,$latestTransferNo);
                $billSummaryCounter = 0;
                $lastMonthCount = 0;
                $amountPaid = 0;
                $prevBillMonth = 0;
                $billSettledPaymentId=[];
                $setSettlementData = [];
                $totalBillCount = count($memberBillSummaryDetails) > 0;
                $taxBal = 0;$InterestBal = 0;$principalBal = 0;$balAmt = 0;
                $currentBillMonth = '';
                $memberData = [
                    'member_id' => $memberId,
                    'society_id' => $societyLoginId
                    ];
                    $jvDebitedAmt = 0;          

                $memberAllBillParams = $this->getMemberAllBillAppliedParameter($memberId,$billType,$latestTransferNo);  
                $memberPaymentData = [];

                if (!empty($memberBillSummaryDetails)) {
                  
                    foreach ($memberBillSummaryDetails as $billData) {
                            
                            if ($billSummaryCounter == 0) {
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_paid'] = 0;
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_paid'] = 0;
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_paid'] = 0;
                                
                                
                                $currentMonthBillId = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['id'];
                                $currentBillMonth = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['bill_end_date'];
                                $currentBillMonthGeneratedDate = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['bill_generated_date'];
                                                                
                                if($billType == 'reg'){
                                    $jvDebitedAmt = $this->getDebitedJvAmount($currentBillMonthGeneratedDate,$currentBillMonth,$memberId,$societyLoginId,$latestTransferNo);
                                }                                  
                                
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['jv_adjustment'] = $jvDebitedAmt;
                                
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_amount'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['discount'];
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'];
                                // Was $jvAdjustment, which is only ever assigned inside
          // calculateMembersRegularBillByTariff() - never in this function. On PHP 5/7
          // that read as null and the sum happened to work; on PHP 8 it warns and
          // amount_payable degrades, which is why bill edit "worked before, not now".
          // $jvDebitedAmt is the variable this function actually maintains (see the
          // jv_adjustment and principal_balance assignments above and below).
          $amountPayable = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_due_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount']+$jvDebitedAmt;
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['amount_payable'] = $amountPayable;
                                
                                if(!empty($latestTransferNo)){
                                    $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_principal_arrears'] = 0;
                                    $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_tax_arrears'] = 0;
                                    $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_interest_arrears'] = 0;
                                }
                                
                                  

                                
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] = ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_principal_arrears'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount']) - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_paid']  - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_adjusted']+ $jvDebitedAmt;
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] = ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_interest_arrears'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount']) - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_paid'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_adjusted'];
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_tax_arrears'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_paid'];
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];
                                
                                $amountPayable = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['amount_payable'];
                                $nextBillMonthGeneratedDate = '';
                                if(isset($memberBillSummaryDetails[$billSummaryCounter+1]['MemberBillSummary']['bill_generated_date']))
                                    $nextBillMonthGeneratedDate = $memberBillSummaryDetails[$billSummaryCounter+1]['MemberBillSummary']['bill_generated_date'];    
                                
                                $nextBillAvailable = !empty($nextBillMonthGeneratedDate) ? true : false;


                                $paidAmountDeduction = 0;
                                $this->addPaymentInUpdation($memberBillSummaryDetails,$currentBillMonth,$billSettledPaymentId,$memberId,$societyLoginId,$setSettlementData,$paidAmountDeduction,$currentMonthBillId,$chqRetData,$nextBillAvailable,$nextBillMonthGeneratedDate,$billType,$latestTransferNo,$memberPaymentData);
                                $currentPaymentSettled = array_keys($setSettlementData);
                                

                                $currentMonthNotConsiderPayment = $this->paymentNotConsideredOfMonth($currentBillMonth,$memberId,$societyLoginId,$currentPaymentSettled,$chqRetData,$nextBillAvailable,$nextBillMonthGeneratedDate,$billType,$latestTransferNo);
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance']-$paidAmountDeduction-$currentMonthNotConsiderPayment; 


                            } else {

                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_paid'] = 0;
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_paid'] = 0;
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_paid'] = 0;
                                
                                $currentMonthBillId = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['id'];
                                $currentBillMonth = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['bill_end_date'];
                                $currentBillMonthGeneratedDate = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['bill_generated_date'];
                                                                
                                if($billType == 'reg'){                                
                                    $jvDebitedAmt = $this->getDebitedJvAmount($currentBillMonthGeneratedDate,$currentBillMonth,$memberId,$societyLoginId,$latestTransferNo);
                                }
                                
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['jv_adjustment'] = $jvDebitedAmt;
    
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_tax_arrears'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['tax_balance']) ? $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['tax_balance'] : 0;
                                $prevPrincipalBal = $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['principal_balance'];
                                $prevBalance = $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['balance_amount'];
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_principal_arrears'] = ($prevBalance < 0) ? $prevBalance : $prevPrincipalBal ;
                                
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_interest_arrears'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['interest_balance']) ? $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['interest_balance'] : 0;
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_due_amount'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['balance_amount']) ? $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['balance_amount'] : 0;
                                
                                $lastMonthBillDetails = $memberBillSummaryDetails[$billSummaryCounter-1];
                                // this is made just to use commnon functio of getInterestOndueAmount
                                $MemberBillGenerate = [
                                        'MemberBillGenerate' => [
                                            'bill_generated_date' => $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['bill_generated_date']
                                            ]
                                    ];

                                $billNo = $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['bill_no'];
                                $billAppliedParam = $this->getIndivisualBillAppliedParams($memberAllBillParams,$billNo);

                                if(isset($billAppliedParam['interest_type_id']))
                                    $societyTariffParameterCheck['SocietyParameter']['interest_type_id'] = $billAppliedParam['interest_type_id'];   
                                $interestTypeId = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];

                                if(isset($interestTypeId)  && $interestTypeId != 4){ //  4 - manually
                                    $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'] = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $MemberBillGenerate,$memberData,$memberBillSummaryDetails,$billType,$latestTransferNo);
                                }


                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_amount']  - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['discount'];
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'];
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['amount_payable'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_due_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount'];
                                

                                                                       
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount']  + $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['principal_balance'] + $jvDebitedAmt ;
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount']  + $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['interest_balance'];
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'] = ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'])  + $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['tax_balance'];
                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];
    
                                $amountPayable = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['amount_payable'];                            

                                $nextBillMonthGeneratedDate = '';
                                if(isset($memberBillSummaryDetails[$billSummaryCounter+1]['MemberBillSummary']['bill_generated_date']))
                                    $nextBillMonthGeneratedDate = $memberBillSummaryDetails[$billSummaryCounter+1]['MemberBillSummary']['bill_generated_date'];    
                                
                                $nextBillAvailable = !empty($nextBillMonthGeneratedDate) ? true : false;                                
                                
                                $paidAmountDeduction = 0;
                                $this->addPaymentInUpdation($memberBillSummaryDetails,$currentBillMonth,$billSettledPaymentId,$memberId,$societyLoginId,$setSettlementData,$paidAmountDeduction,$currentMonthBillId,$chqRetData,$nextBillAvailable,$nextBillMonthGeneratedDate,$billType,$latestTransferNo,$memberPaymentData);

                                $currentPaymentSettled = array_keys($setSettlementData);
                                $currentMonthNotConsiderPayment = $this->paymentNotConsideredOfMonth($currentBillMonth,$memberId,$societyLoginId,$currentPaymentSettled,$chqRetData,$nextBillAvailable,$nextBillMonthGeneratedDate,$billType,$latestTransferNo);

                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance']-$paidAmountDeduction-$currentMonthNotConsiderPayment; 
                                
                                $lastMonthCount++;
                            

                            }
    
                            $billSummaryCounter++;
                            $prevBillMonthId = $currentMonthBillId;

                    }
 
                     $billUpdatedAfterEditDelete = false;
                  
                    if ($this->MemberBillSummary->saveAll($memberBillSummaryDetails)) {
                        if($this->deleteSettlementData($setSettlementData)){
                            if($this->saveSettlement($setSettlementData)){
                                $updatedPayment = true;
                            }
                        }
                    }              

                    if($updatedPayment){
                            $responseArr['error'] = 0;
                            $responseArr['id'] = $postData['id'];
                            $responseArr['month'] = $postData['month'];
                            $responseArr['error_message'] = 'Bill summary updated  successfully';                        
                            $billUpdatedAfterEditDelete = true;
                    }
                    else{
                         $responseArr['error'] = 1;
                         $responseArr['error_message'] = 'Bill summary could not be updated.Please select member if not selected failue in payment data';                        
                    }
                }
                else{
                    $responseArr['error'] = 1;
                    $responseArr['error_message'] = 'Bill summary could not be updated.Please select member if not selected original bill issue';
                }
            }
            else{
                $responseArr['error'] = 1;
                $responseArr['error_message'] = 'Bill summary could not be updated.Please select member if not selected original bill issue';
            }

        if(!$processAfterEditAddDeletePayment){
            return new CakeResponse(array('body' => json_encode($responseArr), 'status' => 200));
        }
        else{
            return $billUpdatedAfterEditDelete;
        }
    }
    
    public function addPaymentInUpdation(&$memberBillSummaryDetails,$currentBillMonth,&$billSettledPaymentId,$memberId,$societyLoginId,&$setSettlementData,&$paidAmountDeduction,$currentMonthBillId,$chqRetData,$nextBillAvailable,$nextBillMonthGeneratedDate,$billType,$latestTransferNo,&$memberPaymentData){
            $updatedPayment = false;
            if($nextBillAvailable){
                $endDate = $nextBillMonthGeneratedDate;
                $paymentCondition = array('MemberPayment.member_id' => $memberId, 'MemberPayment.society_id' => $societyLoginId,'MemberPayment.payment_date <=' => $nextBillMonthGeneratedDate,'MemberPayment.bill_type' => $billType,'MemberPayment.member_transfer' => $latestTransferNo,'MemberPayment.financial_year_id' => $this->financialYearId,'NOT' => array('MemberPayment.id' => $chqRetData ));
            }
            else{
                $endDate = $currentBillMonth;
                $paymentCondition = array('MemberPayment.member_id' => $memberId, 'MemberPayment.society_id' => $societyLoginId,'MemberPayment.payment_date <=' => $currentBillMonth,'MemberPayment.bill_type' => $billType,'MemberPayment.member_transfer' => $latestTransferNo,'MemberPayment.financial_year_id' => $this->financialYearId,'NOT' => array('MemberPayment.id' => $chqRetData ));    
            }
            $memberPaymentData = $this->MemberPayment->find('all', array('conditions' => $paymentCondition, 'order' => array('MemberPayment.payment_date' => 'asc'), 'recursive' => -1));

            if($billType == 'reg'){
                $memberPaymentData = $this->getCreditedJvData($endDate,$memberId,$societyLoginId,$memberPaymentData,$latestTransferNo);

            }

            if(!empty($memberPaymentData)){
                
                $billCounter = 0;
                $paymentId = 0;
                $startIndex = 0;
                $totalIndex = count($memberPaymentData) -1 ;
                $prevIndex = -1;
                $allowIncrementLoop = false;
                $totalBillCount = count($memberBillSummaryDetails);
                $i = 0;
                $prevBillMonthId = 0;

                // echo "<br> Start of the calculation <br>";
                while($i < $totalBillCount){
                    
                    $billSummaryCounter = $i;
                    // echo $billSummaryCounter;
                    $currentIndex = $i;
                    if($prevIndex != $currentIndex){                                            
                        $taxBal = 0;$interestBal= 0 ;$principleBal = 0;
                    }
                    
                    $billMonth = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['month'];
                    $billMonthId = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['id'];

                    if($prevBillMonthId != $billMonthId){
                        $deductedtax = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_paid'];
                        $deductedInterest= $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_paid'] ;
                        $deductedPrinciple= $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_paid'];
                    }
                    
                     // echo "<br> deducted payment $deductedtax $deductedInterest $deductedPrinciple <br>";
                    if($billMonthId > $currentMonthBillId){
                      break;
                    } 

                    if($paidAmountDeduction <= 0)
                        $this->setPaymentData($memberPaymentData,$paidAmountDeduction,$billSettledPaymentId,$paymentId,$setSettlementData);

                    if($paidAmountDeduction > 0)
                       $this->deductPaidAmount($memberBillSummaryDetails,$billSummaryCounter,$paidAmountDeduction,$taxBal,$interestBal,$principleBal,$memberPaymentData,
                       $setSettlementData,$paymentId,$billSettledPaymentId,$deductedtax,$deductedInterest,$deductedPrinciple,$latestTransferNo);

                    // echo "<br> payment -  $paidAmountDeduction tax balance $taxBal - intereest bal - $interestBal principla bal - $principleBal ";
                    if($taxBal > 0 || $interestBal > 0 || $principleBal > 0){ // checking of current bill if any thing pending 
                        if($paidAmountDeduction <= 0)
                            $this->setPaymentData($memberPaymentData,$paidAmountDeduction,$billSettledPaymentId,$paymentId,$setSettlementData);
                        
                        if($paidAmountDeduction <= 0)
                            // $i++;
                            break;
                    }
                    else{
                        $i++;
                        $preIndex = $currentIndex;
                    }
                    
                    $prevBillMonthId = $billMonthId;
                      //  echo "<br> $currentMonthBillId - payment -  $paidAmountDeduction tax balance $taxBal - intereest bal - $interestBal principla bal - $principleBal ";
                }
            } // end of for loop
    }


    public function unUtilisedPaymentAmount($paymentId,$totalAmount,&$setSettlementData){
        $totalUnusedAmt = 0;
        if(!empty($setSettlementData)){
            $totalUsed = 0;
            foreach($setSettlementData as $settlepayId => $utilisedBill){
                if($settlepayId == $paymentId){
                    foreach($utilisedBill as $billId => $billData){
                        $totalUsed = $totalUsed + (isset($billData['principal_paid']) ? $billData['principal_paid'] : 0);
                        $totalUsed = $totalUsed + (isset($billData['tax_paid']) ? $billData['tax_paid'] : 0);
                        $totalUsed = $totalUsed + (isset($billData['interest_paid']) ? $billData['interest_paid'] : 0);
                    }
                }
            }
        }
        
        $totalUnusedAmt = ceil($totalAmount)- ceil($totalUsed);
        return $totalUnusedAmt;
        
    }    

    public function setPaymentData($paymentData = array(),&$paidAmountDeduction,&$billSettledPaymentId,&$paymentId,&$setSettlementData){
        if(!empty($paymentData)){
            // if(count($paymentData) != count($billSettledPaymentId)){
                foreach($paymentData as $key => $data){
                    $tempTotalAmtPaid = $paymentData[$key]['MemberPayment']['amount_paid'];
                    $tempPayId = $paymentData[$key]['MemberPayment']['id'];
                    if(!in_array($paymentData[$key]['MemberPayment']['id'],$billSettledPaymentId)){
                        $paidAmountDeduction = $tempTotalAmtPaid;
                        $paymentId = $tempPayId;
                        array_push($billSettledPaymentId,$paymentId);
                        break;
                    }
                      else{
                        $unsedPayment = $this->unUtilisedPaymentAmount($tempPayId,$tempTotalAmtPaid,$setSettlementData);
                        // echo "----------$unsedPayment ------------------------";
                        if($unsedPayment > 0){
                            $paidAmountDeduction = $unsedPayment;
                            $paymentId = $tempPayId;
                            break;
                        } 
                    else{
                            $paidAmountDeduction = 0;
                            $paymentId = 0;
                        }
                     }
                }
              //  echo "<br> inside to  payment methods $paidAmountDeduction  Id $paymentId";
        }
    }    
    
    public function deductPaidAmount(&$memberBillSummaryDetails,$billSummaryCounter,&$paidAmountDeduction,&$taxBal,&$interestBal,&$principleBal,$paymentData,&$setSettlementData,&$paymentId,&$billSettledPaymentId,&$deductedtax,&$deductedInterest,&$deductedPrinciple,$latestTransferNo){
        $billId = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['id'];
        
        if($paymentId > 0){
            $setSettlementData[$paymentId][$billId]['member_id'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['member_id'];
            $setSettlementData[$paymentId][$billId]['payment_id'] = $paymentId;
            $setSettlementData[$paymentId][$billId]['bill_summary_id'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['id'];
            $setSettlementData[$paymentId][$billId]['bill_no'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['bill_no'];
            $setSettlementData[$paymentId][$billId]['bill_type'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['bill_type'];
            $setSettlementData[$paymentId][$billId]['bill_month'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['month'];
        }
        
        
        $cntMemberBillSummaryDetails = count($memberBillSummaryDetails);
        $taxBal = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];        
        if($paidAmountDeduction > 0){

            if (isset($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance']) && $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'] > 0) {
                $tempPaidAmount = $paidAmountDeduction;
                $paidAmountDeduction = $paidAmountDeduction - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];
                $tempTaxPaid = 0;
                if ($paidAmountDeduction >= 0) {
                        $tempTaxPaid = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];
                } else {
                    $tempTaxPaid = $tempPaidAmount;
                    $paidAmountDeduction = 0; // set by default because value is less than 0
                }
                $monthProcessed[$billSummaryCounter]['tax_balance'] = $tempTaxPaid;
                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'] = $taxBal=  $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'] - $tempTaxPaid;
                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance']+$memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance']+$memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];
                $deductedtax = $deductedtax + $tempTaxPaid;
                $setSettlementData[$paymentId][$billId]['tax_paid'] = $tempTaxPaid;
                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_paid'] = $deductedtax;
                 for ($t=($billSummaryCounter+1);$t<$cntMemberBillSummaryDetails;$t++){
                      $memberBillSummaryDetails[$t]['MemberBillSummary']['tax_balance'] = $memberBillSummaryDetails[$t]['MemberBillSummary']['tax_total'] + $memberBillSummaryDetails[$t-1]['MemberBillSummary']['tax_balance'];
                      $memberBillSummaryDetails[$t]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$t]['MemberBillSummary']['principal_balance']+$memberBillSummaryDetails[$t]['MemberBillSummary']['interest_balance']+$memberBillSummaryDetails[$t]['MemberBillSummary']['tax_balance'];
                 }   
            }
        }
        else{
            $this->setPaymentData($paymentData,$paidAmountDeduction,$billSettledPaymentId,$paymentId,$setSettlementData);
            if($paidAmountDeduction > 0) return true;
        }

        $interestBal = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'];
        if($paidAmountDeduction > 0){
             //  echo "$billSummaryCounter -- inside of interest -". $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance']." $paidAmountDeduction <br>";
            if (isset($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance']) && $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] > 0) {
                $tempPaidAmount = $paidAmountDeduction;
                $paidAmountDeduction = $paidAmountDeduction - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'];
                $tempInterestPaid = 0;
                if ($paidAmountDeduction >= 0) {
                    // $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_paid'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'];
                    $tempInterestPaid = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'];
                } else {
                    $tempInterestPaid = $tempPaidAmount;
                    $paidAmountDeduction = 0; // set by default because value is less than 0
                }
                
        
                 $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] = $interestBal= $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] - $tempInterestPaid;                
                 $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance']+$memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance']+$memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];
                 $deductedInterest = $deductedInterest + $tempInterestPaid;
                 $setSettlementData[$paymentId][$billId]['interest_paid'] = $tempInterestPaid;
                 $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_paid'] = $deductedInterest;
                    for ($in=($billSummaryCounter+1);$in<$cntMemberBillSummaryDetails;$in++){
                        $memberBillSummaryDetails[$in]['MemberBillSummary']['interest_balance'] = $memberBillSummaryDetails[$in]['MemberBillSummary']['interest_on_due_amount'] + $memberBillSummaryDetails[$in-1]['MemberBillSummary']['interest_balance'];
                        $memberBillSummaryDetails[$in]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$in]['MemberBillSummary']['principal_balance']+$memberBillSummaryDetails[$in]['MemberBillSummary']['interest_balance']+$memberBillSummaryDetails[$in]['MemberBillSummary']['tax_balance'];
                    }                     
            }
            
        }
        else{
            $this->setPaymentData($paymentData,$paidAmountDeduction,$billSettledPaymentId,$paymentId,$setSettlementData);
            if($paidAmountDeduction > 0) return true;
        }        
        


        $principleBal = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'];
        if($paidAmountDeduction > 0){
            if ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] > 0) {

                $tempPaidAmount = $paidAmountDeduction;
                $paidAmountDeduction = $paidAmountDeduction - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'];
                $tempPrincipalPaid = 0;
                
                if ($paidAmountDeduction >= 0) {
                     $tempPrincipalPaid = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'];                    
                } else {
                    $tempPrincipalPaid = $tempPaidAmount;
                    $paidAmountDeduction = 0; // set by default because value is less than 0
                }
                

                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] = $principleBal= $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] - $tempPrincipalPaid;                
                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance']+$memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance']+$memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];
                $deductedPrinciple = $deductedPrinciple + $tempPrincipalPaid;
                $setSettlementData[$paymentId][$billId]['principal_paid'] = $tempPrincipalPaid;
                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_paid'] = $deductedPrinciple;
             
                for ($p=($billSummaryCounter+1);$p<$cntMemberBillSummaryDetails;$p++){
                    $currentMonthBillId = $memberBillSummaryDetails[$p]['MemberBillSummary']['id'];
                    $currentBillMonth = $memberBillSummaryDetails[$p]['MemberBillSummary']['bill_end_date'];
                    $currentBillMonthGeneratedDate = $memberBillSummaryDetails[$p]['MemberBillSummary']['bill_generated_date'];
                    $memberId = $memberBillSummaryDetails[$p]['MemberBillSummary']['member_id'];
                    $societyLoginId = $memberBillSummaryDetails[$p]['MemberBillSummary']['society_id'];
                    $memberBillSummaryDetails[$p]['MemberBillSummary']['principal_balance'] = $memberBillSummaryDetails[$p]['MemberBillSummary']['monthly_principal_amount'] + $memberBillSummaryDetails[$p]['MemberBillSummary']['jv_adjustment'] + $memberBillSummaryDetails[$p-1]['MemberBillSummary']['principal_balance'];
                    $memberBillSummaryDetails[$p]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$p]['MemberBillSummary']['principal_balance']+$memberBillSummaryDetails[$p]['MemberBillSummary']['interest_balance']+$memberBillSummaryDetails[$p]['MemberBillSummary']['tax_balance'];
                }            
            }
        }
        else{
            $this->setPaymentData($paymentData,$paidAmountDeduction,$billSettledPaymentId,$paymentId,$setSettlementData);
            if($paidAmountDeduction > 0) return true;
        }

    }    
    
    
    

    public function updateMemberBillSummaryById_bkOM(){



        $this->layout = false;



        $responseArr = array();



        $societyLoginId = $this->societyloginID;



        $responseArr['error'] = 1;



        $responseArr['error_message'] = 'Bill summary could not update';



        if ($this->RequestHandler->isAjax()) {



            $this->autoRender = false;



            $postData['id'] = isset($this->request->data['MemberBillSummary']['id']) ? $this->request->data['MemberBillSummary']['id'] : '';



            $postData['discount'] = isset($this->request->data['MemberBillSummary']['discount']) ? $this->request->data['MemberBillSummary']['discount'] : '';



            $postData['principal_adjusted'] = isset($this->request->data['MemberBillSummary']['principal_adjusted']) ? $this->request->data['MemberBillSummary']['principal_adjusted'] : 0.00;



            $postData['interest_adjusted'] = isset($this->request->data['MemberBillSummary']['interest_adjusted']) ? $this->request->data['MemberBillSummary']['interest_adjusted'] : 0.00;



            $postData['member_id'] = isset($this->request->data['MemberBillSummary']['member_id']) ? $this->request->data['MemberBillSummary']['member_id'] : '';



            $postData['month'] = isset($this->request->data['MemberBillSummary']['month']) ? $this->request->data['MemberBillSummary']['month'] : '';



            $postData['bill_no'] = isset($this->request->data['MemberBillSummary']['bill_no']) ? $this->request->data['MemberBillSummary']['bill_no'] : '';



            $postData['interest_on_due_amount'] = isset($this->request->data['MemberBillSummary']['interest_on_due_amount']) ? $this->request->data['MemberBillSummary']['interest_on_due_amount'] : 0.00;



            



           



            $this->updateMemberTarrifDetails($this->request->data);







            $conditionsArr = array('MemberBillSummary.member_id' => $postData['member_id'], 'MemberBillSummary.society_id' => $societyLoginId, 'MemberBillSummary.id >=' => $postData['id']);



            $memberBillSummaryDetails = $this->MemberBillSummary->find('all', array('conditions' => $conditionsArr, 'recursive' => -1, 'order' => array('MemberBillSummary.id' => 'asc')));



            $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id')));



            $billSummaryCounter = 0;



            $lastMonthCount = 0;



            //print_r($conditionsArr);die;



            if (isset($memberBillSummaryDetails) && count($memberBillSummaryDetails) > 0) {



                foreach ($memberBillSummaryDetails as $billData) {



                    if (!empty($billData)) {



                        if ($billSummaryCounter == 0) {



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_adjusted'] = $postData['principal_adjusted'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_adjusted'] = $postData['interest_adjusted'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['discount'] = $postData['discount'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'] = $postData['interest_on_due_amount'];



                            



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_amount'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['discount'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['amount_payable'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_due_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount'];







                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] = ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_principal_arrears'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount']) - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_paid'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_adjusted'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] = ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_interest_arrears'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount']) - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_paid'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_adjusted'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_tax_arrears'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_paid'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];



                        } else {



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_tax_arrears'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['tax_balance']) ? $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['tax_balance'] : 0;



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_principal_arrears'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['principal_balance']) ? $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['principal_balance'] : 0;



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_interest_arrears'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['interest_balance']) ? $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['interest_balance'] : 0;



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_due_amount'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['balance_amount']) ? $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['balance_amount'] : 0;



                            if ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_due_amount'] > 0) {



                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['balance_amount']) ? round(($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['balance_amount'] * ($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 12) / 100)) : 0;



                            } else {



                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'] = 0.00;



                            }



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_amount'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['discount'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['amount_payable'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_due_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount'];







                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] = ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_principal_arrears'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount']) - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_paid'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] = ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_interest_arrears'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount']) - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_paid'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_tax_arrears'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_paid'];







                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];







                            $lastMonthCount++;



                        }



                        $billSummaryCounter++;



                    } else {



                        $responseArr['error'] = 1;



                        $responseArr['error_message'] = 'Bill summary could not be updated.Please select member if not selected';



                    }



                }



               // echo "<pre>";

               // print_r($memberBillSummaryDetails);

              //  die;



                if (isset($memberBillSummaryDetails) && $this->MemberBillSummary->saveAll($memberBillSummaryDetails)) {



                    $responseArr['error'] = 0;



                    $responseArr['id'] = $postData['id'];



                    $responseArr['month'] = $postData['month'];



                    $responseArr['error_message'] = 'Bill summary updated  successfully';



                }



            }



        }



        return new CakeResponse(array('body' => json_encode($responseArr), 'status' => 200));



    }







    public function loadLedgerDetailsSupplementaryBills() {
        $societyLoginID = $this->societyloginID;

        //$this->SocietyLedgerHeads->recursive = -1;
        if ($this->SocietyTariffOrder->hasAny(array('SocietyTariffOrder.society_id' => $societyLoginID))) {
            $societyLedgerHeadList = $this->SocietyTariffOrder->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID,'SocietyTariffOrder.society_id' => $societyLoginID,'SocietyLedgerHeads.is_in_bill_charges' => 1,'SocietyLedgerHeads.is_supplementary_bill' => 1), 'order' => array('SocietyTariffOrder.id' => 'asc')));
        } else {
            $societyLedgerHeadList = $this->SocietyLedgerHeads->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID, 'SocietyLedgerHeads.is_in_bill_charges' => 1, 'SocietyLedgerHeads.is_supplementary_bill' => 1), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyLedgerHeads.title' => 'asc'), 'recursive' => -1));
        }

        $societyMemberFlatLists = $this->SocietyBill->getSocietyMemberFlatList();
        $this->set(compact('societyLedgerHeadList','societyMemberFlatLists'));
        
    }







    public function generateSupplementaryMemberBills_old_09022020(){



        $this->layout = false;



        $response = array();



        $response['error'] = 1;



        $response['error_message'] = 'You have not selected Unit/Area.';



        if ($this->RequestHandler->isAjax()) {



            $this->autoRender = false;



            $this->Member->Behaviors->attach('Containable');



            if (isset($this->request->data['MemberBillGenerate']['bill_generated_date']) && !empty($this->request->data['MemberBillGenerate']['bill_generated_date']) && !empty($this->request->data['MemberBillGenerate']['bill_type'])) {



                $societyMemberLists = $this->Member->find('all', array('fields' => array('Member.id', 'Member.building_id', 'Member.wing_id', 'Member.unit_type', 'Member.area', 'Member.flat_no', 'Member.op_principal', 'Member.op_interest'), 'conditions' => array('Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active')), 'recursive' => 0));



                //Based on society Parameters get tariffs for member



                if (count($societyMemberLists) > 0) {



                    foreach ($societyMemberLists as $details) {



                        $billNo = $this->SocietyBill->memberBillsUniqueNumber();



                        $memberDetails = $details['Member'];



                        $memberId = $memberDetails['id'];



                        if (isset($this->request->data['SupplementaryBillGenerate']) && !empty($this->request->data['SupplementaryBillGenerate'])) {



                            $responseArray = $this->calculateSocietyMembersSupplementaryBills($memberDetails, $memberId, $billNo, $this->request->data);



                            if (!empty($responseArray)) {



                                $response = $responseArray;



                            }



                        }



                    }



                }



            }



        }



        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));



    }







    public function generateSupplementaryMemberBills(){
        $this->layout = false;
        // This is an AJAX/JSON endpoint. A stray PHP notice or warning printed
        // before the JSON breaks JSON.parse on the client - and because the caller
        // has no error handler, the loader then spins forever with no bill shown.
        // Keep the output pure JSON and give a heavy run time to finish.
        @ini_set('display_errors', '0');
        error_reporting(0);
        @set_time_limit(600);
        $response = array();
        $response['error'] = 1;
        $this->financialYearId = $this->Util->getFinancialYear();
        $this->loadModel('YearWiseClosingBalance');
        $response['error_message'] = 'You have not selected Unit/Area.';
        $societyId = $this->Session->read('Auth.User.id');
        if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            try {
            $this->Member->Behaviors->attach('Containable');

            $memberListWithTransferStatus = $this->SocietyBill->memberTransferList($societyId);
            $this->memberClosingbalance = $this->SocietyBill->getMemberslastClosingBalance('sup');

            if ($this->request->data['name_suppl_appl'] == "all"){
                if (isset($this->request->data['MemberBillGenerate']['bill_generated_date']) && !empty($this->request->data['MemberBillGenerate']['bill_generated_date']) && !empty($this->request->data['MemberBillGenerate']['bill_type'])) {
                    $societyMemberLists = $this->Member->find('all', array('fields' => array('Member.id', 'Member.building_id', 'Member.wing_id', 'Member.unit_type', 'Member.area', 'Member.flat_no', 'Member.op_principal', 'Member.op_interest', 'Member.supplementary_principal', 'Member.supplementary_penality', 'Member.supplementary_interest','Member.member_transfer', 'Member.supplementary_tax'), 'conditions' => array('Member.society_id' => $societyId, 'Member.status' => Configure::read('Active')), 'recursive' => 0));
                    //Based on society Parameters get tariffs for member
                    if (count($societyMemberLists) > 0) {
                        foreach ($societyMemberLists as $details) {
                            $billNo = $this->SocietyBill->memberBillsUniqueNumber();
                            $memberDetails = $details['Member'];
                            $memberId = $memberDetails['id'];
                            $member_transfer = $memberDetails['member_transfer'];
                            
                            $memberTransferData = isset($memberListWithTransferStatus[$memberId]) ? $memberListWithTransferStatus[$memberId] : [];
                            $this->memberTransferInCurrentYear = (!empty($memberTransferData)) ? true : false;  
                            if (isset($this->request->data['SupplementaryBillGenerate']) && !empty($this->request->data['SupplementaryBillGenerate'])) {
                                $responseArray = $this->calculateSocietyMembersSupplementaryBills($memberDetails, $memberId, $billNo, $this->request->data,$member_transfer);
                                if (!empty($responseArray)) {
                                    $response = $responseArray;
                                }
                            }
                        }
                    }
                }
            }
            else {
                // Only members with an amount actually entered get a supplementary
                // bill. Skipping the empty rows both matches what was filled in and
                // stops the whole society being fetched, billed and processed - which
                // was also why generation crawled.
                $suppAmounts = isset($this->request->data['SupplementaryBillGenerate']['suplementary_amount']) ? $this->request->data['SupplementaryBillGenerate']['suplementary_amount'] : array();
                $individualIds = array();
                foreach ($suppAmounts as $__mId => $__amt) {
                    if ($__amt !== '' && $__amt !== null && (float)$__amt > 0) { $individualIds[] = $__mId; }
                }
            if (!empty($individualIds) && isset($this->request->data['MemberBillGenerate']['bill_generated_date']) && !empty($this->request->data['MemberBillGenerate']['bill_generated_date']) && !empty($this->request->data['MemberBillGenerate']['bill_type'])) {
                $societyMemberLists = $this->Member->find('all', array('fields' => array('Member.id', 'Member.building_id', 'Member.wing_id', 'Member.unit_type', 'Member.area', 'Member.flat_no', 'Member.op_principal', 'Member.op_interest', 'Member.supplementary_principal', 'Member.supplementary_penality', 'Member.supplementary_interest', 'Member.supplementary_tax'), 'conditions' => array('Member.society_id' => $societyId, 'Member.status' => Configure::read('Active'), 'Member.id' => $individualIds), 'recursive' => 0));
                if (count($societyMemberLists) > 0) {
                    foreach ($societyMemberLists as $details) {
                        $billNo = $this->SocietyBill->memberBillsUniqueNumber();
                        $memberDetails = $details['Member'];
                        $memberId = $memberDetails['id'];
                        $member_transfer = $memberDetails['member_transfer'];
                        $memberTransferData = isset($memberListWithTransferStatus[$memberId]) ? $memberListWithTransferStatus[$memberId] : [];
                        $transferInCurrentyear = (!empty($memberTransferData)) ? true : false;                                                
                        $idvPostData = $this->individualSupplementaryBillsArray($this->request->data['supp_head_id'],$memberId,$this->request->data); 
                        if (isset($this->request->data['SupplementaryBillGenerate']) && !empty($this->request->data['SupplementaryBillGenerate'])) {
                            $responseArray = $this->calculateSocietyMembersSupplementaryBills($memberDetails, $memberId, $billNo, $idvPostData,$member_transfer);

                            if (!empty($responseArray)) {
                                $response = $responseArray;
                            }
                        }
                    }
                }
            }
         }

        if(!empty($this->appliedParmaOnBill)){
            $this->loadModel('MemberBillAppliedParameter');
            $this->MemberBillAppliedParameter->saveAll($this->appliedParmaOnBill);
        }
            } catch (\Throwable $__e) {
                // Return the real failure as JSON instead of a 500 (which the client
                // can only report as "failed or timed out"). \Throwable also catches
                // fatal Errors on PHP 7+ - a null method call, a type error, etc. -
                // deep in the supplementary calculation.
                $response = array('error' => 1, 'error_message' => 'Generation error: ' . $__e->getMessage() . ' @ ' . basename($__e->getFile()) . ':' . $__e->getLine());
            }
    }
        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));
    }


    public function individualSupplementaryBillsArray($ledgerId,$memberId,$postData){
        $this->layout = false;
        $response = array();
        $response['MemberBillGenerate'] = $postData['MemberBillGenerate'];
        $actual_supplementary = (isset($postData['SupplementaryBillGenerate']['is_suppl_appl']))?$postData['SupplementaryBillGenerate']['is_suppl_appl']:array();
        $area_supplementary = (isset($postData['SupplementaryBillGenerate']['is_suppl_area']))?$postData['SupplementaryBillGenerate']['is_suppl_area']:array();

        $response['SupplementaryBillGenerate'][$ledgerId] = array(
            'rateUnitType'=>(array_key_exists($memberId,$actual_supplementary))?'Actual':'Area',
            'rate'=>$postData['SupplementaryBillGenerate']['suplementary_amount'][$memberId],
            'description'=>''
        );

        return $response;
    }

    public function calculateSocietyMembersSupplementaryBills($memberDetails, $memberId, $billNo, $postData = array(),$member_transfer){
        $billGenerateArr = array();
        $appliedParams = [];

        $responseError = array();
        
        $societyId = $this->Session->read('Auth.User.id');
        $billType = $appliedParams['bill_type'] = Configure::read('BillType.Supplementary');
        $appliedParams['member_id'] = $memberId;
        $appliedParams['society_id'] = $societyId;


        $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('SocietyParameter.society_id' =>$societyId ));
        $billGeneratedMonths = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : date('m');

        $societyMemberBillExists = $this->societyMemberBillExists($memberId, $billGeneratedMonths, $billType); //check same bill allready exists in system 
        if (empty($societyMemberBillExists)) {
            //Check if last months bill details available

            $lastMonthBillDetails = [];
            $memberAllBills = $this->MemberBillSummary->find('all', array('conditions' => array('MemberBillSummary.member_id' => $memberId, 'MemberBillSummary.society_id' => $this->societyloginID, 'MemberBillSummary.bill_type' => $billType,'MemberBillSummary.member_transfer'=>$member_transfer,'MemberBillSummary.financial_year_id' => $this->financialYearId ),'order'=>array('MemberBillSummary.id'=>'asc')));

            if(!empty($memberAllBills)){
                $lastIndex = count($memberAllBills)-1;
                $lastMonthBillDetails =  $memberAllBills[$lastIndex];
            }

            $interestTypeId = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];
            $appliedParams['interest_type_id'] = $interestTypeId; 
            $appliedParams['method_id'] = $societyTariffParameterCheck['SocietyParameter']['method_id'];            

            if (isset($lastMonthBillDetails) && !empty($lastMonthBillDetails)) {

                $lastMonthBillDetails = $lastMonthBillDetails['MemberBillSummary'];
                $memberBillSummaryData['op_principal_arrears'] = $lastMonthBillDetails['principal_balance'];
                $memberBillSummaryData['op_interest_arrears'] = $lastMonthBillDetails['interest_balance'];
                $memberBillSummaryData['op_due_amount'] = $lastMonthBillDetails['balance_amount'];
                $memberBillSummaryData['op_tax_arrears'] = $lastMonthBillDetails['op_tax_arrears'] + $lastMonthBillDetails['tax_total'];

                $memberBillSummaryData['tax_balance'] = $lastMonthBillDetails['tax_balance'];
                $memberBillSummaryData['interest_on_due_amount'] = 0;
                $balanceAmount = $memberBillSummaryData['op_due_amount'];



                if ($memberBillSummaryData['op_due_amount'] > 0) {
                    // only calculate intereset if it not in advance payment 
                    if($balanceAmount > 0 &&  $interestTypeId != 4){ //  4 - manually 
                        $memberBillSummaryData['interest_on_due_amount'] = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData,$memberDetails,$memberAllBills,$billType,$memberTransfer);
                    }

                }



            } else {
                // Added by OM on date 22/04/2021
                if (isset($memberDetails) && !empty($memberDetails)) {
                // supplemetary data
                    if ($this->memberTransferInCurrentYear) {
                        $memberDetails['supplementary_principal'] = 0;
                        $memberDetails['supplementary_tax'] = 0;
                        $memberDetails['supplementary_interest'] = 0;
                    }
                    else{                        
                        if(!empty($this->memberClosingbalance[$memberId])){ // if member have last closing balace
                            $previoutClosingBal  = $this->memberClosingbalance[$memberId];
                            if( $member_transfer == $previoutClosingBal['member_transfer']){ 
                                $memberDetails['supplementary_principal'] = $previoutClosingBal['principal_balance'];
                                $memberDetails['supplementary_tax'] = $previoutClosingBal['tax_balance'];
                                $memberDetails['supplementary_interest'] = $previoutClosingBal['interest_balance'];                                    
                            }                                
                        }
                        // otherwise take members first time entered opening amount  
                    }

                    $lastMonthBillDetails['principal_balance'] = isset($memberDetails['supplementary_principal']) ? $memberDetails['supplementary_principal'] : 0.00;
                    $lastMonthBillDetails['balance_amount'] = $memberDetails['supplementary_principal'] + $memberDetails['supplementary_interest'] + $memberDetails['supplementary_tax'];
                    $lastMonthBillDetails['bill_due_date'] = isset($memberDetails['op_bill_due_date']) ? $memberDetails['op_bill_due_date'] : '';
                    $lastMonthBillDetails['bill_generated_date'] = isset($memberDetails['op_bill_date']) ? $memberDetails['op_bill_date'] : '';
                    $lastMonthBillDetails['interest_free_amount'] = 0;
                    $memberBillSummaryData['op_principal_arrears_original'] = $memberBillSummaryData['op_principal_arrears'] = isset($memberDetails['supplementary_principal']) ? $memberDetails['supplementary_principal'] : 0.00;

                    $memberBillSummaryData['op_interest_arrears'] = isset($memberDetails['supplementary_interest']) ? $memberDetails['supplementary_interest'] : 0.00;
                    $memberBillSummaryData['op_tax_arrears'] = isset($memberDetails['supplementary_tax']) ? $memberDetails['supplementary_tax'] : 0.00;
                    $memberBillSummaryData['op_due_amount'] = $lastMonthBillDetails['balance_amount'] ;
                    $memberBillSummaryData['interest_on_due_amount'] = 0;

                    if (isset($lastMonthBillDetails['bill_due_date']) && $lastMonthBillDetails['bill_due_date'] != '0000-00-00' && $memberBillSummaryData['op_due_amount'] > 0) {

                        $interestTypeId = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];
                        $appliedParams['interest_type_id'] = $interestTypeId;
                        if($interestTypeId != 4){ //  4 - manually   
                            $principalAmt = !empty($memberDetails['supplementary_principal']) ?  $memberDetails['supplementary_principal'] : 0;
                            $intererstAmt = !empty($memberDetails['supplementary_interest']) ?  $memberDetails['supplementary_interest'] : 0;
                            $taxAmount = !empty($memberDetails['supplementary_tax']) ?  $memberDetails['supplementary_tax'] : 0;
                            $billDueDate = $lastBillGeneratedDate = $lastMonthBillDetails['bill_generated_date'];
                            $billGeneratedDate = $this->request->data['MemberBillGenerate']['bill_generated_date'];
                            $paymentDate = $lastMonthBillDetails['bill_due_date'];

                            
                            $memberBillSummaryData['interest_on_due_amount'] = $this->getInterestOnDueAmountOnFirstBill($principalAmt,$intererstAmt,$taxAmount,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);
                        }
                    }

                } else {
                    $memberBillSummaryData['op_principal_arrears'] = 0;
                    $memberBillSummaryData['op_interest_arrears'] = 0;
                    $memberBillSummaryData['op_due_amount'] = 0;
                    $memberBillSummaryData['interest_on_due_amount'] = 0;
                    $memberBillSummaryData['op_tax_arrears'] = 0;
                    $memberBillSummaryData['tax_balance'] = 0;
                    $memberBillSummaryData['tax_total'] = 0;
                }
            }
                    $appliedParams['bill_no'] = $billNo;
                    $memberBillSummaryData['bill_no'] = $billNo;
                    $memberBillSummaryData['month'] = $billGeneratedMonths;
                    $memberBillSummaryData['member_id'] = $memberId;
                    $memberBillSummaryData['bill_type'] = $billType;
                    $memberBillSummaryData['society_id'] = $this->societyloginID;
                    $memberBillSummaryData['flat_no'] = isset($memberDetails['flat_no']) ? $memberDetails['flat_no'] : '';
                    $memberBillSummaryData['monthly_amount'] = 0; //monthly member maintenance value
                    $memberBillSummaryData['monthly_principal_amount'] = 0; //monthly_amount - discount;
                    $memberBillSummaryData['discount'] = 0;
                    $memberBillSummaryData['monthly_bill_amount'] = 0; //monthly_principal_amount + interest_on_due_amount;
                    $memberBillSummaryData['member_transfer'] = $member_transfer; 
                    $memberBillSummaryData['amount_payable'] = 0; //op_due_amount + monthly_bill_amount;
                    $memberBillSummaryData['principal_paid'] = 0;
                    $memberBillSummaryData['interest_paid'] = 0;
                    $memberBillSummaryData['principal_adjusted'] = 0;
                    $memberBillSummaryData['interest_adjusted'] = 0;
                    $memberBillSummaryData['principal_balance'] = 0; //op_principal_arrears + monthly_principal_amount - principal_paid;
                    $memberBillSummaryData['interest_balance'] = 0; //op_interest_arrears + interest_on_due_amount - interest_paid;
                    $memberBillSummaryData['balance_amount'] = 0; //principal_balance + interest_balance;
                    $memberBillSummaryData['interest_adjusted'] = 0;
                    $memberBillSummaryData['igst_total'] = 0;
                    $memberBillSummaryData['cgst_total'] = 0;
                    $memberBillSummaryData['sgst_total'] = 0;
                    $memberBillSummaryData['tax_total'] = 0;
                    $memberBillSummaryData['tax_paid'] = 0;
                    $memberBillSummaryData['tax_adjusted'] = 0;
                    $memberBillSummaryData['interest_free_amount'] = 0;
                    $memberBillSummaryData['bill_tariff_type'] = $societyTariffParameterCheck['SocietyParameter']['tariff_id'];
                    $billGeneretedDate = $memberBillSummaryData['bill_generated_date'] = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : '';
                    $memberBillSummaryData['bill_due_date'] = (isset($postData['MemberBillGenerate']['bill_due_date']) && !empty($postData['MemberBillGenerate']['bill_due_date'])) ? trim($postData['MemberBillGenerate']['bill_due_date']) : NULL;
                    $memberBillSummaryData['cdate'] = $this->Util->getDateTime();
                    $memberBillSummaryData['udate'] = $this->Util->getDateTime();
                    $memberBillSummaryData['bill_frequency_id'] = $appliedParams['bill_frequency_id'] = $societyTariffParameterCheck['SocietyParameter']['billing_frequency_id'];
                    $memberBillSummaryData['bill_end_date'] = $this->getBillEndDate($societyTariffParameterCheck,$billGeneretedDate);
                    $memberBillSummaryData['financial_year_id'] = $this->financialYearId; 
                    //member tariff for supplementary bill
                    if (isset($postData['SupplementaryBillGenerate'])) {
                        $tariffCounter = 0;
                        $supUnitType = '';
                        foreach ($postData['SupplementaryBillGenerate'] as $ledgerHeadID => $tarrifData) {
                            // removed on 05/02/2022 to genarate bill with 0 unite type
                            // if (isset($tarrifData['rateUnitType']) && $tarrifData['rateUnitType'] != '' && !empty($tarrifData['rate'])) {
                                $billGenerateArr[$tariffCounter]['financial_year_id'] = $this->financialYearId; 
                                $billGenerateArr[$tariffCounter]['member_id'] = isset($memberDetails['id']) ? $memberDetails['id'] : $memberId;
                                $billGenerateArr[$tariffCounter]['bill_generated_date'] = isset($postData['MemberBillGenerate']['bill_generated_date']) ? trim($postData['MemberBillGenerate']['bill_generated_date']) : NULL;
                                $billGeneratedMonths = $billGenerateArr[$tariffCounter]['month'] = (isset($postData['MemberBillGenerate']['month']) && !empty($postData['MemberBillGenerate']['month'])) ? $postData['MemberBillGenerate']['month'] : NULL;
                                $billGenerateArr[$tariffCounter]['bill_number'] = isset($billNo) ? $billNo : 0;
                                 $supUnitType = $tarrifData['rateUnitType'];
                                switch ($tarrifData['rateUnitType']) {
                                    case "Actual":
                                        $billGenerateArr[$tariffCounter]['amount'] = isset($tarrifData['rate']) ? $tarrifData['rate'] : 0;
                                        break;
                                    case "Area":
                                        $billGenerateArr[$tariffCounter]['amount'] = isset($tarrifData['rate']) ? ($tarrifData['rate'] * $memberDetails['area']) : 0;
                                        break;
                                    default:
                                        $billGenerateArr[$tariffCounter]['amount'] = 0.00;
                                        break;
                                }
                               
                                $billGenerateArr[$tariffCounter]['ledger_head_id'] = $ledgerHeadID;
                                $billGenerateArr[$tariffCounter]['society_id'] = $this->societyloginID;
                                $billGenerateArr[$tariffCounter]['bill_type'] = $billType;
                                $billGenerateArr[$tariffCounter]['cdate'] = $this->Util->getDateTime();
                                $memberBillSummaryData['monthly_amount'] += isset($billGenerateArr[$tariffCounter]['amount']) ? $billGenerateArr[$tariffCounter]['amount'] : 0;
                               
                                $taxStatus = $this->checkTaxesApplicableForLedgerHead($ledgerHeadID);
                                //If taxes applicable
                                if ($taxStatus) {
                                    $taxAmount = 0;
                                    if ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] > 0) {
                                        $igstTaxAmount = $billGenerateArr[$tariffCounter]['amount'] * ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] / 100);
                                        $billGenerateArr[$tariffCounter]['igst_total'] = $igstTaxAmount;
                                        $taxAmount += $igstTaxAmount;
                                    }
                                    if ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] > 0) {
                                        $cgstTaxAmount = $billGenerateArr[$tariffCounter]['amount'] * ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] / 100);
                                        $billGenerateArr[$tariffCounter]['cgst_total'] = $cgstTaxAmount;
                                        $taxAmount += $cgstTaxAmount;
                                    }
                                    if ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] > 0) {
                                        $sgstTaxAmount = $billGenerateArr[$tariffCounter]['amount'] * ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] / 100);
                                        $billGenerateArr[$tariffCounter]['sgst_total'] = $sgstTaxAmount;
                                        $taxAmount += $sgstTaxAmount;
                                    }
                                    $memberBillSummaryData['tax_total'] = $memberBillSummaryData['tax_total'] + $taxAmount;
                                    $billGenerateArr[$tariffCounter]['tax_total'] = $taxAmount;
                                    $memberBillSummaryData['monthly_amount'] += $taxAmount;
                                    $memberBillSummaryData['tax_balance'] += $taxAmount;
                                }
                            // }
                            $tariffCounter++;
                        }
                    }

                    $appliedParams['supp_unit_type'] = $supUnitType;

                    if (isset($billGenerateArr) && count($billGenerateArr) > 0) {
                        
                        
                        $memberBillSummaryData['monthly_principal_amount'] = $memberBillSummaryData['monthly_amount'] - $memberBillSummaryData['discount'];
                        $memberBillSummaryData['monthly_bill_amount'] = $memberBillSummaryData['monthly_principal_amount'] + $memberBillSummaryData['interest_on_due_amount'];
                        $memberBillSummaryData['amount_payable'] = $memberBillSummaryData['op_due_amount'] + $memberBillSummaryData['monthly_bill_amount'];                   
                        
                        if($memberBillSummaryData['op_due_amount'] < 0 ){ // in case of advance payment
                            $memberBillSummaryData['principal_balance'] = $memberBillSummaryData['monthly_principal_amount'] - $memberBillSummaryData['principal_paid'] ;
                            $memberBillSummaryData['interest_balance'] =  $memberBillSummaryData['interest_on_due_amount'] - $memberBillSummaryData['interest_paid'];
                            $tempDueAmount = abs($memberBillSummaryData['op_due_amount']);
                            $this->updateGeneratedBillBalanceAmountInAdvance($memberBillSummaryData,$tempDueAmount);
                        }
                        else{
                            $memberBillSummaryData['principal_balance'] = ($memberBillSummaryData['op_principal_arrears'] + $memberBillSummaryData['monthly_principal_amount']) - $memberBillSummaryData['principal_paid'] ;
                            $memberBillSummaryData['interest_balance'] = ($memberBillSummaryData['op_interest_arrears'] + $memberBillSummaryData['interest_on_due_amount']) - $memberBillSummaryData['interest_paid'];
                        }
                        
                        $memberBillSummaryData['balance_amount'] = $memberBillSummaryData['principal_balance'] + $memberBillSummaryData['interest_balance'];

                        if($this->MemberBillGenerate->saveAll($billGenerateArr)){ 
                            $this->MemberBillSummary->create();
                            if ($this->MemberBillSummary->save($memberBillSummaryData)) {
                                array_push($this->appliedParmaOnBill,$appliedParams);

                                $responseError['error'] = 0;
                                $responseError['error_message'] = 'Supplementary bill has been generated successfully.';
                            } else {
                                $responseError['error'] = 1;
                                $responseError['error_message'] = 'Supplementary bill count not generated.';
                            }
                        }
                        else{
                            $responseError['error'] = 1;
                            $responseError['error_message'] = 'Supplementary bill count not generated.';
                        }
                    }
                } else {
                    $responseError['error'] = 1;
                    $responseError['error_message'] = 'Members Supplementary Bill already exists in the system with the same month .';
                }
                return $responseError;
            }







    public function checkTaxesApplicableForLedgerHead($ledgerHeadID = null) {
        $societyLedgerHeadList = $this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $this->Session->read('Auth.User.id'), 'SocietyLedgerHeads.id' => (int) $ledgerHeadID, 'SocietyLedgerHeads.is_in_bill_charges' => 1), 'fields' => array('SocietyLedgerHeads.id', 'SocietyLedgerHeads.is_tax_applicable', 'SocietyLedgerHeads.is_rebate_applicable', 'SocietyLedgerHeads.is_interest_free'), 'recursive' => -1));
            if (isset($societyLedgerHeadList['SocietyLedgerHeads'])) {
                if ($societyLedgerHeadList['SocietyLedgerHeads']['is_tax_applicable'] == 1) {
                    return true;
                }
            }
        return false;
    }







    public function memberRegularBillLedgerHeadSettings() {
        $societyLedgerHeadList = $this->SocietyLedgerHeads->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $this->Session->read('Auth.User.id'), 'SocietyLedgerHeads.is_in_bill_charges' => 1), 'fields' => array('SocietyLedgerHeads.id', 'SocietyLedgerHeads.is_tax_applicable', 'SocietyLedgerHeads.is_rebate_applicable', 'SocietyLedgerHeads.is_interest_free'), 'recursive' => -1));
        $billLedgerHeadSettings = array();
        foreach ($societyLedgerHeadList as $headDetails) {
            if ($headDetails['SocietyLedgerHeads']['is_tax_applicable'] == 1) {
                $billLedgerHeadSettings['tax'][] = $headDetails['SocietyLedgerHeads']['id'];
            }
            if ($headDetails['SocietyLedgerHeads']['is_rebate_applicable'] == 1) {
                $billLedgerHeadSettings['rebate'][] = $headDetails['SocietyLedgerHeads']['id'];
            }
            if ($headDetails['SocietyLedgerHeads']['is_interest_free'] == 1) {
                $billLedgerHeadSettings['interestFree'][] = $headDetails['SocietyLedgerHeads']['id'];
            }
        }
        return $billLedgerHeadSettings;
    }

    public function updateAppliedParameterOnBill($billNo = array(),$billType = 'reg',$societyTariffParameterCheck){
        $this->loadModel('BillAppliedParameter');
        $methodId = $societyTariffParameterCheck['SocietyParameter']['method_id'];
        $interestTypeId = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];

        return $this->BillAppliedParameter->updateAll(array('BillAppliedParameter.method_id' => $methodId,'BillAppliedParameter.interest_type_id' => $interestTypeId),array('BillAppliedParameter.bill_no ' => $billNo,'BillAppliedParameter.bill_type ' => $billType));
    }




    public function updateMemberTarrifDetails($postData = array(),$billType) {


        if (!empty($postData)) {

            $societyLoginId = $this->societyloginID;
            $memberID = isset($postData['MemberBillSummary']['member_id']) ? $postData['MemberBillSummary']['member_id'] : '';
            $billMonth = (isset($postData['MemberBillSummary']['month'])) ? $postData['MemberBillSummary']['month'] :( isset($postData['society_member_bill_next_month']) ? $postData['society_member_bill_next_month'] :'');

            $billNo = isset($postData['MemberBillSummary']['bill_no']) ? $postData['MemberBillSummary']['bill_no'] : '';
            $billGeneratedDate = isset($postData['MemberBillSummary']['bill_generated_date']) ? $postData['MemberBillSummary']['bill_generated_date'] : '';
            $oldMonthlyAmount = 0;
            $totalMonthlyAmount = 0;
            $billGenerateData = array();
            $billGeneratedDataToUpdate = array();
            
            $discount = isset($postData['MemberBillSummary']['discount']) ? $postData['MemberBillSummary']['discount'] : 0;
            $principalAdjusted = isset($postData['MemberBillSummary']['principal_adjusted']) ? $postData['MemberBillSummary']['principal_adjusted'] : 0;
            $interestAdjusted = isset($postData['MemberBillSummary']['interest_adjusted']) ? $postData['MemberBillSummary']['interest_adjusted'] : 0;
            $interestOnDueAmt = isset($postData['MemberBillSummary']['interest_on_due_amount']) ? $postData['MemberBillSummary']['interest_on_due_amount'] : 0;
            
            
            $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id'))));
            $multiplyTariffAmountValue = $this->SocietyBill->multiplyTariffAmountByBillingFrequency();
            $billLedgerHeadSettings = $this->memberRegularBillLedgerHeadSettings();
            

            if (isset($postData['MemberTariff']) && !empty($postData['MemberTariff'])) {
                $count = 0;
                $igstTaxAmount = $cgstTaxAmount = $sgstTaxAmount = $tax_total = $igstTotalTaxAmount = $cgstTotalTaxAmount = $sgstTotalTaxAmount = $taxTotalAmount = 0;

                foreach ($postData['MemberTariff'] as $societyLedgerHeadId => $ledgerData) {
                    $totalMonthlyAmount += $ledgerData['amount'];
                    if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['tax']) && in_array($societyLedgerHeadId, $billLedgerHeadSettings['tax'])) {
                        if ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] > 0) {
                            $igstTaxAmount = ($ledgerData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] / 100);
                            $igstTotalTaxAmount += $igstTaxAmount;
                        }

                        if ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] > 0) {
                            $cgstTaxAmount = ($ledgerData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] / 100);
                            $cgstTotalTaxAmount += $cgstTaxAmount;
                        }

                        if ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] > 0) {
                            $sgstTaxAmount = ($ledgerData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] / 100);
                            $sgstTotalTaxAmount += $sgstTaxAmount;
                        }

                        $tax_total = $igstTaxAmount + $cgstTaxAmount + $sgstTaxAmount;
                        $taxTotalAmount += $tax_total;
                    } 
                    else {
                        $igstTaxAmount = $cgstTaxAmount = $sgstTaxAmount = $tax_total = 0;
                    }

                    $condtitionArr = array('MemberBillGenerate.society_id' => $societyLoginId, 'MemberBillGenerate.month' => $billMonth, 'MemberBillGenerate.ledger_head_id' => $societyLedgerHeadId, 'MemberBillGenerate.member_id' => $memberID,'MemberBillGenerate.financial_year_id' => $this->financialYearId);
                    
                    if ($this->MemberBillGenerate->hasAny($condtitionArr)) {
                        // financial_year_id must be in the WHERE, not only in the hasAny
                        // check above: without it this matched the same month in every
                        // year, so editing one year's charge rewrote that charge on the
                        // member's bill for every year. The summary updateAll below is
                        // already scoped this way.
                        $this->MemberBillGenerate->updateAll(array('MemberBillGenerate.amount' => "'$ledgerData[amount]'", 'MemberBillGenerate.igst_total' => "$igstTaxAmount", 'MemberBillGenerate.cgst_total' => "$cgstTaxAmount", 'MemberBillGenerate.sgst_total' => "$sgstTaxAmount", 'MemberBillGenerate.tax_total' => "$tax_total"), array('MemberBillGenerate.society_id' => $societyLoginId, 'MemberBillGenerate.month' => $billMonth, 'MemberBillGenerate.ledger_head_id' => $societyLedgerHeadId, 'MemberBillGenerate.member_id' => $memberID, 'MemberBillGenerate.bill_type' => $billType, 'MemberBillGenerate.financial_year_id' => $this->financialYearId));
                    } else {
                        $count++;
                        $billGenerateData[$count]['id'] = null;
                        $billGenerateData[$count]['member_id'] = $memberID;
                        $billGenerateData[$count]['bill_type'] = $billType;
                        $billGenerateData[$count]['society_id'] = $societyLoginId;
                        $billGenerateData[$count]['month'] = $billMonth;
                        $billGenerateData[$count]['ledger_head_id'] = $societyLedgerHeadId;
                        $billGenerateData[$count]['amount'] = $ledgerData['amount'];
                        $billGenerateData[$count]['igst_total'] = $igstTaxAmount;
                        $billGenerateData[$count]['cgst_total'] = $cgstTaxAmount;
                        $billGenerateData[$count]['sgst_total'] = $sgstTaxAmount;
                        $billGenerateData[$count]['tax_total'] = $tax_total;
                        $billGenerateData[$count]['bill_number'] = $billNo;
                        $billGenerateData[$count]['bill_generated_date'] = $billGeneratedDate;
                        $billGenerateData[$count]['financial_year_id'] = $this->financialYearId;
                        // $billGenerateData[$count]['bill_generated_date'] = $this->Util->getDateTime();
                    }
                }
            }

            if (!empty($billGenerateData)) {
                $insertBillGeneratedRes = $this->MemberBillGenerate->saveAll($billGenerateData);
                if(!$insertBillGeneratedRes) return false;
            }

                $billUpdatedData = array(
                    'MemberBillSummary.monthly_amount' => "'$totalMonthlyAmount'",
                    'MemberBillSummary.igst_total' => "'$igstTotalTaxAmount'",
                    'MemberBillSummary.cgst_total' => "'$cgstTotalTaxAmount'", 
                    'MemberBillSummary.sgst_total' => "'$sgstTotalTaxAmount'", 
                    'MemberBillSummary.tax_total' => "'$taxTotalAmount'",
                    'MemberBillSummary.discount' => $discount,
                    'MemberBillSummary.principal_adjusted' => $principalAdjusted,
                    'MemberBillSummary.interest_adjusted' => $interestAdjusted,
                    'MemberBillSummary.interest_on_due_amount' => $interestOnDueAmt
                    );
                $billSummaryUpdatedRes = $this->MemberBillSummary->updateAll($billUpdatedData, array('MemberBillSummary.society_id' => $societyLoginId, 'MemberBillSummary.bill_no' => $billNo, 'MemberBillSummary.month' => $billMonth, 'MemberBillSummary.member_id' => $memberID,'MemberBillSummary.financial_year_id' => $this->financialYearId));
                if($billSummaryUpdatedRes){
                    $this->updateAppliedParameterOnBill(array($billNo),$billType,$societyTariffParameterCheck);
                }
                else{  
                    return false;
                }

            return true;
        }
        return false;
    }

    public function updateMemberTarrifDetails_bkOM($postData = array()) {



        if (!empty($postData)) {



            $societyLoginId = $this->societyloginID;



            $memberID = isset($postData['MemberBillSummary']['member_id']) ? $postData['MemberBillSummary']['member_id'] : '';



            $billMonth = isset($postData['MemberBillSummary']['month']) ? $postData['MemberBillSummary']['month'] : '';



            $billNo = isset($postData['MemberBillSummary']['bill_no']) ? $postData['MemberBillSummary']['bill_no'] : '';



            $billGeneratedDate = isset($postData['MemberBillSummary']['bill_generated_date']) ? $postData['MemberBillSummary']['bill_generated_date'] : '';



            $oldMonthlyAmount = 0;



            $totalMonthlyAmount = 0;



            $billGenerateData = array();



            $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id'))));



            $multiplyTariffAmountValue = $this->SocietyBill->multiplyTariffAmountByBillingFrequency();



            $billLedgerHeadSettings = $this->memberRegularBillLedgerHeadSettings();







            if (isset($postData['MemberTariff']) && !empty($postData['MemberTariff'])) {



                $count = 0;



                $igstTaxAmount = $cgstTaxAmount = $sgstTaxAmount = $tax_total = $igstTotalTaxAmount = $cgstTotalTaxAmount = $sgstTotalTaxAmount = $taxTotalAmount = 0;



                foreach ($postData['MemberTariff'] as $societyLedgerHeadId => $ledgerData) {



                    $totalMonthlyAmount += $ledgerData['amount'];



                    if (!empty($billLedgerHeadSettings) && isset($billLedgerHeadSettings['tax']) && in_array($societyLedgerHeadId, $billLedgerHeadSettings['tax'])) {



                        if ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] > 0) {



                            $igstTaxAmount = ($ledgerData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['igst_tax_per'] / 100);



                            $igstTotalTaxAmount += $igstTaxAmount;



                        }



                        if ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] > 0) {



                            $cgstTaxAmount = ($ledgerData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['cgst_tax_per'] / 100);



                            $cgstTotalTaxAmount += $cgstTaxAmount;



                        }



                        if ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] > 0) {



                            $sgstTaxAmount = ($ledgerData['amount'] * $multiplyTariffAmountValue) * ($societyTariffParameterCheck['SocietyParameter']['sgst_tax_per'] / 100);



                            $sgstTotalTaxAmount += $sgstTaxAmount;



                        }



                        $tax_total = $igstTaxAmount + $cgstTaxAmount + $sgstTaxAmount;



                        $taxTotalAmount += $tax_total;



                        $totalMonthlyAmount += $tax_total;



                    } else {



                        $igstTaxAmount = $cgstTaxAmount = $sgstTaxAmount = $tax_total = 0;



                    }







                    $condtitionArr = array('MemberBillGenerate.society_id' => $societyLoginId, 'MemberBillGenerate.month' => $billMonth, 'MemberBillGenerate.ledger_head_id' => $societyLedgerHeadId, 'MemberBillGenerate.member_id' => $memberID);



                    if ($this->MemberBillGenerate->hasAny($condtitionArr)) {



                        $this->MemberBillGenerate->updateAll(array('MemberBillGenerate.amount' => "'$ledgerData[amount]'", 'MemberBillGenerate.igst_total' => "$igstTaxAmount", 'MemberBillGenerate.cgst_total' => "$cgstTaxAmount", 'MemberBillGenerate.sgst_total' => "$sgstTaxAmount", 'MemberBillGenerate.tax_total' => "$tax_total"), array('MemberBillGenerate.society_id' => $societyLoginId, 'MemberBillGenerate.month' => $billMonth, 'MemberBillGenerate.ledger_head_id' => $societyLedgerHeadId, 'MemberBillGenerate.member_id' => $memberID));



                    } else {



                        $count++;



                        $billGenerateData[$count]['id'] = null;



                        $billGenerateData[$count]['member_id'] = $memberID;



                        $billGenerateData[$count]['society_id'] = $societyLoginId;



                        $billGenerateData[$count]['month'] = $billMonth;



                        $billGenerateData[$count]['ledger_head_id'] = $societyLedgerHeadId;



                        $billGenerateData[$count]['amount'] = $ledgerData['amount'];



                        $billGenerateData[$count]['igst_total'] = $igstTaxAmount;



                        $billGenerateData[$count]['cgst_total'] = $cgstTaxAmount;



                        $billGenerateData[$count]['sgst_total'] = $sgstTaxAmount;



                        $billGenerateData[$count]['tax_total'] = $tax_total;



                        $billGenerateData[$count]['bill_number'] = $billNo;



                        $billGenerateData[$count]['bill_generated_date'] = $billGeneratedDate;



                        $billGenerateData[$count]['bill_generated_date'] = $this->Util->getDateTime();



                    }



                }



            }







            if (!empty($billGenerateData)) {



                $this->MemberBillGenerate->saveAll($billGenerateData);



            }







            $billSummaryData = $this->MemberBillSummary->find('first', array('conditions' => array('MemberBillSummary.society_id' => $societyLoginId, 'MemberBillSummary.bill_no' => $billNo, 'MemberBillSummary.month' => $billMonth, 'MemberBillSummary.member_id' => $memberID), 'recursive' => -1));



            if (isset($billSummaryData['MemberBillSummary']) && !empty($billSummaryData['MemberBillSummary'])) {



                (int) $oldMonthlyAmount = $billSummaryData['MemberBillSummary']['monthly_amount'];



            }



            if ($oldMonthlyAmount != $totalMonthlyAmount) {



                $this->MemberBillSummary->updateAll(array('MemberBillSummary.monthly_amount' => "'$totalMonthlyAmount'", 'MemberBillSummary.igst_total' => "'$igstTotalTaxAmount'", 'MemberBillSummary.cgst_total' => "'$cgstTotalTaxAmount'", 'MemberBillSummary.sgst_total' => "'$sgstTotalTaxAmount'", 'MemberBillSummary.tax_total' => "'$taxTotalAmount'"), array('MemberBillSummary.society_id' => $societyLoginId, 'MemberBillSummary.bill_no' => $billNo, 'MemberBillSummary.month' => $billMonth, 'MemberBillSummary.member_id' => $memberID));



            }



            return true;



        }



        return false;



    }











    public function society_generated_bills() {
         error_reporting(E_ALL);
         ini_set("display_errors",1);

        $this->Member->Behaviors->attach('Containable');
        $this->loadModel('MemberTransactionView');
        $financialYearId = $this->Util->getFinancialYear();
        $postData = $this->request->data;
        $societyId = $this->societyloginID;
        $from_date = $postData['MemberBillSummary']['from_date'];
        $to_date = $postData['MemberBillSummary']['to_date'];
        $memberBillSummaryDataArr = array();
         
        if(isset($from_date) && isset($to_date)){
        
            $this->Session->write('date.from_date', $from_date);
            $this->Session->write('date.to_date', $to_date);



            $memberBillSummary = $this->MemberTransactionView->find('all',array('conditions' => array('MemberTransactionView.society_id' => $societyId ,'financial_year_id' => $financialYearId,'bill_generated_date BETWEEN ? and ?' => [$from_date,$to_date])));

              if (!empty($memberBillSummary)) {
                $billIdArray = [];
                foreach($memberBillSummary as $summary){
                    $data = $summary['MemberTransactionView'];
                    $billid = $data['id'];
                    if(!in_array($billid,$billIdArray)){
                        $memberBillSummaryDataArr[] = $data;    
                        array_push($billIdArray,$billid);
                    }
                    
                }                    
            }
    	}
        $this->set(compact('memberBillSummaryDataArr','from_date','to_date'));
    }







    public function delete_generated_bills($billGeneratedId = null) {



        if (!$this->MemberBillSummary->exists($billGeneratedId)) {



            $this->Session->setFlash(__('Requested bill is not found. Please, try again.'), 'error');



            $this->redirect(array('action' => 'society_generated_bills'));



        }



        $billSummaryDetails = $this->MemberBillSummary->find('first', array('conditions' => array('MemberBillSummary.id' => $billGeneratedId, 'MemberBillSummary.society_id' => $this->societyloginID), 'order' => array('MemberBillSummary.bill_no' => 'asc'), 'recursive' => -1));



        if (isset($billSummaryDetails) && !empty($billSummaryDetails)) {



            $paymentCondition = array('MemberPayment.bill_generated_id' => $billSummaryDetails['MemberBillSummary']['bill_no'], 'MemberPayment.society_id' => $this->societyloginID);



            if ($this->Member->MemberPayment->hasAny($paymentCondition)) {



                $memberPaymentData = $this->Member->MemberPayment->find('first', array('conditions' => $paymentCondition, 'order' => array('MemberPayment.bill_generated_id' => 'asc'), 'recursive' => -1));



                if (isset($memberPaymentData) && !empty($memberPaymentData)) {



                    $this->Session->setFlash(__('Payment .'), 'error');



                }



            }





            $this->MemberBillSummary->id = $billGeneratedId;



            if ($this->MemberBillSummary->delete()) {



                $this->Session->setFlash(__('The bill has been deleted.'), 'success');



            } else {



                $this->Session->setFlash(__('The bill could not be deleted. Please, try again.'), 'error');



            }



            return $this->redirect(array('action' => 'society_generated_bills'));



        }



    }







    public function updateMemberPaymentByBillSummaryDetails($memberBillSummaryDetails = array()) {



        $aData['society_id'] = isset($memberBillSummaryDetails['MemberBillSummary']['society_id']) ? $memberBillSummaryDetails['MemberBillSummary']['society_id'] : '';



        $aData['id'] = NULL;



        $aData['member_id'] = isset($memberBillSummaryDetails['MemberBillSummary']['member_id']) ? $memberBillSummaryDetails['MemberBillSummary']['member_id'] : '';



        $aData['society_bank_id'] = isset($memberBillSummaryDetails['MemberBillSummary']['society_bank_id']) ? $memberBillSummaryDetails['MemberBillSummary']['society_bank_id'] : '';



        $aData['amount_paid'] = isset($memberBillSummaryDetails['MemberBillSummary']['principal_paid']) ? $memberBillSummaryDetails['MemberBillSummary']['principal_paid'] : 0.00;



        $aData['payment_mode'] = isset($memberBillSummaryDetails['MemberBillSummary']['payment_mode']) ? $memberBillSummaryDetails['MemberBillSummary']['payment_mode'] : '';



        $aData['cheque_reference_number'] = isset($memberBillSummaryDetails['MemberBillSummary']['cheque_reference_number']) ? $memberBillSummaryDetails['MemberBillSummary']['cheque_reference_number'] : 0;



        $aData['payment_date'] = isset($memberBillSummaryDetails['MemberBillSummary']['bill_generated_date']) ? $memberBillSummaryDetails['MemberBillSummary']['bill_generated_date'] : '';



        $aData['member_bank_id'] = isset($memberBillSummaryDetails['MemberBillSummary']['member_bank_id']) ? $memberBillSummaryDetails['MemberBillSummary']['member_bank_id'] : '';



        $aData['member_bank_ifsc'] = isset($memberBillSummaryDetails['MemberBillSummary']['member_bank_ifsc']) ? $memberBillSummaryDetails['MemberBillSummary']['member_bank_ifsc'] : '';



        $aData['member_bank_branch'] = isset($memberBillSummaryDetails['MemberBillSummary']['member_bank_branch']) ? $memberBillSummaryDetails['MemberBillSummary']['member_bank_branch'] : '';



        $aData['bill_type'] = isset($memberBillSummaryDetails['MemberBillSummary']['bill_type']) ? trim($memberBillSummaryDetails['MemberBillSummary']['bill_type']) : '';



        $aData['bill_month'] = isset($memberBillSummaryDetails['MemberBillSummary']['month']) ? trim($memberBillSummaryDetails['MemberBillSummary']['month']) : '';



        $aData['bill_generated_id'] = isset($memberBillSummaryDetails['MemberBillSummary']['bill_no']) ? trim($memberBillSummaryDetails['MemberBillSummary']['bill_no']) : '';



        $aData['entry_date'] = $this->Util->getDateTime();



        $aData['cdate'] = $this->Util->getDateTime();



        $aData['udate'] = $this->Util->getDateTime();



        if ($this->Member->MemberPayment->save($aData)) {



            return true;



        }



    }



    



    public function delete_all_bills_payments(){

        $societyLoginId = $this->societyloginID;
        $financialYearId = $this->Util->getFinancialYear();
        $postData = $this->request->data;
 
        $from_date = isset($postData['MemberBillSummary']['from_date']) ?$postData['MemberBillSummary']['from_date'] : '';
        $to_date = isset($postData['MemberBillSummary']['to_date']) ? $postData['MemberBillSummary']['to_date'] : '';

        // Only fall back to the range last listed on screen when the form did not
        // carry one. This used to overwrite the posted dates unconditionally, so
        // the period the user picked was discarded and the last listed range was
        // always what got deleted.
        if ($from_date === '') { $from_date = (string)$this->Session->read('date.from_date'); }
        if ($to_date === '')   { $to_date   = (string)$this->Session->read('date.to_date'); }

        // isset() cannot guard this - both variables always exist by now. An empty
        // string would leave every `date >= ''` comparison matching, wiping the
        // entire financial year, so require a real range instead.
        if ($from_date === '' || $to_date === '') {
            $this->Session->setFlash(__('Select a From and To date before deleting bills.'), 'error');
            $this->redirect(array('action' => 'society_generated_bills'));
            return;
        }

        // NOTE: deleteSocietyMemberIdentifications() takes no date range - it clears
        // every member identification for the society for the whole financial year,
        // regardless of the period selected above. Left as-is because narrowing it
        // changes what a transfer record means; raise before relying on this action.
        $this->deleteSocietyMemberIdentifications($societyLoginId);
        $this->deleteSocietyMemberBillGenerateData($from_date,$to_date,$societyLoginId);
        $this->deleteSocietyMemberBillSummaryData($from_date,$to_date,$societyLoginId);
        $this->deleteSocietyMemberPaymentData($from_date,$to_date,$societyLoginId);
        $this->deleteSocietyMemberJournalData($from_date,$to_date,$societyLoginId);

        $this->layout = false;
        $this->redirect(array('action' => 'society_generated_bills'));
    }

        # society specific deletion starts

        public function deleteSocietyMemberIdentifications($societyId){
             $this->loadModel('MemberIdentification');
             return $this->MemberIdentification->deleteAll(array('financial_year_id' => $this->financialYearId ,'society_id' =>$societyId ));
        }

        public function deleteSocietyMemberBillGenerateData($from_date,$to_date,$societyId){
            $this->loadModel('MemberBillGenerate');  // bill_generated_date          
            return $this->MemberBillGenerate->deleteAll(array('MemberBillGenerate.financial_year_id' => $this->financialYearId,'MemberBillGenerate.society_id' => $societyId,'bill_generated_date >= ' => $from_date,'bill_generated_date <= ' => $to_date  ),false);
        }

        public function deleteSocietyMemberBillSummaryData($from_date,$to_date,$societyId){                
            $this->loadModel('MemberBillSummary'); // bill_generated_date
            return $this->MemberBillSummary->deleteAll(array('financial_year_id' => $this->financialYearId,'MemberBillSummary.society_id' => $societyId,'bill_generated_date >= ' => $from_date,'bill_generated_date <= ' => $to_date ),false);
        }
        public function deleteSocietyMemberPaymentData($from_date,$to_date,$societyId){     
            $this->loadModel('MemberPayment');     // payment_date  
            return $this->MemberPayment->deleteAll(array('financial_year_id' => $this->financialYearId,'MemberPayment.society_id' => $societyId,'payment_date >= ' => $from_date,'payment_date <= ' => $to_date  ),false);
        }

         public function deleteSocietyMemberJournalData($from_date,$to_date,$societyId){ 
            $this->loadModel('JournalVoucher');           // voucher_date
            return $this->JournalVoucher->deleteAll(array('financial_year_id' => $this->financialYearId,'society_id' => $societyId,'voucher_date >= ' => $from_date,'voucher_date <= ' => $to_date  ),false);
        }

        # society specific deletion ends


        public function deleteMemberYearClosingBalance($from_date,$to_date){
            $this->loadModel('YearWiseClosingBalance'); // bill_generated_date
            return $this->YearWiseClosingBalance->deleteAll(array('year_id' => $this->financialYearId ),false);
        }

        public function deleteMemberBillSummaryData($from_date,$to_date){                
            $this->loadModel('MemberBillSummary'); // bill_generated_date
            return $this->MemberBillSummary->deleteAll(array('financial_year_id' => $this->financialYearId,'bill_generated_date >= ' => $from_date,'bill_generated_date <= ' => $to_date ),false);
        }

        public function deleteMemberBillGenerateData($from_date,$to_date){
            $this->loadModel('MemberBillGenerate');  // bill_generated_date          
            return $this->MemberBillGenerate->deleteAll(array('MemberBillGenerate.financial_year_id' => $this->financialYearId,'bill_generated_date >= ' => $from_date,'bill_generated_date <= ' => $to_date  ),false);
        }

        public function deleteMemberPaymentData($from_date,$to_date){     
            $this->loadModel('MemberPayment');     // payment_date  
            return $this->MemberPayment->deleteAll(array('financial_year_id' => $this->financialYearId,'payment_date >= ' => $from_date,'payment_date <= ' => $to_date  ),false);
        }

        public function deleteMemberChequeReturnData($from_date,$to_date){    
            $this->loadModel('ChequeReturnData');          
            return $this->ChequeReturnData->deleteAll(array('financial_year_id' => $this->financialYearId,'bill_generated_date >= ' => $from_date,'bill_generated_date <= ' => $to_date  ),false);
        }

        public function deleteMemberJournalData($from_date,$to_date){ 
            $this->loadModel('JournalVoucher');           // voucher_date
            return $this->JournalVoucher->deleteAll(array('financial_year_id' => $this->financialYearId,'voucher_date >= ' => $from_date,'voucher_date <= ' => $to_date  ),false);
        }



    



    public function deleteSettlement($societyId){



        $members = $this->Member->find('all', array('conditions' => array('Member.society_id' => $societyId)));



        foreach ($members as $member){



            $memberDetails = $member['Member'];



            $memberId = $memberDetails['id'];



            $this->MemberBillSettlement->deleteAll(array('MemberBillSettlement.member_id' => $memberId));



        }



    }



    



    public function upadate_last_member_bill($lastBillGeneratedId = null){



        $societyLoginId = $this->societyloginID;



        $societyParameters = $this->SocietyBill->getSocietyParameters();



        if (isset($societyParameters['SocietyParameter'])) {



            $societyBillingFrequencyId = $societyParameters['SocietyParameter']['billing_frequency_id'];



            $billingMonthArray = $this->Util->societyBillingFrequency($societyBillingFrequencyId);



        }



        if($lastBillGeneratedId != ''){



            $lastBillGeneratedData = $this->MemberBillSummary->find('first', array('conditions' => array('MemberBillSummary.id' => $lastBillGeneratedId, 'MemberBillSummary.society_id' => $societyLoginId), 'recursive' => -1));



        }



        



        if ($this->request->is('post', 'put')) {



            $postData['id'] = isset($this->request->data['UpdateMemberBillSummary']['id']) ? $this->request->data['UpdateUpdateMemberBillSummary']['id'] : '';



            $postData['discount'] = isset($this->request->data['UpdateMemberBillSummary']['discount']) ? $this->request->data['UpdateMemberBillSummary']['discount'] : '';



            $postData['principal_adjusted'] = isset($this->request->data['UpdateMemberBillSummary']['principal_adjusted']) ? $this->request->data['UpdateMemberBillSummary']['principal_adjusted'] : 0.00;



            $postData['interest_adjusted'] = isset($this->request->data['UpdateMemberBillSummary']['interest_adjusted']) ? $this->request->data['UpdateMemberBillSummary']['interest_adjusted'] : 0.00;



            $postData['member_id'] = isset($this->request->data['UpdateMemberBillSummary']['member_id']) ? $this->request->data['UpdateMemberBillSummary']['member_id'] : '';



            $postData['month'] = isset($this->request->data['UpdateMemberBillSummary']['month']) ? $this->request->data['UpdateMemberBillSummary']['month'] : '';



            $postData['bill_no'] = isset($this->request->data['UpdateMemberBillSummary']['bill_no']) ? $this->request->data['UpdateMemberBillSummary']['bill_no'] : '';



            $postData['interest_on_due_amount'] = isset($this->request->data['UpdateMemberBillSummary']['interest_on_due_amount']) ? $this->request->data['UpdateMemberBillSummary']['interest_on_due_amount'] : '';



            $conditionsArr = array('UpdateMemberBillSummary.member_id' => $postData['member_id'], 'UpdateMemberBillSummary.society_id' => $societyLoginId, 'UpdateMemberBillSummary.id >=' => $postData['id']);



            $memberBillSummaryDetails = $this->UpdateMemberBillSummary->find('all', array('conditions' => $conditionsArr, 'recursive' => -1, 'order' => array('UpdateMemberBillSummary.id' => 'asc')));



            $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id')));







            $billSummaryCounter = 0;



            $lastMonthCount = 0;



            if (isset($memberBillSummaryDetails) && count($memberBillSummaryDetails) > 0) {



                foreach ($memberBillSummaryDetails as $billData) {



                    if (!empty($billData)) {



                        if ($billSummaryCounter == 0) {



                            /*$memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_adjusted'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_adjusted'] + $postData['principal_adjusted'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_adjusted'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_adjusted'] + $postData['interest_adjusted'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['discount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['discount'] + $postData['discount'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'] + $postData['interest_on_due_amount'];



                            |*/



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_adjusted'] = $postData['principal_adjusted'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_adjusted'] = $postData['interest_adjusted'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['discount'] = $postData['discount'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'] = $postData['interest_on_due_amount'];



                            



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_amount'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['discount'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['amount_payable'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_due_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount'];







                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] = ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_principal_arrears'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount']) - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_paid'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_adjusted'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] = ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_interest_arrears'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount']) - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_paid'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_adjusted'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_tax_arrears'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_paid'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];



                        } else {



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_tax_arrears'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['tax_balance']) ? $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['tax_balance'] : 0;



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_principal_arrears'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['principal_balance']) ? $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['principal_balance'] : 0;



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_interest_arrears'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['interest_balance']) ? $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['interest_balance'] : 0;



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_due_amount'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['balance_amount']) ? $memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['balance_amount'] : 0;



                            if ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_due_amount'] > 0) {



                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'] = isset($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['balance_amount']) ? round(($memberBillSummaryDetails[$lastMonthCount]['MemberBillSummary']['balance_amount'] * ($societyTariffParameterCheck['SocietyParameter']['interest_rate'] / 12) / 100)) : 0;



                            } else {



                                $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'] = 0.00;



                            }



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_amount'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['discount'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['amount_payable'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_due_amount'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_bill_amount'];







                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] = ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_principal_arrears'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['monthly_principal_amount']) - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_paid'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] = ($memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_interest_arrears'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_on_due_amount']) - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_paid'];



                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_total'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['op_tax_arrears'] - $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_paid'];







                            $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['balance_amount'] = $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['principal_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['interest_balance'] + $memberBillSummaryDetails[$billSummaryCounter]['MemberBillSummary']['tax_balance'];







                            $lastMonthCount++;



                        }



                        $billSummaryCounter++;



                    } else {



                        $this->Session->setFlash(__('Bill summary could not updated for bill no ' . $postData['bill_no'] . ' .'), 'error');



                    }



                }







                if (isset($memberBillSummaryDetails) && $this->MemberBillSummary->saveAll($memberBillSummaryDetails)) {



                    $this->Session->setFlash(__('Bill no ' . $postData['bill_no'] . ' has been updated successsfully. '), 'success');



                }



                return $this->redirect(array('action' => 'society_generated_bills'));



            }



        }



        $this->set(compact('billingMonthArray', 'lastBillGeneratedData'));



    }



    public function get_all_bills_payments($memberId=''){



        $societyLoginId = $this->societyloginID;



        $this->layout = false;



        if (!empty($societyLoginId)) {



            if ($this->MemberBillSummary->hasAny(array('MemberBillSummary.society_id' => $societyLoginId,'MemberBillSummary.member_id'=>$memberId))){



                $this->MemberBillGenerate->deleteAll(array('MemberBillGenerate.society_id' => $societyLoginId,'MemberBillSummary.member_id'=>$memberId));



                if ($this->MemberBillSummary->hasAny(array('MemberBillSummary.society_id' => $societyLoginId,'MemberBillSummary.member_id'=>$memberId))){



                    $this->MemberBillSummary->deleteAll(array('MemberBillSummary.society_id' => $societyLoginId,'MemberBillSummary.member_id'=>$memberId));



                }



                if ($this->Member->MemberPayment->hasAny(array('MemberPayment.society_id' => $societyLoginId,'MemberBillSummary.member_id'=>$memberId))){



                    $this->Member->MemberPayment->deleteAll(array('MemberPayment.society_id' => $societyLoginId,'MemberBillSummary.member_id'=>$memberId));



                }



            }



            $this->Session->setFlash(__('All bills & payments has been deleted.'),'success');



        }



        return $this->redirect(array('action' => 'society_generated_bills'));



    }







   public function getMemberPaymentTillDate($memberId,$date) {



       $this->loadModel('Member');



       $this->loadModel('MemberPayment');



        



       $societyLoginId = $this->Session->read('Auth.User.id');



       $paymentCondition = array('MemberPayment.society_id' => $societyLoginId, 'MemberPayment.member_id' => $memberId, 'MemberPayment.payment_date <=' => $date);



       $memberPayments = $this->MemberPayment->find('all', array('conditions' => $paymentCondition, 'order' => array('MemberPayment.bill_month' => 'asc')));



        



       $totalMemberPayment = 0;



        if(!empty($memberPayments)) {



            foreach($memberPayments as $indEntry) {



                $indMemberPayment = $indEntry['MemberPayment'];



                $totalMemberPayment += $indMemberPayment['amount_paid'];



            }



        }



        



        return $totalMemberPayment;



   }

  public function checkMemberTransferred($memberId){
      $this->loadModel('MemberIdentification');
      $condition = array('MemberIdentification.member_id' => $memberId);
      $totalTransferForYear = $this->MemberIdentification->find('all', array('conditions' => $condition));
      return count($totalTransferForYear) > 0 ? true : false;
  }

  public function getLatestTranferredId($memberId){
      $this->loadModel('Member');
      $condition = array('Member.id' => $memberId);
      $memberDetails = $this->Member->find('first', array('conditions' => $condition,  array('fields'=>array('Member.member_transfer'))));
      $latestNo = !empty($memberDetails) ? $memberDetails['Member']['member_transfer'] : 0 ;
      return $latestNo;
  }         
  
  
  public function updateAllSocietyMember(){
      $societyLoginId = $this->Session->read('Auth.User.id');
      $condition = array('Member.society_id' => $societyLoginId);
      $memberDetails = $this->Member->find('all', array('conditions' => $condition));

      if(!empty($memberDetails)){
        foreach($memberDetails as $memberIfo){
            $memberId = $memberIfo['Member']['id'];
            $updateSuccess = $this->updateMemberBillSummaryById(true,$memberId,$societyLoginId);
            echo "<br> $memberId";
            var_dump($updateSuccess);
        }    
      }
  }

  public function updateMemberRegualarOpenings(){

  }
           


  public function fetch_bills_and_payments(){

        $memberId = isset($this->request->data['memberId']) ? $this->request->data['memberId'] : 0;
        if(!empty($memberId)){

            $this->loadModel('YearWiseClosingBalance');
            $this->loadModel('MemberBillSummary');
            $this->loadModel('SocietyLedgerHeads');        
            $this->loadModel('Member');
            $this->loadModel('Society');
            $this->loadModel('SocietyParameter');

            $financialYearId = $this->Util->getFinancialYear();  


            $updatesupplementary = $this->request->data['updateSuplementary'];
            $billType = ($updatesupplementary == 'Y') ? 'sup' : 'reg';
            if ('reg' == $billType) {
                $op_principal = $this->request->data['op_principal'];
                $op_interest = $this->request->data['op_interest'];
                $op_tax = $this->request->data['op_tax'];
            }
            else{
                $op_principal = $this->request->data['sup_op_principal'];
                $op_interest = $this->request->data['sup_op_interest'];
                $op_tax = $this->request->data['sup_op_tax'];
            }

            $latestTransferNo = $this->getLatestTranferredId($memberId);
            
            $societyLoginId = $this->Session->read('Auth.User.id');
            $memberListWithTransferStatus = $this->SocietyBill->memberTransferList($societyLoginId);

            if(!isset($memberListWithTransferStatus[$memberId])){  

                $previousYearBal = $this->SocietyBill->getMemberClosingBalanceData($memberId,$latestTransferNo);

                if(!empty($previousYearBal)){
                    $billOpeningData = isset($previousYearBal[$billType]) ? $previousYearBal[$billType] : [];
                    if(!empty($billOpeningData)){
                        $lastBillGeneratedDate = $billOpeningData['bill_generated_date'];
                        $lastBillDueDate = $billOpeningData['bill_due_date'];
                        if($this->updateClosingBalanceData($billOpeningData,$op_principal,$op_tax,$op_interest)){
                            $this->updateMemberCurrentYearFirstBill($societyLoginId,$memberId,$billType,$latestTransferNo,$financialYearId,$op_principal,$op_tax,$op_interest,$lastBillGeneratedDate,$lastBillDueDate);
                        }
                    }
                }
                else{
                    if($this->updateMemberOpeningData($op_principal,$op_tax,$op_interest,$billType,$memberId)){
                        $this->updateMemberCurrentYearFirstBill($societyLoginId,$memberId,$billType,$latestTransferNo,$financialYearId,$op_principal,$op_tax,$op_interest);
                    }
                }
            }

            $updatingOpening = true;
            $this->updateMemberBillSummaryById(true,$memberId,$societyLoginId,$billType,$financialYearId,$updatingOpening);
            // $this->SocietyBill->closingBalanceUpdateAfterBillUpdate($memberId,$billType,$latestTransferNo);
        }    
                
               
     //  exit;
  }

  public function updateMemberOpeningData($op_principal,$op_tax,$op_interest,$billType,$memberId){
        $updateArr = [];
        if($billType == 'reg'){
            $updateArr = [
                'Member.op_principal' => $op_principal,
                'Member.op_interest' => $op_interest,
                'Member.op_tax' => $op_tax
            ];
        }
        else{
            $updateArr = [
                'Member.supplementary_principal' => $op_principal,
                'Member.supplementary_interest' => $op_interest,
                'Member.supplementary_tax' => $op_tax
            ];
        }

        return $this->Member->updateAll($updateArr, array('Member.id' => $memberId));
  }

  public function updateMemberCurrentYearFirstBill($societyLoginId,$memberId,$billType,$latestTransferNo,$financialYearId,$op_principal,$op_tax,$op_interest,$lbillGeneratedDate = '',$lbillDueDate = ''){

    $condition = array('MemberBillSummary.society_id' => $societyLoginId, 'MemberBillSummary.member_id' => $memberId,
    'MemberBillSummary.member_transfer' =>$latestTransferNo,'MemberBillSummary.bill_type' => $billType,
    'MemberBillSummary.financial_year_id' => $financialYearId);
    
    $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('SocietyParameter.society_id' => $societyLoginId));

    $memberBillSummary = $this->MemberBillSummary->find('first', array('conditions' => $condition, 'order' => array('MemberBillSummary.bill_generated_date' => 'asc')));

    if(!empty($memberBillSummary)){                     
        $billId = $memberBillSummary['MemberBillSummary']['id'];
            $interestTypeId = $societyTariffParameterCheck['SocietyParameter']['interest_type_id'];
            $principalAmt = $op_principal;
            $intererstAmt = $op_interest;
            $taxAmount = $op_tax;
            $billDueDate = $lastBillGeneratedDate = !empty($lbillGeneratedDate) ? $lbillGeneratedDate :  $memberBillSummary['Member']['op_bill_date'];
            $paymentDate = $billGeneratedDate = !empty($lbillDueDate) ? $lbillDueDate :  $memberBillSummary['Member']['op_bill_due_date'];
            $monthlyBill = $memberBillSummary['MemberBillSummary']['monthly_bill_amount'];
            $monthlyPrincipalAmt = $memberBillSummary['MemberBillSummary']['monthly_amount'];
            $opDueAmount = $op_tax + $op_interest + $op_principal;

            $summaryData = [
                 'op_tax_arrears' => $op_tax,
                 'op_interest_arrears' => $op_interest,
                 'op_principal_arrears' => $op_principal,
                 'op_principal_arrears_original' => $op_principal,
                 'principal_balance' => $op_principal + $monthlyPrincipalAmt,
                 'interest_balance'=> $op_interest,
                 'tax_balance' => $op_tax,
                 'op_due_amount' => $opDueAmount,
                 'balance_amount' => $opDueAmount + $monthlyBill,
                 'amount_payable' => $monthlyBill + $opDueAmount,                
                 'id' => $billId,
                 'udate' => date('Y-m-d H:i:s')
            ];   


            if($opDueAmount > 0  && isset($interestTypeId)  && $interestTypeId != 4){
                $interest_on_due_amount = $this->getInterestOnDueAmountOnFirstBill($principalAmt,$intererstAmt,$taxAmount,$societyTariffParameterCheck,$billDueDate,$paymentDate,$lastBillGeneratedDate,$billGeneratedDate);  
                $summaryData['interest_on_due_amount'] = $interest_on_due_amount;
            }
            

           return $this->MemberBillSummary->save($summaryData);
    }    
}

  public function updateClosingBalanceData($billOpeningData,$op_principal,$op_tax,$op_interest){
        $closingId = $billOpeningData['closing_id'];
        $closingUpdArray = [
            'id' => $closingId,
            'principal_balance' => $op_principal,
            'interest_balance' => $op_interest,
            'tax_balance' => $op_tax,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->YearWiseClosingBalance->save($closingUpdArray);
  }
 

   public function fetch_bills_and_payments_bkOM(){



    $memberId = $this->request->data['memberId'];// = 1608;



    $op_principal = $this->request->data['op_principal'];// = 1000;



    $op_interest = $this->request->data['op_interest'];// = 1000;



    $op_tax = $this->request->data['op_tax'];// = 1000;



    



    //$memberId = 1756;// = 1608;



    //$op_principal = 23657;// = 1000;



    //$op_interest = 4791;// = 1000;*/



    



    $this->layout = false;



    $this->autoRender = false;



    



    if (!empty($memberId)){



        //print_r($postData);die;



        App::import('model', 'Member');



        App::import('controller', 'SocietysMembers');



        $this->loadModel('MemberBillSummary');



        $this->loadModel('Member');



        $this->loadModel('MemberPayment');



        $this->loadModel('SocietyLedgerHeads');



        $this->loadModel('MemberTariff');



        $this->loadModel('SocietyTariffOrder');



        $this->loadModel('Building');



        $this->loadModel('SocietyTariffBuildings');



        $this->loadModel('JournalVoucher');



        



        $societyMemberCtrl = new SocietysMembersController;



        $societyMemberObj = new Member();







        $societyLoginId = $this->Session->read('Auth.User.id');



        $societyMemberCtrl->societyloginID = $societyLoginId;



        



        $db = $this->Session->read('Auth.dbConfig');



        if ($db == 'default'){



            $currentdb = 'Neweasylogics';



        } else {



            $currentdb = 'Neweasylogics';



        }



        $memberTable = $currentdb.'.members';



         



        $tempConn = mysql_connect('localhost','vedhtkjm_easylog','easyDatabase123');



        //$tempConn = mysql_connect('localhost','root','');



        $memberData = mysql_fetch_array(mysql_query("SELECT * FROM $memberTable WHERE id = ".$memberId,$tempConn));



        



        



        //$memberData = $this->Member->find('first', array('conditions' => array('Member.id' => $memberId)))['Member'];



        $memberDetails = $memberData;



        



        $memberData['op_principal'] = $op_principal;



        $memberData['op_interest'] = $op_interest;



        $memberData['op_tax'] = $op_tax;



        



        unset($memberData['cdate']);



        



        $memberData['udate'] = $this->Util->getDateTime();



        $this->Member->id = $memberId;



        $updateQry = "UPDATE $memberTable SET op_principal=$op_principal, op_interest=$op_interest,op_tax=$op_tax,udate='".$memberData['udate']."' WHERE id = ".$memberId;



        mysql_query($updateQry,$tempConn);



        //$this->Member->save($memberData);







        $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id')));



        



        $condition = array('MemberBillSummary.society_id' => $societyLoginId, 'MemberBillSummary.member_id' => $memberId);







        $societyMemberBillPaymentData = array();



        $paymentCondition = array('MemberPayment.society_id' => $societyLoginId, 'MemberPayment.member_id' => $memberId);



        $memberPayments = $this->MemberPayment->find('all', array('conditions' => $paymentCondition, 'order' => array('MemberPayment.bill_month' => 'asc')));



        



        if(!empty($memberPayments)) {



            foreach($memberPayments as $indEntry) {



                $indMemberPayment = $indEntry['MemberPayment'];



                $societyMemberBillPaymentData[$indMemberPayment['bill_month']] = $indMemberPayment['payment_date'];



            }



        }



        



        //echo '<pre>';



        //print_r($societyMemberBillPaymentData);



        



        // get all the bills and payments



        $memberBillSummary = $societyMemberObj->MemberBillSummary->find('all', array('conditions' => $condition, 'order' => array('MemberBillSummary.bill_generated_date' => 'asc')));







        $summaryCounter = 0;







        if (isset($memberBillSummary) && count($memberBillSummary) > 0){







            $summaryCounter = 0;







            $memberBillSr = 0;



            $totalDueAmount = 0;



            $totalBillAmountTillLastMonth = 0;



            



            foreach ($memberBillSummary as $billData){



                $dataToUpdate = array();



                if (isset($billData['MemberBillSummary'])){



                    $indBillSummary = $billData['MemberBillSummary'];



                    



                    $dataToUpdate['id'] = $indBillSummary['id'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['id'] = $indBillSummary['id'];



                            



                    $dataToUpdate['bill_no'] = $indBillSummary['bill_no'];



                    $dataToUpdate['month'] = $indBillSummary['month'];



                    



                    $postData['MemberBillGenerate']['bill_generated_date'] = $indBillSummary['bill_generated_date'];



                    $postData['MemberBillGenerate']['bill_due_date'] = $indBillSummary['bill_due_date'];



                    



                    // JV adjustments



                    $journalVoucherData = $this->JournalVoucher->find('all', array('conditions' => array('JournalVoucher.society_id' => $societyLoginId,'JournalVoucher.voucher_date > '=> $indBillSummary['bill_generated_date'],'JournalVoucher.voucher_date <= '=> $indBillSummary['bill_due_date'],"OR" => array(



                    "JournalVoucher.jv_debit_member_head_id" => $memberId,



                    "JournalVoucher.jv_credit_member_head_id" => $memberId)



                    )));







                    $jvAdjustment = 0;



                    foreach ($journalVoucherData as $counter => $jVData){



                    if (isset($jVData['JournalVoucher']['jv_credit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_credit_member_head_id'])) {



                                $jvAdjustment = $jvAdjustment - $jVData['JournalVoucher']['jv_amount_credited'];



                        }



                    elseif (isset($jVData['JournalVoucher']['jv_debit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_debit_member_head_id'])) {



                                $jvAdjustment = $jvAdjustment + $jVData['JournalVoucher']['jv_amount_debited'];



                        }



                }



            



                    $dataToUpdate['jvAdjusted'] = $jvAdjustment;



                    



                    //Check if last month bill exists to get principal arrears etc



                    if($memberBillSr == 0) {



                        $op_principal_arrears_original = $op_principal;



                        



                        $op_principal_arrears = $op_principal_arrears_original+$dataToUpdate['jvAdjusted'];



                        $op_interest_arrears = $op_interest;



                        $op_tax_arrears = $op_tax;



                        



                        $op_due_amount = $op_principal_arrears+$op_interest_arrears+$op_tax_arrears;



                        $totalDueAmount += $op_due_amount;



                        //Need to calculate interest



                        $lastMonthBillDetails['principal_balance'] = $op_principal;



                        $lastMonthBillDetails['balance_amount'] = $op_due_amount;



                        $lastMonthBillDetails['bill_due_date'] = isset($memberDetails['op_bill_due_date']) ? $memberDetails['op_bill_due_date'] : '';



                        $lastMonthBillDetails['bill_generated_date'] = isset($memberDetails['op_bill_date']) ? $memberDetails['op_bill_date'] : '';



                        $lastMonthBillDetails['interest_free_amount'] = 0;



                        $lastMonthBillDetails['bill_month'] = $indBillSummary['month'];



                        



                        $interest_on_due_amount = 0;                        



                        if($op_due_amount > 0){



                            $interest_on_due_amount = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                        }                        



                        



                        $monthly_bill_amount = $indBillSummary['monthly_amount'] + $interest_on_due_amount - $indBillSummary['discount']; 



                        $totalBillAmountTillLastMonth += $op_due_amount+$monthly_bill_amount;



                        $amount_payable = $monthly_bill_amount+$op_due_amount;                        



                    } else {



                        $op_principal_arrears_original = $principalArrears;



                        $op_principal_arrears = $op_principal_arrears_original+$dataToUpdate['jvAdjusted'];



                        $op_interest_arrears = $interestArrears;



                        $op_tax_arrears = $taxArrears;



                        



                        $op_due_amount = $op_principal_arrears+$op_interest_arrears+$op_tax_arrears;



                                                



                        //Need to calculate interest



                        $lastMonthBillDetails['principal_balance'] = $op_due_amount;



                        $lastMonthBillDetails['balance_amount'] = $op_due_amount;



                        



                        $interest_on_due_amount = 0;



                        // Check if payment made after bill due date



                        $indBillMonth = $indBillSummary['month'];



                        $indBillDueDate = $indBillSummary['bill_due_date'];



                        



                        $totalBillAmountTillLastMonth += $indBillSummary['monthly_bill_amount'];



                        



                        if(array_key_exists($lastMonthBillDetails['bill_month'],$societyMemberBillPaymentData) && (strtotime($lastMonthBillDetails['bill_due_date']) < strtotime($societyMemberBillPaymentData[$lastMonthBillDetails['bill_month']]))) {



                            //echo $indBillSummary['month'].'->'.$dueAmount.'<br>';



                            $memberPaymentTillLastBill = $this->getMemberPaymentTillDate($memberId,$lastMonthBillDetails['bill_due_date']);



                            $memberBillAmountTillLastMonth = $totalBillAmountTillLastMonth-$indBillSummary['monthly_bill_amount'];



                            $memberDueTillLastBill = $memberBillAmountTillLastMonth-$memberPaymentTillLastBill;



                            //echo $lastMonthBillDetails['bill_month'].'->'.$memberBillAmountTillLastMonth.'->'.$memberPaymentTillLastBill.'->'.$memberDueTillLastBill.'<br>';



                            if($memberDueTillLastBill > 0 ){



                                $tempOpDue = $lastMonthBillDetails['monthly_bill_amount'];







                                $tempLastMonthBillDetails = $lastMonthBillDetails;



                                $tempLastMonthBillDetails['principal_balance'] = $lastMonthBillDetails['monthly_principal_amount'];



                                $tempLastMonthBillDetails['balance_amount'] = $lastMonthBillDetails['monthly_bill_amount'];







                                if($tempOpDue > 0){



                                    $interest_on_due_amount = $this->getInterestOnDueAmount($societyTariffParameterCheck, $tempLastMonthBillDetails, $postData);



                                }



                            }



                            //echo $indBillMonth.'->'.$interest_on_due_amount;



                        } else {



                            if($op_due_amount > 0){



                                $interest_on_due_amount = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                            }



                        }



                        



                        $monthly_bill_amount = $indBillSummary['monthly_amount'] + $interest_on_due_amount - $indBillSummary['discount']; 



                        $amount_payable = $monthly_bill_amount+$op_due_amount;



                    }



                    



                    $dataToUpdate['op_principal_arrears_original'] = $op_principal_arrears_original;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['op_principal_arrears_original'] = $dataToUpdate['op_principal_arrears_original'];



                    



                    $dataToUpdate['op_principal_arrears'] = $op_principal_arrears;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['op_principal_arrears'] = $dataToUpdate['op_principal_arrears'];



                    



                    $dataToUpdate['op_interest_arrears'] = $op_interest_arrears;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['op_interest_arrears'] = $dataToUpdate['op_interest_arrears'];



                    



                    $dataToUpdate['op_tax_arrears'] = $op_tax_arrears;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['op_tax_arrears'] = $dataToUpdate['op_tax_arrears'];



                    



                    $dataToUpdate['op_due_amount'] = $op_due_amount;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['op_due_amount'] = $dataToUpdate['op_due_amount'];



                    



                    $dataToUpdate['interest_on_due_amount'] = $interest_on_due_amount;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['interest_on_due_amount'] = $dataToUpdate['interest_on_due_amount'];



                    



                    $dataToUpdate['monthly_bill_amount'] = $monthly_bill_amount;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['monthly_bill_amount'] = $dataToUpdate['monthly_bill_amount'];



                    



                    $dataToUpdate['amount_payable'] = $amount_payable;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['amount_payable'] = $dataToUpdate['amount_payable'];



                    



                    $dataToUpdate['monthly_principal_amount'] = $indBillSummary['monthly_principal_amount'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['monthly_principal_amount'] = $dataToUpdate['monthly_principal_amount'];



                    



                    $dataToUpdate['principal_balance'] = $dataToUpdate['monthly_principal_amount']+$dataToUpdate['op_principal_arrears']-$dataToUpdate['principal_adjusted'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['principal_balance'] = $dataToUpdate['principal_balance'];



                    



                    $dataToUpdate['interest_balance'] = $dataToUpdate['interest_on_due_amount']+$dataToUpdate['op_interest_arrears']-$dataToUpdate['interest_adjusted'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['interest_balance'] = $dataToUpdate['interest_balance'];



                    



                    $dataToUpdate['tax_balance'] = $dataToUpdate['tax_total']+$dataToUpdate['op_tax_arrears']-$dataToUpdate['tax_adjusted'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['tax_balance'] = $dataToUpdate['tax_balance'];



                    



                    $paymentMadeBillNo = $indBillSummary['bill_no'];



                    //Check payments made for this month



                    $conditionPayment = array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'), 'MemberPayment.member_id' => $memberId, 'MemberPayment.bill_generated_id' => $paymentMadeBillNo);







                    $paymentForBill = 0;



                        if ($societyMemberObj->MemberPayment->hasAny($conditionPayment)){







                            $memberPaymentDetails = $societyMemberObj->MemberPayment->find('all', array('conditions' => $conditionPayment));







                            if (isset($memberPaymentDetails) && count($memberPaymentDetails) > 0) {



                                foreach ($memberPaymentDetails as $paymentData){



                                    $billPaymentData = $paymentData['MemberPayment'];



                                    $paymentForBill += $billPaymentData['amount_paid'];



                                }



                            }







                        }



                    



                   //echo '<pre>';



                   //print_r($dataToUpdate);



                   // Adjust the paid amount against tax, interest and principal     



                   $dataToUpdate['amount_paid'] = $paymentForBill;



                   if($dataToUpdate['amount_paid'] > 0) {



                       $remainingAmount = $dataToUpdate['amount_paid'];



                       if($dataToUpdate['tax_balance'] > 0 && $remainingAmount > 0) {



                          $taxAmount =  $dataToUpdate['tax_balance'];



                          $dataToUpdate['tax_paid'] = $taxAmount; 



                          $memberBillSummaryDetails[0]['MemberBillSummary']['tax_paid'] = $dataToUpdate['tax_paid'];



                          if($remainingAmount > $taxAmount) {



                            $dataToUpdate['tax_balance'] = $dataToUpdate['tax_paid']-$taxAmount; 



                            $memberBillSummaryDetails[0]['MemberBillSummary']['tax_balance'] = $dataToUpdate['tax_balance'];



                            



                            $remainingAmount = $remainingAmount-$taxAmount;



                          } else {



                            $dataToUpdate['tax_balance'] = $dataToUpdate['tax_paid']-$remainingAmount;



                            $memberBillSummaryDetails[0]['MemberBillSummary']['tax_balance'] = $dataToUpdate['tax_balance'];



                            



                            $remainingAmount = 0;



                          }



                       }



                       



                       if($dataToUpdate['interest_balance'] > 0 && $remainingAmount > 0) {



                          $interestAmount =  $dataToUpdate['interest_balance'];



                          $dataToUpdate['interest_paid'] = $interestAmount;



                          $memberBillSummaryDetails[0]['MemberBillSummary']['interest_paid'] = $dataToUpdate['interest_paid'];



                          



                          if($remainingAmount > $interestAmount) {



                            $dataToUpdate['interest_balance'] = $dataToUpdate['interest_balance']-$interestAmount; 



                            $memberBillSummaryDetails[0]['MemberBillSummary']['interest_balance'] = $dataToUpdate['interest_balance'];



                            



                            $remainingAmount = $remainingAmount-$interestAmount;



                          } else {



                            $dataToUpdate['interest_balance'] = $dataToUpdate['interest_paid']-$remainingAmount; 



                            $memberBillSummaryDetails[0]['MemberBillSummary']['interest_balance'] = $dataToUpdate['interest_balance'];



                            $remainingAmount = 0;



                          }



                       }



                       



                       if($remainingAmount > 0) {



                          $dataToUpdate['principal_paid'] = $remainingAmount; 



                          $memberBillSummaryDetails[0]['MemberBillSummary']['principal_paid'] = $dataToUpdate['principal_paid'];



                          



                          $dataToUpdate['principal_balance'] = $dataToUpdate['principal_balance']-$remainingAmount; 



                          $memberBillSummaryDetails[0]['MemberBillSummary']['principal_balance'] = $dataToUpdate['principal_balance'];



                       }



                       



                   }



                    //Check JV adjusted for this month



                    $dataToUpdate['balance_amount'] = $dataToUpdate['principal_balance']+$dataToUpdate['interest_balance']+$dataToUpdate['tax_balance'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['balance_amount'] = $dataToUpdate['balance_amount'];



                    $memberBillSr++;



                    



                    $principalArrears = $dataToUpdate['principal_balance'];



                    $interestArrears = $dataToUpdate['interest_balance'];



                    $taxArrears = $dataToUpdate['tax_balance'];



                                       



                    $lastMonthBillDetails['bill_due_date'] = $indBillSummary['bill_due_date'];



                    $lastMonthBillDetails['bill_generated_date'] = $indBillSummary['bill_generated_date'];



                    $lastMonthBillDetails['interest_free_amount'] = $indBillSummary['interest_free_amount'];



                    $lastMonthBillDetails['bill_month'] = $indBillSummary['month'];



                    $lastMonthBillDetails['monthly_bill_amount'] = $indBillSummary['monthly_bill_amount'];



                    $lastMonthBillDetails['monthly_principal_amount'] = $indBillSummary['monthly_principal_amount'];



                    $lastMonthBillDetails['last_balance_amount'] = $indBillSummary['balance_amount'];



                    // Update member Opening balance in members table first and then bills



                    /*echo '<pre>';



                    print_r($memberData);



                    print_r($memberBillSummaryDetails);



                    echo '<hr>';exit;*/



                    



                    



                    $this->MemberBillSummary->saveAll($memberBillSummaryDetails);



                }







                            //$summaryCounter++;







                        }







                    }







       



        



        //echo "<pre>";



        //print_r($societyMemberBillPaymentData);



        //print_r($aData);



        die;



        }



    }



    



    public function update_bill_remarks() {



        $billId = $this->request->data['billId'];



        $remarks = $this->request->data['remarks'];



        $this->layout = false;



        $this->autoRender = false;



        



        if (!empty($billId)){



            $this->loadModel('MemberBillSummary');



            $update['MemberBillSummary']['id'] = $billId;



            $update['MemberBillSummary']['remarks'] = $remarks;



            



            $this->MemberBillSummary->save($update['MemberBillSummary']);







            $response['error'] = 0;



            $response['error_message'] = 'Remark has been saved successfully.';







            



        } else {



            $response['error'] = 1;



            $response['error_message'] = 'Error in saving.';



        }



        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));



    }



    



   /*
    * Bulk version of the single bill's Update: the Member Bill Difference report
    * posts the checked rows here, one per member+financial-year+bill-type, and
    * each is run through the same recalculation the modal's Update button uses
    * (updateMemberBillSummaryById in its recalc-only mode - it does not touch the
    * charges, only recomputes the Dr/Cr balances that were out of step).
    *
    * Scoped to the logged-in society: the recalc's own conditions filter on
    * society_id, so a member from another society cannot be reached from here.
    */
   public function bulk_recalculate_member_bills() {
       $this->autoRender = false;
       $this->layout = false;
       // The report sends these in small batches, but each member's recalc is
       // heavy; lift the default 30s cap so a slow batch is not cut off midway.
       @set_time_limit(120);
       $response = array('error' => 1, 'message' => 'No rows to update');

       $rows = isset($this->request->data['rows']) ? $this->request->data['rows'] : '';
       if (empty($rows)) {
           return new CakeResponse(array('body' => json_encode($response), 'status' => 200));
       }

       $societyLoginId = $this->societyloginID;
       $done = 0; $failed = 0; $seen = array();
       foreach (explode(',', $rows) as $tuple) {
           $tuple = trim($tuple);
           if ($tuple === '' || isset($seen[$tuple])) { continue; }
           $seen[$tuple] = true;

           $parts = explode(':', $tuple);
           if (count($parts) < 3) { continue; }
           $memberId = (int)$parts[0];
           $financialYearId = (int)$parts[1];
           // Only the two real bill types; anything else is ignored rather than
           // passed through to a query.
           $billType = ($parts[2] === 'sup') ? 'sup' : 'reg';
           if ($memberId <= 0 || $financialYearId <= 0) { continue; }

           // The difference is a stale monthly_amount: a charge was edited on the
           // bill after it was generated, so the summary still holds the old figure
           // and Dr != Cr. The single bill's Update button clears this by re-running
           // updateMemberTarrifDetails (which rewrites monthly_amount from the charge
           // rows); the recalc-only path below skips that step, so reproduce just the
           // sync first. The recalc then rebuilds monthly_bill_amount and the running
           // balances from the corrected figures, exactly as the single Update does.
           $this->syncSummaryAmountFromCharges($memberId, $societyLoginId, $billType, $financialYearId);

           $ok = $this->updateMemberBillSummaryById(true, $memberId, $societyLoginId, $billType, $financialYearId);
           if ($ok) { $done++; } else { $failed++; }
       }

       $response['error'] = ($done > 0) ? 0 : 1;
       $response['message'] = $done . ' recalculated' . ($failed ? (', ' . $failed . ' failed') : '');
       return new CakeResponse(array('body' => json_encode($response), 'status' => 200));
   }

   /**
    * Rewrite each of the member's bill summaries so monthly_amount and tax_total
    * equal the sum of that month's charge rows (member_bill_generates) - the exact
    * figures the difference report totals into Cr. This is the piece of the single
    * bill's Update (updateMemberTarrifDetails) that the recalc-only path omits;
    * without it a charge edited after bill generation leaves Dr != Cr for good.
    * Only monthly_amount and tax_total are rewritten - interest, discount and the
    * adjustments are left untouched - and the caller's recalc then rebuilds
    * monthly_bill_amount and the running balances from these values, the same way
    * clicking Update on the single bill does.
    */
   private function syncSummaryAmountFromCharges($memberId, $societyLoginId, $billType, $financialYearId) {
       $this->loadModel('MemberBillSummary');
       $this->loadModel('MemberBillGenerate');

       // Scope to the same transfer number the recalc uses, so the old owner's rows
       // of a transferred flat are not rewritten from the current owner's charges.
       $latestTransferNo = $this->getLatestTranferredId($memberId);

       $summaries = $this->MemberBillSummary->find('all', array(
           'conditions' => array(
               'MemberBillSummary.member_id' => $memberId,
               'MemberBillSummary.society_id' => $societyLoginId,
               'MemberBillSummary.bill_type' => $billType,
               'MemberBillSummary.financial_year_id' => $financialYearId,
               'MemberBillSummary.member_transfer' => $latestTransferNo
           ),
           'fields' => array('MemberBillSummary.id', 'MemberBillSummary.month', 'MemberBillSummary.monthly_amount', 'MemberBillSummary.tax_total'),
           'recursive' => -1
       ));
       if (empty($summaries)) { return; }

       // Charge totals per month, grouped exactly as the report groups Cr (member +
       // month + financial year + bill type). Months with no charge rows do not
       // appear here, so a bill without charges is left as-is rather than zeroed.
       $chargeTotals = $this->MemberBillGenerate->find('all', array(
           'conditions' => array(
               'MemberBillGenerate.member_id' => $memberId,
               'MemberBillGenerate.society_id' => $societyLoginId,
               'MemberBillGenerate.bill_type' => $billType,
               'MemberBillGenerate.financial_year_id' => $financialYearId
           ),
           'fields' => array(
               'MemberBillGenerate.month',
               'COALESCE(SUM(MemberBillGenerate.amount),0) AS contri',
               'COALESCE(SUM(MemberBillGenerate.tax_total),0) AS gst'
           ),
           'group' => array('MemberBillGenerate.month'),
           'recursive' => -1
       ));
       $byMonth = array();
       foreach ($chargeTotals as $ct) {
           $byMonth[(string)$ct['MemberBillGenerate']['month']] = array(
               'contri' => (float)$ct[0]['contri'],
               'gst' => (float)$ct[0]['gst']
           );
       }

       foreach ($summaries as $s) {
           $month = (string)$s['MemberBillSummary']['month'];
           if (!isset($byMonth[$month])) { continue; }

           $contri = $byMonth[$month]['contri'];
           $gst    = $byMonth[$month]['gst'];
           $curAmt = (float)$s['MemberBillSummary']['monthly_amount'];
           $curTax = (float)$s['MemberBillSummary']['tax_total'];

           // Already in sync within half a paisa (the report's own threshold) -
           // nothing to write.
           if (abs($contri - $curAmt) < 0.005 && abs($gst - $curTax) < 0.005) { continue; }

           $this->MemberBillSummary->updateAll(
               array(
                   'MemberBillSummary.monthly_amount' => "'" . number_format($contri, 2, '.', '') . "'",
                   'MemberBillSummary.tax_total' => "'" . number_format($gst, 2, '.', '') . "'"
               ),
               array('MemberBillSummary.id' => (int)$s['MemberBillSummary']['id'])
           );
       }
   }

   public function update_member_bills_future(){



    $memberId = $this->request->data['memberId'];// = 1608;



    $memberBillSummaryId = $this->request->data['memberBillSummaryId'];// = 1000;



    



    



    $this->layout = false;



    $this->autoRender = false;



    



    if (!empty($memberId)){



        //print_r($postData);die;



        App::import('model', 'Member');



        App::import('controller', 'SocietysMembers');



        $this->loadModel('MemberBillSummary');



        $this->loadModel('Member');



        $this->loadModel('MemberPayment');



        $this->loadModel('SocietyLedgerHeads');



        $this->loadModel('MemberTariff');



        $this->loadModel('SocietyTariffOrder');



        $this->loadModel('Building');



        $this->loadModel('SocietyTariffBuildings');



        $this->loadModel('JournalVoucher');



        



        $societyMemberCtrl = new SocietysMembersController;



        $societyMemberObj = new Member();







        $societyLoginId = $this->Session->read('Auth.User.id');



        $societyMemberCtrl->societyloginID = $societyLoginId;



        



        $memberData = $this->Member->find('first', array('conditions' => array('Member.id' => $memberId)))['Member'];



        



        $societyTariffParameterCheck = $this->Society->SocietyParameter->find('first', array('SocietyParameter.society_id' => $this->Session->read('Auth.User.id')));



        



        $condition = array('MemberBillSummary.society_id' => $societyLoginId, 'MemberBillSummary.member_id' => $memberId,'MemberBillSummary.id >=' => $memberBillSummaryId);







        $societyMemberBillPaymentData = array();







        // get all the bills and payments



        $currentBillSummary = $societyMemberObj->MemberBillSummary->find('all', array('conditions' => array('MemberBillSummary.society_id' => $societyLoginId, 'MemberBillSummary.member_id' => $memberId,'MemberBillSummary.id' => $memberBillSummaryId), 'order' => array('MemberBillSummary.bill_generated_date' => 'asc')));



        $currentBillGeneratedDate = $currentBillSummary[0]['MemberBillSummary']['bill_generated_date'];



        



        if(empty($currentBillGeneratedDate)) {



            exit;



        }



        



        $lastBillSummary = $societyMemberObj->MemberBillSummary->find('all', array('conditions' => array('MemberBillSummary.society_id' => $societyLoginId, 'MemberBillSummary.member_id' => $memberId,'MemberBillSummary.bill_generated_date <' => $currentBillGeneratedDate),'limit' => 1,'order' => array('MemberBillSummary.bill_generated_date' => 'desc')));



        $lastBillSummary = $lastBillSummary[0]['MemberBillSummary'];



        



        if(empty($lastBillSummary)) {



                $lastBillSummary['principal_balance'] = $memberData['op_principal'];



                $lastBillSummary['interest_balance'] = $memberData['op_interest'];



                $lastBillSummary['tax_balance'] = $memberData['op_tax'];



                $lastBillSummary['bill_due_date'] = $memberData['op_bill_due_date'];



                $lastBillSummary['bill_generated_date'] = $memberData['op_bill_date'];



        }



        



        $memberBillSummary = $societyMemberObj->MemberBillSummary->find('all', array('conditions' => array('MemberBillSummary.society_id' => $societyLoginId, 'MemberBillSummary.member_id' => $memberId,'MemberBillSummary.bill_generated_date >=' => $currentBillGeneratedDate), 'order' => array('MemberBillSummary.bill_generated_date' => 'asc')));



        



        if (isset($memberBillSummary) && count($memberBillSummary) > 0){







            $memberBillSr = 0;



            



            foreach ($memberBillSummary as $billData){



                $dataToUpdate = array();



                if (isset($billData['MemberBillSummary'])){



                    $indBillSummary = $billData['MemberBillSummary'];



                    



                    $dataToUpdate['id'] = $indBillSummary['id'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['id'] = $indBillSummary['id'];



                            



                    $dataToUpdate['bill_no'] = $indBillSummary['bill_no'];



                    $dataToUpdate['month'] = $indBillSummary['month'];



                    



                    $postData['MemberBillGenerate']['bill_generated_date'] = $indBillSummary['bill_generated_date'];



                    $postData['MemberBillGenerate']['bill_due_date'] = $indBillSummary['bill_due_date'];



                    



                    // JV adjustments



                    $journalVoucherData = $this->JournalVoucher->find('all', array('conditions' => array('JournalVoucher.society_id' => $societyLoginId,'JournalVoucher.voucher_date > '=> $indBillSummary['bill_generated_date'],'JournalVoucher.voucher_date <= '=> $indBillSummary['bill_due_date'],"OR" => array(



                    "JournalVoucher.jv_debit_member_head_id" => $memberId,



                    "JournalVoucher.jv_credit_member_head_id" => $memberId)



                    )));







                    $jvAdjustment = 0;



                    foreach ($journalVoucherData as $counter => $jVData){



                    if (isset($jVData['JournalVoucher']['jv_credit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_credit_member_head_id'])) {



                                $jvAdjustment = $jvAdjustment - $jVData['JournalVoucher']['jv_amount_credited'];



                        }



                    elseif (isset($jVData['JournalVoucher']['jv_debit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_debit_member_head_id'])) {



                                $jvAdjustment = $jvAdjustment + $jVData['JournalVoucher']['jv_amount_debited'];



                        }



                }



            



                    $dataToUpdate['jvAdjusted'] = $jvAdjustment;



                    



                    //Check if last month bill exists to get principal arrears etc



                    if($memberBillSr == 0) {



                        // Get Last Month bill Details



                        $op_principal_arrears_original = $lastBillSummary['principal_balance'];



                        



                        $op_principal_arrears = $op_principal_arrears_original+$dataToUpdate['jvAdjusted'];



                        $op_interest_arrears = $lastBillSummary['interest_balance'];



                        $op_tax_arrears = $lastBillSummary['tax_balance'];



                        



                        $op_due_amount = $op_principal_arrears+$op_interest_arrears+$op_tax_arrears;



                        



                        //Need to calculate interest



                        $lastMonthBillDetails['principal_balance'] = $op_due_amount;



                        $lastMonthBillDetails['balance_amount'] = $op_due_amount;



                        $lastMonthBillDetails['bill_due_date'] = isset($lastBillSummary['bill_due_date']) ? $lastBillSummary['bill_due_date'] : '';



                        $lastMonthBillDetails['bill_generated_date'] = isset($lastBillSummary['bill_generated_date']) ? $memberDetails['bill_generated_date'] : '';



                        $lastMonthBillDetails['interest_free_amount'] = 0;



                        



                        $interest_on_due_amount = 0;                        



                        if($op_due_amount > 0){



                            $interest_on_due_amount = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                        }                        



                        



                        $monthly_bill_amount = $indBillSummary['monthly_amount'] + $interest_on_due_amount - $indBillSummary['discount']; 



                        $amount_payable = $monthly_bill_amount+$op_due_amount;                        



                    } else {



                        $op_principal_arrears_original = $principalArrears;



                        $op_principal_arrears = $op_principal_arrears_original+$dataToUpdate['jvAdjusted'];



                        $op_interest_arrears = $interestArrears;



                        $op_tax_arrears = $taxArrears;



                        



                        $op_due_amount = $op_principal_arrears+$op_interest_arrears+$op_tax_arrears;



                        



                        //Need to calculate interest



                        $lastMonthBillDetails['principal_balance'] = $op_due_amount;



                        $lastMonthBillDetails['balance_amount'] = $op_due_amount;



                        



                        $interest_on_due_amount = 0;                        



                        if($op_due_amount > 0){



                            $interest_on_due_amount = $this->getInterestOnDueAmount($societyTariffParameterCheck, $lastMonthBillDetails, $postData);



                        }



                        



                        $monthly_bill_amount = $indBillSummary['monthly_amount'] + $interest_on_due_amount - $indBillSummary['discount']; 



                        $amount_payable = $monthly_bill_amount+$op_due_amount;



                    }



                    



                    $dataToUpdate['op_principal_arrears_original'] = $op_principal_arrears_original;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['op_principal_arrears_original'] = $dataToUpdate['op_principal_arrears_original'];



                    



                    $dataToUpdate['op_principal_arrears'] = $op_principal_arrears;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['op_principal_arrears'] = $dataToUpdate['op_principal_arrears'];



                    



                    $dataToUpdate['op_interest_arrears'] = $op_interest_arrears;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['op_interest_arrears'] = $dataToUpdate['op_interest_arrears'];



                    



                    $dataToUpdate['op_tax_arrears'] = $op_tax_arrears;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['op_tax_arrears'] = $dataToUpdate['op_tax_arrears'];



                    



                    $dataToUpdate['op_due_amount'] = $op_due_amount;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['op_due_amount'] = $dataToUpdate['op_due_amount'];



                    



                    $dataToUpdate['interest_on_due_amount'] = $interest_on_due_amount;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['interest_on_due_amount'] = $dataToUpdate['interest_on_due_amount'];



                    



                    $dataToUpdate['monthly_bill_amount'] = $monthly_bill_amount;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['monthly_bill_amount'] = $dataToUpdate['monthly_bill_amount'];



                    



                    $dataToUpdate['amount_payable'] = $amount_payable;



                    $memberBillSummaryDetails[0]['MemberBillSummary']['amount_payable'] = $dataToUpdate['amount_payable'];



                    



                    $dataToUpdate['monthly_principal_amount'] = $indBillSummary['monthly_principal_amount'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['monthly_principal_amount'] = $dataToUpdate['monthly_principal_amount'];



                    



                    $dataToUpdate['principal_balance'] = $dataToUpdate['monthly_principal_amount']+$dataToUpdate['op_principal_arrears']-$dataToUpdate['principal_adjusted'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['principal_balance'] = $dataToUpdate['principal_balance'];



                    



                    $dataToUpdate['interest_balance'] = $dataToUpdate['interest_on_due_amount']+$dataToUpdate['op_interest_arrears']-$dataToUpdate['interest_adjusted'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['interest_balance'] = $dataToUpdate['interest_balance'];



                    



                    $dataToUpdate['tax_balance'] = $dataToUpdate['tax_total']+$dataToUpdate['op_tax_arrears']-$dataToUpdate['tax_adjusted'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['tax_balance'] = $dataToUpdate['tax_balance'];



                    



                    $paymentMadeBillNo = $indBillSummary['bill_no'];



                    //Check payments made for this month



                    $conditionPayment = array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'), 'MemberPayment.member_id' => $memberId, 'MemberPayment.bill_generated_id' => $paymentMadeBillNo);







                    $paymentForBill = 0;



                        if ($societyMemberObj->MemberPayment->hasAny($conditionPayment)){







                            $memberPaymentDetails = $societyMemberObj->MemberPayment->find('all', array('conditions' => $conditionPayment));







                            if (isset($memberPaymentDetails) && count($memberPaymentDetails) > 0) {



                                foreach ($memberPaymentDetails as $paymentData){



                                    $billPaymentData = $paymentData['MemberPayment'];



                                    $paymentForBill += $billPaymentData['amount_paid'];



                                }



                            }







                        }



                    



                   /*echo '<pre>';



                   print_r($dataToUpdate);*/



                   // Adjust the paid amount against tax, interest and principal     



                   $dataToUpdate['amount_paid'] = $paymentForBill;



                   if($dataToUpdate['amount_paid'] > 0) {



                       $remainingAmount = $dataToUpdate['amount_paid'];



                       if($dataToUpdate['tax_balance'] > 0 && $remainingAmount > 0) {



                          $taxAmount =  $dataToUpdate['tax_balance'];



                          $dataToUpdate['tax_paid'] = $taxAmount; 



                          $memberBillSummaryDetails[0]['MemberBillSummary']['tax_paid'] = $dataToUpdate['tax_paid'];



                          if($remainingAmount > $taxAmount) {



                            $dataToUpdate['tax_balance'] = $dataToUpdate['tax_paid']-$taxAmount; 



                            $memberBillSummaryDetails[0]['MemberBillSummary']['tax_balance'] = $dataToUpdate['tax_balance'];



                            



                            $remainingAmount = $remainingAmount-$taxAmount;



                          } else {



                            $dataToUpdate['tax_balance'] = $dataToUpdate['tax_paid']-$remainingAmount;



                            $memberBillSummaryDetails[0]['MemberBillSummary']['tax_balance'] = $dataToUpdate['tax_balance'];



                            



                            $remainingAmount = 0;



                          }



                       }



                       



                       if($dataToUpdate['interest_balance'] > 0 && $remainingAmount > 0) {



                          $interestAmount =  $dataToUpdate['interest_balance'];



                          $dataToUpdate['interest_paid'] = $interestAmount;



                          $memberBillSummaryDetails[0]['MemberBillSummary']['interest_paid'] = $dataToUpdate['interest_paid'];



                          



                          if($remainingAmount > $interestAmount) {



                            $dataToUpdate['interest_balance'] = $dataToUpdate['interest_balance']-$interestAmount; 



                            $memberBillSummaryDetails[0]['MemberBillSummary']['interest_balance'] = $dataToUpdate['interest_balance'];



                            



                            $remainingAmount = $remainingAmount-$interestAmount;



                          } else {



                            $dataToUpdate['interest_balance'] = $dataToUpdate['interest_paid']-$remainingAmount; 



                            $memberBillSummaryDetails[0]['MemberBillSummary']['interest_balance'] = $dataToUpdate['interest_balance'];



                            $remainingAmount = 0;



                          }



                       }



                       



                       if($remainingAmount > 0) {



                          $dataToUpdate['principal_paid'] = $remainingAmount; 



                          $memberBillSummaryDetails[0]['MemberBillSummary']['principal_paid'] = $dataToUpdate['principal_paid'];



                          



                          $dataToUpdate['principal_balance'] = $dataToUpdate['principal_balance']-$remainingAmount; 



                          $memberBillSummaryDetails[0]['MemberBillSummary']['principal_balance'] = $dataToUpdate['principal_balance'];



                       }



                       



                   }



                    //Check JV adjusted for this month



                    $dataToUpdate['balance_amount'] = $dataToUpdate['principal_balance']+$dataToUpdate['interest_balance']+$dataToUpdate['tax_balance'];



                    $memberBillSummaryDetails[0]['MemberBillSummary']['balance_amount'] = $dataToUpdate['balance_amount'];



                    $memberBillSr++;



                    



                    $principalArrears = $dataToUpdate['principal_balance'];



                    $interestArrears = $dataToUpdate['interest_balance'];



                    $taxArrears = $dataToUpdate['tax_balance'];



                                       



                    $lastMonthBillDetails['bill_due_date'] = $indBillSummary['bill_due_date'];



                    $lastMonthBillDetails['bill_generated_date'] = $indBillSummary['bill_generated_date'];



                    $lastMonthBillDetails['interest_free_amount'] = $indBillSummary['interest_free_amount'];



                        



                    // Update member Opening balance in members table first and then bills



                    /*echo '<pre>';



                    print_r($memberData);



                    print_r($memberBillSummaryDetails);



                    echo '<hr>';*/



                    



                    



                    $this->MemberBillSummary->saveAll($memberBillSummaryDetails);



                }







                            //$summaryCounter++;







                        }







                    }







       



        



        //echo "<pre>";



        //print_r($societyMemberBillPaymentData);



        //print_r($aData);



        die;



        }



    }


	public function email_member_bills() {    

      
		App::uses('CakeEmail', 'Network/Email');
		$this->layout = false;
		$societyId = $this->societyloginID;
		$financialYearId = $this->Util->getFinancialYear();  
        $ledgerHeadDetails = array();
        $billMonth = (isset($this->request->query['month']) && !empty($this->request->query['month'])) ? $this->request->query['month'] : 1;
        $billNumberFrom = (isset($this->request->query['bill_from']) && !empty($this->request->query['bill_from'])) ? $this->request->query['bill_from'] : '';
        $billNumberTo = (isset($this->request->query['bill_to']) && !empty($this->request->query['bill_to'])) ? $this->request->query['bill_to'] : '';
		$billType = (isset($this->request->query['bill_type']) && !empty($this->request->query['bill_type'])) ? $this->request->query['bill_type'] : 'reg';
        $receiptPeriod = array('From'=>$this->request->query['receipt_from'],'To'=>$this->request->query['receipt_to']);
        $societyParameters = $this->SocietyBill->getSocietyParameters();
        $optionsQuery[] = array('AND' => array('MemberBillSummary.society_id' => $societyId, 'MemberBillSummary.month' => $billMonth));
        /*Society bill taiffs*/
        $societyLedgerHeadTitleList = array();
        $societyLedgerHeads = $this->SocietyTariffOrder->find('all', array('fields'=>array('SocietyLedgerHeads.id','SocietyLedgerHeads.title'),'conditions' => array('SocietyLedgerHeads.society_id'=>$societyId),'order'=>array('SocietyTariffOrder.tariff_serial' => 'ASC')));
        foreach($societyLedgerHeads as $head) {
            $societyLedgerHeadTitleList[$head['SocietyLedgerHeads']['id']] = $head['SocietyLedgerHeads']['title'];
        }
        if (isset($this->request->query['flat_no']) && !empty($this->request->query['flat_no'])) {
            $optionsQuery['OR']['MemberBillSummary.flat_no'] = (isset($this->request->query['flat_no']) && !empty($this->request->query['flat_no'])) ? $this->request->query['flat_no'] : '';
        } else if (isset($this->request->query['bill_generated_date']) && !empty($this->request->query['bill_generated_date'])) {
            $optionsQuery['OR']['MemberBillSummary.bill_generated_date'] = (isset($this->request->query['bill_generated_date']) && !empty($this->request->query['bill_generated_date'])) ? $this->request->query['bill_generated_date'] : '';
        }
        if (isset($billNumberFrom) && !empty($billNumberFrom) && !empty($billNumberTo)) {
            $optionsQuery['OR']['MemberBillSummary.bill_no BETWEEN ? and ?'] = array($billNumberFrom,$billNumberTo);
        } else if (isset($billNumberTo) && !empty($billNumberTo)) {
            $optionsQuery['AND']['MemberBillSummary.bill_no'] = $billNumberTo;
        } else if (isset($billNumberFrom) && !empty($billNumberFrom)) {
            $optionsQuery['AND']['MemberBillSummary.bill_no'] = $billNumberFrom;
        }
        $optionsQuery['AND']['MemberBillSummary.financial_year_id'] = $financialYearId;
        //Society ledger head list which are part of bill
        if ($this->SocietyTariffOrder->hasAny(array('SocietyTariffOrder.society_id' => $societyId))) {
            $societyLedgerHeadList = $this->SocietyTariffOrder->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyId), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyTariffOrder.id' => 'asc')));
        } else {
            $societyLedgerHeadList = $this->SocietyLedgerHeads->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyId, 'SocietyLedgerHeads.is_in_bill_charges' => 1), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyLedgerHeads.title' => 'asc'), 'recursive' => -1));
        }
        foreach ($societyLedgerHeadList as $key => $ledgerDetails) {
            $ledgerHeadDetails[$ledgerDetails['SocietyLedgerHeads']['id']] = $ledgerDetails['SocietyLedgerHeads']['title'];
        }
		$monthlyBills = $this->MemberBillSummary->find('all', array('conditions' => $optionsQuery, 'order' => array('MemberBillSummary.bill_no' => 'asc')));
        if ($monthlyBills) {
            $emailSent = '<b>Emails sent to following members</b><br>							<table border="1">								<tr>									<td><b>Sr.</b></td>									<td><b>Flat No</b></td>'



                    . '<td><b>Member</b></td>'



                    . '</tr>';



            



            $emailNotSent = '<b>Emails not sent to following members</b><br>'



                    . '<table border="1">'



                    . '<tr>'



                    . '<td><b>Sr.</b></td>'



                    . '<td><b>Flat No</b></td>'



                    . '<td><b>Member</b></td>'



                    . '</tr>';



            



            $emailNotSentSr = 1;
            $emailSentSr = 1;

            foreach ($monthlyBills as $key => $bill) {
                $printBillDetails = array();
                $memberDetails = $this->Member->find('first', array('conditions' => array('Member.id' => $bill['MemberBillSummary']['member_id'])));
                // $memberDetails['Member']['member_email']='akhileshchoudhary843@gmail.com';
                $societyName = str_replace(' ','_',$memberDetails['Society']['society_name']);
                $societyEmail = str_replace(' ','_',$memberDetails['Society']['email_id']);
                $printBillDetails[$bill['MemberBillSummary']['member_id']] = $bill;
                $tariffDetails = $this->MemberBillGenerate->find('all', array('conditions' => array('MemberBillGenerate.society_id' => $societyId, 'MemberBillGenerate.month' => $billMonth, 'MemberBillGenerate.financial_year_id' => $financialYearId,'MemberBillGenerate.member_id' => $bill['MemberBillSummary']['member_id'])));
                $printBillDetails[$bill['MemberBillSummary']['member_id']]['tariff'] = $tariffDetails;

                $printBillDetails[$bill['MemberBillSummary']['member_id']]['memberDetails'] = $memberDetails;

                // If April bill is printing then we need receipt after last bills generated dates
                if($bill['MemberBillSummary']['month'] == 4) {
                    // Get receipts from last years database
                    $memberPaymentDetails = $this->getLastYearsMarchReceipts($societyId,$bill['MemberBillSummary']['member_id'],$billType);
                } else {
                    if (isset($this->request->query['receipt_from']) && !empty($this->request->query['receipt_from']) && isset($this->request->query['receipt_to']) && !empty($this->request->query['receipt_to'])) {
                        $memberPaymentDetails = $this->Member->MemberPayment->find('all', array('conditions' => array('MemberPayment.member_id' => $bill['MemberBillSummary']['member_id'],'MemberPayment.payment_date >=' => $this->request->query['receipt_from'],'MemberPayment.payment_date <=' => $this->request->query['receipt_to'],'MemberPayment.bill_type'=>$billType),'order'=>array('MemberPayment.id'=>'desc')));
                    } else {
                        $memberPaymentDetails = $this->Member->MemberPayment->find('all', array('conditions' => array('MemberPayment.member_id' => $bill['MemberBillSummary']['member_id'],'MemberPayment.bill_type'=>$billType),'order'=>array('MemberPayment.id'=>'desc')));
                    }
                }

                // $memberPaymentDetails = $this->Member->MemberPayment->find('all', array('conditions' => array('MemberPayment.member_id' => $bill['MemberBillSummary']['member_id'])));
                $printBillDetails[$bill['MemberBillSummary']['member_id']]['receipts'] = $memberPaymentDetails;

                if(empty($memberDetails['Member']['member_email']) || trim($memberDetails['Member']['member_email']) == '-') {
                    $emailNotSent .= '<tr>'
                    . "<td>$emailNotSentSr</td>"
                    . "<td>".$memberDetails['Member']['flat_no']."</td>"
                    . "<td>".$memberDetails['Member']['member_name']."</td>"
                    . '</tr>';
                    $emailNotSent++;
                    continue;
                } else {
                    $emailBill = $this->email_bill($ledgerHeadDetails, $printBillDetails, $societyParameters, $societyLedgerHeadTitleList,$memberDetails,$this->request->query['email_id'],$receiptPeriod);
                    if($emailBill){
                        $emailSent .= '<tr>'
                        . "<td>$emailSentSr</td>"
                        . "<td>".$memberDetails['Member']['flat_no']."</td>"
                        . "<td>".$memberDetails['Member']['member_name']."</td>"
                        . '</tr>';
    
                        $emailSentSr++;
                    }
                    else{
                        $emailNotSent .= '<tr>'
                        . "<td>$emailNotSentSr</td>"
                        . "<td>".$memberDetails['Member']['flat_no']."</td>"
                        . "<td>".$memberDetails['Member']['member_name']."</td>"
                        . '</tr>';
                        $emailNotSent++;
                        continue;                        
                    }
                }
            }

            $societyEmail='admin@easylogicstechnology.com';

             if(!empty($societyEmail)) {

                     $emailMessage .= 'Hello<br><br>'.$emailSent.'</table>';
                     $emailMessage .= '<p>&nbsp;</p>';
                     $emailMessage .= $emailNotSent.'</table>';
                     //$Email = new CakeEmail("gmail");
                     //$Email->from(array('admin@easylogicstechnology.com' => 'Easy Logics'));
                     //$Email->to($societyEmail);                            
                     //$Email->subject($societyName. ' - Email Bill Report');
                     //$Email->emailFormat('html');
                     //$Email->send($emailMessage);
            }
        }

    }	
	public function send_bill_email(){}
	
	// This Code Developed By - DeepBase :: Sonu Chohan
    public function callEmailApi(
        array $recipients,
        string $subject,
        string $htmlBody,
        array $attachments = [],
        array $cc = [],
        array $bcc = [],
        ?string $replyTo = null
    ) {
        $url = 'https://api.easylogicstechnology.com/api/send-email';

        if (empty($recipients) || empty($subject) || empty($htmlBody)) {
            return ['success' => false, 'error' => 'Missing required email fields'];
        }

        // Normalize attachments
        $normalizedAttachments = [];
        foreach ($attachments as $file) {
            if (!isset($file['name'], $file['type'], $file['data'])) {
                return [
                    'success' => false,
                    'error'   => 'Attachment must include name, type, and data'
                ];
            }

            $normalizedAttachments[] = [
                'name' => $file['name'],
                'type' => $file['type'],
                'data' => $file['data']
            ];
        }

        $payload = [
            'recipients' => $recipients,
            'subject'    => $subject,
            'htmlBody'   => $htmlBody
        ];

        if ($normalizedAttachments) $payload['attachments'] = $normalizedAttachments;
        if ($cc) $payload['cc'] = $cc;
        if ($bcc) $payload['bcc'] = $bcc;
        if ($replyTo) $payload['replyTo'] = $replyTo;

        $json = json_encode($payload);
        if ($json === false) {
            return ['success' => false, 'error' => json_last_error_msg()];
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_POSTFIELDS     => $json,
            CURLOPT_TIMEOUT        => 30
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            return ['success' => false, 'error' => curl_error($ch)];
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'success'  => $httpCode >= 200 && $httpCode < 300,
            'httpCode' => $httpCode,
            'response' => json_decode($response, true)
        ];
    }





	
    function email_bill($ledgerHeadDetails, $printBillDetails,$societyParameters,$societyLedgerHeadTitleList,$memberDetails,$ccEmails,$receiptPeriod) {
        // App::uses('CakeEmail', 'Network/Email');
        $dompdf = new DOMPDF(array('debugLayout' => true));
        $this->set(compact('ledgerHeadDetails', 'printBillDetails','societyParameters','societyLedgerHeadTitleList','receiptPeriod'));
        $this->layout = false;
        
        try {
            $html= $this->render('email_member_bills');
            $dompdf->load_html($html);
            $dompdf->render();

            $output = $dompdf->output();
            $ccEmailId = explode(',',$ccEmails);
            $ccEmailId = $ccEmailId[0];

            $emailIdArray = explode(',',$memberDetails['Member']['member_email']);
            //$emailId = trim($memberDetails['Member']['member_email']);
            $emailId = trim($emailIdArray[0]);

            $flatNo = str_replace(' ','_',$memberDetails['Member']['flat_no']);
            $societyName = str_replace(' ','_',$memberDetails['Society']['society_name']);
            $societyEmail = str_replace(' ','_',$memberDetails['Society']['email_id']);
            $memberName = str_replace(' ','_',$memberDetails['Member']['member_name']);
            // if(empty($societyEmail)) {
                $societyEmail = 'admin@easylogicstechnology.com';
            //}
            $flatNo = (strpos($flatNo,'/') !== false) ? str_replace('/','-',$flatNo) : $flatNo;
            $fileName = APP.'/tmp/Bill_'.$flatNo.'_'.time().'.pdf';
            file_put_contents($fileName, $output);

            $headers[] = 'To:Name<'.$emailId.'>';
            $headers[] = 'from: '.$societyName.'<'.$societyEmail.'>';           
            
            $cc = [];
            if(!empty($ccEmailId)) {
                $cc = (array)$ccEmailId;
            }    
            
            /******************************************* Edit by Sonu Chohan   ************************/
            $base64Pdf = base64_encode($output);
            $recipients = [$emailId];
            $subject    = 'Maintenance Bill - Flat '.$flatNo.' Member '.$memberName;
            $htmlBody   = "<!DOCTYPE html><html><body><p><strong>Society Name :</strong>$societyName</p><p>Dear $memberName </p><p>Your maintenance bill is attached below.</p></body></html>";
            
            // Example attachment
            $attachments = [
                [
                    "name" => "bill_".$flatNo.".pdf",
                    "type" => "application/pdf",
                    "data" => $base64Pdf
                ]
            ];
          
            // Ensure CC is always an array
			$cc = $cc ?? [];
        
            // Call API method
            $response = $this->callEmailApi($recipients, $subject, $htmlBody, $attachments,$cc);
          
             //echo "hi";
        
            // Print / handle API response
            //print_r($response);   die;
            /******************************************* End Here   ************************/
    
            // $Email->send('Maintenance Bill');
            return true;
        }
        catch(Exception $e){
            echo "error message ".$e->getMessage();exit;
        }        
    }
    
    public function settleMemberBillByLIFOMethod(){
        
    }
    
}



