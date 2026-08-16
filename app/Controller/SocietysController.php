<?php



App::uses('AppController', 'Controller');

App::uses('CakeEmail', 'Network/Email');



/**

 * Users Controller

 *

 * @property User $User

 * @property PaginatorComponent $Paginator

 * @property SessionComponent $Session

 */ 

class SocietysController extends AppController {



    /**

     * Components

     *

     * @var array

     */

    public $components = array('Paginator', 'Session', 'RequestHandler', 'Util', 'SocietyBill', 'PhpExcel');

    public $helpers = array('Js');

    public $societyloginID = '';
    



    //public $uses = array('Society');



    public function beforeFilter() {

        parent::beforeFilter();

        $this->layout = 'society_dashboard';

        $this->societyloginID = $this->Session->read('Auth.User.id');

        $this->set("utilObj", $this->Util);
        
        $this->loadModel('User');

        $this->loadModel('Member');

        $this->loadModel('Society');

        $this->loadModel('Wing');

        $this->loadModel('Tenant');

        $this->loadModel('SocietyPayment');

        $this->loadModel('CashWithdraw');

        $this->loadModel('SocietyLedgerHeads');

        $this->loadModel('SocietyOtherIncome');

        $this->loadModel('MemberBillSummary');

        $this->loadModel('MemberPayment');
        $this->loadModel('SocietyPayment');

        if (!$this->RequestHandler->isAjax()) {
            $this->loadAllNavigationModals();
        }



	

    }



    function loadAllNavigationModals() {

        $this->SocietyBill->getSocietyBuildingLists();

        $this->SocietyBill->getSocietyLedgerHeadsDetails();

        $this->SocietyBill->navigateSocietyMemberDetails();

        $this->SocietyBill->loadAllNavigationModalsDropdowns();

    }



    function sendMailWithAttachment($template = null, $to_email = null, $from_email = null, $subject = null, $contents = array()) {

        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);



        /* $from_email = 'noreply@xyz.com';

          $contents = array('shobha');

          $to_email = 'shobha.solanki@gmail.com'

          $email = new CakeEmail();

          $result = $email->template($template, 'default')

          ->emailFormat('html')

          ->to($to_email)

          ->from($from_email)

          ->subject($subject)

          ->attachments('/home/sysadmin/mozilla.pdf')

          ->viewVars($contents);

          if ($email->send('default')) {

          return true;

          }

          return false; */





        // $Email = new CakeEmail("smtp");

        // $Email->from(array('me@example.com' => 'My Site'));

        // $Email->to('shobha.solanki@gmail.com');

        // $Email->subject('About');

        // $Email->send('My message');

