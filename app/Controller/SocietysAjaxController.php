<?php

App::uses('AppController', 'Controller');

App::uses('SocietyBillsController','Controller');

App::uses('AccountReportsController','Controller');



/**

 * Users Controller

 *

 * @property User $User

 * @property PaginatorComponent $Paginator

 * @property SessionComponent $Session

 */









class SocietysAjaxController extends AppController {



    /**

     * Components

     *

     * @var array

     */

    public $components = array('Paginator', 'Session', 'RequestHandler','Util','SocietyBill');

    public $helpers = array('Js');

    //public $uses = array('Society');



    public function beforeFilter() {

        parent::beforeFilter();

        $this->loadModel('Society');

        $this->loadModel('Wing');

        $this->loadModel('Society');

    }

    

    public function get_all_buildingwings() {

        if ($this->RequestHandler->isAjax()) {

            //$this->autoRender = false;

            $buildingId = isset($this->request->data['building_id']) ? $this->request->data['building_id'] : 0;

            $wingLists = $this->Wing->find('list', array('fields' => 'id,wing_name', 'conditions' => array('Wing.building_id' =>$buildingId,'Wing.society_id' => $this->Auth->user('id'),'Wing.status' =>Configure::read('Active')),'order' => array('wing_name' => 'asc')));

            $this->set(compact('wingLists'));

        }

    }

    

    public function get_account_heads(){

        if ($this->RequestHandler->isAjax()) {

            $this->loadModel('AccountHead');

            $accountId = isset($this->request->data['accountId']) ? $this->request->data['accountId'] : 0;

            $accountHeadLists = $this->AccountHead->find('list', array('fields' => 'id,title','conditions' => array('AccountHead.account_category_id' =>$accountId,'AccountHead.status' =>Configure::read('Active')),'order' => array('AccountHead.title' => 'asc')));

            $this->set(compact('accountHeadLists'));

        }

    }

    

    public function get_account_categories(){

        if ($this->RequestHandler->isAjax()) {		

            $this->loadModel('SocietyHeadSubCategory');

            $this->SocietyHeadSubCategory->recursive = 2;

            $headSubCategoryId = isset($this->request->data['headSubCategoryId']) ? $this->request->data['headSubCategoryId'] : 0;

            $accountHeadCategoryLists = $this->SocietyHeadSubCategory->find('all',array('fields' =>array('AccountCategory.id,AccountCategory.title'),'conditions' => array('SocietyHeadSubCategory.id' =>$headSubCategoryId,'SocietyHeadSubCategory.status' =>Configure::read('Active')),'order' => array('SocietyHeadSubCategory.title' => 'asc')));

            $this->set(compact('accountHeadCategoryLists'));

        }

    }

    

    public function account_heads_by_categoryids(){

        if ($this->RequestHandler->isAjax()) {

            $this->loadModel('SocietyHeadSubCategory');

            $this->SocietyHeadSubCategory->recursive = 1;

            $headSubCategoryId = isset($this->request->data['headSubCategoryId']) ? $this->request->data['headSubCategoryId'] : 0;

            $accountHeadsLists = $this->SocietyHeadSubCategory->find('all',array('fields' =>array('AccountHead.id,AccountHead.title'),'conditions' => array('SocietyHeadSubCategory.id' =>$headSubCategoryId,'SocietyHeadSubCategory.status' =>Configure::read('Active')),'order' => array('SocietyHeadSubCategory.title' => 'asc')));

            $this->set(compact('accountHeadsLists'));

        }

    }

    

