<?php



App::uses('Component', 'Controller');



class MenuComponent extends Component {



    public $components = array('Session');

    public function initialize(Controller $controller) {

        $this->controller = $controller;

    }



    function menuItems($accessLevel = null, $url = null,$societyData) {

        $menuArray = null;



        if ($accessLevel == 'Admin') {

            $menuArray = array('menu' => array(

                    'Dashboard' => array(

                        'link' => Router::url(array('controller' => 'admin', 'action' => 'dashboard'), true),

                        'icon' => '<i class="zmdi zmdi-landscape mr-20"></i>'

                    ),

                    'Manage Societies' => array('icon' => '<i class="zmdi zmdi-edit mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'admin', 'action' => 'dashboard'), true),

                        'submenu' => array(

                            Router::url(array('controller' => 'admin', 'action' => 'add_societys'), true) => 'Add Society',

                            Router::url(array('controller' => 'admin', 'action' => 'assign_societies'), true) => 'Reseller Societies',

                            Router::url(array('controller' => 'admin', 'action' => 'view_societys'), true) => 'Societies List',

                        ),

                    ),

                    'Society Parameters' => array(

                        'link' => Router::url(array('controller' => 'admin', 'action' => 'society_parameter'), true),

                        'icon' => '<i class="zmdi zmdi-google-pages mr-20"></i></i>',

                    //'submenu' => array(

                    // Router::url(array('controller' => 'admin', 'action' => 'society_parameter'), true) => 'Bill Frequency',

                    // Router::url(array('controller' => 'Requests', 'action' => 'index'), true) => 'Interest Types',

                    // Router::url(array('controller' => 'Requests', 'action' => 'project_tracker'), true) => 'Tariff Types',

                    //),

                    //'link' => Router::url(array('controller' => 'Batches', 'action' => 'index'), true)

                    )

                /* 'Ledger Management' => array(

                  'icon' => '<i class="zmdi zmdi-book mr-20"></i>',

                  'submenu' => array(

                  Router::url(array('controller' => 'Users', 'action' => 'index'), true) => 'Ledger Categories Management',

                  Router::url(array('controller' => 'Requests', 'action' => 'index'), true) => 'Ledger Groups Management',

                  Router::url(array('controller' => 'Requests', 'action' => 'index'), true) => 'Ledger Sub Groups Management',

                  ),

                  //'link' => Router::url(array('controller' => 'Batches', 'action' => 'index'), true)

                  ),

                  'Reports' => array(

                  'link' => Router::url(array('controller' => 'Requests', 'action' => 'report'), true),

                  'icon' => '<i class="zmdi zmdi-chart-donut mr-20"></i>'

                  ), */

                )

            );

        } else if ($accessLevel == 'Reseller') {

            $menuArray = array('menu' => array(

                    'Dashboard' => array(

                        'link' => Router::url(array('controller' => 'resellers', 'action' => 'dashboard'), true),

                        'icon' => '<i class="zmdi zmdi-landscape mr-20"></i>'

                    ),

                    'Personal Identity' => array(

                        'link' => Router::url(array('controller' => 'resellers', 'action' => 'update_profiles'), true),

                        'icon' => '<i class="zmdi zmdi-edit mr-20"></i>',

                    //'submenu' => array(

                    // Router::url(array('controller' => 'societys', 'action' => 'update_assign_society'), true) => 'Update Society',

                    //Router::url(array('controller' => 'society', 'action' => 'add_update_societies'), true) => 'Add/Edit/Delete Societies',

                    //Router::url(array('controller' => 'societys', 'action' => 'view_societys',AuthComponent::user('id')), true) => 'View Society',

                    //)

                    ),
                    'Manage Users' => array(
                        'icon' => '<i class="zmdi zmdi-accounts mr-20"></i>',
                        'submenu' => array(
                            Router::url(array('controller' => 'resellers', 'action' => 'manage_users'), true) => 'User List',
                            Router::url(array('controller' => 'resellers', 'action' => 'add_user'), true) => 'Create User',
                            Router::url(array('controller' => 'resellers', 'action' => 'permissions'), true) => 'Permissions',
                        )
                    ),

                    'Audit reports' => array(

                        'link' => Router::url(array('controller' => 'resellers', 'action' => 'audit_report'), true),

                        'icon' => '<i class="zmdi zmdi-edit mr-20"></i>',
                    ),


                    'My Societies' => array(

                        'link' => Router::url(array('controller' => 'resellers', 'action' => 'assigned_societys'), true),

                        'icon' => '<i class="zmdi zmdi-google-pages mr-20"></i>'

                    )
                    ,

                    'Create Society' => array(

                        'link' => Router::url(array('controller' => 'resellers', 'action' => 'create_society'), true),

                        'icon' => '<i class="zmdi zmdi-google-pages mr-20"></i>'

                    )
                    ,
                    'Society Finance Year Mapping' => array(

                        'link' => Router::url(array('controller' => 'resellers', 'action' => 'society_finance_year_mapping'), true),

                        'icon' => '<i class="zmdi zmdi-google-pages mr-20"></i>'

                    )                    

            ));

        } else if ($accessLevel == 'Society') {
            if(isset($societyData['society']['enable_sms']) && $societyData['society']['enable_sms'] == 'Y'){
                  $menuArray = array('menu' => array(
                    'Dashboard' => array(
                        'link' => Router::url(array('controller' => 'societys', 'action' => 'dashboard'), true),
                        'icon' => '<i class="zmdi zmdi-landscape mr-20"></i>'
                    ),
                    'Society' => array('icon' => '<i class="zmdi zmdi-city-alt mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'society', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'societys', 'action' => 'update_societys', AuthComponent::user('id')), true) => 'Society Identity',
                            Router::url(array('controller' => 'societys', 'action' => 'society_parameter'), true) => 'Society Parameters',
                            Router::url(array('controller' => 'societys', 'action' => 'tarrif_defination'), true) => 'Tariff Defination',                            
                            Router::url(array('controller' => 'societys', 'action' => 'society_head_sub_categories'), true) => 'Society Head Sub Group',
                            Router::url(array('controller' => 'societys', 'action' => 'society_ledger_heads'), true) => 'Society Ledger Heads ',
                            Router::url(array('controller' => 'societys_members', 'action' => 'society_tariffs'), true) => 'Society Tariffs',
                            Router::url(array('controller' => 'societys', 'action' => 'society_tariff_orders'), true) => 'Society Tariff Order',
                            Router::url(array('controller' => 'societys', 'action' => 'society_payments'), true) => 'Society Payments',
                            Router::url(array('controller' => 'societys', 'action' => 'society_payments_from_xls'), true) => 'Import Society Payments',
                            Router::url(array('controller' => 'societys', 'action' => 'cheque_clear_date'), true) => 'Bank Reconciliation',
                            Router::url(array('controller' => 'societys', 'action' => 'cash_withdraws'), true) => 'Society Cash Contra',
                            Router::url(array('controller' => 'societys', 'action' => 'general_receipt'), true) => 'General Receipt'
                        )
                    ),
                    'Member' => array('icon' => '<i class="zmdi zmdi-account mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'society', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'societys', 'action' => 'building_identitys'), true) => 'Building Identity',
                            Router::url(array('controller' => 'societys', 'action' => 'wing_identitys'), true) => 'Wing Identity',
                            Router::url(array('controller' => 'societys', 'action' => 'member_identitys'), true) => 'Member Identity',
                            Router::url(array('controller' => 'societys_members', 'action' => 'member_tariffs'), true) => 'Member Tariff',
                            Router::url(array('controller' => 'societys', 'action' => 'tenant_member_identitys'), true) => 'Tenant Member Identity',
                            Router::url(array('controller' => 'societys_members', 'action' => 'member_payments'), true) => 'Member Payments',
			                Router::url(array('controller' => 'societys_members', 'action' => 'add_member_payment_from_xls'), true) => 'Import Member Payments',
                            Router::url(array('controller' => 'societys', 'action' => 'add_society_payment_from_xls'), true) => 'Import Society Payments',
                            Router::url(array('controller' => 'society_bills', 'action' => 'society_generated_bills'), true) => 'All Generated Bills',
                            Router::url(array('controller' => 'societys_members', 'action' => 'member_logins'), true) => 'Create Member Logins',
                            Router::url(array('controller' => 'societys', 'action' => 'update_opening_balance'), true) => 'Update Opening Balance'
                           // Router::url(array('controller' => 'societys_members', 'action' => 'SEND SMS'), true) => 'Reprt send SMS'
                        )
                    ),
                    'Registers' => array('icon' => '<i class="zmdi zmdi-library mr-20"></i>', 'sublink' => Router::url(array('controller' => 'registers', 'action' => 'index'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'registers', 'action' => 'fd_register'), true) => 'FD Register',
                            Router::url(array('controller' => 'registers', 'action' => 'shares_register'), true) => 'Shares Register',
                            Router::url(array('controller' => 'registers', 'action' => 'lien_register'), true) => 'Lien Register',
                            Router::url(array('controller' => 'registers', 'action' => 'nominee_register'), true) => 'Nominee Register',
                            Router::url(array('controller' => 'registers', 'action' => 'form_i'), true) => 'Form I',
                            Router::url(array('controller' => 'registers', 'action' => 'form_j'), true) => 'Form J',
                        )
                    ),
                    'Employee' => array('icon' => '<i class="zmdi zmdi-accounts-alt mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'society', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'societys_employees', 'action' => 'employee_categories'), true) => 'Employee Category',
                            Router::url(array('controller' => 'societys_employees', 'action' => 'employee_sub_categories'), true) => 'Employee Sub Category',
                            Router::url(array('controller' => 'societys_employees', 'action' => 'employee_details'), true) => 'Employee Details',

                        )
                    ),

                    'Send Sms' => array('icon' => '<i class="zmdi zmdi-envelope mr-20" ></i></i>',  'sublink' => Router::url(array('controller' => 'society', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'society_sms', 'action' => 'account_dues_member'),  true) => 'account dues from member',
                            Router::url(array('controller' => 'society_sms', 'action' => 'Greetings'), true) => 'Greetings',
                            Router::url(array('controller' => 'society_sms', 'action' => 'member_tariffs'), true) => 'Member Tariff',
                            Router::url(array('controller' => 'society_sms', 'action' => 'receipt_acknowledgment'), true) => 'Receipt Acknowledgment',
                            Router::url(array('controller' => 'society_sms', 'action' => 'generated_bills'), true) => 'All Generated Bills',
                           // Router::url(array('controller' => 'societys_members', 'action' => 'SEND SMS'), true) => 'Reprt send SMS'
                        )
                    ),
                    'Reports' => array('icon' => '<i class="zmdi zmdi-account mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'societys', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'account_reports', 'action' => 'account'), true) => 'Accounts',
                            Router::url(array('controller' => 'reports', 'action' => 'society'), true) => 'Society',
                            Router::url(array('controller' => 'reports', 'action' => 'journal_vouchers'), true) => 'Journal Voucher',
                            Router::url(array('controller' => 'account_reports', 'action' => 'closing_balances'), true) => 'Closing Balances',
                            Router::url(array('controller' => 'account_reports', 'action' => 'ledger_closing_from_trial_balance'), true) => 'Ledger - closing vs trial balance'
                        )
                    ),
                    'Utilities' => array('icon' => '<i class="zmdi zmdi-account mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'societys', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'utility', 'action' => 'update_bill_date'), true) => 'update bill dates',
                        )
                    ),

                    'Bill Print' => array('icon' => '<i class="zmdi zmdi-account mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'societys', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'reports', 'action' => 'bill_with_receipt_tabular'), true) => 'Bill With Receipt Tabular',
                            Router::url(array('controller' => 'account_reports', 'action' => 'bill_tax_invoice_gst'), true) => 'Bill Tax Invoice Gst',
                            Router::url(array('controller' => 'account_reports', 'action' => 'bill_full_page'), true) => 'Bill Full Page',
                            Router::url(array('controller' => 'account_reports', 'action' => 'bill_half_page'), true) => 'Bill Half Page',
                            Router::url(array('controller' => 'account_reports', 'action' => 'bill_with_interest_gst'), true) => 'Bill With Interest Gst',
                            Router::url(array('controller' => 'account_reports', 'action' => 'bill_summary_with_prev_data'), true) => 'Bill Summary With Prev Data',
                            Router::url(array('controller' => 'reports', 'action' => 'bill_with_receipt'), true) => 'Bill Receipt'

                        )
                    )
            ));
                
            }
            else {
                  $menuArray = array('menu' => array(
                    'Dashboard' => array(
                        'link' => Router::url(array('controller' => 'societys', 'action' => 'dashboard'), true),
                        'icon' => '<i class="zmdi zmdi-landscape mr-20"></i>'
                    ),
                    'Society' => array('icon' => '<i class="zmdi zmdi-city-alt mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'society', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'societys', 'action' => 'update_societys', AuthComponent::user('id')), true) => 'Society Identity',
                            Router::url(array('controller' => 'societys', 'action' => 'society_parameter'), true) => 'Society Parameters',
                            Router::url(array('controller' => 'societys', 'action' => 'tarrif_defination'), true) => 'Tariff Defination',                            
                            Router::url(array('controller' => 'societys', 'action' => 'society_head_sub_categories'), true) => 'Society Head Sub Group',
                            Router::url(array('controller' => 'societys', 'action' => 'society_ledger_heads'), true) => 'Society Ledger Heads ',
                            Router::url(array('controller' => 'societys_members', 'action' => 'society_tariffs'), true) => 'Society Tariffs',
                            Router::url(array('controller' => 'societys', 'action' => 'society_tariff_orders'), true) => 'Society Tariff Order',
                            Router::url(array('controller' => 'societys', 'action' => 'society_payments'), true) => 'Society Payments',
                            Router::url(array('controller' => 'societys', 'action' => 'add_society_payment_from_xls'), true) => 'Import Society Payments',
                            Router::url(array('controller' => 'societys', 'action' => 'cheque_clear_date'), true) => 'Bank Reconciliation',
                            Router::url(array('controller' => 'societys', 'action' => 'cash_withdraws'), true) => 'Society Cash Contra',
                            Router::url(array('controller' => 'societys', 'action' => 'general_receipt'), true) => 'General Receipt'
                        )
                    ),
                    'Member' => array('icon' => '<i class="zmdi zmdi-account mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'society', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'societys', 'action' => 'building_identitys'), true) => 'Building Identity',
                            Router::url(array('controller' => 'societys', 'action' => 'wing_identitys'), true) => 'Wing Identity',
                            Router::url(array('controller' => 'societys', 'action' => 'member_identitys'), true) => 'Member Identity',
                            Router::url(array('controller' => 'societys_members', 'action' => 'member_tariffs'), true) => 'Member Tariff',
                            Router::url(array('controller' => 'societys', 'action' => 'tenant_member_identitys'), true) => 'Tenant Member Identity',
                            Router::url(array('controller' => 'societys_members', 'action' => 'member_payments'), true) => 'Member Payments',
			                Router::url(array('controller' => 'societys_members', 'action' => 'add_member_payment_from_xls'), true) => 'Import Member Payments',
                            Router::url(array('controller' => 'societys', 'action' => 'add_society_payment_from_xls'), true) => 'Import Society Payments',
                            Router::url(array('controller' => 'society_bills', 'action' => 'society_generated_bills'), true) => 'All Generated Bills',
                            Router::url(array('controller' => 'societys_members', 'action' => 'member_logins'), true) => 'Create Member Logins',
                           Router::url(array('controller' => 'societys', 'action' => 'update_opening_balance'), true) => 'Update Opening Balance'
                           // Router::url(array('controller' => 'societys_members', 'action' => 'SEND SMS'), true) => 'Reprt send SMS'
                        )
                    ),
                    'Registers' => array('icon' => '<i class="zmdi zmdi-library mr-20"></i>', 'sublink' => Router::url(array('controller' => 'registers', 'action' => 'index'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'registers', 'action' => 'fd_register'), true) => 'FD Register',
                            Router::url(array('controller' => 'registers', 'action' => 'shares_register'), true) => 'Shares Register',
                            Router::url(array('controller' => 'registers', 'action' => 'lien_register'), true) => 'Lien Register',
                            Router::url(array('controller' => 'registers', 'action' => 'nominee_register'), true) => 'Nominee Register',
                            Router::url(array('controller' => 'registers', 'action' => 'form_i'), true) => 'Form I',
                            Router::url(array('controller' => 'registers', 'action' => 'form_j'), true) => 'Form J',
                        )
                    ),
                    'Employee' => array('icon' => '<i class="zmdi zmdi-accounts-alt mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'society', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'societys_employees', 'action' => 'employee_categories'), true) => 'Employee Category',
                            Router::url(array('controller' => 'societys_employees', 'action' => 'employee_sub_categories'), true) => 'Employee Sub Category',
                            Router::url(array('controller' => 'societys_employees', 'action' => 'employee_details'), true) => 'Employee Details',

                        )
                    ),

                    'Reports' => array('icon' => '<i class="zmdi zmdi-account mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'societys', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'account_reports', 'action' => 'account'), true) => 'Accounts',
                            Router::url(array('controller' => 'reports', 'action' => 'society'), true) => 'Society',
                            Router::url(array('controller' => 'reports', 'action' => 'journal_vouchers'), true) => 'Journal Voucher',
                            Router::url(array('controller' => 'account_reports', 'action' => 'closing_balances'), true) => 'Closing Balances'
                        )
                    ),
                    'Utilities' => array('icon' => '<i class="zmdi zmdi-account mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'societys', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'utility', 'action' => 'update_bill_date'), true) => 'update bill dates',
                        )
                    ),

                    'Bill Print' => array('icon' => '<i class="zmdi zmdi-account mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'societys', 'action' => 'dashboard'), true),
                        'submenu' => array(
                            Router::url(array('controller' => 'reports', 'action' => 'bill_with_receipt_tabular'), true) => 'Bill With Receipt Tabular',
                            Router::url(array('controller' => 'account_reports', 'action' => 'bill_tax_invoice_gst'), true) => 'Bill Tax Invoice Gst',
                            Router::url(array('controller' => 'account_reports', 'action' => 'bill_full_page'), true) => 'Bill Full Page',
                            Router::url(array('controller' => 'account_reports', 'action' => 'bill_half_page'), true) => 'Bill Half Page',
                            Router::url(array('controller' => 'account_reports', 'action' => 'bill_with_interest_gst'), true) => 'Bill With Interest Gst',
                            Router::url(array('controller' => 'account_reports', 'action' => 'bill_summary_with_prev_data'), true) => 'Bill Summary With Prev Data',
                            Router::url(array('controller' => 'reports', 'action' => 'bill_with_receipt'), true) => 'Bill Receipt'

                        )
                    ),
                    /*'PrintBill' => array(
                        'link' => Router::url(array('controller' => 'societybills', 'action' => 'print_member_bills'), true),
                        'icon' => '<i class="fa fa-print mr-20"></i> '
                    )*/
            ));
            }
            
        }

        else if ($accessLevel == 'Member') {

            $memberData=$this->Session->read('Auth.member');

           

            $flag=0;

            if (!is_null($memberData['report_access'])) {

                $access = explode(',',$memberData['report_access']);

                if (count($access)){

                    $flag=1;

                     $menuArray = array('menu' => array(

                            'Dashboard' => array(

                                'link' => Router::url(array('controller' => 'societys_members', 'action' => 'dashboard'), true),

                                'icon' => '<i class="zmdi zmdi-landscape mr-20"></i>'

                            ),

                            'Reports' => array('icon' => '<i class="zmdi zmdi-account mr-20"></i></i>', 'sublink' => Router::url(array('controller' => 'societys', 'action' => 'dashboard'), true),

                                'submenu' => array(

                                    Router::url(array('controller' => 'account_reports', 'action' => 'account'), true) => 'Accounts')

                                )

                    ));

                }

            }

            if ($flag == 0){

                $menuArray = array('menu' => array(

                    'Dashboard' => array(

                        'link' => Router::url(array('controller' => 'societys_members', 'action' => 'dashboard'), true),

                        'icon' => '<i class="zmdi zmdi-landscape mr-20"></i>'

                    )

                ));

            }

        }

        return $menuArray;

    }

}



?>

