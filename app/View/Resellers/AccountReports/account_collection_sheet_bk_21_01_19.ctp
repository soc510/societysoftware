<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Collection Sheet</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="societyReportBillFrm" name="societyReportBillFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-9 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4">    
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">For the Period &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[CollectionSheet][payment_date]" value="<?php echo isset($postData['CollectionSheet']['payment_date']) ? $postData['CollectionSheet']['payment_date'] :'' ; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">    
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[CollectionSheet][payment_date_to]" value="<?php echo isset($postData['CollectionSheet']['payment_date_to']) ? $postData['CollectionSheet']['payment_date_to'] :'' ; ?>">
                                            </div>  
                                        </div>                                                                 
                                    </div> 
                                    <br>
                                    <div class="form-group society-report-form">
                                        <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                        <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
                                        <button type="button" class="btn btn-success btn-icon btns" onclick="printAllReports('print_collection_sheet');"><i class="fa fa-print"></i><span> Print</span></button>
                                            <?php
                                                    $month = isset($postData['CollectionSheet']['month']) && !empty($postData['CollectionSheet']['month']) ? base64_encode($postData['CollectionSheet']['month']) : '';
                                                    $month_to = isset($postData['CollectionSheet']['month_to']) && !empty($postData['CollectionSheet']['month_to']) ? base64_encode($postData['CollectionSheet']['month_to']) : '';
                                                    $bill_date = isset($postData['MemberBillSummary']['bill_generated_date']) && !empty($postData['MemberBillSummary']['bill_generated_date']) ? base64_encode($postData['MemberBillSummary']['bill_generated_date']) : '';
                                                    $bill_date_to = isset($postData['MemberBillSummary']['bill_generated_date_to']) && !empty($postData['MemberBillSummary']['bill_generated_date_to']) ? base64_encode($postData['MemberBillSummary']['bill_generated_date_to']) : '';
                                                    $receiptID = isset($postData['CollectionSheet']['receipt_id']) && !empty($postData['CollectionSheet']['receipt_id']) ? base64_encode($postData['CollectionSheet']['receipt_id']) : '';
                                                    $receiptIdTo = isset($postData['CollectionSheet']['receipt_id_to']) && !empty($postData['CollectionSheet']['receipt_id_to']) ? base64_encode($postData['CollectionSheet']['receipt_id_to']) : '';
                                                    $building_id = isset($postData['CollectionSheet']['building_id']) && !empty($postData['CollectionSheet']['building_id']) ? base64_encode($postData['CollectionSheet']['building_id']) : '';
                                                    $flat_no = isset($postData['CollectionSheet']['flat_no']) && !empty($postData['CollectionSheet']['flat_no']) ? base64_encode($postData['CollectionSheet']['flat_no']) : '';
                                                    $flat_no_to = isset($postData['CollectionSheet']['flat_no_to']) && !empty($postData['CollectionSheet']['flat_no_to']) ? base64_encode($postData['CollectionSheet']['flat_no_to']) : '';
                                                    $wing_id = isset($postData['CollectionSheet']['wing_id']) && !empty($postData['CollectionSheet']['wing_id']) ? base64_encode($postData['CollectionSheet']['wing_id']) : '';
                                                    echo $this->Html->link('<i class="fa fa-file-excel-o" style="color:#fff;font-size:13px;"></i> Export to Excel', array('controller' => 'excels_report', 'action' => 'download_account_collection_sheet', '?' => array('bill_generated_date_to' => $bill_date_to, 'bill_generated_date' => $bill_date,'month' => $month, 'month_to' => $month_to, 'receipt_id' => $receiptID, 'receipt_id_to' => $receiptIdTo, 'building_id' => $building_id, 'wing_id' => $wing_id, 'flat_no' => $flat_no, 'flat_no_to' => $flat_no_to)), array('class' => 'btn btn-warning btns', 'data-toggle' => 'tooltip', 'data-original-title' => 'Member Collection Register', 'escape' => false, 'target' => '_blank'));
                                            ?>
                                    </div>
                                </div> 
                                <div class="col-md-3 right-section">
                                    <div class="row">   
                                        <div class="right-title">Select if required </div> 
                                        <div class="col-md-12">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Building &nbsp;</div>                                    
                                                <select name="data[CollectionSheet][building_id]" id="wingBuildingName" class="society-report-form-box-field-dropdown" onchange="getAllBuildingWings(this.value, '#society_bill_report');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($postData['CollectionSheet']['building_id']) && $postData['CollectionSheet']['building_id'] == $buildingID){?>
                                                    <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                                </select>
                                            </div>
                                        </div>  
                                        <div class="col-md-12">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                                <select class="society-report-form-box-field-dropdown" id="society_bill_report" name="data[CollectionSheet][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Unit No &nbsp;</div>                                   
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[CollectionSheet][flat_no]" value="<?php echo isset($postData['CollectionSheet']['flat_no']) ? $postData['CollectionSheet']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[CollectionSheet][flat_no_to]" value="<?php echo isset($postData['CollectionSheet']['flat_no_to']) ? $postData['CollectionSheet']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                    </div>    
                                </div> 
                            </form> 
                            <div class="clearfix"></div><br><br>
                            <div id="print_collection_sheet">
                                <div class="print-collection-sheet">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="report-bill">Collection Sheet</div>
                                    <div class="report-bill"></div>  
                                    <div class="report-bill-outer-section">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered padding-td-none padding-th-none print-custom-table-border">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="text-center">Sr</th>
                                                        <th rowspan="2" class="text-center">Bill No.</th>
                                                        <th rowspan="2" class="text-center">Unit No.</th>
                                                        <th rowspan="2" class="text-center">Member</th>
                                                        <th rowspan="2" class="text-center">Bill Amount</th>    
                                                        <th rowspan="2" class="text-center">Int.</th>
                                                        <th rowspan="2" class="text-center">GST</th>
                                                        <th rowspan="2" class="text-center">Arrears</th>
                                                        <th rowspan="2" class="text-center">Total Amount</th>
                                                        <th rowspan="2" class="text-center">Amt. Rec.</th>
                                                        <th colspan="3" class="text-center">Payment Received Details</th>
                                                        <th rowspan="2" class="text-center">Remarks</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Ch.No.</th>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-center">Bank's Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php  
                                                    if(isset($collectionSheetDetails) && count($collectionSheetDetails) > 0){
                                                        $idCount = 1;
                                                        $arrears = 0;
                                                        $billTotal = 0;
                                                        
                                                        $totalBillAmount = 0;
                                                        $totalInt = 0;
                                                        $totalTax = 0;
                                                        $totalArrears = 0;
                                                        $totalAmount = 0;
                                                        
                                                        foreach($collectionSheetDetails as $collectionData){
                                                            $billTotal = $collectionData['MemberBillSummary']['amount_payable'];
                                                            $arrears = $collectionData['MemberBillSummary']['op_due_amount'];
                                                            
                                                            $monthlyBillAmount = $collectionData['MemberBillSummary']['monthly_amount']-$collectionData['MemberBillSummary']['tax_total'];
                                                            
                                                            $totalBillAmount += $monthlyBillAmount;
                                                            $totalInt += $collectionData['MemberBillSummary']['interest_on_due_amount'];
                                                            $totalTax += $collectionData['MemberBillSummary']['tax_total'];
                                                            $totalArrears += $arrears;
                                                            $totalAmount += $billTotal;
                                                      ?>
                                                    <tr id="<?php echo $idCount; ?>">
                                                        <td style="width:4%;" class="text-center"><?php echo $idCount; ?></td>
                                                        <td style="width:4%;" class="text-center"><?php echo isset($collectionData['MemberBillSummary']['bill_no']) ? $collectionData['MemberBillSummary']['bill_no'] :'' ; ?></td>
                                                        <td style="width:4%;" class="text-center"><?php echo isset($collectionData['Member']['flat_no']) ? $collectionData['Member']['flat_no'] :'' ; ?></td>
                                                        <td><?php echo isset($collectionData['Member']['member_prefix']) ? $collectionData['Member']['member_prefix'] :'' ; ?> <?php echo isset($collectionData['Member']['member_name']) ? $collectionData['Member']['member_name'] :'' ; ?></td>
                                                        <td style="width:8%;" class="text-right"><?php echo isset($monthlyBillAmount) ? $monthlyBillAmount :'' ; ?></td>
                                                        <td style="width:8%;" class="text-right"><?php echo isset($collectionData['MemberBillSummary']['interest_on_due_amount']) ? $collectionData['MemberBillSummary']['interest_on_due_amount'] :'' ; ?></td>
                                                        <td style="width:8%;" class="text-right"><?php echo isset($collectionData['MemberBillSummary']['tax_total']) ? $collectionData['MemberBillSummary']['tax_total'] :'' ; ?></td>
                                                        <td style="width:8%;" class="text-right"><?php echo isset($arrears) ? $arrears :'' ; ?></td>
                                                        <td style="width:8%;" class="text-right"><?php echo isset($billTotal) ? $billTotal :'' ; ?></td>
                                                        <td style="width:8% !important;"></td>
                                                        <td></td>
                                                        <td style="width:8% !important;"></td>
                                                        <td style="width:12% !important;"></td>
                                                        <td style="width:12% !important;"></td>
                                                    </tr>
                                                    <?php $idCount++; } ?>

                                                <tfoot id=""> 
                                                    <tr>                                                                       
                                                        <td colspan="4" class="text-right" style="font-weight:bold;"> Total</td>
                                                        <td colspan="1" class="text-right" style="font-weight:bold;"><?php echo $utilObj->CreditDebitAmountCheck($utilObj->NumberFormat2Decimal($totalBillAmount)); ?></td>
                                                        <td colspan="1" class="text-right" style="font-weight:bold;"><?php echo $utilObj->CreditDebitAmountCheck($utilObj->NumberFormat2Decimal($totalInt)); ?></td>
                                                        <td colspan="1" class="text-right" style="font-weight:bold;"><?php echo $utilObj->CreditDebitAmountCheck($utilObj->NumberFormat2Decimal($totalTax)); ?></td>
                                                        <td colspan="1" class="text-right" style="font-weight:bold;"><?php echo $utilObj->CreditDebitAmountCheck($utilObj->NumberFormat2Decimal($totalArrears)); ?></td>
                                                        <td colspan="1" class="text-right" style="font-weight:bold;"><?php echo $utilObj->CreditDebitAmountCheck($utilObj->NumberFormat2Decimal($totalAmount)); ?></td>
                                                        <td colspan="5" class="text-left" style="font-weight:bold;"></td>
                                                    </tr>
                                                </tfoot>
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