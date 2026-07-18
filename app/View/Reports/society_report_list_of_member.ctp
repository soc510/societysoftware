<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">List of Members</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form class="" method="post" id="listOfMemberFrm" name="listOfMemberFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">  
                                <div id="show_notify_error"></div>
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-5">    
                                            <div class="society-report-form-box-label">Report Type &nbsp;</div>                                                                     
                                            <select class="society-report-form-box-field-dropdown" id="report_type" name="data[Member][report_type]">
                                                <option value="Member List"<?php if(isset($postData['Member']['report_type']) && $postData['Member']['report_type']=='Member List'){echo 'selected';}?>>Member List</option> 
                                                <!-- <option value="">Member Detail</option> 
                                                 <option value="">Member List With Dependent</option> 
                                                 <option value="">Senior Citizen Member List</option>
                                                 <option value="">Member Address</option> 
                                                 <option value="">Nominee Details</option> 
                                                 <option value="">Loan Details</option> 
                                                 <option value="">Joint Member</option> -->
                                            </select> 
                                        </div>
                                        <div class="col-md-2">                                
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="">Inactive
                                            </label>
                                        </div>                                                                  
                                    </div> 
                                    <div class="mr-top-20">                                
                                        <div class="form-group">
                                            <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                                                            
                                            <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_list_of_members');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                            <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>                                                                                                                  
                                            <a type="button" data-toggle="tooltip" data-original-title="Cancel" class="btn btn-success btn-anim btn-sm" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>  
                                        </div>
                                    </div>  
                                    <!--
                                    <div class="report-export-pdf-btn"><a class="export-pdf-btn pull-right" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class=""> Export to PDF</span></a></div>
                                    <div class="report-print-btn"><a class="print-btn pull-right" href="javascript:void(0);" onclick="printAllReports('print_list_of_members');"><i class="fa fa-print"></i><span class=""> Print Friendly</span></a></div> 
                                    -->
                                </div> 
                                <div class="col-md-4 right-section">
                                    <div class="row">   
                                        <div class="right-title">Select (if required) </div> 
                                        <div class="col-md-12">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Building &nbsp;</div>                                    
                                                <select name="data[Member][building_id]" id="wingBuildingName" class="society-report-form-box-field-dropdown" onchange="getAllBuildingWings(this.value, '#member_wing_id');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($postData['Member']['building_id']) && $postData['Member']['building_id'] == $buildingID){?>
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
                                                <div class="account-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                                <select class="account-report-form-box-field-dropdown" id="member_wing_id" name="data[MemberPayment][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Unit No &nbsp;&nbsp;</div>                                   
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[Member][flat_no]" value="<?php echo isset($postData['Member']['flat_no']) ? $postData['Member']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[Member][flat_no_to]" value="<?php echo isset($postData['Member']['flat_no_to']) ? $postData['Member']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div><br>
                                    </div>    
                                </div>  
                                <div class="clearfix"></div>
                                <br><br><br>
                            </form> 
                            <div id="print_list_of_members">
                                <div class="print-list-of-members">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] :'' ; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] :'' ; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] :'' ; ?></div>                            
                                        <br>                            
                                        <div class="report-bill">List of Member</div>                                                             
                                        <span class="bill-date"></span>
                                    </div>                            
                                    <div class="report-bill-outer-section">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered padding-td-none padding-th-none" style="margin-top: -22px;">
                                                <thead>
                                                    <tr>
                                                        <th width="2%">Sr.</th>
                                                        <th></th>
                                                        <th width="10%">Flat No</th>
                                                        <th>Member Name</th>
                                                        <th class="text-center">Email Id</th>
                                                        <th class="text-center">Occupant</th>
                                                        <th class="text-center">Phone No.</th>
                                                        <th width="8%" class="text-center">Area</th>
                                                    </tr>
                                                </thead>
                                                <tbody>                                      
                                            <?php 

                                            if(isset($wingMemberData) && count($wingMemberData) > 0){
                                              $idCount = 1;
                                              foreach($wingMemberData as $memberData){
                                            ?>
                                                    <tr id="">
                                                        <td><?= $idCount;?></td>
                                                        <td colspan="1" style="border:none !important;" ><span class="font-weight-600">Building :- <?php echo isset($memberData['Building']['building_name']) ? $memberData['Building']['building_name'] :'' ; ?></span><br><span class="font-weight-600">Wing :- <?php echo isset($memberData['Wing']['wing_name']) ? $memberData['Wing']['wing_name'] :'' ; ?></span></td>
                                                        <td><?php echo isset($memberData['Member']['flat_no']) ? $memberData['Member']['flat_no'] :'' ; ?></td>
                                                        <td><?php echo $memberData['Member']['member_prefix']; echo ' ';echo isset($memberData['Member']['member_name']) ? $memberData['Member']['member_name'] :'' ; ?></td>
                                                        <td><?php echo isset($memberData['Member']['member_email']) ? $memberData['Member']['member_email'] :'' ; ?></td>
                                                        <td></td>
                                                        <td><?php echo isset($memberData['Member']['member_phone']) ? $memberData['Member']['member_phone'] :'' ; ?></td>
                                                        <td><?php echo isset($memberData['Member']['area']) ? $memberData['Member']['area'] :'' ; ?></td>
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