<?php

App::uses('AppController', 'Controller');



/**

 * Users Controller

 *

 * @property User $User

 * @property PaginatorComponent $Paginator

 * @property SessionComponent $Session

 */

class ReportsController extends AppController {



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

        $this->set("societyBillObj", $this->SocietyBill);

        $this->loadModel('User');

        $this->loadModel('Member');

        $this->loadModel('Wing');

        $this->loadModel('Tenant');

        $this->loadModel('Society');

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



    function society() {

        $this->SocietyBill->getSocietyBuildingLists();

        $this->SocietyBill->getSocietyLedgerHeadsDetails();

        $this->SocietyBill->navigateSocietyMemberDetails();

    }



    function society_report_list_of_member() {

        $societyLoginID = $this->societyloginID;

        $postData = $this->request->data;

        $societyDetails = $this->SocietyBill->getSocietyDetails();
        $options['AND'] = array('Member.society_id' => $societyLoginID, 'Member.status' => Configure::read('Active'));
        if ($this->request->is(array('post'))) {

            

            if (isset($this->request->data['Member']['building_id']) && !empty($this->request->data['Member']['building_id'])) {

                $options['AND']['Member.building_id'] = $this->request->data['Member']['building_id'];

            }

            if (isset($this->request->data['Member']['wing_id']) && !empty($this->request->data['Member']['wing_id'])) {

                $options['AND']['Member.wing_id'] = $this->request->data['Member']['wing_id'];

            }



            if (isset($this->request->data['Member']['flat_no']) && !empty($this->request->data['Member']['flat_no']) && !empty($this->request->data['Member']['flat_no_to'])) {

                $options['OR']['Member.flat_no BETWEEN ? and ?'] = array($this->request->data['Member']['flat_no'], $this->request->data['Member']['flat_no_to']);

            } else if (isset($this->request->data['Member']['flat_no']) && !empty($this->request->data['Member']['flat_no'])) {

                $options['AND']['Member.flat_no'] = $this->request->data['Member']['flat_no'];

            } else if (isset($this->request->data['Member']['flat_no_to']) && !empty($this->request->data['Member']['flat_no_to'])) {

                $options['AND']['Member.flat_no'] = $this->request->data['Member']['flat_no_to'];

            }
        }        
        $wingMemberData = $this->Member->find('all', array('conditions' => $options, 'fields' => array('Member.member_prefix', 'Member.member_name', 'Member.member_email','Member.id', 'Member.member_photo', 'Member.floor_no', 'Building.building_name', 'Society.society_name', 'Wing.wing_name', 'Member.flat_no', 'Member.member_phone','Member.area'), 'order' => array('Member.flat_no' => 'asc')));
        // echo "<pre>";
        // print_r($wingMemberData);exit;
        $this->set(compact('postData','wingMemberData','societyDetails'));            

       //print_r($societyDetails);die;

    }



    function society_report_list_of_tenant() {

        $societyLoginID = $this->societyloginID;

        $postData = $this->request->data;

        $societyDetails = $this->SocietyBill->getSocietyDetails();

        if ($this->request->is(array('post'))) {

            $options['AND'] = array('Tenant.society_id' => $societyLoginID, 'Tenant.status' => Configure::read('Active'));

            if (isset($this->request->data['Tenant']['agreement_from']) && !empty($this->request->data['Tenant']['agreement_from'])) {

                $options['AND']['Tenant.agreement_on'] = $this->request->data['Tenant']['agreement_from'];

            }

            if (isset($this->request->data['Tenant']['building_id']) && !empty($this->request->data['Tenant']['building_id'])) {

                $options['OR']['Tenant.building_id'] = $this->request->data['Tenant']['building_id'];

            }

            if (isset($this->request->data['Tenant']['wing_id']) && !empty($this->request->data['Tenant']['wing_id'])) {

                $options['OR']['Tenant.wing_id'] = $this->request->data['Tenant']['wing_id'];

            }



            if (isset($this->request->data['Tenant']['flat_no']) && !empty($this->request->data['Tenant']['flat_no'])) {

                $options['AND']['Tenant.flat_no'] = $this->request->data['Tenant']['flat_no'];

            }



            $wingTenantData = $this->Tenant->find('all', array('conditions' => $options, 'fields' => array('Tenant.tenant_name', 'Tenant.id', 'Tenant.lease_type', 'Tenant.agreement_on', 'Tenant.rent_per_month', 'Building.building_name', 'Wing.wing_name', 'Tenant.phone'), 'order' => array('Tenant.tenant_name' => 'asc')));

            $this->request->data = $wingTenantData;

            $societyBuildingLists = $this->SocietyBill->getSocietyBuildingListsById();

            

        }

        $this->set(compact('postData','societyDetails','wingTenantData', 'societyBuildingLists'));

        //print_r($postData);die;

    }



