<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">List of Tenant</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form class="" method="post" id="listOfTenantFrm" name="listOfTenantFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div id="show_notify_error"></div>
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4">    
                                            <div class="society-report-form-box-label">From Date &nbsp;</div>                                                                     
                                            <input type="date" class="society-report-form-box-field-date" id="agreement_from" name="data[Tenant][agreement_from]" value="<?php echo isset($postData['Tenant']['agreement_from']) ? $postData['Tenant']['agreement_from'] :'' ; ?>">
                                        </div>
                                        <div class="col-md-4">    
                                            <div class="society-report-form-box-label">To &nbsp;</div>                                                                     
                                            <input type="date" class="society-report-form-box-field-date" id="agreement_to" name="data[Tenant][agreement_to]" value="<?php echo isset($postData['Tenant']['agreement_to']) ? $postData['Tenant']['agreement_to'] :'' ; ?>">
                                        </div>
                                        <div class="col-md-2">                                
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="">With Tenant Family
                                            </label>
                                        </div>
                                        <div class="col-md-2">                                
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="">Active
                                            </label>
                                        </div>                                                                   
                                    </div>  
                                    <div class="row">
                                        <div class="col-md-12 mr-top-20">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button> 
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_list_of_tenants');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
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
                                                <select name="data[Tenant][building_id]" id="wingBuildingName" class="society-report-form-box-field-dropdown" onchange="getAllBuildingWings(this.value, '#tenant_report_wing_id');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($postData['Tenant']['building_id']) && $postData['Tenant']['building_id'] == $buildingID){?>
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
                                                <select class="society-report-form-box-field-dropdown" id="tenant_report_wing_id" name="data[Tenant][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Flat No &nbsp;&nbsp;</div>                                   
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[Tenant][flat_no]" value="<?php echo isset($postData['Tenant']['flat_no']) ? $postData['Tenant']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[Tenant][flat_no_to]" value="<?php echo isset($postData['Tenant']['flat_no_to']) ? $postData['Tenant']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div><br>
                                    </div>    
                                </div>
                            </form>  
                            <div class="clearfix"></div>
                            <br><br><br> 
                            <div id="print_list_of_tenants">
                                <div class="print-list-of-tenants">
                                    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] :'' ; ?></h5>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] :'' ; ?></div>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] :'' ; ?></div>
                                    <br>
                                    <div class="report-bill">List of Tenant</div>                                 
                                    <span class="bill-date"></span>
                                    <div class="report-bill-outer-section">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered padding-td-none padding-th-none" style="margin-top: -22px;">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Name</th>
                                                        <th>Lease Type</th>
                                                        <th>Agreement on</th>
                                                        <th>Contact no</th>
                                                        <th width="12%">Rent_Per_Month</th>
                                                    </tr>
                                                </thead>
                                                <tbody>                                   
                                                    <?php if(isset($wingTenantData) && count($wingTenantData) > 0){
                                                      $idCount = 1;
                                                      foreach($wingTenantData as $wingTenantInfo){
                                                   ?>
                                                    <tr id="">
                                                        <td colspan="2" style="border:none !important;" ><span class="font-weight-600">Building :- </span><?php echo isset($wingTenantInfo['Building']['building_name']) ? $wingTenantInfo['Building']['building_name'] :'' ; ?></td>
                                                        <td colspan="2" style="border:none !important;"><span class="font-weight-600">Wing :- </span> <?php echo isset($wingTenantInfo['Wing']['wing_name']) ? $wingTenantInfo['Wing']['wing_name'] :'' ; ?></td>                                                        
                                                        <td style="border:none !important;"></td>
                                                        <td style="border-left:none !important;"></td>
                                                    </tr>
                                                    <tr id="<?php echo $idCount; ?>">
                                                        <td><?php echo $idCount; ?></td>
                                                        <td><?php echo isset($wingTenantInfo['Tenant']['tenant_name']) ? $wingTenantInfo['Tenant']['tenant_name'] :'' ; ?></td>
                                                        <td><?php echo isset($wingTenantInfo['Tenant']['lease_type']) ? $wingTenantInfo['Tenant']['lease_type'] :'' ; ?></td>
                                                        <td><?php echo isset($wingTenantInfo['Tenant']['agreement_on']) ? $wingTenantInfo['Tenant']['agreement_on'] :'' ; ?></td>
                                                        <td><?php echo isset($wingTenantInfo['Tenant']['phone']) ? $wingTenantInfo['Tenant']['phone'] :'' ; ?></td>
                                                        <td class="text-right"><?php echo isset($wingTenantInfo['Tenant']['rent_per_month']) ? $wingTenantInfo['Tenant']['rent_per_month'] :'' ; ?></td>
                                                    </tr>
                                                     <?php $idCount++; } }?>
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