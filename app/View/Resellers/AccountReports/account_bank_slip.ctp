<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash();?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Bank Slip</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <div class="col-md-12 left-section"> 
                                <form class="" method="post" id="bankSlipFrm" name="bankSlipFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                    <div class="row">
                                        <div class="col-md-5 no-padding-right">  
                                            <div class="account-report-form-field-box">  
                                                <div class="account-report-form-box-label">Bank Name &nbsp;</div>                                                                     
                                                <select class="account-report-form-box-field-dropdown-80" id="" name="data[BankSlip][society_bank_id]" required>
                                                    <option value="">Select Bank</option>
                                                            <?php
                                                            if (isset($societyBankLists) && count($societyBankLists) > 0) :
                                                            $s = 1;
                                                            foreach ($societyBankLists as $societyBankId => $societyBankName) {
                                                            if (isset($postData['BankSlip']['society_bank_id']) && $postData['BankSlip']['society_bank_id'] == $societyBankId) {
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
                                                <div class="account-report-form-box-label">From Date &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[BankSlip][payment_date]" value="<?php echo isset($postData['BankSlip']['payment_date']) ? $postData['BankSlip']['payment_date'] :'' ; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[BankSlip][payment_date_to]" value="<?php echo isset($postData['BankSlip']['payment_date_to']) ? $postData['BankSlip']['payment_date_to'] :'' ; ?>">
                                            </div>  
                                        </div>       
                                        <div class="col-md-3 no-padding-right"> 
                                            <div class="account-report-form-box-label">Slip No &nbsp;</div>
                                            <input type="text" class="account-report-form-box-field-60" id="bill_no" name="data[BankSlip][slip_no]" value="<?php echo isset($postData['BankSlip']['slip_no']) ? $postData['BankSlip']['slip_no'] :'' ; ?>">                                        
                                        </div>
                                        <div class="col-md-3 nopadding"> 
                                            <div class="account-report-form-box-label">To &nbsp;</div>
                                            <input type="text" class="account-report-form-box-field-60" id="bill_no" name="data[BankSlip][slip_no_to]" value="<?php echo isset($postData['BankSlip']['slip_no_to']) ? $postData['BankSlip']['slip_no_to'] :'' ; ?>" >                                        
                                        </div>
                                        <div class="col-md-4 account-report-form"> 
                                            <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button> 
                                        </div> 
                                    </div>  
                                    <div class="row"> 
                                    </div>  
                                </form>  
                            </div> 
                            <div class="clearfix"></div>
                            <br><br><br>
                            <div id="society_bank_slip">
                                <div class="print-bank-slip"> 
                                    <div class="bill-outer-border">
                                        <div class="row">
                                            <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                            <div class="address-heading">Registration No. <?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?> Dated: <?php echo isset($societyDetails['Society']['registration_date']) ? $utilObj->getFormatDate($societyDetails['Society']['registration_date'],'d/m/Y') : ''; ?></div>
                                            <div class="address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                        </div>
                                        <?php 
                                        if (!empty($postData['BankSlip']['society_bank_id'])) {
                                            if (count($slipDetail)){ 
?>
                                        <div class="row1">
                                            <div class="bill">Bank Slip</div>
                                            <div class="row">
                                                <div class="col-md-8 col-xs-8">
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="">Bank Name : <?php echo $societyBankLists[$postData['BankSlip']['society_bank_id']];?></div>
                                                        </div>

                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="">Branch : <?php echo isset($societyBankData['SocietyBank']['branch']) ? $societyBankData['SocietyBank']['branch'] : '';?></div>
                                                        </div>
                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="">A/c No. : <?php echo isset($societyBankData['SocietyBank']['account_no']) ? $societyBankData['SocietyBank']['account_no'] : '';?></div>
                                                        </div>
                                                    </div>                                       
                                                </div>   
                                                <div class="col-md-4 col-xs-4">
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="">Slip No : <?php echo $postData['BankSlip']['slip_no'];?></div>
                                                        </div>
                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="">Date : <?php echo date('d/m/Y'); ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row1">
                                                <div style="padding: 0px;">
                                                    <div class="table-wrap">
                                                        <div class="table-responsive1">
                                                            <table id="datable_1" class="table padding-td-none padding-th-none table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">Sr.</th>
                                                                        <th>Unit No</th>
                                                                        <th>Voucher No</th>
                                                                        <th>Cheque No.</th>
                                                                        <th>Receive Date</th>
                                                                        <th>Cheque Date</th>
                                                                        <th>Bank Slip</th>
                                                                        <th>Drawee Bank</th>
                                                                        <th>Branch</th>
                                                                        <th class="text-center">Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <?php $totBal = 0; 
                                                                for ($i=0;$i<count($slipDetail);$i++){ 
                                                                    $totBal += $slipDetail[$i]['MemberPayment']['amount_paid'];
                                                                ?>
                                                                <tr id="">
                                                                    <td style="text-align:center;"><?php echo $i+1;?></td>
                                                                    <td><?php echo $slipDetail[$i]['Member']['flat_no'];?></td>
                                                                    <td><?php echo $slipDetail[$i]['MemberPayment']['receipt_id'];?></td>
                                                                    <td><?php echo $slipDetail[$i]['MemberPayment']['cheque_reference_number'];?></td>
                                                                    <td><?php echo isset($slipDetail[$i]['MemberPayment']['payment_date']) ? $utilObj->getFormatDate($slipDetail[$i]['MemberPayment']['payment_date'],'d/m/Y') : ''; ?></td>
                                                                    <td><?php echo isset($slipDetail[$i]['MemberPayment']['entry_date']) ? $utilObj->getFormatDate($slipDetail[$i]['MemberPayment']['entry_date'],'d/m/Y') : ''; ?></td>
                                                                    <td><?php echo $slipDetail[$i]['MemberPayment']['bank_slip_no']?></td>
                                                                    <td><?php echo $slipDetail[$i]['MemberBank']['bank_name'];?></td>
                                                                    <td><?php echo $slipDetail[$i]['MemberPayment']['member_bank_branch'];?></td>
                                                                    <td class="text-right"><?php echo number_format($slipDetail[$i]['MemberPayment']['amount_paid'],2,'.',','); ?></td>
                                                                </tr>
                                                                <?php } ?>
                                                                <tr id="">
                                                                    <td colspan="6"><?php echo ($totBal > 0) ? $utilObj->getAmountInRupees(number_format($totBal,2,'.',','))                                                          : '';?></td>
                                                                    <td  class="text-center" colspan="3">Total (Rs.)</td>
                                                                    <td class="text-right"><?php echo number_format($totBal,2,'.',','); ?></td>
                                                                </tr>
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
                                        <?php } else { ?>
                                        <div class="row1"><center><br>No result found. </center></div>
                                        <?php } } ?>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <center>
                                <button type="button" class="btn btn-success btn-icon left-icon" onclick="printAllReports('society_bank_slip');"><i class="fa fa-print"></i><span> Print</span></button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>