    public function societyMemberPaymentDetails($societyMemberId = null) {

        if ($this->RequestHandler->isAjax()) {
            $financialYearId = $this->Util->getFinancialYear();
            $this->loadModel('Member');

            $requestData = $this->request->data;

            $societyMemberId = isset($societyMemberId) ? $societyMemberId : 0;

            if ($this->Member->MemberPayment->hasAny(array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'), 
            'MemberPayment.financial_year_id'=> $financialYearId,'MemberPayment.member_id' => $societyMemberId))) {

                $resultArr = $this->getSocietyMemberPaymentDataListing($requestData, $societyMemberId);

                $totalFiltered = isset($resultArr['total_filtered']) ? $resultArr['total_filtered'] : count($resultArr);

                $data = $resultArr['data'];

                $json_data = array(

                    "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.

                    "recordsTotal" => intval(count($resultArr)), // total number of records

                    "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData

                    "data" => $data   // total data array

                );

            } else {

                $json_data = array(

                    "draw" => intval(0), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.

                    "recordsTotal" => intval(0), // total number of records

                    "recordsFiltered" => intval(0), // total number of records after searching, if there is no searching then totalFiltered = totalData

                    "data" => array()   // total data array

                );

            }

            return new CakeResponse(array('body' => json_encode($json_data), 'status' => 200));

        }

    }

    

    public function getSocietyMemberPaymentDataListing($requestData, $societyMemberId) {

        $returnArr = array();
        $financialYearId = $this->Util->getFinancialYear();
        $this->loadModel('MemberPayment');

        if (!empty($requestData)) {

            $societyBankBalanceHeadsLists = $this->SocietyBill->societyBankBalanceHeadsLists();

            $societyCashBalanceHeadsLists = $this->SocietyBill->societyCashBalanceHeadsLists();

            $PaymentModeLists = $this->SocietyBill->getSocietyPaymentModeLists();

            //print_r($societyBankLists);die;

            $data = array();

            $columns = array(

                0 => 'id',

                1 => 'bill_generated_id',

                2 => 'payment_date',

                3 => 'amount_paid',

                4 => 'society_bank_id',

                5 => 'payment_mode',

                6 => 'cheque_reference_number',

                7 => 'bill_type',

                8 => 'action_edit',

                9 => 'action_delete'

            );

            $requestData['start'] = isset($requestData['start']) ? $requestData['start'] : 0;

            $requestData['length'] = isset($requestData['length']) ? $requestData['length'] : 5;



            if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {

                $options['OR']['MemberPayment.payment_date LIKE'] = '%' . $requestData['search']['value'] . '%';

                $options['OR']['MemberPayment.amount_paid LIKE'] = '%' . $requestData['search']['value'] . '%';

                $options['OR']['MemberPayment.payment_mode LIKE'] = '%' . $requestData['search']['value'] . '%';

                $options['OR']['MemberPayment.cheque_reference_number LIKE'] = '%' . $requestData['search']['value'] . '%';

                $options['OR']['MemberPayment.society_bank_id LIKE'] = '%' . $requestData['search']['value'] . '%';

                $options['OR']['MemberPayment.bill_generated_id LIKE'] = '%' . $requestData['search']['value'] . '%';

                $options['OR']['MemberPayment.bill_type LIKE'] = '%' . $requestData['search']['value'] . '%';

            }

            $options[] = array('AND' => array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'), 'MemberPayment.member_id' => $societyMemberId ,'MemberPayment.financial_year_id'  => $financialYearId));



            if (isset($requestData['order'][0]['column']) && isset($requestData['order'][0]['dir'])) {

                $order[] = array('MemberPayment.' . $columns[$requestData['order'][0]['column']] => $requestData['order'][0]['dir']);

            } else {

                $order[] = array('MemberPayment.id' => 'asc');

            }



            if (isset($requestData['start']) && isset($requestData['length'])) {

                $limit = $requestData['length'];

            } else {

                $limit = 10;

            }



            $memberPaymentArr = $this->MemberPayment->find('all', array('conditions' => $options, 'limit' => $limit, 'order' => $order));

            if (count($memberPaymentArr) > 0) {

                $count = 1;

                foreach ($memberPaymentArr as $memberData) {

                    $nestedData = array();

                    $actionEditString = "<td class='text-nowrap'><a href='javascript:void(0);' class='mr-25' data-toggle='tooltip' data-original-title='Edit' onclick='editMemberPaymentDetails(".$memberData['MemberPayment']['id'].");'><i class='fa fa-pencil text-inverse m-r-10'></i></a></td>";

                    //$actionDeleteString = "<td class='text-nowrap'><a href='javascript:void(0);' class='mr-25' data-toggle='tooltip' data-original-title='Edit' onclick='deleteMemberPaymentDetails(".$memberData['MemberPayment']['id'].");'><i class='fa fa-close text-danger'></i></a></td>";

                    $actionDeleteString = "<td class='text-nowrap'><a href='javascript:void(0);' class='mr-25' data-toggle='tooltip' data-original-title='Edit'><i class='fa fa-close text-danger'></i></a></td>";

                    $nestedData[] = $count;

                    $nestedData[] = isset($memberData['MemberPayment']['bill_generated_id']) ? $memberData['MemberPayment']['bill_generated_id'] : '';

                    $nestedData[] = isset($memberData['MemberPayment']['payment_date']) ? $memberData['MemberPayment']['payment_date'] : '';

                    $nestedData[] = isset($memberData['MemberPayment']['amount_paid']) ? $memberData['MemberPayment']['amount_paid'] : '';

                    if($memberData['MemberPayment']['payment_mode'] == Configure::read('PaymentMode.Cash')){

                        $nestedData[] = isset($societyCashBalanceHeadsLists[$memberData['MemberPayment']['society_bank_id']]) ? $societyCashBalanceHeadsLists[$memberData['MemberPayment']['society_bank_id']] : '';

                    }else{

                        $nestedData[] = isset($societyBankBalanceHeadsLists[$memberData['MemberPayment']['society_bank_id']]) ? $societyBankBalanceHeadsLists[$memberData['MemberPayment']['society_bank_id']] : '';

                    }

                    $nestedData[] = isset($PaymentModeLists[$memberData['MemberPayment']['payment_mode']]) ? $PaymentModeLists[$memberData['MemberPayment']['payment_mode']] : '';

                    $nestedData[] = isset($memberData['MemberPayment']['cheque_reference_number']) ? $memberData['MemberPayment']['cheque_reference_number'] : '';

                    $nestedData[] = isset($memberData['MemberPayment']['bill_type']) ? $memberData['MemberPayment']['bill_type'] : '';

                    $nestedData[] = $actionEditString;

                    $nestedData[] = $actionDeleteString;

                    $data[] = $nestedData;

                    $count++;

                }

            }

            $returnArr['total_filtered'] = count($memberPaymentArr);

            $returnArr['data'] = $data;

        }

        return $returnArr;

    }



    public function society_member_tariff_details() {

        $dataArry = array();

        if ($this->RequestHandler->isAjax()) {

            $this->layout = false;

            $this->loadModel('Member');

            $this->loadModel('MemberTariff');

            $this->loadModel('MemberTariffDetails');

            $societyMemberId = isset($this->request->data['societyMemberId']) ? $this->request->data['societyMemberId'] : 0;

            $societyMemberTariff = $this->MemberTariff->find('all', array('conditions' => array('MemberTariff.member_id' => $societyMemberId, 'MemberTariff.society_id' => $this->Session->read('Auth.User.id'),'SocietyLedgerHeads.status' => 1)));



            $societyMemberTariffDetails = $this->MemberTariffDetails->find('first', array('conditions' => array('MemberTariffDetails.member_id' => $societyMemberId)));



            $societyMemberDetails = $this->Member->find('first', array('conditions' => array('Member.society_id' => $this->Session->read('Auth.User.id'),'Member.id' => $societyMemberId), 'fields' => array('Member.flat_no')));



            $tariffSetData['tariff_effective_since'] = empty($societyMemberTariffDetails['MemberTariffDetails']['tariff_effective_since']) ? '' : $societyMemberTariffDetails['MemberTariffDetails']['tariff_effective_since'];



            $tariffSetData['flat_no'] = empty($societyMemberDetails['Member']['flat_no']) ? '' : $societyMemberDetails['Member']['flat_no'];



            $tariffSetData['remark'] = empty($societyMemberTariffDetails['MemberTariffDetails']['remark']) ? '' : $societyMemberTariffDetails['MemberTariffDetails']['remark'];

            $tariffSetData['updated_date'] = empty($societyMemberTariffDetails['MemberTariffDetails']['updated_date']) ? '' : $societyMemberTariffDetails['MemberTariffDetails']['updated_date'];

            $dataArry['MemberTariffDetails'] = $tariffSetData;



            if (isset($societyMemberTariff) && count($societyMemberTariff) > 0) {

                $tariffCount = 0;

                foreach ($societyMemberTariff as $tariffData) {

                    $memberTariffData[$tariffCount]['ledger_head_id'] = isset($tariffData['MemberTariff']['ledger_head_id']) ? $tariffData['MemberTariff']['ledger_head_id'] : '';

                    $memberTariffData[$tariffCount]['amount'] = isset($tariffData['MemberTariff']['amount']) ? $tariffData['MemberTariff']['amount'] : '';

                    $memberTariffData[$tariffCount]['title'] = isset($tariffData['SocietyLedgerHeads']['title']) ? $tariffData['SocietyLedgerHeads']['title'] : '';

                    $tariffCount++;

                }

                $dataArry['MemberTariff'] = $memberTariffData;

            }

        }

        return new CakeResponse(array('body' => json_encode($dataArry), 'status' => 200));

    }



    public function society_building_tariff_details() {

        $dataArry = array();

        if ($this->RequestHandler->isAjax()) {

            $this->layout = false;

            $this->loadModel('Building');

            $this->loadModel('SocietyTariffBuildings');           

            

            $buildingId = isset($this->request->data['buildingId']) ? $this->request->data['buildingId'] : 0;

            

            $societyBuildingTariff = $this->SocietyTariffBuildings->find('all', array('conditions' => array('SocietyTariffBuildings.building_id' => $buildingId, 'SocietyTariffBuildings.society_id' => $this->Session->read('Auth.User.id'))));

            

            $buildingTariffData = array();

            

            if (isset($societyBuildingTariff) && count($societyBuildingTariff) > 0) {

                $tariffCount = 0;

                foreach ($societyBuildingTariff as $tariffData) {

                    $buildingTariffData[$tariffCount]['ledger_head_id'] = isset($tariffData['SocietyTariffBuildings']['ledger_head_id']) ? $tariffData['SocietyTariffBuildings']['ledger_head_id'] : '';

                    $buildingTariffData[$tariffCount]['amount'] = isset($tariffData['SocietyTariffBuildings']['amount']) ? $tariffData['SocietyTariffBuildings']['amount'] : '';

                    $buildingTariffData[$tariffCount]['effective_since'] = isset($tariffData['SocietyTariffBuildings']['effective_since']) ? $tariffData['SocietyTariffBuildings']['effective_since'] : '';

                    $buildingTariffData[$tariffCount]['title'] = isset($tariffData['SocietyLedgerHeads']['title']) ? $tariffData['SocietyLedgerHeads']['title'] : '';

                    $tariffCount++;

                }

                

                $dataArry['BuildingTariff'] = $buildingTariffData;

            }

        }

        return new CakeResponse(array('body' => json_encode($dataArry), 'status' => 200));

    }

    

    public function society_wing_tariff_details() {

        $dataArry = array();

        if ($this->RequestHandler->isAjax()) {

            $this->layout = false;

            $this->loadModel('SocietyTariffWings');           

            

            $buildingId = isset($this->request->data['buildingId']) ? $this->request->data['buildingId'] : 0;

            $wingId = isset($this->request->data['wingId']) ? $this->request->data['wingId'] : 0;

            

            $societyWingTariff = $this->SocietyTariffWings->find('all', array('conditions' => array('SocietyTariffWings.building_id' => $buildingId,'SocietyTariffWings.wing_id' => $wingId, 'SocietyTariffWings.society_id' => $this->Session->read('Auth.User.id'))));

            

            $wingTariffData = array();

            

            if (isset($societyWingTariff) && count($societyWingTariff) > 0) {

                $tariffCount = 0;

                foreach ($societyWingTariff as $tariffData) {

                    $wingTariffData[$tariffCount]['ledger_head_id'] = isset($tariffData['SocietyTariffWings']['ledger_head_id']) ? $tariffData['SocietyTariffWings']['ledger_head_id'] : '';

                    $wingTariffData[$tariffCount]['amount'] = isset($tariffData['SocietyTariffWings']['amount']) ? $tariffData['SocietyTariffWings']['amount'] : '';

                    $wingTariffData[$tariffCount]['effective_since'] = isset($tariffData['SocietyTariffWings']['effective_since']) ? $tariffData['SocietyTariffWings']['effective_since'] : '';

                    $wingTariffData[$tariffCount]['title'] = isset($tariffData['SocietyLedgerHeads']['title']) ? $tariffData['SocietyLedgerHeads']['title'] : '';

                    $tariffCount++;

                }

                

                $dataArry['WingTariff'] = $wingTariffData;

            }

        }

        return new CakeResponse(array('body' => json_encode($dataArry), 'status' => 200));

    }

    

    public function society_unit_type_tariff_details() {

        $dataArry = array();

        if ($this->RequestHandler->isAjax()) {

            $this->layout = false;

            $this->loadModel('SocietyTariffUnitTypes');           

            

            $unitType = isset($this->request->data['unitType']) ? $this->request->data['unitType'] : '';

            

            $societyUnitTypeTariff = $this->SocietyTariffUnitTypes->find('all', array('conditions' => array('SocietyTariffUnitTypes.unit_type' => $unitType, 'SocietyTariffUnitTypes.society_id' => $this->Session->read('Auth.User.id'))));

            

            $unitTypeTariffData = array();

            

            if (isset($societyUnitTypeTariff) && count($societyUnitTypeTariff) > 0) {

                $tariffCount = 0;

                foreach ($societyUnitTypeTariff as $tariffData) {

                    $unitTypeTariffData[$tariffCount]['ledger_head_id'] = isset($tariffData['SocietyTariffUnitTypes']['ledger_head_id']) ? $tariffData['SocietyTariffUnitTypes']['ledger_head_id'] : '';

                    $unitTypeTariffData[$tariffCount]['amount'] = isset($tariffData['SocietyTariffUnitTypes']['amount']) ? $tariffData['SocietyTariffUnitTypes']['amount'] : '';

                    $unitTypeTariffData[$tariffCount]['effective_since'] = isset($tariffData['SocietyTariffUnitTypes']['effective_since']) ? $tariffData['SocietyTariffUnitTypes']['effective_since'] : '';

                    $unitTypeTariffData[$tariffCount]['title'] = isset($tariffData['SocietyLedgerHeads']['title']) ? $tariffData['SocietyLedgerHeads']['title'] : '';

                    $tariffCount++;

                }

                

                $dataArry['UnitTypeTariff'] = $unitTypeTariffData;

            }

        }

        return new CakeResponse(array('body' => json_encode($dataArry), 'status' => 200));

    }

    

    public function sortMultiDimentionalArray($a,$b){

        return (strtotime($a["formatted_date"]) <= strtotime($b["formatted_date"])) ? -1 : 1;

    }

  

public function society_ledger_head_details_test(){

        $societyLedgerHeadDetails = array('SocietyLedgerHeads'=>array('status'=> 1));
        echo $financialYearId = $this->Util->getFinancialYear();
        echo $societyLoginId = $this->Session->read('Auth.User.id');
        //if ($this->RequestHandler->isAjax()) 
       // echo "1";exit;
        {

            $this->layout = false;

            $this->loadModel('SocietyLedgerHeads');

            //$this->SocietyLedgerHeads->recursive = -1;

            $societyLedgerHeadId = 25998;//isset($this->request->data['societyLedgerheadId']) ? $this->request->data['societyLedgerheadId'] : 0;
            
            $openingData = $this->SocietyBill->getSocietyLedgerOpClosingBal($societyLoginId,$societyLedgerHeadId);

            
            $societyLedgerHeadDetails = $this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.id' => $societyLedgerHeadId,
            'SocietyLedgerHeads.society_id' => $this->Session->read('Auth.User.id'), 'SocietyLedgerHeads.status' => Configure::read('Active'))));            


            if (isset($societyLedgerHeadDetails) && count($societyLedgerHeadDetails) > 0) {

                $societyLedgerHeadsNextId = $this->SocietyLedgerHeads->find('first', array('fields' => array('SocietyLedgerHeads.id', 'SocietyLedgerHeads.title'),
                'conditions' => array('SocietyLedgerHeads.id >' => $societyLedgerHeadDetails['SocietyLedgerHeads']['id'], 
                'SocietyLedgerHeads.society_id' => $societyLoginId, 'SocietyLedgerHeads.status' => Configure::read('Active')), 'order' => array('SocietyLedgerHeads.id' => 'asc')));

                $societyLedgerHeadsPreviousId = $this->SocietyLedgerHeads->find('first', array('fields' => array('SocietyLedgerHeads.id', 'SocietyLedgerHeads.title'), 
                'conditions' => array('SocietyLedgerHeads.id <' => $societyLedgerHeadDetails['SocietyLedgerHeads']['id'], 
                'SocietyLedgerHeads.society_id' => $societyLoginId, 'SocietyLedgerHeads.status' => Configure::read('Active')), 
                'order' => array('SocietyLedgerHeads.id' => 'desc')));

                $op = $this->getFirstDayFinancialYear();                

                $categoryId = $societyLedgerHeadDetails['SocietyLedgerHeads']['account_category_id'];         
                $openingAmount = empty($openingData) ? 0 : $openingData['balance_amount'];
                $societyLedgerHeadDetails['SocietyLedgerHeads']['openingdate'] = $op;
                $societyLedgerHeadDetails['SocietyLedgerHeads']['opening_amount'] = ($categoryId == 3 || $categoryId == 4) ? 0 : $openingAmount;

                $societyLedgerHeadDetails['SocietyLedgerHeads']['next'] = 0;

                $societyLedgerHeadDetails['SocietyLedgerHeads']['previous'] = 0;

                $societyLedgerHeadDetails['SocietyLedgerHeads']['previous_id'] = (isset($societyLedgerHeadsPreviousId['SocietyLedgerHeads']['id']) && $societyLedgerHeadsPreviousId['SocietyLedgerHeads']['id'] != '') ? $societyLedgerHeadsPreviousId['SocietyLedgerHeads']['id'] : 0;

                $societyLedgerHeadDetails['SocietyLedgerHeads']['next_id'] = (isset($societyLedgerHeadsNextId['SocietyLedgerHeads']['id']) && $societyLedgerHeadsNextId['SocietyLedgerHeads']['id'] != '') ? $societyLedgerHeadsNextId['SocietyLedgerHeads']['id'] : 0;

                if (empty($societyLedgerHeadsPreviousId)) {

                    $societyLedgerHeadDetails['SocietyLedgerHeads']['previous'] = 1;

                }

                if (empty($societyLedgerHeadsNextId)) {

                    $societyLedgerHeadDetails['SocietyLedgerHeads']['next'] = 1;

                }

                $societyLedgerHeadDetails['SocietyLedgerHeads']['status'] = 1;

                

                //Get society ledger head expenses and payments

                $txnType = $societyLedgerHeadDetails['AccountHead']['transaction_type'];

                if(stripos($txnType,'credit') !== false) {

                 $txnType = 'credit'; 

             } else if(stripos($txnType,'debit') !== false) {

                 $txnType = 'debit'; 

             }



             $isInBill = $societyLedgerHeadDetails['SocietyLedgerHeads']['is_in_bill_charges'];

            echo "Is in Bill".$isInBill;
            var_dump($isInBill);
        

             $societyPaymentData = array();


                $tdsType = $societyLedgerHeadDetails['SocietyLedgerHeads']['tds_type'];
                $isTdsApp = $societyLedgerHeadDetails['SocietyLedgerHeads']['is_tds'];
             if($isInBill == 0) {

                    // get records from society_payments
                
                $this->loadModel('SocietyPayment');
                if($tdsType == 1){
                    $condition = array(
                    'SocietyPayment.tds_account_id' => $societyLedgerHeadId,
                    'SocietyPayment.financial_year_id' => $financialYearId ,
                    'SocietyPayment.society_id' => $this->Session->read('Auth.User.id'));
                }
                else{
                    
                    $condition = array(
                    'SocietyPayment.ledger_head_id' => $societyLedgerHeadId,
                    'SocietyPayment.financial_year_id' => $financialYearId ,
                    'SocietyPayment.society_id' => $this->Session->read('Auth.User.id'));
                }
                $societyPayments = $this->SocietyPayment->find('all', array('conditions' => $condition));


                if (!empty($societyPayments)) {

                    foreach ($societyPayments as $paymentDetails) {

                        $paymentData = $paymentDetails['SocietyPayment'];

                        $particular = wordwrap($paymentData['particulars'], 60, "<br />");

                        $paymentDate = date('d/m/Y', strtotime($paymentData['payment_date']));

                        $formattedDate = date('Y-m-d', strtotime($paymentData['payment_date']));
                        $txn = ($tdsType == 1) ? 'credit' : 'debit';
                        $amount = ($tdsType == 1) ? $paymentData['tax_amount'] :($isTdsApp == 1 ? $paymentData['amount'] : $paymentData['total_amount']); 
                        $societyPaymentData[] = array('payment_date' => $paymentDate, 'formatted_date' => $formattedDate, 
                        'amount' => $amount, 'txn_type' => $txn,'particulars'=>$particular,'payment_id' => $paymentData['id'],'notes'=>$paymentData['notes'],'flat_no'=>'');

                    }

                }

            } else {

                    // get records from member bills
                   

                $this->loadModel('MemberBillGenerate');

                    //$memberBills = $this->MemberBillGenerate->find('all', array('conditions' => array('MemberBillGenerate.ledger_head_id' => $societyLedgerHeadId, 'MemberBillGenerate.society_id' => $this->Session->read('Auth.User.id'))));

                $conditions = array('MemberBillGenerate.ledger_head_id' => $societyLedgerHeadId, 'MemberBillGenerate.society_id' => $this->Session->read('Auth.User.id'));                    

                $memberBills = (new AccountReportsController)->getMemberBillGenerateData($conditions);

echo "<pre>";
print_r($memberBills);
//exit;

                if (!empty($memberBills)) {

                    foreach ($memberBills as $paymentDetails) {
                       

                        $paymentData = $paymentDetails['MemberBillGenerate'];

//print_r($paymentData);die;
                        $memberInfoData = $paymentDetails['Member'];

                        $flatNo = $memberInfoData['flat_no'];                            

                        $paymentDate = date('d/m/Y', strtotime($paymentData['bill_generated_date']));

                        $formattedDate = date('Y-m-d', strtotime($paymentData['bill_generated_date']));

                        $societyPaymentData[] = array('payment_date' => $paymentDate, 'formatted_date' => $formattedDate, 'amount' => $paymentDetails[0]['amount'], 'txn_type' => $txnType,'notes'=>'','flat_no'=> $flatNo);

                    }

                }
                print_r($societyPaymentData);die;
            }





		//Other income

            $this->loadModel('SocietyOtherIncome');
                 if($tdsType == 1){
                    $condition =  array('SocietyOtherIncome.tds_bank_id'=>$societyLedgerHeadId ,
                    'SocietyOtherIncome.financial_year_id' => $financialYearId);
                }
                else{
                    
                    $condition =  array('SocietyOtherIncome.ledger_head_id'=>$societyLedgerHeadId ,
                    'SocietyOtherIncome.financial_year_id' => $financialYearId);
                }
            $SocietyOtherIncomeDetails = $this->SocietyOtherIncome->find('all', array('conditions' =>  $condition,            
            'order' => array('SocietyOtherIncome.payment_date' => 'asc')));

            if (!empty($SocietyOtherIncomeDetails)) {

                foreach ($SocietyOtherIncomeDetails as $paymentDetails) {

                    $paymentData = $paymentDetails['SocietyOtherIncome'];

                    $particular = wordwrap($paymentData['title'], 60, "<br />");

                    $paymentDate = date('d/m/Y', strtotime($paymentData['payment_date']));

                    $formattedDate = date('Y-m-d', strtotime($paymentData['payment_date']));
                    $txn = ($tdsType == 1) ? 'debit' : 'credit';
                    $amount = ($tdsType == 1) ? $paymentData['tds_amount'] :$paymentData['amount_paid']; 
                    $societyPaymentData[] = array('payment_date' => $paymentDate, 'formatted_date' => $formattedDate, 'amount' => $amount, 'txn_type' => $txn,'particulars'=>$particular,'notes'=>'','flat_no'=> '');

                }

            }



            $journalVoucherLedgerPaymentData = $this->SocietyBill->getJournalVoucherCreaditDebitDataByLedgerHeadId($societyLedgerHeadId);



            if (!empty($journalVoucherLedgerPaymentData)) {

                foreach ($journalVoucherLedgerPaymentData as $jvData) {

                    $txnJvType = '';

                    $jvAmt = 0;

                    $JVPARTICULAR = "";

                    if ($jvData['JournalVoucher']['jv_type'] == 'Debit') {

                        $txnJvType = "debit";

                        $jvAmt = $jvData['JournalVoucher']['jv_amount_debited'];

                        $JVPARTICULAR = "JV Debited. V.No ".$jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'];

                    }



                    if ($jvData['JournalVoucher']['jv_type'] == 'Credit') {

                        $txnJvType = "credit";

                        $jvAmt = $jvData['JournalVoucher']['jv_amount_credited'];

                        $JVPARTICULAR = "JV Credited. V.No ".$jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'];

                    }



                    $paymentDate = isset($jvData['JournalVoucher']['voucher_date']) ? date('d/m/Y', strtotime($jvData['JournalVoucher']['voucher_date'])) : "";

                    $formattedDate = isset($jvData['JournalVoucher']['voucher_date']) ? date('Y-m-d', strtotime($jvData['JournalVoucher']['voucher_date'])) : "";

                    $societyPaymentData[] = array('particulars' => $JVPARTICULAR,'particularNote' => $JVPARTICULAR, 'payment_date' => $paymentDate, 'formatted_date' => $formattedDate, 'amount' => $jvAmt, 'txn_type' => $txnJvType,'notes'=>'','flat_no'=> '');

                }

            }

            usort($societyPaymentData,array($this,"sortMultiDimentionalArray"));

            $societyLedgerHeadDetails['ledgerPaymentData'] = $societyPaymentData;
            
            $societyLedgerHeadDetails['txn_type'] = $txnType;
            
            $societyLedgerHeadDetails['openingData'] = $openingData;
        }

    }

    return new CakeResponse(array('body' => json_encode($societyLedgerHeadDetails), 'status' => 200));

}

    public function society_ledger_head_details(){

        $societyLedgerHeadDetails = array('SocietyLedgerHeads'=>array('status'=> 1));
        $financialYearId = $this->Util->getFinancialYear();
        $societyLoginId = $this->Session->read('Auth.User.id');
        if ($this->RequestHandler->isAjax()) {

            $this->layout = false;

            $this->loadModel('SocietyLedgerHeads');

            //$this->SocietyLedgerHeads->recursive = -1;

            $societyLedgerHeadId = isset($this->request->data['societyLedgerheadId']) ? $this->request->data['societyLedgerheadId'] : 0;
            
            $openingData = $this->SocietyBill->getSocietyLedgerOpClosingBal($societyLoginId,$societyLedgerHeadId);

            
            $societyLedgerHeadDetails = $this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.id' => $societyLedgerHeadId,
            'SocietyLedgerHeads.society_id' => $this->Session->read('Auth.User.id'), 'SocietyLedgerHeads.status' => Configure::read('Active'))));            

            if (isset($societyLedgerHeadDetails) && count($societyLedgerHeadDetails) > 0) {

                $societyLedgerHeadsNextId = $this->SocietyLedgerHeads->find('first', array('fields' => array('SocietyLedgerHeads.id', 'SocietyLedgerHeads.title'),
                'conditions' => array('SocietyLedgerHeads.id >' => $societyLedgerHeadDetails['SocietyLedgerHeads']['id'], 
                'SocietyLedgerHeads.society_id' => $societyLoginId, 'SocietyLedgerHeads.status' => Configure::read('Active')), 'order' => array('SocietyLedgerHeads.id' => 'asc')));

                $societyLedgerHeadsPreviousId = $this->SocietyLedgerHeads->find('first', array('fields' => array('SocietyLedgerHeads.id', 'SocietyLedgerHeads.title'), 
                'conditions' => array('SocietyLedgerHeads.id <' => $societyLedgerHeadDetails['SocietyLedgerHeads']['id'], 
                'SocietyLedgerHeads.society_id' => $societyLoginId, 'SocietyLedgerHeads.status' => Configure::read('Active')), 
                'order' => array('SocietyLedgerHeads.id' => 'desc')));

                $op = $this->getFirstDayFinancialYear();                

                $categoryId = $societyLedgerHeadDetails['SocietyLedgerHeads']['account_category_id'];         
                $openingAmount = empty($openingData) ? 0 : $openingData['balance_amount'];
                $societyLedgerHeadDetails['SocietyLedgerHeads']['openingdate'] = $op;
                $societyLedgerHeadDetails['SocietyLedgerHeads']['opening_amount'] = ($categoryId == 3 || $categoryId == 4) ? 0 : $openingAmount;

                $societyLedgerHeadDetails['SocietyLedgerHeads']['next'] = 0;

                $societyLedgerHeadDetails['SocietyLedgerHeads']['previous'] = 0;

                $societyLedgerHeadDetails['SocietyLedgerHeads']['previous_id'] = (isset($societyLedgerHeadsPreviousId['SocietyLedgerHeads']['id']) && $societyLedgerHeadsPreviousId['SocietyLedgerHeads']['id'] != '') ? $societyLedgerHeadsPreviousId['SocietyLedgerHeads']['id'] : 0;

                $societyLedgerHeadDetails['SocietyLedgerHeads']['next_id'] = (isset($societyLedgerHeadsNextId['SocietyLedgerHeads']['id']) && $societyLedgerHeadsNextId['SocietyLedgerHeads']['id'] != '') ? $societyLedgerHeadsNextId['SocietyLedgerHeads']['id'] : 0;

                if (empty($societyLedgerHeadsPreviousId)) {

                    $societyLedgerHeadDetails['SocietyLedgerHeads']['previous'] = 1;

                }

                if (empty($societyLedgerHeadsNextId)) {

                    $societyLedgerHeadDetails['SocietyLedgerHeads']['next'] = 1;

                }

                $societyLedgerHeadDetails['SocietyLedgerHeads']['status'] = 1;

                

                //Get society ledger head expenses and payments

                $txnType = $societyLedgerHeadDetails['AccountHead']['transaction_type'];

                if(stripos($txnType,'credit') !== false) {

                 $txnType = 'credit'; 

             } else if(stripos($txnType,'debit') !== false) {

                 $txnType = 'debit'; 

             }



             $isInBill = $societyLedgerHeadDetails['SocietyLedgerHeads']['is_in_bill_charges'];



             $societyPaymentData = array();


                $tdsType = $societyLedgerHeadDetails['SocietyLedgerHeads']['tds_type'];
                $isTdsApp = $societyLedgerHeadDetails['SocietyLedgerHeads']['is_tds'];
             if($isInBill == 0) {

                    // get records from society_payments
                
                $this->loadModel('SocietyPayment');
                if($tdsType == 1){
                    $condition = array(
                    'SocietyPayment.tds_account_id' => $societyLedgerHeadId,
                    'SocietyPayment.financial_year_id' => $financialYearId ,
                    'SocietyPayment.society_id' => $this->Session->read('Auth.User.id'));
                }
                else{
                    
                    $condition = array(
                    'SocietyPayment.ledger_head_id' => $societyLedgerHeadId,
                    'SocietyPayment.financial_year_id' => $financialYearId ,
                    'SocietyPayment.society_id' => $this->Session->read('Auth.User.id'));
                }
                $societyPayments = $this->SocietyPayment->find('all', array('conditions' => $condition));


                if (!empty($societyPayments)) {

                    foreach ($societyPayments as $paymentDetails) {

                        $paymentData = $paymentDetails['SocietyPayment'];

                        $particular = wordwrap($paymentData['particulars'], 60, "<br />");

                        $paymentDate = date('d/m/Y', strtotime($paymentData['payment_date']));

                        $formattedDate = date('Y-m-d', strtotime($paymentData['payment_date']));
                        $txn = ($tdsType == 1) ? 'credit' : 'debit';
                        $amount = ($tdsType == 1) ? $paymentData['tax_amount'] :($isTdsApp == 1 ? $paymentData['amount'] : $paymentData['total_amount']); 
                        $societyPaymentData[] = array('payment_date' => $paymentDate, 'formatted_date' => $formattedDate, 
                        'amount' => $amount, 'txn_type' => $txn,'particulars'=>$particular,'payment_id' => $paymentData['id'],'notes'=>$paymentData['notes'],'flat_no'=>'');

                    }

                }

            } else {

                    // get records from member bills

                $this->loadModel('MemberBillGenerate');

                    //$memberBills = $this->MemberBillGenerate->find('all', array('conditions' => array('MemberBillGenerate.ledger_head_id' => $societyLedgerHeadId, 'MemberBillGenerate.society_id' => $this->Session->read('Auth.User.id'))));

                $conditions = array('MemberBillGenerate.ledger_head_id' => $societyLedgerHeadId, 'MemberBillGenerate.society_id' => $this->Session->read('Auth.User.id'));                    

                $memberBills = (new AccountReportsController)->getMemberBillGenerateData($conditions);


                if (!empty($memberBills)) {

                    foreach ($memberBills as $paymentDetails) {
                       

                        $paymentData = $paymentDetails['MemberBillGenerate'];


                        $memberInfoData = $paymentDetails['Member'];

                        $flatNo = $memberInfoData['flat_no'];                            

                        $paymentDate = date('d/m/Y', strtotime($paymentData['bill_generated_date']));

                        $formattedDate = date('Y-m-d', strtotime($paymentData['bill_generated_date']));
                        $monthName = date('F',strtotime($paymentData['bill_generated_date']));
                        $societyPaymentData[] = array('payment_date' => $paymentDate, 'formatted_date' => $formattedDate, 'amount' => $paymentDetails[0]['amount'], 'txn_type' => $txnType,'notes'=>'','flat_no'=> $monthName);

                    }

                }
                //print_r($societyPaymentData);die;
            }





		//Other income

            $this->loadModel('SocietyOtherIncome');
                 if($tdsType == 1){
                    $condition =  array('SocietyOtherIncome.tds_bank_id'=>$societyLedgerHeadId ,
                    'SocietyOtherIncome.financial_year_id' => $financialYearId);
                }
                else{
                    
                    $condition =  array('SocietyOtherIncome.ledger_head_id'=>$societyLedgerHeadId ,
                    'SocietyOtherIncome.financial_year_id' => $financialYearId);
                }
            $SocietyOtherIncomeDetails = $this->SocietyOtherIncome->find('all', array('conditions' =>  $condition,            
            'order' => array('SocietyOtherIncome.payment_date' => 'asc')));

            if (!empty($SocietyOtherIncomeDetails)) {

                foreach ($SocietyOtherIncomeDetails as $paymentDetails) {

                    $paymentData = $paymentDetails['SocietyOtherIncome'];

                    $particular = wordwrap($paymentData['title'], 60, "<br />");

                    $paymentDate = date('d/m/Y', strtotime($paymentData['payment_date']));

                    $formattedDate = date('Y-m-d', strtotime($paymentData['payment_date']));
                    $txn = ($tdsType == 1) ? 'debit' : 'credit';
                    $amount = ($tdsType == 1) ? $paymentData['tds_amount'] :$paymentData['amount_paid']; 
                    $societyPaymentData[] = array('payment_date' => $paymentDate, 'formatted_date' => $formattedDate, 'amount' => $amount, 'txn_type' => $txn,'particulars'=>$particular,'notes'=>'','flat_no'=> '');

                }

            }



            $journalVoucherLedgerPaymentData = $this->SocietyBill->getJournalVoucherCreaditDebitDataByLedgerHeadId($societyLedgerHeadId);



            if (!empty($journalVoucherLedgerPaymentData)) {

                foreach ($journalVoucherLedgerPaymentData as $jvData) {

                    $txnJvType = '';

                    $jvAmt = 0;

                    $JVPARTICULAR = "";

                    if ($jvData['JournalVoucher']['jv_type'] == 'Debit') {

                        $txnJvType = "debit";

                        $jvAmt = $jvData['JournalVoucher']['jv_amount_debited'];

                        $JVPARTICULAR = "JV Debited. V.No ".$jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'];

                    }



                    if ($jvData['JournalVoucher']['jv_type'] == 'Credit') {

                        $txnJvType = "credit";

                        $jvAmt = $jvData['JournalVoucher']['jv_amount_credited'];

                        $JVPARTICULAR = "JV Credited. V.No ".$jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'];

                    }



                    $paymentDate = isset($jvData['JournalVoucher']['voucher_date']) ? date('d/m/Y', strtotime($jvData['JournalVoucher']['voucher_date'])) : "";

                    $formattedDate = isset($jvData['JournalVoucher']['voucher_date']) ? date('Y-m-d', strtotime($jvData['JournalVoucher']['voucher_date'])) : "";

                    $societyPaymentData[] = array('particulars' => $JVPARTICULAR,'particularNote' => $JVPARTICULAR, 'payment_date' => $paymentDate, 'formatted_date' => $formattedDate, 'amount' => $jvAmt, 'txn_type' => $txnJvType,'notes'=>'','flat_no'=> '');

                }

            }

            usort($societyPaymentData,array($this,"sortMultiDimentionalArray"));

            $societyLedgerHeadDetails['ledgerPaymentData'] = $societyPaymentData;
            
            $societyLedgerHeadDetails['txn_type'] = $txnType;
            
            $societyLedgerHeadDetails['openingData'] = $openingData;
        }

    }

    return new CakeResponse(array('body' => json_encode($societyLedgerHeadDetails), 'status' => 200));

}


