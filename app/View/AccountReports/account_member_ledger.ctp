<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Ledger</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="memberLedgerFrm" name="memberLedgerFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">                                            
                                        <div class="col-md-4 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-box-label">Type &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-80" id="" name="data[MemberBillSummary][report_type]">
                                                    <option value="">Select</option>  
                                                    <option value="Default" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Default'){echo 'selected';}?>>Default</option>  
                                                    <option value="Regular" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Regular'){echo 'selected';}?>>Regular</option>  
                                                    <option value="Supplementary" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Supplementary'){echo 'selected';}?>>Supplementary</option>  
                                                    <option value="Combined" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Combined'){echo 'selected';}?>>Combined</option>                                                     
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">From &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][payment_date]" value="<?php echo isset($postData['MemberBillSummary']['payment_date']) ? $postData['MemberBillSummary']['payment_date'] :'' ; ?>">
                                            </div> 
                                        </div>
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">To &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][payment_date_to]" value="<?php echo isset($postData['MemberBillSummary']['payment_date_to']) ? $postData['MemberBillSummary']['payment_date_to'] :'' ; ?>">
                                            </div> 
                                        </div>                                                         
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="Submit" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printAllReports('print_member_ledger');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                                <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>  
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
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
                                                <select class="account-report-form-box-field-dropdown" id="" name="data[MemberBillSummary][building_id]" onchange="getAllBuildingWings(this.value, '#member_ledger');">
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
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                                <select class="account-report-form-box-field-dropdown" id="member_ledger" name="data[MemberBillSummary][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Unit No &nbsp;&nbsp;</div>                                   
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberBillSummary][flat_no]" value="<?php echo isset($postData['MemberBillSummary']['flat_no']) ? $postData['MemberBillSummary']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberBillSummary][flat_no_to]" value="<?php echo isset($postData['MemberBillSummary']['flat_no_to']) ? $postData['MemberBillSummary']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                    </div>    
                                </div>   
                            </form> 
                            <div class="clearfix"></div>
                            <br><br><br>
                            <div id="print_member_ledger">
                                <div class="print-member-ledger">
                                    <div class="row1">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="row1">
                                        <div class="report-bill"></div>
                                        <div class="report-bill">Member Ledger-Regular</div>
                                        <div class="text-center"></div> 
                                        <div class="report-bill-outer-section">
                                            <div class="table-wrap1">
                                                <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2" class="text-center">Date</th>
                                                            <th rowspan="2" class="text-center">Reference</th>
                                                            <th rowspan="2" class="text-center">Vr No</th>
                                                            <th colspan="2" class="text-center">Cheque</th>
                                                            <th colspan="3"  class="text-center">Debit</th>
                                                            <th colspan="3"  class="text-center">Credit</th>
                                                            <th colspan="3"  class="text-center">Balance</th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center">No.</th>
                                                            <th class="text-center">Date</th>
                                                            <th class="text-center">Principal</th>
                                                            <th class="text-center">Int</th>
                                                            <th class="text-center">Total</th>
                                                            <th class="text-center">Principal</th>
                                                            <th class="text-center">Int</th>
                                                            <th class="text-center">Total</th>
                                                            <th class="text-center">Principal</th>
                                                            <th class="text-center">Int</th>
                                                            <th class="text-center">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                            <?php //TODO
                                                if(isset($accountMemberLedgerDetails) && count($accountMemberLedgerDetails) > 0){
                                                $idCount = 1;                                                 
                                                foreach($accountMemberLedgerDetails as $MemberLedgerData){
                                                    $innerCounter = 0;
                                                 foreach($MemberLedgerData as $MemberLedgerDetails){
                                                ?>
                                                <?php if($innerCounter == 0){ ?>
                                                        <tr id="">
                                                            <td colspan="6">Building : <?php echo isset($MemberLedgerDetails['building_name']) ? $MemberLedgerDetails['building_name'] : ''; ?></td>
                                                            <td colspan="2"><span style="display: block;">Wing : <?php echo isset($MemberLedgerDetails['wing_name']) ? $MemberLedgerDetails['wing_name'] : ''; ?></span></td>
                                                            <td colspan="1"><span style="display: block;">Unit No : <?php echo isset($MemberLedgerDetails['flat_no']) ? $MemberLedgerDetails['flat_no'] : ''; ?></span></td>
                                                            <td colspan="5"><span style="display: block;">Member Name : <?php echo isset($MemberLedgerDetails['member_name']) ? $MemberLedgerDetails['member_name'] : ''; ?></span></td>
                                                        </tr>
                                                <?php }else{ ?>
                                                        <tr id="">
                                                            <td><?php echo isset($MemberLedgerDetails['MemberBillSummary']['bill_generated_date']) ? $utilObj->mysqlToDate($MemberLedgerDetails['MemberBillSummary']['bill_generated_date'],'/'): '';  ?></td>
                                                            <td><span style="display: block;">Opening Balance</span></td>
                                                            <td></td>
                                                            <td><?php echo isset($MemberLedgerDetails['MemberBillSummary']['cheque_reference_number']) ? $MemberLedgerDetails['MemberBillSummary']['cheque_reference_number']: '';  ?></td>
                                                            <td><?php echo isset($MemberLedgerDetails['MemberBillSummary']['payment_date']) ? $MemberLedgerDetails['MemberBillSummary']['payment_date']: '';  ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['op_principal_arrears']) ? $MemberLedgerDetails['MemberBillSummary']['op_principal_arrears']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['op_interest_arrears']) ? $MemberLedgerDetails['MemberBillSummary']['op_interest_arrears']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['op_due_amount']) ? $MemberLedgerDetails['MemberBillSummary']['op_due_amount']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['amount_paid']) ? $MemberLedgerDetails['MemberBillSummary']['amount_paid']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['interest_paid']) ? $MemberLedgerDetails['MemberBillSummary']['interest_paid']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['creadit_total']) ? $MemberLedgerDetails['MemberBillSummary']['creadit_total']:'0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['op_principal_arrears']) ? $MemberLedgerDetails['MemberBillSummary']['op_principal_arrears']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['op_interest_arrears']) ? $MemberLedgerDetails['MemberBillSummary']['op_interest_arrears']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['op_due_amount']) ? $MemberLedgerDetails['MemberBillSummary']['op_due_amount']: '0.00'; ?></td>
                                                        </tr>

                                                        <tr id="">
                                                            <td><?php echo isset($MemberLedgerDetails['MemberBillSummary']['bill_generated_date']) ? $utilObj->mysqlToDate($MemberLedgerDetails['MemberBillSummary']['bill_generated_date'],'/'): '';  ?></td>
                                                            <td>Bill<span style="display: block;">For <?php echo isset($MemberLedgerDetails['MemberBillSummary']['month']) ? $utilObj->getMonthWordFormat($MemberLedgerDetails['MemberBillSummary']['month']): '';  ?></span></td>
                                                            <td><?php echo isset($MemberLedgerDetails['MemberBillSummary']['bill_no']) ? $MemberLedgerDetails['MemberBillSummary']['bill_no']: '';  ?></td>
                                                            <td><?php echo isset($MemberLedgerDetails['MemberBillSummary']['cheque_reference_number']) ? $MemberLedgerDetails['MemberBillSummary']['cheque_reference_number']: '0';  ?></td>
                                                            <td><?php echo isset($MemberLedgerDetails['MemberBillSummary']['payment_date']) ? $MemberLedgerDetails['MemberBillSummary']['payment_date']: '0';  ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['monthly_amount']) ? $MemberLedgerDetails['MemberBillSummary']['monthly_amount']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['op_interest_arrears']) ? $MemberLedgerDetails['MemberBillSummary']['op_interest_arrears']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['debit_total']) ? $MemberLedgerDetails['MemberBillSummary']['debit_total']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['amount_paid']) ? $MemberLedgerDetails['MemberBillSummary']['amount_paid']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['interest_paid']) ? $MemberLedgerDetails['MemberBillSummary']['interest_paid']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['creadit_total']) ? $MemberLedgerDetails['MemberBillSummary']['creadit_total']: '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['principal_balance']) ? $MemberLedgerDetails['MemberBillSummary']['principal_balance']: '0.00';  ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['interest_balance']) ? $MemberLedgerDetails['MemberBillSummary']['interest_balance']: '0.00';  ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['MemberBillSummary']['balance_amount']) ? $MemberLedgerDetails['MemberBillSummary']['balance_amount']: '0.00';  ?></td>
                                                        </tr>
                                                <?php } ?>
                                                    </tbody> 
                                            <?php $idCount++;$innerCounter++; }}}?>
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