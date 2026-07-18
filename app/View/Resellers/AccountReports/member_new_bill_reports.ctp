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
                            <div id="print_member_new_bill">
                                    <?php 
                                    if(isset($monthlyBillsSummaryDetails) && !empty($monthlyBillsSummaryDetails)){
                                    foreach($monthlyBillsSummaryDetails as $memberbillReport) {
                                    ?>   
                                <div class="new-bill-page-break"> 
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

                                            <div class="row1">
                                                <div style="padding: 0px;">
                                                    <div class="table-wrap">
                                                        <div class="table-responsive1">
                                                            <table id="datable_1" class="table padding-th-none-zero padding-td-none-zero table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">Sr.</th>
                                                                        <th colspan="3" >Particular of Charges</th>
                                                                        <th class="text-center">Amount</th>
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
                                                                    <td colspan="2" rowspan="5" style="width:5% !important;"> </td>
                                                                    <td colspan="2">Total</td>
                                                                    <td class="text-right"><?php echo $memberbillReport['MemberBillSummary']['monthly_amount']-$memberbillReport['MemberBillSummary']['tax_total'];?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">Add : Interest</td>
                                                                    <td class="text-right"><?php echo $memberbillReport['MemberBillSummary']['tax_total'];  ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">Less : Adjustment</td>
                                                                    <td class="text-right"><?php echo $memberbillReport['MemberBillSummary']['principal_adjusted']+$memberbillReport['MemberBillSummary']['interest_adjusted'];?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Principal Arrears</td>
                                                                    <td class="text-right"><?php echo $memberbillReport['MemberBillSummary']['op_due_amount'];?></td>                                                                
                                                                    <td class="text-right" rowspan="2">
                                                                        <?php echo $memberbillReport['MemberBillSummary']['op_due_amount']+$memberbillReport['MemberBillSummary']['interest_on_due_amount'];?>
                                                                    </td>                                                                
                                                                </tr>
                                                                <tr>
                                                                    <td>Interest Arrears</td>                                                           
                                                                    <td class="text-right"><?php echo $memberbillReport['MemberBillSummary']['interest_on_due_amount'];?></td>                                                              
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3" >Rupees <?php echo $utilObj->convertToWords(abs($memberbillReport['MemberBillSummary']['balance_amount']));?> Only</td>
                                                                    <td colspan="1" class="text-right font-weight-bold"><?php  if($memberbillReport['MemberBillSummary']['balance_amount'] < 0) { echo "<span>Excess Amount Received</span>";  } else { echo "<strong>Total Due & Payable Amount</strong>"; } ?></td>                                                                
                                                                    <td class="text-right"><?php  if($memberbillReport['MemberBillSummary']['balance_amount'] < 0) { echo str_replace('-','',$memberbillReport['MemberBillSummary']['balance_amount']).' Cr'; }
                                                                            else {echo $memberbillReport['MemberBillSummary']['balance_amount'].' Dr'; } ?>
                                                                    </td>
                                                                </tr>

                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>     
                                            <div class="clearfix"></div>
                                            <div class="col-md-7 col-xs-7">
                                                <strong>E.&.O.E :</strong>
                                                <ul>
                                                    <li>Notes :Please pay by a/c paye chq. only.</li>
                                                    <li>Int. @ <?php echo $societyParameters['SocietyParameter']['interest_rate']; ?> % will be charged for payment not received before due date.</li>
                                                    <li>As this is computer generated bill no signature is required.</li>
                                                </ul>  
                                            </div>                                                   
                                            <div class="col-md-5 col-xs-5">
                                                <br>
                                                <div class="pull-right"><strong><?php echo isset($memberbillReport['Society']['society_name']) ? $memberbillReport['Society']['society_name'] : ''; ?></strong></div><br>
                                                <br><div class="pull-right"><?php echo isset($memberbillReport['Society']['authorised_person']) ? $memberbillReport['Society']['authorised_person'] : 'Authorised Signature'; ?></div>
                                            </div>
                                        </div>
                                    </div>                                 
                                </div>
                                    <?php } }?>   
                            </div>
                            <center>
                                <button type="button" class="btn btn-success  btn-icon left-icon" onclick="printAllReports('print_member_new_bill');"><i class="fa fa-print"></i><span> Print</span></button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