    function bill_with_receipt(){

        $societyLoginID = $this->societyloginID;

        $postData = $this->request->data;

        $billingFrequenciesArray = $this->Util->societyBillingFrequency($this->societyBillingFrequencyId);

        $societyDetails = $this->SocietyBill->getSocietyDetails();

        $this->loadModel('SocietyLedgerHeads');

        $this->loadModel('SocietyTariffOrder');

        $this->loadModel('MemberBillSummary');

        $this->loadModel('MemberBillGenerate');

        $monthlyBillsSummaryDetails = array();

        

        /*Society bill taiffs*/

        $societyLedgerHeadTitleList = array();

        $societyLedgerHeads = $this->SocietyTariffOrder->find('all', array('fields'=>array('SocietyLedgerHeads.id','SocietyLedgerHeads.title'),'conditions' => array('SocietyLedgerHeads.society_id'=>$societyLoginID),'order'=>array('SocietyTariffOrder.tariff_serial' => 'ASC')));

        foreach($societyLedgerHeads as $head) {

            $societyLedgerHeadTitleList[$head['SocietyLedgerHeads']['id']] = $head['SocietyLedgerHeads']['title'];

        }

        

        $conditionQuery['AND'] = array('MemberBillSummary.society_id' => $societyLoginID);

        if (isset($this->request->data['MemberBillSummary']['month']) && !empty($this->request->data['MemberBillSummary']['month']) && !empty($this->request->data['MemberBillSummary']['month_to'])) {

            $conditionQuery['AND']['MemberBillSummary.month BETWEEN ? and ?'] = array($this->request->data['MemberBillSummary']['month'], $this->request->data['MemberBillSummary']['month_to']);

        } else if (isset($this->request->data['MemberBillSummary']['month']) && !empty($this->request->data['MemberBillSummary']['month'])) {

            $conditionQuery['AND']['MemberBillSummary.month'] = $this->request->data['MemberBillSummary']['month'];

        } else if (isset($this->request->data['MemberBillSummary']['month_to']) && !empty($this->request->data['MemberBillSummary']['month_to'])) {

            $conditionQuery['AND']['MemberBillSummary.month'] = $this->request->data['MemberBillSummary']['month_to'];

        }



        if (isset($this->request->data['MemberBillSummary']['bill_date']) && !empty($this->request->data['MemberBillSummary']['bill_date']) && !empty($this->request->data['MemberBillSummary']['bill_date_to'])) {

            $conditionQuery['AND']['MemberBillSummary.bill_generated_date BETWEEN ? and ?'] = array($this->request->data['MemberBillSummary']['bill_date'], $this->request->data['MemberBillSummary']['bill_date_to']);

        } else if (isset($this->request->data['MemberBillSummary']['bill_date']) && !empty($this->request->data['MemberBillSummary']['bill_date'])) {

            $conditionQuery['AND']['MemberBillSummary.bill_generated_date'] = $this->request->data['MemberBillSummary']['bill_date'];

        } else if (isset($this->request->data['MemberBillSummary']['bill_date_to']) && !empty($this->request->data['MemberBillSummary']['bill_date_to'])) {

            $conditionQuery['AND']['MemberBillSummary.bill_generated_date'] = $this->request->data['MemberBillSummary']['bill_date_to'];

        }



        if (isset($this->request->data['MemberBillSummary']['flat_no']) && !empty($this->request->data['MemberBillSummary']['flat_no']) && !empty($this->request->data['MemberBillSummary']['flat_no_to'])) {

            $conditionQuery['OR']['MemberBillSummary.flat_no BETWEEN ? and ?'] = array($this->request->data['MemberBillSummary']['flat_no'], $this->request->data['MemberBillSummary']['flat_no_to']);

        } else if (isset($this->request->data['MemberBillSummary']['flat_no_to']) && !empty($this->request->data['MemberBillSummary']['flat_no_to'])) {

            $conditionQuery['AND']['MemberBillSummary.flat_no'] = $this->request->data['MemberBillSummary']['flat_no_to'];

        } else if (isset($this->request->data['MemberBillSummary']['flat_no']) && !empty($this->request->data['MemberBillSummary']['flat_no'])) {

            $conditionQuery['AND']['MemberBillSummary.flat_no'] = $this->request->data['MemberBillSummary']['flat_no'];

        }

        

        if (isset($this->request->data['MemberBillSummary']['bill_no']) && !empty($this->request->data['MemberBillSummary']['bill_no']) && !empty($this->request->data['MemberBillSummary']['bill_no_to'])) {

            $conditionQuery['AND']['MemberBillSummary.bill_no BETWEEN ? and ?'] = array($this->request->data['MemberBillSummary']['bill_no'], $this->request->data['MemberBillSummary']['bill_no_to']);

        } else if (isset($this->request->data['MemberBillSummary']['bill_no']) && !empty($this->request->data['MemberBillSummary']['bill_no'])) {

            $conditionQuery['AND']['MemberBillSummary.bill_no'] = $this->request->data['MemberBillSummary']['bill_no'];

        } else if (isset($this->request->data['MemberBillSummary']['bill_date_to']) && !empty($this->request->data['MemberBillSummary']['bill_no_to'])) {

            $conditionQuery['AND']['MemberBillSummary.bill_no'] = $this->request->data['MemberBillSummary']['bill_date_to'];

        }



        if ($this->request->is(array('post'))) {

            $monthlyBillsSummaryDetails = $this->MemberBillSummary->find('all', array('conditions' => $conditionQuery, 'order' => array('MemberBillSummary.bill_no' => 'asc'), 'recursive' => -1));

        }

        if (isset($monthlyBillsSummaryDetails) && !empty($monthlyBillsSummaryDetails)) {

            $summaryCounter = 0;

            foreach ($monthlyBillsSummaryDetails as $monthlyData) {

                

                $monthlyBillsSummaryDetails[$summaryCounter]['MemberBillSummary']['monthName'] = $this->SocietyBill->monthWordFormatByBillingFrequency($monthlyBillsSummaryDetails[$summaryCounter]['MemberBillSummary']['month']);

                $this->Member->Behaviors->attach('Containable');

                $memberDetails = $this->Member->find('first', array('contain' => array('Building', 'Wing', 'Society'), 'conditions' => array('Member.id' => $monthlyData['MemberBillSummary']['member_id'])));

                $tariffDetails = $this->MemberBillGenerate->find('all', array('fields' => array('MemberBillGenerate.ledger_head_id', 'MemberBillGenerate.amount', 'SocietyLedgerHeads.title'), 'conditions' => array('MemberBillGenerate.society_id' => $societyLoginID, 'MemberBillGenerate.month' => $monthlyData['MemberBillSummary']['month'], 'MemberBillGenerate.member_id' => $monthlyData['MemberBillSummary']['member_id'], 'MemberBillGenerate.amount !=' => 0.00)));

                $memberPaymentDetails = $this->Member->MemberPayment->find('all', array('conditions' => array('MemberPayment.member_id' => $monthlyData['MemberBillSummary']['member_id'], 'MemberPayment.bill_generated_id' => $monthlyData['MemberBillSummary']['bill_no']), 'recursive' => -1, 'order' => array('MemberPayment.bill_generated_id' => 'asc')));



                if (isset($memberPaymentDetails) && !empty($memberPaymentDetails)) {

                    $monthlyBillsSummaryDetails[$summaryCounter]['MemberPayment'] = $memberPaymentDetails;

                }

                if (isset($memberDetails) && !empty($memberDetails['Member'])) {

                    $monthlyBillsSummaryDetails[$summaryCounter]['Member'] = $memberDetails['Member'];

                    $monthlyBillsSummaryDetails[$summaryCounter]['Wing'] = $memberDetails['Wing'];

                    $monthlyBillsSummaryDetails[$summaryCounter]['Building'] = $memberDetails['Building'];

                    $monthlyBillsSummaryDetails[$summaryCounter]['Society'] = $memberDetails['Society'];

                }

                if (isset($tariffDetails) && !empty($tariffDetails)) {

                    foreach ($tariffDetails as $key => $tariffData) {

                        $monthlyBillsSummaryDetails[$summaryCounter]['MemberTariff'][$key]['ledger_head_id'] = isset($tariffData['MemberBillGenerate']['ledger_head_id']) ? $tariffData['MemberBillGenerate']['ledger_head_id'] : '';

                        $monthlyBillsSummaryDetails[$summaryCounter]['MemberTariff'][$key]['amount'] = isset($tariffData['MemberBillGenerate']['amount']) ? $tariffData['MemberBillGenerate']['amount'] : '';

                        $monthlyBillsSummaryDetails[$summaryCounter]['MemberTariff'][$key]['title'] = isset($tariffData['SocietyLedgerHeads']['title']) ? $tariffData['SocietyLedgerHeads']['title'] : '';

                    }

                }

                $summaryCounter++;

            }

        }

        $this->set(compact('postData','billingFrequenciesArray', 'societyDetails', 'monthlyBillsSummaryDetails','societyLedgerHeadTitleList'));

        //print_r($monthlyBillsSummaryDetails);die;

        if (isset($monthlyBillsSummaryDetails) && !empty($monthlyBillsSummaryDetails)) {

            $this->render('bill_with_receipt_report');

        }

    }



    function society_memberwise_tariff(){

        $societyLoginID = $this->societyloginID;

        $this->loadModel('SocietyLedgerHeads');

        $this->loadModel('SocietyTariffOrder');

        $this->loadModel('MemberBillGenerate');

        $societyMemberTariffDetails = array();

        $postData = $this->request->data;

        $billingFrequenciesArray = $this->Util->societyBillingFrequency($this->societyBillingFrequencyId);

        $societyDetails = $this->SocietyBill->getSocietyDetails();



        if ($this->SocietyTariffOrder->hasAny(array('SocietyTariffOrder.society_id' => $societyLoginID))) {

            $societyLedgerHeadList = $this->SocietyTariffOrder->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyTariffOrder.id' => 'asc')));

        } else {

            $societyLedgerHeadList = $this->SocietyLedgerHeads->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID, 'SocietyLedgerHeads.is_in_bill_charges' => 1), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyLedgerHeads.title' => 'asc'), 'recursive' => -1));

        }

        $options['AND'] = array('MemberBillGenerate.society_id' => $this->societyloginID);



        if (isset($this->request->data['MemberTariff']['ledger_head_id']) && !empty($this->request->data['MemberTariff']['ledger_head_id'])) {

            $options['AND']['MemberBillGenerate.ledger_head_id'] = $this->request->data['MemberTariff']['ledger_head_id'];

        }



        if (isset($this->request->data['MemberTariff']['month']) && !empty($this->request->data['MemberTariff']['month']) && !empty($this->request->data['Member']['month_to'])) {

            $options['OR']['MemberBillGenerate.month BETWEEN ? and ?'] = array($this->request->data['MemberTariff']['month'], $this->request->data['Member']['month_to']);

        } else if (isset($this->request->data['MemberTariff']['month']) && !empty($this->request->data['MemberTariff']['month'])) {

            $options['AND']['MemberBillGenerate.month'] = $this->request->data['MemberTariff']['month'];

        } else if (isset($this->request->data['MemberTariff']['month_to']) && !empty($this->request->data['MemberTariff']['month_to'])) {

            $options['AND']['MemberBillGenerate.month'] = $this->request->data['MemberTariff']['month_to'];

        }



