<?php?>
<!-- Modal for Print Bill -->
<div class="modal fade" id="Print_Member_Bill" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title modal-title-color">Account Master / Ledger Heads</h6>
            </div>

            <div class="modal-body modal-body-bg">
                <div id="modal_ledger_notify_error"></div>
                 <?php echo $this->Session->flash(); ?>
				  <?php foreach($printBillDetails as $memberId=>$printBill) {
                                $societyDetails = $printBill['memberDetails']['Society'];
                                $memberDetails = $printBill['memberDetails']['Member'];
                                $buildingDetails = $printBill['memberDetails']['Building'];
                                $wingDetails = $printBill['memberDetails']['Wing'];
                                $billSummary = $printBill['MemberBillSummary'];
                                $tariff = $printBill['tariff'];
                                $memberReceipts = $printBill['receipts'];
                            ?>                            
                                <div class="row">
                                    <h5 class="text-center"><?php echo $societyDetails['society_name'];?></h5>
                                    <div class="address-heading">Registration No. <?php echo $societyDetails['registration_no'];?> Date: <?php echo date('d/m/Y',strtotime($societyDetails['registration_date']));?></div>
                                    <div class="address-heading"><?php echo $societyDetails['address'];?></div>
                                </div>
                                <div class="row">
                                    <div class="bill">BILL</div>
                                    <div class="bill-outer-border">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="">Unit No : <?php echo $memberDetails['flat_no'];?> </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="">Unit Area : <?php echo $memberDetails['area'];?> SqFt</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="">Unit type : <?php echo $memberDetails['unit_type'];?></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="">Name : <?php echo $memberDetails['member_prefix'].' '.$memberDetails['member_name'];?></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="">Bill For : <?php echo $billSummary['month'];?></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="">Building : <?php echo $buildingDetails['building_name'];?></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="">Wing : <?php echo $wingDetails['wing_name'];?></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="">Floor No : <?php echo $memberDetails['floor_no'];?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="">Bill No : <?php echo $billSummary['bill_no'];?></div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="">Bill Date : <?php echo date('d/m/Y',strtotime($billSummary['bill_generated_date']));?></div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="">Due Date : <?php echo date('d/m/Y',strtotime($billSummary['bill_due_date']));?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div style="padding: 0px;">
                                                <div class="table-wrap">
                                                    <div class="table-responsive1">
                                                        <table id="datable_1" class="table padding-td-none table-bordered display  pb-30" >
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr.</th>
                                                                    <th colspan="3" >Particular of Charges</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <?php 
                                                                $tariffSr = 1;
                                                                foreach($tariff as $key=>$indTariff) {
                                                                $ledgerHead = $ledgerHeadDetails[$indTariff['MemberBillGenerate']['ledger_head_id']];
                                                                $ledgerAmount = $indTariff['MemberBillGenerate']['amount'];
                                                                echo "<tr>
                                                                        <td>$tariffSr</td>
                                                                        <td  colspan=\"3\" >$ledgerHead</td>
                                                                        <td>$ledgerAmount</td>
                                                                        </tr>";
                                                                $tariffSr++;
                                                            } ?>
                                                            
                                                            <tr>
                                                                <td  width="20%" colspan="2" rowspan="6" style="background:none;border-right:1px solid lime;"> E.&.O.E </td>
                                                                <td  colspan="2" >Sub Total</td>
                                                                <td><?php echo $billSummary['monthly_amount'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Adjustment Credit / Rebate</td>
                                                                <td><?php echo $billSummary['principal_adjusted']+$billSummary['interest_adjusted'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Interest On Arrears</td>
                                                                <td><?php echo $billSummary['interest_on_due_amount'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Previous Arrears</td>
                                                                <td rowspan="3">
                                                                    <?php if($billSummary['op_due_amount'] < 0) { echo str_replace('-','',$billSummary['op_due_amount']).' Cr'; }
                                                                    else { echo $billSummary['op_due_amount'].' Dr'; }                                                                    
                                                                    ?>
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <td>Principal</td>
                                                                <td><?php echo $billSummary['op_principal_arrears'];?></td>                                                                
                                                            </tr>
                                                            <tr>
                                                                <td>Interest</td>
                                                                <td><?php echo $billSummary['op_interest_arrears'];?></td>                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" >Rupees <?php echo ucwords(numberTowords($billSummary['balance_amount']));?> Only</td>
                                                                <td colspan="2" >Excess Amount Received </td>
                                                                <td><?php  if($billSummary['balance_amount'] < 0) { echo str_replace('-','',$billSummary['balance_amount']).' Cr'; }
                                                                    else { echo $billSummary['balance_amount'].' Dr'; } ?></td>
                                                            </tr>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>     

                                        <div class="row" style="padding:8px;">
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
                                            <div class="col-md-8">Received with thanks from <strong><?php echo $memberDetails['member_prefix'].' '.$memberDetails['member_name'];?></strong></div>                                                
                                            <div class="col-md-4">Unit No :<strong> <?php echo $memberDetails['flat_no'];?></strong></div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-8">Details of payments received are as under :-</div>
                                            <?php if(isset($receiptPeriod['From']) && !empty($receiptPeriod['From']) && isset($receiptPeriod['To']) && !empty($receiptPeriod['To'])) { ?>
                                            <div class="col-md-4" style="float:right;">Period : <?php echo date('d/m/Y',strtotime($receiptPeriod['From']));?> To <?php echo date('d/m/Y',strtotime($receiptPeriod['To']));?></div>
                                            <?php } else { ?>
                                            <div class="col-md-4" style="float:right;">Period : 01/04/2018 To 31/03/2019</div>
                                            <?php } ?>
                                        </div> 
                                        <div class="row">
                                            <div style="padding: 0px;">
                                                <div class="table-wrap">
                                                    <div class="table-responsive">
                                                        <table id="datable_1" class="table table-bordered display padding-td-none pb-30" >
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
                                                                $receiptSr = 1;
                                                                $memberTotalPaid = 0;
                                                                
                                                                foreach($memberReceipts as $key=>$receipt) {
                                                                    $receiptDetails = $receipt['MemberPayment'];
                                                                    $paymentDate = date('d/m/Y',strtotime($receiptDetails['payment_date']));
                                                                    $entryDate = date('d/m/Y',strtotime($receiptDetails['payment_date']));
                                                                    
                                                                    echo "<tr id=\"\">
                                                                            <td>$receiptDetails[receipt_id]</td>
                                                                            <td>$entryDate</td>
                                                                            <td>$receiptDetails[payment_mode],$receiptDetails[cheque_reference_number]</td>
                                                                            <td>$paymentDate</td>
                                                                            <td>".$banks[$receiptDetails['member_bank_id']].", $receiptDetails[member_bank_branch]</td>
                                                                            <td>$receiptDetails[bill_generated_id], Bill Date: -</td>
                                                                            <td>".number_format($receiptDetails[amount_paid])."</td>
                                                                           </tr>";   
                                                                    
                                                                    $memberTotalPaid += $receiptDetails['amount_paid'];
                                                                    $receiptSr++;
                                                                } ?>                                                                                                                                
                                                                <tr id="">
                                                                    <td colspan="5" >Rupees <?php echo ucwords(numberTowords($memberTotalPaid));?> only</td>
                                                                    <td>Total</td>
                                                                    <td><?php echo number_format($memberTotalPaid); ?></td>
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
                                                <p class="pull-right">for <?php echo $societyDetails['society_name'];?> </p>
                                                <br> <br> <br>
                                                <p class="pull-right">Authorized Signature</p>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <center><button type="button" class="btn btn-success  btn-icon left-icon" onclick="javascript:window.print();"> 
                                        <i class="fa fa-print"></i><span> Print</span> 
                                    </button></center>
                                </div>
                            <?php } ?>
            </div>
        </div>
    </div>
</div>


<?php 
function numberTowords($num)
{
        $num = str_replace('-','',$num);
        
        error_reporting(0);
        $ones = array( 
        1 => "one", 
        2 => "two", 
        3 => "three", 
        4 => "four", 
        5 => "five", 
        6 => "six", 
        7 => "seven", 
        8 => "eight", 
        9 => "nine", 
        10 => "ten", 
        11 => "eleven", 
        12 => "twelve", 
        13 => "thirteen", 
        14 => "fourteen", 
        15 => "fifteen", 
        16 => "sixteen", 
        17 => "seventeen", 
        18 => "eighteen", 
        19 => "nineteen" 
        ); 
        $tens = array( 
        1 => "ten",
        2 => "twenty", 
        3 => "thirty", 
        4 => "forty", 
        5 => "fifty", 
        6 => "sixty", 
        7 => "seventy", 
        8 => "eighty", 
        9 => "ninety" 
        ); 
        $hundreds = array( 
        "hundred", 
        "thousand", 
        "million", 
        "billion", 
        "trillion", 
        "quadrillion" 
        ); //limit t quadrillion 
        $num = number_format($num,2,".",","); 
        $num_arr = explode(".",$num); 
        $wholenum = $num_arr[0]; 
        $decnum = $num_arr[1]; 
        $whole_arr = array_reverse(explode(",",$wholenum)); 
        krsort($whole_arr); 
        $rettxt = ""; 
        foreach($whole_arr as $key => $i){ 
        if($i < 20){ 
        $rettxt .= $ones[$i]; 
        }elseif($i < 100){ 
        $rettxt .= $tens[substr($i,0,1)]; 
        $rettxt .= " ".$ones[substr($i,1,1)]; 
        }else{ 
        $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        $rettxt .= " ".$tens[substr($i,1,1)]; 
        $rettxt .= " ".$ones[substr($i,2,1)]; 
        } 
        if($key > 0){ 
        $rettxt .= " ".$hundreds[$key]." "; 
        } 
        } 
        if($decnum > 0){ 
        $rettxt .= " and "; 
        if($decnum < 20){ 
        $rettxt .= $ones[$decnum]; 
        }elseif($decnum < 100){ 
        $rettxt .= $tens[substr($decnum,0,1)]; 
        $rettxt .= " ".$ones[substr($decnum,1,1)]; 
        } 
        } 
        return $rettxt; 
} 
?>