public function add_society_ledger_heads() {

        $this->layout = false; //Update add Society/Login 

        $response['error'] = 1;

        $response['error_message'] = 'Opps! Somthing went wrong.';

        if ($this->RequestHandler->isAjax()) {

            $this->autoRender = false;

            $this->loadModel('SocietyLedgerHeads');

            $societyLoginID = $this->Auth->user('id');

            $societyLedgerId = isset($this->request->data['SocietyLedgerHeads']['id']) ? $this->request->data['SocietyLedgerHeads']['id'] : '';

            $societyLedgerTitle = isset($this->request->data['SocietyLedgerHeads']['title']) ? $this->request->data['SocietyLedgerHeads']['title'] : '';

            $societyLedgerSubCategory = isset($this->request->data['SocietyLedgerHeads']['society_head_sub_category_id']) ? $this->request->data['SocietyLedgerHeads']['society_head_sub_category_id'] : '';

            $societyLedgerAccountHeadId = isset($this->request->data['SocietyLedgerHeads']['account_head_id']) ? $this->request->data['SocietyLedgerHeads']['account_head_id'] : '';

            $societyLedgerAccountCategoryId = isset($this->request->data['SocietyLedgerHeads']['account_category_id']) ? $this->request->data['SocietyLedgerHeads']['account_category_id'] : '';

            $societyLedgerShortCode = isset($this->request->data['SocietyLedgerHeads']['short_code']) ? $this->request->data['SocietyLedgerHeads']['short_code'] : '';

            if ($this->SocietyLedgerHeads->updateAll(array('SocietyLedgerHeads.title' =>"'$societyLedgerTitle'",'SocietyLedgerHeads.society_head_sub_category_id' =>"'$societyLedgerSubCategory'", 'SocietyLedgerHeads.account_head_id' => "'$societyLedgerAccountHeadId'", 'SocietyLedgerHeads.account_category_id' => "'$societyLedgerAccountCategoryId'", 'SocietyLedgerHeads.short_code' => "'$societyLedgerShortCode'"),array('SocietyLedgerHeads.id'=>$societyLedgerId,'SocietyLedgerHeads.society_id'=>$societyLoginID))) {

                $response['error'] = 0;

                $update = $this->SocietyBill->addUpdateLedgerOpBalance($this->request->data['SocietyLedgerHeads']);

                $response['error_message'] = 'Society ledger head data updated successfully.';

            }

        }

        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));

    }





    public function getSocietyMemberDetails() {



        $societyMemberDetails = array('Member' => array('status' => 1));

        $societyMemberDetailsArray = array();

        if ($this->RequestHandler->isAjax()) {

            $fincancialYearDates = $this->Session->read('Auth.financial_years_arr');
            $financialyearId = $this->Util->getFinancialYear();

            
            $this->layout = false;

            $this->loadModel('Member');

            $this->loadModel('MemberBillSummary');

            $this->loadModel('MemberIdentification');

            $societyId = $this->Session->read('Auth.User.id');

            $societyMemberId = isset($this->request->data['societyMemberId']) ? $this->request->data['societyMemberId'] : 0;

            $societyMemberDetails = $this->Member->find('first', array('conditions' => array('Member.id' => $societyMemberId, 
            'Member.society_id' => $societyId, 'Member.status' => Configure::read('Active'))));
;
            $this->loadModel('ChequeReturnData');

            $returnedCheckDetails = [];
            $member_transfer = isset($societyMemberDetails['Member']['member_transfer'])?$societyMemberDetails['Member']['member_transfer']:0;
            // Optional override: the "Old Member Bill" tab asks for a previous
            // owner's ledger by passing that owner's transfer number for the same
            // member id. Absent -> the current occupant, unchanged.
            if (isset($this->request->data['oldMember']) && $this->request->data['oldMember'] == '1' && $member_transfer >= 1) {
                $member_transfer = $member_transfer - 1;
                // Show the previous owner's name, not the current occupant's.
                $this->loadModel('MemberIdentification');
                $__oldIdent = $this->MemberIdentification->find('first', array('conditions' => array('MemberIdentification.member_id' => $societyMemberId, 'MemberIdentification.society_id' => $this->Session->read('Auth.User.id')), 'order' => array('MemberIdentification.flat_no' => 'asc')));
                if (!empty($__oldIdent['MemberIdentification']['third_member'])) {
                    $societyMemberDetails['Member']['member_name'] = $__oldIdent['MemberIdentification']['third_member'];
                }
            }
            
            $chequeRetCond = array('member_id'=> $societyMemberId,'society_id'=>$societyId,'financial_year_id'=>$financialyearId);    
            $condition = array('MemberBillSummary.society_id' => $this->Session->read('Auth.User.id'), 
            'MemberBillSummary.member_id' => $societyMemberId, 'MemberBillSummary.member_transfer' => $member_transfer,
            'MemberBillSummary.financial_year_id' => $financialyearId);

            $conditionPayment = array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'), 
            'MemberPayment.member_id' => $societyMemberId, 'MemberPayment.member_transfer' => $member_transfer,'MemberPayment.financial_year_id'=>$financialyearId);
            


            $returnedCheckDetails = $this->ChequeReturnData->find('all',array('conditions' =>$chequeRetCond ));

            $memberTransfered = (new SocietyBillsController)->checkMemberTransferred($societyMemberId); 

            $societyMemberBillPaymentData = array();

            $memberBillSummary = $this->Member->MemberBillSummary->find('all', array('conditions' => $condition,'order' => array('MemberBillSummary.id' => 'asc')));

            $noPaymentIdsArr = [];

            if (isset($memberBillSummary) && count($memberBillSummary) > 0) {        
                $summaryCounter = 0;

                $settlementArray = array();

                $paymentIdsArr = array();

                $noPaymentIdsArr = array();

                foreach ($memberBillSummary as $billData) {

                    if (isset($billData['MemberBillSummary'])) {

                        $this->loadModel('MemberBillSettlement');
                        $condition = array('MemberBillSettlement.bill_summary_id' => $billData['MemberBillSummary']['id'], 'MemberBillSettlement.member_id' => $societyMemberId);
                        $memberBillSettlement = $this->MemberBillSettlement->find('all', array('conditions' => $condition));

                        foreach($memberBillSettlement as $settlementData) {
                            $settlementArray[] = $settlementData['MemberBillSettlement'];
                            $paymentIdsArr[] = $settlementData['MemberBillSettlement']['payment_id'];
                        }

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary'] = $billData['MemberBillSummary'];
                        
                        //Change date format
                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date'] = date('d/m/Y',strtotime($societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date']));
                        $monthName = date("F", mktime(0, 0, 0, $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month'], 10));
                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthName'] = $this->SocietyBill->monthWordFormatByBillingFrequency($societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month']);
                        $suplementary = ($billData['MemberBillSummary']['bill_type'] == "sup")?" Suplementary":"";

                        $societyMemberDetailsArray[] = array(
                            'date'=>$billData['MemberBillSummary']['bill_generated_date'],
                            'formattedDate'=>$societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date'],
                            'particular'=>'To Bill No '.$billData['MemberBillSummary']['bill_no'].' For '.$monthName.$suplementary,
                            'debit'=>$billData['MemberBillSummary']['monthly_bill_amount'],
                            'credit'=>'0.00',
                            'bill_id'=>$billData['MemberBillSummary']['id'],
                            'month'=>$billData['MemberBillSummary']['month'],
                            'flag'=>'bill'
                        );



                        $paymentMadeBillNo = isset($billData['MemberBillSummary']['bill_no']) ? $billData['MemberBillSummary']['bill_no'] : '';                        
                        $membersChqueNo = [];

                        if ($this->Member->MemberPayment->hasAny($conditionPayment)) {

                            $memberPaymentDetails = $this->Member->MemberPayment->find('all', array('conditions' => $conditionPayment));

                            if (isset($memberPaymentDetails) && count($memberPaymentDetails) > 0) {

                                $paymentCounter = 0;

                                foreach ($memberPaymentDetails as $paymentData) {    
                                    $billType = $paymentData['MemberPayment']['bill_type'] == 'sup' ? ' - supplementary' :  '';
                                    if(in_array($paymentData['MemberPayment']['id'],$noPaymentIdsArr)) continue;
                                    $formattedDate = date('d/m/Y',strtotime($paymentData['MemberPayment']['payment_date']));
                                    $chq='';
                                    if ($paymentData['MemberPayment']['cheque_reference_number'] != ''){
                                        $chq .= ' and Cheque Reference Number '. $paymentData['MemberPayment']['cheque_reference_number'];
                                    }

                                    array_push($noPaymentIdsArr,$paymentData['MemberPayment']['id']);

                                    $societyMemberDetailsArray[] = array(
                                        'date'=>$paymentData['MemberPayment']['payment_date'],
                                        'formattedDate'=>$formattedDate,
                                        'particular'=>'By Receipt V.No.'. $paymentData['MemberPayment']['receipt_id'].$chq.$billType,
                                        'debit'=>'0.00',
                                        'credit'=>$paymentData['MemberPayment']['amount_paid'],
                                        'flag'=>'receipt',
                                        'payment_id' => $paymentData['MemberPayment']['id']

                                    );



                                    if (!empty($returnedCheckDetails) && $paymentData['MemberPayment']['cheque_reference_number'] != ''){



                                       foreach($returnedCheckDetails as $cheuqData){

                                           $billType = $cheuqData['ChequeReturnData']['bill_type'] == 'sup' ? 'supplementary' :  '';

                                           if($cheuqData['ChequeReturnData']['cheque_no'] == $paymentData['MemberPayment']['cheque_reference_number'] && $cheuqData['ChequeReturnData']['payment_id'] == $paymentData['MemberPayment']['id']){

                                            $societyMemberDetailsArray[] = array(

                                                'date'=> date('d-m-Y',strtotime($cheuqData['ChequeReturnData']['cheque_return_date'])),
                                                'formattedDate'=>date('d/m/Y',strtotime($cheuqData['ChequeReturnData']['cheque_return_date'])),
                                                'particular'=> 'Ret'.' By Receipt V.No.'. $paymentData['MemberPayment']['receipt_id'].' Cheque Reference Number '. $cheuqData['ChequeReturnData']['cheque_no'].$billType ,// 'By Receipt V.No.'. $paymentData['MemberPayment']['receipt_id'].$chq,
                                                'debit'=> $cheuqData['ChequeReturnData']['cheque_amount'],
                                                'credit'=> 0.00,
                                                'flag'=>''
                                            );
                                        }

                                    }

                                }                                 



                                $societyMemberBillPaymentData[$summaryCounter]['MemberPayment'][$paymentCounter] = $paymentData['MemberPayment'];

                                $paymentCounter++;

                            }

                        }

                    }

                }



                $summaryCounter++;

            }

        }







        if (isset($societyMemberDetails) && count($societyMemberDetails) > 0) {



            $societyMemberNextId = $this->Member->find('first', array('fields' => array('Member.id'), 'conditions' => array('Member.id >' => $societyMemberDetails['Member']['id'], 'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'asc')));



            $societyMemberPreviousId = $this->Member->find('first', array('fields' => array('Member.id'),'conditions' => array('Member.id <' => $societyMemberDetails['Member']['id'], 'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'desc')));



            $societyMemberDetails['Member']['next'] = 0;



            $societyMemberDetails['Member']['previous'] = 0;



            $societyMemberDetails['Member']['previous_id'] = (isset($societyMemberPreviousId['Member']['id']) && $societyMemberPreviousId['Member']['id'] != '') ? $societyMemberPreviousId['Member']['id'] : 0;



            $societyMemberDetails['Member']['next_id'] = (isset($societyMemberNextId['Member']['id']) && $societyMemberNextId['Member']['id'] != '') ? $societyMemberNextId['Member']['id'] : 0;



            if (empty($societyMemberPreviousId)) {
                $societyMemberDetails['Member']['previous'] = 1;
            }



            if (empty($societyMemberNextId)) {



                $societyMemberDetails['Member']['next'] = 1;



            }



            $societyMemberDetails['Member']['status'] = 1;

//echo "MemberTR".$societyMemberDetails['Member']['member_transfer'];

            $openingBalance = $this->SocietyBill->getMemberClosingBalanceData($societyMemberDetails['Member']['id'],$member_transfer);       
       
            if(isset($openingBalance['reg'])){
                $societyMemberDetails['Member']['op_principal'] = !empty($openingBalance['reg']['op_principal']) ?  $openingBalance['reg']['op_principal'] : 0.00;
                $societyMemberDetails['Member']['op_interest'] = !empty($openingBalance['reg']['op_interest']) ?  $openingBalance['reg']['op_interest'] : 0.00;
                $societyMemberDetails['Member']['op_tax'] = !empty($openingBalance['reg']['op_tax']) ?  $openingBalance['reg']['op_tax'] : 0.00;
            }

            if(isset($openingBalance['sup'])){
                $societyMemberDetails['Member']['supplementary_principal'] = !empty($openingBalance['sup']['op_principal']) ?  $openingBalance['sup']['op_principal'] : 0.00;
                $societyMemberDetails['Member']['supplementary_interest'] = !empty($openingBalance['sup']['op_interest']) ?  $openingBalance['sup']['op_interest'] : 0.00;
                $societyMemberDetails['Member']['supplementary_tax'] = !empty($openingBalance['sup']['op_tax']) ?  $openingBalance['sup']['op_tax'] : 0.00;
            }
        }


            //Member payment detail that have ids matching with settlement and removing ids if taken in the above MemberPayment query
        if(isset($paymentIdsArr) && count($paymentIdsArr)) {

            $paymentIdsArr = array_unique($paymentIdsArr);

            $filteredIdsArr = array_diff($paymentIdsArr, $noPaymentIdsArr);

            $conditionMembPayment = array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'), 'MemberPayment.member_id' => $societyMemberId, 
            'MemberPayment.id' => $filteredIdsArr, 'MemberPayment.member_transfer' => $member_transfer,'MemberPayment.financial_year_id' => $financialyearId);                 


            
            
            if ($this->Member->MemberPayment->hasAny($conditionMembPayment)) {

                    $memberPaymentArr = $this->Member->MemberPayment->find('all', array('conditions' => $conditionMembPayment));//print_r($memberPaymentArr);

                    if (isset($memberPaymentArr) && count($memberPaymentArr) > 0) {

                        $paymentCounter = 0;

                        foreach ($memberPaymentArr as $paymentData) { 

                            $billType = ($paymentData['MemberPayment']['bill_type'] == 'sup') ? 'supplementary' : '';

                            $formattedDate = date('d/m/Y',strtotime($paymentData['MemberPayment']['payment_date']));

                            $chq='';

                            if ($paymentData['MemberPayment']['cheque_reference_number'] != ''){

                                $chq .= ' and Cheque Reference Number '. $paymentData['MemberPayment']['cheque_reference_number'];

                            }



                            $societyMemberDetailsArray[] = array('date'=>$paymentData['MemberPayment']['payment_date'],

                                'formattedDate'=>$formattedDate,

                                'particular'=>'By Receipt V.No.'. $paymentData['MemberPayment']['receipt_id'].$chq.$billType,

                                'debit'=>'0.00',

                                'credit'=>$paymentData['MemberPayment']['amount_paid'],

                                'flag'=>'receipt',

                                'payment_id' => $paymentData['MemberPayment']['id']

                            );

                            

                            if (!empty($returnedCheckDetails) && $paymentData['MemberPayment']['cheque_reference_number'] != ''){



                               foreach($returnedCheckDetails as $cheuqData){

                                $billType = ($paymentData['MemberPayment']['bill_type'] == 'sup') ? 'supplementary' : '';

                                if($cheuqData['ChequeReturnData']['cheque_no'] == $paymentData['MemberPayment']['cheque_reference_number'] && $cheuqData['ChequeReturnData']['payment_id'] == $paymentData['MemberPayment']['id']){

                                        // $chq .= ' and Ret Cheque Reference Number '. $cheuqData['ChequeReturnData']['chque_no'];

                                    $societyMemberDetailsArray[] = array(

                                        'date'=> date('d/m/Y',strtotime($cheuqData['ChequeReturnData']['cheque_return_date'])),

                                        'formattedDate'=>date('d/m/Y',strtotime($cheuqData['ChequeReturnData']['cheque_return_date'])),

                                        'particular'=> 'Ret'.' By Receipt V.No.'. $paymentData['MemberPayment']['receipt_id'].' Cheque Reference Number '. $cheuqData['ChequeReturnData']['cheque_no'].$billType ,// 'By Receipt V.No.'. $paymentData['MemberPayment']['receipt_id'].$chq,

                                        'debit'=> $cheuqData['ChequeReturnData']['cheque_amount'],

                                        'credit'=> 0.00,

                                        'flag'=>''

                                    );

                                }

                            }

                        }

                        $societyMemberBillPaymentData[$summaryCounter]['MemberPayment'][$paymentCounter] = $paymentData['MemberPayment'];

                        $paymentCounter++;

                    }

                }

            }

        }



        $journalVoucherMemberPaymentData = $this->SocietyBill->getJournalVoucherCreaditDebitDataByMemberId($societyMemberId,$member_transfer);


        if (!empty($journalVoucherMemberPaymentData)){

            if(empty($paymentCounter) || $paymentCounter == '') {

                    $paymentCounter = 0; //commented by shobha

                }



                //$paymentCounter = 0; //commented by shobha

                foreach ($journalVoucherMemberPaymentData as $jvData){
                    // Only this member-generation's JV rows belong in the current
                    // occupant's ledger. An old owner (member_transfer < current) shares
                    // the same member id, so without this filter the old owner's JV
                    // entries leak in and the balance nets to zero.
                    if (isset($jvData['JournalVoucher']['member_transfer']) && $jvData['JournalVoucher']['member_transfer'] != $member_transfer) { continue; }

                    if ($jvData['JournalVoucher']['jv_credit_member_head_id'] != '' && $jvData['JournalVoucher']['jv_credit_member_head_id'] == $societyMemberId) {

                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['amount_paid'] = isset($jvData['JournalVoucher']['jv_amount_credited']) ? $jvData['JournalVoucher']['jv_amount_credited'] : 0.00;

                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['payment_date'] = isset($jvData['JournalVoucher']['voucher_date']) ? $this->Util->mysqlToDate($jvData['JournalVoucher']['voucher_date'], '/') : "";

                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['cheque_reference_number'] = "JV Credited. V.No ".$jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'];

                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['flag'] = "jv";

                      $paymentCounter++;

                      $formattedDate = date('d/m/Y',strtotime($jvData['JournalVoucher']['voucher_date']));

                      $societyMemberDetailsArray[] = array('date'=>$jvData['JournalVoucher']['voucher_date'],

                        'formattedDate'=>$formattedDate,

                        'particular'=>"JV Credited. V.No ".$jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'],

                        'debit'=>'0.00',

                        'credit'=>$jvData['JournalVoucher']['jv_amount_credited'],

                        'flag'=>'jv'    

                    );

                  }



                  if ($jvData['JournalVoucher']['jv_debit_member_head_id'] != '' && $jvData['JournalVoucher']['jv_debit_member_head_id'] == $societyMemberId) {

                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['id'] = "";

                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_no'] = "";

                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_principal_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_bill_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date'] = isset($jvData['JournalVoucher']['voucher_date']) ? $this->Util->mysqlToDate($jvData['JournalVoucher']['voucher_date'], '/') : "";

                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthName'] = isset($jvData['JournalVoucher']['voucher_date']) ? "JV Debited. V.No " . $jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'] : "";

                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month'] = isset($jvData['JournalVoucher']['voucher_date']) ? date('m', strtotime($jvData['JournalVoucher']['voucher_date'])) : "";

                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_principal_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['flag'] = "jv";

                    $summaryCounter++;



                    $formattedDate = date('d/m/Y',strtotime($jvData['JournalVoucher']['voucher_date']));

                    $societyMemberDetailsArray[] = array('date'=>$jvData['JournalVoucher']['voucher_date'],

                        'formattedDate'=>$formattedDate,

                        'particular'=>"JV Credited. V.No ".$jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'],

                        'debit'=>$jvData['JournalVoucher']['jv_amount_debited'],

                        'credit'=>'0.00',

                        'flag'=>'jv'

                    );

                }

            }

        }

    }


    usort($societyMemberDetailsArray,array($this,"sortMultiDimentionalPaymentsArray"));
    $societyMemberDetails['memberBillPayment'] = $societyMemberDetailsArray;


    $op = $this->getFirstDayFinancialYear();
    $societyMemberDetails['openingdate'] =  $op;

    $societyMemberDetails['tranasfer_status'] = $memberTransfered;     


    //echo "<pre>";
    //print_r($societyMemberDetails); die;
    return new CakeResponse(array('body' => json_encode($societyMemberDetails), 'status' => 200));



}







public function getSocietyMemberDetails_bkOM() {

    error_reporting(0);

    ini_set('display_errors',0);



    $societyMemberDetails = array('Member' => array('status' => 1));



    $societyMemberDetailsArray = array();



    if ($this->RequestHandler->isAjax()) {

        $this->layout = false;

        $this->loadModel('Member');

        $this->loadModel('MemberBillSummary');

            //$this->Member->recursive = -1;

        $societyMemberId = isset($this->request->data['societyMemberId']) ? $this->request->data['societyMemberId'] : 0;

        $societyMemberDetails = $this->Member->find('first', array('conditions' => array('Member.id' => $societyMemberId, 'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active'))));

        $condition = array('MemberBillSummary.society_id' => $this->Session->read('Auth.User.id'), 'MemberBillSummary.member_id' => $societyMemberId);

        $societyMemberBillPaymentData = array();

        $memberBillSummary = $this->Member->MemberBillSummary->find('all', array('conditions' => $condition));



        if (isset($memberBillSummary) && count($memberBillSummary) > 0) {

            $summaryCounter = 0;

            $settlementArray = array();

            $paymentIdsArr = array();

            $noPaymentIdsArr = array();

            foreach ($memberBillSummary as $billData) {

                if (isset($billData['MemberBillSummary'])) {



                    $this->loadModel('MemberBillSettlement');

                    $condition = array('MemberBillSettlement.bill_summary_id' => $billData['MemberBillSummary']['id'], 'MemberBillSettlement.member_id' => $societyMemberId);

                    $memberBillSettlement = $this->MemberBillSettlement->find('all', array('conditions' => $condition));

                    foreach($memberBillSettlement as $settlementData) {

                        $settlementArray[] = $settlementData['MemberBillSettlement'];

                        $paymentIdsArr[] = $settlementData['MemberBillSettlement']['payment_id'];

                    }





                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary'] = $billData['MemberBillSummary'];



                        //Change date format

                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date'] = date('d/m/Y',strtotime($societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date']));

                    $monthName = date("F", mktime(0, 0, 0, $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month'], 10));

                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthName'] = $this->SocietyBill->monthWordFormatByBillingFrequency($societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month']);



                    $suplementary = ($billData['MemberBillSummary']['bill_type'] == "sup")?" Suplementary":"";



                    $societyMemberDetailsArray[] = array('date'=>$billData['MemberBillSummary']['bill_generated_date'],

                        'formattedDate'=>$societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date'],

                        'particular'=>'To Bill No '.$billData['MemberBillSummary']['bill_no'].' For '.$monthName.$suplementary,

                        'debit'=>$billData['MemberBillSummary']['monthly_bill_amount'],

                        'credit'=>'0.00',

                        'bill_id'=>$billData['MemberBillSummary']['id'],

                        'month'=>$billData['MemberBillSummary']['month'],

                        'flag'=>'bill'

                    );





                    $paymentMadeBillNo = isset($billData['MemberBillSummary']['bill_no']) ? $billData['MemberBillSummary']['bill_no'] : '';

                    $conditionPayment = array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'), 'MemberPayment.member_id' => $societyMemberId, 'MemberPayment.bill_generated_id' => $paymentMadeBillNo);

                    if ($this->Member->MemberPayment->hasAny($conditionPayment)) {

                        $memberPaymentDetails = $this->Member->MemberPayment->find('all', array('conditions' => $conditionPayment));

                        if (isset($memberPaymentDetails) && count($memberPaymentDetails) > 0) {

                            $paymentCounter = 0;

                            foreach ($memberPaymentDetails as $paymentData) {                                    



                                $formattedDate = date('d/m/Y',strtotime($paymentData['MemberPayment']['payment_date']));

                                $chq='';

                                if ($paymentData['MemberPayment']['cheque_reference_number'] != ''){

                                    $chq .= ' and Cheque Reference Number '. $paymentData['MemberPayment']['cheque_reference_number'];

                                }

                                $noPaymentIdsArr[] = $paymentData['MemberPayment']['id'];



                                $societyMemberDetailsArray[] = array('date'=>$paymentData['MemberPayment']['payment_date'],

                                    'formattedDate'=>$formattedDate,

                                    'particular'=>'By Receipt V.No.'. $paymentData['MemberPayment']['receipt_id'].$chq,

                                    'debit'=>'0.00',

                                    'credit'=>$paymentData['MemberPayment']['amount_paid'],

                                    'flag'=>'receipt',

                                    'payment_id' => $paymentData['MemberPayment']['id']

                                );





                                $societyMemberBillPaymentData[$summaryCounter]['MemberPayment'][$paymentCounter] = $paymentData['MemberPayment'];

                                $paymentCounter++;

                            }

                        }

                    }

                }

                $summaryCounter++;

            }

        }



        if (isset($societyMemberDetails) && count($societyMemberDetails) > 0) {

            $societyMemberNextId = $this->Member->find('first', array('fields' => array('Member.id'), 'conditions' => array('Member.id >' => $societyMemberDetails['Member']['id'], 'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'asc')));

            $societyMemberPreviousId = $this->Member->find('first', array('fields' => array('Member.id'),'conditions' => array('Member.id <' => $societyMemberDetails['Member']['id'], 'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'desc')));

            $societyMemberDetails['Member']['next'] = 0;

            $societyMemberDetails['Member']['previous'] = 0;

            $societyMemberDetails['Member']['previous_id'] = (isset($societyMemberPreviousId['Member']['id']) && $societyMemberPreviousId['Member']['id'] != '') ? $societyMemberPreviousId['Member']['id'] : 0;

            $societyMemberDetails['Member']['next_id'] = (isset($societyMemberNextId['Member']['id']) && $societyMemberNextId['Member']['id'] != '') ? $societyMemberNextId['Member']['id'] : 0;

            if (empty($societyMemberPreviousId)) {

                $societyMemberDetails['Member']['previous'] = 1;

            }

            if (empty($societyMemberNextId)) {

                $societyMemberDetails['Member']['next'] = 1;

            }

            $societyMemberDetails['Member']['status'] = 1;

        }



            //Member payment detail that have ids matching with settlement and removing ids if taken in the above MemberPayment query

            //print_r($paymentIdsArr);print_r($noPaymentIdsArr);

        if(isset($paymentIdsArr) && count($paymentIdsArr)) {

            $paymentIdsArr = array_unique($paymentIdsArr);

            $filteredIdsArr = array_diff($paymentIdsArr, $noPaymentIdsArr);

            $conditionMembPayment = array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'), 'MemberPayment.member_id' => $societyMemberId, 'MemberPayment.id' => $filteredIdsArr); 

            if ($this->Member->MemberPayment->hasAny($conditionMembPayment)) {

                    $memberPaymentArr = $this->Member->MemberPayment->find('all', array('conditions' => $conditionMembPayment));//print_r($memberPaymentArr);

                    if (isset($memberPaymentArr) && count($memberPaymentArr) > 0) {

                        $paymentCounter = 0;

                        foreach ($memberPaymentArr as $paymentData) {     



                            $formattedDate = date('d/m/Y',strtotime($paymentData['MemberPayment']['payment_date']));

                            $chq='';

                            if ($paymentData['MemberPayment']['cheque_reference_number'] != ''){

                                $chq .= ' and Cheque Reference Number '. $paymentData['MemberPayment']['cheque_reference_number'];

                            }



                            $societyMemberDetailsArray[] = array('date'=>$paymentData['MemberPayment']['payment_date'],

                                'formattedDate'=>$formattedDate,

                                'particular'=>'By Receipt V.No.'. $paymentData['MemberPayment']['receipt_id'].$chq,

                                'debit'=>'0.00',

                                'credit'=>$paymentData['MemberPayment']['amount_paid'],

                                'flag'=>'receipt',

                                'payment_id' => $paymentData['MemberPayment']['id']

                            );





                            $societyMemberBillPaymentData[$summaryCounter]['MemberPayment'][$paymentCounter] = $paymentData['MemberPayment'];

                            $paymentCounter++;

                        }

                    }

                }

                

            }

            

            

            $journalVoucherMemberPaymentData = $this->SocietyBill->getJournalVoucherCreaditDebitDataByMemberId($societyMemberId);

            

            

            if (!empty($journalVoucherMemberPaymentData)){

               if(empty($paymentCounter) || $paymentCounter == '') {

                                    $paymentCounter = 0; //commented by shobha

                                }



                //$paymentCounter = 0; //commented by shobha

                                foreach ($journalVoucherMemberPaymentData as $jvData){

                                    if ($jvData['JournalVoucher']['jv_credit_member_head_id'] != '' && $jvData['JournalVoucher']['jv_credit_member_head_id'] == $societyMemberId) {

                                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['amount_paid'] = isset($jvData['JournalVoucher']['jv_amount_credited']) ? $jvData['JournalVoucher']['jv_amount_credited'] : 0.00;

                                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['payment_date'] = isset($jvData['JournalVoucher']['voucher_date']) ? $this->Util->mysqlToDate($jvData['JournalVoucher']['voucher_date'], '/') : "";

                                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['cheque_reference_number'] = "JV Credited. V.No ".$jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'];

                                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['flag'] = "jv";

                                      $paymentCounter++;



                                      $formattedDate = date('d/m/Y',strtotime($jvData['JournalVoucher']['voucher_date']));



                                      $societyMemberDetailsArray[] = array('date'=>$jvData['JournalVoucher']['voucher_date'],

                                        'formattedDate'=>$formattedDate,

                                        'particular'=>"JV Credited. V.No ".$jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'],

                                        'debit'=>'0.00',

                                        'credit'=>$jvData['JournalVoucher']['jv_amount_credited'],

                                        'flag'=>'jv'    

                                    );

                                  }



                                  if ($jvData['JournalVoucher']['jv_debit_member_head_id'] != '' && $jvData['JournalVoucher']['jv_debit_member_head_id'] == $societyMemberId) {

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['id'] = "";

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_no'] = "";

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_principal_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_bill_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date'] = isset($jvData['JournalVoucher']['voucher_date']) ? $this->Util->mysqlToDate($jvData['JournalVoucher']['voucher_date'], '/') : "";

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthName'] = isset($jvData['JournalVoucher']['voucher_date']) ? "JV Debited. V.No " . $jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'] : "";

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month'] = isset($jvData['JournalVoucher']['voucher_date']) ? date('m', strtotime($jvData['JournalVoucher']['voucher_date'])) : "";

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_principal_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['flag'] = "jv";

                                    $summaryCounter++;



                                    $formattedDate = date('d/m/Y',strtotime($jvData['JournalVoucher']['voucher_date']));



                                    $societyMemberDetailsArray[] = array('date'=>$jvData['JournalVoucher']['voucher_date'],

                                        'formattedDate'=>$formattedDate,

                                        'particular'=>"JV Credited. V.No ".$jvData['JournalVoucher']['voucher_no']." ".$jvData['JournalVoucher']['note'],

                                        'debit'=>$jvData['JournalVoucher']['jv_amount_debited'],

                                        'credit'=>'0.00',

                                        'flag'=>'jv'

                                    );

                                }

                            }

                        }



            //$societyMemberDetails['memberBillPayment'] = $societyMemberBillPaymentData;

                    }



                    usort($societyMemberDetailsArray,array($this,"sortMultiDimentionalPaymentsArray"));

                    $societyMemberDetails['memberBillPayment'] = $societyMemberDetailsArray;



        /* $db = $this->Session->read('Auth.dbConfig');

        if ($db != 'default'){

            $op = '01/04/2020';

        } else {

            $mon = date('m');



            if ($mon > 4) {

                $y = date('Y');

            } else {

                $y = date('Y', strtotime('-1 year'));

            }

            $op = '01/04/'.$y;

        } */

        $op = $this->getFirstDayFinancialYear();

        $societyMemberDetails['openingdate'] = $op;

        //echo '<pre>';print_r($societyMemberDetails); exit;

        return new CakeResponse(array('body' => json_encode($societyMemberDetails), 'status' => 200));

    }

    

    public function sortMultiDimentionalPaymentsArray($a,$b){

        return (strtotime($a["date"]) <= strtotime($b["date"])) ? -1 : 1;

    }

    

    public function getSocietyMemberDetailsBack() {

        error_reporting(0);

        ini_set('display_errors',0);

        $societyMemberDetails = array('Member' => array('status' => 1));

        if ($this->RequestHandler->isAjax()) {

            $this->layout = false;

            $this->loadModel('Member');

            $this->loadModel('MemberBillSummary');

            //$this->Member->recursive = -1;

            $societyMemberId = isset($this->request->data['societyMemberId']) ? $this->request->data['societyMemberId'] : 0;

            $societyMemberDetails = $this->Member->find('first', array('conditions' => array('Member.id' => $societyMemberId, 'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active'))));

            $condition = array('MemberBillSummary.society_id' => $this->Session->read('Auth.User.id'), 'MemberBillSummary.member_id' => $societyMemberId);

            $societyMemberBillPaymentData = array();

            $memberBillSummary = $this->Member->MemberBillSummary->find('all', array('conditions' => $condition));



            if (isset($memberBillSummary) && count($memberBillSummary) > 0) {

                $summaryCounter = 0;

                foreach ($memberBillSummary as $billData) {

                    if (isset($billData['MemberBillSummary'])) {

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary'] = $billData['MemberBillSummary'];

                        

                        //Change date format

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date'] = date('d/m/Y',strtotime($societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date']));

                        $monthName = date("F", mktime(0, 0, 0, $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month'], 10));

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthName'] = $this->SocietyBill->monthWordFormatByBillingFrequency($societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month']);

                        

                        $paymentMadeBillNo = isset($billData['MemberBillSummary']['bill_no']) ? $billData['MemberBillSummary']['bill_no'] : '';

                        $conditionPayment = array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'), 'MemberPayment.member_id' => $societyMemberId, 'MemberPayment.bill_generated_id' => $paymentMadeBillNo);

                        if ($this->Member->MemberPayment->hasAny($conditionPayment)) {

                            $memberPaymentDetails = $this->Member->MemberPayment->find('all', array('conditions' => $conditionPayment));

                            if (isset($memberPaymentDetails) && count($memberPaymentDetails) > 0) {

                                $paymentCounter = 0;

                                foreach ($memberPaymentDetails as $paymentData) {

                                    $paymentData['MemberPayment']['payment_date'] = date('d/m/Y',strtotime($paymentData['MemberPayment']['payment_date']));

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberPayment'][$paymentCounter] = $paymentData['MemberPayment'];

                                    $paymentCounter++;

                                }

                            }

                        }

                    }

                    $summaryCounter++;

                }

            }



            if (isset($societyMemberDetails) && count($societyMemberDetails) > 0) {

                $societyMemberNextId = $this->Member->find('first', array('fields' => array('Member.id'), 'conditions' => array('Member.id >' => $societyMemberDetails['Member']['id'], 'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'asc')));

                $societyMemberPreviousId = $this->Member->find('first', array('fields' => array('Member.id'),'conditions' => array('Member.id <' => $societyMemberDetails['Member']['id'], 'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'desc')));

                $societyMemberDetails['Member']['next'] = 0;

                $societyMemberDetails['Member']['previous'] = 0;

                $societyMemberDetails['Member']['previous_id'] = (isset($societyMemberPreviousId['Member']['id']) && $societyMemberPreviousId['Member']['id'] != '') ? $societyMemberPreviousId['Member']['id'] : 0;

                $societyMemberDetails['Member']['next_id'] = (isset($societyMemberNextId['Member']['id']) && $societyMemberNextId['Member']['id'] != '') ? $societyMemberNextId['Member']['id'] : 0;

                if (empty($societyMemberPreviousId)) {

                    $societyMemberDetails['Member']['previous'] = 1;

                }

                if (empty($societyMemberNextId)) {

                    $societyMemberDetails['Member']['next'] = 1;

                }

                $societyMemberDetails['Member']['status'] = 1;

            }

            

            $journalVoucherMemberPaymentData = $this->SocietyBill->getJournalVoucherCreaditDebitDataByMemberId($societyMemberId);

            

            

            if (!empty($journalVoucherMemberPaymentData)){

               if(empty($paymentCounter) || $paymentCounter == '') {

                                    $paymentCounter = 0; //commented by shobha

                                }



                //$paymentCounter = 0; //commented by shobha

                                foreach ($journalVoucherMemberPaymentData as $jvData){

                                    if ($jvData['JournalVoucher']['jv_credit_member_head_id'] != '' && $jvData['JournalVoucher']['jv_credit_member_head_id'] == $societyMemberId) {

                                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['amount_paid'] = isset($jvData['JournalVoucher']['jv_amount_credited']) ? $jvData['JournalVoucher']['jv_amount_credited'] : 0.00;

                                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['payment_date'] = isset($jvData['JournalVoucher']['voucher_date']) ? $this->Util->mysqlToDate($jvData['JournalVoucher']['voucher_date'], '/') : "";

                                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['cheque_reference_number'] = "JV Credited. V.No ".$jvData['JournalVoucher']['voucher_no'];

                                      $societyMemberBillPaymentData[0]['MemberPayment'][$paymentCounter]['flag'] = "jv";

                                      $paymentCounter++;

                                  }



                                  if ($jvData['JournalVoucher']['jv_debit_member_head_id'] != '' && $jvData['JournalVoucher']['jv_debit_member_head_id'] == $societyMemberId) {

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['id'] = "";

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_no'] = "";

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_principal_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_bill_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date'] = isset($jvData['JournalVoucher']['voucher_date']) ? $this->Util->mysqlToDate($jvData['JournalVoucher']['voucher_date'], '/') : "";

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthName'] = isset($jvData['JournalVoucher']['voucher_date']) ? "JV Debited. V.No " . $jvData['JournalVoucher']['voucher_no'] : "";

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month'] = isset($jvData['JournalVoucher']['voucher_date']) ? date('m', strtotime($jvData['JournalVoucher']['voucher_date'])) : "";

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_principal_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['flag'] = "jv";

                                    $summaryCounter++;

                                }

                            }

                        }



                        $societyMemberDetails['memberBillPayment'] = $societyMemberBillPaymentData;

                    }

        //echo '<pre>';print_r($societyMemberDetails);

                    return new CakeResponse(array('body' => json_encode($societyMemberDetails), 'status' => 200));

                }



                public function getMemberDetailsByFlatNo() {

                    if ($this->RequestHandler->isAjax()) {

                        $this->layout = false;

                        $this->loadModel('Member');

                        $societyMemberDeta = array();

                        if($this->request->data['wing_id'] > 0) {

                            $societyMemberDetails = $this->Member->find('first', array('conditions' => array('Member.flat_no' => $this->request->data['flat_no'], 'Member.building_id' => $this->request->data['building_id'],'Member.wing_id' => $this->request->data['wing_id'],'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active'))));            

                        } else {

                         $societyMemberDetails = $this->Member->find('first', array('conditions' => array('Member.flat_no' => $this->request->data['flat_no'], 'Member.building_id' => $this->request->data['building_id'],'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active'))));             

                     }

                     if(isset($societyMemberDetails['Member']) && count($societyMemberDetails['Member']) > 0){
                        $societyMemberDeta = $societyMemberDetails['Member'];
/*                $memberId = $societyMemberDeta['id'];
                $openingBalance = $this->getMemberClosingBalanceData($memberId);
                
                $societyMemberDeta['op_principal'] = !empty($openingBalance) ?  $openingBalance['op_principal'] : 0;
                $societyMemberDeta['op_interest'] = !empty($openingBalance) ?  $openingBalance['op_interest'] : 0;
                $societyMemberDeta['op_tax'] = !empty($openingBalance) ?  $openingBalance['op_tax'] : 0;*/

            }

        }

        return new CakeResponse(array('body' => json_encode($societyMemberDeta), 'status' => 200));

    }

    

    public function updateSocietyMemberDetails() {

        $this->layout = false; //Update add Society/Login 

        $response['error'] = 1;

        $response['error_message'] = 'Member data could not saved.';

        if ($this->request->is('ajax')) {

            $this->autoRender = false;

            $this->loadModel('Member');

            if (isset($this->request->data['Member']) && count($this->request->data['Member']) > 0) {

                if ($this->Member->save($this->request->data['Member'])) {

                    $response['error'] = 0;

                    $response['error_message'] = 'Details of "'.$this->request->data['Member']['member_name'].'" has been updated successfully.';

                }

            }

        }

        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));

    }



    public function updateSocietyLedgerOrder(){

        $this->layout = false; //Update add Society/Login 

        $response['error'] = 1;

        $response['error_message'] = 'Society tariff order data could not saved.';

        $societyLoginID = $this->Auth->user('id');

        $this->loadModel('SocietyTariffOrder');

        if ($this->request->is('ajax')) {

            if(isset($this->request->data['sort_order'])){

                $societyLedgerIdArray = explode(",",$this->request->data["sort_order"]);

                $serialNo=1;

                foreach ($societyLedgerIdArray as $key => $societyLedgerId) {

                    $postData[$serialNo]['society_id'] = $societyLoginID; 

                    $postData[$serialNo]['ledger_head_id'] = $societyLedgerId;

                    $postData[$serialNo]['tariff_serial'] = $serialNo;

                    $serialNo++;

                }

                if($this->SocietyTariffOrder->hasAny(array('SocietyTariffOrder.society_id'=>$societyLoginID))){

                   $this->SocietyTariffOrder->deleteAll(array('SocietyTariffOrder.society_id'=>$societyLoginID));

               }

               if($this->SocietyTariffOrder->saveAll($postData)){

                $response['error'] = 0;

                $response['error_message'] = 'Society tariff order set successfully.';

            }

        }

    }

    return new CakeResponse(array('body' => json_encode($response), 'status' => 200));

}



public function getMembersOutstandingPaymentsBalance($societyMemberId=null) {
    $financialYearId = $this->Util->getFinancialYear();
    $jsonData = array();

    $this->layout = false; 

    if ($this->RequestHandler->isAjax()) {

        $this->loadModel('Member');

        $conditions = array('MemberBillSummary.society_id' => $this->Session->read('Auth.User.id'),'MemberBillSummary.financial_year_id' => $financialYearId,
        'MemberBillSummary.member_id' => $societyMemberId);

        if ($this->Member->MemberBillSummary->hasAny($conditions)) {

            $memberPaymentBalance = $this->Member->MemberBillSummary->find('all', array('conditions' =>$conditions,'order' => array('MemberBillSummary.month' => 'desc')));

            if(!empty($memberPaymentBalance)){

                $jsonData = $memberPaymentBalance;

            }
           

        }

        return new CakeResponse(array('body' => json_encode($jsonData), 'status' => 200));

    }

}

public function getSocietyMembersOpBalance($societyMemberId=null) {
    $financialYearId = $this->Util->getFinancialYear();
    $jsonData = array();

    $this->layout = false; 

    if ($this->RequestHandler->isAjax()) {

        $this->loadModel('Member');

        $conditions = array('MemberBillSummary.society_id' => $this->Session->read('Auth.User.id'),'MemberBillSummary.financial_year_id' => $financialYearId,
        'MemberBillSummary.member_id' => $societyMemberId);

        if ($this->Member->MemberBillSummary->hasAny($conditions)) {

            $memberPaymentBalance = $this->Member->MemberBillSummary->find('all', array('conditions' =>$conditions,'order' => array('MemberBillSummary.id' => 'desc')));

            if(!empty($memberPaymentBalance)){

                $jsonData = $memberPaymentBalance[0];

            }
           

        }

        return new CakeResponse(array('body' => json_encode($jsonData), 'status' => 200));

    }

}



    //To Do For Search



public function getMemberDetailsByMemberID(){

    $this->layout = false;

    $jsonData = array();

    if ($this->RequestHandler->isAjax()) {

        $this->loadModel('SocietyHeadSubCategory');

        $this->SocietyHeadSubCategory->recursive = 1;

        $memberId = isset($this->request->data['member_id']) ? $this->request->data['member_id'] : 0;

        $jsonData = $this->SocietyBill->getMemberDetailsByMemberIDAndFlatNo($memberId);

        return new CakeResponse(array('body' => json_encode($jsonData), 'status' => 200));

    }

}

public function searchSocietyMemberDetailsByFlat() {

    $societyMemberDetails = array('Member' => array('status' => 1));

    if ($this->RequestHandler->isAjax()) {

        $this->layout = false;

        $this->loadModel('Member');

        $this->loadModel('MemberBillSummary');

            //$this->Member->recursive = -1;

        $societyMemberId = isset($this->request->data['societyMemberId']) ? $this->request->data['societyMemberId'] : 0;

        $societyMemberFlatId = isset($this->request->data['$societyMemberFlatId']) ? $this->request->data['$societyMemberFlatId'] : 0;

        $societyMemberDetails = $this->Member->find('first', array('conditions' => array('Member.id' => $societyMemberId,'Member.flat_no' => $societyMemberFlatId, 'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active'))));

        $condition = array('MemberBillSummary.society_id' => $this->Session->read('Auth.User.id'), 'MemberBillSummary.member_id' => $societyMemberId);

        $societyMemberBillPaymentData = array();

        $memberBillSummary = $this->Member->MemberBillSummary->find('all', array('conditions' => $condition));







        $societyMemberDetails['memberBillPayment'] = $societyMemberBillPaymentData;

    }

    return new CakeResponse(array('body' => json_encode($societyMemberDetails), 'status' => 200));

}



