<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Outstanding Bills</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="outstandingBillFrm" name="outstandingBillFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> <div class="row">
                                        <div class="col-md-4">    
                                            <div class="society-report-form-box-label">As on Date &nbsp;</div> 
                                            <input type="date" class="society-report-form-box-field-date" id="bill_generated_date" name="data[MemberBillSummary][bill_generated_date]" value="<?php echo isset($postData['MemberBillSummary']['bill_generated_date']) ? $postData['MemberBillSummary']['bill_generated_date'] :'' ; ?>">                                           
                                        </div>
                                        <div class="col-md-4">    
                                            <div class="society-report-form-box-label">Operator &nbsp;</div>                                                                     
                                            <select class="society-report-form-box-field-dropdown" id="operator" name="data[MemberBillSummary][operator]">
                                                <option value="">Select Operator</option>   
                                                <?php //if(isset($postData['MemberBillSummary']['operator']) && $postData['MemberBillSummary']['operator'] ==''){?>
                                                <option value=">" <?php if(isset($postData['MemberBillSummary']['operator']) && $postData['MemberBillSummary']['operator'] == '>') { echo 'selected';}?>>></option>
                                                <option value="<" <?php if(isset($postData['MemberBillSummary']['operator']) && $postData['MemberBillSummary']['operator'] == '<') { echo 'selected';}?>><</option>
                                                <option value=">=" <?php if(isset($postData['MemberBillSummary']['operator']) && $postData['MemberBillSummary']['operator'] == '>=') { echo 'selected';}?>>>=</option>
                                                <option value="<=" <?php if(isset($postData['MemberBillSummary']['operator']) && $postData['MemberBillSummary']['operator'] == '<=') { echo 'selected';}?>><=</option>
                                                <option value="=" <?php if(isset($postData['MemberBillSummary']['operator']) && $postData['MemberBillSummary']['operator'] == '=') { echo 'selected';}?>>=</option>
                                                <option value="<>" <?php if(isset($postData['MemberBillSummary']['operator']) && $postData['MemberBillSummary']['operator'] == '<>') { echo 'selected';}?>><></option>
                                                <?php //}?>
                                            </select> 
                                        </div>
                                        <div class="col-md-4">    
                                            <div class="society-report-form-box-label">Amount &nbsp;</div>  
                                            <input type="text" class="society-report-form-box-field-40 text-right" id="" name="data[MemberBillSummary][op_due_amount]" value="<?php echo isset($postData['MemberBillSummary']['op_due_amount']) ? $postData['MemberBillSummary']['op_due_amount'] :'' ; ?>">                                            
                                        </div>                                                              
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>     
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_society_outstanding_bill');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                                <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>                                    
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btn-sm" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>    
                                    </div>
                                </div> 
                                <div class="col-md-4 right-section">
                                    <div class="row">   
                                        <div class="right-title">Select if required </div> 
                                        <div class="col-md-12">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Building &nbsp;</div>   
                                                <select name="data[MemberBillSummary][building_id]" id="wingBuildingName" class="society-report-form-box-field-dropdown" onchange="getAllBuildingWings(this.value, '#society_outstanding_bill');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($postData['MemberBillSummary']['building_id']) && $postData['MemberBillSummary']['building_id'] == $buildingID){?>
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
                                                <select class="society-report-form-box-field-dropdown" id="society_outstanding_bill" name="data[MemberBillSummary][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Flat No &nbsp;&nbsp;</div>                                   
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberBillSummary][flat_no]" value="<?php echo isset($postData['MemberBillSummary']['flat_no']) ? $postData['MemberBillSummary']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberBillSummary][flat_no_to]" value="<?php echo isset($postData['MemberBillSummary']['flat_no_to']) ? $postData['MemberBillSummary']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div><br>
                                    </div>    
                                </div> 
                                <div class="clearfix"></div>
                                <br><br><br>
                            </form> 

                            <div id="print_society_outstanding_bill">
                                <div class="print-society-outstanding-bill">
                                <div class="row1">
                                    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>

                                    <div class="report-bill">Bill wise Outstanding - Regular Bill</div>
                                    <div class="text-center">As on 01/02/2018</div> 
                                    <div class="report-bill-outer-section">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered padding-td-none padding-th-none" style="margin-top: -22px;">
                                                <thead>
                                                    <tr>
                                                        <th>Bill No.</th>
                                                        <th>Bill Date</th>
                                                        <th>Financial Year</th>
                                                        <th>Principal</th>
                                                        <th>Interest</th>
                                                        <th>Tax</th>
                                                        <th>Amount Due</th>
                                                    </tr>
                                                </thead>
                                                <tbody>  
                                                <?php //TODO
                                                    $GrandTotal = 0;
                                                    if(isset($societyMemberOutstandingBillDetails) && count($societyMemberOutstandingBillDetails) > 0){
                                                    $idCount = 1;                                                 
                                                    foreach($societyMemberOutstandingBillDetails as $outstandingBillData){
                                                    $AmountTotal = isset($outstandingBillData['MemberBillSummary']['amount']) ? $OutstandingBillData['MemberBillSummary']['amount']: 0;
                                                ?>
                                                    <tr id="">
                                                        <td style="border:none !important;">Unit No:- <?php echo isset($outstandingBillData['Member']['flat_no']) ? $outstandingBillData['Member']['flat_no'] :'' ; ?></td>
                                                        <td colspan="2" style="border:none !important;">Member Name : <?php echo isset($outstandingBillData['Member']['member_name']) ? $outstandingBillData['Member']['member_name'] :'' ; ?></td>
                                                        <td style="border:none !important;"></td>
                                                        <td style="border:none !important;"></td>
                                                        <td style="border:none !important;"></td>
                                                        <td style="border-left:none !important;"></td>
                                                    </tr>
                                                    <tr id="">
                                                        <td><?php echo isset($outstandingBillData['MemberBillSummary']['bill_no']) ? $outstandingBillData['MemberBillSummary']['bill_no']: '';  ?></td>
                                                        <td><?php echo isset($outstandingBillData['MemberBillSummary']['bill_generated_date']) ? $outstandingBillData['MemberBillSummary']['bill_generated_date']: '';  ?></td>
                                                        <td><?php  echo $utilObj->getFinancialYearFromDate(date('Y-m-d')); ?></td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['principal_balance']) ? $outstandingBillData['MemberBillSummary']['principal_balance']: '';  ?></td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['interest_balance']) ? $outstandingBillData['MemberBillSummary']['interest_balance']: '';  ?></td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['tax_balance']) ? $outstandingBillData['MemberBillSummary']['tax_balance']: '';  ?></td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['balance_amount']) ? $outstandingBillData['MemberBillSummary']['balance_amount']: '';  ?></td>
                                                    </tr>
                                                    <tr id="">
                                                        <td colspan="3" class="text-center">Regular Total</td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['principal_balance']) ? $outstandingBillData['MemberBillSummary']['principal_balance']: '';  ?></td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['interest_balance']) ? $outstandingBillData['MemberBillSummary']['interest_balance']: '';  ?></td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['tax_balance']) ? $outstandingBillData['MemberBillSummary']['tax_balance']: '';  ?></td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['balance_amount']) ? $outstandingBillData['MemberBillSummary']['balance_amount']: '';  ?></td>
                                                    </tr>
                                                    <tr id="" style="border-bottom:2px solid #000;">
                                                        <td colspan="3" class="text-center">Total Regular + Supplemetary</td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['principal_balance']) ? $outstandingBillData['MemberBillSummary']['principal_balance']: '';  ?></td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['interest_balance']) ? $outstandingBillData['MemberBillSummary']['interest_balance']: '';  ?></td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['tax_balance']) ? $outstandingBillData['MemberBillSummary']['tax_balance']: '';  ?></td>
                                                        <td class="text-right"><?php echo isset($outstandingBillData['MemberBillSummary']['balance_amount']) ? $outstandingBillData['MemberBillSummary']['balance_amount']: '';  ?></td>
                                                    </tr>
                                                <?php $idCount++;}}?>
                                                </tbody>  
                                                <br><br>
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
</div>