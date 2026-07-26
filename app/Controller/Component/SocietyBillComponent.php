<?php

App::uses('Component', 'Controller');



class SocietyBillComponent extends Component {

    public $components = array('Session','Util');

    public function initialize(Controller $controller) {

        $this->controller = $controller;
    }



    public function getSocietyBuildingLists(){

        App::import('model', 'Society');

        $societyObj = new Society();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyBuildingLists = $societyObj->Building->find('list', array('fields' => array('Building.id', 'Building.building_name'), 'conditions' => array('Building.society_id' => $societyLoginID)));

        $this->controller->set('societyBuildingLists', $societyBuildingLists);

    }



    

    public function getSocietyBuildingListsById(){

        App::import('model', 'Society');

        $societyObj = new Society();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyBuildingLists = $societyObj->Building->find('list', array('fields' => array('Building.id', 'Building.building_name'), 'conditions' => array('Building.society_id' => $societyLoginID)));

        if($societyBuildingLists){

            return $societyBuildingLists;

        }

        return array();

    }

    

    public function getSocietyWingListsById(){

        App::import('model', 'Society');

        $societyObj = new Society();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyBuildingLists = $societyObj->Wing->find('list', array('fields' => array('Wing.id', 'Wing.wing_name'), 'conditions' => array('Wing.society_id' => $societyLoginID)));

        if($societyBuildingLists){

            return $societyBuildingLists;

        }

        return array();

    }

    

    public function getSocietyParameters() {

        App::import('model', 'SocietyParameter');

        $societyParameterObj = new SocietyParameter();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyParameters = $societyParameterObj->find('first', array('conditions' => array('SocietyParameter.society_id' => $societyLoginID)));

        return $societyParameters;

    }

    

    public function loadAllNavigationModalsDropdowns(){

        $societyAccountCategoryLists = $this->getAccountCategoryData();

        if (count($societyAccountCategoryLists) > 0) {

            $this->controller->set('societyAccountCategoryLists', $societyAccountCategoryLists);

        }



        $societyHeadSubCategoryData = $this->getSocietyHeadSubCategories();

        if (count($societyHeadSubCategoryData) > 0) {

            $this->controller->set('societyHeadSubCategoryData', $societyHeadSubCategoryData);

        }



        $societyBankBalanceHeadsLists = $this->societyBankBalanceHeadsLists();

        if (is_array($societyBankBalanceHeadsLists) && count($societyBankBalanceHeadsLists) > 0) {

            $this->controller->set('societyBankBalanceHeadsLists', $societyBankBalanceHeadsLists);

        }



        $societyCashBalanceHeadsLists = $this->societyCashBalanceHeadsLists();

        if (count($societyCashBalanceHeadsLists) > 0) {

            $this->controller->set('societyCashBalanceHeadsLists', $societyCashBalanceHeadsLists);

        }



        $societyExpenseLedgerHeadsLists = $this->societyAllLedgerHeadsLists();

        if (is_array($societyExpenseLedgerHeadsLists) && count($societyExpenseLedgerHeadsLists) > 0) {

            $this->controller->set('societyExpenseLedgerHeadsLists', $societyExpenseLedgerHeadsLists);

        }



        $societyOtherIncomeHeadsLists = $this->societyOtherIncomeHeadsLists();

        if (is_array($societyOtherIncomeHeadsLists) && count($societyOtherIncomeHeadsLists) > 0) {

            $this->controller->set('societyOtherIncomeHeadsLists', $societyOtherIncomeHeadsLists);

        }



        $societyMemberListData = $this->getSocietyMemberList();



        if (is_array($societyMemberListData) && count($societyMemberListData) > 0) {

            $this->controller->set('societyMemberListData', $societyMemberListData);

        }



        $societyBankLists = $this->getSocietyBankNameList();

        if (is_array($societyBankLists) && count($societyBankLists) > 0) {

            $this->controller->set('societyBankLists', $societyBankLists);

        }



        $societyParameters = $this->getSocietyParameters();

        $this->controller->set('societyParameters',$societyParameters);



        $financialYear = $this->getFinancialYearFromDate();

        // getFinancialYearFromDate() concatenates Auth.start_year and Auth.end_year
        // from the session. When neither is set - an admin who has not entered a
        // society yet, or a session that expired - it returns a bare "-", which is
        // not empty, so the old `!= ''` guard let it through. substr("-", 0, 4) is
        // "-", and DateTime("--07-01") then throws, turning every screen that loads
        // the nav dropdowns into a blank 500. Require a real 4-digit year instead.
        $checkYear = substr((string)$financialYear, 0, 4);
        if (ctype_digit($checkYear) && strlen($checkYear) === 4) {
            $checkMonth = date('m');
            $financialStartEndDate = $this->Util->getBillDateBilldueDateFromFinancialYearDate($checkYear, $checkMonth);
            if (isset($financialStartEndDate) && $financialStartEndDate != '') {
                $this->controller->set('financialStartEndDate', $financialStartEndDate);
            }

            $this->controller->set('financialYear', $financialYear);

        }
        
        
        $financialYearStartDate = $this->Session->read('Auth.year_start_date');
        
        $financialYearEndDate = $this->Session->read('Auth.year_end_date');
        
        $this->controller->set('financialYearStartDate', $financialYearStartDate);
        $this->controller->set('financialYearEndDate', $financialYearEndDate);
        
        $startYear = $this->Session->read('Auth.start_year');
        $endYear = $this->Session->read('Auth.end_year');
        $this->controller->set('startYear', $startYear);
        $this->controller->set('endYear', $endYear);
        
        

        $this->controller->set('societyName', $this->Session->read('Auth.User.society_name'));
    
    }

    public function sortMultiDimentionalArray($a,$b){

        return (strtotime($a["formatted_date"]) <= strtotime($b["formatted_date"])) ? -1 : 1;

    }

    public function getSocietyLedgerHeadsDetails(){

        App::import('model', 'SocietyLedgerHeads');

        App::import('model','MemberBillGenerate');

        App::import('model','SocietyPayment');

        

        $societyLedgerHeadsObj = new SocietyLedgerHeads();

        $memberBillGenerateObj = new MemberBillGenerate();

        $societyPaymentObj = new SocietyPayment();

        

        $societyLoginID = $this->Session->read('Auth.User.id');

        //$societyLedgerHeadsObj->recursive = -1;

	$societyLedgerHeadsLists = $societyLedgerHeadsObj->find('all', array('conditions' => array('SocietyLedgerHeads.society_id' => $societyLoginID, 'SocietyLedgerHeads.status' => Configure::read('Active')), 'fields' => array('SocietyLedgerHeads.id', 'SocietyLedgerHeads.title'), 'order' => array('SocietyLedgerHeads.title' => 'asc')));



        $societyLedgerHeadsDetails = $societyLedgerHeadsObj->find('first', array('conditions' => array('SocietyLedgerHeads.society_id' => $societyLoginID), 'order' => array('SocietyLedgerHeads.id' => 'asc')));
    
        if(isset($societyLedgerHeadsDetails['AccountHead']['title']) && $societyLedgerHeadsDetails['AccountHead']['title']=='Income'){
            $societyLedgerHeadsDetails['SocietyLedgerHeads']['opening_amount']=0.00;
        }

        if (isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['id'])) {

            $societyLedgerHeadsNextRecord = $societyLedgerHeadsObj->find('first', array('fields' => array('SocietyLedgerHeads.id', 'SocietyLedgerHeads.title'), 'conditions' => array('SocietyLedgerHeads.id >' => $societyLedgerHeadsDetails['SocietyLedgerHeads']['id'], 'SocietyLedgerHeads.society_id' => $this->Session->read('Auth.User.id'), 'SocietyLedgerHeads.status' => Configure::read('Active')), 'order' => array('SocietyLedgerHeads.id' => 'asc')));

            $this->controller->set('societyLedgerHeadsNextRecord', $societyLedgerHeadsNextRecord);

        }

        

        //print_r($societyCashBalanceHeadsLists);die;

        

