<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Change Personal Details</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <form class="form-horizontal" action="<?php echo Router::url(array('controller' =>'resellers','action' =>'update_profiles')); ?>" method="post" id="ResellerProfileForm" name="ResellerProfileForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="firstName">First Name <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="ResellerFirstname" name="data[Reseller][firstname]" placeholder="Enter First Name" required="" value="<?php echo isset($this->request->data['Reseller']['firstname']) ? $this->request->data['Reseller']['firstname'] :''; ?>">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="lastName">Last Name <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="lastName" name="data[Reseller][lastname]" placeholder="Enter Last Name" value="<?php echo isset($this->request->data['Reseller']['lastname']) ? $this->request->data['Reseller']['lastname'] :''; ?>"  required="">
                                        </div>
                                    </div>
                                    </div>    
                                </div>
                                <div class="row">                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="emailId">Email Id<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text"  class="form-control" id="emailId" name="data[Reseller][email]" placeholder="Email Id" data-parsley-type="email" maxlength="50" value="<?php echo isset($this->request->data['Reseller']['email']) ? $this->request->data['Reseller']['email'] :''; ?>"  required="">
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="date_of_birth">Date of Birth</label>
                                            <div class="col-md-9">
                                                <input type="date"  class="form-control" id="date_of_birth" name="data[Reseller][date_of_birth]" value="<?php echo isset($this->request->data['Reseller']['date_of_birth']) ? $this->request->data['Reseller']['date_of_birth'] :''; ?>">
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="mobile">Mobile No<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text"  class="form-control" id="contact_no" name="data[Reseller][contact_no]" placeholder="Mobile No" value="<?php echo isset($this->request->data['Reseller']['contact_no']) ? $this->request->data['Reseller']['contact_no'] :''; ?>"  required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="address">Address<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="address" name="data[Reseller][address]" placeholder="Address" value="<?php echo isset($this->request->data['Reseller']['address']) ? $this->request->data['Reseller']['address'] :''; ?>"  required="">
                                        </div>
                                    </div>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="country">Country<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <select name="data[Reseller][country]" id="country" class="form-control input-group-lg">
                                                    <option value="">Select country</option>
                                                    <?php if(count($countryList) > 0) {
                                                    foreach($countryList as $countryID => $countryName){?>
                                                    <?php if(isset($this->request->data['Reseller']['country']) && $this->request->data['Reseller']['country'] == $countryID){?>
                                                    <option value="<?php echo $countryID; ?>" selected><?php echo $countryName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $countryID; ?>"><?php echo $countryName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">  
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="state">State</label>
                                            <div class="col-md-9">
                                                <select name="data[Reseller][state]" id="state" class="form-control input-group-lg">
                                                    <option value="">Select state</option>
                                                    <?php if(count($stateList) > 0) {
                                                    foreach($stateList as $stateID => $stateName){?>
                                                    <?php if(isset($this->request->data['Reseller']['state']) && $this->request->data['Reseller']['state'] == $stateID){?>
                                                    <option value="<?php echo $stateID; ?>" selected><?php echo $stateName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $stateID; ?>"><?php echo $stateName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                                <div class="row">
                                   <!--<div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="city">City<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <select name="data[Reseller][city]" id="city" class="form-control input-group-lg">
                                                    <option value="">select</option>
                                                    <option value="MUM">Mumbai</option>
                                                    <option value="SR">Surat</option>
                                                    <option value="BG">Bangalore</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <label class="control-label col-md-3" for="address">City<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="city" name="data[Reseller][city]" placeholder="Address" value="<?php echo isset($this->request->data['Reseller']['city']) ? $this->request->data['Reseller']['city'] :''; ?>"  required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="address">License No.<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="license_no" name="data[Reseller][license_no]" placeholder="License No" value="<?php echo isset($this->request->data['Reseller']['license_no']) ? $this->request->data['Reseller']['license_no'] :''; ?>"  required="">
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label class="control-label col-md-3" for="address">Job Role<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="job_role" name="data[Reseller][job_role]" placeholder="Job Role" value="<?php echo isset($this->request->data['Reseller']['job_role']) ? $this->request->data['Reseller']['job_role'] :''; ?>"  required="">
                                            </div>
                                        </div>
                                    </div>                                    
                                 </div>
                                <div class="form-group mb-0">
                                     <div class="col-sm-offset-2 col-sm-10"> 
                                        <button type="submit" data-toggle="tooltip" data-original-title="Update society details" class="btn btn-success btn-anim" onclick=""><i class="icon-rocket"></i><span class="btn-text">Update</span></button>                                        
                                        <a type="button" data-toggle="tooltip" data-original-title="Go to dashboard" class="btn btn-success btn-anim" href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                        
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

