<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Payment Voucher</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="societyPaymentVoucherFrm" name="societyPaymentVoucherFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-12 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-3">    
                                            <div class="society-report-form-box-label">From Date &nbsp;</div>                                                                     
                                            <input type="date" class="society-report-form-box-field-date" id="" name="data[SocietyPayment][payment_date]" value="<?php echo isset($postData['SocietyPayment']['payment_date']) ? $postData['SocietyPayment']['payment_date'] :'' ; ?>">
                                        </div>
                                        <div class="col-md-3">    
                                            <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                                                     
                                            <input type="date" class="society-report-form-box-field-date" id="" name="data[SocietyPayment][payment_date_to]" value="<?php echo isset($postData['SocietyPayment']['payment_date']) ? $postData['SocietyPayment']['payment_date'] :'' ; ?>">
                                        </div>
                                        <div class="col-md-6">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>   
                                                <a type="button" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_society_payment_voucher');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                               
                                                <a type="button" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text"> Export to PDF</span></a>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btn-sm" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                            
                                            </div>
                                        </div>  
                                    </div> 
                                </div>   
                            </form>  
                            <div class="col-md-4">
                            </div>   
                            <!-- <div class="col-md-4 right-section">
                                 <div class="row">   
                                     <div class="right-title">Select (if required) </div> 
                                     <div class="col-md-12">
                                         <div class="society-report-form-field-box">
                                             <div class="society-report-form-box-label">Building &nbsp;</div>                                    
                                              <select class="society-report-form-box-field-dropdown" id="" name="" required="">
                                                 <option value="">Select Building</option>  
                                             </select> 
                                         </div>
                                     </div>  
                                     <div class="col-md-12">
                                         <div class="society-report-form-field-box">
                                             <div class="society-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                              <select class="society-report-form-box-field-dropdown" id="" name="" required="">
                                                 <option value="">Select Wing</option>  
                                             </select> 
                                         </div>
                                     </div> 
                                     <div class="clearfix"></div>
                                     <div class="col-md-6">
                                         <div class="society-report-form-field-box">
                                             <div class="society-report-form-box-label">Unit No &nbsp;&nbsp;</div>                                   
                                             <input type="text" class="society-report-form-box-field-50" id="" name="" value="">
                                         </div>
                                     </div>  
                                     <div class="col-md-6">
                                         <div class="society-report-form-field-box">
                                             <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                             <input type="text" class="society-report-form-box-field-50" id="" name="" value="">
                                         </div>
                                     </div> 
                                     <div class="clearfix"></div><br>
                                 </div>    
                             </div>    -->     

                            <div class="clearfix"></div>
                            <br><br><br>
                            <div id="print_society_payment_voucher">
                            <?php 
                            if(isset($paymentVoucherDetails) && !empty($paymentVoucherDetails)){
                            foreach($paymentVoucherDetails as $voucherReport) {
                            ?> 
                                <div class="print-society-payment-voucher">
                                <div class="payment_voucher_outer_border">
                                <div class="row">
                                    <h5 class="text-center"><?php echo isset($voucherReport['Society']['society_name']) ? $voucherReport['Society']['society_name'] : ''; ?></h5>
                                    <div class="report-address-heading">Registration No: <?php echo isset($voucherReport['Society']['registration_no']) ? $voucherReport['Society']['registration_no'] : ''; ?> Dated: <?php echo isset($voucherReport['Society']['registration_date']) ? $utilObj->getFormatDate($voucherReport['Society']['registration_date'],'d/m/Y') : ''; ?></div>
                                    <div class="report-address-heading"><?php echo isset($voucherReport['Society']['address']) ? $voucherReport['Society']['address'] : ''; ?></div>
                                </div>
                                <br>
                                
                                    <div class="report-bill"><?php echo isset($voucherReport['SocietyPayment']['payment_type']) ? $voucherReport['SocietyPayment']['payment_type'] : 'Bank'; ?> Payment Voucher</div>
                                    <div class="report-bill-outer-section1  payment-voucher-section">
                                        <div class="row">
                                            <div class="col-md-9 col-sm-9 col-xs-9">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="">Cheque No : <?php echo isset($voucherReport['SocietyPayment']['cheque_reference_number']) ? $voucherReport['SocietyPayment']['cheque_reference_number'] : ''; ?></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="">Paid to : <?php echo isset($voucherReport['SocietyLedgerHeads']['title']) ? $voucherReport['SocietyLedgerHeads']['title'] : ''; ?></div>
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="">No : <?php echo isset($voucherReport['SocietyPayment']['bill_voucher_number']) ? $voucherReport['SocietyPayment']['bill_voucher_number'] : ''; ?></div>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="">Date : <?php echo isset($voucherReport['SocietyPayment']['payment_date']) ? $utilObj->getFormatDate($voucherReport['SocietyPayment']['payment_date'],'d/m/Y') : ''; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row1">
                                            <div style="padding: 0px;">
                                                <div class="table-wrap">
                                                    <div class="">
                                                        <table id="datable_1" class="table padding-td-none padding-th-none table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Particular</th>
                                                                    <th style="width:10%" class="text-center">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody> 
                                                                <tr id="">
                                                                    <td><?php echo isset($voucherReport['SocietyPayment']['particulars']) ? $voucherReport['SocietyPayment']['particulars'] : ''; ?></td>
                                                                    <td colspan="1" rowspan="2" class="text-right"><?php echo isset($voucherReport['SocietyPayment']['amount']) ? $voucherReport['SocietyPayment']['amount'] : '0.00'; ?></td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td colspan="1">Remark: <?php echo isset($voucherReport['SocietyPayment']['notes']) ? $voucherReport['SocietyPayment']['notes'] : ''; ?></td>                                                                   
                                                                </tr>
                                                                <tr id="">
                                                                    <td>(<?php echo isset($voucherReport['SocietyPayment']['amount']) ? $utilObj->getAmountInRupees($voucherReport['SocietyPayment']['amount']) : ''; ?>)<span style="float:right;">Total &nbsp;&nbsp;&nbsp;</span></td>
                                                                    <td style="width:10%" class="text-right"><?php echo isset($voucherReport['SocietyPayment']['amount']) ? $voucherReport['SocietyPayment']['amount'] : ''; ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="col-md-12">
                                                Paid by : <?php if ($voucherReport['SocietyPayment']['payment_type']== "Bank"){echo isset($societyBankBalanceHeadsLists[$voucherReport['SocietyPayment']['payment_by_ledger_id']]) ? $societyBankBalanceHeadsLists[$voucherReport['SocietyPayment']['payment_by_ledger_id']] : ''; }else{echo 'Cash';} ?>
                                            </div> 
                                        </div> 
                                        <br><br><br>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                                Prepared By
                                            </div> 
                                            <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                               <?php echo isset($voucherReport['Society']['authorised_person']) ? $voucherReport['Society']['authorised_person'] : 'Authorised By'; ?>
                                            </div> 
                                            <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                                Receiver's Signature
                                            </div> 
                                        </div>
                                    </div>
                                </div>    
                                </div>    
                               
                            <?php  } }?>
                                <!--<div class="row1">
                                    <h5 class="text-center">AMBIKA SHRUSTI C.H.S. LTD</h5>
                                    <div class="report-address-heading">Registration No. N.B.O/CIDCO/HHG(OH)/2858/JTR/YEAR 2008-2009 Dated: 19/01/2009</div>
                                    <div class="report-address-heading">PLOT NO.92 SECTOR-44 KARVE SEAWOODS NERUL NAVI MUMBAI 4000706</div>
                                </div>
                                <br>
                                <div class="row1">
                                    <div class="report-bill">Cash Payment Voucher</div>
                                    <div class="report-bill-outer-section">
                                        <div class="row">
                                            <div class="col-md-9 col-sm-9 col-xs-9">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-4">
                                                        <div class="">Cheque No : 101</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="">Paid to : Sandesh Y Ghorpade</div>
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="">No : 136</div>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="">Date : 11/01/2018</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div style="padding: 0px;">
                                                <div class="table-wrap">
                                                    <div class="table-responsive">
                                                        <table id="datable_1" class="table padding-td-none padding-th-none table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Particular</th>
                                                                    <th class="text-center">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody> 
                                                                <tr id="">
                                                                    <td>Being New CCTV DVR Purchase</td>
                                                                    <td rowspan="2" class="text-right">1520.00</td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td>CCTV Expenses <span style="float:right;">A/c... &nbsp;&nbsp;&nbsp;</span></td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td>(Rupees  Ten Thousand Five Hundred Only)<span style="float:right;">Total &nbsp;&nbsp;&nbsp;</span></td>
                                                                    <td class="text-right">100.00</td>
                                                                </tr>
                                                            </tbody>
                                                        </table> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="col-md-12">
                                                Paid by :
                                            </div> 
                                        </div> 
                                        <br><br><br>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                                Prepared By
                                            </div> 
                                            <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                                Authorised By
                                            </div> 
                                            <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                                Receiver's Signature
                                            </div> 
                                        </div>
                                    </div>
                                </div> -->    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>