<?php
$db = $this->Session->read('Auth.dbConfig');
if ($db != 'default'){
    $op = '01/04/2018';
} else {
    $mon = date('m');

    if ($mon > 4) {
        $y = date('Y');
    } else {
        $y = date('Y', strtotime('-1 year'));
    }
    $op = '01/04/'.$y;
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Ledger</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="memberLedgerFrm" name="memberLedgerFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">                                            
                                        <div class="col-md-4 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-box-label">Type &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-80" id="" name="data[MemberBillSummary][report_type]">
                                                    <!--<option value="">Select</option>  -->
                                                    <option value="Default" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Default'){echo 'selected';}?>>Default</option>  
                                                    <!--<option value="Regular" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Regular'){echo 'selected';}?>>Regular</option>  
                                                    <option value="Supplementary" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Supplementary'){echo 'selected';}?>>Supplementary</option>  
                                                    <option value="Combined" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Combined'){echo 'selected';}?>>Combined</option>     -->                                                
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">From &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][payment_date]" value="<?php echo isset($postData['MemberBillSummary']['payment_date']) ? $postData['MemberBillSummary']['payment_date'] :'' ; ?>">
                                            </div> 
                                        </div>
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">To &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][payment_date_to]" value="<?php echo isset($postData['MemberBillSummary']['payment_date_to']) ? $postData['MemberBillSummary']['payment_date_to'] :'' ; ?>">
                                            </div> 
                                        </div>                                                         
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_member_ledger');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                                <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>  
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btn-sm" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>    
                                    </div>
                                </div> 
                                <div class="col-md-4 right-section">
                                    <div class="row">   
                                        <div class="right-title">Select (if required) </div> 
                                        <div class="col-md-12">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Building &nbsp;</div>                                    
                                                <select class="account-report-form-box-field-dropdown" id="" name="data[MemberBillSummary][building_id]" onchange="getAllBuildingWings(this.value, '#member_ledger');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($postData['MemberBillSummary']['building_id']) && $postData['MemberBillSummary']['building_id'] == $buildingID){?>
                                                    <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                                    <?php }?>
                                                    <?php } }?> 
                                                </select> 
                                            </div>
                                        </div>  
                                        <div class="col-md-12">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                                <select class="account-report-form-box-field-dropdown" id="member_ledger" name="data[MemberBillSummary][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Unit No &nbsp;&nbsp;</div>                                   
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberBillSummary][flat_no]" value="<?php echo isset($postData['MemberBillSummary']['flat_no']) ? $postData['MemberBillSummary']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberBillSummary][flat_no_to]" value="<?php echo isset($postData['MemberBillSummary']['flat_no_to']) ? $postData['MemberBillSummary']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div><br>
                                    </div>    
                                </div>   
                            </form> 
                             <div class="clearfix"></div>
                            <br><br><br>

                            <div id="print_member_ledger">
                            <div class="print-member-ledger">
                                <div class="row">
                                    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                </div>
                                <br>
                                <div class="row1">
                                    <div class="report-bill"></div>
                                    <div class="report-bill">Member Ledger-Regular</div>
                                    <div class="text-center"></div> 
                                    <div class="report-bill-outer-section">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="text-center">Date</th>
                                                        <th colspan="2" class="text-center">Reference</th>
                                                        <th colspan="2" class="text-center">Cheque</th>
                                                        <th rowspan="2" class="text-center">Dr. Amount</th>
                                                        <th rowspan="2" class="text-center">Cr. Amount</th>
                                                        <th rowspan="2" class="text-center">Balance</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Type.</th>
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php //TODO                                                    
                                                    if(isset($accountMemberLedgerDetails) && count($accountMemberLedgerDetails) > 0){
                                                    $idCount = 1;                                                 
                                                    foreach($accountMemberLedgerDetails as $MemberLedgerData){
                                                        if(!isset($MemberLedgerData['Member'])){ continue; }
                                                    ?>
                                                        <?php $innerCounter = 0; if(isset($MemberLedgerData['Member'])){?>
                                                        <tr id="">
                                                            <td width="8%" colspan="1" style="border:none !important"><span style="display: block;">Unit No : <?php echo isset($MemberLedgerData['Member']['flat_no']) ? $MemberLedgerData['Member']['flat_no'] : ''; ?></span></td>
                                                            <td colspan="7" style="border:none !important"><span style="display: block;">Member Name : <?php echo isset($MemberLedgerData['Member']['member_name']) ? $MemberLedgerData['Member']['member_name'] : ''; ?></span></td>
                                                        </tr>
                                                        <tr id="">
                                                            <td colspan="8" class="border-none">Building : <?php echo isset($MemberLedgerData['Member']['building_name']) ? $MemberLedgerData['Member']['building_name'] : ''; ?></td>
                                                        </tr>
                                                        <tr id="">
                                                            <td colspan="8" class="border-none"><span style="display: block;">Wing : <?php echo isset($MemberLedgerData['Member']['wing_name']) ? $MemberLedgerData['Member']['wing_name'] : ''; ?></span></td>
                                                        </tr>
                                                        <?php } ?>
                                                    <?php 
                                                                $openingCr = 0;
                                                                $openingDr = 0;
                                                                /*$op_principal = isset($MemberLedgerData['Member']['op_principal']) ? $MemberLedgerData['Member']['op_principal'] * 1 : 0.00;
                                                                $op_interest = isset($MemberLedgerData['Member']['op_interest']) ? $MemberLedgerData['Member']['op_interest'] * 1 : 0.00 ;
                                                                $op_tax = isset($MemberLedgerData['Member']['op_tax']) ? $MemberLedgerData['Member']['op_tax'] * 1 : 0.00 ;*/
                                                                $op_principal = isset($memberArr[$MemberLedgerData['Member']['id']]['op_principal']) ? $memberArr[$MemberLedgerData['Member']['id']]['op_principal'] * 1 : 0.00;
                                                                $op_interest = isset($memberArr[$MemberLedgerData['Member']['id']]['op_interest']) ? $memberArr[$MemberLedgerData['Member']['id']]['op_interest'] * 1 : 0.00;
                                                                $op_tax = isset($memberArr[$MemberLedgerData['Member']['id']]['op_tax']) ? $memberArr[$MemberLedgerData['Member']['id']]['op_tax'] * 1 : 0.00;
                                                                $op_balance =  $op_principal + $op_interest + $op_tax ; 
                                                                $indBalance = $op_balance;

                                                                $paymentTotalAmount = 0;
                                                                $monthlyTotalAmount = 0;

                                                                if($op_balance < 0) {
                                                                    $openingDr = abs($op_balance);
                                                                    $posNeg = 'Cr';
                                                                    $paymentTotalAmount = $op_balance;                                                                    
                                                                } else {
                                                                    $openingCr = abs($op_balance);
                                                                    $posNeg = 'Dr'; 
                                                                    $monthlyTotalAmount = $op_balance;                                                                    
                                                                }

                                                     ?>
                                                     <tr id="">
                                                        <td><?php echo $op;//echo (date('m') < 4) ? '01/04/'.date('Y', strtotime('-1 year')) : '01/04'.date('Y');   ?></td>
                                                        <td><span style="display: block;">Opening Balance</span></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-right total_debit"><?php echo number_format((float)$openingCr, 2, '.', ''); ?></td>
                                                        <td class="text-right total_credit"><?php echo number_format((float)$openingDr, 2, '.', ''); ?></td>
                                                        <td class="text-right total_balance"><?php echo number_format((float)abs($op_balance), 2, '.', '').' '.$posNeg;?></td>
                                                    </tr>
                                                     <?php
                                                     foreach($MemberLedgerData['MemberBillSummary'] as $MemberLedgerDetails){
                                                         //print_r($MemberLedgerDetails);
                                                         
                                                        if(!isset($MemberLedgerDetails['monthly_bill_amount'])) {
                                                            $MemberLedgerDetails['monthly_bill_amount'] = 0;
                                                        }

                                                        $monthlyTotalAmount = $monthlyTotalAmount + $MemberLedgerDetails['monthly_bill_amount'];

                                                        $indBalance += $MemberLedgerDetails['monthly_bill_amount']; 

                                                        if($indBalance < 0) {
                                                            $posNeg = 'Cr';
                                                        } else { $posNeg = 'Dr';}
                                                             
                                                        ?>
                                                        <?php if(isset($MemberLedgerDetails['bill_generated_date']) && $MemberLedgerDetails['bill_generated_date'] != '') { ?>
                                                        <tr>
                                                            <td><?php echo isset($MemberLedgerDetails['bill_generated_date']) ? $MemberLedgerDetails['bill_generated_date'] : '';  ?></td>
                                                            <td>Bill For <?php echo isset($MemberLedgerDetails['month']) ? $MemberLedgerDetails['monthName']: '';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberLedgerDetails['bill_no']) ? $MemberLedgerDetails['bill_no']: '';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberLedgerDetails['cheque_reference_number']) ? $MemberLedgerDetails['cheque_reference_number']: '0';  ?></td>
                                                            <td width="8%" class="text-center"><?php echo isset($MemberLedgerDetails['payment_date']) ? $MemberLedgerDetails['payment_date']: '-';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberLedgerDetails['monthly_bill_amount']) ? $MemberLedgerDetails['monthly_bill_amount']: '';  ?></td>
                                                            <td width="8%" class="text-right">0.00</td>
                                                            <td width="8%" class="text-right"><?php echo number_format((float)abs($indBalance),2,'.', '').' '.$posNeg;  ?></td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php if(isset($MemberLedgerDetails['MemberPayment'])){
                                                                $creaditAmt = 0;
                                                            foreach($MemberLedgerDetails['MemberPayment'] as $MemberPaymentData){
                                                                $creaditAmt = isset($MemberPaymentData['amount_paid']) ? $MemberPaymentData['amount_paid']: '0.00';
                                                                
                                                                $paymentTotalAmount = $paymentTotalAmount + $creaditAmt;
                                                                                
                                                                $indBalance -= $creaditAmt;
                                                                if($indBalance < 0) {
                                                                    $posNeg = 'Cr';
                                                                } else { $posNeg = 'Dr';}
                                                            ?>
                                                        <tr>
                                                            <td><?php echo isset($MemberPaymentData['payment_date']) ? $MemberPaymentData['payment_date'] : '';  ?></td>
                                                            <td>Receipt For <?php echo isset($MemberPaymentData['bill_month']) ? $utilObj->getMonthWordFormat($MemberPaymentData['bill_month']): '';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberPaymentData['receipt_id']) ? $MemberPaymentData['receipt_id']: '';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberPaymentData['cheque_reference_number']) ? $MemberPaymentData['cheque_reference_number']: '0';  ?></td>
                                                            <td width="8%" class="text-center"><?php echo isset($MemberPaymentData['payment_date']) ? $MemberPaymentData['payment_date']: '-';  ?></td>
                                                            <td width="8%" class="text-right">0.00</td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberPaymentData['amount_paid']) ? $MemberPaymentData['amount_paid']: '0.00'; ?></td>
                                                            <td width="8%" class="text-right"><?php echo number_format((float)abs($indBalance),2,'.', '').' '.$posNeg;  ?></td>
                                                        </tr>
                                                        <?php }} ?>                                                         
                                             
                                            <?php } ?>
                                            <?php if(isset($MemberLedgerData['JVData'])){                                                                
                                                            foreach($MemberLedgerData['JVData'] as $MemberPaymentData){
                                                            $credit = 0;
                                                            $debit = 0;
                                                            
                                                            if($MemberPaymentData['type'] == 'Credit') {
                                                                $credit = $MemberPaymentData['amount_paid'];
                                                                $indBalance = $indBalance - $credit;
                                                                $paymentTotalAmount = $paymentTotalAmount + $credit;
                                                            } else if($MemberPaymentData['type'] == 'Debit') {
                                                                $debit = $MemberPaymentData['amount_paid'];
                                                                $indBalance = $indBalance + $debit;
                                                                $monthlyTotalAmount = $monthlyTotalAmount + $debit;
                                                            }
                                                            if($indBalance < 0) {
                                                                $posNeg = 'Cr';
                                                            } else { $posNeg = 'Dr';}
                                                            ?>
                                                        <tr>
                                                            <td><?php echo isset($MemberPaymentData['payment_date']) ? $utilObj->mysqlToDate($MemberPaymentData['payment_date'],'/'): '';  ?></td>
                                                            <td><?php echo isset($MemberPaymentData['particulars']) ? $MemberPaymentData['particulars'] : '';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberPaymentData['voucher_no']) ? $MemberPaymentData['voucher_no']: '';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberPaymentData['cheque_reference_number']) ? $MemberPaymentData['cheque_reference_number']: '0';  ?></td>
                                                            <td width="8%" class="text-center"><?php echo isset($MemberPaymentData['payment_date']) ? $utilObj->mysqlToDate($MemberPaymentData['payment_date'],'/'): '';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo $debit; ?></td>
                                                            <td width="8%" class="text-right"><?php echo $credit; ?></td>
                                                            <td width="8%" class="text-right"><?php echo number_format((float)abs($indBalance),2,'.', '').' '.$posNeg;  ?></td>
                                                        </tr>
                                                        <?php }}?> 
                                                            <tr style="background-color: #d2d2d2;">
                                                                <?php 
                                                                    $totalBal = $monthlyTotalAmount - abs($paymentTotalAmount);
                                                                   // echo $totalBal;

                                                                    if($totalBal < 0) {
                                                                        $posNeg = 'Cr';
                                                                    } else { $posNeg = 'Dr';}
                                                                ?>
                                                                <td colspan="5" class="text-right">Total</td>
                                                                <td id="total_debit_flat_shop_sum" class="text-right"><?php  echo number_format((float)$monthlyTotalAmount, 2, '.', '');?></td>
                                                                <td id="total_credit_flat_shop_sum" class="text-right"><?php  echo number_format((float)abs($paymentTotalAmount),2,'.', ''); ?></td>
                                                                <td id="total_balance_flat_shop_sum" class="text-right" style="font-weight:bold;"><?php echo number_format((float)abs($totalBal),2,'.', '').' '; echo $posNeg;?></td>

                                                            </tr>
                                            <?php $idCount++;$innerCounter++; } ?>
                                            <?php } ?>
                                        </tbody>
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
        </div>
    </div>
</div>