public function getSocietyLedgerHeadsList(){

    $this->layout = false;

    $jsonData = array();

    if ($this->RequestHandler->isAjax()) {

        $this->loadModel('SocietyLedgerHeads');

        $this->loadModel('SocietyTariffOrder');

        $this->SocietyLedgerHeads->recursive = -1;

        if ($this->SocietyTariffOrder->hasAny(array('SocietyTariffOrder.society_id' => $this->Session->read('Auth.User.id')))) {

                //$societyLedgerHeadList = $this->SocietyTariffOrder->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $this->Session->read('Auth.User.id')), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyTariffOrder.id' => 'asc')));

            $societyLedgerHeadList = $this->SocietyLedgerHeads->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $this->Session->read('Auth.User.id'), 'SocietyLedgerHeads.is_in_bill_charges' => 1), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyLedgerHeads.title' => 'asc'), 'recursive' => -1));

        } else {

            $societyLedgerHeadList = $this->SocietyLedgerHeads->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $this->Session->read('Auth.User.id'), 'SocietyLedgerHeads.is_in_bill_charges' => 1), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyLedgerHeads.title' => 'asc'), 'recursive' => -1));

        }

        return new CakeResponse(array('body' => json_encode($societyLedgerHeadList),'status' => 200));

    }

}



