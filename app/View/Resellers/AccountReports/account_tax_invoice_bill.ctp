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
                            <div id="society_print_gst_bill">                                 
                                    <?php 
                                    if(isset($taxBillsSummaryDetails) && !empty($taxBillsSummaryDetails)){
                                    foreach($taxBillsSummaryDetails as $memberbillReport) {
                                    ?> 
                                <div class="print-gst-bill">    
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($memberbillReport['Society']['society_name']) ? $memberbillReport['Society']['society_name'] : ''; ?></h5>
                                        <div class="address-heading">Registration No. <?php echo isset($memberbillReport['Society']['registration_no']) ? $memberbillReport['Society']['registration_no'] : ''; ?> Dated: <?php echo isset($memberbillReport['Society']['registration_date']) ? $memberbillReport['Society']['registration_date'] : ''; ?></div>
                                        <div class="address-heading"><?php echo isset($memberbillReport['Society']['address']) ? $memberbillReport['Society']['address'] : ''; ?></div>
                                    </div>
                                    <div class="row1">
                                        <div class="bill">BILL</div>
                                        <div class="bill-outer-border">
                                            <div class="row">
                                                <div class="col-md-8 col-xs-8">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                                            <div class="">Unit No : <?php echo isset($memberbillReport['Member']['flat_no']) ? $memberbillReport['Member']['flat_no'] :'' ; ?></div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                                            <div class="">Unit Area : <?php echo isset($memberbillReport['Member']['area']) ? $memberbillReport['Member']['area'] :'' ; ?> SqFt</div>
                                                        </div>

                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="">Name : <?php echo $memberbillReport['Member']['member_prefix'].' '.$memberbillReport['Member']['member_name'];?></div>
                                                        </div>
                                                        
                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="">Bill For : <?php echo isset($memberbillReport['MemberBillSummary']['monthName']) ? $memberbillReport['MemberBillSummary']['monthName'] :'' ; ?></div>
                                                        </div>
                                                    </div>                                       
                                                </div> 
                                                <div class="col-md-4 col-xs-4">
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="">Bill No : <?php echo isset($memberbillReport['MemberBillSummary']['bill_no']) ? $memberbillReport['MemberBillSummary']['bill_no'] :'' ; ?></div>
                                                        </div>
                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="">Bill Date : <?php echo isset($memberbillReport['MemberBillSummary']['bill_generated_date']) ? $memberbillReport['MemberBillSummary']['bill_generated_date'] :'' ; ?></div>
                                                        </div>
                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="">Due Date :  <?php echo isset($memberbillReport['MemberBillSummary']['bill_due_date']) ? $memberbillReport['MemberBillSummary']['bill_due_date'] :'' ; ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">Member GSTIN : <?php echo isset($memberbillReport['Member']['GSTIN']) ? $memberbillReport['Member']['GSTIN'] :'-' ; ?></div>
                                            <div class="row1">
                                                <div style="padding: 0px;">
                                                    <div class="table-wrap">
                                                        <div class="table-responsive1">
                                                            <table id="datable_1" class="table padding-td-none padding-th-none table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">Sr.</th>
                                                                        <th colspan="3" >Particular of Charges</th>
                                                                        <th class="text-center">Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <?php 
                                                                    $tariffSr = 1;
                                                                    $noGstTaxAmt = $gstTaxAmt = 0;
                                                                    foreach($memberbillReport['noTaxGst']['MemberTariff'] as $key => $indTariff) {
                                                                    if($indTariff['amount'] > 0) {	
                                                                            $ledgerHead = $indTariff['title'];
                                                                            $ledgerAmount = $indTariff['amount'];
                                                                            $noGstTaxAmt += $ledgerAmount;
                                                                            echo "<tr>
                                                                                    <td style=\"text-align:center;\">$tariffSr</td>
                                                                                    <td  colspan=\"3\" >$ledgerHead</td>
                                                                                    <td style=\"width:10%\"; class=\"text-right\">$ledgerAmount</td>
                                                                                    </tr>";
                                                                            $tariffSr++;
                                                                    }
                                                                } ?>
                                                                <tr>
                                                                    <td colspan="2" rowspan="1" style="width:5% !important;"> </td>
                                                                    <td colspan="2">Total</td>
                                                                    <td class="text-right" style="border-top:2px solid #000 !important;border-bottom:2px solid #000 !important;"><?php echo $utilObj->NumberFormat2Decimal($noGstTaxAmt);?></td>
                                                                </tr>
                                                                <?php 
                                                                    $tariffSr = 1 ;
                                                                    
                                                                    foreach($memberbillReport['taxGst']['MemberTariff'] as $key => $indTariff) {
                                                                    if($indTariff['amount'] > 0) {	
                                                                            $ledgerHead = $indTariff['title'];
                                                                            $ledgerAmount = $indTariff['amount'];
                                                                            $gstTaxAmt += $ledgerAmount;
                                                                            echo "<tr>
                                                                                    <td style=\"text-align:center;\">$tariffSr</td>
                                                                                   <td colspan=\"3\" >$ledgerHead</td>
                                                                                    <td style=\"width:10%;\" class=\"text-right\">$ledgerAmount</td>
                                                                                    </tr>";
                                                                            $tariffSr++;
                                                                    }
                                                                } ?>
                                                                <tr>
                                                                    <td colspan="2" rowspan="1" style="width:5% !important;"> </td>
                                                                    <td colspan="2">Total</td>
                                                                    <td style="border-top:2px solid #000 !important;border-bottom:2px solid #000 !important;" class="text-right"><?php echo $utilObj->NumberFormat2Decimal($gstTaxAmt); ?></td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td style="text-align:center;"></td>
                                                                    <td colspan="3">IGST @9%</td>
                                                                    <td style="width:10%;" class="text-right"><?php echo $memberbillReport['MemberBillSummary']['igst_total'];?></td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td style="text-align:center;"></td>
                                                                    <td colspan="3">CGST @9%</td>
                                                                    <td style="width:10%;" class="text-right"><?php echo $memberbillReport['MemberBillSummary']['cgst_total'];?></td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td style="text-align:center;"></td>
                                                                    <td colspan="3">SGCT @ 9%</td>
                                                                    <td style="width:10%;" class="text-right"><?php echo $memberbillReport['MemberBillSummary']['sgst_total'];?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" rowspan="1" style="width:5% !important;"> </td>
                                                                    <td colspan="2">Total</td>
                                                                    <td class="text-right" style="border-top:2px solid #000 !important;border-bottom:2px solid #000 !important;"><?php echo $memberbillReport['MemberBillSummary']['tax_total']?></td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td style="text-align:center;"></td>
                                                                    <td colspan="3">ARREARS / CREDIT </td> 
                                                                    <td style="width:10%;" class="text-right"><?php echo $memberbillReport['MemberBillSummary']['op_due_amount']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" rowspan="1" style="width:5% !important;"> </td>
                                                                    <td colspan="2">INTEREST ON ARREARS @21% P.A.</td>
                                                                    <td class="text-right"><?php echo $memberbillReport['MemberBillSummary']['interest_on_due_amount']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" rowspan="1" style="width:5% !important;"> </td>
                                                                    <td colspan="2">Discount</td>
                                                                    <td class="text-right"><?php echo$memberbillReport['MemberBillSummary']['discount']; ?></td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td style="text-align:center;"></td>
                                                                    <td colspan="3"><?php  if($memberbillReport['MemberBillSummary']['balance_amount'] < 0) { echo "Excess Amount Received";  } else { echo "<strong>Total Due & Payable Amount</strong>"; } ?></td>                                                                
                                                                    <td style="width:10%;" class="text-right"><?php  if($memberbillReport['MemberBillSummary']['balance_amount'] < 0) { echo str_replace('-','',$memberbillReport['MemberBillSummary']['balance_amount']).' Cr'; }
                                                                            else {echo $memberbillReport['MemberBillSummary']['balance_amount'].' Dr'; } ?>
                                                                    </td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td colspan="5" >Rupees <?php echo $utilObj->convertToWords($memberbillReport['MemberBillSummary']['balance_amount']);?> Only</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>     
                                            <div class="clearfix"></div>
                                            <ul>
                                                <li>1. Pleas Issue A/c Payee Chequs Only.</li>
                                                <li>2. Interest Will be Charged @ <?php echo $societyParameters['SocietyParameter']['interest_rate']; ?> % p.a .after due date.</li>
                                                <li>3. Please Issue Cheque in favour of "<?php echo isset($memberbillReport['Society']['society_name']) ? $memberbillReport['Society']['society_name'] : ''; ?>".</li>
                                                <li>4. Part Payament Will not be Accepted</li>                                                   
                                            </ul>  
                                            <br>
                                            <div class="clearfix"></div>
                                            <div class="col-md-5 col-xs-5">
                                                <div>GSTIN : <strong><?php echo isset($memberbillReport['Society']['gstin_no']) ? $memberbillReport['Society']['gstin_no'] : ''; ?></strong></div>
                                                <div>State : Maharastra</div>
                                                <div>S. A .C : 9995</div>
                                            </div> 
                                            <div class="col-md-7 col-xs-7">
                                                <div class="pull-right"><strong><?php echo isset($memberbillReport['Society']['society_name']) ? $memberbillReport['Society']['society_name'] : ''; ?></strong></div><br>
                                                <br><div class="pull-right"><?php echo isset($memberbillReport['Society']['authorised_person']) ? $memberbillReport['Society']['authorised_person'] : 'Authorised Signature'; ?></div>
                                            </div>
                                        </div>
                                        <br>
                                    </div>

                                </div>
                                <?php } }?>
                            </div>
                            <center>
                                <button type="button" class="btn btn-success btn-icon left-icon" onclick="printAllReports('society_print_gst_bill');"><i class="fa fa-print"></i><span> Print</span></button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>