<?php
$paymentModeArray = array(1=>'Cash',3=>'Cheque',2=>'NEFT',4=>'Other');

$monthArray = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December',);



?>
<div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close"><a href="<?php echo Router::url(array('controller' =>'societys_members','action' =>'dashboard')); ?>">&times;</a></button>
                <h6 class="modal-title modal-title-color">Member Receipt</h6>
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

                                                <?php 
                                                    $memberReceipts = $memberPaymentDetails;//print_r($memberReceipts);
                                                ?>
                                                <div class="society-print-bill">                                        
                                                    <div class="bill-outer-border">

                                                        <div class="row bill-padding-5">
                                                            <div class="receipt-title">RECEIPT</div>
                                                            <div class="col-md-8 col-xs-8">Received with thanks from <strong><?php echo $memberReceipts[0]['Member']['member_prefix'].' '.$memberReceipts[0]['Member']['member_name'];?></strong></div>
                                                            <div class="col-md-4 col-xs-4 text-right">Flat No :<strong> <?php echo $memberReceipts[0]['Member']['flat_no'];?></strong></div>
                                                            <div class="clearfix"></div>
                                                            <div class="col-md-12">Details of payments received are on : <strong><?php echo date('d/m/Y',strtotime($memberReceipts[0]['MemberPayment']['payment_date']));?></strong></div>
                                                        </div>
                                                        <div class="row1">
                                                            <div style="padding: 0px;">
                                                                <div class="table-wrap">
                                                                    <div class="table-responsive">
                                                                        <table  class="table padding-th-none-zero padding-td-none-zero table-bordered print-custom-table-border">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="width:6%;" class="text-center">Receipt</th>
                                                                                    <th style="width:10%;" class="text-center">Date</th>
                                                                                    <th style="width:8%;" class="text-center">Chq No.</th>
                                                                                    <th style="width:10%;" class="text-center">Chq Date</th>
                                                                                    <th class="text-center">Bank & Branch</th>
                                                                                    <?php if($showBillsInReceipt == 1) {?><th style="width:18%;" class="text-center">Towards bill No.</th><?php } ?>
                                                                                    <th style="width:8%;" class="text-center">Amount</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php
                                                                            $receiptSr = 1;
                                                                            $memberTotalPaid = 0;

                                                                            foreach($memberReceipts as $key=>$receipt) {
            //if ($receiptSr > 1) 
            //break;                                                                    
            $receiptDetails = $receipt['MemberPayment'];
                                                                                $paymentDate = date('d/m/Y',strtotime($receiptDetails['payment_date']));
                                                                                $entryDate = date('d/m/Y',strtotime($receiptDetails['entry_date']));
                                                                                $chequeDate = (!is_null($receiptDetails['entry_date']) && !empty($receiptDetails['entry_date'])) ? date('d/m/Y',strtotime($receiptDetails['entry_date'])) : '';
                                                                                if (!is_null($receiptDetails['entry_date']) && !empty($receiptDetails['entry_date'])){

                                                                                    if (strtotime($chequeDate) > strtotime($paymentDate)) {
                                                                                      $chequeDate = $paymentDate;
                                                                                    }
                                                                                }
                                                                                $billFor = $societyObj->monthWordFormatByBillingFrequency($billSummary['month']);	// for $billFor removed from the bill generated id
                                                                                if (!empty($receiptDetails['cheque_reference_number']) && !empty($receiptDetails['credited_date']))
                                                                                    //$paymentDate = date('d/m/Y',strtotime($receiptDetails['credited_date']));
                                                                                $paymentMode = $paymentModeArray[$receiptDetails['payment_mode']];
                                                                                    $rcpt = '';
                                                                                    if($showBillsInReceipt == 1) { 
                                                                                            $rcpt = "<td class=\"text-center\">".$receiptDetails[bill_generated_id]."</td>"; 
                                                                                    } 
                                                                                echo "<tr id=\"\">
                                                                                        <td class=\"text-center\">$receiptDetails[receipt_id]</td>
                                                                                        <td class=\"text-center\">$paymentDate</td>
                                                                                        <td class=\"text-center\">$receiptDetails[cheque_reference_number]</td>
                                                                                        <td class=\"text-center\">$chequeDate</td>
                                                                                        <td>".$banks[$receiptDetails['member_bank_id']].", $receiptDetails[member_bank_branch]</td>
                                                                                        ".$rcpt."
                                                                                        <td class=\"text-right\">".number_format($receiptDetails[amount_paid])."</td>
                                                                                       </tr>";

                                                                                $memberTotalPaid += $receiptDetails['amount_paid'];
                                                                                $receiptSr++;
                                                                            } ?>
                                                                                <tr id="">
                                                                                    <td colspan="4" >&nbsp; Rupees <?php echo ucwords(numberTowords($memberTotalPaid));?> only</td>
                                                                                    <td class="font-weight-600">Total</td>
                                                                                    <td class="text-right"><?php echo number_format($memberTotalPaid); ?></td>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <div class="bill-padding-10">(Subject to Realisation of Cheque)</div>
                                                            </div>
                                                            <div class="col-md-7">
                                                                <p class="pull-right">for <?php echo $societyDetails['society_name'];?>&nbsp;&nbsp; </p>
                                                                <br> <br> <br>
                                                                <p class="pull-right"><?php echo isset($societyDetails['authorised_person']) ? $societyDetails['authorised_person'] : 'Authorised Signature'; ?>&nbsp;&nbsp;</p>
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