public function getCashInHandSocietyLedgerHeadId(){

    $this->layout = false;

    $jsonData = array();

    if ($this->RequestHandler->isAjax()) {

        $jsonData = $this->SocietyBill->getCashInHandSocietyLedgerHeadId();

    }

    return new CakeResponse(array('body' => json_encode($jsonData),'status' => 200));

}



public function getCashAndBankSocietyLedgerHeadDropDown(){

    $this->layout = false;

    $ledgerData = array();

    if ($this->RequestHandler->isAjax()) {

        $paymentMode = isset($this->request->data['paymentMode']) ? $this->request->data['paymentMode'] : "Bank";

        if($paymentMode == "Bank"){

            $ledgerData = $this->SocietyBill->societyBankBalanceHeadsLists();

        }else{

           $ledgerData = $this->SocietyBill->societyCashBalanceHeadsLists();

       }



       echo "<option value=\"\">Select Balance Ledger</option>";

       if(isset($ledgerData) && count($ledgerData) > 0){

        foreach($ledgerData as $societyLedgerHeadsId => $societyLedgerHeadsTitle){

            echo "<option value=\"$societyLedgerHeadsId\">$societyLedgerHeadsTitle</option>";

        }

    }

}

return new CakeResponse(array('body' =>'','status' => 200));

}



public function getBillDateBilldueDateFromFinancialYearDate(){

    $this->layout = false;

    $jsonData = array();

    if ($this->RequestHandler->isAjax()){

        $checkYear = isset($this->request->data['financialBillYear']) ? substr($this->request->data['financialBillYear'],0,4) : date('Y');

        $checkMonth = isset($this->request->data['financialMonth']) ? $this->request->data['financialMonth'] : date('m');

        $jsonData = $this->Util->getBillDateBilldueDateFromFinancialYearDate($checkYear,$checkMonth);  

    }

    return new CakeResponse(array('body' => json_encode($jsonData),'status' => 200));

}



