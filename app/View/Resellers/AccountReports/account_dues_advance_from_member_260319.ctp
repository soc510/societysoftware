<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Dues-Advance From Member</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="advanceReceivedFromMemberFrm" name="advanceReceivedFromMemberFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">As on Date &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberPayment][payment_date]" value="<?php echo isset($postData['MemberPayment']['payment_date']) ? $postData['MemberPayment']['payment_date'] :'' ; ?>">
                                            </div> 
                                        </div>                                            
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-box-label">Type &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-80" id="" name="data[MemberPayment][report_type]">
                                                    <option value="">Select</option>  
                                                    <option value="Summary" <?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Summary'){echo 'selected';}?>>Summary</option>  
                                                    <option value="Detail"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Detail'){echo 'selected';}?>>Detail</option>  
                                                    <option value="Dues-Advance"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Dues-Advance'){echo 'selected';}?>>Dues-Advance</option> 
                                                    <option value="With Transaction"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='With Transaction'){echo 'selected';}?>>With Transaction</option> 
                                                    <option value="Regular"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Regular'){echo 'selected';}?>>Regular</option>  
                                                    <option value="Supplementary"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Supplementary'){echo 'selected';}?>>Supplementary</option> 
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">   
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Operator &nbsp;</div>                                                                     
                                                <select class="account-report-form-box-field-dropdown-65" id="" name="data[MemberPayment][operator]">
                                                    <option value="">Select Operator</option>   
                                                    <?php //if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] ==''){?>
                                                    <option value=">" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '>') { echo 'selected';}?>>></option>
                                                    <option value="<" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '<') { echo 'selected';}?>><</option>
                                                    <option value=">=" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '>=') { echo 'selected';}?>>>=</option>
                                                    <option value="<=" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '<=') { echo 'selected';}?>><=</option>
                                                    <option value="=" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '=') { echo 'selected';}?>>=</option>
                                                    <option value="<>" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '<>') { echo 'selected';}?>><></option>
                                                    <?php //}?>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-2 nopadding">
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-box-label">Amount &nbsp;</div>  
                                                <input type="text" class="account-report-form-box-field-50 text-right" id="" name="data[MemberPayment][amount]" value="<?php echo isset($postData['MemberPayment']['amount']) ? $postData['MemberPayment']['amount'] :'' ; ?>">
                                            </div> 
                                        </div>                                                              
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim  btns" href="javascript:void(0);" onclick="printAllReports('print_dues_advance_from_member');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
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
                                                <select class="account-report-form-box-field-dropdown" id="" name="data[MemberPayment][building_id]" onchange="getAllBuildingWings(this.value, '#adv_received_from_member');">
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
                                                <select class="account-report-form-box-field-dropdown" id="adv_received_from_member" name="data[MemberPayment][wing_id]">
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
                            <div class="clearfix"></div>
                            <br><br><br>
                            <div id="print_dues_advance_from_member">
                                <div class="print-dues-advance-from-member">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="row1">
                                        <div class="report-bill">Dues-Advance From Member</div>
                                        <div class="report-bill"></div> 
                                        <div class="report-bill-outer-section no-border-thead">
                                            <div class="table-wrap1">
                                                <table id="" class="table table-bordered1 padding-td-none padding-th-none">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:5%;" class="text-center">Sr.</th>
                                                            <th style="width:5%;" class="text-center">Unit No.</th>
                                                            <th class="text-center">Member Name</th>
                                                            <th style="width:10%;" class="text-center">Advance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr id="">
                                                            <td colspan="5" class="border-none">Building : <?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></td>
                                                        </tr>
                                                        <tr id="">
                                                            <td colspan="5" class="border-none"><span style="display: block;">Wing : <?php echo isset($societyDetails['Wing']['wing_name']) ? $societyDetails['Wing']['wing_name'] : ''; ?></span></td>
                                                        </tr> 
                                                        <?php //TODO
                                                            if(isset($accountDuesAdvanceMemberSummary) && count($accountDuesAdvanceMemberSummary) > 0){
                                                            $idCount = 1;
                                                            $duesAdvanceTotalAmount = 0;
                                                            foreach($accountDuesAdvanceMemberSummary as $accountDuesAdvanceMemberInfo){                                                                
                                                            if($accountDuesAdvanceMemberInfo['total_dues_amount'] == 0) {
                                                                continue;
                                                            }    
                                                            
                                                            if(round(abs($accountDuesAdvanceMemberInfo['total_dues_amount'])) <= 1) {
                                                                continue;
                                                            }
                                                            
                                                            $duesAdvanceTotalAmount += isset($accountDuesAdvanceMemberInfo['total_dues_amount']) && !empty($accountDuesAdvanceMemberInfo['total_dues_amount']) ? $accountDuesAdvanceMemberInfo['total_dues_amount'] * 1 : 0.00;
                                                         ?>
                                                        <tr id="<?php echo $idCount;  ?>">
                                                            <td><?php echo $idCount;  ?></td>
                                                            <td><?php echo isset($accountDuesAdvanceMemberInfo['flat_no']) ? $accountDuesAdvanceMemberInfo['flat_no'] : '';  ?></td>
                                                            <td><?php echo isset($accountDuesAdvanceMemberInfo['member_prefix']) ? $accountDuesAdvanceMemberInfo['member_prefix'].$accountDuesAdvanceMemberInfo['member_name'] : '';  ?> <?php echo isset($accountDuesAdvanceMemberInfo['Member']['member_name']) ? $accountDuesAdvanceMemberInfo['Member']['member_name'] : '';  ?></td>                                                    
                                                            <td class="text-right"><?php echo isset($accountDuesAdvanceMemberInfo['total_dues_amount']) ? round(abs($accountDuesAdvanceMemberInfo['total_dues_amount'])).' Cr' : '';  ?></td>
                                                        </tr>      
                                                        <?php $idCount++; }?> 
                                                        <tr id="">
                                                            <td class="text-right" colspan="3" class="border-none">Total</td>
                                                            <td class="text-right"><?php $duesAdvanceTotalAmount = abs($duesAdvanceTotalAmount); echo number_format($duesAdvanceTotalAmount,2,'.',',').' Cr'; ?></td>
                                                        </tr>                                                        
                                                        <tr id="">
                                                            <td class="text-right" colspan="3"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?> Total</td>
                                                            <td class="text-right"><?php $duesAdvanceTotalAmount = abs($duesAdvanceTotalAmount); echo number_format($duesAdvanceTotalAmount,2,'.',',').' Cr'; ?></td>
                                                        </tr>                                                        
                                                        <tr id="">
                                                            <td class="text-right" colspan="3">Grand Total</td>
                                                            <td class="text-right"><?php $duesAdvanceTotalAmount = abs($duesAdvanceTotalAmount); echo number_format($duesAdvanceTotalAmount,2,'.',',').' Cr'; ?></td>
                                                        </tr> 
                                                        <?php }else{?>
                                                        <tr><td colspan="11"><center>Record not found.</center></td></tr>
                                                        <?php }?>
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
</div>