        if (isset($this->request->data['MemberTariff']['building_id']) && !empty($this->request->data['MemberTariff']['building_id'])) {

            $options['AND']['Member.building_id'] = $this->request->data['MemberTariff']['building_id'];

        }

        if (isset($this->request->data['MemberTariff']['wing_id']) && !empty($this->request->data['MemberTariff']['wing_id'])) {

            $options['AND']['Member.wing_id'] = $this->request->data['MemberTariff']['wing_id'];

        }



        if (isset($this->request->data['MemberTariff']['flat_no']) && !empty($this->request->data['MemberTariff']['flat_no']) && !empty($this->request->data['MemberTariff']['flat_no_to'])) {

            $options['OR']['Member.flat_no BETWEEN ? and ?'] = array($this->request->data['MemberTariff']['flat_no'], $this->request->data['MemberTariff']['flat_no_to']);

        } else if (isset($this->request->data['MemberTariff']['flat_no_to']) && !empty($this->request->data['MemberTariff']['flat_no_to'])) {

            $options['AND']['Member.flat_no'] = $this->request->data['MemberTariff']['flat_no_to'];

        } else if (isset($this->request->data['MemberTariff']['flat_no']) && !empty($this->request->data['MemberTariff']['flat_no'])) {

            $options['AND']['Member.flat_no'] = $this->request->data['MemberTariff']['flat_no'];

        }





        if ($this->request->is(array('post'))) {            

            $societyMemberTariffDetails = $this->MemberBillGenerate->find('all', array('conditions' => $options));

        }