public function getAllMemberTariffDetails(){

    ini_set('memory_limit', -1);

        set_time_limit(0); // make sure we don't time out

        $this->loadModel('MemberTariff');

        $societyLoginID = $this->Session->read('Auth.User.id');

        $this->loadModel('SocietyLedgerHeads');

        $this->loadModel('SocietyTariffOrder');

        $memberTariffHeader  = array();

        $memberTariffDataArra = array();

        $memberTariffTotalArra = array(); //sammeer new added

        if ($this->RequestHandler->isAjax()){

            if ($this->SocietyTariffOrder->hasAny(array('SocietyTariffOrder.society_id' => $societyLoginID))) {

                $societyLedgerHeadList = $this->SocietyTariffOrder->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID), 'fields' => array('SocietyLedgerHeads.id','SocietyLedgerHeads.title'), 'order' => array('SocietyTariffOrder.id' => 'asc')));

            } else {

                $societyLedgerHeadList = $this->SocietyLedgerHeads->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID, 'SocietyLedgerHeads.is_in_bill_charges' => 1), 'fields' => array('SocietyLedgerHeads.id','SocietyLedgerHeads.title'),'order' => array('SocietyLedgerHeads.title' => 'asc'),));

            }

            $orConditions = '';

            if(isset($this->request->data['building_id']) && !empty($this->request->data['building_id'])){

                $orConditions  = array('OR'=>array('Member.building_id'=>$this->request->data['building_id']));

            }

            

            $societyMemberListArr = $this->SocietyBill->getSocietyMemberList();



            $wingID = isset($this->request->data['wing_id']) ? $this->request->data['wing_id'] :'';

            $memberTariffHeader['sr_no'] = 'SR.';

            $memberTariffHeader['Member_Name'] = 'MEMBER NAME';

            $memberTariffHeader['flat_no'] = 'Flat No';

            if(isset($societyLedgerHeadList) && !empty($societyLedgerHeadList)){

                foreach($societyMemberListArr as $memberID => $memberName){

                    foreach($societyLedgerHeadList as $ledgerData){

                        $memberTariffDetails = $this->MemberTariff->find('all', array('conditions' => array('MemberTariff.society_id' => $societyLoginID,'MemberTariff.member_id' => $memberID,'MemberTariff.ledger_head_id' => $ledgerData['SocietyLedgerHeads']['id'],'Member.status' => Configure::read('Active'),$orConditions)));

                        if(isset($memberTariffDetails) && count($memberTariffDetails) > 0){

                            foreach($memberTariffDetails as $memberTarrifData){

                                $memberTariffDataArra[$memberTarrifData['MemberTariff']['member_id']]['member_name'] = $memberName;

                                $memberTariffDataArra[$memberTarrifData['MemberTariff']['member_id']]['flat_no'] = $memberTarrifData['Member']['flat_no'];

                                $memberTariffDataArra[$memberTarrifData['MemberTariff']['member_id']]['ledgerData'][$memberTarrifData['MemberTariff']['ledger_head_id']][$memberTarrifData['MemberTariff']['ledger_head_id']] = $memberTarrifData['MemberTariff']['amount'];

                                $memberTariffHeader[$memberTarrifData['SocietyLedgerHeads']['title']] = $memberTarrifData['SocietyLedgerHeads']['title'];

                                $memberTariffTotalArra[$memberTarrifData['MemberTariff']['ledger_head_id']] += $memberTarrifData['MemberTariff']['amount']; 

                            }

                        }else{

                            $memberTariffDataArra[$memberID]['member_name'] = $memberName;

                            $memberTariffDataArra[$memberID]['flat_no'] = "";

                            $memberTariffDataArra[$memberID]['ledgerData'][$ledgerData['SocietyLedgerHeads']['id']][$ledgerData['SocietyLedgerHeads']['id']] = "0.00";

                            $memberTariffHeader[$ledgerData['SocietyLedgerHeads']['title']] = $ledgerData['SocietyLedgerHeads']['title'];

                            $memberTariffTotalArra[] = "0.00";

                        }

                    }

                }

                $memberTariffHeader['total'] = 'Total';

            }

        }

        $this->set(compact('memberTariffHeader','memberTariffDataArra','memberTariffTotalArra'));

    }

    

    public function updateAllMemberTariffDetails(){

        $this->layout = false;

        ini_set('memory_limit', -1);

        set_time_limit(0); // make sure we don't time out

        $jsonData = array();

        $this->loadModel('MemberTariff');

        $this->loadModel('SocietyTariffOrder');

        if ($this->RequestHandler->isAjax()) {

            $societyLoginID = $this->Session->read('Auth.User.id');

            $postMemberTariff = array();

            if ($this->request->is('post', 'put')) {

                if (isset($this->request->data['MemberTariff']) && count($this->request->data['MemberTariff']) > 0) {

                    foreach ($this->request->data['MemberTariff'] as $MemberID => $mTariffData) {

                        foreach ($mTariffData as $ledgerHeadId => $fData) {

                            $this->MemberTariff->create();

                            $societyMemberExists = $this->MemberTariff->find('first', array('conditions' => array('MemberTariff.member_id' => $MemberID, 'MemberTariff.ledger_head_id' => $ledgerHeadId, 'MemberTariff.society_id' => $societyLoginID), 'recursive' => -1));

                            if (!empty($societyMemberExists)) {

                                $this->MemberTariff->id = $societyMemberExists['MemberTariff']['id'];

                            }

                            $postMemberTariff['society_id'] = $societyLoginID;

                            $postMemberTariff['member_id'] = isset($MemberID) ? $MemberID : '';

                            $postMemberTariff['amount'] = isset($fData[$ledgerHeadId]) ? $fData[$ledgerHeadId] : 0;

                            $postMemberTariff['updated_date'] = $this->Util->getDateTime();

                            $postMemberTariff['ledger_head_id'] = isset($ledgerHeadId) ? $ledgerHeadId : null;

                            $serialOrderDetails = $this->SocietyTariffOrder->find('first',array('conditions' => array('SocietyTariffOrder.society_id' => $societyLoginID, 'SocietyTariffOrder.ledger_head_id' => $ledgerHeadId),'recursive' => -1));

                            $postMemberTariff['tariff_serial'] = isset($serialOrderDetails['SocietyTariffOrder']['tariff_serial']) ? $serialOrderDetails['SocietyTariffOrder']['tariff_serial'] : null;

                            $postMemberTariff['updated_date'] = $this->Util->getDateTime();

                            if ($this->MemberTariff->save($postMemberTariff)) {

                                $jsonData['error'] = 0;

                                $jsonData['error_message'] = 'The member tariff has been Updated.';

                                unset($postMemberTariff);

                            } else {

                                $jsonData['error'] = 1;

                                $jsonData['error_message'] = 'The member tariff could not be saved. Please, try again.';

                            }

                        }

                    }

                }

            }

        }

        return new CakeResponse(array('body' => json_encode($jsonData), 'status' => 200));

    }

    

    public function updateChequeClearedDates() {

        $this->layout = false; 

        $response['error'] = 1;

        $response['error_message'] = 'Cheque Cleared Dates could not be saved.';

        if ($this->request->is('ajax')) {

            $this->autoRender = false;

            

            $this->loadModel('SocietyPayment');

            $this->loadModel('MemberPayment');

            $this->loadModel('SocietyOtherIncome');

            

            $recieptType = $this->request->data['update_type'];

            $dataArr = $this->request->data['ClearChequePayment'];

            if(isset($dataArr) && !empty($dataArr)){                

                foreach($dataArr as $paymentID =>$chequeData){

                    if($recieptType == "Receipt"){                        

                        $this->MemberPayment->id = $paymentID;

                        $this->MemberPayment->saveField('credited_date',$chequeData['cleared_date']);

                        $response['error'] = 0;

                        $response['error_message'] = 'Cheque Cleared Dates have been updated successfully.';

                    } else if($recieptType == "Payment"){

                        $this->SocietyPayment->id = $paymentID;

                        $this->SocietyPayment->saveField('debited_date',$chequeData['cleared_date']);

                        $response['error'] = 0;

                        $response['error_message'] = 'Cheque Cleared Dates have been updated successfully.';

                    }  else if($recieptType == "General"){                        

                        $this->SocietyOtherIncome->id = $paymentID;

                        $this->SocietyOtherIncome->saveField('credited_date',$chequeData['cleared_date']);

                        $response['error'] = 0;

                        $response['error_message'] = 'Cheque Cleared Dates have been updated successfully.';

                    }

                }

            }

        }

        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));

    }



    public function bank_reconciliation() {

        $societyLoginID = $this->Session->read('Auth.User.id');

        if (1) {

            $cashBookData = array();        



            if (isset($societyLoginID) && !empty($societyLoginID)) {

                $this->loadModel('SocietyPayment');

                $this->loadModel('MemberPayment');

                $this->loadModel('SocietyLedgerHeads');

                $this->loadModel('CashWithdraw');

                $this->loadModel('SocietyOtherIncome');



                $societyDetails = $this->SocietyBill->getSocietyDetails();

                $mon = date('m');



                if ($mon > 4) {

                    $y = date('Y');

                } else {

                    $y = date('Y', strtotime('-1 year'));

                }

                $firstDay = $y . "-04-01";



                $options['AND'] = array('MemberPayment.society_id' => $societyLoginID);

                $optionsSociety['AND'] = array('SocietyPayment.society_id' => $societyLoginID);

                $optionsCashWithdraw['AND'] = array('CashWithdraw.society_id' => $societyLoginID);

                $optionsContraReceived['AND'] = array('CashWithdraw.society_id' => $societyLoginID);

                $optionsSocietyOtherIncome['AND'] = array('SocietyOtherIncome.society_id' => $societyLoginID);



                $openingBalance = 0;



                if (isset($this->request->data['society_bank_id']) && !empty($this->request->data['society_bank_id'])) {

                    $options['AND']['MemberPayment.society_bank_id'] = $this->request->data['society_bank_id'];

                    $optionsCashWithdraw['AND']['CashWithdraw.bank_ledger_head_id'] = $this->request->data['society_bank_id'];



                    $optionsContraReceived['AND']['CashWithdraw.bank_to_ledger_head_id'] = $this->request->data['society_bank_id'];

                    $optionsSociety['AND']['SocietyPayment.payment_by_ledger_id'] = $this->request->data['society_bank_id'];

                    $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.society_bank_id'] = $this->request->data['society_bank_id'];



                    //For Ledger head opening balance

                    $ledgerHeadDetails = $this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.id' => $this->request->data['society_bank_id'], 'SocietyLedgerHeads.society_id' => $societyLoginID)));

                    $openingBalance = $ledgerHeadDetails['SocietyLedgerHeads']['opening_amount'];

                    $bankName = $ledgerHeadDetails['SocietyLedgerHeads']['title'];

                }

                if (isset($this->request->data['payment_date']) && !empty($this->request->data['payment_date']) && !empty($this->request->data['payment_date_to'])) {

                    $options['OR']['MemberPayment.payment_date BETWEEN ? and ?'] = array($this->request->data['payment_date'], $this->request->data['payment_date_to']);

                    $optionsCashWithdraw['OR']['CashWithdraw.payment_date BETWEEN ? and ?'] = array($this->request->data['payment_date'], $this->request->data['payment_date_to']);

                    $optionsContraReceived['OR']['CashWithdraw.payment_date BETWEEN ? and ?'] = array($this->request->data['payment_date'], $this->request->data['payment_date_to']);

                    $optionsSociety['OR']['SocietyPayment.payment_date BETWEEN ? and ?'] = array($this->request->data['payment_date'], $this->request->data['payment_date_to']);

                    $optionsSocietyOtherIncome['OR']['SocietyOtherIncome.payment_date BETWEEN ? and ?'] = array($this->request->data['payment_date'], $this->request->data['payment_date_to']);



                   /*  if ($this->request->data['payment_date'] != $firstDay){

                        $openingBalance = $this->getOpeningBalanceForthePeriod($this->request->data['payment_date'], $societyLoginID, $this->request->data['society_bank_id']);

                    }*/

                } else if (isset($this->request->data['payment_date']) && !empty($this->request->data['payment_date'])) {

                    $options['AND']['MemberPayment.payment_date'] = $this->request->data['payment_date'];

                    $optionsCashWithdraw['AND']['CashWithdraw.payment_date'] = $this->request->data['payment_date'];

                    $optionsContraReceived['AND']['CashWithdraw.payment_date'] = $this->request->data['payment_date'];

                    $optionsSociety['AND']['SocietyPayment.payment_date'] = $this->request->data['payment_date'];

                    $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.payment_date'] = $this->request->data['payment_date'];



                    /* if ($this->request->data['payment_date'] != $firstDay){

                        $openingBalance = $this->getOpeningBalanceForthePeriod($this->request->data['payment_date'], $societyLoginID, $this->request->data['society_bank_id']);

                    }*/ 

                } else if (isset($this->request->data['payment_date_to']) && !empty($this->request->data['payment_date_to'])) {

                    $options['AND']['MemberPayment.payment_date'] = $this->request->data['payment_date_to'];

                    $optionsCashWithdraw['AND']['CashWithdraw.payment_date'] = $this->request->data['payment_date_to'];

                    $optionsContraReceived['AND']['CashWithdraw.payment_date'] = $this->request->data['payment_date_to'];

                    $optionsSociety['AND']['SocietyPayment.payment_date'] = $this->request->data['payment_date_to'];

                    $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.payment_date'] = $this->request->data['payment_date_to'];



                    /* if ($this->request->data['payment_date_to'] != $firstDay){

                        $openingBalance = $this->getOpeningBalanceForthePeriod($this->request->data['payment_date_to'], $societyLoginID, $this->request->data['society_bank_id']);

                    }*/ 

                }







                $societyBankBbookDetails = array();

                $memberBankBbookDetails = array();

                $cashWithdrawDetails = array();

                $SocietyOtherIncomeDetails = array();



                $cashDeposit = 0;

                $cashWithdraw = 0;

                $transferWithdraw = 0;

                $transferDeposit = 0;

                $chequeDeposit = 0;

                $chequeWithdraw = 0;

                $otherDeposit = 0;

                $otherWithdraw = 0;

                $unclearedDeposit = 0;

                $unclearedWithdraw = 0;



                $this->MemberPayment->Behaviors->attach('Containable');

                $this->SocietyPayment->Behaviors->attach('Containable');



                $memberBankBbookDetails = $this->MemberPayment->find('all', array('contain' => array('Member'), 'conditions' => $options, 'order' => array('MemberPayment.payment_date' => 'asc')));

                $societyBankBbookDetails = $this->SocietyPayment->find('all', array('contain' => array('SocietyLedgerHeads'), 'conditions' => $optionsSociety, 'order' => array('SocietyPayment.payment_date' => 'asc')));

                $cashWithdrawDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsCashWithdraw, 'order' => array('CashWithdraw.payment_date' => 'asc')));

                $cashContraReceivedDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsContraReceived, 'order' => array('CashWithdraw.payment_date' => 'asc')));







                $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.payment_mode'] = 'Bank';

                $SocietyOtherIncomeDetails = $this->SocietyOtherIncome->find('all', array('conditions' => $optionsSocietyOtherIncome,'order' => array('SocietyOtherIncome.payment_date' => 'asc')));



                if (isset($societyBankBbookDetails) && !empty($societyBankBbookDetails)) {

                    foreach ($societyBankBbookDetails as $societyBankBbookData) {

                  //      echo '<pre>';

                //print_r($societyBankBbookData['SocietyPayment']);

                //exit;



                        if(strpos(strtolower($societyBankBbookData['SocietyPayment']['cheque_reference_number']), 'cash') === false) {

                            $chequeWithdraw += $societyBankBbookData['SocietyPayment']['total_amount'];

                            //echo $societyBankBbookData['SocietyPayment']['total_amount'].'->'.$societyBankBbookData['SocietyPayment']['ledger_head_id'].'->'.$societyBankBbookData['SocietyPayment']['cheque_reference_number'].'<br>';

                        }

                        

                        if($societyBankBbookData['SocietyPayment']['debited_date']> $this->request->data['payment_date_to']){

                         $unclearedWithdraw += $societyBankBbookData['SocietyPayment']['total_amount'];



                     }



                     $validDate = (bool)strtotime($societyBankBbookData['SocietyPayment']['debited_date']);

                     if ($societyBankBbookData['SocietyPayment']['debited_date'] != '0000-00-00' && $societyBankBbookData['SocietyPayment']['debited_date'] != NULL && $validDate == true){

                            //

                     } else {

                        $unclearedWithdraw += $societyBankBbookData['SocietyPayment']['total_amount'];

                    }





                    unset($societyBankBbookData);

                }

            }



            if (isset($memberBankBbookDetails) && !empty($memberBankBbookDetails)) {

                foreach ($memberBankBbookDetails as $memberBankBbookData) {

                    if ($memberBankBbookData['MemberPayment']['payment_mode'] != 1) {                            

                        $chequeDeposit += $memberBankBbookData['MemberPayment']['amount_paid'];

                    }

                    if($memberBankBbookData['MemberPayment']['credited_date']> $this->request->data['payment_date_to']){

                      $unclearedDeposit+= $memberBankBbookData['MemberPayment']['amount_paid'];



                  }



                  $validDate = (bool)strtotime($memberBankBbookData['MemberPayment']['credited_date']);



                  if ($memberBankBbookData['MemberPayment']['credited_date'] != '0000-00-00' && $memberBankBbookData['MemberPayment']['credited_date'] != NULL && $validDate == true){



                  } else {

                      $unclearedDeposit+= $memberBankBbookData['MemberPayment']['amount_paid'];

                  }

                  unset($memberBankBbookData);

              }

          }



          if (isset($cashWithdrawDetails) && !empty($cashWithdrawDetails)) {

            foreach ($cashWithdrawDetails as $cashWithdrawData) {

                switch ($cashWithdrawData['CashWithdraw']['txn_type']) {

                    case 'contra' :

                    $flag = 'Contra';

                    break;

                    case 'deposit' :

                    $flag = 'Deposit';

                    break;

                    case 'withdraw' :

                    $flag = 'Withdrawal';

                    break;

                }



                if ($flag == 'Withdrawal') {

                    $cashWithdraw += $cashWithdrawData['CashWithdraw']['amount'];

                } else if ($flag == 'Deposit') {

                    $cashDeposit += $cashWithdrawData['CashWithdraw']['amount'];

                } else if ($flag == 'Contra') {

                    $transferWithdraw += $cashWithdrawData['CashWithdraw']['amount'];

                }

                unset($cashWithdrawData);

            }



        }



        if (isset($cashContraReceivedDetails) && !empty($cashContraReceivedDetails)) {

            foreach ($cashContraReceivedDetails as $cashWithdrawData) {

                switch ($cashWithdrawData['CashWithdraw']['txn_type']) {

                    case 'contra' :

                    $flag = 'Contra';

                    break;

                }



                if ($flag == 'Contra') {

                    $transferDeposit += $cashWithdrawData['CashWithdraw']['amount'];

                }

                unset($cashWithdrawData);

            }



        }

        

                //society other income 

        if (isset($SocietyOtherIncomeDetails) && !empty($SocietyOtherIncomeDetails)) {

            foreach ($SocietyOtherIncomeDetails as $otherIncomeData) {

                if (strpos(strtolower($otherIncomeData['SocietyOtherIncome']['cheque_no']), 'cash') === false && $otherIncomeData['SocietyOtherIncome']['cheque_no'] != '') {

                    $chequeDeposit += $otherIncomeData['SocietyOtherIncome']['amount_paid'];

                }

                if($otherIncomeData['SocietyOtherIncome']['credited_date']> $this->request->data['payment_date_to']){

 						//$unclearedDeposit= $otherIncomeData['SocietyOtherIncome']['amount_paid'];



                }



                $validDate = (bool)strtotime($otherIncomeData['SocietyOtherIncome']['credited_date']);

                if ($otherIncomeData['SocietyOtherIncome']['credited_date'] != '0000-00-00' && $validDate == true){

                            //

                } else {

                 $unclearedDeposit+= $otherIncomeData['SocietyOtherIncome']['amount_paid'];

             }

             unset($otherIncomeData);

         }

     }



     $cashBookData['OpeningBalance'] = $openingBalance;

     $cashBookData['CashDeposit'] = $cashDeposit;

     $cashBookData['CashWithdraw'] = $cashWithdraw;

     $cashBookData['TransferDeposit'] = $transferDeposit;

     $cashBookData['TransferWithdraw'] = $transferWithdraw;

     $cashBookData['ChequeDeposit'] = $chequeDeposit;

     $cashBookData['ChequeWithdraw'] = $chequeWithdraw;

     $cashBookData['OtherDeposit'] = $otherDeposit;

     $cashBookData['OtherWithdraw'] = $otherWithdraw;

     $cashBookData['UnclearedDeposit'] = $unclearedDeposit;



     $cashBookData['UnclearedWithdraw'] = $unclearedWithdraw;



     $cashBookData['CashDeposit'] = $cashDeposit+$otherDeposit;

     $cashBookData['CashWithdraw'] = $cashWithdraw+$otherWithdraw;



     $dates['payment_date'] = (isset($this->request->data['payment_date']) && !empty($this->request->data['payment_date'])) ? $this->request->data['payment_date'] : '';

     $dates['payment_date_to'] = (isset($this->request->data['payment_date_to']) && !empty($this->request->data['payment_date_to'])) ? $this->request->data['payment_date_to'] : '';





     $this->set(compact('cashBookData','societyDetails','dates','bankName'));

 } 

}

}  