        if (count($societyLedgerHeadsDetails) > 0) {

            //Get society ledger head expenses and payments

            $txnType = $societyLedgerHeadsDetails['AccountHead']['transaction_type'];

            if (stripos($txnType, 'credit') !== false) {

                $txnType = 'credit';

            } else if (stripos($txnType, 'debit') !== false) {

                $txnType = 'debit';

            }



            $isInBill = $societyLedgerHeadsDetails['SocietyLedgerHeads']['is_in_bill_charges'];



            $societyPaymentData = array();



            $societyLedgerHeadId = $societyLedgerHeadsDetails['SocietyLedgerHeads']['id'];



            if ($isInBill == 0) {

                $societyPayments = $societyPaymentObj->find('all', array('conditions' => array('SocietyPayment.ledger_head_id' => $societyLedgerHeadId, 'SocietyPayment.society_id' => $this->Session->read('Auth.User.id'))));

                if (!empty($societyPayments)) {

                    foreach ($societyPayments as $paymentDetails) {

                        $paymentData = $paymentDetails['SocietyPayment'];

                        $paymentDate = date('d/m/Y', strtotime($paymentData['payment_date']));

                        $formattedDate = date('Y-m-d',strtotime($paymentData['bill_generated_date']));

                        $societyPaymentData[] = array('payment_date' => $paymentDate,'formatted_date' => $formattedDate,'amount' => $paymentData['total_amount'], 'txn_type' => $txnType);

                    }

                }

            } else {

                $memberBills = $memberBillGenerateObj->find('all', array('conditions' => array('MemberBillGenerate.ledger_head_id' => $societyLedgerHeadId, 'MemberBillGenerate.society_id' => $this->Session->read('Auth.User.id'))));

                if (!empty($memberBills)) {

                    foreach ($memberBills as $paymentDetails) {

                        $paymentData = $paymentDetails['MemberBillGenerate'];

                        $paymentDate = date('d/m/Y', strtotime($paymentData['bill_generated_date']));

                        $formattedDate = date('Y-m-d',strtotime($paymentData['bill_generated_date']));

                        $societyPaymentData[] = array('payment_date' => $paymentDate,'formatted_date' => $formattedDate,'amount' => $paymentData['amount'], 'txn_type' => $txnType);

                    }

                }

            }

            

            $journalVoucherLedgerPaymentData = $this->getJournalVoucherCreaditDebitDataByLedgerHeadId($societyLedgerHeadId);

            

            if (!empty($journalVoucherLedgerPaymentData)) {

                //$societyPaymentData[] = $JournalVoucherPaymentReceiptDetails;

                foreach ($journalVoucherLedgerPaymentData as $jvData) {

                    $txnJvType = '';

                    $jvAmt = 0;

                    $JVPARTICULAR = "";

                    if ($jvData['JournalVoucher']['jv_debit_ledger_head_id'] != '') {

                        $txnJvType = "debit";

                        $jvAmt = $jvData['JournalVoucher']['jv_amount_debited'];

                        $JVPARTICULAR = "JV Debited. V.No ".$jvData['JournalVoucher']['voucher_no'];

                    }



                    if ($jvData['JournalVoucher']['jv_credit_ledger_head_id'] != '') {

                        $txnJvType = "credit";

                        $jvAmt = $jvData['JournalVoucher']['jv_amount_credited'];

                        $JVPARTICULAR = "JV Credited. V.No ".$jvData['JournalVoucher']['voucher_no'];

                    }



                    $paymentDate = isset($jvData['JournalVoucher']['voucher_date']) ? date('d/m/Y',strtotime($jvData['JournalVoucher']['voucher_date'])) : "";

                    $formattedDate = isset($jvData['JournalVoucher']['voucher_date']) ? date('Y-m-d',strtotime($jvData['JournalVoucher']['voucher_date'])) : "";

                    $societyPaymentData[] = array('particularNote' => $JVPARTICULAR,'payment_date' => $paymentDate,'formatted_date' => $formattedDate,'amount' => $jvAmt, 'txn_type' => $txnJvType);

                }

            }

            

            usort($societyPaymentData,array($this,"sortMultiDimentionalArray"));


            $societyLedgerHeadsDetails['ledgerPaymentData'] = $societyPaymentData;

            $societyLedgerHeadsDetails['txn_type'] = $txnType;

            $this->controller->set('societyLedgerHeadsDetails', $societyLedgerHeadsDetails);

	    $this->controller->set('societyLedgerHeadsLists', $societyLedgerHeadsLists);

        }

    }

    

    



   public function navigateSocietyMemberDetails(){
//Configure::write('debug', 2);
        //flat shop details

        App::import('model', 'Member');

        $societyMemberObj = new Member();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $this->getSocietyBuildingLists();

        //$societyMemberObj->Behaviors->attach('Containable');

        $societyMemberLists = $societyMemberObj->find('first', array('contain' => array('Society', 'Building', 'Wing'), 'conditions' => array('Member.status' => Configure::read('Active'), 'Member.society_id' => $societyLoginID), 'order' => array('Member.id' => 'asc')));

       

        $societyMemberFlatList = $societyMemberObj->find('all', array('contain' => array('flat_no'), 'conditions' => array('Member.status' => Configure::read('Active'), 'Member.society_id' => $societyLoginID), 'fields'=>array('flat_no'), 'order' => array('Member.id' => 'asc')));


        if (isset($societyMemberLists['Member']['id'])) {

            $societyMemberNextRecord = $societyMemberObj->find('first', array('fields' => array('Member.id', 'Member.member_name'), 'conditions' => array('Member.id >' => $societyMemberLists['Member']['id'], 'Member.society_id' => $societyLoginID, 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'asc'), 'recursive' => -1));

            $this->controller->set('societyMemberNextRecord', $societyMemberNextRecord);



            $condition = array('MemberBillSummary.society_id' => $societyLoginID, 'MemberBillSummary.member_id' => $societyMemberLists['Member']['id']);

            $societyMemberBillPaymentData = array();

            $memberBillSummary = $societyMemberObj->MemberBillSummary->find('all', array('conditions' => $condition));

            

            $summaryCounter = 0;

            if (isset($memberBillSummary) && count($memberBillSummary) > 0){

                $summaryCounter = 0;

                foreach ($memberBillSummary as $billData){

                    if (isset($billData['MemberBillSummary'])){

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary'] = $billData['MemberBillSummary'];

                        //Change date format

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date'] = date('d/m/Y', strtotime($societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date']));

                        if (isset($societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['balance_amount']) && $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['balance_amount'] > 0) {

                            $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['debit_credit_value'] = "DR";

                        } else {

                            $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['debit_credit_value'] = "CR";

                        }

                        $monthName = date("F", mktime(0, 0, 0, $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month'], 10));

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthName'] = $this->monthWordFormatByBillingFrequency($societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month']);

                        $paymentMadeBillNo = isset($billData['MemberBillSummary']['bill_no']) ? $billData['MemberBillSummary']['bill_no'] : '';

                        $conditionPayment = array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'), 'MemberPayment.member_id' => $societyMemberLists['Member']['id'], 'MemberPayment.bill_generated_id' => $paymentMadeBillNo);

                        if ($societyMemberObj->MemberPayment->hasAny($conditionPayment)){

                            $memberPaymentDetails = $societyMemberObj->MemberPayment->find('all', array('conditions' => $conditionPayment));

                            if (isset($memberPaymentDetails) && count($memberPaymentDetails) > 0) {

                                $paymentCounter = 0;

                                foreach ($memberPaymentDetails as $paymentData) {

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberPayment'][$paymentCounter] = $paymentData['MemberPayment'];

                                    $societyMemberBillPaymentData[$summaryCounter]['MemberPayment'][$paymentCounter]['payment_date'] = date('d/m/Y', strtotime($societyMemberBillPaymentData[$summaryCounter]['MemberPayment'][$paymentCounter]['payment_date']));

                                    $paymentCounter++;

                                }

                            }

                        }

                    }

                    $summaryCounter++;

                }

            }

            

            $journalVoucherMemberPaymentData = $this->getJournalVoucherCreaditDebitDataByMemberId($societyMemberLists['Member']['id']);

            if (!empty($journalVoucherMemberPaymentData)){

                $paymentCounter = 0;

				foreach ($journalVoucherMemberPaymentData as $jvData) {

                    if ($jvData['JournalVoucher']['jv_credit_member_head_id'] != '' && $jvData['JournalVoucher']['jv_credit_member_head_id'] == $societyMemberLists['Member']['id']){

                        $societyMemberBillPaymentData[$summaryCounter]['MemberPayment'][$paymentCounter]['amount_paid'] = isset($jvData['JournalVoucher']['jv_amount_credited']) ? $jvData['JournalVoucher']['jv_amount_credited'] : 0.00;

                        $societyMemberBillPaymentData[$summaryCounter]['MemberPayment'][$paymentCounter]['payment_date'] = isset($jvData['JournalVoucher']['voucher_date']) ? $this->Util->mysqlToDate($jvData['JournalVoucher']['voucher_date'], '/') : "";

                        $societyMemberBillPaymentData[$summaryCounter]['MemberPayment'][$paymentCounter]['cheque_reference_number'] = "JV Credited. V.No ".$jvData['JournalVoucher']['voucher_no'];

						$paymentCounter++;

                    }

                    

                    if ($jvData['JournalVoucher']['jv_debit_member_head_id'] != '' && $jvData['JournalVoucher']['jv_debit_member_head_id'] == $societyMemberLists['Member']['id']){

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['id'] = "";

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_no'] = "";

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_principal_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_bill_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['bill_generated_date'] = isset($jvData['JournalVoucher']['voucher_date']) ? $this->Util->mysqlToDate($jvData['JournalVoucher']['voucher_date'], '/') : "";

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthName'] = isset($jvData['JournalVoucher']['voucher_date']) ? "JV Debited. V. No ".$jvData['JournalVoucher']['voucher_no'] : "";

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['month'] = isset($jvData['JournalVoucher']['voucher_date']) ? date('m', strtotime($jvData['JournalVoucher']['voucher_date'])) : "";

                        $societyMemberBillPaymentData[$summaryCounter]['MemberBillSummary']['monthly_principal_amount'] = isset($jvData['JournalVoucher']['jv_amount_debited']) ? $jvData['JournalVoucher']['jv_amount_debited'] : 0.00;

                        $summaryCounter++;

                    }

                }

            }

            

            $this->controller->set('societyMemberBillPaymentData', $societyMemberBillPaymentData);

        }

        $this->controller->set('societyMemberLists', $societyMemberLists);

        $this->controller->set('societyMemberFlatList',$societyMemberFlatList);

    }



    public function getFlatShopDetails(){

        App::import('model', 'Member');

        $Member = new Member();

        $societyLoginID = $this->Session->read('Auth.User.id');

        //$societyLedgerHeadsObj->recursive = -1;

        $societyFlatShopDetails = $Member->find('first', array('conditions' => array('Member.society_id' => $societyLoginID), 'order' => array('Member.id' => 'asc')));

        if (isset($societyFlatShopDetails['societyFlatShopDetails']['id'])) {

            $societyFlatShopDetailsNextRecord = $Member->find('first', array('fields' => array('Member.id', 'Member.member_name'), 'conditions' => array('Member.id >' => $societyFlatShopDetails['Member']['id'], 'Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active')), 'order' => array('Member.id' => 'asc')));

            $this->controller->set('societyFlatShopDetailsNextRecord', $societyFlatShopDetailsNextRecord);

        }

    }

    

    public function getAccountCategoryData(){

        App::import('model','AccountCategory');

        $accountCategoryObj = new AccountCategory();

        $societyAccountCategoryLists = $accountCategoryObj->find('list', array('conditions' => array('AccountCategory.status' => Configure::read('Active')), 'fields' => array('AccountCategory.id', 'AccountCategory.title'), 'order' => array('AccountCategory.title' => 'asc')));

        if($societyAccountCategoryLists){

            return $societyAccountCategoryLists;

        }

        return array();

    }

    

    public function getSocietyHeadSubCategories(){

        App::import('model','SocietyHeadSubCategory');

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyHeadSubCategoryObj = new SocietyHeadSubCategory();

        $societyHeadSubCategoryData = $societyHeadSubCategoryObj->find('all', array('conditions' => array('SocietyHeadSubCategory.society_id' => $societyLoginID), 'fields' => array('SocietyHeadSubCategory.id', 'SocietyHeadSubCategory.account_status', 'SocietyHeadSubCategory.title'), 'order' => array('SocietyHeadSubCategory.title' => 'asc')));

         if($societyHeadSubCategoryData){

            return $societyHeadSubCategoryData;

        }

        return array();

    }

    

    public function getSocietyMemberPaymentBillNo() {

        $billNo = 1;

        App::import('model', 'MemberBillGenerate');

        $memberBillGenerateObj = new MemberBillGenerate();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $lastGeneratedBillData = $memberBillGenerateObj->find('first', array('conditions' => array('MemberBillGenerate.society_id' => $societyLoginID), 'fields' => array('MAX(bill_number) AS bill_number')));

        if (isset($lastGeneratedBillData[0]['bill_number']) && !empty($lastGeneratedBillData[0]['bill_number'])) {

            $billNo = $billNo + $lastGeneratedBillData[0]['bill_number'];

        } else {

            $billNo = $billNo;

        }

        return $billNo;

    }

    

    public function setUpdateBillCounterNo(){

        $billNo = 1;

        App::import('model', 'MasterCounter');

        $masterCounterObj = new MasterCounter();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $cData['society_id'] = $societyLoginID;

        $cData['counter_name'] = Configure::read('CounterName');

        $cData['counter'] = $billNo;

        $cData['status'] = Configure::read('Active');

        $cData['udate'] = $this->Util->getDateTime();

        

        $MasterDataCounter = $masterCounterObj->find('first', array('conditions' => array('MasterCounter.society_id' => $societyLoginID)));

        if(!empty($MasterDataCounter)){

             $counterValue = isset($MasterDataCounter['MasterCounter']['counter']) ? $MasterDataCounter['MasterCounter']['counter'] : 0;

             $billNo = $counterValue + 1;

             $masterCounterID = isset($MasterDataCounter['MasterCounter']['id']) ? $MasterDataCounter['MasterCounter']['id'] : 0;

             $masterCounterObj->updateAll(array("MasterCounter.counter" => "'".$billNo."'","MasterCounter.udate" => "'".$this->Util->getDateTime()."'"),array("MasterCounter.id" => $masterCounterID));

        }

        if(empty($MasterDataCounter)){

            $cData['cdate'] = $this->Util->getDateTime();

            $masterCounterObj->save($cData);

        }

        return $billNo;

    }

    

    public function getSocietyBankNameList() {

        $societyBankBalanceHeadsLists = $this->societyBankBalanceHeadsLists();

        if($societyBankBalanceHeadsLists){

            return $societyBankBalanceHeadsLists;

        }

    }

    

    public function getSocietyPaymentModeLists() {

        App::import('model', 'PaymentMode');

        $societyPaymentModeObj = new PaymentMode();

        $societyPaymentModeLists = $societyPaymentModeObj->find('list', array('fields' => array('PaymentMode.id','PaymentMode.payment_mode'), 'order' => array('PaymentMode.payment_mode' => 'asc')));

        if (count($societyPaymentModeLists) > 0) {

            return $societyPaymentModeLists;

        }

        return array();

    }

    

    public function getSocietyMemberList(){ 

        App::import('model', 'Member');

        $societyMemberObj = new Member();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyMemberLists = $societyMemberObj->find('list', array('conditions' => array('Member.status' => Configure::read('Active'), 'Member.society_id' => $societyLoginID), 'fields' => array('Member.id', 'Member.member_name'), 'order' => array('Member.id' => 'asc')));

        if (count($societyMemberLists) > 0) {

            return $societyMemberLists;

        }

        return array();

    }


    public function getSocietyMemberListAndMemberTranfer($limit=null,$offset=null){ 

        App::import('model', 'Member');

        $societyMemberObj = new Member();

        $societyLoginID = $this->Session->read('Auth.User.id');
        // changes by om on 24-04-2021 if else added becuase did not know impact of adding limit offset on callee methods
        if(!is_null($limit) && !is_null($offset))
            $societyMemberLists = $societyMemberObj->find('list', array('conditions' => array('Member.status' => Configure::read('Active'), 'Member.society_id' => $societyLoginID), 'fields' => array('Member.id', 'Member.member_transfer'), 'order' => array('Member.id' => 'asc'),'limit' => $limit,'offset'=>$offset));
        else
            $societyMemberLists = $societyMemberObj->find('list', array('conditions' => array('Member.status' => Configure::read('Active'), 'Member.society_id' => $societyLoginID), 'fields' => array('Member.id', 'Member.member_transfer'), 'order' => array('Member.id' => 'asc')));
        
        if (count($societyMemberLists) > 0) {

            return $societyMemberLists;

        }

        return array();

    }



    public function getMemberFlatNoList(){ 

        App::import('model', 'Member');

        $societyMemberObj = new Member();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyMemberLists = $societyMemberObj->find('list', array('conditions' => array('Member.status' => Configure::read('Active'), 'Member.society_id' => $societyLoginID), 'fields' => array('Member.id', 'Member.flat_no'), 'order' => array('Member.id' => 'asc')));

        if (count($societyMemberLists) > 0) {

            return $societyMemberLists;

        }

        return array();

    }



	public function getSocietyMemberFlatList(){

        App::import('model', 'Member');

        $societyMemberObj = new Member();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyMemberLists = $societyMemberObj->find('list', array('conditions' => array('Member.status' => Configure::read('Active'), 'Member.society_id' => $societyLoginID), 'fields' => array('Member.flat_no','Member.member_name','Member.id'), 'order' => array('Member.id' => 'asc')));

        if (count($societyMemberLists) > 0) {

            return $societyMemberLists;

        }

        return array();

    }

    

    public function getBankList(){

        App::import('model', 'Bank');

        $BankObj = new Bank();

        $bankLists = $BankObj->find('list', array('conditions' => array('Bank.status' => Configure::read('Active')), 'fields' => array('Bank.id', 'Bank.bank_name'), 'order' => array('Bank.bank_name' => 'asc')));

         if (count($bankLists) > 0) {

            return $bankLists;

        }

        return array();

    }

    

    public function societyExpenseLedgerHeadsLists() {

        App::import('model', 'SocietyLedgerHeads');

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyLedgerHeadsObj = new SocietyLedgerHeads();

        $societyExpenseLedgerHeadsLists = $societyLedgerHeadsObj->find('list', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID, 'SocietyLedgerHeads.account_category_id' => 4), 'fields' => array('SocietyLedgerHeads.title'), 'order' => array('SocietyLedgerHeads.title' => 'asc')));

        //$societyExpenseLedgerHeadsLists = $societyHeadSubCategoryObj->find('list',array('fields' => array('id','title'),'conditions' => array('SocietyHeadSubCategory.status' => Configure::read('Active'), 'SocietyHeadSubCategory.society_id' => $societyLoginID, 'SocietyHeadSubCategory.account_head_id' => 4),'recursive' => -1));

        if($societyExpenseLedgerHeadsLists){

            return $societyExpenseLedgerHeadsLists;

        }

    }

    

    public function societyAllLedgerHeadsLists() {

        App::import('model', 'SocietyLedgerHeads');

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyLedgerHeadsObj = new SocietyLedgerHeads();

        $societyExpenseLedgerHeadsLists = $societyLedgerHeadsObj->find('list', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID), 'fields' => array('SocietyLedgerHeads.title'), 'order' => array('SocietyLedgerHeads.title' => 'asc')));

        //$societyExpenseLedgerHeadsLists = $societyHeadSubCategoryObj->find('list',array('fields' => array('id','title'),'conditions' => array('SocietyHeadSubCategory.status' => Configure::read('Active'), 'SocietyHeadSubCategory.society_id' => $societyLoginID, 'SocietyHeadSubCategory.account_head_id' => 4),'recursive' => -1));

        if($societyExpenseLedgerHeadsLists){

            return $societyExpenseLedgerHeadsLists;

        }

    }

    

    public function societyBankBalanceHeadsLists(){

        App::import('model', 'SocietyLedgerHeads');

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyLedgerHeadsObj = new SocietyLedgerHeads();

        $BankBalanceDetails = $this->societyBankBalanceDetails();

        $bankBalanceHeadSubCategoryId = isset($BankBalanceDetails['SocietyHeadSubCategory']['id']) ? $BankBalanceDetails['SocietyHeadSubCategory']['id'] :'';

        $societyBankBalanceHeadsLists = $societyLedgerHeadsObj->find('list', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'),'SocietyLedgerHeads.society_id' => $societyLoginID,'SocietyLedgerHeads.society_head_sub_category_id'=>$bankBalanceHeadSubCategoryId),'fields' => array('SocietyLedgerHeads.title'), 'order' => array('SocietyLedgerHeads.title' => 'asc')));

        if($societyBankBalanceHeadsLists){

            return $societyBankBalanceHeadsLists;

        }

    }

    

    public function societyCashBalanceHeadsLists(){

        App::import('model', 'SocietyLedgerHeads');

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyLedgerHeadsObj = new SocietyLedgerHeads();

        $cashBalanceDetails = $this->societyCashBalanceDetails();

        $cashBalanceHeadSubCategoryId = isset($cashBalanceDetails['SocietyHeadSubCategory']['id']) ? $cashBalanceDetails['SocietyHeadSubCategory']['id'] :'';

        $societyCashBalanceHeadsLists = $societyLedgerHeadsObj->find('list', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'),'SocietyLedgerHeads.society_id' => $societyLoginID,'SocietyLedgerHeads.society_head_sub_category_id'=>$cashBalanceHeadSubCategoryId),'fields' => array('SocietyLedgerHeads.title'), 'order' => array('SocietyLedgerHeads.title' => 'asc')));

        if($societyCashBalanceHeadsLists){

            return $societyCashBalanceHeadsLists;

        }

        return array();

    }

    

    public function societyBankBalanceDetails(){

        App::import('model','SocietyHeadSubCategory');

        $societyHeadSubCategoryObj = new SocietyHeadSubCategory();

        $BankBalanceDetails = $societyHeadSubCategoryObj->find('first',array('conditions'=>array('SocietyHeadSubCategory.title LIKE'=>"%Bank Balances%",'SocietyHeadSubCategory.status'=>Configure::read('Active')),'order' =>array('SocietyHeadSubCategory.id' =>'asc'),'recursive'=>-1));

        if(isset($BankBalanceDetails['SocietyHeadSubCategory']) && !empty($BankBalanceDetails['SocietyHeadSubCategory'])){

            return $BankBalanceDetails;

        }

        return array();

    }

    

    public function societyCashBalanceDetails(){

        App::import('model','SocietyHeadSubCategory');

        $societyHeadSubCategoryObj = new SocietyHeadSubCategory();

        $CashBalanceDetails = $societyHeadSubCategoryObj->find('first',array('conditions'=>array('SocietyHeadSubCategory.title LIKE'=>"%Cash Balance%",'SocietyHeadSubCategory.status'=>Configure::read('Active')),'order' =>array('SocietyHeadSubCategory.id' =>'asc'),'recursive'=>-1));

        if(isset($CashBalanceDetails['SocietyHeadSubCategory']) && !empty($CashBalanceDetails['SocietyHeadSubCategory'])){

            return $CashBalanceDetails;

        }

        return array();

    }

    

    public function getSocietyDetails(){

        App::import('model', 'Society');

        $societyObj = new Society();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyDetails = $societyObj->find('first',array('conditions' => array('Society.id' => $societyLoginID,'Society.status' => Configure::read('Active')),'order' => array('Society.id' => 'asc')));


        if(!empty($societyDetails)){

            return $societyDetails;

        }

        return array();

    }

    

    public function getMemberDetailsByMemberIDAndFlatNo($fieldValue=null){

        App::import('model', 'Member');

        $societyMemberObj = new Member();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyMemberLists = $societyMemberObj->find('all',array('conditions' => array('Member.status' =>Configure::read('Active'),'Member.society_id' => $societyLoginID,'OR'=>array('Member.id'=>$fieldValue,'Member.flat_no'=>$fieldValue)),'order' => array('Member.floor_no'=>'asc','Member.flat_no'=>'asc')));

        if (count($societyMemberLists) > 0) {

            return $societyMemberLists;

        }

        return array();

    }

    

    public function getTariffDetailsBySocietyParameterTariffType($tariffType = null,$memberDetails = array()) {

        switch ($tariffType) {

            case Configure::read('TariffType.Building'):

                App::import('model','SocietyTariffBuildings');

                $societyTariffBuildingsObj = new SocietyTariffBuildings();

                $societyLoginID = $this->Session->read('Auth.User.id');

                $conditions = array('SocietyTariffBuildings.society_id' => $societyLoginID,'SocietyTariffBuildings.amount >' => 0);

                if(isset($memberDetails['building_id']) && !empty($memberDetails['building_id'])){

                    $conditions['SocietyTariffBuildings.building_id'] = $memberDetails['building_id'];

                }

                $societyBuildingTariffDetails = $societyTariffBuildingsObj->find('all', array('conditions' => $conditions));

                if (isset($societyBuildingTariffDetails) && !empty($societyBuildingTariffDetails)) {

                    return $societyBuildingTariffDetails;

                }

                return array();

                exit;

                break;

            case Configure::read('TariffType.Wing') :

                App::import('model', 'SocietyTariffWings');

                $societyTariffWingsObj = new SocietyTariffWings();

                $societyLoginID = $this->Session->read('Auth.User.id');

                $conditions = array('SocietyTariffWings.society_id' => $societyLoginID, 'SocietyTariffWings.amount >' => 0);

                if (isset($memberDetails['wing_id']) && !empty($memberDetails['wing_id'])) {

                    $conditions['SocietyTariffWings.building_id'] = $memberDetails['building_id'];

                    $conditions['SocietyTariffWings.wing_id'] = $memberDetails['wing_id'];

                }

                $societyTariffWingsDetails = $societyTariffWingsObj->find('all', array('conditions' => $conditions));

                if (isset($societyTariffWingsDetails) && !empty($societyTariffWingsDetails)) {

                    return $societyTariffWingsDetails;

                }

                return array();

                exit;

                break;

            case Configure::read('TariffType.UnitNo'):

            case Configure::read('TariffType.PerPerson'):    

                App::import('model', 'MemberTariff');

                $societyMemberTariffObj = new MemberTariff();

                $societyLoginID = $this->Session->read('Auth.User.id');

                $conditions = array('MemberTariff.society_id' => $societyLoginID, 'MemberTariff.amount >' => 0, 'SocietyLedgerHeads.is_in_bill_charges' => 1);

                if (isset($memberDetails['id']) && !empty($memberDetails['id'])) {

                    $conditions['MemberTariff.member_id'] = $memberDetails['id'];

                }

                $societyMemberTariffDetails = $societyMemberTariffObj->find('all', array('fields' => array('MemberTariff.society_id', 'MemberTariff.member_id', 'MemberTariff.ledger_head_id', 'MemberTariff.amount', 'MemberTariff.tariff_serial', 'SocietyLedgerHeads.title', 'SocietyLedgerHeads.id', 'SocietyLedgerHeads.is_in_bill_charges', 'Member.id', 'Member.member_name', 'Member.flat_no'), 'conditions' => $conditions));

                if (isset($societyMemberTariffDetails) && !empty($societyMemberTariffDetails)) {

                    return $societyMemberTariffDetails;

                }

                return array();

                exit;

                exit;

                break;

            case Configure::read('TariffType.UnitType') :

                App::import('model', 'SocietyTariffUnitTypes');

                $societyTariffUnitTypesObj = new SocietyTariffUnitTypes();

                $societyLoginID = $this->Session->read('Auth.User.id');

                $unityTypeCondition = array('SocietyTariffUnitTypes.society_id' => $societyLoginID);

                if (isset($memberDetails['unit_type']) && !empty($memberDetails['unit_type'])) {

                    $unityTypeCondition['SocietyTariffUnitTypes.unit_type'] = $memberDetails['unit_type'];

                }

                $societyTariffUnitTypesDetails = $societyTariffUnitTypesObj->find('all', array('conditions' => $unityTypeCondition));

                if (isset($societyTariffUnitTypesDetails) && !empty($societyTariffUnitTypesDetails)) {

                    return $societyTariffUnitTypesDetails;

                }

                return array();

                exit;

                break;

            case Configure::read('TariffType.TotalArea'):

                App::import('model', 'SocietyTariffTotalAreas');

                $societyTariffTotalAreasObj = new SocietyTariffTotalAreas();

                $societyLoginID = $this->Session->read('Auth.User.id');

                $societyTotalAreasTariffDetails = $societyTariffTotalAreasObj->find('all', array('conditions' => array('SocietyTariffTotalAreas.society_id' => $societyLoginID)));

                if (isset($societyTotalAreasTariffDetails) && !empty($societyTotalAreasTariffDetails)) {

                    return $societyTotalAreasTariffDetails;

                }

                return array();

                exit;

                break;

            case Configure::read('TariffType.PerUnitArea'):

                App::import('model', 'SocietyTariffPerAreas');

                $societyTariffPerAreasObj = new SocietyTariffPerAreas();

                $societyLoginID = $this->Session->read('Auth.User.id');

                $societyTariffPerAreasDetails = $societyTariffPerAreasObj->find('all', array('conditions' => array('SocietyTariffPerAreas.MemberTariff' => $societyLoginID)));

                if (isset($societyTariffPerAreasDetails) && !empty($societyTariffPerAreasDetails)) {

                    return $societyTariffPerAreasDetails;

                }

                return array();

                exit;

                break;

            default:

                return array();

                break;

        }

    }

    

    public function checkPaymentModeBySocietyLedgerId($societyLedgerID,$returnPaymentID=false){

        App::import('model', 'SocietyLedgerHeads');

        $societyLedgerHeadsObj = new SocietyLedgerHeads();

        $societyLedgerData = $societyLedgerHeadsObj->find('first', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'),'SocietyLedgerHeads.id'=>$societyLedgerID),'recursive' => -1));

        if (isset($societyLedgerData['SocietyLedgerHeads']['title']) && !empty($societyLedgerData['SocietyLedgerHeads']['title'])) {

            if(stripos($societyLedgerData['SocietyLedgerHeads']['title'],'Cash') !== FALSE) {

                   $paymentMode = 'Cash';

                } else {

                   $paymentMode = 'Cheque'; 

                }

                $PaymentModeLists = $this->getSocietyPaymentModeLists();

                if($returnPaymentID){

                    foreach($PaymentModeLists as $paymentID =>$paymentModeValue){

                        if($paymentModeValue == $paymentMode){

                            $paymentMode = $paymentID;

                            break;

                        }

                    }

                }

           return $paymentMode;     

        }

        return false;

    }

    

    public function getCashInHandSocietyLedgerHeadId(){

        App::import('model', 'SocietyLedgerHeads');

        $societyLedgerHeadsObj = new SocietyLedgerHeads();

        $cashInHandLedgerDetails = $societyLedgerHeadsObj->find('first',array('conditions'=>array('SocietyLedgerHeads.title LIKE'=>"Cash in hand",'SocietyLedgerHeads.status'=>Configure::read('Active'),'SocietyLedgerHeads.society_id'=>$this->Session->read('Auth.User.id')),'order' =>array('SocietyLedgerHeads.id' => 'asc'),'recursive'=>-1));

        if(isset($cashInHandLedgerDetails['SocietyLedgerHeads']) && !empty($cashInHandLedgerDetails['SocietyLedgerHeads'])){

            return $cashInHandLedgerDetails;

        }

        return array();

    }

    public function getFinancialYearFromDate(){
        $FinancialYear = $this->Session->read('Auth.start_year');
        $EndYear = $this->Session->read('Auth.end_year');
        
        $FinancialYearDate = $FinancialYear.'-'.substr($EndYear,2,3);
        
        // $checkDate = $FinancialYear . '-' . date('m-d');
        // $FinancialYearDate = $this->Util->getFinancialYearFromDate($checkDate);

        return $FinancialYearDate;        
        
    }

    public function getFinancialYearFromDatebk(){

        App::import('model', 'Society');

        $societyObj = new Society();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyObj->id = $societyLoginID;

        $FinancialYear = $societyObj->field('financial_year');

        $checkDate = $FinancialYear . '-' . date('m-d');

        $FinancialYearDate = $this->Util->getFinancialYearFromDate($checkDate);

        return $FinancialYearDate;

    }

    

    public function billVoucherNumber(){

        App::import('model', 'SocietyPayment');

        $societyPaymentObj = new SocietyPayment();

        $billVoucherNumber = 1;

        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Util->getFinancialYear();

        $billVoucherArr = $societyPaymentObj->find('all',array('conditions' => 
        array('SocietyPayment.society_id' =>$societyLoginID,'SocietyPayment.financial_year_id' =>$financialYearId ),'fields' => array('MAX(cast(SocietyPayment.bill_voucher_number as unsigned)) as max_bill_voucher'),'recursive' => 1));

        if(isset($billVoucherArr[0][0]['max_bill_voucher']) && $billVoucherArr[0][0]['max_bill_voucher'] >= 0){

            $billVoucherNumber = $billVoucherArr[0][0]['max_bill_voucher']+1;

        }

        

        return $billVoucherNumber;

    }



public function generalReceiptNumber(){

        App::import('model', 'SocietyPaymentOtherIncome');

        $societyPaymentObj = new SocietyOtherIncome();

        $billVoucherNumber = 1;

        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Util->getFinancialYear();

        $billVoucherArr = $societyPaymentObj->find('all',
        array('conditions' => array('SocietyOtherIncome.society_id' =>$societyLoginID,'SocietyOtherIncome.financial_year_id' => $financialYearId),
        'fields' => array('MAX(SocietyOtherIncome.general_receipt_number) as max_bill_voucher'),'recursive' => 1));

        if(isset($billVoucherArr[0][0]['max_bill_voucher']) && $billVoucherArr[0][0]['max_bill_voucher'] >= 0){

            $billVoucherNumber = $billVoucherArr[0][0]['max_bill_voucher']+1;

        }

        return $billVoucherNumber;

    }

    

    public function memberPaymentReceiptUniqueNumber(){

        App::import('model', 'MemberPayment');

        $memberPaymentObj = new MemberPayment();

        $paymentReceiptNumber = 1;

        $societyLoginID = $this->Session->read('Auth.User.id');
        
        $financialYearId = $this->Util->getFinancialYear();
        $billReceiptArr = $memberPaymentObj->find('all',array('conditions' => array('MemberPayment.society_id' =>$societyLoginID,'MemberPayment.financial_year_id' => $financialYearId),
        'fields' => array('MAX(MemberPayment.receipt_id) as max_receipt_number'),'recursive' => 1));

        if(isset($billReceiptArr[0][0]['max_receipt_number']) && $billReceiptArr[0][0]['max_receipt_number'] >= 0){

            $paymentReceiptNumber = $billReceiptArr[0][0]['max_receipt_number']+1;

        }

        return $paymentReceiptNumber;

    }

    

    public function memberBillsUniqueNumber($billStartingNo=1){

        App::import('model', 'MemberBillSummary');

        $memberMemberBillSummaryObj = new MemberBillSummary();

        //$billNumber = 1;

        $billNumber = empty($billStartingNo) ? 1 : $billStartingNo;

        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Util->getFinancialYear();

        $billReceiptArr = $memberMemberBillSummaryObj->find('all',array('conditions' => array('MemberBillSummary.society_id' =>$societyLoginID ,'MemberBillSummary.financial_year_id' => $financialYearId),
        'fields' => array('MAX(MemberBillSummary.bill_no) as bill_number'),'recursive' => 1));


        if(isset($billReceiptArr[0][0]['bill_number']) && $billReceiptArr[0][0]['bill_number'] >= 0){

            $billNumber = $billReceiptArr[0][0]['bill_number']+1;

        }

        return $billNumber;

    }

    

    public function monthWordFormatByBillingFrequency($monthNo=null){
        $societyParameters = $this->getSocietyParameters();
        if (isset($societyParameters['SocietyParameter'])) {
            $societyBillingFrequencyId = $societyParameters['SocietyParameter']['billing_frequency_id'];
            $billingFrequenciesArray = $this->Util->societyBillingFrequency($societyBillingFrequencyId);
            if (isset($billingFrequenciesArray[$monthNo])){
                return $billingFrequenciesArray[$monthNo];
            }
        }
        return false;
    }

    

    public function numberTowords($num)

    {

        $num = str_replace('-','',$num);

        

        error_reporting(0);

        $ones = array(

        1 => "one",

        2 => "two",

        3 => "three",

        4 => "four",

        5 => "five",

        6 => "six",

        7 => "seven",

        8 => "eight",

        9 => "nine",

        10 => "ten",

        11 => "eleven",

        12 => "twelve",

        13 => "thirteen",

        14 => "fourteen",

        15 => "fifteen",

        16 => "sixteen",

        17 => "seventeen",

        18 => "eighteen",

        19 => "nineteen"

        );

        $tens = array(

        1 => "ten",

        2 => "twenty",

        3 => "thirty",

        4 => "forty",

        5 => "fifty",

        6 => "sixty",

        7 => "seventy",

        8 => "eighty",

        9 => "ninety"

        );

        $hundreds = array(

        "hundred",

        "thousand",

        "million",

        "billion",

        "trillion",

        "quadrillion"

        ); //limit t quadrillion

        $num = number_format($num,2,".",",");

        $num_arr = explode(".",$num);

        $wholenum = $num_arr[0];

        $decnum = $num_arr[1];

        $whole_arr = array_reverse(explode(",",$wholenum));

        krsort($whole_arr);

        $rettxt = "";

        foreach($whole_arr as $key => $i){

        if($i < 20){

        $rettxt .= $ones[$i];

        }elseif($i < 100){

        $rettxt .= $tens[substr($i,0,1)];

        $rettxt .= " ".$ones[substr($i,1,1)];

        }else{

        $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0];

        $rettxt .= " ".$tens[substr($i,1,1)];

        $rettxt .= " ".$ones[substr($i,2,1)];

        }

        if($key > 0){

        $rettxt .= " ".$hundreds[$key]." ";

        }

        }

        if($decnum > 0){

        $rettxt .= " and ";

        if($decnum < 20){

        $rettxt .= $ones[$decnum];

        }elseif($decnum < 100){

        $rettxt .= $tens[substr($decnum,0,1)];

        $rettxt .= " ".$ones[substr($decnum,1,1)];

        }

        }

        return $rettxt;

    }

    

     public function getAllBankList(){

        App::import('model', 'Bank');

        $bankObj = new Bank();

        $societyLoginID = $this->Session->read('Auth.User.id');

        $bankLists = $bankObj->find('all',array('order'=>array('Bank.id asc'))); 

        if(isset($bankLists) && count($bankLists) > 0){

            return $bankLists;

        }

        return array();

    }

    

    public function multiplyTariffAmountByBillingFrequency($societyParameters=array()){
        $societyParameters = $this->getSocietyParameters();
        
        $multiplyBillingValue = 1;
        if (isset($societyParameters['SocietyParameter']['is_tariff_mothly']) && $societyParameters['SocietyParameter']['is_tariff_mothly'] == 1){
            switch ($societyParameters['SocietyParameter']['billing_frequency_id']) {
                case Configure::read('BillingFrequency.Monthly'):
                    $multiplyBillingValue = 1;
                    break;
                case Configure::read('BillingFrequency.BiMonthly') :
                    $multiplyBillingValue = 2;
                    break;
                case Configure::read('BillingFrequency.Quarterly'):
                    $multiplyBillingValue = 3;
                    break;
                case Configure::read('BillingFrequency.Quadruple') :
                    $multiplyBillingValue = 4;
                    break;
                case Configure::read('BillingFrequency.HalfYearly'):
                    $multiplyBillingValue = 6;
                    break;
                case Configure::read('BillingFrequency.Yearly'):
                    $multiplyBillingValue = 12;
                    break;
                default:
                    $multiplyBillingValue = 1;
                    break;
            }
        }
        
        return $multiplyBillingValue;
    }
        public function societyOtherIncomeHeadsLists() {

        App::import('model', 'SocietyLedgerHeads');

        $societyLoginID = $this->Session->read('Auth.User.id');

        $societyLedgerHeadsObj = new SocietyLedgerHeads();

        $societyOtherIncomeHeadsLists = $societyLedgerHeadsObj->find('list', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID), 'fields' => array('SocietyLedgerHeads.title'), 'order' => array('SocietyLedgerHeads.title' => 'asc')));

        //$societyExpenseLedgerHeadsLists = $societyHeadSubCategoryObj->find('list',array('fields' => array('id','title'),'conditions' => array('SocietyHeadSubCategory.status' => Configure::read('Active'), 'SocietyHeadSubCategory.society_id' => $societyLoginID, 'SocietyHeadSubCategory.account_head_id' => 4),'recursive' => -1));

        if($societyOtherIncomeHeadsLists){

            return $societyOtherIncomeHeadsLists;

        }

    }

    

    public function memberJournalVoucherUniqueNumber(){

        App::import('model', 'JournalVoucher');

        $journalVoucherObj = new JournalVoucher();

        $journalVoucherNumber = 1;

        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Util->getFinancialYear();

        $billReceiptArr = $journalVoucherObj->find('all',array('conditions' => array('JournalVoucher.society_id' =>$societyLoginID ,'JournalVoucher.financial_year_id' => $financialYearId),
        'fields' => array('MAX(JournalVoucher.voucher_no) as max_voucher_number'),'recursive' => 1));

        if(isset($billReceiptArr[0][0]['max_voucher_number']) && $billReceiptArr[0][0]['max_voucher_number'] >= 0){

            $journalVoucherNumber = $billReceiptArr[0][0]['max_voucher_number']+1;

        }

        return $journalVoucherNumber;

    }

    

    public function getJournalVoucherCreaditDebitDataByLedgerHeadId($ledgerHeadId = ""){

        App::import('model', 'JournalVoucher');

        $journalVoucherObj = new JournalVoucher();

        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Util->getFinancialYear();

        $options['AND'] = array('JournalVoucher.society_id' => $societyLoginID);
        $options['AND'] = array('JournalVoucher.financial_year_id' => $financialYearId);

        $options['AND']['OR'][] = array('JournalVoucher.jv_debit_ledger_head_id' => $ledgerHeadId);

        $options['AND']['OR'][] = array('JournalVoucher.jv_credit_ledger_head_id' => $ledgerHeadId);
        

        

        $journalVoucherLedgerPaymentData = $journalVoucherObj->find('all', array('conditions' => $options,'order' => array('JournalVoucher.voucher_date' => 'asc'),'recursive'=>-1));

        

        if(isset($journalVoucherLedgerPaymentData) && !empty($journalVoucherLedgerPaymentData)){

           return $journalVoucherLedgerPaymentData;

        }

        return array();

    }

    

    

    public function getJournalVoucherCreaditDebitDataByMemberId($memberId = "",$memberTransfer = ""){

        App::import('model', 'JournalVoucher');
        $journalVoucherObj = new JournalVoucher();

        $fincancialYearDates = $this->Session->read('Auth.financial_years_arr');
        
        $societyLoginID = $this->Session->read('Auth.User.id');
        
        // Did the caller name a specific transfer generation? (before it is
        // normalised to 0 below). Only then do we scope vouchers to it.
        $filterByMemberTransfer = ($memberTransfer !== "" && $memberTransfer !== null);
        if($memberTransfer==''){
            $memberTransfer=0;
        }
        
        $options['AND'] = array('JournalVoucher.society_id' => $societyLoginID);
        $options['AND']= array('JournalVoucher.member_transfer' => $memberTransfer);

        // $options['AND']['OR'][] = array('JournalVoucher.jv_debit_member_head_id' => $memberId);
        // $options['AND']['OR'][] = array('JournalVoucher.jv_credit_member_head_id' => $memberId);
        $financialYearId = $this->Util->getFinancialYear();
        $options = array('JournalVoucher.society_id' => $societyLoginID, 'JournalVoucher.financial_year_id' =>$financialYearId );
        // A transferred member shares one id across owners; when a generation is
        // named, return only that owner's vouchers so old and new member ledgers
        // do not bleed into each other. Callers that pass none stay unfiltered.
        if ($filterByMemberTransfer) {
            $options['JournalVoucher.member_transfer'] = $memberTransfer;
        }
        
        $journalVoucherMemberPaymentData = $journalVoucherObj->find('all', array('conditions' => $options,
        'order' => array('JournalVoucher.voucher_date' => 'asc'), 'recursive' => -1));


        if (isset($journalVoucherMemberPaymentData) && !empty($journalVoucherMemberPaymentData)) {

            return $journalVoucherMemberPaymentData;

        }

        return array();

    }

    public function memberBillNumberByMonth($memberId='',$billMonth=''){

	if ($billMonth < 10)

		$billMonth=str_replace('0','',$billMonth);

        App::import('model', 'MemberBillSummary');
        $memberMemberBillSummaryObj = new MemberBillSummary();

        $billNumber = 1;

        $societyLoginID = $this->Session->read('Auth.User.id');

        $billReceiptArr = $memberMemberBillSummaryObj->find('first',array('conditions' => array('MemberBillSummary.society_id' =>$societyLoginID,'MemberBillSummary.member_id' =>$memberId,'MemberBillSummary.month'=>$billMonth),'fields' => array('MemberBillSummary.bill_no as bill_no'),'recursive' => -1));

        return $billReceiptArr;

    }
    
    public function getSocietyOldMeberFlatList(){        
        $societyLoginID = $this->Session->read('Auth.User.id');
        $sql =  "select  third_member,member_id,flat_no from member_identifications where id in (
                select max(id)  from  member_identifications where member_id in (
                    SELECT id FROM `members` where member_transfer > 0 and society_id = $societyLoginID and status = 1
                ) group by member_id)";
        $db = connectionManager::getDataSource('default');
        return  $db->query($sql);
    }  
    
    public function setMemberClosingBalance($memberLastBillSummaryData,&$closingBalanceData,$billType='reg'){
        if($this->Session->check('Auth.year_id')){
            App::import('model', 'YearWiseClosingBalance');
            $yearWiseClosingObj = new YearWiseClosingBalance();
            $yearId = $this->Session->read('Auth.year_id');
            $memberId = $memberLastBillSummaryData['member_id'];
            $memberTransfer = $memberLastBillSummaryData['member_transfer'];
            $yearClosingData = $yearWiseClosingObj->find('first',array('conditions'=> array('YearWiseClosingBalance.member_id' => $memberId,'YearWiseClosingBalance.year_id' => $yearId,'YearWiseClosingBalance.member_transfer' => $memberTransfer,'YearWiseClosingBalance.bill_type' => $billType)));
            $balanceAmt = $memberLastBillSummaryData['balance_amount'];
            $closingBalance = [
                'year_id' => $yearId,
                'bill_type' => $billType,
                'society_id' => $memberLastBillSummaryData['society_id'],
                'member_id' => $memberId,
                'principal_balance' => ( $balanceAmt < 0) ? $balanceAmt : $memberLastBillSummaryData['principal_balance'],
                'interest_balance' => $memberLastBillSummaryData['interest_balance'],
                'tax_balance' => $memberLastBillSummaryData['tax_balance'],
                'member_transfer' => $memberLastBillSummaryData['member_transfer'],
                'bill_generated_date' => $memberLastBillSummaryData['bill_generated_date'],
                'bill_due_date' => $memberLastBillSummaryData['bill_due_date'],
                'bill_end_date' => $memberLastBillSummaryData['bill_end_date'],
                'financial_year_id' => $memberLastBillSummaryData['financial_year_id']
                ];
    
            if(!empty($yearClosingData)){
                $closingBalance['updated_at'] = date('Y-m-d H:i:s');
                $closingBalance['id'] = $yearClosingData['YearWiseClosingBalance']['id'];
            }else{
                $closingBalance['created_at'] = date('Y-m-d H:i:s');
            }
            array_push($closingBalanceData,$closingBalance);
        }
        return true;
    }    
    
    public function closingBalanceUpdateAfterBillUpdate($memberId,$billType,$latestTransferNo){
            $societyLoginID = $this->Session->read('Auth.User.id');
            App::import('model', 'MemberBillSummary');
            
            App::import('model', 'YearWiseClosingBalance');
            $yearWiseClosingObj = new YearWiseClosingBalance();        
            
            $financialYearId = $this->Session->read('Auth.year_id');
            $memberMemberBillSummaryObj = new MemberBillSummary();
            $lstbillSummary = $memberMemberBillSummaryObj->find('first',array('conditions' => array('MemberBillSummary.society_id' =>$societyLoginID,'MemberBillSummary.member_id' =>$memberId,'MemberBillSummary.bill_type' =>$billType,'MemberBillSummary.member_transfer' => $latestTransferNo ,'MemberBillSummary.financial_year_id'=>$financialYearId),'order' => array('MemberBillSummary.id' => 'desc')));        
            
            $closingBalanceData=[];
            if(!empty($lstbillSummary)){ 
                $this->setMemberClosingBalance($lstbillSummary['MemberBillSummary'],$closingBalanceData); 
                if(isset($closingBalanceData[0]))
                    $yearWiseClosingObj->save($closingBalanceData[0]);
            }
    }
    
    public function getPreviousYearBalance($memberId,$transferStautsId=0){
        $closingBalanceData = [];
        if($this->Session->check('Auth.year_id')){
            App::import('model', 'YearWiseClosingBalance');
            $yearWiseClosingObj = new YearWiseClosingBalance();
            $yearId = $this->Session->read('Auth.year_id');
            $yearId = $yearId-1;
            $yearClosingData = $yearWiseClosingObj->find('first',array('conditions'=> array('YearWiseClosingBalance.member_id' => $memberId,'YearWiseClosingBalance.year_id' => $yearId,'YearWiseClosingBalance.member_transfer' => $transferStautsId)));        
            if(!empty($yearClosingData)){
                $closingBalanceData =  $yearClosingData['YearWiseClosingBalance']   ;
            }
        }
        return $closingBalanceData;
    }
    
    public function getMemberOpBalanceSocietyWise($societyId,$billType='reg'){

        $closingBalanceData = [];
        
        if($this->Session->check('Auth.year_id')){
            App::import('model', 'YearWiseClosingBalance');
            $yearWiseClosingObj = new YearWiseClosingBalance();
            $yearId = $this->Session->read('Auth.year_id');
            $yearId = $yearId-1;

            $yearClosingData = $yearWiseClosingObj->find('all',array('conditions'=> array('YearWiseClosingBalance.society_id' => $societyId,'YearWiseClosingBalance.year_id' => $yearId,'bill_type'=>$billType))); 
         //   print_r($yearClosingData);die;       
            if(!empty($yearClosingData)){
                foreach ($yearClosingData as $key => $data){
                    $closingBalanceData[$data['YearWiseClosingBalance']['member_id']]['principal_balance'] =  $data['YearWiseClosingBalance']['principal_balance'];
                    $closingBalanceData[$data['YearWiseClosingBalance']['member_id']]['interest_balance'] =  $data['YearWiseClosingBalance']['interest_balance'];
                    $closingBalanceData[$data['YearWiseClosingBalance']['member_id']]['tax_balance'] =  $data['YearWiseClosingBalance']['tax_balance'];  
                }
                //$closingBalanceData =  $yearClosingData['YearWiseClosingBalance']   ;
            }
            return $closingBalanceData;
        }
    }

    public function setFinancialYearArr(){
        return $this->Session->read('Auth.financial_years_arr');
    }    
    
    public function getGeneratedBillMonths(){
        $societyMemberbillDataWithMonthName = [];
        $societyLoginID = $this->Session->read('Auth.User.id');
        App::import('model', 'MemberBillSummary');
        $memberSummaryObj = new MemberBillSummary();        
        $options['AND'] = array('MemberBillSummary.society_id' => $societyLoginID);
        $societyMemberbillData = $memberSummaryObj->find('all', array('conditions' => $options, 'fields' => array('MemberBillSummary.month', 'MemberBillSummary.member_id','MemberBillSummary.bill_frequency_id'),'order' => array('MemberBillSummary.bill_no')));
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

        return $societyMemberbillDataWithMonthName;
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

    public function memberTransferList($societyId){
        $memberObj = new Member();   
        $memberObj->recursive = -1;
         
        $financialYearId = $this->Session->read('Auth.year_id');

        $memberTransferList = $memberObj->find('all', array(
            'joins' => array(
                array(
                    'table' => 'member_identifications',
                    'alias' => 'iden',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'iden.member_id = Member.id'
                    )
                )
            ),
            'conditions' => array(
                'Member.society_id' => $societyId,
                'iden.financial_year_id' => $financialYearId
            ),
            'fields' => array('Member.id','Member.member_transfer', 'iden.member_id','iden.financial_year_id'),
        ));    

        $transferfinalArray = [];
        if(!empty($memberTransferList)){
            foreach($memberTransferList as $data){
                    $transferfinalArray[$data['Member']['id']] = array_merge($data['Member'],$data['iden']);
            }
        }

        return $transferfinalArray;
    }

    public function memberOpenningBalanceOTTransfer(){
        $financialYearId = $this->Session->read('Auth.year_id');
        $societyLoginID = $this->Session->read('Auth.User.id');
        $yearId = $financialYearId - 1;

        $insertClosingBalanceRegularSql = "insert into `member_year_wise_closing_balance` (`year_id`, `society_id`, `member_id`, `principal_balance`, `interest_balance`, `tax_balance`, `bill_type`,`created_at`) SELECT  $yearId,`society_id`, `id`, op_principal,op_interest, op_tax,'reg',now() FROM `members` WHERE society_id = $societyLoginID and `status`=1";
       $db = connectionManager::getDataSource('default');
       $lastInsertIds = $db->query($insertClosingBalanceRegularSql);
       $lastInsertId=$db->lastInsertId();


        $insertClosingBalanceSupSql = "insert into `member_year_wise_closing_balance` (`year_id`, `society_id`, `member_id`, `principal_balance`, `interest_balance`, `tax_balance`, `bill_type`,`created_at`) SELECT  $yearId,`society_id`, `id`, supplementary_principal,supplementary_interest, supplementary_tax,'sup',now() FROM `members` WHERE society_id = $societyLoginID and `status`=1";
      
       $lastInsertIds = $db->query($insertClosingBalanceSupSql);

       $lastInsertId=$db->lastInsertId();
       if (!empty($lastInsertId)){
            return true;
       }
       else{
            return false;
       }
    }

    public function ledgerOpeningBalanceOTTransfer($yearId=0,$societyId=0){
        if (empty($yearId))

            $yearId = $this->Session->read('Auth.year_id');

        if (empty($societyId))
            $societyId = $this->Session->read('Auth.User.id');

        $sql = "insert into society_ledger_heads_opening_year_wise(`financial_year_id`,`society_id`,`ledger_head_id`,`account_category_id`,`balance_amount`,`created_at`) select $yearId,$societyId,`id`,`account_category_id`,`opening_amount`,now() from society_ledger_heads where `society_id`= $societyId";
        $db = connectionManager::getDataSource('default');
        $lastInsertIds = $db->query($sql);

        $lastInsertId=$db->lastInsertId();
        if (!empty($lastInsertId)){
            return true;
        }
        else{
            return false;
       }
    }
    public function ledgerHeadTransfer($ledgerClosingData=array(),$ledger_head_ids=array()){
        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');
 

        //$db = connectionManager::getDataSource('default');
        App::import('model', 'LedgerHeadOpeningModel');
        $ledgerHeadOpening = new LedgerHeadOpeningModel();
       

        $existingData = $ledgerHeadOpening->find('all', array('conditions'=>array('ledger_head_id'=>$ledger_head_ids,'society_id'=>$societyLoginID, 'financial_year_id'=>$financialYearId),'recursive'=>-1));

      //  print_r($existingData);
        if (!empty($existingData)){
          //  print_r($existingData);
            foreach ($existingData as $key => $ledgerData)
            {
                $ledgerClosingData[$ledgerData['LedgerHeadOpeningModel']['ledger_head_id']]['id']=$ledgerData['LedgerHeadOpeningModel']['id'];
            }
        }

//print_r($ledgerClosingData);
        $ledgerHeadOpening->saveAll($ledgerClosingData,['updateFields'=>['op_credit','op_debit','balance_amount'],'updateModifiedFields'=>true]);

    }
    public function addUpdateLedgerOpBalance($ledgerPostData){

        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id')-1;
        $ledgerDataUpdate = array();

        App::import('model', 'LedgerHeadOpeningModel');
        $ledgerHeadOpening = new LedgerHeadOpeningModel();

        $existingData = $ledgerHeadOpening->find('all', array('conditions'=>array('ledger_head_id'=>$ledgerPostData['id'],'society_id'=>$societyLoginID, 'financial_year_id'=>$financialYearId),'recursive'=>-1));

        if (!empty($existingData)){
            foreach ($existingData as $key => $data){
                $existingData[$key]['LedgerHeadOpeningModel']['balance_amount']=$ledgerPostData['opening_amount'];
                if ($ledgerPostData['account_category_id'] == '2')  #Asset
                    $existingData[0]['LedgerHeadOpeningModel']['op_debit']=$ledgerPostData['opening_amount'];
                else if ($ledgerPostData['account_category_id'] == '1')
                     $existingData[0]['LedgerHeadOpeningModel']['op_credit']=$ledgerPostData['opening_amount'];
            }
            $ledgerDataUpdate = $existingData;
        }
        else
        {
            $ledgerDataUpdate['financial_year_id'] = $financialYearId;
            $ledgerDataUpdate['society_id'] = $societyLoginID;
            $ledgerDataUpdate['ledger_head_id'] = $ledgerPostData['id'];
            $ledgerDataUpdate['account_category_id'] = $ledgerPostData['account_category_id']; 
            if ($ledgerPostData['account_category_id'] == '2')  #Asset
                    $ledgerDataUpdate['op_debit']=$ledgerPostData['opening_amount'];
                else if ($ledgerPostData['account_category_id'] == '1')
                     $ledgerDataUpdate['op_credit']=$ledgerPostData['opening_amount'];

            
            $ledgerDataUpdate['balance_amount'] = $ledgerPostData['opening_amount'];
            $ledgerDataUpdate['created_at'] = date('Y-m-d H:i:s');

        }

        /*if (!empty($existingData)){
            foreach ($existingData as $key => $ledgerData)
            {
                $ledgerDataUpdate = $ledgerData[$key]['LedgerHeadOpeningModel'];
            }
        }*/
        //print_r($existingData);die;
        $ledgerHeadOpening->saveAll($ledgerDataUpdate,['updateFields'=>['op_credit','op_debit','balance_amount'],'updateModifiedFields'=>true]);
    }
    public function memberClosingBalanceTransfer($memberIds=array())
    {
        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');

        $db = connectionManager::getDataSource('default');
        App::import('model', 'YearWiseClosingBalance');
        $yearWiseClosingBalance = new YearWiseClosingBalance();


        $memberPaymentBalance = $this->getMemberWiseClosingBalance('reg',1); #call for save
        $memberPaymentSupBalance = $this->getMemberWiseClosingBalance('sup',1);#call for save

        $existingData = $yearWiseClosingBalance->find('all', array('fields' => array('member_id','id'),'conditions'=>array('member_id'=>$memberIds,'society_id'=>$societyLoginID, 'year_id'=>$financialYearId,'bill_type'=>'reg'),'recursive'=>-1));
        
		
        if (!empty($existingData)){
          //  print_r($existingData);
            foreach ($existingData as $key => $memberData)
            {
                $memberPaymentBalance[$memberData['YearWiseClosingBalance']['member_id']]['id']=$memberData['YearWiseClosingBalance']['id'];
            }
            
        }
		
		
      
		foreach ($memberPaymentBalance as $key=>$details){
			$sum = $details['principal_balance']+$details['interest_balance']+$details['tax_balance'];
				
			if (($sum != $details['balance_amount']) && $details['balance_amount'] < 0)
			{
				//echo "<br>".$key."--".$details['principal_balance']." ".$details['interest_balance']." ".$details['tax_balance']." ".$details['balance_amount'];
				$memberPaymentBalance[$key]['principal_balance'] = $details['balance_amount'];
			}
		}

        $yearWiseClosingBalance->saveAll($memberPaymentBalance,['updateFields'=>['principal_balance','interest_balance','tax_balance'],'updateModifiedFields'=>true]);


        unset($existingData);
        $existingData = $yearWiseClosingBalance->find('all', array('fields' => array('member_id','id'),'conditions'=>array('member_id'=>$memberIds,'society_id'=>$societyLoginID, 'year_id'=>$financialYearId,'bill_type'=>'sup'),'recursive'=>-1));
       
        //print_r($memberPaymentBalance);die;
        if (!empty($existingData)){
          //  print_r($existingData);
            foreach ($existingData as $key => $memberData)
            {
                $memberPaymentSupBalance[$memberData['YearWiseClosingBalance']['member_id']]['id']=$memberData['YearWiseClosingBalance']['id'];
            }
        }
        else
        {
			//echo "no data sup";
            $memberPaymentSupBalance = array_map(function ($array){ $array['year_id']=$this->Session->read('Auth.year_id');return $array;},$memberPaymentSupBalance);

        }
        $yearWiseClosingBalance->saveAll($memberPaymentSupBalance,['updateFields'=>['principal_balance','interest_balance','tax_balance'],'updateModifiedFields'=>true]);

        
    }
    
    public function updateMemberClosingBalance($paymentData=array())
    {
        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');

        $db = connectionManager::getDataSource('default');
        App::import('model', 'YearWiseClosingBalance');
        $yearWiseClosingBalance = new YearWiseClosingBalance();
        
        $memberPaymentBalance = array();
        $memberPaymentBalance[] = $paymentData;

       
        $yearWiseClosingBalance->saveAll($memberPaymentBalance,['updateFields'=>['principal_balance','interest_balance','tax_balance',''],'updateModifiedFields'=>true]);
        
    }
    
    public function memberClosingBalanceTransfer_bk(){
        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');
                
        $insertClosingBalanceRegularSql = "insert into `member_year_wise_closing_balance` (`year_id`, `society_id`, `member_id`, `principal_balance`, `interest_balance`, `tax_balance`, `bill_type`,`bill_generated_date`,`bill_due_date`,`bill_end_date`,`member_transfer`) SELECT `financial_year_id`,`society_id`, `member_id`, `principal_balance`, `interest_balance`, `tax_balance`, `bill_type`,`bill_generated_date`,`bill_due_date`,`bill_end_date`,`member_transfer` FROM `member_bill_summaries` where id in(
        select max(id) from member_bill_summaries where society_id = ".$societyLoginID." and bill_type='reg' and `financial_year_id`=".$financialYearId." group by member_id)";


        $db = connectionManager::getDataSource('default');
        $lastInsertIds = $db->query($insertClosingBalanceRegularSql);

        $lastInsertId=$db->lastInsertId();
        
        
       // var_dump($saveRecordReg);

        if (empty($lastInsertId)){
            echo "error in inserting regular bill closing balance";   
        }
        

          $updateMemberQueryReg = "update members m join  member_bill_summaries mbs on m.id=mbs.member_id  set m.op_principal=mbs.principal_balance,m.op_interest=mbs.interest_balance,m.op_tax=mbs.tax_balance where mbs.id in (select max(id) from member_bill_summaries where society_id =".$societyLoginID." and bill_type='reg' and `financial_year_id`=".$financialYearId." group by member_id)";
            $update = $db->execute($updateMemberQueryReg);
            
          
            if (is_object($update)){
                if(empty($update->rowCount())){
                    echo 'Error in member balance update for regular balance or no rows updated'; 
                }
            }

             $insertClosingBalanceSupplementarySql = "insert into `member_year_wise_closing_balance` (`year_id`, `society_id`, `member_id`, `principal_balance`, `interest_balance`, `tax_balance`, `bill_type`,`bill_generated_date`,`bill_due_date`,`bill_end_date`,`member_transfer`) SELECT `financial_year_id`,`society_id`, `member_id`, `principal_balance`, `interest_balance`, `tax_balance`, `bill_type`,`bill_generated_date`,`bill_due_date`,`bill_end_date`,`member_transfer` FROM `member_bill_summaries` where id in(
            select max(id) from member_bill_summaries where society_id = ".$societyLoginID." and bill_type='sup' and `financial_year_id`=".$financialYearId." group by member_id)";
            $saveRecordSup = $db->query($insertClosingBalanceSupplementarySql);
            $lastInsertId=$db->lastInsertId();
            
            if (empty($lastInsertId)){
                echo "error in inserting supplementary bill ";
            }

            //update members m join member_bill_summaries mbs on m.id=mbs.member_id set m.op_principal=mbs.principal_balance,m.op_interest=mbs.interest_balance,m.op_tax=mbs.tax_balance where mbs.id in (select max(id) from member_bill_summaries where society_id =9410 and bill_type='sup' and `financial_year_id`=18 group by member_id)

            $updateMemberQuerySup = "update members m join  member_bill_summaries mbs on m.id=mbs.member_id  set m.supplementary_principal=mbs.principal_balance,m.supplementary_interest=mbs.interest_balance,m.supplementary_tax=mbs.tax_balance where mbs.id in (select max(id) from member_bill_summaries where society_id =".$societyLoginID." and bill_type='sup' and `financial_year_id`=".$financialYearId." group by member_id)";

            $update =  $db->execute($updateMemberQuerySup); 
            if (is_object($update)){
                if(empty($update->rowCount())){
                    echo 'Error in member balance update for supplementary balance or no rows updated'; 
                }
            }  

    }
    public function getMemberWiseClosingBalance($billType = 'reg',$save=0){
        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');
        if (!empty($save)){
        	$sql = "SELECT `financial_year_id` as `year_id`,`society_id`, `member_id`, `principal_balance`, `interest_balance`, `tax_balance`, `bill_type`,`bill_generated_date`,`bill_due_date`,`bill_end_date`,balance_amount FROM `member_bill_summaries` where id in(select max(id) from member_bill_summaries where society_id = ".$societyLoginID." and bill_type='".$billType."' and `financial_year_id`=".$financialYearId." group by member_id)";
        }
        else
        {
            $sql = "SELECT `financial_year_id`,`society_id`, `member_id`, `principal_balance`, `interest_balance`, `tax_balance`, `bill_type`,`bill_generated_date`,`bill_due_date`,`bill_end_date`,`balance_amount`,balance_amount FROM `member_bill_summaries` where id in(select max(id) from member_bill_summaries where society_id = ".$societyLoginID." and bill_type='".$billType."' and `financial_year_id`=".$financialYearId." group by member_id)";
        }
        $db = connectionManager::getDataSource('default');
        $memberClosingBal = $db->query($sql);
        $memberIdBasedData = [];
        //print_r($memberIdBasedData);die;
        if(!empty($memberClosingBal)){
            foreach($memberClosingBal as $data){
                $memberIdBasedData[$data['member_bill_summaries']['member_id']] = $data['member_bill_summaries'];
            }
        }
        return $memberIdBasedData;
    }
    public function getMemberslastClosingBalance($billType = 'reg'){
        $financialYearId = $this->Session->read('Auth.year_id');
        $yearId = $financialYearId-1; #get closing balance of previous year
        $societyLoginID = $this->Session->read('Auth.User.id');

        //$sql = "select * from member_year_wise_closing_balance where id in(
        //select max(id) from member_year_wise_closing_balance where society_id = $societyLoginID and year_id=$yearId and bill_type='$billType' group by member_id)";
        $sql = "select * from member_year_wise_closing_balance where society_id = $societyLoginID and year_id=$yearId and bill_type='$billType'";

        $db = connectionManager::getDataSource('default');
        $memberClosingBal = $db->query($sql);
        $memberIdBasedData = [];
        if(!empty($memberClosingBal)){
            foreach($memberClosingBal as $data){
                $memberIdBasedData[$data['member_year_wise_closing_balance']['member_id']] = $data['member_year_wise_closing_balance'];
            }
        }
        return $memberIdBasedData;
    }

    public function getMemberClosingBalanceData($memberId,$memberTransferStatusId){
        App::import('model', 'YearWiseClosingBalance');
        $yearWiseClosingBalance = new YearWiseClosingBalance();

        $currentYearStart = $this->Session->read('Auth.year_id');
       // echo "<pre>";
      
        $billTypeWiseClosingData = [];
        $prevYearData = []; 

        $prevYearId = $this->Session->read('Auth.year_id') - 1;

        if(!empty($memberId)){
            $yearClosingData = $yearWiseClosingBalance->find('all',array('conditions'=> array('YearWiseClosingBalance.member_id' => $memberId,
            'YearWiseClosingBalance.year_id' => $prevYearId)));

            // $yearClosingData = $yearWiseClosingBalance->find('all',array('conditions'=> array('YearWiseClosingBalance.member_id' => $memberId,
           // 'YearWiseClosingBalance.year_id' => $currentYearStart,'YearWiseClosingBalance.member_transfer' => $memberTransferStatusId)));
        
            if(!empty($yearClosingData)){
                foreach ($yearClosingData as $data) {
                    $billTypeWiseClosingData[$data['YearWiseClosingBalance']['bill_type']] = [
                        'closing_id' => $data['YearWiseClosingBalance']['id'],
                        'year_id' => $data['YearWiseClosingBalance']['year_id'],
                        'op_principal' => $data['YearWiseClosingBalance']['principal_balance'],
                        'op_interest' => $data['YearWiseClosingBalance']['interest_balance'],
                        'op_tax' => $data['YearWiseClosingBalance']['tax_balance']
                    ];
                }                
            }
        }
        return $billTypeWiseClosingData;
    }   
    
    public function updateLedgerOpeningClosingBalance($societyId){
        $financialYearId = $this->Session->read('Auth.year_id');
        $sql = "call ExcecuteLedgerOpeningClosingBalaneProcedure($financialYearId,$societyId)";
        $db = connectionManager::getDataSource('default');
        $db->query($sql);
        
    }
    
    public function getLedgerHeadOpening($societyId,$ledgerHeadId){
        App::import('model', 'LedgerHeadOpeningModel');
        $ledgerHeadOpeningObj = new LedgerHeadOpeningModel();
        $financialYearId = $this->Session->read('Auth.year_id');
        $ledgerClosingRes = $ledgerHeadOpeningObj->find('first',array('condtions' => array('LedgerHeadOpeningModel.society_id' => $societyId,
        'LedgerHeadOpeningModel.financial_year_id' => $financialYearId,'LedgerHeadOpeningModel.ledger_head_id' => $ledgerHeadId)));
    }
    
   public function getBankLedgerHeadOpening($societyId){
        App::import('model', 'BanksLedgerOpeningClosingModel');
        $bankLedgerHeadOpeningObj = new BanksLedgerOpeningClosingModel();
        $financialYearId = $this->Session->read('Auth.year_id');
        $ledgerClosingRes = $bankLedgerHeadOpeningObj->find('first',array('condtions' => array('BanksLedgerOpeningClosingModel.society_id' => $societyId,
        'BanksLedgerOpeningClosingModel.financial_year_id' => $financialYearId,'BanksLedgerOpeningClosingModel.ledger_head_id' => $ledgerHeadId)));        
    }
    
    public function getSocietyLedgerOpClosingBal($societyId,$ledgerHeadId){
        $openingData = [];
        $financialYearId = $this->Session->read('Auth.year_id');

        App::import('model', 'LedgerHeadOpeningModel');
        $ledgerHeadOpeningObj = new LedgerHeadOpeningModel();
        $financialYearId = $this->Session->read('Auth.year_id')-1;

        $ledgerClosingRes = $ledgerHeadOpeningObj->find('all', array('conditions'=>array('ledger_head_id'=>$ledgerHeadId,'society_id'=>$societyId, 'financial_year_id'=>$financialYearId),'recursive'=>-1));

        return !empty($ledgerClosingRes) ? $ledgerClosingRes[0]['LedgerHeadOpeningModel'] : array();
    }

    public function getSocietyLedgerOpClosingBalSocietyWise($societyId){
        $opBalanceAmount = [];
        
        App::import('model', 'LedgerHeadOpeningModel');
        $ledgerHeadOpeningObj = new LedgerHeadOpeningModel();
        $financialYearId = $this->Session->read('Auth.year_id')-1;
    
        $ledgerClosingRes = $ledgerHeadOpeningObj->find('all', array('conditions'=>array('society_id'=>$societyId, 'financial_year_id'=>$financialYearId),'fields'=>array('balance_amount','ledger_head_id'),'recursive'=>-1));

        foreach ($ledgerClosingRes as $key => $data){
            $opBalanceAmount[$data['LedgerHeadOpeningModel']['ledger_head_id']] = $data['LedgerHeadOpeningModel']['balance_amount'];

        }
       
        return $opBalanceAmount;
    }
    
    public function callProcedures($queryString){
        $db = connectionManager::getDataSource('default');
        return  $db->query($queryString);
    }
    
   /* public saveOpeningLedgeAmouunt(){
         $db = connectionManager::getDataSource('default');
         $data = ;
    }*/

    public function getLedgerJVData(){
        $db = connectionManager::getDataSource('default');
        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');
        $sql = "SELECT sum(`jv_amount_debited`) as d_amount,sum(`jv_amount_credited`) as c_amount,`jv_debit_ledger_head_id`,`jv_credit_ledger_head_id` FROM `journal_vouchers` WHERE `society_id`=$societyLoginID and financial_year_id=$financialYearId group by `jv_debit_ledger_head_id`,`jv_credit_ledger_head_id`";
        $jvData = $db->query($sql);
        
        if (!empty( $jvData)){
            foreach ($jvData as $key=> $data){
                if (isset($jvLedgerData['debit_data'][$data['journal_vouchers']['jv_debit_ledger_head_id']]))
                    $jvLedgerData['debit_data'][$data['journal_vouchers']['jv_debit_ledger_head_id']]+=$data[0]['d_amount'];
                else
                    $jvLedgerData['debit_data'][$data['journal_vouchers']['jv_debit_ledger_head_id']]=$data[0]['d_amount'];

                if (isset($jvLedgerData['credit_data'][$data['journal_vouchers']['jv_credit_ledger_head_id']]))
                    $jvLedgerData['credit_data'][$data['journal_vouchers']['jv_credit_ledger_head_id']]+=$data[0]['c_amount'];
                else
                    $jvLedgerData['credit_data'][$data['journal_vouchers']['jv_credit_ledger_head_id']]=$data[0]['c_amount'];
            }
            return $jvLedgerData;
        }
        else
            return false;
        
    }
    public function getLedgerSocietyPayments(){
        $db = connectionManager::getDataSource('default');
        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');

        $ledgerPaymentData= array();
        $ledgerPaymentByLedgerHead = array();

        $sql ="SELECT sum(total_amount) as total_amount,ledger_head_id,payment_by_ledger_id  FROM `society_payments` WHEre `society_id`=$societyLoginID and financial_year_id=$financialYearId group by ledger_head_id,payment_by_ledger_id";
        $societyPaymentData = $db->query($sql);
       // print_r($societyPaymentData);die;

        if (!empty($societyPaymentData)){
            foreach ($societyPaymentData as $key => $data){
                if (isset($ledgerPaymentData[$data['society_payments']['ledger_head_id']]))
                    $ledgerPaymentData[$data['society_payments']['ledger_head_id']]+=$data[0]['total_amount'];
                else
                    $ledgerPaymentData[$data['society_payments']['ledger_head_id']] = $data[0]['total_amount'];
                if (isset($ledgerPaymentByLedgerHead[$data['society_payments']['payment_by_ledger_id']]))
                    $ledgerPaymentByLedgerHead[$data['society_payments']['payment_by_ledger_id']] += $data[0]['total_amount'];
                else
                    $ledgerPaymentByLedgerHead[$data['society_payments']['payment_by_ledger_id']] = $data[0]['total_amount'];
            }
            //print_r($ledgerPaymentData);
           // print_r($ledgerPaymentByLedgerHead);
            
            return array($ledgerPaymentData, $ledgerPaymentByLedgerHead);//$societyPaymentData;
        }
        else
            return false;
        
    }

    public function getLedgerSocietyOtherIcomes(){
        $db = connectionManager::getDataSource('default');
        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');

        $sql ="SELECT sum(`amount_paid`) as total_amount,society_bank_id,ledger_head_id FROM `society_other_incomes` WHEre `society_id`=$societyLoginID and financial_year_id=$financialYearId group by society_bank_id, ledger_head_id";
        $societyOtherIncomeData = $db->query($sql);
      //  print_r($societyOtherIncomeData);

        if (!empty($societyOtherIncomeData)){
            foreach ($societyOtherIncomeData as $key => $data){
                $societyOtherIncomeByLedger[$data['society_other_incomes']['ledger_head_id']] +=$data[0]['total_amount'];
                $societyOtherIncomeByBank[$data['society_other_incomes']['society_bank_id']] +=$data[0]['total_amount'];
            }
            return array($societyOtherIncomeByLedger,$societyOtherIncomeByBank);
        }
        else
            return false;
        
    }

    public function getLedgerMemberBillData(){
        $db = connectionManager::getDataSource('default');
        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');
        
        $sql ="SELECT sum(amount) as total_amount_credited,`ledger_head_id` FROM `member_bill_generates` WHERE `society_id`=$societyLoginID and `financial_year_id`=$financialYearId group by ledger_head_id";
        $societyMemberBillData = $db->query($sql);

        
        if (!empty($societyMemberBillData)){
            foreach ($societyMemberBillData as $key=>$data){
                if (isset($societyMemberBillLedgerData[$data['member_bill_generates']['ledger_head_id']]))
                    $societyMemberBillLedgerData[$data['member_bill_generates']['ledger_head_id']] += $data[0]['total_amount_credited'];
                else
                    $societyMemberBillLedgerData[$data['member_bill_generates']['ledger_head_id']] = $data[0]['total_amount_credited'];
            }
          
            return $societyMemberBillLedgerData;
        }
        else
            return false;
        
    }

    public function getLedgerBankPayments(){
        $db = connectionManager::getDataSource('default');
        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');

        $sql ="SELECT sum(amount_paid) as total_amount_credited,society_bank_id FROM `member_payments` where society_id=$societyLoginID and financial_year_id=$financialYearId GROUP by society_bank_id";
        $ledgerBankPayemnts = $db->query($sql);
        if (!empty($ledgerBankPayemnts)){
            foreach ($ledgerBankPayemnts as $key=>$data){
                if (isset($ledgerBankPaymentsData[$data['member_payments']['society_bank_id']]))
                    $ledgerBankPaymentsData[$data['member_payments']['society_bank_id']]+=$data[0]['total_amount_credited'];
                else
                    $ledgerBankPaymentsData[$data['member_payments']['society_bank_id']]=$data[0]['total_amount_credited'];
            }
            
            return $ledgerBankPaymentsData;
        }
        else
            return false;
    }

    public function getLedgerCashwithdrawRecords(){
        $db = connectionManager::getDataSource('default');
        $societyLoginID =  $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');

        $sql ="SELECT sum(`amount`) as total_amount,`txn_type`,`bank_ledger_head_id`,`bank_to_ledger_head_id` FROM `cash_withdraws` WHERE `society_id`=$societyLoginID and financial_year_id=$financialYearId group by `txn_type`,`bank_ledger_head_id`,`bank_to_ledger_head_id`";
        $cashwidrawrec= $db->query($sql);
       
        if (!empty($cashwidrawrec)){
            foreach ($cashwidrawrec as $key=> $data){
                # for the transaction type based amount
                if ($cashwithdrawData['txn_type'][$data['cash_withdraws']['txn_type']])
                    $cashwithdrawData['txn_type'][$data['cash_withdraws']['txn_type']]+=$data[0]['total_amount'];
                else
                    $cashwithdrawData['txn_type'][$data['cash_withdraws']['txn_type']]=$data[0]['total_amount'];

                # for the transaction type with bank_ledger_head_id
                if (!empty($data['cash_withdraws']['bank_ledger_head_id']))
                    if (isset($cashwithdrawData['txn_type_from_bank_ledger'][$data['cash_withdraws']['txn_type']][$data['cash_withdraws']['bank_ledger_head_id']]))
                        $cashwithdrawData['txn_type_from_bank_ledger'][$data['cash_withdraws']['txn_type']][$data['cash_withdraws']['bank_ledger_head_id']]+=$data[0]['total_amount'] ;
                    else
                        $cashwithdrawData['txn_type_from_bank_ledger'][$data['cash_withdraws']['txn_type']][$data['cash_withdraws']['bank_ledger_head_id']]=$data[0]['total_amount'] ;

                #for the trsansaction type with bank_to_ledger_head_id
                if (!empty($data['cash_withdraws']['bank_to_ledger_head_id']))
                    $cashwithdrawData['txn_type_to_bank_ledger'][$data['cash_withdraws']['txn_type']][$data['cash_withdraws']['bank_to_ledger_head_id']]+=$data[0]['total_amount'] ;

               // $cashwithdrawData['txn_type']+=$data[0]['total_amount']
                /*$cashwithdrawData['bank_to_ledger'][$data['cash_withdraws']['bank_to_ledger_head_id']]+= $data[0]['total_amount'] ;
                $cashwithdrawData['bank_ledger'][$data['cash_withdraws']['bank_ledger_head_id']]+= $data[0]['total_amount'];
                $cashwithdrawData['txn_type'][$data['cash_withdraws']['txn_type']]+=$data[0]['total_amount'];
                $cashwithdrawData['txn_type']+=$data[0]['total_amount'];*/
            }
            
            return $cashwithdrawData;
        }
        else
            return false;
    }

    public function getLedgerBillSummaryAmounts(){
        $db = connectionManager::getDataSource('default');
        $societyLoginID = $this->Session->read('Auth.User.id');
        $financialYearId = $this->Session->read('Auth.year_id');

        $sql = "SELECT sum(tax_total) as total_tax, sum(interest_on_due_amount) as total_interest, sum(discount) as total_discount FROM `member_bill_summaries` WHERE `society_id`=$societyLoginID and financial_year_id=$financialYearId";
        $billSummaryData = $db->query($sql);
        
        if (!empty($billSummaryData)){
            return $billSummaryData[0][0];
        }
        else
            return false;

    }
    public function getSocietyBankList(){
        $societyLoginID = $this->Session->read('Auth.User.id');
        App::import('model', 'SocietyBank');      
        $societyBankObj = new SocietyBank();
        $societyBanks = $societyBankObj->find('all', array('conditions'=>array('society_id'=>$societyLoginID),'fields'=>array('bank_ledger_head_id'),'recursive'=>-1));

        
        if (!empty($societyBanks)){
            foreach($societyBanks as $key=>$data)
            {
                $societyBankList[$data['SocietyBank']['bank_ledger_head_id']] = $data['SocietyBank']['bank_ledger_head_id'];
            }   
        }

        return $societyBankList;
    }
}    

