<?php
$paymentModeArray = array(1=>'Cash',3=>'Cheque',2=>'NEFT',4=>'Other');

$monthArray = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December',);



?>
<div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close"><a href="<?php echo Router::url(array('controller' =>'societys_members','action' =>'dashboard')); ?>">&times;</a></button>
                <h6 class="modal-title modal-title-color">Member Bill</h6>
            </div>
            
            <div class="modal-body modal-body-bg"> 
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default card-view">
                        <?php echo $this->Session->flash(); ?>
                            <div class="panel-heading">
                                <div class="pull-left">
                                    <button type="button" class="btn btn-success btn-icon left-icon" onclick="printAllReports('society_print_bill');"><i class="fa fa-print"></i><span> Print</span></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-wrapper collapse in">
                                <div class="panel-body">
                                    <div class="form-wrap">
                                        <div id="show_notify_error">
                                        </div>
                                        <div id="society_print_bill">
                                            <div id="page-break1">

                                        <?php foreach($printBillDetails as $memberId=>$printBill) {
                                            $societyDetails = $printBill['memberDetails']['Society'];
                                            $memberDetails = $printBill['memberDetails']['Member'];
                                            $buildingDetails = $printBill['memberDetails']['Building'];
                                            $wingDetails = $printBill['memberDetails']['Wing'];
                                            $billSummary = $printBill['MemberBillSummary'];
                                            $tariff = $printBill['tariff'];
                                            $memberReceipts = $printBill['receipts'];
                                        ?>
                                        <div class="society-print-bill">                                        
                                        <div class="bill-outer-border">
                                            <div class="row">
                                                <h5 class="text-center"><?php echo $societyDetails['society_name'];?></h5>
                                                <div class="address-heading">Registration No. <?php echo $societyDetails['registration_no'];?> Dated: <?php echo date('d/m/Y',strtotime($societyDetails['registration_date']));?></div>
                                                <div class="address-heading"><?php echo $societyDetails['address'];?></div>
                                            </div>
                                            <div class="bill">Maintenance Bill</div>
                                            <div class="simple-border"></div>
                                            <div class="col-md-9 col-xs-9">
                                                <div class="row">
                                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                                        <div class=""><?php echo 'Unit No'; ?> : <strong><?php echo $memberDetails['flat_no'];?></strong> </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <div class="">Unit Area : <?php echo $memberDetails['area'];?> SqFt</div>
                                                    </div>
                                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                                        <div class="">Unit type : <?php if($memberDetails['unit_type'] == 'C') { echo 'Commercial';} else if($memberDetails['unit_type'] == 'R') { echo 'Residential';} else if($memberDetails['unit_type'] == 'B') { echo 'Both';} else { echo $memberDetails['unit_type']; }?></div>
                                                    </div>
                                                </div>                                                    
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="">Name &nbsp;&nbsp; : &nbsp;<?php echo $memberDetails['member_prefix'].' '.$memberDetails['member_name'];?></div>
                                                    </div>
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="">Bill For &nbsp; : &nbsp;<?php echo $societyObj->monthWordFormatByBillingFrequency($billSummary['month']);?> <?php echo $societyDetails['financial_year'];?></div>
                                                    </div>
                                                </div>                                                    
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <div class="">Wing &nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;<?php echo $wingDetails['wing_name'];?></div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <div class="">Floor No : <?php echo $memberDetails['floor_no'];?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-xs-3">
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class=""><strong>Bill No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;<?php echo $billSummary['bill_no'];?></strong></div>
                                                    </div>
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class=""><strong>Bill Date &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;<?php echo date('d/m/Y',strtotime($billSummary['bill_generated_date']));?></strong></div>
                                                    </div>
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class=""><strong>Due Date &nbsp;&nbsp;: &nbsp;&nbsp;<?php echo date('d/m/Y',strtotime($billSummary['bill_due_date']));?></strong></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row1">
                                                <div style="padding: 0px;">
                                                    <div class="table-wrap">
                                                        <div class="">
                                                            <table class="table table-print-all-bill padding-th-none-zero padding-td-none-zero table-bordered print-custom-table-border">
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
                                                                    foreach($tariff as $key=>$indTariff) {
                                                                    if($indTariff['MemberBillGenerate']['amount'] > 0) {
                                                                            $memberBillTariff[] = $indTariff['MemberBillGenerate']['ledger_head_id'];
                                                                            $ledgerHead = $ledgerHeadDetails[$indTariff['MemberBillGenerate']['ledger_head_id']];
                                                                            $ledgerAmount = $indTariff['MemberBillGenerate']['amount'];
                                                                            echo "<tr>
                                                                                    <td style='text-align:center;'>$tariffSr</td>
                                                                                    <td  colspan=\"3\" >&nbsp; $ledgerHead</td>
                                                                                    <td style='width:10%;' class='text-right'>";
                                                                            echo number_format($ledgerAmount,2);
                                                                            echo "</td>
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
                                    <td colspan="2" rowspan="5" style="width:50% !important;"><?php if(trim($billSummary['remarks']) != ''){ ?>&nbsp;&nbsp;<?php echo $billSummary['remarks'];?><?php } ?></td>
                                                                    <td colspan="2" >&nbsp; Total</td>
                                                                    <td class="text-right" style="font-weight:bold;"><b>
                                                                        <?php 
                                                                            $totalTariffAmount =  $billSummary['monthly_amount'] - $billSummary['tax_total'];
                                                                            echo number_format($totalTariffAmount,2);
                                                                        ?>
                                                                        </b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" >&nbsp; Interest</td>
                                                                    <td class="text-right"><?php echo number_format($billSummary['interest_on_due_amount'],2); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" >&nbsp; Less: Adjustment</td>
                                                                    <td class="text-right"><?php echo number_format($billSummary['principal_adjusted']+$billSummary['interest_adjusted']+$billSummary['discount'],2);?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td >&nbsp; Principal Arrears</td>                                                                    
                                                                    <td class="text-right" >
                                                                        <?php
                                                                        $totalArrears = $billSummary['op_principal_arrears'];
                                                                        if($totalArrears < 0) { $posNeg = 'Cr'; }
                                                                        else { $posNeg = 'Dr'; }
                                                                        $totalArrears = abs($totalArrears);
                                                                        echo number_format($totalArrears,2).' '.$posNeg;
                                                                        ?>                                                                      
                                                                    </td> 
                                                                    <td class="text-right" rowspan="2">
                                                                        <?php if($billSummary['op_due_amount'] < 0) { $posNeg = 'Cr'; }
                                                                        else { $posNeg = 'Dr'; }
                                                                        $billSummary['op_due_amount'] = abs($billSummary['op_due_amount']);
                                                                        echo number_format($billSummary['op_due_amount'],2).' '.$posNeg;
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp; Interest Arrears</td>
                                                                    <td class="text-right"><?php echo $utilObj->CreditDebitAmountCheck($billSummary['op_interest_arrears']) ;?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" >&nbsp; <span class='font-weight-bold'>Rupees <?php echo ucwords(numberTowords($billSummary['amount_payable']));?> Only</span></td>
                                                                    <td colspan="2" >
                                                                    <?php  if($billSummary['amount_payable'] < 0) { echo "<span class='font-weight-bold'>&nbsp; Excess Amount Received</span>";  } else { echo "<span class='font-weight-bold'>&nbsp; Total Due Amount & Payable</span>"; } ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                    <?php  if($billSummary['amount_payable'] < 0) { $posNeg = 'Cr'; }
                                                                    else { $posNeg = 'Dr'; }
                                                                    $billSummary['amount_payable'] = abs($billSummary['amount_payable']);

                                                                    echo "<span class='font-weight-bold'>".number_format($billSummary['amount_payable'],2).' '.$posNeg.'</span>';
                                                                    ?>
                                                                    </td>
                                                                </tr>

                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-xs-12">
                                                <strong>Notes: </strong>
                                                <div><?php echo $societyParameters['SocietyParameter']['bill_note']; ?> </div> 
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-12 col-xs-12">
                                                <p class="pull-right">for <?php echo $societyDetails['society_name'];?>&nbsp;&nbsp; </p>
                                                <br> <br>
                                                <p class="pull-right"><?php echo isset($societyDetails['authorised_person']) ? $societyDetails['authorised_person'] : 'Authorised Signature'; ?>&nbsp;&nbsp;</p>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-12 col-xs-12 special-field-text"><?php echo $societyParameters['SocietyParameter']['special_field']; ?></div>
                                            
                                        </div>
                                    </div>
                                <?php } ?>
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