public function bank_reconciliation_detail() {

    $societyLoginID = $this->Session->read('Auth.User.id');

    if (1) {

        $cashBookData = array();        





        if (isset($societyLoginID) && !empty($societyLoginID)) {

            $this->loadModel('SocietyPayment');

            $this->loadModel('MemberPayment');

            $this->loadModel('SocietyLedgerHeads');

            $this->loadModel('CashWithdraw');

            $this->loadModel('SocietyOtherIncome');



            $societyDetails = $this->SocietyBill->getSocietyDetails();

            $mon = date('m');



            if ($mon > 4) {

                $y = date('Y');

            } else {

                $y = date('Y', strtotime('-1 year'));

            }

            $firstDay = $y . "-04-01";



            $options['AND'] = array('MemberPayment.society_id' => $societyLoginID);

            $optionsSociety['AND'] = array('SocietyPayment.society_id' => $societyLoginID);

            $optionsCashWithdraw['AND'] = array('CashWithdraw.society_id' => $societyLoginID);

            $optionsContraReceived['AND'] = array('CashWithdraw.society_id' => $societyLoginID);

            $optionsSocietyOtherIncome['AND'] = array('SocietyOtherIncome.society_id' => $societyLoginID);



            $openingBalance = 0;



            if (isset($this->request->data['society_bank_id']) && !empty($this->request->data['society_bank_id'])) {

                $options['AND']['MemberPayment.society_bank_id'] = $this->request->data['society_bank_id'];

                $optionsCashWithdraw['AND']['CashWithdraw.bank_ledger_head_id'] = $this->request->data['society_bank_id'];



                $optionsContraReceived['AND']['CashWithdraw.bank_to_ledger_head_id'] = $this->request->data['society_bank_id'];

                $optionsSociety['AND']['SocietyPayment.payment_by_ledger_id'] = $this->request->data['society_bank_id'];

                $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.society_bank_id'] = $this->request->data['society_bank_id'];



                    //For Ledger head opening balance

                $ledgerHeadDetails = $this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.id' => $this->request->data['society_bank_id'], 'SocietyLedgerHeads.society_id' => $societyLoginID)));

                $openingBalance = $ledgerHeadDetails['SocietyLedgerHeads']['opening_amount'];

                $bankName = $ledgerHeadDetails['SocietyLedgerHeads']['title'];

            }

            if (isset($this->request->data['payment_date']) && !empty($this->request->data['payment_date']) && !empty($this->request->data['payment_date_to'])) {

                $options['OR']['MemberPayment.payment_date BETWEEN ? and ?'] = array($this->request->data['payment_date'], $this->request->data['payment_date_to']);

                $optionsCashWithdraw['OR']['CashWithdraw.payment_date BETWEEN ? and ?'] = array($this->request->data['payment_date'], $this->request->data['payment_date_to']);

                $optionsContraReceived['OR']['CashWithdraw.payment_date BETWEEN ? and ?'] = array($this->request->data['payment_date'], $this->request->data['payment_date_to']);

                $optionsSociety['OR']['SocietyPayment.payment_date BETWEEN ? and ?'] = array($this->request->data['payment_date'], $this->request->data['payment_date_to']);

                $optionsSocietyOtherIncome['OR']['SocietyOtherIncome.payment_date BETWEEN ? and ?'] = array($this->request->data['payment_date'], $this->request->data['payment_date_to']);



                    /* if ($this->request->data['payment_date'] != $firstDay){

                        $openingBalance = $this->getOpeningBalanceForthePeriod($this->request->data['payment_date'], $societyLoginID, $this->request->data['society_bank_id']);

                    } */

                } else if (isset($this->request->data['payment_date']) && !empty($this->request->data['payment_date'])) {

                    $options['AND']['MemberPayment.payment_date'] = $this->request->data['payment_date'];

                    $optionsCashWithdraw['AND']['CashWithdraw.payment_date'] = $this->request->data['payment_date'];

                    $optionsContraReceived['AND']['CashWithdraw.payment_date'] = $this->request->data['payment_date'];

                    $optionsSociety['AND']['SocietyPayment.payment_date'] = $this->request->data['payment_date'];

                    $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.payment_date'] = $this->request->data['payment_date'];



                    /* if ($this->request->data['payment_date'] != $firstDay){

                        $openingBalance = $this->getOpeningBalanceForthePeriod($this->request->data['payment_date'], $societyLoginID, $this->request->data['society_bank_id']);

                    }*/

                } else if (isset($this->request->data['payment_date_to']) && !empty($this->request->data['payment_date_to'])) {

                    $options['AND']['MemberPayment.payment_date'] = $this->request->data['payment_date_to'];

                    $optionsCashWithdraw['AND']['CashWithdraw.payment_date'] = $this->request->data['payment_date_to'];

                    $optionsContraReceived['AND']['CashWithdraw.payment_date'] = $this->request->data['payment_date_to'];

                    $optionsSociety['AND']['SocietyPayment.payment_date'] = $this->request->data['payment_date_to'];

                    $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.payment_date'] = $this->request->data['payment_date_to'];



                    /* if ($this->request->data['payment_date_to'] != $firstDay){

                        $openingBalance = $this->getOpeningBalanceForthePeriod($this->request->data['payment_date_to'], $societyLoginID, $this->request->data['society_bank_id']);

                    }*/

                }





                $societyBankBbookDetails = array();

                $memberBankBbookDetails = array();

                $cashWithdrawDetails = array();

                $SocietyOtherIncomeDetails = array();



                $cashDeposit = 0;

                $cashWithdraw = 0;

                $transferWithdraw = 0;

                $transferDeposit = 0;

                $chequeDeposit = 0;

                $chequeWithdraw = 0;

                $otherDeposit = 0;

                $otherWithdraw = 0;

                $unclearedDeposit = 0;

                $unclearedWithdraw = 0;

                $unclearedWithdrawArr = array();

                $unclearedDepositArr = array();



                $this->MemberPayment->Behaviors->attach('Containable');

                $this->SocietyPayment->Behaviors->attach('Containable');



                $memberBankBbookDetails = $this->MemberPayment->find('all', array('contain' => array('Member'), 'conditions' => $options, 'order' => array('MemberPayment.payment_date' => 'asc')));

                $societyBankBbookDetails = $this->SocietyPayment->find('all', array('contain' => array('SocietyLedgerHeads'), 'conditions' => $optionsSociety, 'order' => array('SocietyPayment.payment_date' => 'asc')));

                $cashWithdrawDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsCashWithdraw, 'order' => array('CashWithdraw.payment_date' => 'asc')));

                $cashContraReceivedDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsContraReceived, 'order' => array('CashWithdraw.payment_date' => 'asc')));



                $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.payment_mode'] = 'Bank';

                $SocietyOtherIncomeDetails = $this->SocietyOtherIncome->find('all', array('conditions' => $optionsSocietyOtherIncome,'order' => array('SocietyOtherIncome.payment_date' => 'asc')));



                if (isset($societyBankBbookDetails) && !empty($societyBankBbookDetails)) {

                    foreach ($societyBankBbookDetails as $societyBankBbookData) {

                  //      echo '<pre>';

                //print_r($societyBankBbookData['SocietyPayment']);

                //exit;



                        if(strpos(strtolower($societyBankBbookData['SocietyPayment']['cheque_reference_number']), 'cash') === false) {

                            $chequeWithdraw += $societyBankBbookData['SocietyPayment']['total_amount'];

                            //echo $societyBankBbookData['SocietyPayment']['total_amount'].'->'.$societyBankBbookData['SocietyPayment']['ledger_head_id'].'->'.$societyBankBbookData['SocietyPayment']['cheque_reference_number'].'<br>';

                        }



                        if($societyBankBbookData['SocietyPayment']['debited_date']> $this->request->data['payment_date_to']){

                           $unclearedWithdraw += $societyBankBbookData['SocietyPayment']['total_amount'];

                           $unclearedWithdrawArr[] = array('voucher_no'=>$societyBankBbookData['SocietyPayment']['bill_voucher_number'],'payment_date'=>$societyBankBbookData['SocietyPayment']['payment_date'],'particulars'=>$societyBankBbookData['SocietyPayment']['particulars'],'cheque_no'=>$societyBankBbookData['SocietyPayment']['cheque_reference_number'],'amount'=>$societyBankBbookData['SocietyPayment']['total_amount']);

                       }



                       $validDate = (bool)strtotime($societyBankBbookData['SocietyPayment']['debited_date']);

                       if ($societyBankBbookData['SocietyPayment']['debited_date'] != '0000-00-00' && $societyBankBbookData['SocietyPayment']['debited_date'] != NULL && $validDate == true){

                            //

                       } else {

                        $unclearedWithdraw += $societyBankBbookData['SocietyPayment']['total_amount'];

                        $societyBankBbookData['SocietyPayment']['particulars'] = $societyBankBbookData['SocietyPayment']['particulars'] . '&' . $societyBankBbookData['SocietyLedgerHeads']['title'];

                        $unclearedWithdrawArr[] = array('voucher_no'=>$societyBankBbookData['SocietyPayment']['bill_voucher_number'],'payment_date'=>$societyBankBbookData['SocietyPayment']['payment_date'],'particulars'=>$societyBankBbookData['SocietyPayment']['particulars'],'cheque_no'=>$societyBankBbookData['SocietyPayment']['cheque_reference_number'],'amount'=>$societyBankBbookData['SocietyPayment']['total_amount']);

                    }

                    unset($societyBankBbookData);

                }

            }



            if (isset($memberBankBbookDetails) && !empty($memberBankBbookDetails)) {

                foreach ($memberBankBbookDetails as $memberBankBbookData) {

                    if ($memberBankBbookData['MemberPayment']['payment_mode'] != 1) {                            

                        $chequeDeposit += $memberBankBbookData['MemberPayment']['amount_paid'];

                    }

                    if($memberBankBbookData['MemberPayment']['credited_date']> $this->request->data['payment_date_to']){

                        $unclearedDeposit += $memberBankBbookData['MemberPayment']['amount_paid'];

                        $unclearedDepositArr[] = array('voucher_no'=>$memberBankBbookData['MemberPayment']['bill_generated_id'],'payment_date'=>$memberBankBbookData['MemberPayment']['payment_date'],'particulars'=>$memberBankBbookData['MemberPayment']['particulars'],'cheque_no'=>$memberBankBbookData['MemberPayment']['cheque_reference_number'],'amount'=>$memberBankBbookData['MemberPayment']['amount_paid']);



                    }

                    $validDate = (bool)strtotime($memberBankBbookData['MemberPayment']['credited_date']);

                    if ($memberBankBbookData['MemberPayment']['credited_date'] != '0000-00-00' && $memberBankBbookData['MemberPayment']['credited_date'] != NULL && $validDate == true){

                            //

                    } else {

                        $unclearedDeposit += $memberBankBbookData['MemberPayment']['amount_paid'];

                        $memberBankBbookData['MemberPayment']['particulars'] = $memberBankBbookData['Member']['flat_no'] . ' - ' . $memberBankBbookData['Member']['member_name'] . '<br>' . 'Being Maintenance received';

                        $unclearedDepositArr[] = array('voucher_no'=>$memberBankBbookData['MemberPayment']['bill_generated_id'],'payment_date'=>$memberBankBbookData['MemberPayment']['payment_date'],'particulars'=>$memberBankBbookData['MemberPayment']['particulars'],'cheque_no'=>$memberBankBbookData['MemberPayment']['cheque_reference_number'],'amount'=>$memberBankBbookData['MemberPayment']['amount_paid']);

                    }

                    unset($memberBankBbookData);

                }

            }



            if (isset($cashWithdrawDetails) && !empty($cashWithdrawDetails)) {

                foreach ($cashWithdrawDetails as $cashWithdrawData) {

                    switch ($cashWithdrawData['CashWithdraw']['txn_type']) {

                        case 'contra' :

                        $flag = 'Contra';

                        break;

                        case 'deposit' :

                        $flag = 'Deposit';

                        break;

                        case 'withdraw' :

                        $flag = 'Withdrawal';

                        break;

                    }



                    if ($flag == 'Withdrawal') {

                        $cashWithdraw += $cashWithdrawData['CashWithdraw']['amount'];

                    } else if ($flag == 'Deposit') {

                        $cashDeposit += $cashWithdrawData['CashWithdraw']['amount'];

                    } else if ($flag == 'Contra') {

                        $transferWithdraw += $cashWithdrawData['CashWithdraw']['amount'];

                    }

                    unset($cashWithdrawData);

                }



            }



            if (isset($cashContraReceivedDetails) && !empty($cashContraReceivedDetails)) {

                foreach ($cashContraReceivedDetails as $cashWithdrawData) {

                    switch ($cashWithdrawData['CashWithdraw']['txn_type']) {

                        case 'contra' :

                        $flag = 'Contra';

                        break;

                    }



                    if ($flag == 'Contra') {

                        $transferDeposit += $cashWithdrawData['CashWithdraw']['amount'];

                    }

                    unset($cashWithdrawData);

                }



            }



                //society other income 

            if (isset($SocietyOtherIncomeDetails) && !empty($SocietyOtherIncomeDetails)) {

                foreach ($SocietyOtherIncomeDetails as $otherIncomeData) {

                    if (strpos(strtolower($otherIncomeData['SocietyOtherIncome']['cheque_no']), 'cash') === false && $otherIncomeData['SocietyOtherIncome']['cheque_no'] != '') {

                        $chequeDeposit += $otherIncomeData['SocietyOtherIncome']['amount_paid'];

                    } 

                    $validDate = (bool)strtotime($otherIncomeData['SocietyOtherIncome']['credited_date']);

                    if ($otherIncomeData['SocietyOtherIncome']['credited_date'] != '0000-00-00' && $validDate == true){

                            //

                    } else {

                        $unclearedDeposit += $otherIncomeData['SocietyOtherIncome']['amount_paid'];

                        $otherIncomeData['SocietyOtherIncome']['particulars'] = $otherIncomeData['SocietyOtherIncome']['title'];

                        $unclearedDepositArr[] = array('voucher_no'=>$otherIncomeData['SocietyOtherIncome']['general_receipt_number'],'payment_date'=>$otherIncomeData['SocietyOtherIncome']['payment_date'],'particulars'=>$otherIncomeData['SocietyOtherIncome']['particulars'],'cheque_no'=>$otherIncomeData['SocietyOtherIncome']['cheque_no'],'amount'=>$otherIncomeData['SocietyOtherIncome']['amount_paid']);

                    }

                    unset($otherIncomeData);

                }

            }





            $cashBookData['OpeningBalance'] = $openingBalance;

            $cashBookData['CashDeposit'] = $cashDeposit;

            $cashBookData['CashWithdraw'] = $cashWithdraw;

            $cashBookData['TransferDeposit'] = $transferDeposit;

            $cashBookData['TransferWithdraw'] = $transferWithdraw;

            $cashBookData['ChequeDeposit'] = $chequeDeposit;

            $cashBookData['ChequeWithdraw'] = $chequeWithdraw;

            $cashBookData['OtherDeposit'] = $otherDeposit;

            $cashBookData['OtherWithdraw'] = $otherWithdraw;

            $cashBookData['UnclearedDeposit'] = $unclearedDeposit;

            $cashBookData['UnclearedDepositArr'] = $unclearedDepositArr;

            $cashBookData['UnclearedWithdraw'] = $unclearedWithdraw;

            $cashBookData['UnclearedWithdrawArr'] = $unclearedWithdrawArr;

            $cashBookData['CashDeposit'] = $cashDeposit+$otherDeposit;

            $cashBookData['CashWithdraw'] = $cashWithdraw+$otherWithdraw;



            $dates['payment_date'] = (isset($this->request->data['payment_date']) && !empty($this->request->data['payment_date'])) ? $this->request->data['payment_date'] : '';

            $dates['payment_date_to'] = (isset($this->request->data['payment_date_to']) && !empty($this->request->data['payment_date_to'])) ? $this->request->data['payment_date_to'] : '';

            $this->set(compact('cashBookData','societyDetails','dates','bankName'));

        } 

    }

}  



