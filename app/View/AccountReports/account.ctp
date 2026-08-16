<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <?php echo $this->Session->flash(); ?>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="button-list mt-25">
                            <?php 
                            $access = array();
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $access = explode(',',$this->Session->read('Auth.member.report_access'));
                            } 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                if(!in_array(3,$access)){
                                    $show = false;
                                }
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Bank Book" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_bank_book')); ?>"><span class="btn-text">Bank Book</span></a>
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Bank Reconciliation" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_bank_reconciliation')); ?>"><span class="btn-text">Bank Reconciliation</span></a>
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                if(!in_array(4,$access)){
                                    $show = false;
                                }
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Cash Book" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_cash_book')); ?>"><span class="btn-text">Cash Book</span></a>
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                if(!in_array(6,$access)){
                                    $show = false;
                                }
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Dues From Members" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_dues_from_member')); ?>"><span class="btn-text">Dues From Members</span></a>
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                if(!in_array(7,$access)){
                                    $show = false;
                                }
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Advanced Received From Members" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_dues_advance_from_member')); ?>"><span class="btn-text">Dues-Advanced From Members</span></a>
                            <!--<a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php //echo Router::url(array('controller' =>'account_reports','action' =>'account_member_ledger')); ?>"><span class="btn-text">Member Ledger</span></a>-->
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="General Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_general_ledger')); ?>"><span class="btn-text">General Ledger</span></a>
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                if(!in_array(2,$access)){
                                    $show = false;
                                }
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Income & Expenditure Statement" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_income_exp_statement')); ?>"><span class="btn-text">Income & Expenditure Statement</span></a>                          
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                if(!in_array(1,$access)){
                                    $show = false;
                                }
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Income & Expenditure Statement" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_income_exp_statement_details')); ?>"><span class="btn-text">Income & Expenditure Statement Details</span></a>                          
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                if(!in_array(1,$access)){
                                    $show = false;
                                }
                            }
                            ?>                            
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Balance Sheet" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_balance_sheet')); ?>"><span class="btn-text">Balance Sheet</span></a>  
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Petty Cash Register" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_petty_cash')); ?>"><span class="btn-text">Petty Cash Register</span></a>                          
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Payment Register" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_payment_register')); ?>"><span class="btn-text">Payment Register</span></a>                          
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Monthly Contribution" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_member_monthly_contribution')); ?>"><span class="btn-text">Member Monthly Contribution</span></a> 
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                if(!in_array(5,$access)){
                                    $show = false;
                                }
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_member_ledger_default')); ?>"><span class="btn-text">Member Ledger Default</span></a>                         
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'bill_half_page')); ?>"><span class="btn-text">Bill Half Page</span></a>
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'bill_full_page')); ?>"><span class="btn-text">Bill Full Page</span></a>      
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'bill_tax_invoice_gst')); ?>"><span class="btn-text">Bill Tax Invoice(GST)</span></a>                                                   <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_bank_slip')); ?>"><span class="btn-text">Bank Slip</span></a> 
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_journal_voucher_register')); ?>"><span class="btn-text">Journal Voucher Register</span></a>                                    <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_member_list')); ?>"><span class="btn-text">Member List</span></a>                         
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_collection_sheet')); ?>"><span class="btn-text">Collection Sheet</span></a>                          
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_trial_balance')); ?>"><span class="btn-text">Trial Balance</span></a>  
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_trial_balance_difference')); ?>"><span class="btn-text">Trial Balance Difference</span></a> 
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_bill_register')); ?>"><span class="btn-text">Bill Register</span></a>
                            <?php } ?>
                            <?php
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Chart" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'member_chart')); ?>"><span class="btn-text">Member Chart</span></a>
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_gst_register')); ?>"><span class="btn-text">GST Register</span></a> 
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'bill_with_interest_gst')); ?>"><span class="btn-text">Bill With Interest & GST</span></a> 
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'member_collection_register')); ?>"><span class="btn-text">Member Collection Register</span></a>
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'account_receipt_payment')); ?>"><span class="btn-text">Receipt & Payment</span></a> 
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'bill_opening_balance')); ?>"><span class="btn-text">Opening Balance</span></a>
                            <?php } ?>
                            <?php 
                            $show = true;
                            if ($this->Session->read('Auth.User.role') == 'Member'){ 
                                $show = false;
                            }
                            ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'comparison_2018closing_2019opening')); ?>"><span class="btn-text">Closing2018 vs Opening2019</span></a>
                            <?php } ?>
                            <?php if ($show){ ?>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Ledger" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'account_reports','action' =>'bill_summary_with_prev_data')); ?>"><span class="btn-text">Bill Summary With PrevData</span></a>
                            <?php } ?>                            
                                                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
