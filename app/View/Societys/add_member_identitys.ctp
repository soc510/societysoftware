<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            	<?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Identity</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form  method="post" id="buildingIdentityForm" name="buildingIdentityForm" autocomplete="off" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="member_prefix" class="control-label">Member Prefix</label>
                                            <input type="hidden" name="data[Member][member_id]" value="<?php echo isset($this->request->data['Member']['id']) ? $this->request->data['Member']['id'] :''; ?>">			
                                            <select class="form-control" id="member_prefix" name="data[Member][member_prefix]">
                                                    <option value="">Select type</option>
                                                    <option value="Mr." <?php if(isset($this->request->data['Member']['member_prefix']) && $this->request->data['Member']['member_prefix'] == 'Mr.') { echo 'selected';} ?>>Mr.</option>
                                                    <option value="Mrs." <?php if(isset($this->request->data['Member']['member_prefix']) && $this->request->data['Member']['member_prefix'] == 'Mrs.') { echo 'selected';} ?>>Mrs.</option>
                                                    <option value="Ms." <?php if(isset($this->request->data['Member']['member_prefix']) && $this->request->data['Member']['member_prefix'] == 'Ms.') { echo 'selected';} ?>>Ms.</option>
                                                    <option value="M/s." <?php if(isset($this->request->data['Member']['member_prefix']) && $this->request->data['Member']['member_prefix'] == 'M/s.') { echo 'selected';} ?>>M/s.</option>
                                                    <option value="Shri." <?php if(isset($this->request->data['Member']['member_prefix']) && $this->request->data['Member']['member_prefix'] == 'Shri.') { echo 'selected';} ?>>Shri.</option>
                                                    <option value="Smt." <?php if(isset($this->request->data['Member']['member_prefix']) && $this->request->data['Member']['member_prefix'] == 'Smt.') { echo 'selected';} ?>>Smt.</option>
                                                    <option value="Dr." <?php if(isset($this->request->data['Member']['member_prefix']) && $this->request->data['Member']['member_prefix'] == 'Dr.') { echo 'selected';} ?>>Dr.</option>
                                            </select>                                           
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="member_name" class="control-label">Member Name<span class="required">*</span></label>
                                            <input type="text" class="form-control" id="member_name" name="data[Member][member_name]" value="<?php echo isset($this->request->data['Member']['member_name']) ? $this->request->data['Member']['member_name'] :''; ?>" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="joint_member_name" class="control-label">Joint Member Name</label>
                                            <input type="text" class="form-control" id="joint member_name" name="data[Member][joint_member_name]" value="<?php echo isset($this->request->data['Member']['joint_member_name']) ? $this->request->data['Member']['joint_member_name'] :''; ?>">
                                        </div>
                                    </div>                                    
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="flat_no" class="control-label">Flat No<span class="required">*</span></label>
                                            <input type="text" class="form-control" id="flat_no" name="data[Member][flat_no]" value="<?php echo isset($this->request->data['Member']['flat_no']) ? $this->request->data['Member']['flat_no'] :''; ?>" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="flat_no" class="control-label">Email id</label>
                                            <input type="email" class="form-control" id="email_id" name="data[Member][member_email]" value="<?php echo isset($this->request->data['Member']['member_email']) ? $this->request->data['Member']['member_email'] :''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="flat_no" class="control-label">Phone no</label>
                                            <input type="text" class="form-control" id="member_phone" name="data[Member][member_phone]" maxlength="10" value="<?php echo isset($this->request->data['Member']['member_phone']) ? $this->request->data['Member']['member_phone'] :''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-2">
                                        <div class="form-group">
                                            <label for="member_photo" class="control-label">Member Profile Picture</label>
                                            <input type="file" class="form-control" id="member_photo" name="data[Member][member_photo]">
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="building_id" class="control-label">Building <span class="required">*</span></label>
                                            <select name="data[Member][building_id]" id="wingBuildingName" class="form-control input-group-lg" onchange="getAllBuildingWings(this.value, '#wing_id');" required>
                                                <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($this->request->data['Member']['building_id']) && $this->request->data['Member']['building_id'] == $buildingID){?>
                                                <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                                    <?php }else {?>
                                                <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="wing_id" class="control-label">Wing</label>
                                            <select name="data[Member][wing_id]" id="wing_id" class="form-control input-group-lg">
                                                <?php 
                                                if(!empty($societyBuildingwings)){
                                                foreach($societyBuildingwings as $wing){ ?>
                                                    <option value="<?php echo $wing['Wing']['id'];?>" <?php if($wing['Wing']['building_id'] == $buildingID) echo "selected"; ?> ><?php echo $wing['Wing']['wing_name'];; ?></option>
                                                <?php 

                                                   }
                                                }
                                            
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-0">
                                        <div class="form-group">
                                            <label for="GSTIN" class="control-label">Member GSTIN No.</label>
                                            <input type="text" class="form-control" id="gstin_no" name="data[Member][gstin_no]" value="<?php echo isset($this->request->data['Member']['gstin_no']) ? $this->request->data['Member']['gstin_no'] :0; ?>" placeholder="Enter GSTIN No.">
                                        </div>
                                    </div>   
                                     <div class="col-md-2 padding-0">
                                        <div class="form-group">
                                            <label for="member_parking_no" class="control-label">Member Parking No.</label>
                                            <input type="text" class="form-control" id="member_parking_no" name="data[Member][member_parking_no]" value="<?php echo isset($this->request->data['Member']['member_parking_no']) ? $this->request->data['Member']['member_parking_no'] :0; ?>" placeholder="Enter Parking No.">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="unit_type" class="control-label">Unit Type</label>				
                                            <select class="form-control" id="unit_type" name="data[Member][unit_type]">
                                                    <option value="R" <?php if(isset($this->request->data['Member']['unit_type']) && $this->request->data['Member']['unit_type'] == 'R') { echo 'selected';} ?>>Residential</option>
                                                    <option value="C" <?php if(isset($this->request->data['Member']['unit_type']) && $this->request->data['Member']['unit_type'] == 'C') { echo 'selected';} ?>>Commercial</option>
                                                    <option value="B" <?php if(isset($this->request->data['Member']['unit_type']) && $this->request->data['Member']['unit_type'] == 'B') { echo 'selected';} ?>>Both</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>                                
				<div class="row">
                                <div class="col-md-1 padding-1">
                                    <div class="form-group">
                                        <label for="floor_no" class="control-label">Floor No.<span class="required">*</span></label>
                                        <input type="text" class="form-control"  id="floor_no" name="data[Member][floor_no]" value="<?php echo isset($this->request->data['Member']['floor_no']) ? $this->request->data['Member']['floor_no'] :''; ?>" placeholder="Enter Floor No." required="">
                                    </div>
                                </div>
				<div class="col-md-1 padding-1">
                                        <div class="form-group">
                                            <label for="area" class="control-label">Area</label>
                                            <input type="text" class="form-control" id="area" name="data[Member][area]" value="<?php echo isset($this->request->data['Member']['area']) ? $this->request->data['Member']['area'] :0; ?>" placeholder="Enter Area" required="">
                                        </div>
                                    </div>                                    
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="carpet" class="control-label">Carpet</label>
                                            <input type="text" class="form-control" id="carpet" name="data[Member][carpet]" value="<?php echo isset($this->request->data['Member']['carpet']) ? $this->request->data['Member']['carpet'] :0; ?>" placeholder="Enter Carpet" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="commercial" class="control-label">Commercial</label>
                                            <input type="text" class="form-control" id="commercial" name="data[Member][commercial]" value="<?php echo isset($this->request->data['Member']['commercial']) ? $this->request->data['Member']['commercial'] :0; ?>"  placeholder="Enter Commercial" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="residential" class="control-label">Residential</label>
                                            <input type="text" class="form-control" id="residential" name="data[Member][residential]" value="<?php echo isset($this->request->data['Member']['residential']) ? $this->request->data['Member']['residential'] :0; ?>"  required="">
                                        </div>
                                    </div>                               
                                    <div class="col-md-2 padding-0">
                                        <div class="form-group">
                                            <label for="terrace" class="control-label">Terrace</label>
                                            <input type="text" class="form-control" id="terrace" name="data[Member][terrace]" value="<?php echo isset($this->request->data['Member']['terrace']) ? $this->request->data['Member']['terrace'] :0; ?>" placeholder="Enter Terrace" required="">
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-2 padding-0">
                                        <div class="form-group">
                                            <label for="op_bill_date" class="control-label">Opening Bill Date</label>
                                            <input type="date" class="form-control" id="op_bill_date" name="data[Member][op_bill_date]" value="<?php echo isset($this->request->data['Member']['op_bill_date']) ? $this->request->data['Member']['op_bill_date'] :0; ?>" placeholder="Enter Opening Bill Date" >
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-0">
                                        <div class="form-group">
                                            <label for="op_bill_due_date" class="control-label">Opening Bill Due Date</label>
                                            <input type="date" class="form-control" id="op_bill_due_date" name="data[Member][op_bill_due_date]" value="<?php echo isset($this->request->data['Member']['op_bill_due_date']) ? $this->request->data['Member']['op_bill_due_date'] :0; ?>" placeholder="Enter Opening Principal" >
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-0">
                                        <div class="form-group">
                                            <label for="area" class="control-label">Opening Principal</label>
                                            <input type="text" class="form-control" id="op_principal" name="data[Member][op_principal]" value="<?php echo isset($this->request->data['Member']['op_principal']) ? $this->request->data['Member']['op_principal'] :0; ?>" placeholder="Enter Opening Principal" required="">
                                        </div>
                                    </div>                                    
                                    <div class="col-md-2 padding-2">
                                        <div class="form-group">
                                            <label for="carpet" class="control-label">Opening Interest</label>
                                            <input type="text" class="form-control" id="op_interest" name="data[Member][op_interest]" value="<?php echo isset($this->request->data['Member']['op_interest']) ? $this->request->data['Member']['op_interest'] :0; ?>" placeholder="Enter Opening Interest" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-2">
                                        <div class="form-group">
                                            <label for="carpet" class="control-label">Opening Tax</label>
                                            <input type="text" class="form-control" id="op_tax" name="data[Member][op_tax]" value="<?php echo isset($this->request->data['Member']['op_tax']) ? $this->request->data['Member']['op_tax'] :0; ?>" placeholder="Enter Opening Tax" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-2">
                                        <div class="form-group">
                                            <label for="penality" class="control-label">Penality</label>
                                            <input type="text" class="form-control" id="penality" name="data[Member][penality]" value="<?php echo isset($this->request->data['Member']['penality']) ? $this->request->data['Member']['penality'] :0; ?>" placeholder="Enter Penality" required="">
                                        </div>
                                    </div>                                    
                                </div>
                            <div class="row">
                                <div class="col-md-2 padding-0">
                                    <div class="form-group">
                                        <label for="supplementary_principal" class="control-label">Supplementary Principal</label>
                                        <input type="text" class="form-control" id="supplementary_principal" name="data[Member][supplementary_principal]" value="<?php echo isset($this->request->data['Member']['supplementary_principal']) ? $this->request->data['Member']['supplementary_principal'] :0; ?>" placeholder="Enter  Principal" required="">
                                    </div>
                                </div>                                    
                                <div class="col-md-2 padding-2">
                                    <div class="form-group">
                                        <label for="supplementary_interest" class="control-label">Supplementary Interest</label>
                                        <input type="text" class="form-control" id="supplementary_interest" name="data[Member][supplementary_interest]" value="<?php echo isset($this->request->data['Member']['supplementary_interest']) ? $this->request->data['Member']['supplementary_interest'] :0; ?>" placeholder="Enter interest" required="">
                                    </div>
                                </div>
                                <div class="col-md-2 padding-2">
                                    <div class="form-group">
                                        <label for="supplementary_tax" class="control-label">Supplementary Tax</label>
                                        <input type="text" class="form-control" id="supplementary_tax" name="data[Member][supplementary_tax]" value="<?php echo isset($this->request->data['Member']['supplementary_tax']) ? $this->request->data['Member']['supplementary_tax'] :0; ?>" placeholder="Enter supplementary Tax" required="">
                                    </div>
                                </div>
                                <div class="col-md-2 padding-2">
                                    <div class="form-group">
                                        <label for="supplementary_penality" class="control-label">Supplementary Penality</label>
                                        <input type="text" class="form-control" id="supplementary_penality" name="data[Member][supplementary_penality]" value="<?php echo isset($this->request->data['Member']['supplementary_penality']) ? $this->request->data['Member']['supplementary_penality'] :0; ?>" placeholder="Enter Penality" required="">
                                    </div>
                                </div>  
                            </div>
                                <div class="row">
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="report_access" class="control-label">Report Access</label>
                                            <select name="data[Member][report_access][]" id="report_access" class="form-control input-group-lg" multiple="yes">
                                                <option value="">Select Reports</option>
                                                    <?php if(isset($memberReports) && count($memberReports) > 0) {
                                                         if(isset($this->request->data['Member']['report_access']) && $this->request->data['Member']['report_access'] != '' && !is_null($this->request->data['Member']['report_access'])){
                                                        $access = explode(',', $this->request->data['Member']['report_access']);
                                                    }
                                                    
                                                    foreach($memberReports as $k => $report){
                                                        $selected = '';//print_r($report);
                                                        $reportID = $report['MemberReports']['id'];
                                                        $reportName = $report['MemberReports']['report_name'];
                                                        if (in_array($reportID,$access)){
                                                            $selected = ' selected';
                                                        }
                                                    ?>
                                                    
                                                <option value="<?php echo $reportID; ?>" <?php echo $selected;?>><?php echo $reportName; ?></option>
                                                    <?php } }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <div class="col-sm-offset-2 col-sm-10"> 
                                        <button type="submit" data-toggle="tooltip" data-original-title="Add / Update member details" class="btn btn-success btn-anim" onclick=""><i class="icon-rocket"></i><span class="btn-text"><?php if(isset($this->request->data['Member']['id']) && !empty($this->request->data['Member']['id'])) { ?>Update Member<?php }else{ ?> Add Member <?php } ?></span></button>                                        
                                        <a type="button" data-toggle="tooltip" data-original-title="Go to member list" class="btn btn-success btn-anim" href="<?php echo Router::url(array('controller' =>'societys','action' =>'member_identitys')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

