<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Bank Reconciliation</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <div class="col-md-12 left-section"> 
                                <form class="" method="post" id="bankReconciliationFrm" name="bankReconciliationFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                    <div class="row">
                                        <div class="col-md-3 no-padding-right">  
                                            <div class="account-report-form-field-box">  
                                                <div class="account-report-form-box-label">Report Type &nbsp;</div>                                                                     
                                                <select class="account-report-form-box-field-dropdown" id="" name="data[SocietyPayment][report_type]">
                                                    <option value="">select</option>
                                                    <option value="Detail" <?php if (isset($postData['SocietyPayment']['report_type'])&& $postData['SocietyPayment']['report_type']=='Detail'){echo 'selected';} ?>>Detail</option>
                                                    <option value="Summary"<?php if (isset($postData['SocietyPayment']['report_type']) && $postData['SocietyPayment']['report_type']=='Summary'){echo 'selected';}?>>Summary</option>
                                                </select>
                                            </div>
                                        </div>         
                                        <div class="col-md-3 no-padding-right">  
                                            <div class="account-report-form-field-box">  
                                                <div class="account-report-form-box-label">Bank Name &nbsp;</div>                                                                     
                                                <select class="account-report-form-box-field-dropdown" id="" name="data[MemberPayment][society_bank_id]" required="">
                                                   <option value="">Select Bank</option>
                                                            <?php
                                                            if (isset($societyBankLists) && count($societyBankLists) > 0) :
                                                            $s = 1;
                                                            foreach ($societyBankLists as $societyBankId => $societyBankName) {
                                                            if (isset($postData['MemberPayment']['society_bank_id']) && $postData['MemberPayment']['society_bank_id'] == $societyBankId) {
                                                            ?>
                                                                <option value="<?php echo $societyBankId; ?>" selected=""><?php echo $societyBankName; ?></option>
                                                                    <?php } else { ?>
                                                                <option value="<?php echo $societyBankId; ?>"><?php echo $societyBankName; ?></option>
                                                                <?php
                                                                $s++;
                                                                    }
                                                                } endif;
                                                            ?>  
                                                </select>
                                            </div>
                                        </div>  
                                         <div class="col-md-3 nopadding">   
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">For the Period &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[SocietyPayment][payment_date]" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To &nbsp;&nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[SocietyPayment][payment_date_to]" value="">
                                            </div>  
                                        </div> 
                                    </div>  
                                    <br>
                                    <div class="row">   
                                        <div class="col-md-4 account-report-form"> 
                                            <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                                                        
                                            <a type="button" data-toggle="tooltip" data-original-title=""  class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printAllReports('print_bank_reconciliation');"><i class="fa fa-print"></i><span class="btn-text"> Print Friendly</span></a>
                                            <a type="button" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text"> Export to PDF</span></a>   
                                            <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a> 
                                        </div>                                                                       
                                    </div>    
                                     <div class="row">                                                        
                                    </div>  
                                </form>  
                            </div> 
                           <!-- <div class="col-md-4 right-section">
                                <div class="row">   
                                    <div class="right-title">Select (if required) </div> 
                                    <div class="col-md-12">
                                        <div class="account-report-form-field-box">
                                            <div class="account-report-form-box-label">Building &nbsp;</div>                                    
                                             <select class="account-report-form-box-field-dropdown" id="" name="" required="">
                                                <option value="">Select Building</option>  
                                            </select> 
                                        </div>
                                    </div>  
                                    <div class="col-md-12">
                                        <div class="account-report-form-field-box">
                                            <div class="account-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                             <select class="account-report-form-box-field-dropdown" id="" name="" required="">
                                                <option value="">Select Wing</option>  
                                            </select> 
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div>
                                    <div class="col-md-6 ">
                                        <div class="account-report-form-field-box">
                                            <div class="account-report-form-box-label">Unit No &nbsp;&nbsp;</div>                                   
                                            <input type="text" class="account-report-form-box-field-50" id="" name="" value="">
                                        </div>
                                    </div>  
                                    <div class="col-md-6 ">
                                        <div class="account-report-form-field-box">
                                            <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                            <input type="text" class="account-report-form-box-field-50" id="" name="" value="">
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div><br>
                                </div>    
                            </div>    -->  
                            <div class="clearfix"></div>
                            <br><br><br>
                            
                            <div id="print_bank_reconciliation">
                                <div class="print-bank-reconciliation">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="report-bill">Bank Reconciliation</div>
                                    <div class="simple-border"></div>
                                        <br>
                                    <div class="col-md-2 col-xs-2"></div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="">
                                                <label class="col-md-6 col-xs-4 text-right font-weight-bold txt-black">Opening Balance</label>
                                                <div class="col-md-2 col-xs-2 text-right">236,995.77</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Deposits During the Period Cash</label>
                                                <div class="col-md-2 col-xs-2 text-right">236,995.77</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                            </div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Cheque</label>
                                                <div class="col-md-2 col-xs-2 text-right">236,995.77</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                            </div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Total</label>
                                                <div class="col-md-2 col-xs-2 text-right font-weight-bold txt-black" style="border-top:2px solid #000;border-bottom:2px solid #000;">236,995.77</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                            </div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Withdrawls During the Period Cash</label>
                                                <div class="col-md-2 col-xs-2 text-right">236,995.77</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                            </div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Cheque</label>
                                                <div class="col-md-2 col-xs-2 text-right">236,995.77</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                            </div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Total</label>
                                                <div class="col-md-2 col-xs-2 text-right font-weight-bold txt-black" style="border-top:2px solid #000;border-bottom:2px solid #000;">236,995.77</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                            </div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Balance As Per Bank Book</label>
                                                <div class="col-md-2 col-xs-2 text-right">236,995.77</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                            </div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Cheques Issued But Not Presented</label>
                                                <div class="col-md-2 col-xs-2 text-right">236,995.77</div>
                                                <div class="col-md-4 col-xs-2text-right"></div>
                                            </div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Cheques Deposited But Not Credited</label>
                                                <div class="col-md-2 col-xs-2 text-right">236,995.77</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                            </div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Simple Balance As Per Bank Statement</label>
                                                <div class="col-md-2 col-xs-2 text-right font-weight-bold txt-black" style="border-top:2px solid #000;border-bottom:2px solid #000;">236,995.77</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                            </div>
                                            <br>
                                            <div class="clearfix"></div><br>
                                            <div class="">
                                                <label class="col-md-12 col-xs-12 text-center font-weight-bold txt-black">(Rupees Fourteen Lac Twenty One Thousand Two Hundred Eleven And Forty Five Paise Only )</label>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="simple-border"></div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Passbook Amount</label>
                                                <div class="col-md-2 col-xs-2 text-right">0.00</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                            </div>
                                            <div class="">
                                                <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Difference Amount</label>
                                                <div class="col-md-2 col-xs-2 text-right">1421211.45</div>
                                                <div class="col-md-4 col-xs-2 text-right"></div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="simple-border"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- old design

