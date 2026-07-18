<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Payment Register</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="memberMonthlyControFrm" name="memberMonthlyControFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-12 member-tariff-left-box"> 
                                    <div class="row">                                            
                                        <div class="col-md-2 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <div class="account-report-form-box-label">From &nbsp;</div>  
                                                    <input type="date" class="account-report-form-box-field-date-75" id=""  name="data[SocietyPayment][payment_date]" value="<?php echo isset($postData['SocietyPayment']['payment_date']) ? $postData['SocietyPayment']['payment_date'] :'' ; ?>">
                                                </div> 
                                            </div>
                                        </div>                                           
                                        <div class="col-md-2 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <div class="account-report-form-box-label">To &nbsp;</div>  
                                                    <input type="date" class="account-report-form-box-field-date-75" id="" name="data[SocietyPayment][payment_date_to]" value="<?php echo isset($postData['SocietyPayment']['payment_date_to']) ? $postData['SocietyPayment']['payment_date_to'] :'' ; ?>">
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="col-md-2 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Type &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-65" id="" name="data[SocietyPayment][payment_type]" onchange="hidePaymentRegisterInputFields(this.value);" required="">
                                                    <option value="">Select</option>  
                                                    <option value="Bank" <?php if(isset($postData['SocietyPayment']['payment_type']) && $postData['SocietyPayment']['payment_type']=='Bank'){echo 'selected';} ?>>Bank</option>  
                                                    <option value="Cash" <?php if(isset($postData['SocietyPayment']['payment_type']) && $postData['SocietyPayment']['payment_type']=='Cash'){echo 'selected';} ?>>Cash</option>  
                                                    <option value="Both" <?php if(isset($postData['SocietyPayment']['payment_type']) && $postData['SocietyPayment']['payment_type']=='Both'){echo 'selected';} ?>>Both</option>  
                                                </select> 
                                            </div> 
                                        </div>   
                                        <div class="col-md-3 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Transaction Sub-Type &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-40" id="" name="data[SocietyPayment][transaction_type]" >
                                                    <option value="">Select</option> 
                                                    <option value="Bank" <?php if(isset($postData['SocietyPayment']['transaction_type']) && $postData['SocietyPayment']['transaction_type']=='Bank'){echo 'selected';} ?>>Bank</option>  
                                                    <option value="Cash" <?php if(isset($postData['SocietyPayment']['transaction_type']) && $postData['SocietyPayment']['transaction_type']=='Cash'){echo 'selected';} ?>>Cash</option>  
                                                </select> 
                                            </div> 
                                        </div>                                                             
                                    </div>  
                                    <div class="row">                                        
                                        <div class="col-md-12">                                
                                            <div class="account-report-form-field-box">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-sm btn-anim"><i class="icon-rocket"></i><span class="btn-text">Show</span></button>  
                                            <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_account_payment_register');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                            <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>                                                                              
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-sm btn-anim" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="clearfix"></div>
                                </div> 
                            </form> 
                            <div class="clearfix"></div>
                            <br> 
                            <div id="print_account_payment_register">
                            <div class="print-account-payment-register">
                            <div class="row">
                                <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                <div class="account-payment-register-heading">Payment Register</div>
                            </div>
                            <table id="" class="table table-bordered padding-td-none padding-th-none">
                                <thead>
                                    <tr>
                                        <th>VNo</th>
                                        <th>VDate</th>
                                        <th class="so-check" id="payment_by_ledger_id">By</th>
                                        <th class="so-check" id="cheque_reference_number">Cheque No</th>
                                        <th class="so-check" id="cheque_date">Cheque Date</th>
                                        <th>Amount</th>
                                        <th>Account Name</th>
                                        <th>Rs.</th>
                                        <th>Particular</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php //TODO
                                        if(isset($paymentRegisterData) && count($paymentRegisterData) > 0){
                                        $idCount = 1;
                                        $paymentTotalAmount = 0;
                                        foreach($paymentRegisterData as $paymentData){
                                            $paymentTotalAmount += isset($paymentData['SocietyPayment']['amount']) && !empty($paymentData['SocietyPayment']['amount']) ? $paymentData['SocietyPayment']['amount'] * 1 : 0.00;
                                    ?>
                                    <tr id="">
                                        <td><?php echo isset($paymentData['SocietyPayment']['bill_voucher_number']) ? $paymentData['SocietyPayment']['bill_voucher_number']: '';  ?></td>
                                        <td><?php echo isset($paymentData['SocietyPayment']['payment_date']) ? $utilObj->mysqlToDate($paymentData['SocietyPayment']['payment_date'],'/'): '';  ?></td>
                                        <td><?php echo isset($societyBankLists[$paymentData['SocietyPayment']['payment_by_ledger_id']]) ? $societyBankLists[$paymentData['SocietyPayment']['payment_by_ledger_id']]: '';  ?></td>
                                        <td><?php echo isset($paymentData['SocietyPayment']['cheque_reference_number']) ? $paymentData['SocietyPayment']['cheque_reference_number']: '';  ?></td>
                                        <td><?php echo isset($paymentData['SocietyPayment']['cheque_date']) ? $utilObj->mysqlToDate($paymentData['SocietyPayment']['cheque_date'],'/'): '';  ?></td>
                                        <td class="text-right"><?php echo isset($paymentData['SocietyPayment']['amount']) ? $paymentData['SocietyPayment']['amount']: '';  ?></td>
                                        <td><?php echo isset($paymentData['SocietyLedgerHeads']['title']) ? $paymentData['SocietyLedgerHeads']['title']: '';  ?></td>
                                        <td class="text-right"><?php echo isset($paymentData['SocietyPayment']['amount']) ? $paymentData['SocietyPayment']['amount']: '';  ?></td>
                                        <td><?php echo isset($paymentData['SocietyPayment']['particulars']) ? $paymentData['SocietyPayment']['particulars']: '';  ?></td>
                                        <td><?php echo isset($paymentData['SocietyPayment']['notes']) ? $paymentData['SocietyPayment']['notes']: '';  ?></td>
                                    </tr>
                                    <?php $idCount++;}?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">Rs.<?php echo round($paymentTotalAmount,2);  ?></td>
                                        <td colspan="7"><?php echo $utilObj->getAmountInRupees($paymentTotalAmount); ?></td>
                                    </tr>
                                </tfoot>
                              <?php } ?>
                            </table> 
                            </div> 
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>