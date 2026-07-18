<?php 
$societyBankLists = $societyBillObj->getSocietyBankNameList();
$societyMemberBankLists = $societyBillObj->getBankList();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                       
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                           <div id="print_member_bill">
                            
                            <?php 
                            if(isset($monthlyBillsSummaryDetails) && !empty($monthlyBillsSummaryDetails)){
                            foreach($monthlyBillsSummaryDetails as $memberbillReport) {
                            ?>    
                                <div class="print-member-bill">
                                <div class="row">
                                    <h5 class="text-center"><?php echo isset($memberbillReport['Society']['society_name']) ? $memberbillReport['Society']['society_name'] : ''; ?></h5>
                                    <div class="address-heading">Registration No. <?php echo isset($memberbillReport['Society']['registration_no']) ? $memberbillReport['Society']['registration_no'] : ''; ?> Dated: <?php echo isset($memberbillReport['Society']['registration_date']) ? $memberbillReport['Society']['registration_date'] : ''; ?></div>
                                    <div class="address-heading"><?php echo isset($memberbillReport['Society']['address']) ? $memberbillReport['Society']['address'] : ''; ?></div>
                                </div>
                                <div class="row1">
                                    <div class="bill">BILL</div>
                                    <div class="bill-outer-border">
                                        <div class="row">
                                            <div class="col-md-9 col-xs-9">
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <div class="">Unit No : <?php echo isset($memberbillReport['Member']['flat_no']) ? $memberbillReport['Member']['flat_no'] :'' ; ?> </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <div class="">Unit Area : <?php echo isset($memberbillReport['Member']['area']) ? $memberbillReport['Member']['area'] :'' ; ?> SqFt</div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <div class="">Unit type : <?php echo isset($memberbillReport['Member']['unit_type']) ? $memberbillReport['Member']['unit_type'] :'' ; ?></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8 col-xs-8">
                                                        <div class="">Name : <?php echo $memberbillReport['Member']['member_prefix'].' '.$memberbillReport['Member']['member_name'];?></div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <div class="">Bill For : <?php echo isset($memberbillReport['MemberBillSummary']['month']) ? $utilObj->getMonthWordFormat($memberbillReport['MemberBillSummary']['month']) :'' ; ?></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="">Building : <?php echo isset($memberbillReport['Building']['building_name']) ? $memberbillReport['Building']['building_name'] :'' ; ?></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-4 col-xs-6">
                                                        <div class="">Wing : <?php echo isset($memberbillReport['Wing']['wing_name']) ? $memberbillReport['Wing']['wing_name'] :'' ; ?></div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-4 col-xs-6">
                                                        <div class="">Floor No : <?php echo isset($memberbillReport['Member']['floor_no']) ? $memberbillReport['Member']['floor_no'] :'' ; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-xs-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="">Bill No : <?php echo isset($memberbillReport['MemberBillSummary']['bill_no']) ? $memberbillReport['MemberBillSummary']['bill_no'] :'' ; ?></div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="">Bill Date : <?php echo isset($memberbillReport['MemberBillSummary']['bill_generated_date']) ? $memberbillReport['MemberBillSummary']['bill_generated_date'] :'' ; ?></div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="">Due Date : <?php echo isset($memberbillReport['MemberBillSummary']['bill_due_date']) ? $memberbillReport['MemberBillSummary']['bill_due_date'] :'' ; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row1">
                                            <div style="padding: 0px;">
                                                <div class="table-wrap">
                                                    <div class="table-responsive1">
                                                        <table id="datable_1" class="table padding-th-none-zero padding-td-none-zero table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr.</th>
                                                                    <th colspan="3" >Particular of Charges</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <?php 
                                                                $tariffSr = 1;
                                                                foreach($memberbillReport['MemberTariff'] as $key => $indTariff) {
                                                                if($indTariff['amount'] > 0) {	
                                                                        $ledgerHead = $indTariff['title'];
                                                                        $ledgerAmount = $indTariff['amount'];
                                                                        echo "<tr>
                                                                                <td style='text-align:center;'>$tariffSr</td>
                                                                               <td  colspan=\"3\" >$ledgerHead</td>
                                                                                <td style='width:10%;' class='text-right'>$ledgerAmount</td>
                                                                                </tr>";
                                                                        $tariffSr++;
                                                                }
                                                            } ?>
                                                            
                                                            <tr>
                                                                <td colspan="2" rowspan="6" style="width:5% !important;"> E.&.O.E </td>
                                                                <td  colspan="2" >Sub Total</td>
                                                                <td class="text-right"><?php echo $memberbillReport['MemberBillSummary']['monthly_amount'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Adjustment Credit / Rebate</td>
                                                                <td class="text-right"><?php echo $memberbillReport['MemberBillSummary']['principal_adjusted']+$memberbillReport['MemberBillSummary']['interest_adjusted'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Interest On Arrears</td>
                                                                <td class="text-right"><?php echo $memberbillReport['MemberBillSummary']['interest_on_due_amount'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Previous Arrears</td>
                                                                <td class="text-right" rowspan="3">
                                                                    <?php if($memberbillReport['MemberBillSummary']['op_due_amount'] < 0) { echo str_replace('-','',$memberbillReport['MemberBillSummary']['op_due_amount']).' Cr'; }
                                                                    else { echo $memberbillReport['MemberBillSummary']['op_due_amount'].' Dr'; }                                                                    
                                                                    ?>
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <td>Principal</td>
                                                                <td class="text-right"><?php echo $memberbillReport['MemberBillSummary']['op_principal_arrears'];?></td>                                                                
                                                            </tr>
                                                            <tr>
                                                                <td>Interest</td>
                                                                <td class="text-right"><?php echo $memberbillReport['MemberBillSummary']['op_interest_arrears'];?></td>                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3" >Rupees <?php echo $utilObj->convertToWords(abs($memberbillReport['MemberBillSummary']['balance_amount']));?> Only</td>
                                                                <td colspan="1" >
                                                                    <?php  if($memberbillReport['MemberBillSummary']['balance_amount'] < 0) { echo "Excess Amount Received";  } else { echo "<strong>Total Due Amount & Payable</strong>"; } ?>
                                                                </td>                                                                
                                                                <td class="text-right"><?php  if($memberbillReport['MemberBillSummary']['balance_amount'] < 0) { echo str_replace('-','',$memberbillReport['MemberBillSummary']['balance_amount']).' Cr'; }
                                                                    else { echo $memberbillReport['MemberBillSummary']['balance_amount'].' Dr'; } ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>     

                                        <div class="row1" style="padding:8px;">
                                            <strong>Notes :</strong>
                                            <ul>
                                                <li>1. As this Computer Generated Bill No. Signature is Required.</li>
                                                <li>2. Please pay by A/c Cheque Only.</li>
                                                <li>3. Kindly pay this bill by the due date mentioned on the bill. thereafter interest 21% p.a. will be charged for entire period.</li>
                                            </ul>  
                                        </div>
                                        <br>
                                        <div class="row simple-border" style="padding:0px;"></div>
                                        <div class="row">
                                            <div class="receipt-title">RECEIPT</div>
                                            <div class="col-md-8 col-xs-8">Received with thanks from <strong><?php echo $memberbillReport['Member']['member_prefix'].' '.$memberbillReport['Member']['member_name'];?></strong></div>                                                
                                            <div class="col-md-4 col-xs-4 text-right">Unit No :<strong> <?php echo $memberbillReport['Member']['flat_no'];?></strong></div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-12">Details of payments received are as under : Period :  <strong>01/04/2017 To 31/03/2018 </strong></div>                                
                                        </div> 
                                        <div class="row1">
                                            <div style="padding: 0px;">
                                                <div class="table-wrap">
                                                    <div class="table-responsive">
                                                        <table id="datable_1" class="table table-bordered padding-th-none-zero padding-td-none-zero" >
                                                            <thead>
                                                                <tr>
                                                                    <th>Receipt</th>
                                                                    <th>Date</th>
                                                                    <th>Chq No.</th>
                                                                    <th>Chq Date</th>
                                                                    <th>Bank & Branch</th>
                                                                    <th>Towards bill No.</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php  
                                                                if(isset($memberbillReport['MemberPayment'])){
                                                                    $receiptCounter = 0;
                                                                    foreach($memberbillReport['MemberPayment'] as $memberPaymentData){?>
                                                                    <tr id="">
                                                                        <td><?php echo isset($memberPaymentData['MemberPayment']['receipt_id']) ? $memberPaymentData['MemberPayment']['receipt_id']: '';  ?></td>
                                                                        <td><?php echo isset($memberPaymentData['MemberPayment']['payment_date']) ? $utilObj->mysqlToDate($memberPaymentData['MemberPayment']['payment_date'],'/'): '';  ?></td>
                                                                        <td><?php echo isset($memberPaymentData['MemberPayment']['cheque_reference_number']) ? $memberPaymentData['MemberPayment']['cheque_reference_number']: '';  ?></td>
                                                                        <td><?php echo isset($memberPaymentData['MemberPayment']['credited_date']) ? $utilObj->mysqlToDate($memberPaymentData['MemberPayment']['credited_date'],'/'): '';  ?></td>                                                    
                                                                        <td><?php echo isset($societyBankLists[$memberPaymentData['MemberPayment']['society_bank_id']]) ? $societyBankLists[$memberPaymentData['MemberPayment']['society_bank_id']]: '';  ?></td>
                                                                        <td><?php echo isset($memberPaymentData['MemberPayment']['bill_generated_id']) ? $memberPaymentData['MemberPayment']['bill_generated_id']: '';  ?></td>
                                                                        <td class="text-right"><?php echo isset($memberPaymentData['MemberPayment']['amount_paid']) ? $memberPaymentData['MemberPayment']['amount_paid']: '';  ?></td>
                                                                    </tr>
                                                                <?php $receiptCounter++; }}?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="">(Subject to Realisation of Cheque)</div>
                                            </div>
                                            <div class="col-md-7">
                                                <p class="pull-right">for <?php echo isset($memberbillReport['Society']['society_name']) ? $memberbillReport['Society']['society_name'] : ''; ?> </p>
                                                <br> <br> <br>
                                                <p class="pull-right">Authorized Signature</p>
                                            </div>
                                        </div>                                        
                                    </div>                                    
                                </div>
                                </div>
                            <?php } }?>
                            </div>
                                <center><button type="button" class="btn btn-success  btn-icon left-icon" onclick="printAllReports('print_member_bill');"> 
                                <i class="fa fa-print"></i><span> Print</span> 
                                </button></center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