<div class="row">
<div class="report-bill">Financial Year 2017-2018</div>
<div class="report-bill">Reconciliation Statement For RDCC Bank As on 15/02/2018</div>
<div class="report-bill-outer-section">
    <div class="table-wrap1">
        <table id="" class="table table-bordered padding-td-none padding-th-none" >
            <thead>
                <tr>
                    <th>Voucher No</th>
                    <th>Voucher Date</th>
                    <th>Cheque No</th>
                    <th>Particulars</th>
                    <th width="10%" class="text-center">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr id="">
                    <td colspan="4" class="text-center">Balance as per Bank Book Rs.</td>
                    <td class="text-right">161322.30 Dr</td>
                </tr>
                <tr id="">
                    <td colspan="4">Add: Cheque issued but not presented to bank<span style="display:block">15/02/2018</span></td>
                    <td class="text-right">161322.30</td>
                </tr>
                <tr id="">
                    <td colspan="4" class="text-center">Total</td>
                    <td class="text-right">161322.30</td>
                </tr>
                <tr id="">
                    <td colspan="4">Less: Cheque Deposited but not Credited by bank<span style="display:block">15/02/2018</span></td>
                    <td class="text-right">161322.30</td>
                </tr>
                <tr id="">
                    <td colspan="4" class="text-center">Total</td>
                    <td class="text-right">161322.30</td>
                </tr>
                <tr id="">
                    <td colspan="4" class="text-center">Balance as per Bank</td>
                    <td class="text-right">161322.30 Cr</td>
                </tr>
            </tbody>   
        </table> 
    </div>
</div>  
</div> 

-->