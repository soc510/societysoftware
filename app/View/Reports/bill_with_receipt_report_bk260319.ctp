<?php
$societyBankLists = $societyBillObj->getSocietyBankNameList();
$societyMemberBankLists = $societyBillObj->getBankList();
$societyLedgerHeadIds = array_keys($societyLedgerHeadTitleList);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <button type="button" class="btn btn-success  btn-icon left-icon" onclick="printAllReports('print_bill_with_receipt');"><i class="fa fa-print"></i><span> Print</span></button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <div id="print_bill_with_receipt">

                            <?php 
                            if(isset($monthlyBillsSummaryDetails) && !empty($monthlyBillsSummaryDetails)){
                            foreach($monthlyBillsSummaryDetails as $memberbillReport) {
                                $societyRegistrationDate = isset($memberbillReport['Society']['registration_date']) ? $memberbillReport['Society']['registration_date'] : ''; 
                                $billDate = isset($memberbillReport['MemberBillSummary']['bill_generated_date']) ? $memberbillReport['MemberBillSummary']['bill_generated_date'] :'' ;
                                $billDueDate = isset($memberbillReport['MemberBillSummary']['bill_due_date']) ? $memberbillReport['MemberBillSummary']['bill_due_date'] :'' ;
                                $memberReceipts = isset($memberbillReport['MemberPayment']) ? $memberbillReport['MemberPayment']:'' ;
                            ?>    
                                <div class="print-bill-with-receipt">

                                    <div class="bill-outer-border">
                                        <div class="row">
                                            <h5 class="text-center"><?php echo isset($memberbillReport['Society']['society_name']) ? $memberbillReport['Society']['society_name'] : ''; ?></h5>
                                            <div class="address-heading">Registration No. <?php echo isset($memberbillReport['Society']['registration_no']) ? $memberbillReport['Society']['registration_no'] : ''; ?> Dated: <?php echo date('d/m/Y',strtotime($societyRegistrationDate)); ?></div>
                                            <div class="address-heading"><?php echo isset($memberbillReport['Society']['address']) ? $memberbillReport['Society']['address'] : ''; ?></div>
                                        </div>
                                        <div class="bill">BILL</div>
                                        <div class="simple-border"></div>
                                        <div class="row bill-member-details-section">
                                            <div class="col-md-9 col-xs-9">
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <div class="">Unit No : <strong><?php echo isset($memberbillReport['Member']['flat_no']) ? $memberbillReport['Member']['flat_no'] :'' ; ?></strong> </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <div class="">Unit Area : <strong><?php echo isset($memberbillReport['Member']['area']) ? $memberbillReport['Member']['area'] :'' ; ?></strong> SqFt</div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-4"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="">Name : <?php echo $memberbillReport['Member']['member_prefix'].' '.$memberbillReport['Member']['member_name'];?></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="">Bill For : <?php echo isset($memberbillReport['MemberBillSummary']['monthName']) ? $memberbillReport['MemberBillSummary']['monthName'] :'' ; ?> <?php echo isset($memberbillReport['Society']['financial_year']) ? $memberbillReport['Society']['financial_year'] : ''; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-xs-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="">Bill No : <strong><?php echo isset($memberbillReport['MemberBillSummary']['bill_no']) ? $memberbillReport['MemberBillSummary']['bill_no'] :'' ; ?></strong></div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="">Bill Date : <strong><?php echo date('d/m/Y',strtotime($billDate)); ?></strong></div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="">Due Date : <strong><?php echo date('d/m/Y',strtotime($billDueDate)); ?></strong></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="">
                                            <div style="padding: 0px;">
                                                <div class="table-wrap">
                                                    <div class="table-responsive1">
                                                        <table id="datable_1" class="table padding-th-none-zero padding-td-none-zero table-bordered print-custom-table-border">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center" style="width: 5% !important;" >Sr.</th>
                                                                    <th colspan="3" class="text-center">Particular of Charges</th>
                                                                    <th class="text-center">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <?php 
                                                                    $tariffSr = 1;
                                                                    $memberBillTariff = array();
                                                                    foreach($memberbillReport['MemberTariff'] as $key => $indTariff) {
                                                                    if($indTariff['amount'] > 0) {	
                                                                            $memberBillTariff[] = $indTariff['ledger_head_id'];
                                                                            $ledgerHead = $indTariff['title'];
                                                                            $ledgerAmount = $indTariff['amount'];
                                                                            echo "<tr>
                                                                                    <td class=\"text-center\">$tariffSr</td>
                                                                                   <td colspan=\"3\" > &nbsp; $ledgerHead</td>
                                                                                    <td style='width:10%;' class='text-right'> $ledgerAmount</td>
                                                                                    </tr>";
                                                                            $tariffSr++;
                                                                    }
                                                                }
                                                            
                                                                if(isset($societyParameters['SocietyParameter']['show_all_tariff_name']) && $societyParameters['SocietyParameter']['show_all_tariff_name'] == 1){                                                                            
                                                                            if(!empty($societyLedgerHeadIds)) {
                                                                                $tariffsNotInBill = array_diff($societyLedgerHeadIds,$memberBillTariff);
                                                                                if(!empty($tariffsNotInBill)) {
                                                                                    foreach($tariffsNotInBill as $headId) {                                                                        
                                                                                            $ledgerHead = $societyLedgerHeadTitleList[$headId];
                                                                                            $ledgerAmount = '0.00';
                                                                                            echo "<tr>
                                                                                                    <td style='text-align:center;width: 5% !important;'>$tariffSr</td>
                                                                                                   <td  colspan=\"3\" >&nbsp; $ledgerHead</td>
                                                                                                    <td style='width:14%;' class='text-right'>$ledgerAmount</td>
                                                                                                    </tr>";
                                                                                            $tariffSr++;
                                                                                     }
                                                                                }
                                                                            }
                                                                        }
                                                                ?>

                                                            <tr>
                                                                <td colspan="2" rowspan="5" style="width:50% !important;">  </td>
                                                                <td colspan="2">&nbsp; Total</td>
                                                                <td class="text-right">
                                                                    <?php 
                                                                        $totalTariffAmount =  $memberbillReport['MemberBillSummary']['monthly_amount'] - $memberbillReport['MemberBillSummary']['tax_total'];
                                                                        echo number_format($totalTariffAmount,2);
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" >&nbsp; Interest</td>
                                                                <td class="text-right"><?php echo number_format($memberbillReport['MemberBillSummary']['interest_balance'],2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" >&nbsp; Less: Adjustment</td>
                                                                <td class="text-right">
                                                                    <?php 
                                                                        $totalAdjustment =  $memberbillReport['MemberBillSummary']['principal_adjusted'] + $memberbillReport['MemberBillSummary']['interest_adjusted'];
                                                                        echo number_format($totalAdjustment,2);
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>&nbsp; Principal Arrears</td>
                                                                <td class="text-right" >
                                                                    <?php if($memberbillReport['MemberBillSummary']['op_due_amount'] < 0) { $posNeg = 'Cr'; }
                                                                        else { $posNeg = 'Dr'; }
                                                                        $memberbillReport['MemberBillSummary']['op_due_amount'] = abs($memberbillReport['MemberBillSummary']['op_due_amount']);
                                                                        echo number_format($memberbillReport['MemberBillSummary']['op_due_amount'],2).' '.$posNeg;
                                                                    ?>
                                                                </td>                                                                
                                                                <td class="text-right" rowspan="2">
                                                                    <?php
                                                                        $totalArrears = $memberbillReport['MemberBillSummary']['op_principal_arrears'];
                                                                        if($totalArrears < 0) { $posNeg = 'Cr'; }
                                                                        else { $posNeg = 'Dr'; }
                                                                        $totalArrears = abs($totalArrears);
                                                                        echo number_format($totalArrears,2).' '.$posNeg;
                                                                   ?>
                                                                </td>   
                                                            </tr>
                                                            <tr>
                                                                <td>&nbsp; Interest Arrears</td>
                                                                <td class="text-right"><?php echo number_format($memberbillReport['MemberBillSummary']['op_interest_arrears'],2);?></td>                                                               
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" > &nbsp; Rupees<?php echo $utilObj->convertToWords(abs($memberbillReport['MemberBillSummary']['balance_amount']));?> Only</td>
                                                                <td colspan="2" >
                                                                    <?php  if($memberbillReport['MemberBillSummary']['balance_amount'] < 0) { echo "<strong>&nbsp; Excess Amount Received</strong>";  } else { echo "<strong>&nbsp; Total Due Amount & Payable</strong>"; } ?>
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
                                            <strong>E.&.O.E.</strong>
                                            <ul>
                                                <li>1. Interest @21% will be Charged if the bill is not paid within 45 days from the date of this bill .</li>
                                                <li>2. Any objection to the bill should be intimated immediately .No claim will be entereained after 15 days.</li>
                                                <li>3. Bank Details ( Bank Name ): JANAKALYAN SAHAKARI BANK LTD., DADAR(W) BRANCH , A/C NO. 002010100801010 IFSC CODE : JSBL0000020</li>
                                            </ul>  
                                        </div>

                                    </div> 
                                    <div class="pull-right">For <?php echo isset($memberbillReport['Society']['society_name']) ? $memberbillReport['Society']['society_name'] : ''; ?></div>
                                    <br><br><br>
                                    <div class="pull-right"><?php echo isset($memberbillReport['Society']['authorised_person']) ? $memberbillReport['Society']['authorised_person'] : 'Authorised Signature'; ?></div>
                                    <br>

                                    <div class="report-bill">Receipt</div>
                                    <div class="bill-outer-border">
                                        <?php if(!empty($memberReceipts)) {
                                            foreach($memberReceipts as $indReciept) {
                                                $receipt = $indReciept['MemberPayment']; ?>
                                            
                                        <div class="row bill-padding-10">
                                            <div class=""> 
                                                <div class="row1">
                                                    <div class="col-md-6 col-xs-7">Receipt No : <?php echo isset($receipt['receipt_id']) ? $receipt['receipt_id'] : '-'; ?> </div>
                                                    <div class="col-md-6 col-xs-5 text-right">Date : <?php echo isset($receipt['payment_date']) ? date('d/m/Y',strtotime($receipt['payment_date'])) : '-';?></div>
                                                </div>
                                                <div class="row1">
                                                    <div class="col-md-12 col-xs-12">Received with Thanks From : <strong><?php echo $memberbillReport['Member']['member_prefix'].' '.$memberbillReport['Member']['member_name'];?></strong></div>
                                                </div>                                            
                                                <div class="row1">
                                                        <div class="col-md-12 col-xs-12">Unit No: <strong><?php echo $memberbillReport['Member']['flat_no'];?></strong></div>
                                                </div>
                                                <div class="row1">
                                                    <div class="col-md-2 col-xs-2"> <strong>Rs. <?php echo isset($receipt['amount_paid']) ? $receipt['amount_paid'] : '-'; ?></strong></div>   
                                                    <div class="col-md-10 col-xs-10">Sum of (Rupees <?php echo $utilObj->convertToWords(abs($receipt['amount_paid'])); ?> Only)</div>                                                
                                                </div> 
                                                <div class="row1">
                                                    <?php if($societyDetails['SocietyParameter'][0]['show_bills_in_receipt'] == 1) {?>
                                                    <div class="col-md-12">Towards Bill No : <strong><?php echo isset($memberbillReport['MemberBillSummary']['bill_no']) ? $memberbillReport['MemberBillSummary']['bill_no'] :'' ; ?></strong>, Bill Date: <strong><?php echo date('d/m/Y',strtotime($billDate)); ?></strong></div>
                                            <?php } ?>
                                                    <div class="col-md-12">By Cheque No : <strong><?php echo isset($receipt['cheque_reference_number']) ? $receipt['cheque_reference_number'] : '-';?></strong> &nbsp;&nbsp;&nbsp; Dated on : <strong><?php if(!empty($receipt['credited_date']) && $receipt['credited_date'] != '0000-00-00') { echo date('d/m/Y',strtotime($receipt['credited_date'])); } else { echo '-';}?></strong></div>
                                                    <div class="col-md-12">Drawn On : <strong><?php echo isset($receipt['member_bank_id']) ? $societyMemberBankLists[$receipt['member_bank_id']] : '-';?></strong>,</div>
                                                </div> 
                                                <div class="clearfix"></div> <br>

                                                <div class="col-md-12">This Receipt is Valid Subject to realisation of cheque..</div>

                                            </div>
                                        </div>
                                        <?php }
                                        } else {
                                    ?>
                                        <div class="row bill-padding-10">
                                            <div class="row1" style="padding-left:10px;">No receipts available.</div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row1">
                                        <div class="col-md-12 text-right">For <?php echo isset($memberbillReport['Society']['society_name']) ? $memberbillReport['Society']['society_name'] : ''; ?></div>
                                        <div class="clearfix"></div>  <br><br>
                                        <div class="col-md-12 text-right"><?php echo isset($memberbillReport['Society']['authorised_person']) ? $memberbillReport['Society']['authorised_person'] : 'Authorised Signature'; ?></div>                                        
                                    </div>
                                </div>
                            <?php } }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
