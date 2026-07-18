<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Bill Register</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="duesFromMemberFrm" method="post" id="duesFromMemberFrm" name="duesFromMemberFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">From Date &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][bill_generated_date]" value="<?php echo isset($postData['MemberBillSummary']['bill_generated_date']) ? $postData['MemberBillSummary']['bill_generated_date'] :'' ; ?>">
                                            </div> 
                                        </div>
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">To Date &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][bill_generated_date_to]" value="<?php echo isset($postData['MemberBillSummary']['bill_generated_date_to']) ? $postData['MemberBillSummary']['bill_generated_date_to'] :'' ; ?>">
                                            </div> 
                                        </div>
                                    </div>  
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printAllReports('print_bill_register');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>
                                                <?php echo $this->Html->link('<i class="fa fa-download" style="color:#fff;font-size:10px;"></i> Export to Excel', array('controller'=>'excels_report','action' => 'download_account_bill_register','?' => array('bill_generated_date_to' => isset($postData['MemberBillSummary']['bill_generated_date_to']) ? base64_encode($postData['MemberBillSummary']['bill_generated_date_to']) : '','bill_generated_date' => isset($postData['MemberBillSummary']['bill_generated_date']) ? base64_encode($postData['MemberBillSummary']['bill_generated_date']) : '')), array('class' => 'btn btn-success btns','data-toggle' => 'tooltip', 'data-original-title' => 'download bill register','escape' => false,'target' => '_blank')); ?>
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
                                                <select class="account-report-form-box-field-dropdown" id="" name="data[MemberPayment][building_id]" onchange="getAllBuildingWings(this.value, '#dues_from_member');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($postData['MemberPayment']['building_id']) && $postData['MemberPayment']['building_id'] == $buildingID){?>
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
                                                <select class="account-report-form-box-field-dropdown" id="dues_from_member" name="data[MemberPayment][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Unit No &nbsp;&nbsp;</div>                                   
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberPayment][flat_no]" value="<?php echo isset($postData['MemberPayment']['flat_no']) ? $postData['MemberPayment']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberPayment][flat_no_to]" value="<?php echo isset($postData['MemberPayment']['flat_no_to']) ? $postData['MemberPayment']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                    </div>    
                                </div>
                            </form>
                            <div class="clearfix"></div><br><br>
                            <div id="print_bill_register">
                                <div class="print-bill-register">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="report-bill">Bill Register</div>
                                        <div class="report-bill"></div> 
                                        <div class="report-bill-outer-section">
                                            <div class="table-wrap table-responsive">
                                                <table id="" class="table padding-td-none padding-th-none">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>Unit No</th>
                                                            <th>Member</th>
                                                            <th>For</th>
                                                            <th width="5%">Bill No</th>
                                                            <th>Bill Date</th>
                                                    <?php foreach($uniqueTariffsInBills as $billLedgerId=>$billLedgerTitle) {
                                                        echo "<th>".substr($billLedgerTitle,0,5)."</th>";
                                                    } ?>
                                                            <th>Amount</th>
                                                            <th>Int</th>
                                                            <th>Bill Amt</th>
                                                            <th>P_Arrear</th>
                                                            <th>I_Arrear</th>
                                                            <th>T_Arrear</th>
                                                            <th>Payable</th>
                                                            <th>Paid</th>
                                                            <th>Adjust</th>
                                                            <th>Bal.</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                <?php
                                                $tarrifVerticalTotal = array();
                                                $monthlyAmtTotal = $inteAmtTotal = $billAmtTotal = $prinArrTotal = $inteArrTotal = $taxArrTotal = $payAmtTotal = $paidAmtTotal = $adjuAmtTotal = $balanceAmtTotal = 0.00;
                                                $sr=1;
                                                foreach($billRegisterData as $billId=>$billDetails) {
                                                    $billSummary = $billDetails['MemberBillSummary'];                                                    
                                                    $monthlyAmtTotal +=$billSummary['monthly_amount'];
                                                    $inteAmtTotal +=$billSummary['interest_on_due_amount'];
                                                    $billAmtTotal +=$billSummary['monthly_bill_amount'];
                                                    $prinArrTotal +=$billSummary['op_principal_arrears'];
                                                    $inteArrTotal +=$billSummary['op_interest_arrears'];
                                                    $taxArrTotal +=$billSummary['op_tax_arrears'];
                                                    $payAmtTotal +=$billSummary['amount_payable'];
                                                    $billTariffs = $billDetails['MemberBillGenerate'];
                                                    $paidAmtTotal += $billDetails['amountPaid'];
                                                    $adjuAmtTotal +=($billSummary['interest_adjusted']+$billSummary['tax_adjusted']+$billSummary['principal_adjusted']+$billSummary['discount']);
                                                    $balanceAmtTotal +=$billSummary['amount_payable']-$billDetails['amountPaid']-($billSummary['interest_adjusted']+$billSummary['tax_adjusted']+$billSummary['principal_adjusted']);
                                                    $memberDetails = $membersArray[$billSummary['member_id']];
                                                ?>
                                                        <tr>
                                                            <td><?php echo $sr; ?></td>
                                                            <td><?php echo $memberDetails['flat_no']; ?></td>
                                                            <td><?php echo $memberDetails['member_prefix'].''.$memberDetails['member_name']; ?></td>
                                                            <td><?php echo $billSummary['month']; ?></td>
                                                            <td><?php echo $billSummary['bill_no']; ?></td>
                                                            <td><?php echo $utilObj->getFormatDate($billSummary['bill_generated_date'],'d/m/Y'); ?></td>

                                                    <?php foreach($uniqueTariffsInBills as $billLedgerId=>$billLedgerTitle) {
                                                        $amount = 0.00;
                                                        if(isset($billTariffs[$billLedgerId])) {
                                                           $amount = $billTariffs[$billLedgerId];
                                                           $tarrifVerticalTotal[$billLedgerId]['amount'] = isset($tarrifVerticalTotal[$billLedgerId]['amount']) ? $tarrifVerticalTotal[$billLedgerId]['amount'] + $amount : $amount;
                                                        }   
                                                        echo "<td class=\"text-right\">$amount</td>";
                                                    } ?>
                                                            <td class="text-right"><?php echo $billSummary['monthly_amount']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['interest_on_due_amount']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['monthly_bill_amount']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['op_principal_arrears']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['op_interest_arrears']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['op_tax_arrears']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['amount_payable']; ?></td>
                                                            <td class="text-right"><?php echo $billDetails['amountPaid']; ?></td>
                                                            <td class="text-right"><?php echo ($billSummary['interest_adjusted']+$billSummary['tax_adjusted']+$billSummary['principal_adjusted']); ?></td>
                                                            <td class="text-right"><?php echo ($billSummary['amount_payable']-$billDetails['amountPaid']-($billSummary['interest_adjusted']+$billSummary['tax_adjusted']+$billSummary['principal_adjusted'])); ?></td>
                                                        </tr> 
                                                <?php $sr++;}?>
                                                    </tbody>   
                                                    <tfoot id=""> 
                                                        <tr>                                                                       
                                                            <td colspan="6" class="text-right" style="font-weight:bold;">Grand Total:</td>
                                                    <?php 
                                                    foreach($tarrifVerticalTotal as $billLedgerId=>$amountTariff) {
                                                        echo "<td class=\"text-right\" style=\"font-weight:bold;\">".number_format((float)$amountTariff['amount'],2,'.','')."</td>";
                                                    } 
                                                    ?>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $monthlyAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $inteAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $billAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $prinArrTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $inteArrTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $taxArrTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $payAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $paidAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $adjuAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $balanceAmtTotal; ?></td>
                                                        </tr>
                                                    </tfoot>
                                                </table> 
                                            </div>
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