function getOpeningBalanceForthePeriod($date, $societyId, $ledgerHeadId) {

    $this->loadModel('SocietyPayment');

    $this->loadModel('MemberPayment');

    $this->loadModel('SocietyLedgerHeads');

    $this->loadModel('CashWithdraw');

    $this->loadModel('SocietyOtherIncome');

    $totalBal = 0;

    $options['AND'] = array('MemberPayment.society_id' => $societyId);

    $optionsSociety['AND'] = array('SocietyPayment.society_id' => $societyId);

    $optionsCashWithdraw['AND'] = array('CashWithdraw.society_id' => $societyId);

    $optionsContraReceived['AND'] = array('CashWithdraw.society_id' => $societyId);

    $optionsSocietyOtherIncome['AND'] = array('SocietyOtherIncome.society_id' => $societyId);



    if (isset($ledgerHeadId) && !empty($ledgerHeadId)) {

        $options['AND']['MemberPayment.society_bank_id'] = $ledgerHeadId;

        $optionsCashWithdraw['AND']['CashWithdraw.bank_ledger_head_id'] = $ledgerHeadId;



        $optionsContraReceived['AND']['CashWithdraw.bank_to_ledger_head_id'] = $ledgerHeadId;

        $optionsSociety['AND']['SocietyPayment.payment_by_ledger_id'] = $ledgerHeadId;

        $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.society_bank_id'] = $ledgerHeadId;



            //For Ledger head opening balance

        $ledgerHeadDetails = $this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.id' => $ledgerHeadId, 'SocietyLedgerHeads.society_id' => $societyId)));

    }



    if (isset($date) && !empty($date)) {

        $paymentDateFrom = $this->getFirstDayFinancialYear();

           // $paymentDateTo = date('Y-m-d', strtotime($date. ' - '.'1'.' days'));

        //     $paymentDateTo = date('Y-m-d', strtotime($paymentDateTo. ' + '.'1'.' years'));

        $options['OR']['MemberPayment.payment_date BETWEEN ? and ?'] = array($paymentDateFrom, $paymentDateTo);

        $optionsCashWithdraw['OR']['CashWithdraw.payment_date BETWEEN ? and ?'] = array($paymentDateFrom, $paymentDateTo);

        $optionsContraReceived['OR']['CashWithdraw.payment_date BETWEEN ? and ?'] = array($paymentDateFrom, $paymentDateTo);

        $optionsSociety['OR']['SocietyPayment.payment_date BETWEEN ? and ?'] = array($paymentDateFrom, $paymentDateTo);

        $optionsSocietyOtherIncome['OR']['SocietyOtherIncome.payment_date BETWEEN ? and ?'] = array($paymentDateFrom, $paymentDateTo);

    } 



    $bankBookData = array();

    $societyBankBbookDetails = array();

    $memberBankBbookDetails = array();

    $cashWithdrawDetails = array();

    $SocietyOtherIncomeDetails = array();





    $this->MemberPayment->Behaviors->attach('Containable');

        $this->SocietyPayment->Behaviors->attach('Containable'); //Make sure report type is deposit or withdrawal

        $memberBankBbookDetails = $this->MemberPayment->find('all', array('contain' => array('Member'), 'conditions' => $options, 'order' => array('MemberPayment.payment_date' => 'asc')));

        $societyBankBbookDetails = $this->SocietyPayment->find('all', array('contain' => array('SocietyLedgerHeads'), 'conditions' => $optionsSociety, 'order' => array('SocietyPayment.payment_date' => 'asc')));

        $cashWithdrawDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsCashWithdraw, 'order' => array('CashWithdraw.payment_date' => 'asc')));

        $cashContraReceivedDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsContraReceived, 'order' => array('CashWithdraw.payment_date' => 'asc')));



        $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.payment_mode'] = 'Bank';

        $SocietyOtherIncomeDetails = $this->SocietyOtherIncome->find('all', array('conditions' => $optionsSocietyOtherIncome,'order' => array('SocietyOtherIncome.payment_date' => 'asc')));



        if (isset($societyBankBbookDetails) && !empty($societyBankBbookDetails)) {

            $sCounter = 0;

            foreach ($societyBankBbookDetails as $societyBankBbookData) {

                $bankBookData[$societyBankBbookData['SocietyPayment']['payment_date']]['Payment'][$sCounter]['withdrawal'] = $societyBankBbookData['SocietyPayment']['total_amount'];

                $bankBookData[$societyBankBbookData['SocietyPayment']['payment_date']]['Payment'][$sCounter]['deposit'] = "0.00";

                unset($societyBankBbookData);

                $sCounter++;

            }

        }



        $memberReceipt = 0;

        if (isset($memberBankBbookDetails) && !empty($memberBankBbookDetails)) {

            $mCounter = 0;

            foreach ($memberBankBbookDetails as $memberBankBbookData) {

                $bankBookData[$memberBankBbookData['MemberPayment']['payment_date']]['Receipt'][$mCounter]['withdrawal'] = "0.00";

                $bankBookData[$memberBankBbookData['MemberPayment']['payment_date']]['Receipt'][$mCounter]['deposit'] = $memberBankBbookData['MemberPayment']['amount_paid'];

                $memberReceipt += $memberBankBbookData['MemberPayment']['amount_paid'];

                unset($memberBankBbookData);

                $mCounter++;

            }

        }



        $cashCounter = 0;



        $depositAmount = 0;



        if (isset($cashWithdrawDetails) && !empty($cashWithdrawDetails)) {

            foreach ($cashWithdrawDetails as $cashWithdrawData) {

                switch ($cashWithdrawData['CashWithdraw']['txn_type']) {

                    case 'contra' :

                    $flag = 'Contra';

                    break;

                    case 'deposit' :

                    $flag = 'Deposit';

                    break;

                    case 'withdraw' :

                    $flag = 'Withdrawal';

                    break;

                }



                if ($flag == 'Contra' || $flag == 'Withdrawal') {

                    $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['withdrawal'] = $cashWithdrawData['CashWithdraw']['amount'];

                    $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['deposit'] = '0.00';

                } else if ($flag == 'Deposit') {

                    $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['withdrawal'] = '0.00';

                    $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['deposit'] = $cashWithdrawData['CashWithdraw']['amount'];

                    $depositAmount += $cashWithdrawData['CashWithdraw']['amount'];

                }



                $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['payment_flag'] = $flag;

                unset($cashWithdrawData);

                $cashCounter++;

            }



        }



        //society other income bank general receipts only for withdraw data

        $otherIncomeAmount = 0;

        if (isset($SocietyOtherIncomeDetails) && !empty($SocietyOtherIncomeDetails)) {

            $otherIncomeCounter = $cashCounter;

            $flag = "Deposit";

            foreach ($SocietyOtherIncomeDetails as $otherIncomeData) {

                $bankBookData[$otherIncomeData['SocietyOtherIncome']['payment_date']][$flag][$otherIncomeCounter]['withdrawal'] = "0.00";

                $bankBookData[$otherIncomeData['SocietyOtherIncome']['payment_date']][$flag][$otherIncomeCounter]['deposit'] = $otherIncomeData['SocietyOtherIncome']['amount_paid'];

                $otherIncomeAmount += $otherIncomeData['SocietyOtherIncome']['amount_paid'];

                unset($otherIncomeData);

                $otherIncomeCounter++;

            }

        }



        if (isset($cashContraReceivedDetails) && !empty($cashContraReceivedDetails)) {

            $contraCounter = 0;

            foreach ($cashContraReceivedDetails as $contraData) {



                if ($contraData['CashWithdraw']['txn_type'] != 'contra') {

                    continue;

                }



                $bankBookData[$contraData['CashWithdraw']['payment_date']][$flag][$cashCounter]['withdrawal'] = '0.00';

                $bankBookData[$contraData['CashWithdraw']['payment_date']][$flag][$cashCounter]['deposit'] = $contraData['CashWithdraw']['amount'];

                unset($contraData);

                $contraCounter++;

            }

        }



        ksort($bankBookData); //sorting society and member payment by payment 





        $totalDeposit = 0;

        $totalWithdraw = 0;



        if(isset($ledgerHeadDetails) && !empty($ledgerHeadDetails['SocietyLedgerHeads']['opening_amount'])) { 

            $totalDeposit = $ledgerHeadDetails['SocietyLedgerHeads']['opening_amount'];

        }

        if(isset($bankBookData) && count($bankBookData) > 0){

            $idCount = 1;                                                

            foreach($bankBookData as $paymentDate => $bankBookInfo){

                foreach($bankBookInfo as $flagType => $bookData){



                    if(isset($bookData) && count($bookData) > 0){

                        foreach($bookData as $finalData){  

                            $totalDeposit += $finalData['deposit'];

                            $totalWithdraw += $finalData['withdrawal'];



                            $totalBalancePerRow = $totalDeposit - abs($totalWithdraw);



                            $idCount++;

                        } 

                    } 

                } 

            }

            $totalBal = $totalDeposit - abs($totalWithdraw);

        }

        return $totalBal;

    }

    

    public function get_society_bill_dates(){

        $this->loadModel('MemberBillSummary');

        $billDateData = [

            'bill_generated_date' => '',

            'bill_due_date' => ''

        ];

        if(!empty($this->request->data)){

            $societyLoginID = $this->Session->read('Auth.User.id');

            $billMonthNoWithFreqId = $this->request->data['bill_month_combined_freq_id'];

            $exploadedBillMonthNoWithFreqId = explode('-',$billMonthNoWithFreqId);

            $monthId = $exploadedBillMonthNoWithFreqId[0];

            $billFreqId = $exploadedBillMonthNoWithFreqId[1];



            

            $options['AND'] = array('MemberBillSummary.society_id' => $societyLoginID,'MemberBillSummary.bill_frequency_id' => $billFreqId,'MemberBillSummary.month' => $monthId);

            $societyMemberbillData = $this->MemberBillSummary->find('first', array('conditions' => $options, 'fields' => array('MemberBillSummary.bill_due_date', 'MemberBillSummary.bill_generated_date')));            

            if(!empty($societyMemberbillData)){

                $billDateData = [

                    'bill_generated_date' => $societyMemberbillData['MemberBillSummary']['bill_generated_date'],

                    'bill_due_date' => $societyMemberbillData['MemberBillSummary']['bill_due_date']

                ];

            }

        }

        return new CakeResponse(array('body' => json_encode($billDateData), 'status' => 200)); 

    }    

    public function get_society_payment_paricular_narration(){
        $societyPayments = [];
        $SocietyLedgerHeads = [];
        
        if(!empty($this->request->data)){
           
            
            $this->loadModel('SocietyPayment');
            $ledgerHeaderId = $this->request->data['debit_to_id'];
            $societyLoginID = $this->Session->read('Auth.User.id');
             $this->loadModel('SocietyLedgerHeads');
           $SocietyLedgerHeads = $this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.id' => $ledgerHeaderId),'fields' => array('tds_value')));

   
            // $this->SocietyLedgerHeads->find('first', array('fields' => array('SocietyLedgerHeads.tds_value'),'conditions' => array('SocietyLedgerHeads.id' => $ledgerHeaderId )));
            $societyPayments = $this->SocietyPayment->find('all',array('conditions' => array('SocietyPayment.society_id' => $societyLoginID,'SocietyPayment.ledger_head_id' => $ledgerHeaderId),'fields' => array('ledger_head_id','particulars','notes')));
        }
        $nao= [
            'societyPayments' =>$societyPayments,
            
            'SocietyLedgerHeads' =>$SocietyLedgerHeads];
        
        return new CakeResponse(array('body' => json_encode($nao),'status' => 200)); 
    }      

    

    public function getFirstDayFinancialYear() {

        /* $mon = date('m');



        if ($mon >= 4) {

            $y = date('Y');

        } else {

            $y = date('Y', strtotime('-1 year'));

        }*/
        
        return $this->Session->read('Auth.formated_year_start_date');

        /* $db = $this->Session->read('Auth.dbConfig');

        return ($db == 'default') ? "01/04/20222" : "01/04/2099"; */

    }

    public function get_general_receipt_details() {

        $this->layout = false;

        $this->autoRender = false;

        $response = array('error' => 1, 'error_message' => 'General receipt details could not be loaded.', 'data' => array());

        if ($this->request->is('ajax')) {

            $this->loadModel('SocietyOtherIncome');

            $societyLoginID = $this->Session->read('Auth.User.id');

            $generalReceiptId = isset($this->request->data['general_receipt_id']) ? $this->request->data['general_receipt_id'] : 0;

            $generalReceiptData = $this->SocietyOtherIncome->find('first', array('conditions' => array('SocietyOtherIncome.id' => $generalReceiptId, 'SocietyOtherIncome.society_id' => $societyLoginID), 'recursive' => -1));

            if (!empty($generalReceiptData)) {

                $receiptData = $generalReceiptData['SocietyOtherIncome'];

                foreach (array('payment_date', 'cheque_date') as $dateField) {

                    if (!isset($receiptData[$dateField]) || $receiptData[$dateField] == '0000-00-00') {

                        $receiptData[$dateField] = '';

                    }

                }

                $response['error'] = 0;

                $response['error_message'] = '';

                $response['data'] = $receiptData;

            } else {

                $response['error_message'] = 'Requested general receipt is not found.';

            }

        }

        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));

    }

    public function update_general_receipt() {

        $this->layout = false;

        $this->autoRender = false;

        $response = array('error' => 1, 'error_message' => 'The general receipt could not be updated. Please, try again.');

        if ($this->request->is('ajax')) {

            $this->loadModel('SocietyOtherIncome');

            $societyLoginID = $this->Session->read('Auth.User.id');

            $requestData = isset($this->request->data['SocietyOtherIncome']) ? $this->request->data['SocietyOtherIncome'] : array();

            $generalReceiptId = isset($requestData['id']) ? $requestData['id'] : 0;

            $generalReceiptData = $this->SocietyOtherIncome->find('first', array('conditions' => array('SocietyOtherIncome.id' => $generalReceiptId, 'SocietyOtherIncome.society_id' => $societyLoginID), 'recursive' => -1));

            if (empty($generalReceiptData)) {

                $response['error_message'] = 'Requested general receipt is not found.';

                return new CakeResponse(array('body' => json_encode($response), 'status' => 200));

            }

            if (empty($requestData['ledger_head_id'])) {

                $response['error_message'] = 'Please select the head in Received By.';

                return new CakeResponse(array('body' => json_encode($response), 'status' => 200));

            }

            $paymentDate = isset($requestData['payment_date']) ? $requestData['payment_date'] : '';

            if (empty($paymentDate) || !$this->Util->isDateInCurrentFinancialYear($paymentDate)) {

                $response['error_message'] = 'Payment date should be within the current financial year.';

                return new CakeResponse(array('body' => json_encode($response), 'status' => 200));

            }

            $postData = array();

            $postData['id'] = $generalReceiptData['SocietyOtherIncome']['id'];

            $postData['ledger_head_id'] = $requestData['ledger_head_id'];

            $postData['payment_mode'] = isset($requestData['payment_mode']) ? trim($requestData['payment_mode']) : '';

            $postData['amount_paid'] = isset($requestData['amount_paid']) && $requestData['amount_paid'] !== '' ? $requestData['amount_paid'] : '0.00';

            $postData['tds_amount'] = isset($requestData['tds_amount']) && $requestData['tds_amount'] !== '' ? $requestData['tds_amount'] : '0.00';

            $postData['net_amount'] = isset($requestData['net_amount']) && $requestData['net_amount'] !== '' ? $requestData['net_amount'] : '0.00';

            $postData['tds_bank_id'] = isset($requestData['tds_bank_id']) ? $requestData['tds_bank_id'] : '';

            $postData['title'] = isset($requestData['title']) ? $requestData['title'] : '';

            $postData['description'] = isset($requestData['description']) ? $requestData['description'] : '';

            $postData['payment_date'] = $paymentDate;

            if ($postData['payment_mode'] == 'Bank') {

                $postData['society_bank_id'] = isset($requestData['society_bank_id']) && $requestData['society_bank_id'] !== '' ? $requestData['society_bank_id'] : 0;

                $postData['cheque_no'] = isset($requestData['cheque_no']) && $requestData['cheque_no'] !== '' ? $requestData['cheque_no'] : 0;

                $postData['cheque_date'] = isset($requestData['cheque_date']) && $requestData['cheque_date'] != '' ? $requestData['cheque_date'] : null;

                $postData['general_bank_name'] = isset($requestData['general_bank_name']) ? $requestData['general_bank_name'] : '';

            } else {

                $postData['society_bank_id'] = 0;

                $postData['cheque_no'] = 0;

                $postData['cheque_date'] = null;

                $postData['general_bank_name'] = '';

            }

            $postData['udate'] = $this->Util->getDateTime();

            $this->SocietyOtherIncome->id = $postData['id'];

            if ($this->SocietyOtherIncome->save(array('SocietyOtherIncome' => $postData), false, array_keys($postData))) {

                $response['error'] = 0;

                $response['error_message'] = 'The general receipt has been updated.';

            }

        }

        return new CakeResponse(array('body' => json_encode($response), 'status' => 200));

    }

}

