<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Journal Voucher</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="" name="" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-12 left-section"> 
                                    <div class="row">
                                        <div class="col-md-3 no-padding-right">  
                                            <div class="account-report-form-field-box">  
                                                <div class="account-report-form-box-label">Type &nbsp;</div>                                                                     
                                                <select class="account-report-form-box-field-dropdown-70" id="" name="">
                                                    <option value="">Journal Voucher</option>                                                   
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 no-padding-right">  
                                            <div class="account-report-form-field-box">  
                                                <div class="account-report-form-box-label">Sub Type &nbsp;</div>                                                                     
                                                <select class="account-report-form-box-field-dropdown-65" id="" name="">
                                                    <option value="">General</option>                                                   
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">   
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">Voucher Date &nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-1 nopadding">  
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">V.No &nbsp;</div>                                                                     
                                                <input type="text" class="account-report-form-box-field-date" id="voucher_no" name="voucher_no" value="<?php echo $postData['voucher_no'];?>">
                                            </div>  
                                        </div>                                                            
                                        <div class="col-md-1 nopadding">  
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">Sr. &nbsp;</div>                                                                     
                                                <input type="text" class="account-report-form-box-field-date" id="" name="" value="">
                                            </div>  
                                        </div>                                                            
                                        <div class="col-md-1 nopadding"></div>  
                                        <div class="clearfix"></div><br>
                                        <div class="col-md-12 account-report-form"> 
                                            <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button> 
                                            <button type="button" class="btn btn-success btn-icon btns" onclick="printAllReports('society_journal_voucher');"><i class="fa fa-print"></i><span> Print</span></button>
                                            <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>                                        
                                            <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a> 
                                        </div>                                                                       
                                    </div> 
                                </div>

                          </form>

                            <div class="clearfix"></div><br><br>                            
                            <div id="society_journal_voucher">
                                <div class="print_journal_voucher"> 
                                    <div class="bill-outer-border">
                                        <div class="row"><br>
                                            <h5 class="text-center"><?php echo $societyDetails['Society']['society_name'];?></h5>
                                            <div class="address-heading">Registration No. <?php echo $societyDetails['Society']['registration_no'];?> Dated: <?php echo $societyDetails['Society']['registration_date'];?></div>
                                            <div class="address-heading"><?php echo $societyDetails['Society']['address'];?></div>
                                        </div>
                                        <br>
                                        <div class="row1">
                                            <div class="bill">Journal Voucher Register</div>
                                            <div class="text-center"></div>
                                            
                                            <div class="row1">
                                                <div style="padding: 0px;">
                                                    <div class="table-wrap">
                                                        <div class="table-responsive1">
                                                            <table id="datable_1" class="table padding-td-none padding-th-none table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="15%" class="text-center">Date</th>
                                                                        <th>Particulars</th>
                                                                        <th>Note</th>
                                                                        <th class="text-center" width="8%">Dr.</th>
                                                                        <th class="text-center" width="8%">Cr.</th>
                                                                    </tr>
                                                                </thead>
                                            
                                            <?php
                                                $totalDebit = 0;
                                                $totalCredit = 0;
                                                
                                                foreach($journalVoucherDataArr as $voucherNo=>$tempDetails) {
                                                    $voucherDate = $tempDetails['date'];
                                                    $debitDetails = $tempDetails['debit'];
                                                    $creditDetails = $tempDetails['credit'];
                                                ?>
                                                    <tr id="">
                                                                    <td colspan="4" class="border-none"><?php echo date('d/m/Y',strtotime($voucherDate));?></td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td colspan="5" class="border-none">Voucher No:&nbsp;&nbsp;<?php echo $voucherNo;?></td>
                                                                </tr>
                                                                <?php if(!empty($debitDetails)) { 
                                                                    foreach($debitDetails as $tempKey=>$tempDebitDetails) { 
                                                                        $totalDebit += $tempDebitDetails['jv_amount_debited'];
                                                                        if($tempDebitDetails['flag'] == 'ledger') {
                                                                            $particular = $ledgerData[$tempDebitDetails['jv_debit_ledger_head_id']];
                                                                            
                                                                             $note = $tempDebitDetails['note'];
                                                                        } else if($tempDebitDetails['flag'] == 'member') {
                                                                            // debit_member_title is the previous owner's name on rows saved
                                                                            // before a transfer, the current name otherwise.
                                                                            $particular = isset($tempDebitDetails['debit_member_title']) && $tempDebitDetails['debit_member_title'] !== '' ? $tempDebitDetails['debit_member_title'] : $memberData[$tempDebitDetails['jv_debit_member_head_id']];
                                                                            $note = $tempDebitDetails['note'];
                                                                        } else {
                                                                            $particular = '';
                                                                            $note = '';
                                                                        }
                                                                ?>
                                                                    <tr id="">
                                                                    <td></td>
                                                                    <td <?php if (isset($postData['voucher_no']) && $postData['voucher_no'] !='') echo 'style="padding-top:40px;padding-bottom:40px;"';?> ><?php echo $particular;?></td>
                                                                    <td><?php echo wordwrap($note, 60, "<br />");?></td>
                                                                    <td class="text-right"><?php echo $tempDebitDetails['jv_amount_debited'];?></td>
                                                                    <td class="text-right">0</td>
                                                                </tr>
                                                                <?php    }
                                                                }
                                                                ?>
                                                                
                                                                <?php if(!empty($creditDetails)) { 
                                                                    foreach($creditDetails as $tempKey=>$tempCreditDetails) { 
                                                                        $totalCredit += $tempCreditDetails['jv_amount_credited'];
                                                                        if($tempCreditDetails['flag'] == 'ledger') {
                                                                            $particular = $ledgerData[$tempCreditDetails['jv_credit_ledger_head_id']];
                                                                            $note = $tempCreditDetails['note'];
                                                                        } else if($tempCreditDetails['flag'] == 'member') {
                                                                            $particular = isset($tempCreditDetails['credit_member_title']) && $tempCreditDetails['credit_member_title'] !== '' ? $tempCreditDetails['credit_member_title'] : $memberData[$tempCreditDetails['jv_credit_member_head_id']];
                                                                            $note = $tempCreditDetails['note'];
                                                                        } else {
                                                                            $particular = '';
                                                                            $note = '';
                                                                        }
                                                                ?>
                                                                    <tr id="">
                                                                    <td></td>
                                                                    <td <?php if (isset($postData['voucher_no']) && $postData['voucher_no'] !='') echo 'style="padding-top:40px;padding-bottom:40px;"';?>><?php echo $particular;?></td>
                                                                    <td><?php echo wordwrap($note, 60, "<br />");?></td>
                                                                    <td class="text-right"></td>
                                                                    <td class="text-right"><?php echo $tempCreditDetails['jv_amount_credited'];?></td>
                                                                </tr>
                                                                <?php    }
                                                                }
                                                                ?>
                                            <?php    }
                                            ?>
                                            
                                                                
                                                                 <tfoot id=""> 
                                                                    <tr>                                                                       
                                                                        <td colspan="3" style="font-weight:bold;">Grand Total</td>
                                                                        <td class="text-right" style="font-weight:bold;"><?php echo number_format($totalDebit);?></td>
                                                                        <td class="text-right" style="font-weight:bold;"><?php echo number_format($totalCredit);?></td>                                                                        
                                                                    </tr>
                                                                </tfoot>
                                                              
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><br><br><br>
                                            <div class="row">
                                                <div class="col-md-3 col-xs-1"></div>  
                                                <div class="col-md-3 col-xs-5">Cashier's Signature</div> 
                                                <div class="col-md-3 col-xs-1"></div>  
                                                <div class="col-md-3 col-xs-5">Authorised Signatory</div> 
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>