        return true;

    }



    public function dashboard() {
        $this->loadModel('MemberBillGenerate');

        $societyCollectionSummary = array();

        $societyFlatsSummary = array();

        $dashboardBillSummaryDetails = array();
        
        $financialYearId = $this->Util->getFinancialYear();

        $getSocietyCashPaymentDetails = $this->SocietyPayment->find('first', array('conditions' => array('SocietyPayment.society_id' => $this->Session->read('Auth.User.id'),
        'SocietyPayment.payment_type' => Configure::read('PaymentType.Cash'),'SocietyPayment.financial_year_id' => $financialYearId), 'fields' => array('SUM(SocietyPayment.amount) as cashPayment'),
        'order' => array('SocietyPayment.payment_date' => 'desc')));

        $getSocietyBankPaymentDetails = $this->SocietyPayment->find('first', array('conditions' => array('SocietyPayment.society_id' => $this->Session->read('Auth.User.id'),
        'SocietyPayment.payment_type' => Configure::read('PaymentType.Bank'),'SocietyPayment.financial_year_id' => $financialYearId), 'fields' => array('SUM(SocietyPayment.amount) as bankPayment'),
        'order' => array('SocietyPayment.payment_date' => 'desc')));

        if (isset($getSocietyCashPaymentDetails) && !empty($getSocietyCashPaymentDetails)) {

            $societyCollectionSummary['cashPayment'] = isset($getSocietyCashPaymentDetails[0]['cashPayment']) ? $getSocietyCashPaymentDetails[0]['cashPayment'] : 0;

        }

        if (isset($getSocietyBankPaymentDetails) && !empty($getSocietyBankPaymentDetails)) {

            $societyCollectionSummary['bankPayment'] = isset($getSocietyBankPaymentDetails[0]['bankPayment']) ? $getSocietyBankPaymentDetails[0]['bankPayment'] : 0;

        }



        $countTotalFlats = $this->Member->find('count', array('conditions' => array('Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active')), 'recursive' => -1));

        $countCommercialFlats = $this->Member->find('count', array('conditions' => array('Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active'), 'unit_type' => Configure::read('UnitType.Commercial')), 'recursive' => -1));

        $countResidentialFlats = $this->Member->find('count', array('conditions' => array('Member.society_id' => $this->Session->read('Auth.User.id'), 'Member.status' => Configure::read('Active'), 'unit_type' => Configure::read('UnitType.Residency')), 'recursive' => -1));



        $societyFlatsSummary['flatCount'] = isset($countTotalFlats) ? $countTotalFlats : 0;

        $societyFlatsSummary['commercialFlats'] = isset($countCommercialFlats) ? $countCommercialFlats : 0;

        $societyFlatsSummary['residentialCount'] = isset($countResidentialFlats) ? $countResidentialFlats : 0;



        $getSocietyExpensesDetails = $this->SocietyPayment->find('all', array('conditions' => array('SocietyPayment.society_id' => $this->Session->read('Auth.User.id'), 
        'SocietyPayment.status' => Configure::read('Active'),'SocietyPayment.financial_year_id' => $financialYearId),
        'fields' => array('SocietyPayment.id', 'SocietyPayment.particulars', 'SocietyPayment.amount'), 
        'limit' => 5, 'order' => array('SocietyPayment.payment_date' => 'desc')));

        $getDuesFromMemberDetails = $this->MemberBillSummary->find('all', array('conditions' =>
        array('MemberBillSummary.society_id' => $this->Session->read('Auth.User.id'),'MemberBillSummary.financial_year_id' => $financialYearId),
        'fields' => array('MemberBillSummary.id', 'MemberBillSummary.flat_no', 'MemberBillSummary.amount_payable'), 
        'limit' => 5, 'order' => array('MemberBillSummary.amount_payable' => 'desc'), 'recursive' => -1));

        $getReceiptDetails = $this->MemberPayment->find('all', array('conditions' => array('MemberPayment.society_id' => $this->Session->read('Auth.User.id'),
        'MemberPayment.financial_year_id' => $financialYearId), 'fields' => array('MemberPayment.id',
        'MemberPayment.bill_generated_id', 'MemberPayment.amount_paid'), 'limit' => 5, 'order' => array('MemberPayment.id' => 'desc'), 'recursive' => -1));

        $getSocietyLatestPaymentDetails = $this->SocietyPayment->find('all', array('conditions' => array('SocietyPayment.society_id' => $this->Session->read('Auth.User.id'),
        'SocietyPayment.financial_year_id' => $financialYearId,
        'SocietyPayment.status' => Configure::read('Active')), 'fields' => array('SocietyPayment.id', 'SocietyPayment.payment_date', 
         'SocietyPayment.amount'), 'limit' => 5, 'order' => array('SocietyPayment.payment_date' => 'desc'), 'recursive' => -1));

        //Dashboard Bill Summary

        $monthList = $this->MemberBillSummary->find('list', array('fields' => array('MemberBillSummary.month', 'MemberBillSummary.month'), 
        'conditions' => array('MemberBillSummary.society_id' => $this->Session->read('Auth.User.id'),'MemberBillSummary.financial_year_id' => $financialYearId), 
        'limit' => 5, 'order' => array('MemberBillSummary.month' => 'asc'), 'recursive' => -1));

        if (isset($monthList) && !empty($monthList)) {

            foreach ($monthList as $month) {

                $memberBillSummaryDetails = $this->MemberBillSummary->find('first', array('fields' => array('SUM(MemberBillSummary.amount_payable) as amount_payable',
                'SUM(MemberBillSummary.principal_paid) as principal_paid', 'SUM(MemberBillSummary.op_due_amount) as op_due_amount'), 
                'conditions' => array('MemberBillSummary.society_id' => $this->Session->read('Auth.User.id'), 'MemberBillSummary.financial_year_id' => $financialYearId), 
                'order' => array('MemberBillSummary.month' => 'asc'), 'recursive' => -1));

                $dashboardBillSummaryDetails[$month]['amount'] = isset($memberBillSummaryDetails[0]['amount_payable']) ? $memberBillSummaryDetails[0]['amount_payable'] : 0;

                $dashboardBillSummaryDetails[$month]['collectionAmount'] = isset($memberBillSummaryDetails[0]['principal_paid']) ? $memberBillSummaryDetails[0]['principal_paid'] : 0;

                $dashboardBillSummaryDetails[$month]['dueAmount'] = isset($memberBillSummaryDetails[0]['op_due_amount']) ? $memberBillSummaryDetails[0]['op_due_amount'] : 0;

                $dashboardBillSummaryDetails[$month]['monthName'] = $this->Util->getMonthWordFormat($month);

            }

        }

        $this->set(compact('societyFlatsSummary', 'societyCollectionSummary', 'getSocietyExpensesDetails', 'getDuesFromMemberDetails', 'getReceiptDetails', 'getSocietyLatestPaymentDetails', 'dashboardBillSummaryDetails'));

    }



    public function index() {

        

    }



    public function update_societys($societyUserID = null) {

        if ($this->request->is('ajax')) {

            $response = array();

            $response['error'] = 1;

            $response['error_message'] = 'Opps! Somthing went wrong.';

            if ($this->request->data) {

                if ($this->Society->save($this->request->data['Society'])) {

                    $response['error'] = 0;

                    $response['error_message'] = 'Society Data has been updated successfully';

                }

            }

            return new CakeResponse(array('body' => json_encode($response), 'status' => 200));

        } else {

            $conditions = array('Society.id' => $societyUserID);

            if ($this->Society->hasany($conditions)) {

                $singleSocietyRecord = $this->Society->find("first", array('conditions' => array('Society.id' => $societyUserID)));

                $this->set('singleSocietyRecord', $singleSocietyRecord);

            } else {

                throw new NotFoundException(__('Invalid society id'));

            }

        }

    }



    public function building_identitys($buildingId = null) {

         $societyUserId = $this->Session->read('Auth.User.id');


        $message = "added";

        if ($this->request->is(array('get'))) {

            $this->request->data = $this->Society->Building->find('first', array('conditions' => array('Building.id' => $buildingId)));

        }

        if ($this->request->is(array('post', 'put'))) {

            if ($buildingId != '') {

                $message = "updated";

                $this->Society->Building->id = $buildingId;

            }

            //$buildingModelObj = ClassRegistry::init('Building');

            $this->Society->Building->set($this->request->data);



            if (!$this->Society->Building->validates()) {

                $errors = $this->Society->Building->validationErrors;

                if (isset($errors['num_flats'])) {

                    $this->Session->setFlash(__('The building could not be saved .' . $errors['num_flats'][0]), 'error');

                }

                $this->redirect(array('controller' => 'societys', 'action' => 'building_identitys', $buildingId));

            } else {

                $this->request->data['Building']['cdate'] = $this->Util->getDateTime();

                $this->request->data['Building']['udate'] = $this->Util->getDateTime();

                if ($this->Society->Building->save($this->request->data)) {

                    $this->Session->setFlash(__('The building has been ' . $message . '.'), 'success');

                } else {

                    $this->Session->setFlash(__('The building could not be saved. Please, try again.'), 'error');

                }

            }

            $this->redirect(array('controller' => 'societys', 'action' => 'building_identitys'));

        }

        $societyBuildingLists = $this->Society->Building->find('all', array('conditions' => array('Building.status' => Configure::read('Active'), 'Building.society_id' => $societyUserId), 'fields' => array('Building.building_name', 'Society.society_name', 'Building.society_id', 'Building.num_flats', 'Building.udate', 'Society.user_id', 'Building.id'), 'order' => array('Building.building_name' => 'asc')));

        $this->set(compact('societyBuildingLists'));


    }



    public function wing_identitys($buildingWingId = null) {

        $societyUserId = $this->Session->read('Auth.User.id');

        $message = "added";

        if ($this->request->is(array('get'))) {

            $this->request->data = $this->Wing->find('first', array('fields' => array('Wing.wing_name', 'Wing.id', 'Building.id'), 'conditions' => array('Wing.id' => $buildingWingId)));

        }

        if ($this->request->is(array('post', 'put'))) {

            if ($buildingWingId != '') {

                $message = "updated";

                $this->Wing->id = $buildingWingId;

            }

            $this->request->data['Wing']['society_id'] = $societyUserId;

            if ($this->Wing->save($this->request->data)) {

                $this->Session->setFlash(__('The Wing has been ' . $message . '.'), 'success');

            } else {

                $this->Session->setFlash(__('The Wing could not be saved. Please, try again.'), 'error');

            }

            $this->redirect(array('controller' => 'societys', 'action' => 'wing_identitys'));

        }

        $societyBuildingLists = $this->Society->Building->find('list', array('conditions' => array('Building.society_id' => $societyUserId), 'fields' => array('id', 'building_name'), 'order' => array('Building.building_name' => 'asc')));

        $buildingWingData = $this->Wing->find('all', array('conditions' => array('Wing.status' => Configure::read('Active'), 'Wing.society_id' => $societyUserId), 'fields' => array('Wing.wing_name', 'Society.society_name', 'Building.building_name', 'Wing.society_id', 'Wing.udate', 'Society.user_id', 'Wing.id'), 'order' => array('Wing.wing_name' => 'asc')));

        $this->set(compact('societyBuildingLists', 'buildingWingData'));

    }



    public function member_identitys() {

        $this->Member->Behaviors->attach('Containable');

        $wingMemberData = $this->Member->find('all', array('contain' => array('Building', 'Society', 'Wing'), 'conditions' => array('Member.status' => Configure::read('Active'), 'Member.society_id' => $this->Session->read('Auth.User.id')), 'fields' => array('Member.member_prefix', 'Member.member_name', 'Member.id', 'Member.member_photo', 'Member.floor_no', 'Member.op_principal', 'Member.op_interest', 'Member.op_tax', 'Building.building_name', 'Society.society_name', 'Wing.wing_name', 'Member.flat_no'), 'order' => array('Member.id' => 'asc')));

        $this->set(compact('wingMemberData'));

    }
  
  // create by sonu :: 10-04-2026
    // SELECT 
    // m.id AS m_id,
    // cb.id AS cd_id,
    // m.member_name,
    // m.flat_no,
    // m.society_id,
    // cb.year_id,
    // yr.year,
    // cb.principal_balance,
    // cb.interest_balance,
    // cb.tax_balance
    // FROM members AS m
    // LEFT JOIN member_year_wise_closing_balance AS cb 
    //     ON m.id = cb.member_id 
    //     AND m.society_id = cb.society_id
    // LEFT JOIN financial_year_master AS yr 
    //     ON cb.year_id = yr.id
    // WHERE 
    //     m.society_id = '104157' 
    //     AND cb.year_id = 21;
    public function update_opening_balance() {

        $this->Member->Behaviors->attach('Containable');
        $societyUserId = $this->Session->read('Auth.User.id');
        $wingMemberData = $this->Member->find('all', array('contain' => array('Building', 'Society', 'Wing'), 'conditions' => array('Member.status' => Configure::read('Active'), 'Member.society_id' => $this->Session->read('Auth.User.id')), 'fields' => array('Member.member_prefix', 'Member.member_name', 'Member.id', 'Member.member_photo', 'Member.floor_no', 'Member.op_principal', 'Member.op_interest', 'Member.op_tax', 'Building.building_name', 'Society.society_name', 'Wing.wing_name', 'Member.flat_no'), 'order' => array('Member.id' => 'asc')));

        $fy = "21";
        // $societyId = $this->Session->read('Auth.User.id');
        //    $financialYearId = $this->Util->getFinancialYear();

        // select m.id as m_id,cb.id as cd_id,m.member_name,m.flat_no,m.society_id,cb.year_id,yr.year,
        // cb.principal_balance, cb.interest_balance, cb.tax_balance
        // from members as m
        // left join member_year_wise_closing_balance as cb on m.id = cb.member_id and m.society_id = cb.society_id 
        // left join financial_year_master as yr on cb.year_id = yr.id
        // where m.society_id = '104157' and cb.year_id = 21


        // echo "<pre>";
        // print_r($financialYearId);die;

  
        $this->set(compact('wingMemberData'));
    }

    public function download_member_details() {

            $this->autoRender = false;
            $this->layout = false;

            // ✅ VERY IMPORTANT (fix CSV header issue)
            if (ob_get_length()) {
                ob_clean();
            }
            
            $societyUserId = $this->Session->read('Auth.User.id');
            $financialYearId = $this->Util->getFinancialYear();

            $memberData = $this->Member->find('all', array(
                'fields' => array(
                    'Member.id AS m_id',
                    'ClosingBalance.id AS cd_id',
                    'Member.member_name',
                    'Member.flat_no',
                    'Member.society_id',
                    'ClosingBalance.year_id',
                    'FinancialYear.year',
                    'ClosingBalance.principal_balance',
                    'ClosingBalance.interest_balance',
                    'ClosingBalance.tax_balance'
                ),
                'joins' => array(
                    array(
                        'table' => 'member_year_wise_closing_balance',
                        'alias' => 'ClosingBalance',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'Member.id = ClosingBalance.member_id',
                            'Member.society_id = ClosingBalance.society_id'
                        )
                    ),
                    array(
                        'table' => 'financial_year_master',
                        'alias' => 'FinancialYear',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'ClosingBalance.year_id = FinancialYear.id'
                        )
                    )
                ),
                'conditions' => array(
                    'Member.society_id' => $societyUserId,
                    'ClosingBalance.year_id' => $financialYearId
                )
            ));

            // ✅ Flatten Data
            $finalData = array();

            foreach ($memberData as $row) {
                $finalData[] = array(
                    'Sr.No.' => isset($row['ClosingBalance']['cd_id']) ? $row['ClosingBalance']['cd_id'] : '',
                    'Member ID' => $row['Member']['id'],
                    'Member Name' => $row['Member']['member_name'],
                    'Flat No' => $row['Member']['flat_no'],
                    'Society ID' => $row['Member']['society_id'],
                    'Year ID' => isset($row['FinancialYear']['year']) ? $row['ClosingBalance']['year_id'] : '',
                    'Year' => isset($row['FinancialYear']['year']) ? $row['FinancialYear']['year'] : '',
                    'Principal Balance' => isset($row['ClosingBalance']['principal_balance']) ? $row['ClosingBalance']['principal_balance'] : 0,
                    'Interest Balance' => isset($row['ClosingBalance']['interest_balance']) ? $row['ClosingBalance']['interest_balance'] : 0,
                    'Tax Balance' => isset($row['ClosingBalance']['tax_balance']) ? $row['ClosingBalance']['tax_balance'] : 0
                );
            }

            // ✅ CSV Download
            $fileName = "Member_details_" . date('Ymd_His') . ".csv";

            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename={$fileName}");
            header("Pragma: no-cache");
            header("Expires: 0");

            $output = fopen("php://output", "w");

            // ✅ Add CSV Header
            if (!empty($finalData)) {
                fputcsv($output, array_keys($finalData[0]));
            }

            // ✅ Add Data Rows
            foreach ($finalData as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
            exit;
     }


     public function upload_member_details(){
        // echo "upload";die;

            if ($this->request->is('post')) {

                $file = $this->request->data['Member']['member_csv'];

                // ✅ File validation
                if (empty($file['tmp_name'])) {
                    $this->Session->setFlash('Please upload a CSV file.');
                    return $this->redirect($this->referer());
                }

                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                if (strtolower($ext) != 'csv') {
                    $this->Session->setFlash('Only CSV files are allowed.');
                    return $this->redirect($this->referer());
                }

                if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {

                    $header = fgetcsv($handle); // ✅ Read header

                    // ✅ Expected headers
                    $requiredHeaders = array(
                        'Sr.No.',
                        'Member ID',
                        'Member Name',
                        'Flat No',
                        'Society ID',
                        'Year ID',
                        'Member ID',
                        'Year',
                        'Principal Balance',
                        'Interest Balance',
                        'Tax Balance'
                    );

                    // ✅ Normalize headers (trim spaces)
                    $header = array_map('trim', $header);

                    // ✅ Check missing headers
                    $missingHeaders = array_diff($requiredHeaders, $header);

                    if (!empty($missingHeaders)) {
                        fclose($handle);

                        $this->Session->setFlash(
                            'Invalid CSV format. Missing columns: ' . implode(', ', $missingHeaders)
                        );
                        return $this->redirect($this->referer());
                    }

                    // ✅ If header valid → proceed
                    $csvData = array();

                    while (($row = fgetcsv($handle)) !== FALSE) {

                        if (!array_filter($row)) {
                            continue;
                        }

                        $csvData[] = array_combine($header, $row);
                    }

                    fclose($handle);
                }
                // die;

                // ✅ Load Model (IMPORTANT)
                $this->loadModel('MemberYearWiseClosingBalance');

                $updated = 0;
                $skipped = 0;
                $errors  = array();
                // ✅ Initialize counters (before loop)
                $billUpdated = 0;
                $billSkipped = 0;

                foreach ($csvData as $key => $row) {

                    // ✅ Validate required fields
                    if (
                        empty($row['Sr.No.']) ||
                        !is_numeric($row['Sr.No.'])
                    ) {
                        $skipped++;
                        $errors[] = "Row " . ($key + 2) . ": Invalid Sr.No.";
                        continue;
                    }

                    $id = (int)$row['Sr.No.'];

                    $principal = isset($row['Principal Balance']) ? (float)$row['Principal Balance'] : 0;
                    $interest  = isset($row['Interest Balance']) ? (float)$row['Interest Balance'] : 0;
                    $tax       = isset($row['Tax Balance']) ? (float)$row['Tax Balance'] : 0;

                    // ✅ Check record exists
                    $exists = $this->MemberYearWiseClosingBalance->find('count', array(
                        'conditions' => array('id' => $id),
                        'recursive' => -1
                    ));

                    if (!$exists) {
                        $skipped++;
                        $errors[] = "Row " . ($key + 2) . ": Record not found (ID: {$id})";
                        continue;
                    }

                    // ✅ Update using updateAll (safe)
                    $this->MemberYearWiseClosingBalance->updateAll(
                        array(
                            'principal_balance' => $principal,
                            'interest_balance'  => $interest,
                            'tax_balance'       => $tax
                        ),
                        array(
                            'id' => $id
                        )
                    );

                    $updated++;

                    // bil summary update    

                    // inside foreach loop
                    $memberId  = isset($row['Member ID']) ? (int)$row['Member ID'] : 0;
                    $societyId = isset($row['Society ID']) ? (int)$row['Society ID'] : 0;
                    $yearId    = isset($row['Year ID']) ? (int)$row['Year ID'] : 0;

                    if ($memberId && $societyId && $yearId) {

                        // ✅ Get latest record ID
                        $billSummary = $this->Member->query("
                            SELECT id 
                            FROM member_bill_summaries 
                            WHERE society_id = {$societyId}
                            AND member_id = {$memberId}
                            AND financial_year_id = {$yearId}
                            ORDER BY id DESC, udate DESC
                            LIMIT 1
                        ");

                        if (!empty($billSummary)) {

                            $billId = $billSummary[0]['member_bill_summaries']['id'];

                            $balance_amount = $principal+$interest+$tax;

                            // ✅ Update latest record
                            $this->Member->query("
                                UPDATE member_bill_summaries
                                SET 
                                    principal_balance = {$principal},
                                    interest_balance = {$interest},
                                    tax_balance = {$tax},balance_amount={$balance_amount}
                                WHERE id = {$billId}
                            ");

                            // ✅ Count updated
                            $billUpdated++;

                        } else {
                            // ❌ No record found
                            $billSkipped++;
                        }

                    } else {
                        // ❌ Invalid data
                        $billSkipped++;
                    }
                }

                // ✅ Flash Message
                $message = "
                <strong>Member Closing Balance Update:</strong> Updated: {$updated}, Skipped: {$skipped}<br>
                <strong>Bill Summary Update:</strong> Updated: {$billUpdated}, Skipped: {$billSkipped}
                ";

                if (!empty($errors)) {
                    $message .= "<br><small>" . implode('<br>', $errors) . "</small>";
                }

                $this->Session->setFlash(
                    $message,
                    'default',
                    array('class' => 'alert alert-success')
                );

                return $this->redirect($this->referer());
            }

     }




    public function add_member_identitys($buildingMemberId = null) {

        $societyUserId = $this->Session->read('Auth.User.id');

        $societyBuildingLists = $this->Society->Building->find('list', array('conditions' => array('Building.status' => Configure::read('Active'), 'Building.society_id' => $societyUserId), 'fields' => array('Building.id', 'Building.building_name'), 'order' => array('Building.building_name' => 'asc'), 'recursive' => -1));

        $societyBuildingwings = $this->Wing->find('all',array('conditions' => array('Wing.status' => Configure::read('Active'), 'Wing.society_id' => $societyUserId)));

        

        $this->loadModel('MemberReports');

        $memberReports = $this->MemberReports->find('all', array('list' => array('MemberReports.status' => Configure::read('Active')), 'fields' => array('MemberReports.id', 'MemberReports.report_name'), 'order' => array('MemberReports.report_name' => 'asc')));

        $error = 'Member has been added successfully.';

        if ($this->request->is(array('get'))) {

            $this->request->data = $this->Member->find('first', array('conditions' => array('Member.id' => $buildingMemberId)));

        }

        if ($this->request->is(array('post', 'put'))) {

            if ($this->request->data['Member']) {

                $postData['society_id'] = isset($societyUserId) ? $societyUserId : 0;

                $postData['member_name'] = isset($this->request->data['Member']['member_name']) ? $this->request->data['Member']['member_name'] : '';

                $postData['member_prefix'] = isset($this->request->data['Member']['member_prefix']) ? $this->request->data['Member']['member_prefix'] : '';

                $postData['member_id'] = isset($this->request->data['Member']['member_id']) ? $this->request->data['Member']['member_id'] : '';

                $postData['flat_no'] = isset($this->request->data['Member']['flat_no']) ? $this->request->data['Member']['flat_no'] : '';

                $postData['building_id'] = isset($this->request->data['Member']['building_id']) ? $this->request->data['Member']['building_id'] : '';

                $postData['member_email'] = isset($this->request->data['Member']['member_email']) ? $this->request->data['Member']['member_email'] : '';

                $postData['member_phone'] = $memberPhone  = isset($this->request->data['Member']['member_phone']) ? $this->request->data['Member']['member_phone'] : '';

                $postData['wing_id'] = isset($this->request->data['Member']['wing_id']) ? $this->request->data['Member']['wing_id'] : 0;

                $postData['floor_no'] = !empty($this->request->data['Member']['floor_no']) ? $this->request->data['Member']['floor_no'] : 0;

                $postData['unit_type'] = isset($this->request->data['Member']['unit_type']) ? $this->request->data['Member']['unit_type'] : '';

                $postData['area'] = !empty($this->request->data['Member']['area']) ? $this->request->data['Member']['area'] : 0;

                $postData['carpet'] = !empty($this->request->data['Member']['carpet']) ? $this->request->data['Member']['carpet'] : 0;

                $postData['commercial'] = !empty($this->request->data['Member']['commercial']) ? $this->request->data['Member']['commercial'] : 0;

                $postData['residential'] = !empty($this->request->data['Member']['residential']) ? $this->request->data['Member']['residential'] : 0;

                $postData['terrace'] = !empty($this->request->data['Member']['terrace']) ? $this->request->data['Member']['terrace'] : 0;

                $postData['op_principal'] = !empty($this->request->data['Member']['op_principal']) ? $this->request->data['Member']['op_principal'] : 0;

                $postData['op_interest'] = !empty($this->request->data['Member']['op_interest']) ? $this->request->data['Member']['op_interest'] : 0;

                $postData['op_tax'] = !empty($this->request->data['Member']['op_tax']) ? $this->request->data['Member']['op_tax'] : 0;

                $postData['penality'] = isset($this->request->data['Member']['penality']) ? $this->request->data['Member']['penality'] : '';

                $postData['supplementary_principal'] = isset($this->request->data['Member']['supplementary_principal']) ? $this->request->data['Member']['supplementary_principal'] : '';

                $postData['supplementary_interest'] = isset($this->request->data['Member']['supplementary_interest']) ? $this->request->data['Member']['supplementary_interest'] : '';

                $postData['supplementary_tax'] = isset($this->request->data['Member']['supplementary_tax']) ? $this->request->data['Member']['supplementary_tax'] : '';

                $postData['supplementary_penality'] = isset($this->request->data['Member']['supplementary_penality']) ? $this->request->data['Member']['supplementary_penality'] : '';                

                

                $postData['op_bill_due_date'] = !empty($this->request->data['Member']['op_bill_due_date']) ? $this->Util->getFormatDate($this->request->data['Member']['op_bill_due_date']) : 0;

                $postData['op_bill_date'] = !empty($this->request->data['Member']['op_bill_date']) ? $this->Util->getFormatDate($this->request->data['Member']['op_bill_date']) : 0;

                $postData['gstin_no'] = !empty($this->request->data['Member']['gstin_no']) ? $this->request->data['Member']['gstin_no'] : 0;

                $postData['member_parking_no'] = !empty($this->request->data['Member']['member_parking_no']) ? $this->request->data['Member']['member_parking_no'] : 0;

                $postData['report_access'] = count($this->request->data['Member']['report_access']) ? implode(',', $this->request->data['Member']['report_access']) : NULL;

                $postData['udate'] = $this->Util->getDateTime();

                $postData['joint_member_name'] = !empty($this->request->data['Member']['joint_member_name']) ? $this->request->data['Member']['joint_member_name'] : '';
                
                $numberExist = false;
                $conditions = array('Member.member_phone' => $memberPhone);
                if (!empty($memberPhone) && $this->Member->hasany($conditions)) {
                    $numberExist = true;
                }

                if (isset($postData['member_id']) && $postData['member_id'] != '') {
                    $memberId = isset($postData['member_id']) ? $postData['member_id'] : 0;
                    $this->Member->id = $memberId;
                    $memberSaveData = $this->Member->save($postData);      
                    if (!empty($memberSaveData) && !empty($memberPhone)) {                   
                        if (empty($numberExist)) {
                            $userData = $this->createMemberLogin($memberPhone);
                            if(!empty($userData)){
                                $userId = $userData['User']['id'];
                                $this->Member->updateAll(array('Member.user_id' => $userId), array('Member.id' => $memberId));
                            }
                        }
                    }                    

                    $error = "Member has been updated successfully.";

                } else {
                    if ($numberExist) {
                        $this->Session->setFlash(__('Mobile No Already Exist Try with Other Number'), 'error');
                        $this->redirect(array('action' => 'member_identitys'));
                    }else{
                        $postData['cdate'] = $this->Util->getDateTime();
                        $memberSaveData = $this->Member->save($postData);
                        if ($memberSaveData) {
                            $memberId = isset($memberSaveData['Member']['id']) ? $memberSaveData['Member']['id'] : 0;
                            if(!empty($memberPhone)) {
                                $userData = $this->createMemberLogin($memberPhone);
                                if(!empty($userData)){
                                    $userId = $userData['User']['id'];
                                    $this->Member->updateAll(array('Member.user_id' => $userId), array('Member.id' => $memberId));
                                }
                            }                            
                        }                                                
                    }
                    
                }



                if (isset($this->request->data['Member']['member_photo']['name']) && !empty($this->request->data['Member']['member_photo']['name'])) {

                    $errorResponce = $this->saveUpdateMemberProfilePhoto($this->request->data['Member']['member_photo'], $memberId);

                    if ($errorResponce) {

                        $error = $errorResponce;

                        $this->Session->setFlash(__($error), 'error');

                    } else {

                        $this->Session->setFlash(__($error), 'success');

                        $this->redirect(array('action' => 'member_identitys'));

                    }

                } else {

                    $this->Session->setFlash(__($error), 'success');

                    $this->redirect(array('action' => 'member_identitys'));

                }

            }

        }



        $this->set(compact('societyBuildingLists', 'memberReports','societyBuildingwings'));

    }
    
    public function createMemberLogin($mobileNo){
        $userData = [];
        if(!empty($mobileNo)){      
            $mobileNo = trim($mobileNo);
            $aData = array();
            $aData['username'] = $mobileNo;
            $aData['password'] = substr($mobileNo,0,3);
            $aData['cdate'] = $this->Util->getDateTime();
            $aData['added_by'] = $this->Session->read('Auth.User.id');
            $aData['access_level'] = 4;
            $aData['role'] = 'Member';
            $aData['status'] = Configure::read('Active');
            $aData['udate'] = $this->Util->getDateTime();
            $aData['password'] = $aData['password'];  
            $conditions = array('User.username' => $mobileNo);
            if(!$this->User->hasAny($conditions))
                $userData = $this->User->save($aData);  
        }
        
        return $userData;
    }



    public function tenant_member_identitys() {

        $societyUserId = $this->Session->read('Auth.User.id');

        $wingTenantData = $this->Tenant->find('all', array('conditions' => array('Tenant.status' => Configure::read('Active'), 'Tenant.society_id' => $societyUserId), 'fields' => array('Tenant.tenant_name', 'Tenant.id', 'Tenant.lease_type', 'Tenant.agreement_on', 'Tenant.rent_per_month', 'Tenant.phone'), 'order' => array('Tenant.tenant_name' => 'asc')));

        $this->set(compact('wingTenantData'));

    }



    public function add_tenant_member_identitys($tenantMemberId = null) {

        if ($this->request->is(array('get'))) {

            $this->request->data = $this->Tenant->find('first', array('conditions' => array('Tenant.id' => $tenantMemberId)));

        }

        if ($this->request->is('post', 'put')) {

            //pr($this->request->data);exit;

            $this->Tenant->set($this->request->data);

            if (!$this->Tenant->validates()) {

                $errors = $this->Tenant->validationErrors;

                if (isset($errors) && count($errors) > 0) {

                    $error = $this->Util->getModelValidationError($errors);

                    $this->Session->setFlash(__('The tenant could not be saved .' . $error), 'error');

                }

            } else {

                $this->request->data['Tenant']['society_id'] = $this->Session->read('Auth.User.id');

                $this->request->data['Tenant']['cdate'] = $this->Util->getDateTime();

                $this->request->data['Tenant']['udate'] = $this->Util->getDateTime();

                if ($this->Tenant->save($this->request->data)) {

                    $this->Session->setFlash(__('Tenant data saved successfully.'), 'success');

                    $this->redirect(array('controller' => 'societys', 'action' => 'tenant_member_identitys'));

                } else {

                    $this->Session->setFlash(__('The tenant could not be saved .'), 'error');

                }

            }

        }

        $stateList = $this->Util->getStateList();

        $countryList = $this->Util->getCountryList();

        $this->set(compact('stateList', 'countryList'));

    }



    public function saveUpdateMemberProfilePhoto($filesData, $memberIdentityId) {

        $target_dir = "societyMembers/";

        $error = '';

        if (isset($filesData['tmp_name']) && !empty($filesData['tmp_name'])) {

            $target_file = $target_dir . basename($filesData["name"]);

            $imgExt = pathinfo($target_file, PATHINFO_EXTENSION);

            $uniqueSavename = uniqid(rand()) . '.' . $imgExt;

            $fileName = isset($uniqueSavename) ? $memberIdentityId . '_' . $uniqueSavename : $memberIdentityId . '_' . trim($filesData["name"]);

            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

            $imageSize = $filesData["size"];

            $tmpDir = $filesData["tmp_name"];



            if (!is_dir($target_dir)) {

                mkdir($target_dir, 0777);

            }



            if (in_array($imgExt, $valid_extensions)) {

                if ($imageSize < 5000000) {

                    move_uploaded_file($tmpDir, $target_dir . $fileName);

                    $this->Member->updateAll(array("Member.member_photo" => "'" . $target_dir . $fileName . "'"), array("Member.id" => $memberIdentityId));

                } else {

                    $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";

                }

            } else {

                $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";

            }

        }

        return $error;

    }



    public function delete_wing_identitys($buildingWingId = null) {

        if (!$this->Wing->exists($buildingWingId)) {

            throw new NotFoundException(__('Requested wing is not found.'));

        }

        $this->request->allowMethod('post', 'delete');

        $this->Wing->id = $buildingWingId;

        if ($this->Wing->saveField('status', Configure::read('InActive'))) {

            $this->Session->setFlash(__('The wing has been deleted.'), 'success');

        } else {

            $this->Session->setFlash(__('The wing could not be deleted. Please, try again.'), 'error');

        }

        return $this->redirect(array('action' => 'wing_identitys'));

    }



    public function delete_building_identitys($societyBuildingId = null) {

        if (!$this->Society->Building->exists($societyBuildingId)) {

            throw new NotFoundException(__('Requested building is not found.'));

        }

        $this->request->allowMethod('post', 'delete');

        $this->Society->Building->id = $societyBuildingId;

        if ($this->Society->Building->saveField('status', Configure::read('InActive'))) {

            $this->Session->setFlash(__('The building has been deleted.'), 'success');

        } else {

            $this->Session->setFlash(__('The building could not be deleted. Please, try again.'), 'error');

        }

        return $this->redirect(array('action' => 'building_identitys'));

    }



    public function delete_member_identitys($buildingMemberId = null) {

        if (!$this->Member->exists($buildingMemberId)) {

            throw new NotFoundException(__('Requested Member is not found.'));

        }

        $this->request->allowMethod('post', 'delete');

        $this->Member->id = $buildingMemberId;

        if ($this->Member->saveField('status', Configure::read('InActive'))) {

            $this->Session->setFlash(__('The Member has been deleted.'), 'success');

        } else {

            $this->Session->setFlash(__('The Member could not be deleted. Please, try again.'), 'error');

        }

        return $this->redirect(array('action' => 'member_identitys'));

    }



    public function delete_tenant_identitys($tanantId = null) {

        if (!$this->Tenant->exists($tanantId)) {

            throw new NotFoundException(__('Requested Tenant is not found.'));

        }

        $this->request->allowMethod('post', 'delete');

        $this->Tenant->id = $tanantId;

        if ($this->Tenant->saveField('status', Configure::read('InActive'))) {

            $this->Session->setFlash(__('The Tenant has been deleted.'), 'success');

        } else {

            $this->Session->setFlash(__('The Tenant could not be deleted. Please, try again.'), 'error');

        }

        return $this->redirect(array('action' => 'tenant_member_identitys'));

    }

    public function manageSettlementOrder($orderString){
        $societyLoginID = $this->societyloginID;
        $this->loadModel('BillSettlementOrder');
        $data = [
                'settle_order' => $orderString
            ];
            
        $societySettlementData = $this->BillSettlementOrder->find('first', array('conditions' => array('BillSettlementOrder.is_active' => Configure::read('Active'),'BillSettlementOrder.society_id' => $societyLoginID), 'fields' => array('BillSettlementOrder.id','BillSettlementOrder.society_id', 'BillSettlementOrder.settle_order')));

        if(!empty($societySettlementData)){
            $settleId = $societySettlementData['BillSettlementOrder']['id'];
            $data['id'] =  $settleId ;
        }
        else{
            $data['society_id'] = $societyLoginID;            
        }
              
        
        $this->BillSettlementOrder->save($data);
    }


    public function society_parameter() {
         
        $this->loadModel('BillingFrequency');

        $this->loadModel('InterestType');

        $this->loadModel('TariffType');

        $this->loadModel('InterestMethod');

        $this->loadModel('SocietyParameter');

        $societyLoginID = $this->societyloginID;

        $billingFrequencyLists = $this->BillingFrequency->find('list', array('conditions' => array('BillingFrequency.status' => Configure::read('Active')), 'fields' => array('BillingFrequency.id', 'BillingFrequency.frequency_type'), 'order' => array('BillingFrequency.frequency_type' => 'asc')));

        $InterestTypeLists = $this->InterestType->find('list', array('conditions' => array('InterestType.status' => Configure::read('Active')), 'fields' => array('InterestType.id', 'InterestType.interest_type'), 'order' => array('InterestType.interest_type' => 'asc')));

        $TariffTypeLists = $this->TariffType->find('list', array('conditions' => array('TariffType.status' => Configure::read('Active')), 'fields' => array('TariffType.id', 'TariffType.tariff_type'), 'order' => array('TariffType.tariff_type' => 'asc')));

        $InterestMethodLists = $this->InterestMethod->find('list', array('conditions' => array('InterestMethod.status' => Configure::read('Active')), 'fields' => array('InterestMethod.id', 'InterestMethod.method_title'), 'order' => array('InterestMethod.method_title' => 'asc')));



        if ($this->request->is('post')) {
            $this->request->data['SocietyParameter']['society_id'] = $societyLoginID;

            $requestData = $this->request->data;

            $societyName = $this->Session->read('Auth.User.username');

            if(!empty($requestData['SocietyParameter']['scanner_image']['name'])){

                $fileData = $requestData['SocietyParameter']['scanner_image'];

                if(isset($fileData['name'])){

                    $fileName = $fileData['name'];

                    $fileType = $this->getFileType($fileName);

                    if(!in_array($fileType,['jpg','jpeg','png'])){

                        $this->Session->setFlash(__('Scanner file format type is not valid.'), 'error');

                        $this->redirect(array('controller' => 'societys', 'action' => 'society_parameter'));

                    }

                    else{

                        $filePath = $this->uploadFile($fileData,$societyLoginID,$societyName);

                        $this->request->data['SocietyParameter']['scanner_image_path'] = $filePath;

                    }

                }

            }

            if(!empty($requestData['SocietyParameter']['signature_image']['name'])){

                $signatureFileData = $requestData['SocietyParameter']['signature_image'];

                $signatureFileType = $this->getFileType($signatureFileData['name']);

                if(!in_array($signatureFileType,['jpg','jpeg','png'])){

                    $this->Session->setFlash(__('Signature file format type is not valid.'), 'error');

                    $this->redirect(array('controller' => 'societys', 'action' => 'society_parameter'));

                }

                else{

                    $signatureFilePath = $this->uploadFile($signatureFileData,$societyLoginID,$societyName,'society_signature','signature');

                    $this->request->data['SocietyParameter']['signature_image_path'] = $signatureFilePath;

                }

            }



            if ($this->SocietyParameter->save($this->request->data)) {
                $id = isset($this->request->data['SocietyParameter']['id']) ? $this->request->data['SocietyParameter']['id'] : 0;
                $orderString = $this->request->data['sorted_order'];
                $settlementType = $this->request->data['SocietyParameter']['settlement'];

                if($settlementType == 2)  // 2 == LIFO
                    $this->manageSettlementOrder($orderString);

                $this->Session->setFlash(__('Society parameter has been set successfully.'), 'success');

                $this->redirect(array('controller' => 'societys', 'action' => 'society_parameter'));

            } else {

                $this->Session->setFlash(__('The Society parameter could not be saved .'), 'error');

            }

        } else {

            if ($this->SocietyParameter->hasAny(array('SocietyParameter.society_id' => $societyLoginID))) {
                $this->loadModel('BillSettlementOrder');

                $this->request->data = $this->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $societyLoginID)));
                $societySettlementData = $this->BillSettlementOrder->find('first', array('conditions' => array('BillSettlementOrder.is_active' => Configure::read('Active'),'BillSettlementOrder.society_id' =>$societyLoginID), 'fields' => array('BillSettlementOrder.id','BillSettlementOrder.society_id', 'BillSettlementOrder.settle_order')));

                if(!empty($societySettlementData)){
                    $settleOrderString = $societySettlementData['BillSettlementOrder']['settle_order'];
                    $exploadedString = explode(',',$settleOrderString);
                    $this->request->data['SocietyParameter']['settle_order'] = $exploadedString;
                }
                else{
                    $this->request->data['SocietyParameter']['settle_order'] = ['Principle','Interest','Tax'];
                }


                $filepath =$this->request->data['SocietyParameter']['scanner_image_path'];

                if(!empty($filepath)){

                    $this->request->data['SocietyParameter']['scanner_image_path']=strchr($filepath,'payment_scanner');

                }

                $signatureFilepath = isset($this->request->data['SocietyParameter']['signature_image_path']) ? $this->request->data['SocietyParameter']['signature_image_path'] : '';

                if(!empty($signatureFilepath)){

                    $this->request->data['SocietyParameter']['signature_image_path']=strchr($signatureFilepath,'society_signature');

                }

            }

        }



        $this->set(compact('billingFrequencyLists', 'InterestTypeLists', 'TariffTypeLists', 'InterestMethodLists'));

    }

    

    public function  getFileType($fileName){

        return substr(strtolower(strrchr("$fileName",".")),1);

    }

    

    public function uploadFile($fileData,$societyLoginID,$societyName,$uploadFolderName = 'payment_scanner',$fileNameSuffix = 'paymentscanner'){

        $folderPath =  WWW_ROOT.'img/'.$uploadFolderName.'/'.$societyName."_".$societyLoginID."/";



        if(!is_dir($folderPath)){

            mkdir("$folderPath", 0755, true);

            chmod("$folderPath", 0755);

        }

        $uploadedFileLoc = '';

       if(isset($fileData['name'])){

            $rand=rand('1111111111','999999999');

            $fileName = $fileData['name']; 

            $tmpName = $fileData['tmp_name']; 

            $type = $this->getFileType($fileName);

            $newFileName = $folderPath.$societyName."_".$rand."_".$fileNameSuffix.".$type";// $fileName;



            if(move_uploaded_file($tmpName,$newFileName)){

                $uploadedFileLoc = $newFileName;

            }

        }

        return $uploadedFileLoc;

    }    



    public function society_head_sub_categories($headSubCategoryId = null) {

        $this->loadModel('AccountCategory');

        $this->loadModel('SocietyHeadSubCategory');

        $societyLoginID = $this->Auth->user('id');

        $societyAccountCategoryLists = $this->AccountCategory->find('list', array('conditions' => array('AccountCategory.status' => Configure::read('Active')), 'fields' => array('AccountCategory.id', 'AccountCategory.title'), 'order' => array('AccountCategory.title' => 'asc')));

        $societyHeadSubCategoryData = $this->SocietyHeadSubCategory->find('all', array('fields' => array('SocietyHeadSubCategory.id', 'SocietyHeadSubCategory.account_status', 'SocietyHeadSubCategory.title', 'AccountCategory.title', 'AccountHead.title'), 'order' => array('SocietyHeadSubCategory.title' => 'asc')));

        if ($this->request->is(array('get')) && $headSubCategoryId) {

            $this->request->data = $this->SocietyHeadSubCategory->find('first', array('conditions' => array('SocietyHeadSubCategory.id' => $headSubCategoryId)));

        }

        if ($this->request->is('post')) {

            $this->request->data['SocietyHeadSubCategory']['society_id'] = $societyLoginID;

            $subgroups = explode("\n", $this->request->data['SocietyHeadSubCategory']['title']);

            $errorFlag = 0;

            if (!empty($subgroups)) {

                foreach ($subgroups as $title) {

                    if (empty($title)) {

                        continue;

                    }



                    $this->request->data['SocietyHeadSubCategory']['title'] = $title;

                    if (!$this->SocietyHeadSubCategory->save($this->request->data)) {

                        $this->Session->setFlash(__('The Society head category could not be saved .'), 'error');

                        $errorFlag = 1;

                        break;

                    }

                }

            }

            if ($errorFlag != 1) {

                $this->Session->setFlash(__('Society head category data saved/Updated successfully.'), 'success');

            }

            $this->redirect(array('controller' => 'societys', 'action' => 'society_head_sub_categories'));

        } else if ($this->request->is('put')) {

            $this->request->data['SocietyHeadSubCategory']['society_id'] = $societyLoginID;

            if ($this->SocietyHeadSubCategory->save($this->request->data)) {

                $this->Session->setFlash(__('Society head category data saved/Updated successfully.'), 'success');

                $this->redirect(array('controller' => 'societys', 'action' => 'society_head_sub_categories'));

            } else {

                $this->Session->setFlash(__('The Society head category could not be saved .'), 'error');

            }

        }



        $this->set(compact('societyAccountCategoryLists', 'societyHeadSubCategoryData'));

    }



    public function setDatabase($database, $datasource = 'default')

    {

      $nds = $datasource . '_' . $database;      

      $db  = &ConnectionManager::getDataSource($datasource);



      $db->setConfig(array(

        'name'       => $nds,

        'database'   => $database,

        'persistent' => false

      ));



      if ( $ds = ConnectionManager::create($nds, $db->config) ) {

        $this->useDbConfig  = $nds;

        $this->cacheQueries = false;

        return true;

      }



      return false;

    }



    /**
     * Switch the logged-in society's active financial year from the header
     * dropdown. Only a year the society is actually assigned to (society_year_mapping)
     * is accepted; the session is updated exactly as the reseller's "Proceed" does,
     * then we return to the page the user was on.
     */
    public function change_financial_year($yearId = null) {
        $societyId = $this->societyloginID;
        $yearId = (int)$yearId;
        if ($societyId && $yearId > 0) {
            $this->loadModel('SocietyFinancialYearMapping');
            $this->loadModel('FinancialYearMaster');
            $assigned = $this->SocietyFinancialYearMapping->find('first', array('conditions' => array('SocietyFinancialYearMapping.society_id' => $societyId, 'SocietyFinancialYearMapping.year_id' => $yearId, 'SocietyFinancialYearMapping.is_active' => 1), 'recursive' => -1));
            if (!empty($assigned)) {
                $fy = $this->FinancialYearMaster->find('first', array('conditions' => array('FinancialYearMaster.id' => $yearId, 'FinancialYearMaster.is_active' => 1), 'recursive' => -1));
                if (!empty($fy)) {
                    $fromDate = $fy['FinancialYearMaster']['year_start_date'];
                    $toDate = $fy['FinancialYearMaster']['year_end_date'];
                    $this->Session->write('Auth.year_id', $fy['FinancialYearMaster']['id']);
                    $this->Session->write('Auth.year_start_date', $fromDate);
                    $this->Session->write('Auth.year_end_date', $toDate);
                    $this->Session->write('Auth.formated_year_start_date', date('d/m/Y', strtotime($fromDate)));
                    $this->Session->write('Auth.formated_year_end_date', date('d/m/Y', strtotime($toDate)));
                    $this->Session->write('Auth.start_year', date('Y', strtotime($fromDate)));
                    $this->Session->write('Auth.end_year', date('Y', strtotime($toDate)));
                    $this->Session->write('Auth.financial_years_arr', array($fromDate, $toDate));
                    $this->Session->setFlash(__('Financial year changed to %s.', $fy['FinancialYearMaster']['year']), 'success');
                }
            }
        }
        return $this->redirect($this->referer(array('controller' => 'societys', 'action' => 'dashboard')));
    }


     public function society_ledger_heads() {

	//$this->setDatabase('database201819');

        $this->loadModel('SocietyLedgerHeads');

        $societyLoginID = $this->societyloginID;

        $societyLedgerHeadsLists = $this->SocietyLedgerHeads->find('all', array('conditions' => array('SocietyLedgerHeads.society_id' => $societyLoginID, 'SocietyLedgerHeads.status' => Configure::read('Active')), 'fields' => array('SocietyLedgerHeads.id', 'SocietyLedgerHeads.title', 'AccountHead.title', 'AccountCategory.title', 'SocietyHeadSubCategory.title'), 'order' => array('SocietyLedgerHeads.title' => 'asc')));

        $openingBalanceMap = $this->SocietyBill->getSocietyLedgerOpClosingBalSocietyWise($societyLoginID);

        // Ledger heads referenced by any bill, voucher, payment, tariff, bank or
        // opening balance must not be deletable - the list hides the delete button
        // for these, and the delete action itself refuses them.
        $usedLedgerHeadIds = $this->getUsedLedgerHeadIds($societyLoginID);

        $this->set(compact('societyLedgerHeadsLists', 'usedLedgerHeadIds', 'openingBalanceMap'));

    }

    /**
     * Returns [ledgerHeadId => true] for every ledger head of the society that is
     * referenced somewhere it must not be orphaned from: generated bills, journal
     * vouchers (debit or credit), society payments, other incomes, member tariffs,
     * bank accounts, cash withdrawals, tariff orders, opening balances and
     * supplementary tariffs. One UNION keeps it to a single round trip and each
     * society-scoped leg uses the society_id index; supplementary_bill_tariffs has
     * no society_id, but its ids only ever match this society's own rows.
     */
    private function getUsedLedgerHeadIds($societyLoginID) {
        $this->loadModel('SocietyLedgerHeads');
        $sid = (int)$societyLoginID;
        $sql = "SELECT ledger_head_id AS lid FROM member_bill_generates WHERE society_id = $sid AND ledger_head_id > 0
            UNION SELECT jv_debit_ledger_head_id FROM journal_vouchers WHERE society_id = $sid AND jv_debit_ledger_head_id > 0
            UNION SELECT jv_credit_ledger_head_id FROM journal_vouchers WHERE society_id = $sid AND jv_credit_ledger_head_id > 0
            UNION SELECT ledger_head_id FROM society_payments WHERE society_id = $sid AND ledger_head_id > 0
            UNION SELECT ledger_head_id FROM society_other_incomes WHERE society_id = $sid AND ledger_head_id > 0
            UNION SELECT ledger_head_id FROM member_tariffs WHERE society_id = $sid AND ledger_head_id > 0
            UNION SELECT bank_ledger_head_id FROM society_banks WHERE society_id = $sid AND bank_ledger_head_id > 0
            UNION SELECT bank_ledger_head_id FROM cash_withdraws WHERE society_id = $sid AND bank_ledger_head_id > 0
            UNION SELECT bank_to_ledger_head_id FROM cash_withdraws WHERE society_id = $sid AND bank_to_ledger_head_id > 0
            UNION SELECT ledger_head_id FROM society_tariff_orders WHERE society_id = $sid AND ledger_head_id > 0
            UNION SELECT ledger_head_id FROM society_ledger_heads_opening_year_wise WHERE society_id = $sid AND ledger_head_id > 0 AND (COALESCE(op_credit,0) <> 0 OR COALESCE(op_debit,0) <> 0 OR COALESCE(balance_amount,0) <> 0)
            UNION SELECT ledger_head_id FROM society_bank_head_opening_year_wise WHERE society_id = $sid AND ledger_head_id > 0
            UNION SELECT ledger_head_id FROM supplementary_bill_tariffs WHERE ledger_head_id > 0";
        $rows = $this->SocietyLedgerHeads->query($sql);
        $used = array();
        if (!empty($rows)) {
            foreach ($rows as $r) {
                // Aliased UNION column normally comes back under [0]['lid']; scan the
                // other groups too so a different driver/result shape still works.
                $val = isset($r[0]['lid']) ? $r[0]['lid'] : null;
                if ($val === null && is_array($r)) {
                    foreach ($r as $sub) {
                        if (is_array($sub) && isset($sub['lid'])) { $val = $sub['lid']; break; }
                    }
                }
                if ($val !== null && (int)$val > 0) {
                    $used[(int)$val] = true;
                }
            }
        }
        return $used;
    }



    public function add_society_ledger_heads($societyLedgerHeadsId = null){
       
        $this->loadModel('SocietyHeadSubCategory');

        $this->loadModel('SocietyLedgerHeads');

        $this->loadModel('SocietyBank');

        $societyLoginID = $this->societyloginID; 
        
        $financialYearId = $this->Util->getFinancialYear();

        $societyHeadSubCategoryLists = $this->SocietyHeadSubCategory->find('list', array('conditions' => array('SocietyHeadSubCategory.status' => Configure::read('Active')), 'fields' => array('SocietyHeadSubCategory.id', 'SocietyHeadSubCategory.title'), 'order' => array('SocietyHeadSubCategory.title' => 'asc')));

        $conditions =  array('SocietyLedgerHeads.id' => $societyLedgerHeadsId);

        if ($this->request->is(array('get')) && $societyLedgerHeadsId) {

           
            // $ledgerheads = $this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.id' => $societyLedgerHeadsId)));

            $ledgerheads = $this->SocietyLedgerHeads->find('first',array(

                'joins' => array(

                array(

                    'table' => 'society_banks',

                    'alias' => 'society_banks',

                    'type' => 'LEFT',

                    'conditions' => array(

                        'SocietyLedgerHeads.id = society_banks.bank_ledger_head_id',

                        )

                    )

                ),

                'conditions' => $conditions,

                 'fields'=> array('*')

            ));       



           
            
            # get the opening amount from ledger closing balance table
            $openingBal = $this->SocietyBill->getSocietyLedgerOpClosingBal($this->Session->read('Auth.User.id'),$ledgerheads['SocietyLedgerHeads']['id']);
            if (!empty($openingBal)){
                $ledgerheads['SocietyLedgerHeads']['opening_amount']=$this->SocietyBill->getSocietyLedgerOpClosingBal($this->Session->read('Auth.User.id'),$ledgerheads['SocietyLedgerHeads']['id'])['balance_amount'];

            }
            $this->request->data = $ledgerheads;
        }      

        

        if ($this->request->is('post', 'put')) {

            $this->request->data['SocietyLedgerHeads']['society_id'] = $societyLoginID;

            if (isset($this->request->data['SocietyLedgerHeads']['id']) && !empty($this->request->data['SocietyLedgerHeads']['id'])) {

                
                $postedSocietyLedgerHeadId = $this->request->data['SocietyLedgerHeads']['id'];

                $this->updateSocietyLedgerHead($postedSocietyLedgerHeadId);

                $this->redirect(array('controller' => 'societys', 'action' => 'society_ledger_heads'));

            }

            else{

                $ledgerPostArr = array();

                $failCnt = 0;

                if (isset($this->request->data['SocietyLedgerHead']) && !empty($this->request->data['SocietyLedgerHead'])) {
                    
                    $ledgerCount = 0;

                    foreach ($this->request->data['SocietyLedgerHead'] as $postData) {

                        if (isset($postData['title']) && isset($postData['short_code']) && $postData['title'] != '' || $postData['short_code'] != '') {

                            $ledgerPostArr['SocietyLedgerHeads']['society_head_sub_category_id'] = isset($this->request->data['SocietyLedgerHeads']['society_head_sub_category_id']) ? $this->request->data['SocietyLedgerHeads']['society_head_sub_category_id'] : '';

                            $ledgerPostArr['SocietyLedgerHeads']['account_category_id'] = isset($this->request->data['SocietyLedgerHeads']['account_category_id']) ? $this->request->data['SocietyLedgerHeads']['account_category_id'] : '';

                            $ledgerPostArr['SocietyLedgerHeads']['account_head_id'] = isset($this->request->data['SocietyLedgerHeads']['account_head_id']) ? $this->request->data['SocietyLedgerHeads']['account_head_id'] : '';

                            $ledgerPostArr['SocietyLedgerHeads']['society_id'] = isset($this->request->data['SocietyLedgerHeads']['society_id']) ? $this->request->data['SocietyLedgerHeads']['society_id'] : '';

                            $ledgerPostArr['SocietyLedgerHeads']['title'] = isset($postData['title']) ? $postData['title'] : '';

                            $ledgerPostArr['SocietyLedgerHeads']['short_code'] = isset($postData['short_code']) ? $postData['short_code'] : '';

                            $ledgerPostArr['SocietyLedgerHeads']['opening_amount'] = isset($postData['op_amount']) ? $postData['op_amount'] : '';

                            $ledgerPostArr['SocietyLedgerHeads']['is_in_bill_charges'] = isset($postData['is_in_bill_charges']) ? $postData['is_in_bill_charges'] : 0;

                            $ledgerPostArr['SocietyLedgerHeads']['is_tax_applicable'] = isset($postData['is_tax_applicable']) ? $postData['is_tax_applicable'] : 0;

                            $ledgerPostArr['SocietyLedgerHeads']['is_rebate_applicable'] = isset($postData['is_rebate_applicable']) ? $postData['is_rebate_applicable'] : 0;

                            $ledgerPostArr['SocietyLedgerHeads']['is_interest_free'] = isset($postData['is_interest_free']) ? $postData['is_interest_free'] : 0;
                            $ledgerPostArr['SocietyLedgerHeads']['is_tds'] = isset($postData['is_tds']) ? $postData['is_tds'] : 0;
                            $ledgerPostArr['SocietyLedgerHeads']['tds_value'] = isset($postData['tds_value']) ? $postData['tds_value'] : 0;
                            $ledgerPostArr['SocietyLedgerHeads']['tds_type'] = isset($postData['tds_type']) ? $postData['tds_type'] : 0;

                            $ledgerPostArr['SocietyLedgerHeads']['society_id'] = $societyLoginID;

                            $ledgerPostArr['SocietyLedgerHeads']['cdate'] = $this->Util->getDateTime();

                            $ledgerPostArr['SocietyLedgerHeads']['is_supplementary_bill'] = isset($postData['is_supplementary_bill']) ? $postData['is_supplementary_bill'] : 0;   
                            $ledgerPostArr['SocietyLedgerHeads']['financial_year_id']    = $financialYearId;
                            

                          
                            $savedLedgerData = $this->SocietyLedgerHeads->save($ledgerPostArr);

                            
                            if($savedLedgerData){
                                
                                //$postData['ledger_head_id'] = $savedLedgerData['SocietyLedgerHeads']['id'];
                                $this->SocietyBill->addUpdateLedgerOpBalance($savedLedgerData['SocietyLedgerHeads']);
                               

                                if($postData['bank_branch'] != '' && $postData['account_no'] != ''){

                                    $societyBankData['SocietyBank']['society_id'] = $ledgerPostArr['SocietyLedgerHeads']['society_id'];

                                    $societyBankData['SocietyBank']['bank_ledger_head_id'] = $savedLedgerData['SocietyLedgerHeads']['id'];

                                    $societyBankData['SocietyBank']['branch'] = !empty($postData['bank_branch']) ? $postData['bank_branch'] :'' ;

                                    $societyBankData['SocietyBank']['account_no'] = !empty($postData['account_no']) ? $postData['account_no'] :'' ;

                                    $savedBankData = $this->SocietyBank->save($societyBankData);    

                                    if(empty($savedBankData)){

                                        $failCnt++;    

                                    }

                                }

                            }

                            else{

                                $failCnt++;

                            }

                            $this->SocietyLedgerHeads->clear();

                            $this->SocietyBank->clear();                            

                        }

                    }



                    if($failCnt > 0) {

                        $this->Session->setFlash(__('Some records failed to insert in society ledger head data.'), 'error');

                    }

                    else{

                        $this->Session->setFlash(__('Society ledger head data saved/Updated successfully.'), 'success');

                    }

                    $this->redirect(array('controller' => 'societys', 'action' => 'society_ledger_heads'));

                        

                        

                }

            }

            

        }

        

        $this->set(compact('societyHeadSubCategoryLists'));

    }

    

    public function updateSocietyLedgerHead($societyLeadgerheadId){

            //print_r($this->request->data);
            $update = $this->SocietyBill->addUpdateLedgerOpBalance($this->request->data['SocietyLedgerHeads']);
            $societyId = $this->request->data['SocietyLedgerHeads']['society_id'];

            $headSubCategoryId = $this->request->data['SocietyLedgerHeads']['society_head_sub_category_id'];

            $updateSocietyLegderData['SocietyLedgerHeads'] = [

                'id' => $societyLeadgerheadId,

                'title' => $this->request->data['SocietyLedgerHeads']['title'],

                'short_code' => $this->request->data['SocietyLedgerHeads']['short_code'],

                'account_head_id' => $this->request->data['SocietyLedgerHeads']['account_head_id'],

                'account_category_id' => $this->request->data['SocietyLedgerHeads']['account_category_id'],

                'society_head_sub_category_id' => $headSubCategoryId,

                //'opening_amount' => $this->request->data['SocietyLedgerHeads']['opening_amount'],

                'is_in_bill_charges' => $this->request->data['SocietyLedgerHeads']['is_in_bill_charges'],

                'is_tax_applicable' => $this->request->data['SocietyLedgerHeads']['is_tax_applicable'],

                'is_rebate_applicable' => $this->request->data['SocietyLedgerHeads']['is_rebate_applicable'],

                'is_interest_free' => $this->request->data['SocietyLedgerHeads']['is_interest_free'],

                // An unchecked checkbox is not posted, so default to 0. Without this
                // is_supplementary_bill was missing from the update entirely, so
                // unchecking "Is Supplementary Bill" never saved - the old value stayed.
                'is_supplementary_bill' => isset($this->request->data['SocietyLedgerHeads']['is_supplementary_bill']) ? $this->request->data['SocietyLedgerHeads']['is_supplementary_bill'] : 0,

                'udate' => $this->Util->getDateTime()

                ];

            $ledgerUpdateStatus = true;

            $societyBankUpdateStatus = true; 

            $savedLedgerData = $this->SocietyLedgerHeads->save($updateSocietyLegderData);

            if($savedLedgerData){

                if($headSubCategoryId == 20) { // bank balance ledger only

                    $ledgerSocietyBank = $this->SocietyBank->find('first',array('conditions' => array('bank_ledger_head_id' => $societyLeadgerheadId)));                

                    $updateSocietyBankData = [

                        'SocietyBank.branch' => "'".$this->request->data['SocietyLedgerHeads']['bank_branch']."'",

                        'SocietyBank.account_no' => $this->request->data['SocietyLedgerHeads']['account_no'] ,

                        'SocietyBank.udate' => "'".$this->Util->getDateTime()."'"

                        ];

                    

                    if(!empty($ledgerSocietyBank)){

                        $updateBankData = $this->SocietyBank->updateAll($updateSocietyBankData,['SocietyBank.society_id' =>$societyId , 'SocietyBank.bank_ledger_head_id' => $societyLeadgerheadId]);    

                        if(!$updateBankData){

                           $societyBankUpdateStatus = false;

                        }

                    }

                    else{

                        $societyBankData['SocietyBank'] = [

                            'society_id' => $societyId,

                            'bank_ledger_head_id' => $societyLeadgerheadId,

                            'branch' => $this->request->data['SocietyLedgerHeads']['bank_branch'],

                            'account_no' => $this->request->data['SocietyLedgerHeads']['account_no'] ,

                            'cdate' => $this->Util->getDateTime()

                            ];                        

                        $saveSocietyBankData =  $this->SocietyBank->save($societyBankData);

                        if(empty($saveSocietyBankData)){

                           $societyBankUpdateStatus = false; 

                        }

                    }

                }

            }

            else{

                $ledgerUpdateStatus = false;    

            }

            

            if($ledgerUpdateStatus && $societyBankUpdateStatus) {

                $this->Session->setFlash(__('Society ledger head data Updated successfully.'), 'success');

            }

            else if($societyBankUpdateStatus){

                $this->Session->setFlash(__('Society ledger head data Updated successfully.'), 'success');

            }            

            

            return true;

    }    



    public function add_society_ledger_headsbk_04022022($societyLedgerHeadsId = null) {

        $this->loadModel('SocietyHeadSubCategory');

        $this->loadModel('SocietyLedgerHeads');

        $societyLoginID = $this->societyloginID;

        $societyHeadSubCategoryLists = $this->SocietyHeadSubCategory->find('list', array('conditions' => array('SocietyHeadSubCategory.status' => Configure::read('Active')), 'fields' => array('SocietyHeadSubCategory.id', 'SocietyHeadSubCategory.title'), 'order' => array('SocietyHeadSubCategory.title' => 'asc')));

        $tempConn = mysql_connect('localhost', 'vedhtkjm_easylog', 'easyDatabase123');

        //$tempConn = mysql_connect('localhost','root','');

        $db = $this->Session->read('Auth.dbConfig');

        if ($db == 'default') {

            $currentdb = 'vedhtkjm_easylogics';

        } else {

            $currentdb = 'vedhtkjm_easy201920';

        }

                $table = $currentdb . '.society_ledger_heads';

        mysql_select_db($currentdb, $tempConn);

        //

        if ($this->request->is(array('get')) && $societyLedgerHeadsId) {



            $query = mysql_query("SELECT * FROM $table WHERE society_id = " . $this->Session->read('Auth.User.id') . " and id='" . $societyLedgerHeadsId . "' ", $tempConn);

            $res['SocietyLedgerHeads'] = mysql_fetch_assoc($query);



            $this->request->data = $res; //$this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.id' => $societyLedgerHeadsId)));

        }

        if ($this->request->is('post', 'put')) {

            $this->request->data['SocietyLedgerHeads']['society_id'] = $societyLoginID;

            $ledgerPostArr = array();

            if (isset($this->request->data['SocietyLedgerHead']) && !empty($this->request->data['SocietyLedgerHead'])) {

                $ledgerCount = 0;

                foreach ($this->request->data['SocietyLedgerHead'] as $postData) {

                    if (isset($postData['title']) && isset($postData['short_code']) && $postData['title'] != '' || $postData['short_code'] != '') {

                        $ledgerPostArr[$ledgerCount]['society_head_sub_category_id'] = isset($this->request->data['SocietyLedgerHeads']['society_head_sub_category_id']) ? $this->request->data['SocietyLedgerHeads']['society_head_sub_category_id'] : '';

                        $ledgerPostArr[$ledgerCount]['account_category_id'] = isset($this->request->data['SocietyLedgerHeads']['account_category_id']) ? $this->request->data['SocietyLedgerHeads']['account_category_id'] : '';

                        $ledgerPostArr[$ledgerCount]['account_head_id'] = isset($this->request->data['SocietyLedgerHeads']['account_head_id']) ? $this->request->data['SocietyLedgerHeads']['account_head_id'] : '';

                        $ledgerPostArr[$ledgerCount]['society_id'] = isset($this->request->data['SocietyLedgerHeads']['society_id']) ? $this->request->data['SocietyLedgerHeads']['society_id'] : '';

                        $ledgerPostArr[$ledgerCount]['title'] = isset($postData['title']) ? $postData['title'] : '';

                        $ledgerPostArr[$ledgerCount]['short_code'] = isset($postData['short_code']) ? $postData['short_code'] : '';

                        $ledgerPostArr[$ledgerCount]['op_amount'] = isset($postData['op_amount']) ? $postData['op_amount'] : '';

                        $ledgerPostArr[$ledgerCount]['is_in_bill_charges'] = isset($postData['is_in_bill_charges']) ? $postData['is_in_bill_charges'] : 0;

                        $ledgerPostArr[$ledgerCount]['is_tax_applicable'] = isset($postData['is_tax_applicable']) ? $postData['is_tax_applicable'] : 0;

                        $ledgerPostArr[$ledgerCount]['is_rebate_applicable'] = isset($postData['is_rebate_applicable']) ? $postData['is_rebate_applicable'] : 0;

                        $ledgerPostArr[$ledgerCount]['is_interest_free'] = isset($postData['is_interest_free']) ? $postData['is_interest_free'] : 0;

                        $ledgerPostArr[$ledgerCount]['society_id'] = $societyLoginID;

                        $ledgerPostArr[$ledgerCount]['udate'] = $this->Util->getDateTime();

                        $ledgerPostArr[$ledgerCount]['cdate'] = $this->Util->getDateTime();

                        $ledgerPostArr[$ledgerCount]['is_supplementary_bill'] = isset($postData['is_supplementary_bill']) ? $postData['is_supplementary_bill'] : 0;

                        $ledgerCount = $ledgerCount + 1;

                    }

                }



                if (count($ledgerPostArr) > 0) {

                    $flag = 0;



                    foreach ($ledgerPostArr as $l => $postArr) {

                        //insert

                        $count = 0;

                        $fields = '';



                        foreach ($postArr as $col => $val) {

                            if ($col == 'op_amount') {

                                $col = 'opening_amount';

                            }

                            if ($count++ != 0)

                                $fields .= ', ';

                            $fields .= "`$col` = '$val'";

                        }



                        $insQry = "INSERT INTO $table SET $fields;";

                        if (mysql_query($insQry, $tempConn)) {

                            $flag = 1;

                        }

                    }

                    if ($flag == 1) {

                        //if ($this->SocietyLedgerHeads->saveAll($ledgerPostArr)) {

                        $this->Session->setFlash(__('Society ledger head data saved/Updated successfully.'), 'success');

                        $this->redirect(array('controller' => 'societys', 'action' => 'society_ledger_heads'));

                    }

                }

            }



            //$this->request->data['SocietyLedgerHeads']['op_amount'] = isset($this->request->data['SocietyLedgerHeads']['op_amount']) ? $this->request->data['SocietyLedgerHeads']['op_amount'] : 0;

            $this->request->data['SocietyLedgerHeads']['is_in_bill_charges'] = isset($this->request->data['SocietyLedgerHeads']['is_in_bill_charges']) ? $this->request->data['SocietyLedgerHeads']['is_in_bill_charges'] : 0;

            $this->request->data['SocietyLedgerHeads']['is_tax_applicable'] = isset($this->request->data['SocietyLedgerHeads']['is_tax_applicable']) ? $this->request->data['SocietyLedgerHeads']['is_tax_applicable'] : 0;

            $this->request->data['SocietyLedgerHeads']['is_rebate_applicable'] = isset($this->request->data['SocietyLedgerHeads']['is_rebate_applicable']) ? $this->request->data['SocietyLedgerHeads']['is_rebate_applicable'] : 0;

            $this->request->data['SocietyLedgerHeads']['is_interest_free'] = isset($this->request->data['SocietyLedgerHeads']['is_interest_free']) ? $this->request->data['SocietyLedgerHeads']['is_interest_free'] : 0;

            $this->request->data['SocietyLedgerHeads']['udate'] = $this->Util->getDateTime();

            $this->request->data['SocietyLedgerHeads']['cdate'] = $this->Util->getDateTime();

            $postData = $this->request->data['SocietyLedgerHeads'];

            $flag = 0;

            if (isset($postData['id']) && $postData['id'] != '') {

                //update

                $valueSets = array();

                foreach ($postData as $key => $value) {

                    $valueSets[] = $key . " = '" . $value . "'";

                }

                $updateQry = "UPDATE $table SET " . join(",", $valueSets) . " WHERE id='" . $postData['id'] . "'";

                if (mysql_query($updateQry, $tempConn)) {

                    $flag += 1;

                }

            }

            if ($flag >= 1) {

                //if ($this->SocietyLedgerHeads->save($this->request->data)) {

                $this->Session->setFlash(__('Society ledger head data saved/Updated successfully.'), 'success');

                $this->redirect(array('controller' => 'societys', 'action' => 'society_ledger_heads'));

            } else {

                $this->Session->setFlash(__('The Society ledger head could not be saved .'), 'error');

            }

        }

        $this->set(compact('societyHeadSubCategoryLists'));

    }
    
    

    public function society_payments($societyPaymentId = null) {
        $societyLoginID = $this->societyloginID;
        $financialYearId = $this->Util->getFinancialYear();
        $societyPaymentData = [];
        
        $this->loadModel('SocietyPayment');        
        $societyPayments = $this->SocietyPayment->find('all',array('fields'=>array('SocietyPayment.id','SocietyPayment.particulars',
        'SocietyPayment.bill_voucher_number',
        'SocietyPayment.payment_date','SocietyPayment.cheque_date','SocietyPayment.payment_type',
        'SocietyLedgerHeads.account_category_id','SocietyLedgerHeads.title',
        'SocietyPayment.amount','SocietyPayment.total_amount','SocietyPayment.tax_amount',
        'SocietyPayment.financial_year_id'),
        'conditions' => array('SocietyPayment.society_id' => $societyLoginID,'SocietyPayment.status'=>Configure::read('Active'),'SocietyPayment.financial_year_id' => $financialYearId),
        'order'=>array('SocietyPayment.payment_date' => 'desc'))); 
        
        if(!empty($societyPayments)){
            foreach ($societyPayments as $key => $value) { // Loops 4 times because there are 4 columns
                $societyPaymentData[$key]['SocietyPayment'] = array_merge($value['SocietyPayment'],$value['SocietyLedgerHeads']);
            }
        }

        $this->loadModel('AccountCategory');

        $accountCategoriesArr = $this->AccountCategory->find('all');

        $accountCategories = array();

        foreach ($accountCategoriesArr as $k => $val) {

            $accountCategories[$val['AccountCategory']['id']] = $val['AccountCategory']['title'];

        }
        
     

        $this->set(compact('societyPaymentData', 'accountCategories'));


    }

    public function society_paymentsbk($societyPaymentId = null) {

        $societyLoginID = $this->societyloginID;



        $tempConn = mysqli_connect('localhost', 'vedhtkjm_easylog', 'easyDatabase123');

        //$tempConn = mysqli_connect('localhost','root','welcome');

        $db = $this->Session->read('Auth.dbConfig');

        if ($db == 'default') {

            $currentdb = 'vedhtkjm_easylogics';

        } else {

            $currentdb = 'vedhtkjm_easy201920';

        }

        $table = $currentdb . '.society_ledger_heads';

        $table1 = $currentdb . '.society_payments';

        mysqli_select_db($tempConn,$currentdb);



        $sql = "SELECT $table1.id as SocietyPayment_id,$table1.particulars as SocietyPayment_particulars
        ,$table1.particulars as SocietyPayment_particulars,$table1.bill_voucher_number as SocietyPayment_bill_voucher_number,
        $table1.payment_date as SocietyPayment_payment_date,$table1.cheque_date as SocietyPayment_cheque_date,$table1.payment_type as SocietyPayment_payment_type,
        $table1.amount as SocietyPayment_amount,$table1.total_amount as SocietyPayment_total_amount,$table1.tax_amount as SocietyPayment_tax_amount,
        $table.account_category_id as SocietyLedgerHeads_account_category_id,$table.title as SocietyLedgerHeads_title FROM society_payments LEFT JOIN 
        society_ledger_heads ON (society_payments.ledger_head_id=society_ledger_heads.id) WHERE society_payments.society_id = '" . $societyLoginID . "' AND 
        society_payments.status = '" . Configure::read('Active') . "' ORDER BY $table1.payment_date DESC";

        $query = mysqli_query($tempConn, $sql);

        $societyPaymentData = array();

        $i = 0;

        while ($res = mysqli_fetch_assoc($query)) {

            foreach ($res as $key => $value) { // Loops 4 times because there are 4 columns

                if (strpos($key, 'SocietyPayment_') !== FALSE) {

                    $key1 = explode('SocietyPayment_', $key);

                    $societyPaymentData[$i]['SocietyPayment'][$key1[1]] = $value;

                } else {

                    $key1 = explode('SocietyLedgerHeads_', $key);

                    $societyPaymentData[$i]['SocietyLedgerHeads'][$key1[1]] = $value;

                }

            }

            $i++;

        }


        $this->loadModel('AccountCategory');

        $accountCategoriesArr = $this->AccountCategory->find('all');

        $accountCategories = array();

        foreach ($accountCategoriesArr as $k => $val) {

            $accountCategories[$val['AccountCategory']['id']] = $val['AccountCategory']['title'];

        }

        $this->set(compact('societyPaymentData', 'accountCategories'));

    }



   public function add_society_payments($societyPaymentId = null) {
        $financialYearId = $this->Util->getFinancialYear();
        $this->loadModel('SocietyParameter');
      
         

        $societyLoginID = $this->societyloginID;
//   echo '<pre>';

// 	print_r($societyLoginID); exit;
        if ($this->request->is(array('get')) && $societyPaymentId) {
           
            $this->request->data = $this->SocietyPayment->find('first', array('conditions' => array('SocietyPayment.id' => $societyPaymentId,
            'SocietyPayment.society_id' => $societyLoginID, 'SocietyPayment.financial_year_id' => $financialYearId)));

        }

        $billVoucherNumber = $this->SocietyBill->billVoucherNumber();
//   echo '<pre>';

// 	print_r($billVoucherNumber); exit;
        if ($this->request->is('post', 'put')) {

            $this->request->data['SocietyPayment']['id'] = $societyPaymentId;

            $this->request->data['SocietyPayment']['society_id'] = $societyLoginID;

            if ($societyPaymentId == null) {

                if (isset($this->request->data['SocietyPayment']['bill_voucher_number']) && !empty($this->request->data['SocietyPayment']['bill_voucher_number'])) {

                    $this->request->data['SocietyPayment']['bill_voucher_number'] = $this->request->data['SocietyPayment']['bill_voucher_number'];

                } else {

                    $this->request->data['SocietyPayment']['bill_voucher_number'] = $billVoucherNumber;

                }

            }

            $checkSocietyParameters = $this->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $societyLoginID)));
        //   if(isset($this->request->data['SocietyPayment']['tds_account_id'])){
        //                   $TdsSocietyParameters = $this->SocietyLedgerHeads->find('first', array('conditions' => array('SocietyLedgerHeads.id' => $this->request->data['SocietyPayment']['tds_account_id'])));
                          
        //                   $TdsSocietyopening= $TdsSocietyParameters[SocietyLedgerHeads][opening_amount] + $this->request->data['SocietyPayment']['tax_amount'];
        //                     $updateSocietyLegderData['SocietyLedgerHeads'] = [                
        //                         'id' => $this->request->data['SocietyPayment']['tds_account_id'],              
                               
                
        //                         'opening_amount' => $TdsSocietyopening,
                
                               
                
        //                         'udate' => $this->Util->getDateTime()
                
        //                         ];
        //                  $savedLedgerData = $this->SocietyLedgerHeads->save($updateSocietyLegderData);
        //         // print_r($savedLedgerData);exits;
        
        //   }
            if (isset($checkSocietyParameters['SocietyParameter']) && !empty($checkSocietyParameters['SocietyParameter'])) {

                $paymentAmount = isset($this->request->data['SocietyPayment']['amount']) ? floatval($this->request->data['SocietyPayment']['amount']) * 1 : 0.00;

                

                $taxAmt = isset($this->request->data['SocietyPayment']['tax_amount']) ? floatval($this->request->data['SocietyPayment']['tax_amount']) * 1 : 0.00;

                $this->request->data['SocietyPayment']['tax_amount'] = floatval($this->request->data['SocietyPayment']['tax_amount']);

                $this->request->data['SocietyPayment']['total_amount'] = $paymentAmount - floatval($this->request->data['SocietyPayment']['tax_amount']);
                $this->request->data['SocietyPayment']['tds_account_id'] = !empty($this->request->data['SocietyPayment']['tds_account_id']) ? $this->request->data['SocietyPayment']['tds_account_id'] : $this->request->data['SocietyPayment']['tds_account_id'];// $this->request->data['SocietyPayment']['tds_account_id'];
                $this->request->data['SocietyPayment']['cdate'] = date('Y-m-d H:s:i');

                $this->request->data['SocietyPayment']['udate'] = date('Y-m-d H:s:i');
                $this->request->data['SocietyPayment']['financial_year_id'] = $financialYearId;
                
                $this->request->data['SocietyPayment']['particulars'] = !empty($this->request->data['SocietyPayment']['particulars-text']) ? $this->request->data['SocietyPayment']['particulars-text'] : $this->request->data['SocietyPayment']['particulars-select'];
                $this->request->data['SocietyPayment']['notes'] = !empty($this->request->data['SocietyPayment']['notes-text']) ? $this->request->data['SocietyPayment']['notes-text'] : $this->request->data['SocietyPayment']['notes-select'];

            }
            
            $paymentDate = $this->request->data['SocietyPayment']['payment_date'];
            if($this->Util->isDateInCurrentFinancialYear($paymentDate)){

                    
                if ($this->SocietyPayment->save($this->request->data)) {  
                        
                    $this->Session->setFlash(__('Society Payment saved successfully.'), 'success');
                    
    
                    $this->redirect(array('controller' => 'societys', 'action' => 'society_payments'));
    
                } else {
    
                    $this->Session->setFlash(__('The SocietyPayment could not be saved .'), 'error');
                }
            }
            else {

                $this->Session->setFlash(__('The SocietyPayment could not be saved due payment Date .'), 'error');
            }

        }
        $societyExpenseLedgerHeadsTdsLists = $this->SocietyLedgerHeads->find('list', [
        'fields' => ['id', 'title'],
        // Scope to this society (and active heads) - without society_id the TDS Bank
        // dropdown listed every society's TDS Payable head, so the same "TDS Payable"
        // appeared many times and heads from other societies were selectable.
         'conditions' => ['SocietyLedgerHeads.tds_type' => 1, 'SocietyLedgerHeads.society_id' => $this->societyloginID, 'SocietyLedgerHeads.status' => Configure::read('Active')],
    ]);
        $this->set(compact('societyExpenseLedgerHeadsLists', 'societyBankBalanceHeadsLists','societyExpenseLedgerHeadsTdsLists'));

    }

    private function _parseExcelDate($dateValue) {
        if (empty($dateValue)) return '';
        $dateValue = trim($dateValue);

        if (is_numeric($dateValue) && $dateValue > 25000) {
            $unixDate = PHPExcel_Shared_Date::ExcelToPHP($dateValue);
            return date('Y-m-d', $unixDate);
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateValue)) {
            return $dateValue;
        }

        if (strstr($dateValue, '/')) {
            $parts = explode('/', $dateValue);
            if (count($parts) == 3) {
                list($dd, $mm, $yy) = $parts;
                if (strlen($yy) != 4) {
                    $dt = DateTime::createFromFormat('y', $yy);
                    $yy = $dt ? $dt->format('Y') : '20' . $yy;
                }
                if ($mm > 12) { $tmp = $dd; $dd = $mm; $mm = $tmp; }
                return $yy . '-' . str_pad($mm, 2, '0', STR_PAD_LEFT) . '-' . str_pad($dd, 2, '0', STR_PAD_LEFT);
            }
        }

        if (strstr($dateValue, '-')) {
            $parts = explode('-', $dateValue);
            if (count($parts) == 3) {
                list($dd, $mm, $yy) = $parts;
                if (strlen($yy) != 4) {
                    $dt = DateTime::createFromFormat('y', $yy);
                    $yy = $dt ? $dt->format('Y') : '20' . $yy;
                }
                if ($mm > 12) { $tmp = $dd; $dd = $mm; $mm = $tmp; }
                return $yy . '-' . str_pad($mm, 2, '0', STR_PAD_LEFT) . '-' . str_pad($dd, 2, '0', STR_PAD_LEFT);
            }
        }

        $ts = strtotime($dateValue);
        if ($ts !== false) {
            return date('Y-m-d', $ts);
        }

        return '';
    }

    public function add_society_payment_from_xls() {
        set_time_limit(0);
        $societyLoginID = $this->societyloginID;
        $financialYearId = $this->Util->getFinancialYear();

        $societyBankLists = $this->SocietyBill->societyBankBalanceHeadsLists();
        $societyCashLists = $this->SocietyBill->societyCashBalanceHeadsLists();
        $societyExpenseLedgerHeadsLists = $this->SocietyBill->societyAllLedgerHeadsLists();

        if (!empty($this->request->data['data']['file']['name'])) {
            $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $this->request->data['data']['file']['name']);
            $uploadPath = sys_get_temp_dir() . '/';
            $uploadFile = $uploadPath . $fileName;
            move_uploaded_file($this->request->data['data']['file']['tmp_name'], $uploadFile);

            $data = $this->PhpExcel->loadWorksheet($uploadFile);
            $sheetData = $data->getActiveSheet()->toArray(null, true, true, true);

            $headers = $sheetData[1];

            $mapping = array(
                'DebitTo'       => 'ledger_head_title',
                'PaymentDate'   => 'payment_date',
                'PaymentType'   => 'payment_type',
                'PaymentBy'     => 'payment_by_name',
                'Amount'        => 'amount',
                'TaxAmount'     => 'tax_amount',
                'Particulars'   => 'particulars',
                'ChequeNo'      => 'cheque_reference_number',
                'ChequeDate'    => 'cheque_date',
                'VoucherNo'     => 'bill_voucher_number',
                'Notes'         => 'notes'
            );

            foreach ($headers as $key => $value) {
                if ($value === null) { unset($headers[$key]); continue; }
                $trimmed = trim($value);
                if (!empty($mapping[$trimmed]))
                    $headers[$key] = $mapping[$trimmed];
                else
                    unset($headers[$key]);
            }
            unset($sheetData[1]);

            $ledgerHeadsByTitle = array_flip($societyExpenseLedgerHeadsLists);

            $bankByTitle = array();
            if (!empty($societyBankLists)) {
                $bankByTitle = array_flip($societyBankLists);
            }
            $cashByTitle = array();
            if (!empty($societyCashLists)) {
                $cashByTitle = array_flip($societyCashLists);
            }
            $allByTitle = $bankByTitle + $cashByTitle;

            $this->loadModel('SocietyParameter');
            $checkSocietyParameters = $this->SocietyParameter->find('first', array(
                'conditions' => array('SocietyParameter.society_id' => $societyLoginID)
            ));

            if (empty($checkSocietyParameters['SocietyParameter'])) {
                $this->Session->setFlash(__('Society parameters not configured. Cannot save payments.'), 'error');
                $this->set(compact('societyBankLists', 'societyCashLists', 'societyExpenseLedgerHeadsLists'));
                return;
            }

            $addedPayment = 0;
            $failedCnt = 0;
            $failedReasons = array();
            $billVoucherNumber = $this->SocietyBill->billVoucherNumber();

            if (!empty($sheetData)) {
                foreach ($sheetData as $index => $row) {
                    if (empty($row))
                        continue;

                    $allEmpty = true;
                    foreach ($row as $val) {
                        if ($val !== null && trim($val) !== '') { $allEmpty = false; break; }
                    }
                    if ($allEmpty) continue;

                    $aData = array();
                    foreach ($row as $column => $value) {
                        if (!empty($headers[$column]))
                            $aData[$headers[$column]] = $value;
                    }

                    if (empty($aData['ledger_head_title']) || empty($aData['amount']) || empty($aData['payment_date'])) {
                        $failedCnt++;
                        $missing = array();
                        if (empty($aData['ledger_head_title'])) $missing[] = 'DebitTo';
                        if (empty($aData['amount'])) $missing[] = 'Amount';
                        if (empty($aData['payment_date'])) $missing[] = 'PaymentDate';
                        $failedReasons[] = 'Row ' . $index . ': Missing ' . implode(', ', $missing);
                        continue;
                    }

                    $ledgerTitle = trim($aData['ledger_head_title']);
                    if (!isset($ledgerHeadsByTitle[$ledgerTitle])) {
                        $failedCnt++;
                        $failedReasons[] = 'Row ' . $index . ': DebitTo "' . $ledgerTitle . '" not found';
                        continue;
                    }

                    $paymentType = isset($aData['payment_type']) ? trim($aData['payment_type']) : 'Bank';
                    if (!in_array($paymentType, array('Bank', 'Cash'))) {
                        $paymentType = 'Bank';
                    }

                    $paymentByLedgerId = 0;
                    if (!empty($aData['payment_by_name'])) {
                        $byName = trim($aData['payment_by_name']);
                        if ($paymentType == 'Bank' && isset($bankByTitle[$byName])) {
                            $paymentByLedgerId = $bankByTitle[$byName];
                        } elseif ($paymentType == 'Cash' && isset($cashByTitle[$byName])) {
                            $paymentByLedgerId = $cashByTitle[$byName];
                        } elseif (isset($allByTitle[$byName])) {
                            $paymentByLedgerId = $allByTitle[$byName];
                        }
                    }
                    if (empty($paymentByLedgerId)) {
                        if ($paymentType == 'Bank' && !empty($bankByTitle)) {
                            $paymentByLedgerId = reset($bankByTitle);
                        } elseif ($paymentType == 'Cash' && !empty($cashByTitle)) {
                            $paymentByLedgerId = reset($cashByTitle);
                        }
                    }

                    $paymentDate = $this->_parseExcelDate($aData['payment_date']);
                    if (empty($paymentDate)) {
                        $failedCnt++;
                        $failedReasons[] = 'Row ' . $index . ': Invalid PaymentDate "' . $aData['payment_date'] . '"';
                        continue;
                    }

                    if (!$this->Util->isDateInCurrentFinancialYear($paymentDate)) {
                        $failedCnt++;
                        $failedReasons[] = 'Row ' . $index . ': PaymentDate ' . $paymentDate . ' not in current financial year';
                        continue;
                    }

                    $chequeDate = $this->_parseExcelDate(isset($aData['cheque_date']) ? $aData['cheque_date'] : '');

                    $paymentAmount = floatval($aData['amount']);
                    $taxAmount = isset($aData['tax_amount']) ? floatval($aData['tax_amount']) : 0.00;
                    $totalAmount = $paymentAmount - $taxAmount;

                    $saveData = array('SocietyPayment' => array(
                        'ledger_head_id'           => $ledgerHeadsByTitle[$ledgerTitle],
                        'payment_date'             => $paymentDate,
                        'payment_type'             => $paymentType,
                        'payment_by_ledger_id'     => $paymentByLedgerId,
                        'amount'                   => $paymentAmount,
                        'tax_amount'               => $taxAmount,
                        'total_amount'             => $totalAmount,
                        'cheque_reference_number'  => isset($aData['cheque_reference_number']) ? $aData['cheque_reference_number'] : '',
                        'cheque_date'              => $chequeDate,
                        'bill_voucher_number'      => !empty($aData['bill_voucher_number']) ? $aData['bill_voucher_number'] : $billVoucherNumber,
                        'particulars'              => isset($aData['particulars']) ? $aData['particulars'] : '',
                        'notes'                    => isset($aData['notes']) ? $aData['notes'] : '',
                        'society_id'               => $societyLoginID,
                        'financial_year_id'        => $financialYearId,
                        'cdate'                    => date('Y-m-d H:i:s'),
                        'udate'                    => date('Y-m-d H:i:s')
                    ));

                    $this->SocietyPayment->create();
                    if ($this->SocietyPayment->save($saveData)) {
                        $addedPayment++;
                        $billVoucherNumber++;
                    } else {
                        $failedCnt++;
                        $failedReasons[] = 'Row ' . $index . ': Database save failed';
                    }
                }
            }

            $errorDetail = '';
            if (!empty($failedReasons)) {
                $errorDetail = ' Errors: ' . implode(' | ', array_slice($failedReasons, 0, 5));
                if (count($failedReasons) > 5) {
                    $errorDetail .= ' ... and ' . (count($failedReasons) - 5) . ' more';
                }
            }

            if (empty($failedCnt) && $addedPayment > 0) {
                $this->Session->setFlash(__($addedPayment . ' payment(s) imported successfully.'), 'success');
                $this->redirect(array('action' => 'society_payments'));
            } elseif (!empty($failedCnt) && !empty($addedPayment)) {
                $this->Session->setFlash(__($addedPayment . ' payment(s) saved, ' . $failedCnt . ' record(s) failed.' . $errorDetail), 'error');
                $this->redirect(array('action' => 'society_payments'));
            } elseif (!empty($failedCnt) && empty($addedPayment)) {
                $this->Session->setFlash(__('No payments could be saved. ' . $failedCnt . ' record(s) failed.' . $errorDetail), 'error');
                $this->redirect(array('action' => 'add_society_payment_from_xls'));
            }

            @unlink($uploadFile);
        } else {
            $this->set(compact('societyBankLists', 'societyCashLists', 'societyExpenseLedgerHeadsLists'));
        }
    }

    public function add_society_payment_from_pdf() {
        set_time_limit(0);
        $societyLoginID = $this->societyloginID;
        $financialYearId = $this->Util->getFinancialYear();

        $societyBankLists = $this->SocietyBill->societyBankBalanceHeadsLists();
        $societyCashLists = $this->SocietyBill->societyCashBalanceHeadsLists();
        $societyExpenseLedgerHeadsLists = $this->SocietyBill->societyAllLedgerHeadsLists();

        if ($this->request->is('post') && !empty($this->request->data['pdf_payments'])) {
            $paymentsJson = $this->request->data['pdf_payments'];
            $payments = json_decode($paymentsJson, true);

            if (empty($payments) || !is_array($payments)) {
                $this->Session->setFlash(__('No valid payment data received.'), 'error');
                $this->set(compact('societyExpenseLedgerHeadsLists'));
                return;
            }

            $ledgerHeadsByTitle = array();
            foreach ($societyExpenseLedgerHeadsLists as $id => $title) {
                $ledgerHeadsByTitle[strtolower(trim($title))] = $id;
            }

            $bankByTitle = array();
            if (!empty($societyBankLists)) {
                foreach ($societyBankLists as $id => $title) {
                    $bankByTitle[strtolower(trim($title))] = $id;
                }
            }
            $cashByTitle = array();
            if (!empty($societyCashLists)) {
                foreach ($societyCashLists as $id => $title) {
                    $cashByTitle[strtolower(trim($title))] = $id;
                }
            }
            $allByTitle = $bankByTitle + $cashByTitle;

            $this->loadModel('SocietyParameter');
            $checkSocietyParameters = $this->SocietyParameter->find('first', array(
                'conditions' => array('SocietyParameter.society_id' => $societyLoginID)
            ));

            if (empty($checkSocietyParameters['SocietyParameter'])) {
                $this->Session->setFlash(__('Society parameters not configured. Cannot save payments.'), 'error');
                $this->set(compact('societyExpenseLedgerHeadsLists'));
                return;
            }

            $addedPayment = 0;
            $failedCnt = 0;
            $failedReasons = array();
            $billVoucherNumber = $this->SocietyBill->billVoucherNumber();

            foreach ($payments as $index => $row) {
                $rowNum = $index + 1;

                $debitTo = isset($row['accountName']) ? trim($row['accountName']) : '';
                if (empty($debitTo)) {
                    $debitTo = isset($row['particulars']) ? trim($row['particulars']) : '';
                }

                $ledgerHeadId = null;
                $debitToLower = strtolower($debitTo);
                if (isset($ledgerHeadsByTitle[$debitToLower])) {
                    $ledgerHeadId = $ledgerHeadsByTitle[$debitToLower];
                } else {
                    foreach ($ledgerHeadsByTitle as $title => $id) {
                        if (strpos($debitToLower, $title) !== false || strpos($title, $debitToLower) !== false) {
                            $ledgerHeadId = $id;
                            break;
                        }
                    }
                }

                if (empty($ledgerHeadId)) {
                    $failedCnt++;
                    $failedReasons[] = 'Row ' . $rowNum . ': Debit To "' . $debitTo . '" not found in ledger heads';
                    continue;
                }

                $paymentDate = isset($row['paymentDate']) ? trim($row['paymentDate']) : '';
                if (empty($paymentDate)) {
                    $failedCnt++;
                    $failedReasons[] = 'Row ' . $rowNum . ': Missing payment date';
                    continue;
                }

                if (!$this->Util->isDateInCurrentFinancialYear($paymentDate)) {
                    $failedCnt++;
                    $failedReasons[] = 'Row ' . $rowNum . ': Payment date ' . $paymentDate . ' not in current financial year';
                    continue;
                }

                $paymentAmount = isset($row['amount']) ? floatval($row['amount']) : 0;
                if ($paymentAmount <= 0) {
                    $failedCnt++;
                    $failedReasons[] = 'Row ' . $rowNum . ': Invalid amount';
                    continue;
                }

                $paymentType = isset($row['paymentType']) ? trim($row['paymentType']) : 'Bank';
                if (!in_array($paymentType, array('Bank', 'Cash'))) {
                    $paymentType = 'Bank';
                }

                $paymentByLedgerId = 0;
                if (!empty($row['paymentBy'])) {
                    $byName = strtolower(trim($row['paymentBy']));
                    if ($paymentType == 'Bank' && isset($bankByTitle[$byName])) {
                        $paymentByLedgerId = $bankByTitle[$byName];
                    } elseif ($paymentType == 'Cash' && isset($cashByTitle[$byName])) {
                        $paymentByLedgerId = $cashByTitle[$byName];
                    } elseif (isset($allByTitle[$byName])) {
                        $paymentByLedgerId = $allByTitle[$byName];
                    }
                }
                if (empty($paymentByLedgerId)) {
                    if ($paymentType == 'Bank' && !empty($bankByTitle)) {
                        $paymentByLedgerId = reset($bankByTitle);
                    } elseif ($paymentType == 'Cash' && !empty($cashByTitle)) {
                        $paymentByLedgerId = reset($cashByTitle);
                    }
                }

                $taxAmount = isset($row['taxAmount']) ? floatval($row['taxAmount']) : 0.00;
                $totalAmount = $paymentAmount - $taxAmount;
                $chequeDate = isset($row['chequeDate']) ? trim($row['chequeDate']) : '';

                $saveData = array('SocietyPayment' => array(
                    'ledger_head_id'           => $ledgerHeadId,
                    'payment_date'             => $paymentDate,
                    'payment_type'             => $paymentType,
                    'payment_by_ledger_id'     => $paymentByLedgerId,
                    'amount'                   => $paymentAmount,
                    'tax_amount'               => $taxAmount,
                    'total_amount'             => $totalAmount,
                    'cheque_reference_number'  => isset($row['chequeNo']) ? $row['chequeNo'] : '',
                    'cheque_date'              => $chequeDate,
                    'bill_voucher_number'      => !empty($row['voucherNo']) ? $row['voucherNo'] : $billVoucherNumber,
                    'particulars'              => isset($row['particulars']) ? $row['particulars'] : '',
                    'notes'                    => isset($row['remark']) ? $row['remark'] : '',
                    'society_id'               => $societyLoginID,
                    'financial_year_id'        => $financialYearId,
                    'cdate'                    => date('Y-m-d H:i:s'),
                    'udate'                    => date('Y-m-d H:i:s')
                ));

                $this->SocietyPayment->create();
                if ($this->SocietyPayment->save($saveData)) {
                    $addedPayment++;
                    $billVoucherNumber++;
                } else {
                    $failedCnt++;
                    $failedReasons[] = 'Row ' . $rowNum . ': Database save failed';
                }
            }

            $errorDetail = '';
            if (!empty($failedReasons)) {
                $errorDetail = ' Errors: ' . implode(' | ', array_slice($failedReasons, 0, 5));
                if (count($failedReasons) > 5) {
                    $errorDetail .= ' ... and ' . (count($failedReasons) - 5) . ' more';
                }
            }

            if (empty($failedCnt) && $addedPayment > 0) {
                $this->Session->setFlash(__($addedPayment . ' payment(s) imported successfully from PDF.'), 'success');
                $this->redirect(array('action' => 'society_payments'));
            } elseif (!empty($failedCnt) && !empty($addedPayment)) {
                $this->Session->setFlash(__($addedPayment . ' payment(s) saved, ' . $failedCnt . ' record(s) failed.' . $errorDetail), 'error');
                $this->redirect(array('action' => 'society_payments'));
            } elseif (!empty($failedCnt) && empty($addedPayment)) {
                $this->Session->setFlash(__('No payments could be saved. ' . $failedCnt . ' record(s) failed.' . $errorDetail), 'error');
            } else {
                $this->Session->setFlash(__('No payment data found to import.'), 'error');
            }
        }

        $this->set(compact('societyExpenseLedgerHeadsLists'));
    }

    public function downloadSampleFileForSocietyPayment() {
        $societyLoginID = $this->societyloginID;
        $societyExpenseLedgerHeadsLists = $this->SocietyBill->societyAllLedgerHeadsLists();
        $societyBankLists = $this->SocietyBill->societyBankBalanceHeadsLists();
        $societyCashLists = $this->SocietyBill->societyCashBalanceHeadsLists();

        $allByNames = array();
        if (!empty($societyBankLists)) {
            foreach ($societyBankLists as $title) {
                $allByNames[] = $title;
            }
        }
        if (!empty($societyCashLists)) {
            foreach ($societyCashLists as $title) {
                if (!in_array($title, $allByNames)) {
                    $allByNames[] = $title;
                }
            }
        }

        $this->PhpExcel->createWorksheet()->setDefaultFont('Arial', 9);
        $this->PhpExcel->createSheet(0);
        $this->PhpExcel->getDefaultStyle()
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $this->PhpExcel->setActiveSheetIndex(0);
        $this->PhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->PhpExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->PhpExcel->getActiveSheet()->freezePane('A2');
        $this->PhpExcel->getActiveSheet()->setTitle('Society Payment');
        $this->PhpExcel->getActiveSheet()->getSheetView()->setZoomScale(100);

        $headerRow = array(
            'DebitTo', 'PaymentDate', 'PaymentType', 'PaymentBy', 'Amount', 'TaxAmount',
            'Particulars', 'ChequeNo', 'ChequeDate', 'VoucherNo', 'Notes'
        );

        $this->PhpExcel->setRow(1);
        $this->PhpExcel->addTableRow($headerRow, array('name' => 'Arial', 'bold' => true, 'size' => 12));

        $lastRow = count($societyExpenseLedgerHeadsLists) + 1;
        $rowNum = 2;
        if (!empty($societyExpenseLedgerHeadsLists)) {
            foreach ($societyExpenseLedgerHeadsLists as $id => $title) {
                $this->PhpExcel->setActiveSheetIndex(0)->setCellValue('A' . $rowNum, $title);
                $rowNum++;
            }
        }

        $sheet = $this->PhpExcel->getActiveSheet();

        // PaymentDate - date format + validation (Column B)
        $sheet->getStyle('B2:B' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('DD/MM/YYYY');
        $dateValidation = $sheet->getCell('B2')->getDataValidation();
        $dateValidation->setType(PHPExcel_Cell_DataValidation::TYPE_DATE);
        $dateValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
        $dateValidation->setAllowBlank(true);
        $dateValidation->setShowErrorMessage(true);
        $dateValidation->setErrorTitle('Invalid Date');
        $dateValidation->setError('Please enter a valid date (DD/MM/YYYY).');
        $dateValidation->setShowInputMessage(true);
        $dateValidation->setPromptTitle('Payment Date');
        $dateValidation->setPrompt('Enter payment date in DD/MM/YYYY format.');
        for ($r = 3; $r <= $lastRow; $r++) {
            $sheet->getCell('B' . $r)->setDataValidation(clone $dateValidation);
        }

        // PaymentType - dropdown Bank/Cash (Column C)
        $paymentTypeValidation = $sheet->getCell('C2')->getDataValidation();
        $paymentTypeValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
        $paymentTypeValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
        $paymentTypeValidation->setAllowBlank(true);
        $paymentTypeValidation->setShowDropDown(true);
        $paymentTypeValidation->setShowErrorMessage(true);
        $paymentTypeValidation->setErrorTitle('Invalid Payment Type');
        $paymentTypeValidation->setError('Please select Bank or Cash.');
        $paymentTypeValidation->setFormula1('"Bank,Cash"');
        for ($r = 3; $r <= $lastRow; $r++) {
            $sheet->getCell('C' . $r)->setDataValidation(clone $paymentTypeValidation);
        }

        // PaymentBy - dropdown of bank + cash names (Column D)
        $byNamesStr = implode(',', $allByNames);
        $paymentByValidation = $sheet->getCell('D2')->getDataValidation();
        $paymentByValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
        $paymentByValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
        $paymentByValidation->setAllowBlank(true);
        $paymentByValidation->setShowDropDown(true);
        $paymentByValidation->setShowErrorMessage(true);
        $paymentByValidation->setErrorTitle('Invalid Bank/Cash Name');
        $paymentByValidation->setError('Please select a valid Bank or Cash account name.');
        $paymentByValidation->setShowInputMessage(true);
        $paymentByValidation->setPromptTitle('Payment By');
        $paymentByValidation->setPrompt('Select the Bank or Cash account.');
        $paymentByValidation->setFormula1('"' . $byNamesStr . '"');
        for ($r = 3; $r <= $lastRow; $r++) {
            $sheet->getCell('D' . $r)->setDataValidation(clone $paymentByValidation);
        }

        // ChequeDate - date format + validation (Column I now)
        $sheet->getStyle('I2:I' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('DD/MM/YYYY');
        $chequeDateValidation = $sheet->getCell('I2')->getDataValidation();
        $chequeDateValidation->setType(PHPExcel_Cell_DataValidation::TYPE_DATE);
        $chequeDateValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
        $chequeDateValidation->setAllowBlank(true);
        $chequeDateValidation->setShowErrorMessage(true);
        $chequeDateValidation->setErrorTitle('Invalid Date');
        $chequeDateValidation->setError('Please enter a valid date (DD/MM/YYYY).');
        $chequeDateValidation->setShowInputMessage(true);
        $chequeDateValidation->setPromptTitle('Cheque Date');
        $chequeDateValidation->setPrompt('Enter cheque date in DD/MM/YYYY format.');
        for ($r = 3; $r <= $lastRow; $r++) {
            $sheet->getCell('I' . $r)->setDataValidation(clone $chequeDateValidation);
        }

        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(12);
        $sheet->getColumnDimension('K')->setWidth(20);

        // Reference sheet with ledger heads and bank/cash names
        $this->PhpExcel->createSheet(1);
        $this->PhpExcel->setActiveSheetIndex(1);
        $this->PhpExcel->getActiveSheet()->setTitle('Reference');
        $this->PhpExcel->setActiveSheetIndex(1)->setCellValue('A1', 'Ledger Head Name');
        $this->PhpExcel->setActiveSheetIndex(1)->setCellValue('B1', 'Bank Accounts');
        $this->PhpExcel->setActiveSheetIndex(1)->setCellValue('C1', 'Cash Accounts');
        $this->PhpExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true)->setSize(12);
        $this->PhpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $this->PhpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->PhpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

        $refRow = 2;
        if (!empty($societyExpenseLedgerHeadsLists)) {
            foreach ($societyExpenseLedgerHeadsLists as $id => $title) {
                $this->PhpExcel->setActiveSheetIndex(1)->setCellValue('A' . $refRow, $title);
                $refRow++;
            }
        }
        $refRow = 2;
        if (!empty($societyBankLists)) {
            foreach ($societyBankLists as $id => $title) {
                $this->PhpExcel->setActiveSheetIndex(1)->setCellValue('B' . $refRow, $title);
                $refRow++;
            }
        }
        $refRow = 2;
        if (!empty($societyCashLists)) {
            foreach ($societyCashLists as $id => $title) {
                $this->PhpExcel->setActiveSheetIndex(1)->setCellValue('C' . $refRow, $title);
                $refRow++;
            }
        }

        $this->PhpExcel->setActiveSheetIndex(0);

        date_default_timezone_set("Asia/Kolkata");
        $filename = "SampleImportSocietyPayment";
        $this->PhpExcel->output($filename . ".xlsx");
        $this->PhpExcel->exit();
    }

    public function cash_withdraws($cashWithdrawId = null) {
        $financialYearId = $this->Util->getFinancialYear();
        $societyLoginID = $this->societyloginID;

        $cashWithdrawData = $this->CashWithdraw->find('all', array('conditions' => array('CashWithdraw.society_id' => $societyLoginID , 'CashWithdraw.financial_year_id' => $financialYearId), 'order' => array('CashWithdraw.payment_date' => 'desc')));

        $this->set(compact('cashWithdrawData'));

    }



    public function add_cash_withdraws($cashWithdrawId = null) {
        $financialYearId = $this->Util->getFinancialYear();
        $societyLoginID = $this->societyloginID;

        if ($this->request->is(array('get')) && $cashWithdrawId) {
            $this->request->data = $this->CashWithdraw->find('first', array('conditions' => array('CashWithdraw.id' => $cashWithdrawId,'CashWithdraw.financial_year_id' => $financialYearId,
            'CashWithdraw.society_id' => $societyLoginID)));
        }

        if ($this->request->is('post', 'put')) {
            $this->request->data['CashWithdraw']['id'] = $cashWithdrawId;
            $this->request->data['CashWithdraw']['society_id'] = $societyLoginID;
            $this->request->data['CashWithdraw']['financial_year_id'] = $financialYearId;

            $paymentDate = $this->request->data['CashWithdraw']['payment_date'];  

            if($this->Util->isDateInCurrentFinancialYear($paymentDate)){
                if ($this->CashWithdraw->save($this->request->data)) {
                    // $this->SocietyBill->updateLedgerOpeningClosingBalance($societyLoginID);
                    $this->Session->setFlash(__(ucfirst($this->request->data['CashWithdraw']['txn_type']) . ' added successfully.'), 'success');    
                    $this->redirect(array('controller' => 'societys', 'action' => 'cash_withdraws'));
                } else {
                    $this->Session->setFlash(__(ucfirst($this->request->data['CashWithdraw']['txn_type']) . ' could not be saved .'), 'error');
                }
            }
            else{
                $this->Session->setFlash(__(ucfirst($this->request->data['CashWithdraw']['txn_type']) . ' could not be saved !Check payment date .'), 'error');
            }
        }
        $this->set(compact('societyBankBalanceHeadsLists'));
    }



    public function delete_cash_withdraws($id = null) {
        $societyLoginID = $this->societyloginID;
        if (!$this->CashWithdraw->exists($id)) {

            throw new NotFoundException(__('Requested record not found.'));

        }

        $this->request->allowMethod('post', 'delete');

        $this->CashWithdraw->id = $id;



        if ($this->CashWithdraw->delete($id)) {
            // $this->SocietyBill->updateLedgerOpeningClosingBalance($societyLoginID);
            $this->Session->setFlash(__('Record has been deleted.'), 'success');

        } else {

            $this->Session->setFlash(__('Record could not be deleted .'), 'error');

        }

        return $this->redirect(array('action' => 'cash_withdraws'));

    }



    public function delete_society_payments($paymentId = null) {
        $societyLoginID = $this->societyloginID;
        if (!$this->SocietyPayment->exists($paymentId)) {

            throw new NotFoundException(__('Requested payment is not found.'));

        }

        $this->request->allowMethod('post', 'delete');

        $this->SocietyPayment->id = $paymentId;

        if ($this->SocietyPayment->delete($paymentId)) {
            // $this->SocietyBill->updateLedgerOpeningClosingBalance($societyLoginID);
            $this->Session->setFlash(__('The payment has been deleted.'), 'success');

        } else {

            $this->Session->setFlash(__('The payment could not be deleted. Please, try again.'), 'error');

        }

        return $this->redirect(array('action' => 'society_payments'));

    }



    public function society_tariff_orders() {

        $societyLoginID = $this->societyloginID;

        $this->loadModel('SocietyTariffOrder');

        $this->loadModel('SocietyLedgerHeads');

        $this->SocietyLedgerHeads->Behaviors->attach('Containable');

//        if($this->SocietyTariffOrder->hasAny(array('SocietyTariffOrder.society_id'=>$societyLoginID))){

//            $societyLedgerHeadList = $this->SocietyTariffOrder->find('all',array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'),'SocietyLedgerHeads.society_id'=>$societyLoginID,'SocietyLedgerHeads.is_in_bill_charges' => 1),'fields' => array('SocietyLedgerHeads.title','SocietyLedgerHeads.id'),'order' => array('SocietyTariffOrder.id' => 'asc')));

//        }else{

//            $societyLedgerHeadList = $this->SocietyLedgerHeads->find('all', array('conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID,'SocietyLedgerHeads.is_in_bill_charges' => 1), 'fields' => array('SocietyLedgerHeads.title', 'SocietyLedgerHeads.id'), 'order' => array('SocietyLedgerHeads.title' => 'asc'),'recursive'=>-1));

//        }

        $societyLedgerHeadList = $this->SocietyLedgerHeads->find('all', array('contain' => array('SocietyTariffOrder'), 'conditions' => array('SocietyLedgerHeads.status' => Configure::read('Active'), 'SocietyLedgerHeads.society_id' => $societyLoginID, 'SocietyLedgerHeads.is_in_bill_charges' => 1), 'order' => array('SocietyTariffOrder.tariff_serial' => 'asc')));

        $this->set(compact('societyLedgerHeadList'));

    }



    public function delete_society_ledger_heads($societyHeadId = null) {

        $this->loadModel('SocietyLedgerHeads');
        $societyLoginID = $this->societyloginID;
        if (!$this->SocietyLedgerHeads->exists($societyHeadId)) {

            throw new NotFoundException(__('Requested building is not found.'));

        }

        $this->request->allowMethod('post', 'delete');

        // A ledger head used anywhere (bills, vouchers, payments, tariffs, bank,
        // opening balances) must not be removed - deactivating it would orphan
        // those rows. The list already hides the button for these; this refuses a
        // direct post as well.
        $usedLedgerHeadIds = $this->getUsedLedgerHeadIds($societyLoginID);
        if (isset($usedLedgerHeadIds[(int)$societyHeadId])) {
            $this->Session->setFlash(__('This ledger head is in use (bills or entries exist) and cannot be deleted.'), 'error');
            return $this->redirect(array('action' => 'society_ledger_heads'));
        }

        $this->SocietyLedgerHeads->id = $societyHeadId;

        if ($this->SocietyLedgerHeads->saveField('status', Configure::read('InActive'))) {
            // $this->SocietyBill->updateLedgerOpeningClosingBalance($societyLoginID);

            $this->Session->setFlash(__('The ledger head has been deleted.'), 'success');

        } else {

            $this->Session->setFlash(__('The ledger head could not be deleted. Please, try again.'), 'error');

        }

        return $this->redirect(array('action' => 'society_ledger_heads'));

    }



    public function saveSocietyPaymentEntryReciepts() {

        $this->layout = false;

        $responseArr['error'] = 1;

        $responseArr['error_message'] = 'Society payment data could not saved.';

        if ($this->RequestHandler->isAjax()) {

            $societyLoginID = $this->societyloginID;

            $this->loadModel('SocietyPayment');

            $this->loadModel('SocietyParameter');



            if ($this->request->is(array('post', 'put'))) {

                $checkSocietyParameters = $this->SocietyParameter->find('first', array('conditions' => array('SocietyParameter.society_id' => $societyLoginID)));



                if ($this->request->data['SocietyPayments']) {

                    foreach ($this->request->data['SocietyPayments'] as $societyPaymentPostData) {



                        if ($societyPaymentPostData['ledger_head_id'] != '' && $societyPaymentPostData['amount'] > 0) {



                            $postData['tax_amount'] = 0;

                            if (empty($this->request->data['generate_one_voucher']))

                                $billVoucherNumber = $this->SocietyBill->billVoucherNumber();

                            elseif (!empty($this->request->data['generate_one_voucher']) && empty($postData['bill_voucher_number']))

                                $billVoucherNumber = $this->SocietyBill->billVoucherNumber();



                            $postData['society_id'] = isset($societyLoginID) ? $societyLoginID : 0;

                            $postData['amount'] = isset($societyPaymentPostData['amount']) ? $societyPaymentPostData['amount'] : '0.00';

                            $postData['bill_voucher_number'] = isset($billVoucherNumber) ? $billVoucherNumber : 0;

                            $postData['ledger_head_id'] = isset($societyPaymentPostData['ledger_head_id']) ? $societyPaymentPostData['ledger_head_id'] : '';

                            $postData['cheque_reference_number'] = isset($societyPaymentPostData['cheque_reference_number']) ? $societyPaymentPostData['cheque_reference_number'] : '';

                            $postData['cheque_date'] = isset($societyPaymentPostData['cheque_date']) ? $societyPaymentPostData['cheque_date'] : '';

                            $postData['notes'] = isset($societyPaymentPostData['notes']) ? $societyPaymentPostData['notes'] : '';

                            $postData['particulars'] = isset($societyPaymentPostData['particulars']) ? $societyPaymentPostData['particulars'] : '';

                            $postData['payment_type'] = isset($this->request->data['SocietyPayment']['payment_type']) ? $this->request->data['SocietyPayment']['payment_type'] : '';

                            $postData['payment_by_ledger_id'] = isset($this->request->data['SocietyPayment']['payment_by_ledger_id']) ? $this->request->data['SocietyPayment']['payment_by_ledger_id'] : 0;

                            $postData['payment_date'] = isset($societyPaymentPostData['payment_date']) ? $societyPaymentPostData['payment_date'] : '';

                            if (isset($checkSocietyParameters['SocietyParameter']) && !empty($checkSocietyParameters['SocietyParameter'])) {

                                $paymentAmount = isset($societyPaymentPostData['amount']) ? $societyPaymentPostData['amount'] * 1 : 0.00;

                                //$cgstTaxAmount = round($paymentAmount * $checkSocietyParameters['SocietyParameter']['cgst_tax_per'] / 100, 2);

                                //$sgstTaxAmount = round($paymentAmount * $checkSocietyParameters['SocietyParameter']['sgst_tax_per'] / 100, 2);

                                $postData['tax_amount'] = isset($societyPaymentPostData['tax_amount']) ? $societyPaymentPostData['tax_amount'] : 0;

                                $postData['total_amount'] = $paymentAmount + $postData['tax_amount'];

                            }

                            $postData['cdate'] = $this->Util->getDateTime();

                            $postData['udate'] = $this->Util->getDateTime();

                            $this->SocietyPayment->create();

                            if ($this->SocietyPayment->save($postData)) {

                                $responseArr['error'] = 0;

                                $responseArr['error_message'] = 'Society payment has been made successfully.';

                            }

                        }

                    }

                }

            }

        }

        return new CakeResponse(array('body' => json_encode($responseArr), 'status' => 200));

    }



    public function cheque_clear_date() {
        $societyLoginID = $this->societyloginID;

        $postData = $this->request->data;

        $societyBankLists = $this->SocietyBill->getSocietyBankNameList();

        $societyCashBookDetails = array();

        $memberCashBookDetails = array();

        $cashBookData = array();
        
        $financialYearId = $this->Util->getFinancialYear();



        $options['AND'] = array('MemberPayment.society_id' => $societyLoginID);

        $optionsSociety['AND'] = array('SocietyPayment.society_id' => $societyLoginID);

        $optionsGeneral['AND'] = array('SocietyOtherIncome.society_id' => $societyLoginID);


        $options['AND'] = array('MemberPayment.financial_year_id' => $financialYearId);

        $optionsSociety['AND'] = array('SocietyPayment.financial_year_id' => $financialYearId);

        $optionsGeneral['AND'] = array('SocietyOtherIncome.financial_year_id' => $financialYearId);


        if (isset($this->request->data['MemberPayment']['society_bank_id']) && !empty($this->request->data['MemberPayment']['society_bank_id'])) {

            $options['AND']['MemberPayment.society_bank_id'] = $this->request->data['MemberPayment']['society_bank_id'];

            $optionsSociety['AND']['SocietyPayment.payment_by_ledger_id'] = $this->request->data['MemberPayment']['society_bank_id'];

            $optionsGeneral['AND']['SocietyOtherIncome.society_bank_id'] = $this->request->data['MemberPayment']['society_bank_id'];

        }

        if (isset($this->request->data['MemberPayment']['cheque_no']) && !empty($this->request->data['MemberPayment']['cheque_no'])) {

            $options['AND']['MemberPayment.cheque_reference_number'] = $this->request->data['MemberPayment']['cheque_no'];

            $optionsSociety['AND']['SocietyPayment.cheque_reference_number'] = $this->request->data['MemberPayment']['cheque_no'];

            $optionsGeneral['AND']['SocietyOtherIncome.cheque_no'] = $this->request->data['MemberPayment']['cheque_no'];

        }

        

        

        if (isset($this->request->data['MemberPayment']['payment_date']) && !empty($this->request->data['MemberPayment']['payment_date']) && !empty($this->request->data['MemberPayment']['payment_date_to'])) {

            if($this->request->data['MemberPayment']['status'] == 'Cleared'){

                $options['AND']['MemberPayment.credited_date BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['payment_date'], $this->request->data['MemberPayment']['payment_date_to']);

                $optionsSociety['AND']['SocietyPayment.debited_date BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['payment_date'], $this->request->data['MemberPayment']['payment_date_to']);

                $optionsGeneral['AND']['SocietyOtherIncome.credited_date BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['payment_date'], $this->request->data['MemberPayment']['payment_date_to']);

            

            }

            else{

                $options['AND']['MemberPayment.payment_date BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['payment_date'], $this->request->data['MemberPayment']['payment_date_to']);                

                $optionsSociety['AND']['SocietyPayment.payment_date BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['payment_date'], $this->request->data['MemberPayment']['payment_date_to']);

                $optionsGeneral['AND']['SocietyOtherIncome.payment_date BETWEEN ? and ?'] = array($this->request->data['MemberPayment']['payment_date'], $this->request->data['MemberPayment']['payment_date_to']);

            

            }



            } else if (isset($this->request->data['MemberPayment']['payment_date']) && !empty($this->request->data['MemberPayment']['payment_date'])) {

            if($this->request->data['MemberPayment']['status'] == 'Cleared'){                

                $options['AND']['MemberPayment.credited_date'] = $this->request->data['MemberPayment']['payment_date'];

                $optionsSociety['AND']['SocietyPayment.debited_date'] = $this->request->data['MemberPayment']['payment_date'];

                $optionsGeneral['AND']['SocietyOtherIncome.credited_date'] = $this->request->data['MemberPayment']['payment_date'];

            }

            else{

                $options['AND']['MemberPayment.payment_date'] = $this->request->data['MemberPayment']['payment_date'];

                $optionsSociety['AND']['SocietyPayment.payment_date'] = $this->request->data['MemberPayment']['payment_date'];

                $optionsGeneral['AND']['SocietyOtherIncome.payment_date'] = $this->request->data['MemberPayment']['payment_date'];

            }

            



        } else if (isset($this->request->data['MemberPayment']['payment_date_to']) && !empty($this->request->data['MemberPayment']['payment_date_to'])) {

            if($this->request->data['MemberPayment']['status'] == 'Cleared'){

                $options['AND']['MemberPayment.credited_date'] = $this->request->data['MemberPayment']['payment_date_to'];

                $optionsSociety['AND']['SocietyPayment.debited_date'] = $this->request->data['MemberPayment']['payment_date_to'];

                $optionsGeneral['AND']['SocietyOtherIncome.credited_date'] = $this->request->data['MemberPayment']['payment_date_to'];                                

            }

            else{

                $options['AND']['MemberPayment.payment_date'] = $this->request->data['MemberPayment']['payment_date_to'];    

                $optionsSociety['AND']['SocietyPayment.payment_date'] = $this->request->data['MemberPayment']['payment_date_to'];

                $optionsGeneral['AND']['SocietyOtherIncome.payment_date'] = $this->request->data['MemberPayment']['payment_date_to'];                

            }



        } else if (isset($this->request->data['MemberPayment']['bill_no']) && !empty($this->request->data['MemberPayment']['bill_no'])) {

            $options['AND']['MemberPayment.bank_slip_no'] = $this->request->data['MemberPayment']['bill_no'];

            $optionsSociety['AND']['SocietyPayment.bill_voucher_number'] = $this->request->data['MemberPayment']['bill_no'];

            $optionsGeneral['AND']['SocietyOtherIncome.general_receipt_number'] = $this->request->data['MemberPayment']['bill_no'];

        }



        if (isset($this->request->data['MemberPayment']['status']) && !empty($this->request->data['MemberPayment']['status'])) {

            if ($this->request->data['MemberPayment']['status'] == 'Cleared') {

                $options['AND']['MemberPayment.credited_date > ?'] = '1970-01-01';

                $optionsSociety['AND']['SocietyPayment.debited_date > ?'] = '1970-01-01';

                $optionsGeneral['AND']['SocietyOtherIncome.credited_date > ?'] = '1970-01-01';

            } else {

                $options['AND']['MemberPayment.credited_date'] = NULL;



                $optionsSociety['AND']['SocietyPayment.debited_date'] = NULL;



                $optionsGeneral['AND']['SocietyOtherIncome.credited_date'] = NULL;

            }

        }



        if ($this->request->is(array('post'))) {

            $this->MemberPayment->Behaviors->attach('Containable');

            $this->SocietyPayment->Behaviors->attach('Containable'); //Make sure report type is deposit or withdrawal

            if (isset($this->request->data['MemberPayment']['reciept_type']) && !empty($this->request->data['MemberPayment']['reciept_type']) && $this->request->data['MemberPayment']['reciept_type'] == "Receipt") {

                $memberCashBookDetails = $this->MemberPayment->find('all', array('contain' => array('Member'), 'conditions' => $options, 'order' => array('MemberPayment.payment_date' => 'asc')));

                /* if(isset($this->request->data['ClearChequePayment']) && !empty($this->request->data['ClearChequePayment'])){

                  $this->updateChequeClearedDate($this->request->data['MemberPayment']['reciept_type'],$this->request->data['ClearChequePayment']);

                  } */

            } else if (isset($this->request->data['MemberPayment']['reciept_type']) && !empty($this->request->data['MemberPayment']['reciept_type']) && $this->request->data['MemberPayment']['reciept_type'] == "Payment") {

                $societyCashBookDetails = $this->SocietyPayment->find('all', array('contain' => array('SocietyLedgerHeads'), 'conditions' => $optionsSociety, 'order' => array('SocietyPayment.payment_date' => 'asc')));

            } else if (isset($this->request->data['MemberPayment']['reciept_type']) && !empty($this->request->data['MemberPayment']['reciept_type']) && $this->request->data['MemberPayment']['reciept_type'] == "General") {

                $societyGeneralBookDetails = $this->SocietyOtherIncome->find('all', array('contain' => array('SocietyOtherIncome'), 'conditions' => $optionsGeneral, 'order' => array('SocietyOtherIncome.payment_date' => 'asc')));

            } else {

                $memberCashBookDetails = $this->MemberPayment->find('all', array('contain' => array('Member'), 'conditions' => $options, 'order' => array('MemberPayment.payment_date' => 'asc')));

                $societyCashBookDetails = $this->SocietyPayment->find('all', array('contain' => array('SocietyLedgerHeads'), 'conditions' => $optionsSociety, 'order' => array('SocietyPayment.payment_date' => 'asc')));

                $societyGeneralBookDetails = $this->SocietyOtherIncome->find('all', array('contain' => array('SocietyOtherIncome'), 'conditions' => $optionsGeneral, 'order' => array('SocietyOtherIncome.payment_date' => 'asc')));

            }

        }



        if (isset($societyGeneralBookDetails) && !empty($societyGeneralBookDetails)) {

            $gCounter = 0;

            foreach ($societyGeneralBookDetails as $societyGeneralBookData) {

                $cashBookData[$gCounter]['id'] = $societyGeneralBookData['SocietyOtherIncome']['id'];

                $cashBookData[$gCounter]['voucher_no'] = $societyGeneralBookData['SocietyOtherIncome']['general_receipt_number'];

                $cashBookData[$gCounter]['payment_date'] = $societyGeneralBookData['SocietyOtherIncome']['payment_date'];

                $cashBookData[$gCounter]['cleared_date'] = $societyGeneralBookData['SocietyOtherIncome']['credited_date'];

                $cashBookData[$gCounter]['cheque_date'] = $societyGeneralBookData['SocietyOtherIncome']['payment_date'];

                $cashBookData[$gCounter]['particulars'] = $societyGeneralBookData['SocietyOtherIncome']['title'] . '&' . $societyGeneralBookData['SocietyOtherIncome']['description'];

                $cashBookData[$gCounter]['cheque_number'] = $societyGeneralBookData['SocietyOtherIncome']['cheque_no'];

                $cashBookData[$gCounter]['withdrawal'] = "0.00";

                $cashBookData[$gCounter]['payment_flag'] = "General";

                $cashBookData[$gCounter]['deposit'] = $societyGeneralBookData['SocietyOtherIncome']['amount_paid'];

                unset($societyGeneralBookData);

                $gCounter++;

            }

        }



        if (isset($societyCashBookDetails) && !empty($societyCashBookDetails)) {

            $sCounter = 0;

            foreach ($societyCashBookDetails as $societyBankBbookData) {

                $cashBookData[$sCounter]['id'] = $societyBankBbookData['SocietyPayment']['id'];

                $cashBookData[$sCounter]['voucher_no'] = $societyBankBbookData['SocietyPayment']['bill_voucher_number'];

                $cashBookData[$sCounter]['payment_date'] = $societyBankBbookData['SocietyPayment']['payment_date'];

                $cashBookData[$sCounter]['cleared_date'] = $societyBankBbookData['SocietyPayment']['debited_date'];

                $cashBookData[$sCounter]['cheque_date'] = $societyBankBbookData['SocietyPayment']['cheque_date'];

                $cashBookData[$sCounter]['particulars'] = $societyBankBbookData['SocietyPayment']['particulars'] . '&' . $societyBankBbookData['SocietyLedgerHeads']['title'];

                $cashBookData[$sCounter]['cheque_number'] = $societyBankBbookData['SocietyPayment']['cheque_reference_number'];

                $cashBookData[$sCounter]['withdrawal'] = $societyBankBbookData['SocietyPayment']['total_amount'];

                $cashBookData[$sCounter]['payment_flag'] = "Payment";

                $cashBookData[$sCounter]['deposit'] = "0.00";

                unset($societyBankBbookData);

                $sCounter++;

            }

        }



        if (isset($memberCashBookDetails) && !empty($memberCashBookDetails)) {

            $mCounter = 0;

            foreach ($memberCashBookDetails as $memberBankBbookData) {

                $cashBookData[$mCounter]['id'] = $memberBankBbookData['MemberPayment']['id'];

                $cashBookData[$mCounter]['voucher_no'] = $memberBankBbookData['MemberPayment']['bill_generated_id'];

                $cashBookData[$mCounter]['payment_date'] = $memberBankBbookData['MemberPayment']['payment_date'];

                $cashBookData[$mCounter]['cheque_date'] = $memberBankBbookData['MemberPayment']['payment_date'];

                $cashBookData[$mCounter]['cleared_date'] = $memberBankBbookData['MemberPayment']['credited_date'];

                $cashBookData[$mCounter]['particulars'] = $memberBankBbookData['Member']['member_name'] . ' Maint received';

                $cashBookData[$mCounter]['cheque_number'] = $memberBankBbookData['MemberPayment']['cheque_reference_number'];

                $cashBookData[$mCounter]['withdrawal'] = "0.00";

                $cashBookData[$mCounter]['deposit'] = $memberBankBbookData['MemberPayment']['amount_paid'];

                $cashBookData[$mCounter]['payment_flag'] = "Receipt";

                $cashBookData[$mCounter]['flat_no'] = $memberBankBbookData['Member']['flat_no'];

                unset($memberBankBbookData);

                $mCounter++;

            }

        }

        ksort($cashBookData);

        $this->set(compact('postData', 'cashBookData', 'societyBankLists'));

    }



    public function society_banks() {

        $societyLoginID = $this->societyloginID;

        $postData = $this->request->data;

        $societyBankLists = $this->SocietyBill->getSocietyBankNameList();



        $this->loadModel('SocietyBank');



        if ($this->request->is('post', 'put')) {

            $this->request->data['SocietyBank']['society_id'] = $societyLoginID;



            if ($this->SocietyBank->save($this->request->data)) {

                $this->Session->setFlash(__(ucfirst('Bank account details added successfully.')), 'success');

                $this->redirect(array('controller' => 'societys', 'action' => 'society_banks'));

            } else {

                $this->Session->setFlash(__(ucfirst('Bank account details could not be saved .')), 'error');

            }

        }



        $societyBankData = $this->SocietyBank->find('all', array('conditions' => array('SocietyBank.society_id' => $societyLoginID)));



        $this->set(compact('postData', 'societyBankLists', 'societyBankData'));

    }



    public function updateChequeClearedDate($recieptType = '', $dataArr = array()) {

        if (isset($dataArr) && !empty($dataArr)) {

            foreach ($dataArr as $paymentID => $chequeData) {

                if ($recieptType == "Receipt") {

                    $this->MemberPayment->id = $paymentID;

                    $this->MemberPayment->saveField('credited_date', $chequeData['cleared_date']);

                } else if ($recieptType == "Payment") {

                    $this->SocietyPayment->id = $paymentID;

                    $this->SocietyPayment->saveField('debited_date', $chequeData['cleared_date']);

                } else if ($recieptType == "General") {

                    $this->SocietyOtherIncome->id = $paymentID;

                    $this->SocietyOtherIncome->saveField('credited_date', $chequeData['cleared_date']);

                }

            }

        }

    }



    public function member_identifications() {

        $this->loadModel('MemberIdentification');

        $MemberIdentificationDetails = $this->MemberIdentification->find('all', array('conditions' => array('MemberIdentification.status' => Configure::read('Active'), 'MemberIdentification.society_id' => $this->Session->read('Auth.User.id')), 'order' => array('MemberIdentification.flat_no' => 'asc')));

        //print_r($MemberIdentificationDetails);die;

        $this->set(compact('MemberIdentificationDetails'));

    }

    
    public function add_member_identifications($societyIdentityId = null) {
        $this->SocietyBill->getMemberslastClosingBalance();

        $financialYearId = $this->Util->getFinancialYear();
        $this->loadModel('MemberIdentification');
        $this->loadModel('Member');
        $societyMemberFlatLists = $this->SocietyBill->getSocietyMemberFlatList();
        $societyLoginID = $this->societyloginID;
        $postData = array();
        $memberIdentificationDetails = array();
        if ($this->request->is(array('post'))) {

        $postData['id'] = $identificationId =  isset($this->request->data['MemberIdentification']['id']) ? $this->request->data['MemberIdentification']['id'] : null;

            if(empty($identificationId)){
                $flatAndMemberIdExpload = explode('__', $this->request->data['MemberIdentification']['flat_no']);            
                $postData['society_id'] = isset($societyLoginID) ? $societyLoginID : 0;
                $postData['member_id'] = $memberId = isset($flatAndMemberIdExpload[1]) ? $flatAndMemberIdExpload[1] : null;
                $postData['flat_no'] = isset($flatAndMemberIdExpload[0]) ? $flatAndMemberIdExpload[0] : '';
                $postData['third_member'] = isset($this->request->data['MemberIdentification']['third_member']) ? $this->request->data['MemberIdentification']['third_member'] : '';
            }
            else{
                $memberId = isset($this->request->data['MemberIdentification']['member_id']) ? $this->request->data['MemberIdentification']['member_id'] : '';
            }

            $postData['second_member'] = isset($this->request->data['MemberIdentification']['second_member']) ? $this->request->data['MemberIdentification']['second_member'] : '';

            $postData['associate_member'] = isset($this->request->data['MemberIdentification']['associate_member']) ? $this->request->data['MemberIdentification']['associate_member'] : '';
            $MemberReports  = $this->Member->find('first', array('conditions' => array('Member.id' => $postData['member_id'], 'Member.society_id' => $societyLoginID)));
            $old_op_principal   =  $MemberReports['Member']['op_principal'];
            $old_op_interest    =  $MemberReports['Member']['op_interest'];
            $old_op_tax         =  $MemberReports['Member']['op_tax'];

            $postData['op_principal'] = isset($old_op_principal) ? $old_op_principal : '';
            $postData['op_interest'] = isset($old_op_interest) ? $old_op_interest : '';
            $postData['op_tax'] = isset($old_op_tax) ? $old_op_tax : '';
            $postData['joint_member'] = isset($this->request->data['MemberIdentification']['joint_member']) ? $this->request->data['MemberIdentification']['joint_member'] : '';
            $postData['date_of_birth'] = isset($this->request->data['MemberIdentification']['date_of_birth']) ? $this->request->data['MemberIdentification']['date_of_birth'] : '';
            $postData['class'] = isset($this->request->data['MemberIdentification']['class']) ? $this->request->data['MemberIdentification']['class'] : '';
            $postData['GSTIN'] = isset($this->request->data['MemberIdentification']['GSTIN']) ? $this->request->data['MemberIdentification']['GSTIN'] : '';
            $postData['date_of_admission_1'] = isset($this->request->data['MemberIdentification']['date_of_admission_1']) ? $this->request->data['MemberIdentification']['date_of_admission_1'] : '';
            $postData['date_of_admission_2'] = isset($this->request->data['MemberIdentification']['date_of_admission_2']) ? $this->request->data['MemberIdentification']['date_of_admission_2'] : '';
            $postData['purchased_on'] = isset($this->request->data['MemberIdentification']['purchased_on']) ? $this->request->data['MemberIdentification']['purchased_on'] : '';
            $postData['disposed_on'] = isset($this->request->data['MemberIdentification']['disposed_on']) ? $this->request->data['MemberIdentification']['disposed_on'] : '';
            $postData['agreement_date'] = isset($this->request->data['MemberIdentification']['agreement_date']) ? $this->request->data['MemberIdentification']['agreement_date'] : '';
            $postData['agreement_value'] = isset($this->request->data['MemberIdentification']['agreement_value']) ? $this->request->data['MemberIdentification']['agreement_value'] : '';
            $postData['stamp_duty_no'] = isset($this->request->data['MemberIdentification']['stamp_duty_no']) ? $this->request->data['MemberIdentification']['stamp_duty_no'] : '';
            $postData['gender'] = isset($this->request->data['MemberIdentification']['gender']) ? $this->request->data['MemberIdentification']['gender'] : '';
            $postData['age'] = isset($this->request->data['MemberIdentification']['age']) ? $this->request->data['MemberIdentification']['age'] : '';
            $postData['religion'] = isset($this->request->data['MemberIdentification']['religion']) ? $this->request->data['MemberIdentification']['religion'] : '';
            $postData['blood_group'] = isset($this->request->data['MemberIdentification']['blood_group']) ? $this->request->data['MemberIdentification']['blood_group'] : '';
            $postData['profession'] = isset($this->request->data['MemberIdentification']['profession']) ? $this->request->data['MemberIdentification']['profession'] : '';
            $postData['nationality'] = isset($this->request->data['MemberIdentification']['nationality']) ? $this->request->data['MemberIdentification']['nationality'] : '';
            $postData['members_bank'] = isset($this->request->data['MemberIdentification']['members_bank']) ? $this->request->data['MemberIdentification']['members_bank'] : '';
            $postData['pan_no'] = isset($this->request->data['MemberIdentification']['pan_no']) ? $this->request->data['MemberIdentification']['pan_no'] : '';
            $postData['date_of_allotment'] = isset($this->request->data['MemberIdentification']['date_of_allotment']) ? $this->request->data['MemberIdentification']['date_of_allotment'] : '';
            $postData['certification_no'] = isset($this->request->data['MemberIdentification']['certification_no']) ? $this->request->data['MemberIdentification']['certification_no'] : '';
            $postData['folio_no'] = isset($this->request->data['MemberIdentification']['folio_no']) ? $this->request->data['MemberIdentification']['folio_no'] : '';
            $postData['no_of_shares'] = isset($this->request->data['MemberIdentification']['no_of_shares']) ? $this->request->data['MemberIdentification']['no_of_shares'] : '';
            $postData['shares_value'] = isset($this->request->data['MemberIdentification']['shares_value']) ? $this->request->data['MemberIdentification']['shares_value'] : '';
            $postData['from_share_no'] = isset($this->request->data['MemberIdentification']['from_share_no']) ? $this->request->data['MemberIdentification']['from_share_no'] : '';
            $postData['to_share_no'] = isset($this->request->data['MemberIdentification']['to_share_no']) ? $this->request->data['MemberIdentification']['to_share_no'] : '';
            $postData['shares_status'] = isset($this->request->data['MemberIdentification']['shares_status']) ? $this->request->data['MemberIdentification']['shares_status'] : '';
            $postData['admission_fees'] = isset($this->request->data['MemberIdentification']['admission_fees']) ? $this->request->data['MemberIdentification']['admission_fees'] : '';
            $postData['full_name'] = isset($this->request->data['MemberIdentification']['full_name']) ? $this->request->data['MemberIdentification']['full_name'] : '';
            $postData['unit_no'] = isset($this->request->data['MemberIdentification']['unit_no']) ? $this->request->data['MemberIdentification']['unit_no'] : '';
            $postData['address'] = isset($this->request->data['MemberIdentification']['address']) ? $this->request->data['MemberIdentification']['address'] : '';
            $postData['occupation'] = isset($this->request->data['MemberIdentification']['occupation']) ? $this->request->data['MemberIdentification']['occupation'] : '';
            $postData['age_on_date_of_admission'] = isset($this->request->data['MemberIdentification']['age_on_date_of_admission']) ? $this->request->data['MemberIdentification']['age_on_date_of_admission'] : '';
            $postData['nominee_name'] = isset($this->request->data['MemberIdentification']['nominee_name']) ? $this->request->data['MemberIdentification']['nominee_name'] : '';
            $postData['nomination_date'] = isset($this->request->data['MemberIdentification']['nomination_date']) ? $this->request->data['MemberIdentification']['nomination_date'] : '';
            $postData['nominee_address'] = isset($this->request->data['MemberIdentification']['nominee_address']) ? $this->request->data['MemberIdentification']['nominee_address'] : '';
            $postData['date_of_cessation_membership'] = isset($this->request->data['MemberIdentification']['date_of_cessation_membership']) ? $this->request->data['MemberIdentification']['date_of_cessation_membership'] : '';
            $postData['reason_for_cessation'] = isset($this->request->data['MemberIdentification']['reason_for_cessation']) ? $this->request->data['MemberIdentification']['reason_for_cessation'] : '';
            $postData['remarks'] = isset($this->request->data['MemberIdentification']['remarks']) ? $this->request->data['MemberIdentification']['remarks'] : '';
            $postData['cdate'] = $this->Util->getDateTime();
            $postData['udate'] = $this->Util->getDateTime();
            $postData['financial_year_id'] = $financialYearId;

            $Counts = $this->MemberIdentification->find('all', array('conditions' => array('
                MemberIdentification.member_id' => $memberId)));            
            $count = count($Counts);
            $postData['member_transfer'] = $count;

            $memberIdentificationDetails = $this->MemberIdentification->save($postData);
            if($memberIdentificationDetails){ 
                $second_member = $postData['second_member'];
                $member_trasfer= $count+1;                // for new new status update to +1
                // Preserve the member's existing opening balance when the form leaves
                // these blank (isset() was true for an empty field, wiping op_* to 0).
                $new_op_principal   =  !empty($this->request->data['MemberIdentification']['op_principal']) ? $this->request->data['MemberIdentification']['op_principal'] : $old_op_principal;
                $new_op_interest    =  !empty($this->request->data['MemberIdentification']['op_interest']) ? $this->request->data['MemberIdentification']['op_interest'] : $old_op_interest;
                $new_op_tax         =  !empty($this->request->data['MemberIdentification']['op_tax']) ? $this->request->data['MemberIdentification']['op_tax'] : $old_op_tax;
                $this->Member->updateAll(array('Member.member_name' =>"'$second_member'", 'Member.member_transfer' => "'$member_trasfer'", 'Member.op_principal' => "'$new_op_principal'", 'Member.op_interest' => "'$new_op_interest'", 'Member.op_tax' => "'$new_op_tax'"),array('Member.id'=>$memberId));
                $this->Session->setFlash(__('Member Information Save Successfully'), 'success');
                $this->redirect(array('controller' => 'societys', 'action' => 'member_identifications'));                
            }
            else{
                $this->Session->setFlash(__('Member Transfer Failed'), 'error');
                $this->redirect(array('controller' => 'societys', 'action' => 'member_identifications'));
            }
            
        } else {
            $postData = $this->MemberIdentification->find('first', array('conditions' => array('MemberIdentification.id' => $societyIdentityId, 'MemberIdentification.society_id' => $societyLoginID)));
        }
        $this->set(compact('postData', 'memberIdentificationDetails', 'societyMemberFlatLists'));
    }    




    public function add_member_identifications_bkOM($societyIdentityId = null) {

        $this->loadModel('MemberIdentification');

        $societyMemberFlatLists = $this->SocietyBill->getSocietyMemberFlatList();

        $societyLoginID = $this->societyloginID;

        $postData = array();

        $memberIdentificationDetails = array();

        if ($this->request->is(array('post'))) {

            $postData['id'] = isset($this->request->data['MemberIdentification']['id']) ? $this->request->data['MemberIdentification']['id'] : null;

            $postData['society_id'] = isset($societyLoginID) ? $societyLoginID : 0;

            $postData['member_id'] = isset($this->request->data['MemberIdentification']['member_id']) ? $this->request->data['MemberIdentification']['member_id'] : null;

            $postData['flat_no'] = isset($this->request->data['MemberIdentification']['flat_no']) ? $this->request->data['MemberIdentification']['flat_no'] : '';

            $postData['second_member'] = isset($this->request->data['MemberIdentification']['second_member']) ? $this->request->data['MemberIdentification']['second_member'] : '';

            $postData['third_member'] = isset($this->request->data['MemberIdentification']['third_member']) ? $this->request->data['MemberIdentification']['third_member'] : '';

            $postData['associate_member'] = isset($this->request->data['MemberIdentification']['associate_member']) ? $this->request->data['MemberIdentification']['associate_member'] : '';

            $postData['joint_member'] = isset($this->request->data['MemberIdentification']['joint_member']) ? $this->request->data['MemberIdentification']['joint_member'] : '';

            $postData['date_of_birth'] = isset($this->request->data['MemberIdentification']['date_of_birth']) ? $this->request->data['MemberIdentification']['date_of_birth'] : '';

            $postData['class'] = isset($this->request->data['MemberIdentification']['class']) ? $this->request->data['MemberIdentification']['class'] : '';

            $postData['GSTIN'] = isset($this->request->data['MemberIdentification']['GSTIN']) ? $this->request->data['MemberIdentification']['GSTIN'] : '';

            $postData['date_of_admission_1'] = isset($this->request->data['MemberIdentification']['date_of_admission_1']) ? $this->request->data['MemberIdentification']['date_of_admission_1'] : '';

            $postData['date_of_admission_2'] = isset($this->request->data['MemberIdentification']['date_of_admission_2']) ? $this->request->data['MemberIdentification']['date_of_admission_2'] : '';

            $postData['purchased_on'] = isset($this->request->data['MemberIdentification']['purchased_on']) ? $this->request->data['MemberIdentification']['purchased_on'] : '';

            $postData['disposed_on'] = isset($this->request->data['MemberIdentification']['disposed_on']) ? $this->request->data['MemberIdentification']['disposed_on'] : '';

            $postData['agreement_date'] = isset($this->request->data['MemberIdentification']['agreement_date']) ? $this->request->data['MemberIdentification']['agreement_date'] : '';

            $postData['agreement_value'] = isset($this->request->data['MemberIdentification']['agreement_value']) ? $this->request->data['MemberIdentification']['agreement_value'] : '';

            $postData['stamp_duty_no'] = isset($this->request->data['MemberIdentification']['stamp_duty_no']) ? $this->request->data['MemberIdentification']['stamp_duty_no'] : '';

            $postData['gender'] = isset($this->request->data['MemberIdentification']['gender']) ? $this->request->data['MemberIdentification']['gender'] : '';

            $postData['age'] = isset($this->request->data['MemberIdentification']['age']) ? $this->request->data['MemberIdentification']['age'] : '';

            $postData['religion'] = isset($this->request->data['MemberIdentification']['religion']) ? $this->request->data['MemberIdentification']['religion'] : '';

            $postData['blood_group'] = isset($this->request->data['MemberIdentification']['blood_group']) ? $this->request->data['MemberIdentification']['blood_group'] : '';

            $postData['profession'] = isset($this->request->data['MemberIdentification']['profession']) ? $this->request->data['MemberIdentification']['profession'] : '';

            $postData['nationality'] = isset($this->request->data['MemberIdentification']['nationality']) ? $this->request->data['MemberIdentification']['nationality'] : '';

            $postData['members_bank'] = isset($this->request->data['MemberIdentification']['members_bank']) ? $this->request->data['MemberIdentification']['members_bank'] : '';

            $postData['pan_no'] = isset($this->request->data['MemberIdentification']['pan_no']) ? $this->request->data['MemberIdentification']['pan_no'] : '';

            $postData['date_of_allotment'] = isset($this->request->data['MemberIdentification']['date_of_allotment']) ? $this->request->data['MemberIdentification']['date_of_allotment'] : '';

            $postData['certification_no'] = isset($this->request->data['MemberIdentification']['certification_no']) ? $this->request->data['MemberIdentification']['certification_no'] : '';

            $postData['folio_no'] = isset($this->request->data['MemberIdentification']['folio_no']) ? $this->request->data['MemberIdentification']['folio_no'] : '';

            $postData['no_of_shares'] = isset($this->request->data['MemberIdentification']['no_of_shares']) ? $this->request->data['MemberIdentification']['no_of_shares'] : '';

            $postData['shares_value'] = isset($this->request->data['MemberIdentification']['shares_value']) ? $this->request->data['MemberIdentification']['shares_value'] : '';

            $postData['from_share_no'] = isset($this->request->data['MemberIdentification']['from_share_no']) ? $this->request->data['MemberIdentification']['from_share_no'] : '';

            $postData['to_share_no'] = isset($this->request->data['MemberIdentification']['to_share_no']) ? $this->request->data['MemberIdentification']['to_share_no'] : '';

            $postData['shares_status'] = isset($this->request->data['MemberIdentification']['shares_status']) ? $this->request->data['MemberIdentification']['shares_status'] : '';

            $postData['admission_fees'] = isset($this->request->data['MemberIdentification']['admission_fees']) ? $this->request->data['MemberIdentification']['admission_fees'] : '';

            $postData['full_name'] = isset($this->request->data['MemberIdentification']['full_name']) ? $this->request->data['MemberIdentification']['full_name'] : '';

            $postData['unit_no'] = isset($this->request->data['MemberIdentification']['unit_no']) ? $this->request->data['MemberIdentification']['unit_no'] : '';

            $postData['address'] = isset($this->request->data['MemberIdentification']['address']) ? $this->request->data['MemberIdentification']['address'] : '';

            $postData['occupation'] = isset($this->request->data['MemberIdentification']['occupation']) ? $this->request->data['MemberIdentification']['occupation'] : '';

            $postData['age_on_date_of_admission'] = isset($this->request->data['MemberIdentification']['age_on_date_of_admission']) ? $this->request->data['MemberIdentification']['age_on_date_of_admission'] : '';

            $postData['nominee_name'] = isset($this->request->data['MemberIdentification']['nominee_name']) ? $this->request->data['MemberIdentification']['nominee_name'] : '';

            $postData['nomination_date'] = isset($this->request->data['MemberIdentification']['nomination_date']) ? $this->request->data['MemberIdentification']['nomination_date'] : '';

            $postData['nominee_address'] = isset($this->request->data['MemberIdentification']['nominee_address']) ? $this->request->data['MemberIdentification']['nominee_address'] : '';

            $postData['date_of_cessation_membership'] = isset($this->request->data['MemberIdentification']['date_of_cessation_membership']) ? $this->request->data['MemberIdentification']['date_of_cessation_membership'] : '';

            $postData['reason_for_cessation'] = isset($this->request->data['MemberIdentification']['reason_for_cessation']) ? $this->request->data['MemberIdentification']['reason_for_cessation'] : '';

            $postData['remarks'] = isset($this->request->data['MemberIdentification']['remarks']) ? $this->request->data['MemberIdentification']['remarks'] : '';

            $postData['cdate'] = $this->Util->getDateTime();

            $postData['udate'] = $this->Util->getDateTime();

            //print_r($postData);die;

            $memberIdentificationDetails = $this->MemberIdentification->save($postData);

            $this->Session->setFlash(__('Member Information Save Successfully'), 'success');

            $this->redirect(array('controller' => 'societys', 'action' => 'member_identifications'));

        } else {

            $postData = $this->MemberIdentification->find('first', array('conditions' => array('MemberIdentification.id' => $societyIdentityId, 'MemberIdentification.society_id' => $societyLoginID)));

        }

        $this->set(compact('postData', 'memberIdentificationDetails', 'societyMemberFlatLists'));

    }



    public function delete_member_identification($meberIdentityId = null) {

        $this->loadModel('MemberIdentification');

        if (!$this->MemberIdentification->exists($meberIdentityId)) {

            throw new NotFoundException(__('Requested Member is not found.'));

        }

        $this->request->allowMethod('post', 'delete');

        $this->MemberIdentification->id = $meberIdentityId;

        if ($this->MemberIdentification->saveField('status', Configure::read('InActive'))) {

            $this->Session->setFlash(__('The Member Identification has been deleted.'), 'success');

        } else {

            $this->Session->setFlash(__('The Member Identification could not be deleted. Please, try again.'), 'error');

        }

        return $this->redirect(array('action' => 'member_identifications'));

    }



    public function general_receipt() {
        $financialYearId = $this->Util->getFinancialYear();

        $this->loadModel('SocietyOtherIncome');

        $societyLoginID = $this->societyloginID;

        $societyOtherIncomeData = $this->SocietyOtherIncome->find('all', array('conditions' => array('SocietyOtherIncome.society_id' => $societyLoginID, 
        'SocietyOtherIncome.status' => Configure::read('Active') , 'SocietyOtherIncome.financial_year_id' => $financialYearId), 'order' => array('SocietyOtherIncome.payment_date' => 'desc')));



        $this->set(compact('societyOtherIncomeData'));

    }



 public function add_general_receipt($societyPaymentId = null) {

        $responseArr['error'] = 1;

        $this->loadModel('SocietyOtherIncome');

        $this->loadModel('SocietyLedgerHeads');

        $societyLoginID = $this->societyloginID;

        $responseArr['error_message'] = 'General Receipt data could not saved.';
        $financialYearId = $this->Util->getFinancialYear();



        $otherIncomerData = [];
        $wrongDataEntrycount = 0;
        if ($this->request->is(array('post', 'put'))) {

            if ($this->request->data['SocietyOtherIncome']) {

                if (isset($this->request->data['SocietyOtherIncome']) && count($this->request->data['SocietyOtherIncome']) > 0) {

                    foreach ($this->request->data['SocietyOtherIncome'] as $societyOhterIncomeData) {
                        $postData = [];

                        if (isset($societyOhterIncomeData['ledger_head_id']) && $societyOhterIncomeData['ledger_head_id'] != '') {

                            $paymentDate = isset($societyOhterIncomeData['payment_date']) ? $societyOhterIncomeData['payment_date'] : '';
                            if(empty($paymentDate) || !$this->Util->isDateInCurrentFinancialYear($paymentDate)) {
                                $wrongDataEntrycount++;
                                continue;
                            }


                            if (empty($this->request->data['generate_one_voucher']))

                                $billVoucherNumber = $this->SocietyBill->generalReceiptNumber();

                            elseif (!empty($this->request->data['generate_one_voucher']) && empty($postData['general_receipt_number']))

                            $billVoucherNumber = $this->SocietyBill->generalReceiptNumber();

                            $postData['general_receipt_number'] = isset($billVoucherNumber) ? $billVoucherNumber : '';

                            $postData['society_id'] = isset($societyLoginID) ? $societyLoginID : 0;

                            $postData['id'] = isset($societyOhterIncomeData['id']) ? $societyOhterIncomeData['id'] : null;

                            $postData['amount_paid'] = isset($societyOhterIncomeData['amount_paid']) && !empty($societyOhterIncomeData['amount_paid']) ? $societyOhterIncomeData['amount_paid'] : '0.00';
                            $postData['tds_amount'] = isset($societyOhterIncomeData['tds_amount']) && !empty($societyOhterIncomeData['tds_amount']) ? $societyOhterIncomeData['tds_amount'] : '0.00';
                            $postData['net_amount'] = isset($societyOhterIncomeData['net_amount']) && !empty($societyOhterIncomeData['net_amount']) ? $societyOhterIncomeData['net_amount'] : '0.00';
                            $postData['tds_bank_id'] = isset($societyOhterIncomeData['tds_bank_id']) && !empty($societyOhterIncomeData['tds_bank_id']) ? $societyOhterIncomeData['tds_bank_id'] : null;
                            $postData['payment_mode'] = isset($this->request->data['SocietyOtherIncomeHeader']['payment_mode']) ? trim($this->request->data['SocietyOtherIncomeHeader']['payment_mode']) : 'null';

                            if ($postData['payment_mode'] == 'Bank') {

                                $postData['cheque_no'] = isset($societyOhterIncomeData['cheque_no']) && !empty($societyOhterIncomeData['cheque_no']) ? $societyOhterIncomeData['cheque_no'] : '0.00';

                                $postData['cheque_date'] = isset($societyOhterIncomeData['cheque_date']) ? $societyOhterIncomeData['cheque_date'] : '';

                                $postData['society_bank_id'] = isset($this->request->data['SocietyOtherIncomeHeader']['society_bank_id']) ? $this->request->data['SocietyOtherIncomeHeader']['society_bank_id'] : 'null';

                            } else {

                                $postData['cheque_no'] = '0.00';

                                $postData['cheque_date'] = '';

                                // A cash receipt is still tied to a ledger - the "By" (Cash in Hand)
                                // dropdown, posted as SocietyOtherIncomeHeader.society_bank_id. The cash
                                // book, trial balance and balance sheet all match cash receipts by this
                                // id, so forcing it to null here dropped every cash general receipt from
                                // the cash figures. Keep the selected ledger, exactly as the Bank branch.
                                $postData['society_bank_id'] = isset($this->request->data['SocietyOtherIncomeHeader']['society_bank_id']) && $this->request->data['SocietyOtherIncomeHeader']['society_bank_id'] !== '' ? $this->request->data['SocietyOtherIncomeHeader']['society_bank_id'] : 'null';

                            }

                            $postData['description']                 = isset($societyOhterIncomeData['description']) ? $societyOhterIncomeData['description'] : '';

                            $postData['general_bank_name']           = isset($societyOhterIncomeData['general_bank_name']) ? $societyOhterIncomeData['general_bank_name'] : '';

                            $postData['title']                       = isset($societyOhterIncomeData['title']) ? $societyOhterIncomeData['title'] : '';

                            $postData['ledger_head_id']              = isset($societyOhterIncomeData['ledger_head_id']) ? $societyOhterIncomeData['ledger_head_id'] : 0;

                            $postData['payment_date']                = isset($societyOhterIncomeData['payment_date']) ? $societyOhterIncomeData['payment_date'] : '';

                            $postData['entry_date']                  = $this->Util->getDateTime();

                            $postData['cdate']                       = $this->Util->getDateTime();

                            $postData['udate']                       = $this->Util->getDateTime();
                            
                            $postData['financial_year_id'] = $financialYearId;     
                            
                            $otherIncomerData[]['SocietyOtherIncome'] = $postData;
                        }

                    }

                }
            }
            
            $message = "";
                        
            if($wrongDataEntrycount > 0) {
                $message = " and Some Entries Not Saved Due to year Issue";
            }
            
            if(!empty($otherIncomerData)){
                if ($this->SocietyOtherIncome->saveAll($otherIncomerData)) {
                   //  $this->SocietyBill->updateLedgerOpeningClosingBalance($societyLoginID);
                    $this->Session->setFlash(__('The general receipts has been added '.$message), 'success');
    
                } else {
                    $this->Session->setFlash(__('The general receipts could not be Saved.'.$message.' Please, try again.'), 'error');
                }
            }

            return $this->redirect(array('action' => 'general_receipt'));

        }



        $otherIncomePostData = array();

        if ($this->request->is(array('get')) && $societyPaymentId) {

            $otherIncomePostData = $this->SocietyOtherIncome->find('all', array('conditions' => array('SocietyOtherIncome.id' => $societyPaymentId, 'SocietyOtherIncome.society_id' => $societyLoginID), 'recursive' => -1));

        }



        //print_r($otherIncomePostData);die;

        $this->set(compact('otherIncomePostData'));

    }





    public function delete_general_receipts($paymentId = null) {

        $this->loadModel('SocietyOtherIncome');

        if (!$this->SocietyOtherIncome->exists($paymentId)) {

            throw new NotFoundException(__('Requested payment is not found.'));

        }

        $this->request->allowMethod('post', 'delete');

        $this->SocietyOtherIncome->id = $paymentId;

        if ($this->SocietyOtherIncome->delete($paymentId)) {

            $this->Session->setFlash(__('The payment has been deleted.'), 'error');

        } else {

            $this->Session->setFlash(__('The payment could not be deleted. Please, try again.'), 'error');

        }

        return $this->redirect(array('action' => 'general_receipt'));

    }



    public function change_password() {

        $this->layout = 'society_dashboard';



        $Email = new CakeEmail();

        //$Email->config('smtp');

        $Email->addTo('machhindra.mbtb@gmail.com');

        $Email->viewVars(array('username' => $this->Auth->user('username')));

        /* $Email->template('change_password', 'fancy')

          ->emailFormat('html')

          ->from('ankush.mbtb@gmail.com')

          ->subject('New Project Added By Manager.')

          ->send(); */

        if ($this->request->is(array('post'))) {

            $conditions = array(

                'User.id' => $this->Auth->user('id'),

                'User.password' => $this->Auth->password($this->data['User']['old_password'])

            );

            if ($this->User->hasAny($conditions)) {

                $password = $this->Auth->password($this->data['User']['old_password']);

                $new_pwd = $this->Auth->password($this->data['User']['new_password']);

                $c_pwd = $this->Auth->password($this->data['User']['confirm_password']);

                //if (preg_match($regex, $this->data['User']['new_password'])) {

                if ($password == $new_pwd) {

                    $this->Session->setFlash(__('New Password cannot be same as Old Password.'), 'error');

                } else if ($new_pwd == $c_pwd) {

                    $this->User->id = $this->Auth->user('id');

                    if ($this->User->saveField('password', $this->data['User']['new_password'])) {

                        

                    }

                    $this->Session->setFlash(__('Password updated successfully. Please re-login with the new password.'));

                    return $this->redirect(array('action' => 'logout'));

                } else {

                    $this->Session->setFlash(__('Confirm Password should match New Password. '), 'error');

                }

            } else {

                $this->Session->setFlash(__('Old password is incorrect.'), 'error');

            }

        }

    }
    
    public function upload_member_list(){
        
    }


}

