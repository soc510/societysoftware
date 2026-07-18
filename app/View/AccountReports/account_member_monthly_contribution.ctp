<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Monthly Contribution</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="memberMonthlyControFrm" name="memberMonthlyControFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-12 member-tariff-left-box"> 
                                    <div class="row">                                            
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-box-label">Building &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-80" id="" name="data[MemberPayment][building_id]" onchange="getAllBuildingWings(this.value, '#member_monthly_contro');">
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
                                        <div class="col-md-3 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Wing &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-80" id="member_monthly_contro"  name="data[MemberPayment][wing_id]">
                                                    <option value="">Select</option>  
                                                </select> 
                                            </div> 
                                        </div>
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Member &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-80" name="data[MemberPayment][member_id]" id="member_monthly_contribution_member_name" onchange="selectMemberFlatDropdownByMemberFlatID(this.value, '#member_monthly_contribution_flat_no', 0);" name="[Member][member_name]">
                                                    <option value="">Select</option>  
                                                    <?php if(isset($societyMemberName) && count($societyMemberName) > 0) {
                                                    foreach($societyMemberName as $memberID => $memberName){?>
                                                    <?php if(isset($postData['MemberPayment']['member_name']) && $postData['MemberPayment']['member_name'] == $memberID){?>
                                                    <option value="<?php echo $memberID; ?>" selected><?php echo $memberName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $memberID; ?>"><?php echo $memberName; ?></option>
                                                    <?php }?>
                                                    <?php } }?> 
                                                </select> 
                                            </div> 
                                        </div> 
                                        <div class="col-md-2 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Unit No &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown" id="member_monthly_contribution_flat_no" onchange="selectMemberFlatDropdownByMemberFlatID(this.value, '#member_monthly_contribution_member_name', 1);" name="data[MemberPayment][flat_no]">
                                                    <option value="">Select</option>  
                                                     <?php if(isset($societyMemberFlatlList) && count($societyMemberFlatlList) > 0) {
                                                    foreach($societyMemberFlatlList as $flatNO => $memberID){?>
                                                    <?php if(isset($postData['MemberPayment']['flat_no']) && $postData['MemberPayment']['flat_no'] == $flatNO){?>
                                                    <option value="<?php echo $flatNO; ?>" selected><?php echo $flatNO; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $flatNO; ?>"><?php echo $flatNO; ?></option>
                                                    <?php }?>
                                                    <?php } }?> 
                                                </select> 
                                            </div> 
                                        </div>                                                         
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-sm btn-anim"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button> 
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim  btn-sm" href="javascript:void(0);" onclick="printAllReports('print_member_monthly_cotribution');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                                <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>                                             
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-sm btn-anim" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>    
                                    </div>
                                </div> 
                            </form> 
                            <div class="clearfix"></div>
                            <br><br>
                            <div id="print_member_monthly_cotribution">
                            <div class="print-member-monthly-cotribution">
                                <div class="row">
                                    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                </div>
                                <div style="padding:15px;">                            
                                    <table id="" class="table table-bordered padding-td-none padding-th-none">
                                        <thead>
                                            <tr>
                                                <th>Particulars</th>
                                                <th>Apr</th>
                                                <th>May</th>
                                                <th>Jun</th>
                                                <th>Jul</th>
                                                <th>Aug</th>
                                                <th>Sep</th>
                                                <th>Oct</th>
                                                <th>Nov</th>
                                                <th>Dec</th>
                                                <th>Jan</th>
                                                <th>Feb</th>
                                                <th>Mar</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(isset($memberMonthlyContributionData) && !empty($memberMonthlyContributionData)) { 
                                            foreach($memberMonthlyContributionData as $ledgerHeadId=>$billingDetails) { ?>
                                            <tr id="">
                                                <td><?php echo $societyLedgerHeads[$ledgerHeadId];?></td>
                                                <td><?php echo $billingDetails[4]; $aprTotal +=$billingDetails[4];?></td>
                                                <td><?php echo $billingDetails[5];$mayTotal +=$billingDetails[5];?></td>
                                                <td><?php echo $billingDetails[6];$junTotal +=$billingDetails[6];?></td>
                                                <td><?php echo $billingDetails[7];$julTotal +=$billingDetails[7];?></td>
                                                <td><?php echo $billingDetails[8];$augTotal +=$billingDetails[8];?></td>
                                                <td><?php echo $billingDetails[9];$sepTotal +=$billingDetails[9];?></td>
                                                <td><?php echo $billingDetails[10];$octTotal +=$billingDetails[10];?></td>
                                                <td><?php echo $billingDetails[11];$novTotal +=$billingDetails[11];?></td>
                                                <td><?php echo $billingDetails[12];$decTotal +=$billingDetails[12];?></td>
                                                <td><?php echo $billingDetails[1];$janTotal +=$billingDetails[1];?></td>
                                                <td><?php echo $billingDetails[2];$febTotal +=$billingDetails[2];?></td>
                                                <td><?php echo $billingDetails[3];$marTotal +=$billingDetails[3];?></td>
                                                <td><?php $total = $billingDetails[4]+$billingDetails[5]+$billingDetails[6]+$billingDetails[7]+$billingDetails[8]+$billingDetails[9]+$billingDetails[10]+$billingDetails[11]+$billingDetails[12]+$billingDetails[1]+$billingDetails[2]+$billingDetails[3];
                                                echo number_format($total, 2, '.', '');;
                                                ?></td>
                                            </tr>    
                                        <?php } ?>
                                            <tr id="">
                                                <td><b>Total</b></td>
                                                <td><?php if(!empty($aprTotal)) { echo $aprTotal; } else { echo '';}?></td>
                                                <td><?php if(!empty($mayTotal)) { echo $mayTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($junTotal)) { echo $junTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($julTotal)) { echo $julTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($augTotal)) { echo $augTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($sepTotal)) { echo $sepTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($octTotal)) { echo $octTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($novTotal)) { echo $novTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($decTotal)) { echo $decTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($janTotal)) { echo $janTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($febTotal)) { echo $febTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($marTotal)) { echo $marTotal; } else { echo '';};?></td>
                                                <td><?php $totalMonths = $aprTotal+$mayTotal+$junTotal+$julTotal+$augTotal+$sepTotal+$octTotal+$novTotal+$decTotal+$janTotal+$febTotal+$marTotal;
                                                echo number_format($totalMonths, 2, '.', '');;
                                                ?></td>
                                            </tr>
                                            <tr id="">
                                                <td>GST</td>
                                                <td><?php echo $memberMonthlyTaxData[4]; $aprTotal +=$memberMonthlyTaxData[4];?></td>
                                                <td><?php echo $memberMonthlyTaxData[5];$mayTotal +=$memberMonthlyTaxData[5];?></td>
                                                <td><?php echo $memberMonthlyTaxData[6];$junTotal +=$memberMonthlyTaxData[6];?></td>
                                                <td><?php echo $memberMonthlyTaxData[7];$julTotal +=$memberMonthlyTaxData[7];?></td>
                                                <td><?php echo $memberMonthlyTaxData[8];$augTotal +=$memberMonthlyTaxData[8];?></td>
                                                <td><?php echo $memberMonthlyTaxData[9];$sepTotal +=$memberMonthlyTaxData[9];?></td>
                                                <td><?php echo $memberMonthlyTaxData[10];$octTotal +=$memberMonthlyTaxData[10];?></td>
                                                <td><?php echo $memberMonthlyTaxData[11];$novTotal +=$memberMonthlyTaxData[11];?></td>
                                                <td><?php echo $memberMonthlyTaxData[12];$decTotal +=$memberMonthlyTaxData[12];?></td>
                                                <td><?php echo $memberMonthlyTaxData[1];$janTotal +=$memberMonthlyTaxData[1];?></td>
                                                <td><?php echo $memberMonthlyTaxData[2];$febTotal +=$memberMonthlyTaxData[2];?></td>
                                                <td><?php echo $memberMonthlyTaxData[3];$marTotal +=$memberMonthlyTaxData[3];?></td>
                                                <td><?php $total = $memberMonthlyTaxData[4]+$memberMonthlyTaxData[5]+$memberMonthlyTaxData[6]+$memberMonthlyTaxData[7]+$memberMonthlyTaxData[8]+$memberMonthlyTaxData[9]+$memberMonthlyTaxData[10]+$memberMonthlyTaxData[11]+$memberMonthlyTaxData[12]+$memberMonthlyTaxData[1]+$memberMonthlyTaxData[2]+$memberMonthlyTaxData[3];
                                                echo number_format($total, 2, '.', '');;
                                                ?></td>
                                            </tr>
                                            <tr id="">
                                                <td><b>Total With GST</b></td>
                                                <td><?php if(!empty($aprTotal)) { echo $aprTotal; } else { echo '';}?></td>
                                                <td><?php if(!empty($mayTotal)) { echo $mayTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($junTotal)) { echo $junTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($julTotal)) { echo $julTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($augTotal)) { echo $augTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($sepTotal)) { echo $sepTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($octTotal)) { echo $octTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($novTotal)) { echo $novTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($decTotal)) { echo $decTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($janTotal)) { echo $janTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($febTotal)) { echo $febTotal; } else { echo '';};?></td>
                                                <td><?php if(!empty($marTotal)) { echo $marTotal; } else { echo '';};?></td>
                                                <td><?php $totalMonths = $aprTotal+$mayTotal+$junTotal+$julTotal+$augTotal+$sepTotal+$octTotal+$novTotal+$decTotal+$janTotal+$febTotal+$marTotal;
                                                echo number_format($totalMonths, 2, '.', '');;
                                                ?></td>
                                            </tr>
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