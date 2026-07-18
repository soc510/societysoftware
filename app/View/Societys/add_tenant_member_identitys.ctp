<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Tenant / Nominal Member Identity</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <form method="post" id="TenantMemeberForm" name="TenantMemeberForm" autocomplete="off" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="tenant_name" class="control-label">Tenant Name <span class="required">*</span></label>
                                            <input type="hidden" class="form-control" id="tenant_id" name="data[Tenant][id]" value="<?php echo isset($this->request->data['Tenant']['id']) ? $this->request->data['Tenant']['id'] :''; ?>">
                                            <input type="text" class="form-control" id="tenant_name" name="data[Tenant][tenant_name]" value="<?php echo isset($this->request->data['Tenant']['tenant_name']) ? $this->request->data['Tenant']['tenant_name'] :''; ?>" placeholder="Enter tenant name" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="building_id" class="control-label">Building <span class="required">*</span></label>
                                            <select name="data[Tenant][building_id]" id="wingBuildingName" class="form-control input-group-lg" onchange="getAllBuildingWings(this.value, '#wing_id');" required>
                                                <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($this->request->data['Tenant']['building_id']) && $this->request->data['Tenant']['building_id'] == $buildingID){?>
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
                                            <select name="data[Tenant][wing_id]" id="wing_id" class="form-control input-group-lg">
                                                <?php if(isset($this->request->data['Tenant']['id']) && $this->request->data['Tenant']['id']){?>
                                                <option value="<?php echo $this->request->data['Tenant']['id']; ?>"><?php echo $this->request->data['Tenant']['wing_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>                                    
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="flat_no" class="control-label">Flat No<span class="required">*</span></label>
                                            <input type="text" class="form-control" id="flat_no" name="data[Tenant][flat_no]" value="<?php echo isset($this->request->data['Tenant']['flat_no']) ? $this->request->data['Tenant']['flat_no'] :''; ?>" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="lease_type" class="control-label">Lease Type<span class="required">*</span></label>
                                            <select name="data[Tenant][lease_type]" id="lease_type" class="form-control input-group-lg">
                                                <option value="">select</option>
                                                <option value="1">Lease 1</option>
                                                <option value="2">Lease 2</option>
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="agreement_on" class="control-label">Agreement On<span class="required">*</span></label>
                                             <input type="date" class="form-control" id="agreement_on" name="data[Tenant][agreement_on]" value="<?php echo isset($this->request->data['Tenant']['agreement_on']) ? $this->request->data['Tenant']['agreement_on'] :''; ?>">
                                        </div>
                                    </div>                        
                                </div> 
                                <div class="row"> 
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="rent_per_month" class="control-label">Rent/Month<span class="required">*</span></label>
                                             <input type="text" class="form-control" id="rent_per_month" name="data[Tenant][rent_per_month]" value="<?php echo isset($this->request->data['Tenant']['rent_per_month']) ? $this->request->data['Tenant']['rent_per_month'] :''; ?>" placeholder="Enter Rent / Month" required="">
                                        </div>
                                    </div>                                       
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="nationality" class="control-label">Nationality<span class="required">*</span></label>
                                            <select name="data[Tenant][nationality]" id="nationality" class="form-control input-group-lg">
                                                <option value="">Select Nationality</option>
                                                <option value="1">Indian</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 padding-0">
                                        <div class="form-group">
                                            <label for="address" class="control-label">Address<span class="required">*</span></label>
                                             <textarea type="text" class="form-control" id="address" name="data[Tenant][address]" value="<?php echo isset($this->request->data['Tenant']['address']) ? $this->request->data['Tenant']['address'] :''; ?>" placeholder="Enter Address" required=""><?php echo isset($this->request->data['Tenant']['address']) ? $this->request->data['Tenant']['address'] :''; ?></textarea>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="country" class="control-label">Country<span class="required">*</span></label>
                                            <select name="data[Tenant][country_id]" id="TenantCountryName" class="form-control input-group-lg" required>
                                                    <option value="">Select Country</option>
                                                    <?php if(isset($countryList) && count($countryList) > 0) {
                                                    foreach($countryList as $countryId => $countryName){?>
                                                    <?php if(isset($this->request->data['Tenant']['country_id']) && $this->request->data['Tenant']['country_id'] == $countryId){?>
                                                    <option value="<?php echo $countryId; ?>" selected><?php echo $countryName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $countryId; ?>"><?php echo $countryName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="state" class="control-label">State<span class="required">*</span></label>
                                            <select name="data[Tenant][state_id]" id="TenantStateName" class="form-control input-group-lg" required>
                                                    <option value="">Select State</option>
                                                    <?php if(isset($stateList) && count($stateList) > 0) {
                                                    foreach($stateList as $stateId => $stateName){?>
                                                    <?php if(isset($this->request->data['Tenant']['state_id']) && $this->request->data['Tenant']['state_id'] == $stateId){?>
                                                    <option value="<?php echo $stateId; ?>" selected><?php echo $stateName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $stateId; ?>"><?php echo $stateName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                            </select>
                                        </div>
                                    </div>                                      
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="city" class="control-label">City<span class="required">*</span></label>
                                             <input type="text" class="form-control" id="city" name="data[Tenant][city]" value="<?php echo isset($this->request->data['Tenant']['city']) ? $this->request->data['Tenant']['city'] :''; ?>" placeholder="Enter City" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <div class="form-group">
                                            <label for="phone" class="control-label">Phone<span class="required">*</span></label>
                                             <input type="text" class="form-control" name="data[Tenant][phone]" value="<?php echo isset($this->request->data['Tenant']['phone']) ? $this->request->data['Tenant']['phone'] :''; ?>" placeholder="Enter phone number" maxlength="10" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-0">
                                        <div class="form-group">
                                            <label for="email" class="control-label">Email<span class="required">*</span></label>
                                            <input type="email" class="form-control" id="email" name="data[Tenant][email]" value="<?php echo isset($this->request->data['Tenant']['email']) ? $this->request->data['Tenant']['email'] :''; ?>" placeholder="Enter Email Id" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                     <div class="col-sm-offset-2 col-sm-10"> 
                                        <button type="submit" data-toggle="tooltip" data-original-title="Add / Update Tenant Member Details" class="btn btn-success btn-anim" onclick=""><i class="icon-rocket"></i><span class="btn-text"><?php if(isset($this->request->data['Tenant']['id']) && !empty($this->request->data['Tenant']['id'])) { ?>Update tenant member<?php }else{ ?> Add tenant member<?php } ?></span></button>                                        
                                        <a type="button" data-toggle="tooltip" data-original-title="Go to tenanat list" class="btn btn-success btn-anim" href="<?php echo Router::url(array('controller' =>'societys','action' =>'tenant_member_identitys')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
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