        $this->set(compact('postData', 'billingFrequenciesArray', 'societyLedgerHeadList', 'societyMemberTariffDetails', 'societyDetails'));

    }



    function society_member_passbook() {

        $societyLoginID = $this->societyloginID;

        $this->loadModel('MemberBillSummary');

        $postData = $this->request->data;

        $societyMemberPassbook = array();

        $societyDetails = $this->SocietyBill->getSocietyDetails();

        $this->MemberBillSummary->Behaviors->attach('Containable');

        $options['AND'] = array('MemberBillSummary.society_id' => $this->societyloginID);

        if (isset($this->request->data['MemberPayment']['bill_type']) && !empty($this->request->data['MemberPayment']['bill_type'])) {
            $billType = $this->request->data['MemberPayment']['bill_type'];
            if ($billType == 'Regular') {
                $options['AND']['MemberBillSummary.bill_type'] = 'reg';
            } else if ($billType == 'Supplementary') {
                $options['AND']['MemberBillSummary.bill_type'] = 'sup';
            }
        }

        if (isset($this->request->data['MemberPayment']['payment_date']) && !empty($this->request->data['MemberPayment']['payment_date'])) {

            $options['AND']['MemberBillSummary.bill_generated_date <='] = $this->request->data['MemberPayment']['payment_date'];

        }

        if (isset($this->request->data['MemberPayment']['building_id']) && !empty($this->request->data['MemberPayment']['building_id'])) {

            $options['AND']['Member.building_id'] = $this->request->data['MemberPayment']['building_id'];

        }

        if (isset($this->request->data['MemberPayment']['wing_id']) && !empty($this->request->data['MemberPayment']['wing_id'])) {

            $options['AND']['Member.wing_id'] = $this->request->data['MemberPayment']['wing_id'];

        }

        if (isset($this->request->data['MemberPayment']['due_amt']) && !empty($this->request->data['MemberPayment']['due_amt']) && !empty($this->request->data['MemberPayment']['adv_amt'])) {

            $options['OR']['MemberBillSummary.op_due_amount BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['due_amt'], $this->request->data['MemberPayment']['adv_amt']);

        } else if (isset($this->request->data['MemberPayment']['due_amt']) && !empty($this->request->data['MemberPayment']['due_amt'])) {

            $options['OR']['MemberBillSummary.op_due_amount'] = $this->request->data['MemberPayment']['due_amt'];

        } else if (isset($this->request->data['MemberPayment']['adv_amt']) && !empty($this->request->data['MemberPayment']['adv_amt'])) {

            $options['OR']['MemberBillSummary.op_due_amount'] = $this->request->data['MemberPayment']['adv_amt'];

        }

        if (isset($this->request->data['MemberPayment']['flat_no']) && !empty($this->request->data['MemberPayment']['flat_no']) && !empty($this->request->data['MemberPayment']['flat_no_to'])) {

            $options['OR']['Member.flat_no BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['flat_no'], $this->request->data['MemberPayment']['flat_no_to']);

        } else if (isset($this->request->data['MemberPayment']['flat_no_to']) && !empty($this->request->data['MemberPayment']['flat_no_to'])) {

            $options['AND']['Member.flat_no'] = $this->request->data['MemberPayment']['flat_no_to'];

        } else if (isset($this->request->data['MemberPayment']['flat_no']) && !empty($this->request->data['MemberPayment']['flat_no'])) {

            $options['AND']['Member.flat_no'] = $this->request->data['MemberPayment']['flat_no'];

        }

        if ($this->request->is(array('post'))) {

            $societyMemberPassbook = $this->MemberBillSummary->find('all', array('contain' => array('Member'), 'conditions' => $options, 'order' => array('Member.flat_no' => 'asc', 'MemberBillSummary.bill_generated_date' => 'asc')));

        }

        $this->set(compact('postData', 'options', 'societyMemberPassbook', 'societyDetails'));

        //print_r($societyMemberPassbook);die;

    }



    function society_outstanding_bills() {

        $societyLoginID = $this->societyloginID;

        $this->loadModel('MemberBillSummary');

        $postData = $this->request->data;

        $societyMemberOutstandingBillDetails = array();

        $societyDetails = $this->SocietyBill->getSocietyDetails();

        $operator = '=';

        $options['AND'] = array('MemberBillSummary.society_id' => $societyLoginID);



        if (isset($this->request->data['MemberBillSummary']['bill_generated_date']) && !empty($this->request->data['MemberBillSummary']['bill_generated_date'])) {

            $options['AND']['MemberBillSummary.bill_generated_date'] = $this->Util->getFormatDate($this->request->data['MemberBillSummary']['bill_generated_date']);

        }



        if (isset($this->request->data['MemberBillSummary']['operator']) && !empty($this->request->data['MemberBillSummary']['operator'])) {

            $operator = $this->request->data['MemberBillSummary']['operator'];

        }



        if (isset($this->request->data['MemberBillSummary']['op_due_amount']) && !empty($this->request->data['MemberBillSummary']['op_due_amount'])) {

            $options['AND']['MemberBillSummary.balance_amount ' . $operator . ' '] = $this->request->data['MemberBillSummary']['op_due_amount'];

        }



        if (isset($this->request->data['MemberBillSummary']['building_id']) && !empty($this->request->data['MemberBillSummary']['building_id'])) {

            $options['AND']['Member.building_id'] = $this->request->data['MemberBillSummary']['building_id'];

        }

        if (isset($this->request->data['MemberBillSummary']['wing_id']) && !empty($this->request->data['MemberBillSummary']['wing_id'])) {

            $options['AND']['Member.wing_id'] = $this->request->data['MemberBillSummary']['wing_id'];

        }



        if (isset($this->request->data['MemberBillSummary']['flat_no']) && !empty($this->request->data['MemberBillSummary']['flat_no']) && !empty($this->request->data['MemberBillSummary']['flat_no_to'])) {

            $options['OR']['Member.flat_no BETWEEN ? and ?'] = array($this->request->data['MemberBillSummary']['flat_no'], $this->request->data['MemberBillSummary']['flat_no_to']);

        } else if (isset($this->request->data['MemberBillSummary']['flat_no_to']) && !empty($this->request->data['MemberBillSummary']['flat_no_to'])) {

            $options['AND']['Member.flat_no'] = $this->request->data['MemberBillSummary']['flat_no_to'];

        } else if (isset($this->request->data['MemberBillSummary']['flat_no']) && !empty($this->request->data['MemberBillSummary']['flat_no'])) {

            $options['AND']['Member.flat_no'] = $this->request->data['MemberBillSummary']['flat_no'];

        }

        //print_r($options);die;

        if ($this->request->is(array('post'))) {

            $societyMemberOutstandingBillDetails = $this->MemberBillSummary->find('all', array('conditions' => $options));

        }

        //print_r($societyMemberOutstandingBillDetails);die;



        $this->set(compact('postData','societyMemberOutstandingBillDetails','societyDetails'));

    }



    function society_payment_voucher() {

        $societyLoginID = $this->societyloginID;

        $this->loadModel('SocietyPayment');

        $postData = $this->request->data;

        $paymentVoucherDetails = array();

        //$societyDetails = $this->SocietyBill->getSocietyDetails();



        //$options['AND'] = array('SocietyPayment.society_id' => $societyLoginID);
$financialYearId = $this->Util->getFinancialYear();
$options['AND']['SocietyPayment.society_id']  = $societyLoginID;
$options['AND']['SocietyPayment.financial_year_id'] = $financialYearId;


        if (isset($this->request->data['SocietyPayment']['payment_date']) && !empty($this->request->data['SocietyPayment']['payment_date']) && !empty($this->request->data['SocietyPayment']['payment_date_to'])) {

            $options['OR']['SocietyPayment.payment_date BETWEEN ? and ?'] = array($this->request->data['SocietyPayment']['payment_date'], $this->request->data['SocietyPayment']['payment_date_to']);

        } else if (isset($this->request->data['SocietyPayment']['payment_date']) && !empty($this->request->data['SocietyPayment']['payment_date'])) {

            $options['AND']['SocietyPayment.payment_date'] = $this->Util->getFormatDate($this->request->data['SocietyPayment']['payment_date']);

        } else if (isset($this->request->data['SocietyPayment']['payment_date_to']) && !empty($this->request->data['SocietyPayment']['payment_date_to'])) {

            $options['AND']['SocietyPayment.payment_date'] = $this->Util->getFormatDate($this->request->data['SocietyPayment']['payment_date_to']);

        }



        if ($this->request->is(array('post'))) {

            $paymentVoucherDetails = $this->SocietyPayment->find('all', array('conditions' => $options));

        }



        $this->set(compact('postData', 'paymentVoucherDetails'));

        //print_r($paymentVoucherDetails);die;

    }



  function society_receipt_voucher() {

        $postData = $this->request->data;

        $this->loadModel('MemberPayment');

        $this->loadModel('MemberBillSettlement');

        $memberPaymentReceiptVoucherData = array();

        $societyDetails = $this->SocietyBill->getSocietyDetails();

       

        $allBankLists = $this->SocietyBill->getAllBankList();
        $financialYearId = $this->Util->getFinancialYear();



        if ($this->request->data['MemberPayment']['receipt_for'] == 'General'){



            $this->loadModel('SocietyOtherIncome');

            $options['AND']['SocietyOtherIncome.society_id'] = $this->societyloginID;

            $options['AND']['SocietyOtherIncome.status'] = Configure::read('Active');      

            $options['AND']['SocietyOtherIncome.financial_year_id'] = $financialYearId;
            
            
            if (isset($this->request->data['MemberPayment']['bill_no']) && !empty($this->request->data['MemberPayment']['bill_no']) && !empty($this->request->data['MemberPayment']['bill_no_to'])) {

                $options['OR']['SocietyOtherIncome.general_receipt_number BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['bill_no'], $this->request->data['MemberPayment']['bill_no_to']);

            }

             else if (isset($this->request->data['MemberPayment']['bill_no']) && !empty($this->request->data['MemberPayment']['bill_no'])) {

                $options['AND']['SocietyOtherIncome.general_receipt_number'] = $this->request->data['MemberPayment']['bill_no'];

            } else if (isset($this->request->data['MemberPayment']['bill_no_to']) && !empty($this->request->data['MemberPayment']['bill_no_to'])) {

                $options['AND']['SocietyOtherIncome.general_receipt_number'] = $this->request->data['MemberPayment']['bill_no_to'];

            }

            

            if (isset($this->request->data['MemberPayment']['bill_from']) && !empty($this->request->data['MemberPayment']['bill_from']) && !empty($this->request->data['MemberPayment']['bill_to'])) {

                $options['AND']['SocietyOtherIncome.payment_date BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['bill_from'], $this->request->data['MemberPayment']['bill_to']);



            } else if (isset($this->request->data['MemberPayment']['bill_from']) && !empty($this->request->data['MemberPayment']['bill_from'])) {

                $options['AND']['SocietyOtherIncome.payment_date'] = $this->request->data['MemberPayment']['bill_from'];



            } else if (isset($this->request->data['MemberPayment']['bill_to']) && !empty($this->request->data['MemberPayment']['bill_to'])) {

                $options['AND']['SocietyOtherIncome.payment_date'] = $this->request->data['MemberPayment']['bill_to'];

            }



            $options['AND'] = array('SocietyOtherIncome.society_id' => $this->societyloginID);



            

            $memberPaymentReceiptVoucherData = $this->SocietyOtherIncome->find('all', array('conditions' => $options,'order'=>array('SocietyOtherIncome.general_receipt_number' => 'asc')));



        } else {
            $options['AND']['MemberPayment.financial_year_id'] = $financialYearId;
            $options['AND']['MemberPayment.society_id'] = $this->societyloginID;

            if (isset($this->request->data['MemberPayment']['bill_no']) && !empty($this->request->data['MemberPayment']['bill_no']) && !empty($this->request->data['MemberPayment']['bill_no_to'])) {

                $options['OR']['MemberPayment.receipt_id BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['bill_no'], $this->request->data['MemberPayment']['bill_no_to']);

            }

            else if (isset($this->request->data['MemberPayment']['bill_from']) && !empty($this->request->data['MemberPayment']['bill_from']) && !empty($this->request->data['MemberPayment']['bill_to'])) {

               $options['OR']['MemberPayment.payment_date BETWEEN ? and ?'] = array($this->Util->getFormatDate($this->request->data['MemberPayment']['bill_from']), $this->Util->getFormatDate($this->request->data['MemberPayment']['bill_to']));

            }

             else if (isset($this->request->data['MemberPayment']['bill_no']) && !empty($this->request->data['MemberPayment']['bill_no'])) {

                $options['AND']['MemberPayment.bill_generated_id'] = $this->request->data['MemberPayment']['bill_no'];

            } else if (isset($this->request->data['MemberPayment']['bill_no_to']) && !empty($this->request->data['MemberPayment']['bill_no_to'])) {

                $options['AND']['MemberPayment.bill_generated_id'] = $this->request->data['MemberPayment']['bill_no_to'];

            }



            if (isset($this->request->data['MemberPayment']['building_id']) && !empty($this->request->data['MemberPayment']['building_id'])) {

                $options['AND']['Member.building_id'] = $this->request->data['MemberPayment']['building_id'];

            }

            if (isset($this->request->data['MemberPayment']['wing_id']) && !empty($this->request->data['MemberPayment']['wing_id'])) {

                $options['AND']['Member.wing_id'] = $this->request->data['MemberPayment']['wing_id'];

            }



            if (isset($this->request->data['MemberPayment']['flat_no']) && !empty($this->request->data['MemberPayment']['flat_no']) && !empty($this->request->data['MemberPayment']['flat_no_to'])) {

                $options['OR']['Member.flat_no BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['flat_no'], $this->request->data['MemberPayment']['flat_no_to']);

            } else if (isset($this->request->data['MemberPayment']['flat_no_to']) && !empty($this->request->data['MemberPayment']['flat_no_to'])) {

                $options['AND']['Member.flat_no'] = $this->request->data['MemberPayment']['flat_no_to'];

            } else if (isset($this->request->data['MemberPayment']['flat_no']) && !empty($this->request->data['MemberPayment']['flat_no'])) {

                $options['AND']['Member.flat_no'] = $this->request->data['MemberPayment']['flat_no'];

            }



echo "<!--";
            print_r($options);
            echo "-->";

            if ($this->request->is(array('post'))) {

                $memberPaymentReceiptVoucherData = $this->MemberPayment->find('all', array('conditions' => $options));

            }


            // To get bill month and bill no

            $receiptBillInfoArray = array();

            $memberBillSettlements = $this->MemberBillSettlement->find('all', array('conditions' => array('MemberBillSummary.society_id' => $this->societyloginID,'MemberBillSummary.financial_year_id'=>$financialYearId),array('fields'=>array('MemberBillSettlement.payment_id,MemberBillSettlement.bill_no','MemberBillSummary.bill_generated_date'))));

            

            if(!empty($memberBillSettlements)) {

                foreach($memberBillSettlements as $tempKey=>$settlement) {

                    $receiptBillInfoArray[$settlement['MemberBillSettlement']['payment_id']] = array('bill_no'=>$settlement['MemberBillSettlement']['bill_no'],'bill_date'=>$settlement['MemberBillSummary']['bill_generated_date']);

                }

            }

        }

       

        $this->set(compact('postData', 'memberPaymentReceiptVoucherData','allBankLists','societyDetails','receiptBillInfoArray'));

        

    }

    

    function journal_vouchers($journalVouchersId = null) {
        
        
        $financialYearId =  $this->Util->getFinancialYear();
        $societyLoginID = $this->societyloginID;

       
        $this->loadModel('SocietyLedgerHeads');
        $this->loadModel('MemberIdentification');
        $this->loadModel('JournalVoucher');
        $this->loadModel('MemberBillSummary');
        $postData = $this->request->data;

        $payment_type =  isset($postData['payment']['type'][0]) ? $postData['payment']['type'][0] : '';
        
        
        
        if(!empty($postData)){
            foreach ($postData as $key=>$value) {
                $data = array();
                $count=0;
                foreach ($value['type'] as $key=>$val) {    
                    $jvAllData[] =  $val."=".$value['jv_member_ledger_head_id'][$count]."=".$value['jv_amount_debit'][$count]."=".$value['jv_amount_credit'][$count];
                    $count++;
                }
            }
    
    
            foreach ($jvAllData as $value) {    
                $explodeData        =  explode("=", $value);
                $exploadMemberData  = explode("-",$explodeData[1]);
    
               if($exploadMemberData[0]=='member' && $explodeData[0]=='Debit'){
            $principal_balance =0;
            $interest_balance  =0;
            $tax_balance       =0;
    
            $conditionsArr  = array('MemberBillSummary.member_id' =>$exploadMemberData[1], 'MemberBillSummary.society_id' => $societyLoginID, 'MemberBillSummary.financial_year_id' => $financialYearId);
            $memberBillSummaryDetails = $this->MemberBillSummary->find('first', array('conditions' => $conditionsArr, 'order' => array('MemberBillSummary.bill_no' => 'desc'), 'recursive' => -1));
            
            $principal_balance   =  $memberBillSummaryDetails['MemberBillSummary']['principal_balance'];   
            $interest_balance    =  $memberBillSummaryDetails['MemberBillSummary']['interest_balance'];   
            $tax_balance         =  $memberBillSummaryDetails['MemberBillSummary']['tax_balance'];   
            
            switch ($payment_type) {
                case 'Principal Arrears':
                    $principal_balance  =  $memberBillSummaryDetails['MemberBillSummary']['principal_balance']+$explodeData[2];   
                    break;
                case 'Interest Arrears':
                    $interest_balance   =  $memberBillSummaryDetails['MemberBillSummary']['interest_balance']+$explodeData[2];   
                    break;
                case 'Tax Arrears':
                    $tax_balance        =  $memberBillSummaryDetails['MemberBillSummary']['tax_balance']+$explodeData[2];
                    break;
            }
    
             $balance_amount = $principal_balance+$interest_balance+$tax_balance;
     
                 $this->MemberBillSummary->updateAll(
                    array(
                        'MemberBillSummary.principal_balance'   => $principal_balance,
                        'MemberBillSummary.interest_balance'    => $interest_balance,
                        'MemberBillSummary.tax_balance'         => $tax_balance,
                        'MemberBillSummary.balance_amount'      => $balance_amount
                    ), 
                    array(
                        'MemberBillSummary.member_id' => $exploadMemberData[1], 
                        'MemberBillSummary.society_id' => $societyLoginID,
                        'MemberBillSummary.financial_year_id' => $financialYearId
                    )
                );
        }
               if($exploadMemberData[0]=='member' && $explodeData[0]=='Credit'){
    
                    $principal_balance =0;
                    $interest_balance  =0;
                    $tax_balance       =0;
                    $conditionsArr  = array('MemberBillSummary.member_id' =>$exploadMemberData[1], 'MemberBillSummary.society_id' => $societyLoginID ,'MemberBillSummary.financial_year_id' => $financialYearId);
                    $memberBillSummaryDetails = $this->MemberBillSummary->find('first', array('conditions' => $conditionsArr, 'order' => array('MemberBillSummary.bill_no' => 'desc'), 'recursive' => -1));
                    
                    if(!empty($memberBillSummaryDetails))  { 
                        $principal_balance   =  $memberBillSummaryDetails['MemberBillSummary']['principal_balance'];   
                        $interest_balance    =  $memberBillSummaryDetails['MemberBillSummary']['interest_balance'];   
                        $tax_balance         =  $memberBillSummaryDetails['MemberBillSummary']['tax_balance'];   
                        
                        switch ($payment_type) {
                            case 'Principal Arrears':
                                $principal_balance  =  $memberBillSummaryDetails['MemberBillSummary']['principal_balance']-$explodeData[3];   
                                break;
                            case 'Interest Arrears':
                                $interest_balance   =  $memberBillSummaryDetails['MemberBillSummary']['interest_balance']-$explodeData[3];   
                                break;
                            case 'Tax Arrears':
                                $tax_balance        =  $memberBillSummaryDetails['MemberBillSummary']['tax_balance']-$explodeData[3];
                                break;
                        }
                
                        $balance_amount = $principal_balance+$interest_balance+$tax_balance;
        
                         $this->MemberBillSummary->updateAll(
                                array(
                                    'MemberBillSummary.principal_balance'   => $principal_balance,
                                    'MemberBillSummary.interest_balance'    => $interest_balance,
                                    'MemberBillSummary.tax_balance'         => $tax_balance,
                                    'MemberBillSummary.balance_amount'      => $balance_amount
                                ), 
                                array(
                                    'MemberBillSummary.member_id' => $exploadMemberData[1], 
                                    'MemberBillSummary.society_id' => $societyLoginID,
                                    'MemberBillSummary.financial_year_id' => $financialYearId
                                )
                            );
                        }
                    }
                }

        }
        $journalVoucherDataArr = array();

        $memberListForJournalVouchers = $this->SocietyBill->getSocietyMemberList();
        $OldmemberListForJournalVouchers = $this->SocietyBill->getSocietyOldMeberFlatList();
        // member id -> previous owner's recorded name, from the same source that
        // fills the Old Members List dropdown. Used to label register rows that
        // were saved against an earlier transfer number.
        $oldMemberNameById = array();
        if (!empty($OldmemberListForJournalVouchers)) {
            foreach ($OldmemberListForJournalVouchers as $__om) {
                $__mid = isset($__om['member_identifications']['member_id']) ? $__om['member_identifications']['member_id'] : null;
                if (!empty($__mid) && isset($__om['member_identifications']['third_member'])) {
                    $oldMemberNameById[$__mid] = $__om['member_identifications']['third_member'];
                }
            }
        }        

        $flatNoList = $this->SocietyBill->getMemberFlatNoList();

        if ($this->request->is(array('post', 'put'))){ // start addition of a new JV
		    $journalVoucherDataArr['JournalVoucher']['society_id'] = $societyLoginID;
		    $journalId = $journalVoucherDataArr['JournalVoucher']['id'] = isset($postData['JournalVoucher']['voucher_no']) && !empty($postData['JournalVoucher']['voucher_no']) ? $postData['JournalVoucher']['voucher_no'] : null;

    		if ($journalId == '') {
    
    			$journalVoucherDataArr['JournalVoucher']['voucher_no'] = $journalId = $this->SocietyBill->memberJournalVoucherUniqueNumber();
    
    		}

	     	$journalVoucherDataArr['JournalVoucher']['voucher_date'] = isset($postData['JournalVoucher']['voucher_date']) ? $postData['JournalVoucher']['voucher_date'] : "";
		    $journalVoucherDataArr['JournalVoucher']['note'] = isset($postData['JournalVoucher']['note']) ? $postData['JournalVoucher']['note'] : "";
		    $success = array();



		

		    if (!empty($postData['JournalVoucher']['voucher_no'])){ // edit of jv

		foreach ($postData as $id => $value){

			if (!is_numeric($id)) continue;

			$jvMemberId = $jvLedgerId = 0;

			$journalVoucherDataArr['JournalVoucher']['id'] = $id;

			$journalVoucherDataArr['JournalVoucher']['voucher_no'] = $postData['JournalVoucher']['voucher_no'];

			$jvType = $postData[$id]['JournalVoucher']['jv_type'];

			$jvMemberId = !empty($postData[$id]['JournalVoucher']['jv_member_head_id'])?$postData[$id]['JournalVoucher']['jv_member_head_id']:0;




			$jvLedgerId = !empty($postData[$id]['JournalVoucher']['jv_ledger_head_id'])?$postData[$id]['JournalVoucher']['jv_ledger_head_id']:0;
            $isOldMember = false;
            if(strpos($jvMemberId,'-') != false){
                $isOldMember = true;
                $explaodedMemberId = explode('-',$jvMemberId);
                $jvMemberId = $explaodedMemberId[0];
            }
            
			

			if ($jvType == 'Credit'){

				



				$journalVoucherDataArr['JournalVoucher']['jv_credit_ledger_head_id'] = $jvLedgerId;

				$journalVoucherDataArr['JournalVoucher']['jv_credit_member_head_id'] = $jvMemberId;



				$journalVoucherDataArr['JournalVoucher']['jv_creadit_type'] = 'Credit';

				$journalVoucherDataArr['JournalVoucher']['jv_type'] = 'Credit';

				$journalVoucherDataArr['JournalVoucher']['jv_amount_credited'] = isset($postData[$id]['JournalVoucher']['jv_amount_credited']) ? $postData[$id]['JournalVoucher']['jv_amount_credited'] : "";



				$journalVoucherDataArr['JournalVoucher']['jv_debit_ledger_head_id'] = 0;

				$journalVoucherDataArr['JournalVoucher']['jv_debit_member_head_id'] = 0;



				$journalVoucherDataArr['JournalVoucher']['jv_amount_debited'] = 0;

				$journalVoucherDataArr['JournalVoucher']['jv_debit_type'] = '';

			}

			else {

				$journalVoucherDataArr['JournalVoucher']['jv_debit_ledger_head_id'] = $jvLedgerId;

			

				$journalVoucherDataArr['JournalVoucher']['jv_debit_member_head_id'] = $jvMemberId;



				$journalVoucherDataArr['JournalVoucher']['jv_debit_type'] = 'Debit';

				$journalVoucherDataArr['JournalVoucher']['jv_type'] = 'Debit';

				$journalVoucherDataArr['JournalVoucher']['jv_amount_debited'] = isset($postData[$id]['JournalVoucher']['jv_amount_debited']) ? $postData[$id]['JournalVoucher']['jv_amount_debited'] : "";



				$journalVoucherDataArr['JournalVoucher']['jv_credit_ledger_head_id'] = 0;

				$journalVoucherDataArr['JournalVoucher']['jv_credit_member_head_id'] = 0;



				$journalVoucherDataArr['JournalVoucher']['jv_amount_credited'] = 0;

				$journalVoucherDataArr['JournalVoucher']['jv_creadit_type'] = '';

			}

            $MemberTransfer = $this->Member->find('first', array('conditions' => array('Member.id' => $jvMemberId), 'fields' => array('Member.member_transfer')));
            $memberTransferStatusId = $MemberTransfer['Member']['member_transfer'];
            if($isOldMember){
                $memberTransferStatusId = $memberTransferStatusId-1;
            }
            
            $journalVoucherDataArr['JournalVoucher']['member_transfer'] = $memberTransferStatusId;



			$journalVoucherDataArr['JournalVoucher']['note'] = isset($postData['JournalVoucher']['note']) ? $postData['JournalVoucher']['note'] : "";	


    			
                    $voucherDate = $this->request->data['JournalVoucher']['voucher_date'];
                    if($this->Util->isDateInCurrentFinancialYear($voucherDate)){
    
            			if ($this->JournalVoucher->save($journalVoucherDataArr)) {
            
            				$success[] = 1;
            
            			}
            
            			else
            
            				$success[] = 0;
                    }
                    else{
                        $success[] = 0;
                    }
        
    		   }

	

		}

		    else { // addition of JV
		    
			    foreach ($postData['JournalVoucher']['type'] as $key => $jvType) {       
                $isOldMember = false;
				if (strstr($postData['JournalVoucher']['jv_member_ledger_head_id'][$key], 'member-')){
				    $memberId = str_replace('member-','',$postData['JournalVoucher']['jv_member_ledger_head_id'][$key]);
					$ledgerId = 0; 
                    if(strpos($memberId,'old') != false){
                        $isOldMember = true;
                        $explaodedMemberId = explode('-',$memberId);
                        $memberId = $explaodedMemberId[0];
                    }  							

				}else{

					$ledgerId =  str_replace('ledger-','',$postData['JournalVoucher']['jv_member_ledger_head_id'][$key]);

					$memberId = 0;

				}



				if ($jvType == 'Debit')	{
					$journalVoucherDataArr['JournalVoucher']['jv_debit_ledger_head_id'] = $ledgerId;
					$journalVoucherDataArr['JournalVoucher']['jv_debit_member_head_id'] = $memberId;
					$journalVoucherDataArr['JournalVoucher']['jv_credit_ledger_head_id'] = 0;
					$journalVoucherDataArr['JournalVoucher']['jv_credit_member_head_id'] = 0;
					$journalVoucherDataArr['JournalVoucher']['jv_amount_debited'] =  !empty($postData['JournalVoucher']['jv_amount_debit'][$key])?$postData['JournalVoucher']['jv_amount_debit'][$key]:0;
					$journalVoucherDataArr['JournalVoucher']['jv_amount_credited'] = 0;
					$journalVoucherDataArr['JournalVoucher']['jv_type'] = 'Debit';
					$journalVoucherDataArr['JournalVoucher']['jv_debit_type'] = 'Debit';

				}

				elseif ($jvType == 'Credit'){
					$journalVoucherDataArr['JournalVoucher']['jv_credit_ledger_head_id'] = $ledgerId;
					$journalVoucherDataArr['JournalVoucher']['jv_credit_member_head_id'] = $memberId;
					$journalVoucherDataArr['JournalVoucher']['jv_debit_ledger_head_id'] = 0;
					$journalVoucherDataArr['JournalVoucher']['jv_debit_member_head_id'] = 0;
					$journalVoucherDataArr['JournalVoucher']['jv_amount_credited'] =  !empty($postData['JournalVoucher']['jv_amount_credit'][$key])?$postData['JournalVoucher']['jv_amount_credit'][$key]:0;

					$journalVoucherDataArr['JournalVoucher']['jv_amount_debited'] = 0;
					$journalVoucherDataArr['JournalVoucher']['jv_type'] = 'Credit';
					$journalVoucherDataArr['JournalVoucher']['jv_creadit_type'] = 'Credit';

				}
                $MemberTransfer = $this->Member->find('first', array('conditions' => array('Member.id' => $memberId), 'fields' => array('Member.member_transfer')));
                $memberTranserStatusId = $MemberTransfer['Member']['member_transfer'];
                if($isOldMember){
                    $memberTranserStatusId = $memberTranserStatusId-1;
                }
                $journalVoucherDataArr['JournalVoucher']['member_transfer'] = $memberTranserStatusId;
                $journalVoucherDataArr['JournalVoucher']['financial_year_id'] = $financialYearId;




                $voucherDate = $this->request->data['JournalVoucher']['voucher_date'];
                if($this->Util->isDateInCurrentFinancialYear($voucherDate)){
    				if ($this->JournalVoucher->save($journalVoucherDataArr)) {
    					$success[] = 1;
    				}
    				else
    					$success[] = 0;
                }
                else
    					$success[] = 0;
			}
		}

	    	if (!in_array('0',$success)){
			    $this->Session->setFlash(__('The journal vouchers has been saved.'), 'success');
	    	}
	    	else
			$this->Session->setFlash(__('The journal vouchers could not be saved. Please, try again.'), 'error');
	        
	        $this->redirect(array('controller' => 'reports', 'action' => 'journal_vouchers'));

	}

	// end addition of a new jv



        if($journalVouchersId != ''){

            $postData = $this->JournalVoucher->find('all',array('conditions' => array('JournalVoucher.voucher_no' => $journalVouchersId,
            'JournalVoucher.society_id' => $societyLoginID,'JournalVoucher.financial_year_id' => $financialYearId),'order'=> array('JournalVoucher.voucher_no' => 'asc')));

        }

        



        $journalVoucherData = $this->JournalVoucher->find('all', array('conditions' => array('JournalVoucher.society_id' => $societyLoginID,'JournalVoucher.financial_year_id' => $financialYearId), 'order' => array('JournalVoucher.id' => 'asc')));

        if (isset($journalVoucherData) && count($journalVoucherData)) {

            foreach ($journalVoucherData as $counter => $jVData) {

                $CreditFlag = false;

                $debitFlag = false;

                $ledgerCondition = [];

                if (isset($jVData['JournalVoucher']['jv_credit_ledger_head_id']) && !empty($jVData['JournalVoucher']['jv_credit_ledger_head_id'])) {

                    $CreditFlag = true;

                    $ledgerCondition['AND']['SocietyLedgerHeads.id'] = $jVData['JournalVoucher']['jv_credit_ledger_head_id'];

                } else if (isset($jVData['JournalVoucher']['jv_debit_ledger_head_id']) && !empty($jVData['JournalVoucher']['jv_debit_ledger_head_id'])) {

                    $ledgerCondition['AND']['SocietyLedgerHeads.id'] = $jVData['JournalVoucher']['jv_debit_ledger_head_id'];

                    $debitFlag = true;

                }



                $jVoucherSocietyLedgerData = $this->SocietyLedgerHeads->find('first', array('conditions' => $ledgerCondition, 'order' => array('SocietyLedgerHeads.id' => 'asc'), 'recursive' => -1));

                if ($CreditFlag) {

                    $journalVoucherData[$counter]['JournalVoucher']['creadit_title'] = isset($jVoucherSocietyLedgerData['SocietyLedgerHeads']['title']) ? $jVoucherSocietyLedgerData['SocietyLedgerHeads']['title'] : "";

                }else if($debitFlag){

                    $journalVoucherData[$counter]['JournalVoucher']['debit_title'] = isset($jVoucherSocietyLedgerData['SocietyLedgerHeads']['title']) ? $jVoucherSocietyLedgerData['SocietyLedgerHeads']['title'] : "";

                }

                $memberCondition = [];

                if (isset($jVData['JournalVoucher']['jv_credit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_credit_member_head_id'])) {

                    $memberCondition['AND']['Member.id'] = $jVData['JournalVoucher']['jv_credit_member_head_id'];

                    $CreditFlag = true;

                } else if (isset($jVData['JournalVoucher']['jv_debit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_debit_member_head_id'])) {

                    $memberCondition['AND']['Member.id'] = $jVData['JournalVoucher']['jv_debit_member_head_id'];

                    $debitFlag = true;

                }

                $memberData = $this->Member->find('first', array('conditions' => $memberCondition, 'order' => array('Member.id' => 'asc'), 'recursive' => -1));
                // A row saved with a lower transfer number belongs to the previous
                // owner; transfer has since overwritten member_name, so show the old name.
                $jvMemberDisplayName = isset($memberData['Member']['member_name']) ? $memberData['Member']['member_name'] : "";
                $__mid  = isset($memberData['Member']['id']) ? $memberData['Member']['id'] : null;
                $__jvT  = isset($jVData['JournalVoucher']['member_transfer']) ? $jVData['JournalVoucher']['member_transfer'] : null;
                $__curT = isset($memberData['Member']['member_transfer']) ? $memberData['Member']['member_transfer'] : null;
                if ($__mid !== null && $__jvT !== null && $__curT !== null && $__jvT < $__curT
                        && isset($oldMemberNameById[$__mid]) && $oldMemberNameById[$__mid] !== '') {
                    $jvMemberDisplayName = $oldMemberNameById[$__mid];
                }
                // Show the flat number with the name, as the dropdown does.
                if ($jvMemberDisplayName !== '' && isset($flatNoList[$__mid]) && $flatNoList[$__mid] !== '') {
                    $jvMemberDisplayName .= ' -- ' . $flatNoList[$__mid];
                }

                if ($CreditFlag && !empty($jVData['JournalVoucher']['jv_credit_member_head_id'])) {
                    $journalVoucherData[$counter]['JournalVoucher']['creadit_title'] = $jvMemberDisplayName;
                }

                if ($debitFlag && !empty($jVData['JournalVoucher']['jv_debit_member_head_id'])) {
                    $journalVoucherData[$counter]['JournalVoucher']['debit_title'] = $jvMemberDisplayName;
                }

            }

        }

    	$voucherNo = $journalVouchersId;

        $ledgerHeadForJournalVouchers = $this->SocietyLedgerHeads->find('list', array('conditions' => array('SocietyLedgerHeads.society_head_sub_category_id !=' => array(20, 21), 'SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID), 'order' => array('SocietyLedgerHeads.title' => 'asc'), 'recursive' => -1));

        $this->set(compact('memberListForJournalVouchers','postData','ledgerHeadForJournalVouchers','journalVoucherData','voucherNo','flatNoList','OldmemberListForJournalVouchers'));

    }



    function journal_vouchers_new($journalVouchersId = null) {

        $societyLoginID = $this->societyloginID;

        $this->loadModel('SocietyLedgerHeads');

        $this->loadModel('JournalVoucher');

        $postData = $this->request->data;

        $journalVoucherDataArr = array();

        $memberListForJournalVouchers = $this->SocietyBill->getSocietyMemberList();

        

        if ($this->request->is(array('post', 'put'))){

            if (isset($postData['JournalVoucher']) && isset($postData['JournalVoucher']['jv_creadit_type']) || isset($postData['JournalVoucher']['jv_debit_type'])) {

                $journalVoucherDataArr['JournalVoucher']['society_id'] = $societyLoginID;

                $journalId = $journalVoucherDataArr['JournalVoucher']['id'] = isset($postData['JournalVoucher']['id']) && !empty($postData['JournalVoucher']['id']) ? $postData['JournalVoucher']['id'] : null;

                if ($journalId == '') {

                    $journalVoucherDataArr['JournalVoucher']['voucher_no'] = $this->SocietyBill->memberJournalVoucherUniqueNumber();

                }

                $journalVoucherDataArr['JournalVoucher']['voucher_date'] = isset($postData['JournalVoucher']['voucher_date']) ? $postData['JournalVoucher']['voucher_date'] : "";

                

                

                $journalVoucherDataArr['JournalVoucher']['jv_credit_ledger_head_id'] = isset($postData['JournalVoucher']['jv_credit_ledger_head_id']) ? $postData['JournalVoucher']['jv_credit_ledger_head_id'] : "";

                $journalVoucherDataArr['JournalVoucher']['jv_debit_ledger_head_id'] = isset($postData['JournalVoucher']['jv_debit_ledger_head_id']) ? $postData['JournalVoucher']['jv_debit_ledger_head_id'] : "";

                $journalVoucherDataArr['JournalVoucher']['jv_credit_member_head_id'] = isset($postData['JournalVoucher']['jv_credit_member_head_id']) ? $postData['JournalVoucher']['jv_credit_member_head_id'] : "";

                $journalVoucherDataArr['JournalVoucher']['jv_debit_member_head_id'] = isset($postData['JournalVoucher']['jv_debit_member_head_id']) ? $postData['JournalVoucher']['jv_debit_member_head_id'] : "";

                $journalVoucherDataArr['JournalVoucher']['jv_debit_type'] = isset($postData['JournalVoucher']['jv_debit_type']) ? $postData['JournalVoucher']['jv_debit_type'] : "";

                $journalVoucherDataArr['JournalVoucher']['jv_creadit_type'] = isset($postData['JournalVoucher']['jv_creadit_type']) ? $postData['JournalVoucher']['jv_creadit_type'] : "";

                $journalVoucherDataArr['JournalVoucher']['jv_amount_debited'] = isset($postData['JournalVoucher']['jv_amount_debited']) ? $postData['JournalVoucher']['jv_amount_debited'] : "";

                $journalVoucherDataArr['JournalVoucher']['jv_amount_credited'] = isset($postData['JournalVoucher']['jv_amount_credited']) ? $postData['JournalVoucher']['jv_amount_credited'] : "";



                $journalVoucherDataArr['JournalVoucher']['note'] = isset($postData['JournalVoucher']['note']) ? $postData['JournalVoucher']['note'] : "";



                if ($this->JournalVoucher->save($journalVoucherDataArr)) {

                    $this->Session->setFlash(__('The journal vouchers has been saved.'), 'success');

                } else {

                    $this->Session->setFlash(__('The journal vouchers could not be saved. Please, try again.'), 'error');

                }

            } else {

                $this->Session->setFlash(__('Select one account name. Please, try again.'), 'error');

            }

            $this->redirect(array('controller' => 'reports', 'action' => 'journal_vouchers'));

        }



        if($journalVouchersId != ''){

            $postData = $this->JournalVoucher->find('first',array('conditions' => array('JournalVoucher.id' => $journalVouchersId,'JournalVoucher.society_id' => $societyLoginID),'order'=> array('JournalVoucher.id' => 'asc')));

            if(isset($postData['JournalVoucher']['jv_debit_type']) && !empty($postData['JournalVoucher']['jv_debit_type'])){

                $postData['JournalVoucher']['jv_type'] = $postData['JournalVoucher']['jv_debit_type'];

            }else if(isset($postData['JournalVoucher']['jv_creadit_type']) && !empty($postData['JournalVoucher']['jv_creadit_type'])){

                $postData['JournalVoucher']['jv_type'] = $postData['JournalVoucher']['jv_creadit_type'];

            }

        }

        

        $journalVoucherData = $this->JournalVoucher->find('all', array('conditions' => array('JournalVoucher.society_id' => $societyLoginID), 'order' => array('JournalVoucher.id' => 'asc')));

        if (isset($journalVoucherData) && count($journalVoucherData)) {

            foreach ($journalVoucherData as $counter => $jVData) {

                $CreditFlag = false;

                $debitFlag = false;

                $ledgerCondition = "";

                if (isset($jVData['JournalVoucher']['jv_credit_ledger_head_id']) && !empty($jVData['JournalVoucher']['jv_credit_ledger_head_id'])) {

                    $CreditFlag = true;

                    $ledgerCondition['AND']['SocietyLedgerHeads.id'] = $jVData['JournalVoucher']['jv_credit_ledger_head_id'];

                } else if (isset($jVData['JournalVoucher']['jv_debit_ledger_head_id']) && !empty($jVData['JournalVoucher']['jv_debit_ledger_head_id'])) {

                    $ledgerCondition['AND']['SocietyLedgerHeads.id'] = $jVData['JournalVoucher']['jv_debit_ledger_head_id'];

                    $debitFlag = true;

                }



                $jVoucherSocietyLedgerData = $this->SocietyLedgerHeads->find('first', array('conditions' => $ledgerCondition, 'order' => array('SocietyLedgerHeads.id' => 'asc'), 'recursive' => -1));

                if ($CreditFlag) {

                    $journalVoucherData[$counter]['JournalVoucher']['creadit_title'] = isset($jVoucherSocietyLedgerData['SocietyLedgerHeads']['title']) ? $jVoucherSocietyLedgerData['SocietyLedgerHeads']['title'] : "";

                }else if($debitFlag){

                    $journalVoucherData[$counter]['JournalVoucher']['debit_title'] = isset($jVoucherSocietyLedgerData['SocietyLedgerHeads']['title']) ? $jVoucherSocietyLedgerData['SocietyLedgerHeads']['title'] : "";

                }



                $memberCondition = "";

                if (isset($jVData['JournalVoucher']['jv_credit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_credit_member_head_id'])) {

                    $memberCondition['AND']['Member.id'] = $jVData['JournalVoucher']['jv_credit_member_head_id'];

                    $CreditFlag = true;

                } else if (isset($jVData['JournalVoucher']['jv_debit_member_head_id']) && !empty($jVData['JournalVoucher']['jv_debit_member_head_id'])) {

                    $memberCondition['AND']['Member.id'] = $jVData['JournalVoucher']['jv_debit_member_head_id'];

                    $debitFlag = true;

                }



                $memberData = $this->Member->find('first', array('conditions' => $memberCondition, 'order' => array('Member.id' => 'asc'), 'recursive' => -1));

                if ($CreditFlag) {

                    $journalVoucherData[$counter]['JournalVoucher']['creadit_title'] = isset($memberData['Member']['member_name']) ? $memberData['Member']['member_name'] : "";

                }

                

                if($debitFlag){

                    $journalVoucherData[$counter]['JournalVoucher']['debit_title'] = isset($memberData['Member']['member_name']) ? $memberData['Member']['member_name'] : "";

                }

            }

        }



        $ledgerHeadForJournalVouchers = $this->SocietyLedgerHeads->find('list', array('conditions' => array('SocietyLedgerHeads.society_head_sub_category_id !=' => array(20, 21), 'SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID), 'order' => array('SocietyLedgerHeads.id' => 'asc'), 'recursive' => -1));

        $this->set(compact('memberListForJournalVouchers','postData','ledgerHeadForJournalVouchers','journalVoucherData'));

    }



    function getLedgerHeadTypeByCreditDebit($postData = array()){

        $getLedgerID = '';

        if (!empty($postData['JournalVoucher']['jv_member_head_id']) && !empty($postData['JournalVoucher']['jv_ledger_head_id'])) {

            $getLedgerID = "";

        } else if (!empty($postData['JournalVoucher']['jv_member_head_id'])) {

            $getLedgerID = $postData['JournalVoucher']['jv_member_head_id'];

        } else if (!empty($postData['JournalVoucher']['jv_ledger_head_id'])) {

            $getLedgerID = $postData['JournalVoucher']['jv_ledger_head_id'];

        } else {

            $getLedgerID = "";

        }

        return $getLedgerID;

    }



	function delete_journal_voucher($voucher_no){

		$societyLoginID = $this->societyloginID;

		

		if (!empty($voucher_no) && !empty($societyLoginID)){

			$this->loadModel('JournalVoucher');



			$jvData = $this->JournalVoucher->find('all', array('conditions' => array('society_id' => $societyLoginID,'voucher_no'=>$voucher_no)),array('fields'=>array('JournalVoucher.id')));



			$ids = array();

			foreach ($jvData as $key => $data){

				$ids[] = $data['JournalVoucher']['id'];

			}

			

			



			if ($this->JournalVoucher->deleteAll(array('JournalVoucher.id'=>$ids)))



				$this->Session->setFlash(__('The journal voucher has been deleted'), 'success');

			else

				$this->Session->setFlash(__('There was an error deleting the  journal voucher. Please, try again.'), 'error');

			$this->redirect(array('controller' => 'reports', 'action' => 'journal_vouchers'));

		}

	}
	
	public function bill_with_receipt_tabular(){
	    
	}



}

