<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">GST Register</h6>
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
                                                <button type="submit" data-toggle="tooltip" data-original-title="submit" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printAllReports('print_gst_register');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>
                                                <?php 
                                                    $month = isset($postData['MemberPayment']['month']) && !empty($postData['MemberPayment']['month']) ? base64_encode($postData['MemberPayment']['month']) : '';
                                                    $month_to = isset($postData['MemberPayment']['month_to']) && !empty($postData['MemberPayment']['month_to']) ? base64_encode($postData['MemberPayment']['month_to']) : '';
                                                    $bill_date = isset($postData['MemberBillSummary']['bill_generated_date']) && !empty($postData['MemberBillSummary']['bill_generated_date']) ? base64_encode($postData['MemberBillSummary']['bill_generated_date']) : '';
                                                    $bill_date_to = isset($postData['MemberBillSummary']['bill_generated_date_to']) && !empty($postData['MemberBillSummary']['bill_generated_date_to']) ? base64_encode($postData['MemberBillSummary']['bill_generated_date_to']) : '';
                                                    $receiptID = isset($postData['MemberPayment']['receipt_id']) && !empty($postData['MemberPayment']['receipt_id']) ? base64_encode($postData['MemberPayment']['receipt_id']) : '';
                                                    $receiptIdTo = isset($postData['MemberPayment']['receipt_id_to']) && !empty($postData['MemberPayment']['receipt_id_to']) ? base64_encode($postData['MemberPayment']['receipt_id_to']) : '';
                                                    $building_id = isset($postData['MemberPayment']['building_id']) && !empty($postData['MemberPayment']['building_id']) ? base64_encode($postData['MemberPayment']['building_id']) : '';
                                                    $flat_no = isset($postData['MemberPayment']['flat_no']) && !empty($postData['MemberPayment']['flat_no']) ? base64_encode($postData['MemberPayment']['flat_no']) : '';
                                                    $flat_no_to = isset($postData['MemberPayment']['flat_no_to']) && !empty($postData['MemberPayment']['flat_no_to']) ? base64_encode($postData['MemberPayment']['flat_no_to']) : '';
                                                    $wing_id = isset($postData['MemberPayment']['wing_id']) && !empty($postData['MemberPayment']['wing_id']) ? base64_encode($postData['MemberPayment']['wing_id']) : '';
                                                    echo $this->Html->link('<i class="fa fa-download" style="color:#fff;font-size:10px;"></i> Export to Excel', array('controller' => 'excels_report', 'action' => 'download_account_gst_register', '?' => array('bill_generated_date_to' => $bill_date_to, 'bill_generated_date' => $bill_date, 'month' => $month, 'month_to' => $month_to, 'receipt_id' => $receiptID, 'receipt_id_to' => $receiptIdTo,'building_id' => $building_id,'wing_id' => $wing_id,'flat_no' => $flat_no,'flat_no_to' => $flat_no_to)), array('class' => 'btn btn-warning btns', 'data-toggle' => 'tooltip', 'data-original-title' => 'Member Collection Register', 'escape' => false, 'target' => '_blank'));
                                                ?>
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
                            <div id="print_gst_register">
                            <div class="print-gst-register">
                                <div class="row">
                                    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                </div>
                                <br>
                                <div class="report-bill">GST Register</div>
                                <div class="report-bill-outer-section">
                                    <div class="table-wrap1">
                                        <table id="" class="table table-bordered padding-td-none padding-th-none">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Unit No</th>
                                                    <th>Bill No</th>
                                                    <th>Member Name</th>
                                                    <th>Bill Date</th>
                                                    <?php foreach($uniqueTariffsInBills as $billLedgerId=>$billLedgerTitle) {
                                                        echo "<th>".substr($billLedgerTitle,0,5)."</th>";
                                                    } ?>
                                                    <th>Bill Amount</th>
                                                    <th>SGST @<?php echo isset($societyParameters['SocietyParameter']['sgst_tax_per']) ? $societyParameters['SocietyParameter']['sgst_tax_per'] : ''; ?>%</th>
                                                    <th>CGST @<?php echo isset($societyParameters['SocietyParameter']['cgst_tax_per']) ? $societyParameters['SocietyParameter']['cgst_tax_per'] : ''; ?>%</th>
                                                    <th>Net Tax Amount</th>
                                                    <th>GST NO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $tarrifVerticalTotal = array();
                                                $monthlyAmtTotal = $billAmtTotal =  $cgstAmtTotal = $sgstAmtTotal = $totalTaxAmtTotal = 0.00;
                                                $sr=1;
                                                foreach($gstRegisterData as $billId=>$billDetails) {
                                                    $billSummary = $billDetails['MemberBillSummary'];
                                                    $billTariffs = $billDetails['MemberBillGenerate'];
                                                    $memberDetails = $membersArray[$billSummary['member_id']];
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $sr; ?></td>
                                                    <td class="text-center"><?php echo $memberDetails['flat_no']; ?></td>
                                                    <td class="text-center"><?php echo $billSummary['bill_no']; ?></td>
                                                    <td><?php echo $memberDetails['member_prefix'].''.$memberDetails['member_name']; ?></td>
                                                    <td><?php echo $utilObj->getFormatDate($billSummary['bill_date'],'d/m/Y'); ?></td>
                                                    <?php $billAmount = $sgstTotal = $cgstTotal = 0.00 ; 
                                                    foreach($uniqueTariffsInBills as $billLedgerId=>$billLedgerTitle) {
                                                        $amount = 0.00;
                                                        if(isset($billTariffs[$billLedgerId])){
                                                           $amount = $billTariffs[$billLedgerId]['amount'];
                                                           $sgstTotal += $billTariffs[$billLedgerId]['sgst'];
                                                           $cgstTotal += $billTariffs[$billLedgerId]['cgst'];
                                                           $billAmount += $amount;
                                                           $tarrifVerticalTotal[$billLedgerId]['amount'] = isset($tarrifVerticalTotal[$billLedgerId]['amount']) ? $tarrifVerticalTotal[$billLedgerId]['amount'] + $amount : $amount;
                                                           
                                                        }   
                                                        echo "<td class=\"text-right\">$amount</td>";
                                                    }
                                                    $billAmtTotal += $billAmount;
                                                    $cgstAmtTotal += $cgstTotal;
                                                    $sgstAmtTotal += $sgstTotal;
                                                    $totalTaxAmtTotal = $sgstAmtTotal + $cgstAmtTotal;
                                                    ?>
                                                    <td class="text-right"><?php echo $billAmount; ?></td>
                                                    <td class="text-right"><?php echo $sgstTotal; ?></td>
                                                    <td class="text-right"><?php echo $cgstTotal; ?></td>
                                                    <td class="text-right"><?php echo ($sgstTotal + $cgstTotal); ?></td>
                                                    <td class="text-right"><?php echo isset($memberDetails['gstin_no']) ? $memberDetails['gstin_no'] : ''; ?></td>
                                                </tr> 
                                                <?php $sr++;}?> 
                                            </tbody>
                                            <tfoot id=""> 
                                                <tr>                                                                       
                                                    <td colspan="5" class="text-right" style="font-weight:bold;">Grand Total:</td>
                                                    <?php 
                                                    foreach($tarrifVerticalTotal as $billLedgerId=>$amountTariff) {
                                                        echo "<td class=\"text-right\" style=\"font-weight:bold;\">".number_format((float)$amountTariff['amount'],2,'.','')."</td>";
                                                    } 
                                                    ?>
                                                    <td class="text-right" style="font-weight:bold;"><?php echo $billAmtTotal; ?></td>
                                                    <td class="text-right" style="font-weight:bold;"><?php echo $sgstAmtTotal; ?></td>
                                                    <td class="text-right" style="font-weight:bold;"><?php echo $cgstAmtTotal; ?></td>
                                                    <td class="text-right" style="font-weight:bold;"><?php echo $totalTaxAmtTotal; ?></td>
                                                    <td class="text-right" style="font-weight:bold;"></td>
                                                </tr>
                                            </tfoot>
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