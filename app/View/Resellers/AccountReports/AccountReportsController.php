<?php







App::uses('AppController', 'Controller');







/**



 * Users Controller



 *



 * @property User $User



 * @property PaginatorComponent $Paginator



 * @property SessionComponent $Session



 */



class AccountReportsController extends AppController {







    /**



     * Components



     *



     * @var array



     */



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







    function account() {



        



    }







    function account_bank_book() {



        $this->loadModel('SocietyLedgerHeads');







        $societyLoginID = $this->societyloginID;



        $postData = $this->request->data;



        $societyBankLists = $this->SocietyBill->getSocietyBankNameList();



        $accountBankBbook = array();



        $societyDetails = $this->SocietyBill->getSocietyDetails();



        $operator = '=';



        $openingBalance = 0;



        $firstDay = $this->getFirstDayFinancialYear();







        $options['AND'] = array('MemberPayment.society_id' => $societyLoginID);



        $optionsSociety['AND'] = array('SocietyPayment.society_id' => $societyLoginID);



        $optionsCashWithdraw['AND'] = array('CashWithdraw.society_id' => $societyLoginID);



        $optionsContraReceived['AND'] = array('CashWithdraw.society_id' => $societyLoginID);



        $optionsSocietyOtherIncome['AND'] = array('SocietyOtherIncome.society_id' => $societyLoginID);







        if (isset($this->request->data['BankBook']['society_bank_id']) && !empty($this->request->data['BankBook']['society_bank_id'])) {



            $options['AND']['MemberPayment.society_bank_id'] = $this->request->data['BankBook']['society_bank_id'];



            $optionsCashWithdraw['AND']['CashWithdraw.bank_ledger_head_id'] = $this->request->data['BankBook']['society_bank_id'];







            $optionsContraReceived['AND']['CashWithdraw.bank_to_ledger_head_id'] = $this->request->data['BankBook']['society_bank_id'];



            $optionsSociety['AND']['SocietyPayment.payment_by_ledger_id'] = $this->request->data['BankBook']['society_bank_id'];



            $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.society_bank_id'] = $this->request->data['BankBook']['society_bank_id'];







            //



            $tempConn = mysql_connect('localhost','vedhtkjm_easylog','easyDatabase123');



            //$tempConn = mysql_connect('localhost','root','');



            $db = $this->Session->read('Auth.dbConfig');



            if ($db == 'default'){



                $currentdb = 'vedhtkjm_easylogics';



            } else {



                $currentdb = 'vedhtkjm_easylogics';



            }



            $table = $currentdb.'.society_ledger_heads';



            $table1 = $currentdb.'.members';















            $query = mysql_query("SELECT * FROM $table WHERE society_id = " . $societyLoginID . " and id='" . $this->request->data['BankBook']['society_bank_id'] . "'", $tempConn);



            $res = mysql_fetch_assoc($query);



            $ledgerHeadDetails['SocietyLedgerHeads'] = $res;







            $query = mysql_query("SELECT id, title FROM $table WHERE status = '" . Configure::read('Active') . "' AND society_id = " . $societyLoginID . " ", $tempConn);



            $allLedgers = array();



            while ($res = mysql_fetch_assoc($query)) {



                $allLedgers[$res['id']] = $res['title'];



            }



            $query = mysql_query("SELECT id, member_name FROM $table1 WHERE status = '" . Configure::read('Active') . "' AND society_id = " . $societyLoginID . " ", $tempConn);



            $allMembers = array();



            while ($res = mysql_fetch_assoc($query)) {



                $allMembers[$res['id']] = $res['member_name'];



            }







            mysql_close();



            //



            //For Ledger head opening balance



            /* $ledgerHeadDetails = $this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.id' => $this->request->data['BankBook']['society_bank_id'], 'SocietyLedgerHeads.society_id' => $societyLoginID))); */



            $openingBalance = $ledgerHeadDetails['SocietyLedgerHeads']['opening_amount'];



        }







        if (isset($this->request->data['BankBook']['operator']) && !empty($this->request->data['BankBook']['operator'])) {



            $operator = $this->request->data['BankBook']['operator'];



        }



 



        if (isset($this->request->data['BankBook']['amount']) && !empty($this->request->data['BankBook']['amount'])) {



            $options['AND']['MemberPayment.amount_paid ' . $operator . ' '] = $this->request->data['BankBook']['amount'];



            $optionsCashWithdraw['AND']['CashWithdraw.amount ' . $operator . ' '] = $this->request->data['BankBook']['amount'];



            $optionsContraReceived['AND']['CashWithdraw.amount ' . $operator . ' '] = $this->request->data['BankBook']['amount'];



            $optionsSociety['AND']['SocietyPayment.amount ' . $operator . ' '] = $this->request->data['BankBook']['amount'];



            $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.amount_paid ' . $operator . ' '] = $this->request->data['BankBook']['amount'];



        }







        if (isset($this->request->data['BankBook']['payment_date']) && !empty($this->request->data['BankBook']['payment_date']) && !empty($this->request->data['BankBook']['payment_date_to'])) {



            $options['OR']['MemberPayment.payment_date BETWEEN ? and ?'] = array($this->request->data['BankBook']['payment_date'], $this->request->data['BankBook']['payment_date_to']);



            $optionsCashWithdraw['OR']['CashWithdraw.payment_date BETWEEN ? and ?'] = array($this->request->data['BankBook']['payment_date'], $this->request->data['BankBook']['payment_date_to']);



            $optionsContraReceived['OR']['CashWithdraw.payment_date BETWEEN ? and ?'] = array($this->request->data['BankBook']['payment_date'], $this->request->data['BankBook']['payment_date_to']);



            $optionsSociety['OR']['SocietyPayment.payment_date BETWEEN ? and ?'] = array($this->request->data['BankBook']['payment_date'], $this->request->data['BankBook']['payment_date_to']);



            $optionsSocietyOtherIncome['OR']['SocietyOtherIncome.payment_date BETWEEN ? and ?'] = array($this->request->data['BankBook']['payment_date'], $this->request->data['BankBook']['payment_date_to']);







            if ($this->request->data['BankBook']['payment_date'] != $firstDay) {



                $openingBalance = $this->getOpeningBalanceForthePeriod($this->request->data['BankBook']['payment_date'], $societyLoginID, $this->request->data['BankBook']['society_bank_id'], 'bankbook');



            }



        } else if (isset($this->request->data['BankBook']['payment_date']) && !empty($this->request->data['BankBook']['payment_date'])) {



            $options['AND']['MemberPayment.payment_date'] = $this->request->data['BankBook']['payment_date'];



            $optionsCashWithdraw['AND']['CashWithdraw.payment_date'] = $this->request->data['BankBook']['payment_date'];



            $optionsContraReceived['AND']['CashWithdraw.payment_date'] = $this->request->data['BankBook']['payment_date'];



            $optionsSociety['AND']['SocietyPayment.payment_date'] = $this->request->data['BankBook']['payment_date'];



            $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.payment_date'] = $this->request->data['BankBook']['payment_date'];







            if ($this->request->data['BankBook']['payment_date'] != $firstDay) {



                $openingBalance = $this->getOpeningBalanceForthePeriod($this->request->data['BankBook']['payment_date'], $societyLoginID, $this->request->data['BankBook']['society_bank_id'], 'bankbook');



            }



        } else if (isset($this->request->data['BankBook']['payment_date_to']) && !empty($this->request->data['BankBook']['payment_date_to'])) {



            $options['AND']['MemberPayment.payment_date'] = $this->request->data['BankBook']['payment_date_to'];



            $optionsCashWithdraw['AND']['CashWithdraw.payment_date'] = $this->request->data['BankBook']['payment_date_to'];



            $optionsContraReceived['AND']['CashWithdraw.payment_date'] = $this->request->data['BankBook']['payment_date_to'];



            $optionsSociety['AND']['SocietyPayment.payment_date'] = $this->request->data['BankBook']['payment_date_to'];



            $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.payment_date'] = $this->request->data['BankBook']['payment_date_to'];







            if ($this->request->data['BankBook']['payment_date_to'] != $firstDay) {



                $openingBalance = $this->getOpeningBalanceForthePeriod($this->request->data['BankBook']['payment_date_to'], $societyLoginID, $this->request->data['BankBook']['society_bank_id'], 'bankbook');



            }



        }







        $bankBookData = array();



        $societyBankBbookDetails = array();



        $memberBankBbookDetails = array();



        $cashWithdrawDetails = array();



        $SocietyOtherIncomeDetails = array();







        if ($this->request->is(array('post'))) {



            $this->MemberPayment->Behaviors->attach('Containable');



            $this->SocietyPayment->Behaviors->attach('Containable'); //Make sure report type is deposit or withdrawal



            if (isset($this->request->data['BankBook']['report_type']) && !empty($this->request->data['BankBook']['report_type']) && $this->request->data['BankBook']['report_type'] == "Deposit") {



                $memberBankBbookDetails = $this->MemberPayment->find('all', array('contain' => array('Member'), 'conditions' => $options, 'order' => array('MemberPayment.payment_date' => 'asc')));



                $optionsCashWithdraw['AND']['CashWithdraw.txn_type'] = 'deposit';



                $cashWithdrawDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsCashWithdraw, 'order' => array('CashWithdraw.payment_date' => 'asc')));



                /* $SocietyOtherIncomeDetails = $this->SocietyOtherIncome->find('all', array('conditions' => array('SocietyOtherIncome.payment_mode'=>'Bank','SocietyOtherIncome.society_bank_id'=>$this->request->data['BankBook']['society_bank_id']),'order' => array('SocietyOtherIncome.payment_date' => 'asc'))); */



                $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.payment_mode'] = 'Bank';



                $SocietyOtherIncomeDetails = $this->SocietyOtherIncome->find('all', array('conditions' => $optionsSocietyOtherIncome, 'order' => array('SocietyOtherIncome.payment_date' => 'asc')));



            } else if (isset($this->request->data['BankBook']['report_type']) && !empty($this->request->data['BankBook']['report_type']) && $this->request->data['BankBook']['report_type'] == "Withdrawal") {



                $societyBankBbookDetails = $this->SocietyPayment->find('all', array('contain' => array('SocietyLedgerHeads'), 'conditions' => $optionsSociety, 'order' => array('SocietyPayment.payment_date' => 'asc')));



                //print_r($SocietyOtherIncomeDetails);



                $optionsCashWithdraw['AND']['CashWithdraw.txn_type'] = 'withdraw';



                $cashWithdrawDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsCashWithdraw, 'order' => array('CashWithdraw.payment_date' => 'asc')));



            } else if (isset($this->request->data['BankBook']['report_type']) && !empty($this->request->data['BankBook']['report_type']) && $this->request->data['BankBook']['report_type'] == "Contra") {



                $optionsCashWithdraw['AND']['CashWithdraw.txn_type'] = 'contra';



                $cashWithdrawDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsCashWithdraw, 'order' => array('CashWithdraw.payment_date' => 'asc')));



                $cashContraReceivedDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsContraReceived, 'order' => array('CashWithdraw.payment_date' => 'asc')));



            } else {







                $memberBankBbookDetails = $this->MemberPayment->find('all', array('contain' => array('Member'), 'conditions' => $options, 'order' => array('MemberPayment.payment_date' => 'asc')));



                $societyBankBbookDetails = $this->SocietyPayment->find('all', array('contain' => array('SocietyLedgerHeads'), 'conditions' => $optionsSociety, 'order' => array('SocietyPayment.payment_date' => 'asc')));



                $cashWithdrawDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsCashWithdraw, 'order' => array('CashWithdraw.payment_date' => 'asc')));



                $cashContraReceivedDetails = $this->CashWithdraw->find('all', array('conditions' => $optionsContraReceived, 'order' => array('CashWithdraw.payment_date' => 'asc')));







                $optionsSocietyOtherIncome['AND']['SocietyOtherIncome.payment_mode'] = 'Bank';



                $SocietyOtherIncomeDetails = $this->SocietyOtherIncome->find('all', array('conditions' => $optionsSocietyOtherIncome, 'order' => array('SocietyOtherIncome.payment_date' => 'asc')));



                      



            }



    



            if (isset($societyBankBbookDetails) && !empty($societyBankBbookDetails)) {



                $sCounter = 0;



                foreach ($societyBankBbookDetails as $societyBankBbookData) {



                    $bankBookData[$societyBankBbookData['SocietyPayment']['payment_date']]['Payment'][$sCounter]['payment_date'] = $societyBankBbookData['SocietyPayment']['payment_date'];



                    $bankBookData[$societyBankBbookData['SocietyPayment']['payment_date']]['Payment'][$sCounter]['particulars'] = $societyBankBbookData['SocietyPayment']['particulars'] . ' &' . $societyBankBbookData['SocietyLedgerHeads']['title'];



                    $bankBookData[$societyBankBbookData['SocietyPayment']['payment_date']]['Payment'][$sCounter]['cheque_number'] = $societyBankBbookData['SocietyPayment']['cheque_reference_number'];



                    $bankBookData[$societyBankBbookData['SocietyPayment']['payment_date']]['Payment'][$sCounter]['withdrawal'] = $societyBankBbookData['SocietyPayment']['total_amount'];



                    $bankBookData[$societyBankBbookData['SocietyPayment']['payment_date']]['Payment'][$sCounter]['payment_flag'] = "Payment";



                    $bankBookData[$societyBankBbookData['SocietyPayment']['payment_date']]['Payment'][$sCounter]['deposit'] = "0.00";



                    $bankBookData[$societyBankBbookData['SocietyPayment']['payment_date']]['Payment'][$sCounter]['link'] = "/societys/add_society_payments/" . $societyBankBbookData['SocietyPayment']['id'];



                    $bankBookData[$societyBankBbookData['SocietyPayment']['payment_date']]['Payment'][$sCounter]['ledgerexists'] = isset($allLedgers[$societyBankBbookData['SocietyPayment']['ledger_head_id']]) ? '1' : 0;







                    unset($societyBankBbookData);



                    $sCounter++;



                }



            }







            $memberReceipt = 0;



            if (isset($memberBankBbookDetails) && !empty($memberBankBbookDetails)) {



                $mCounter = 0;



                foreach ($memberBankBbookDetails as $memberBankBbookData) {



                    // echo '<pre>'; print_r($memberBankBbookData);



                    $bankBookData[$memberBankBbookData['MemberPayment']['payment_date']]['Receipt'][$mCounter]['payment_date'] = $memberBankBbookData['MemberPayment']['payment_date'];



                    $bankBookData[$memberBankBbookData['MemberPayment']['payment_date']]['Receipt'][$mCounter]['particulars'] = $memberBankBbookData['Member']['flat_no'] . ' - ' . $memberBankBbookData['Member']['member_name'] . '<br>' . 'Being Maintenance received';



                    $bankBookData[$memberBankBbookData['MemberPayment']['payment_date']]['Receipt'][$mCounter]['cheque_number'] = $memberBankBbookData['MemberPayment']['cheque_reference_number'];



                    $bankBookData[$memberBankBbookData['MemberPayment']['payment_date']]['Receipt'][$mCounter]['withdrawal'] = "0.00";



                    $bankBookData[$memberBankBbookData['MemberPayment']['payment_date']]['Receipt'][$mCounter]['deposit'] = $memberBankBbookData['MemberPayment']['amount_paid'];



                    $bankBookData[$memberBankBbookData['MemberPayment']['payment_date']]['Receipt'][$mCounter]['payment_flag'] = "Receipt";



                    $bankBookData[$memberBankBbookData['MemberPayment']['payment_date']]['Receipt'][$mCounter]['link'] = "/societys_members/add_member_payment/" . $memberBankBbookData['MemberPayment']['id'];



                    $memberReceipt += $memberBankBbookData['MemberPayment']['amount_paid'];



                    $bankBookData[$memberBankBbookData['MemberPayment']['payment_date']]['Receipt'][$mCounter]['ledgerexists'] = isset($allMembers[$memberBankBbookData['Member']['id']]) ? '1' : 0;



                    unset($memberBankBbookData);



                    $mCounter++;



                }



            }







            //echo 'Receipt ->'.$memberReceipt.'<br>';







            $cashCounter = 0;







            $depositAmount = 0;







            if (isset($cashWithdrawDetails) && !empty($cashWithdrawDetails)) {



                foreach ($cashWithdrawDetails as $cashWithdrawData) {



                    $note = '';



                    switch ($cashWithdrawData['CashWithdraw']['txn_type']) {



                        case 'contra' :



                            $flag = 'Contra';



                            $note = 'Contra Trasferred';



                            break;



                        case 'deposit' :



                            $flag = 'Deposit';



                            $note = 'Deposited in Bank';



                            break;



                        case 'withdraw' :



                            $flag = 'Withdrawal';



                            $note = 'Withdrawn from Bank';



                            break;



                    }



                    //echo '<pre>'; print_r($cashWithdrawData);



                    $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['payment_date'] = $cashWithdrawData['CashWithdraw']['payment_date'];



                    $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['particulars'] = $cashWithdrawData['CashWithdraw']['particulars'] . '<br>' . $note;



                    $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['cheque_number'] = $cashWithdrawData['CashWithdraw']['cheque_no'];







                    if ($flag == 'Contra' || $flag == 'Withdrawal') {



                        $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['withdrawal'] = $cashWithdrawData['CashWithdraw']['amount'];



                        $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['deposit'] = '0.00';



                    } else if ($flag == 'Deposit') {



                        $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['withdrawal'] = '0.00';



                        $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['deposit'] = $cashWithdrawData['CashWithdraw']['amount'];



                        $depositAmount += $cashWithdrawData['CashWithdraw']['amount'];



                    }







                    $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['payment_flag'] = $flag;



                    $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['link'] = "/societys/add_cash_withdraws/" . $cashWithdrawData['CashWithdraw']['id'];



                    $bankBookData[$cashWithdrawData['CashWithdraw']['payment_date']][$flag][$cashCounter]['ledgerexists'] = 1;



                    unset($cashWithdrawData);



                    $cashCounter++;



                }



            }







            //echo 'Deposit ->'.$depositAmount.'<br>';



            //society other income bank general receipts only for withdraw data



            $otherIncomeAmount = 0;



            if (isset($SocietyOtherIncomeDetails) && !empty($SocietyOtherIncomeDetails)) {



                $otherIncomeCounter = $cashCounter;



                $flag = "Deposit";



                foreach ($SocietyOtherIncomeDetails as $otherIncomeData) {



                    $bankBookData[$otherIncomeData['SocietyOtherIncome']['payment_date']][$flag][$otherIncomeCounter]['payment_date'] = $otherIncomeData['SocietyOtherIncome']['payment_date'];



                    $bankBookData[$otherIncomeData['SocietyOtherIncome']['payment_date']][$flag][$otherIncomeCounter]['particulars'] = $otherIncomeData['SocietyOtherIncome']['title'];



                    $bankBookData[$otherIncomeData['SocietyOtherIncome']['payment_date']][$flag][$otherIncomeCounter]['withdrawal'] = "0.00";



                    $bankBookData[$otherIncomeData['SocietyOtherIncome']['payment_date']][$flag][$otherIncomeCounter]['deposit'] = $otherIncomeData['SocietyOtherIncome']['amount_paid'];



                    $bankBookData[$otherIncomeData['SocietyOtherIncome']['payment_date']][$flag][$otherIncomeCounter]['cheque_number'] = $otherIncomeData['SocietyOtherIncome']['cheque_no'];



                    $bankBookData[$otherIncomeData['SocietyOtherIncome']['payment_date']][$flag][$otherIncomeCounter]['payment_flag'] = 'Deposit';



                    $bankBookData[$otherIncomeData['SocietyOtherIncome']['payment_date']][$flag][$otherIncomeCounter]['link'] = "/societys/add_general_receipt/" . $otherIncomeData['SocietyOtherIncome']['id'];



                    $bankBookData[$otherIncomeData['SocietyOtherIncome']['payment_date']][$flag][$otherIncomeCounter]['ledgerexists'] = isset($allLedgers[$otherIncomeData['SocietyOtherIncome']['ledger_head_id']]) ? '1' : 0;



                    $otherIncomeAmount += $otherIncomeData['SocietyOtherIncome']['amount_paid'];







                    unset($otherIncomeData);



                    $otherIncomeCounter++;



                }



            }







            //echo 'Other Income ->'.$otherIncomeAmount.'<br>';



            //exit;







            if (isset($cashContraReceivedDetails) && !empty($cashContraReceivedDetails)) {



                $contraCounter = 0;



                foreach ($cashContraReceivedDetails as $contraData) {







                    if ($contraData['CashWithdraw']['txn_type'] != 'contra') {



                        continue;



                    }







                    $note = 'Contra Received';



                    //echo '<pre>'; print_r($cashWithdrawData);



                    $bankBookData[$contraData['CashWithdraw']['payment_date']][$flag][$cashCounter]['payment_date'] = $contraData['CashWithdraw']['payment_date'];



                    $bankBookData[$contraData['CashWithdraw']['payment_date']][$flag][$cashCounter]['particulars'] = $contraData['CashWithdraw']['particulars'] . '<br>' . $note;



                    $bankBookData[$contraData['CashWithdraw']['payment_date']][$flag][$cashCounter]['cheque_number'] = $contraData['CashWithdraw']['cheque_no'];



                    $bankBookData[$contraData['CashWithdraw']['payment_date']][$flag][$cashCounter]['withdrawal'] = '0.00';



                    $bankBookData[$contraData['CashWithdraw']['payment_date']][$flag][$cashCounter]['deposit'] = $contraData['CashWithdraw']['amount'];



                    $bankBookData[$contraData['CashWithdraw']['payment_date']][$flag][$cashCounter]['payment_flag'] = 'Contra';



                    $bankBookData[$contraData['CashWithdraw']['payment_date']][$flag][$cashCounter]['link'] = "/societys/add_cash_withdraws/" . $contraData['CashWithdraw']['id'];



                    $bankBookData[$contraData['CashWithdraw']['payment_date']][$flag][$cashCounter]['ledgerexists'] = 1;



                    unset($contraData);



                    $contraCounter++;



                }



            }







            ksort($bankBookData); //sorting society and member payment by payment 



            //echo '<pre>';print_r($otherIncomeData);die;



        }



        $this->set(compact('postData', 'societyBankLists', 'accountBankBbook', 'societyDetails', 'bankBookData', 'ledgerHeadDetails', 'openingBalance'));



    }







    function account_bank_reconciliation() {



        $societyLoginID = $this->societyloginID;



        $postData = $this->request->data;



        $accountBankReconciliation = array();



        $societyDetails = $this->SocietyBill->getSocietyDetails();



        $operator = '=';







        $options['AND'] = array('MemberPayment.society_id' => $societyLoginID);







        if (isset($this->request->data['MemberPayment']['amount']) && !empty($this->request->data['MemberPayment']['amount'])) {



            $options['AND']['MemberPayment.amount' . $operator . ' '] = $this->request->data['MemberPayment']['amount'];



        }



        if ($this->request->is(array('post'))) {



            $accountBankReconciliation = $this->MemberPayment->find('all', array('conditions' => $options));



        }



        $this->set(compact('postData', 'accountBankReconciliation', 'societyDetails'));



        // print_r($this->request->data);die;



    }







 function account_cash_book() {



        $this->loadModel('SocietyLedgerHeads');







        $societyLoginID = $this->societyloginID;



        $postData = $this->request->data;



        $accountCashBook = array();



        $societyDetails = $this->SocietyBill->getSocietyDetails();



        $operator = '=';



        $openingBalance = 0;







        $optionsSociety['AND'] = array('SocietyPayment.society_id' => $societyLoginID);



        $options['AND'] = array('MemberPayment.society_id' => $societyLoginID);



        $optionsCashWithdraw['AND'] = array('CashWithdraw.society_id' => $societyLoginID);



        $optionsOtherIncome['AND'] = array('SocietyOtherIncome.society_id' => $societyLoginID);







        if (isset($this->request->data['CashBook']['cash_ledger_head_id']) && !empty($this->request->data['CashBook']['cash_ledger_head_id'])) {



            $options['AND']['MemberPayment.society_bank_id'] = $this->request->data['CashBook']['cash_ledger_head_id'];



            $optionsSociety['AND']['SocietyPayment.payment_by_ledger_id'] = $this->request->data['CashBook']['cash_ledger_head_id'];



           // $optionsOtherIncome['AND']['SocietyOtherIncome.society_bank_id'] = $this->request->data['CashBook']['cash_ledger_head_id'];







            //



           $tempConn = mysql_connect('localhost', 'vedhtkjm_easylog', 'easyDatabase123');



          //  $tempConn = mysql_connect('localhost','root','');



            $db = $this->Session->read('Auth.dbConfig');



            if ($db == 'default') {



                $currentdb = 'vedhtkjm_easylogics';



            } else {



                $currentdb = 'vedhtkjm_easylogics';



            }



            $table = $currentdb . '.society_ledger_heads';



            $table1 = $currentdb . '.members';







































            $query = mysql_query("SELECT * FROM $table WHERE society_id = " . $societyLoginID . " and id='" . $this->request->data['CashBook']['cash_ledger_head_id'] . "'", $tempConn);



            $res = mysql_fetch_assoc($query);



            $ledgerHeadDetails['SocietyLedgerHeads'] = $res;







            $query = mysql_query("SELECT id, title FROM $table WHERE status = '" . Configure::read('Active') . "' AND society_id = " . $societyLoginID . " ", $tempConn);



            $allLedgers = array();



            while ($res = mysql_fetch_assoc($query)) {



                $allLedgers[$res['id']] = $res['title'];



            }



            $query = mysql_query("SELECT id, member_name FROM $table1 WHERE status = '" . Configure::read('Active') . "' AND society_id = " . $societyLoginID . " ", $tempConn);



            $allMembers = array();



            while ($res = mysql_fetch_assoc($query)) {



                $allMembers[$res['id']] = $res['member_name'];



            }



            mysql_close();



            //



            //For Ledger head opening balance



            /* $ledgerHeadDetails = $this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.id' => $this->request->data['CashBook']['cash_ledger_head_id'], '