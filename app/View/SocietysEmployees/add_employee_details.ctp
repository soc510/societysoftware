<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Add Society Employee</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <form class="form-horizontal" method="post" id="employeeDetailsForm" name="employeeDetailsForm" enctype="multipart/form-data" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-6">    
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="emp_name">Employee Name <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="hidden" name="data[Employee][id]" value="<?php echo isset($this->request->data['Employee']['id']) ? $this->request->data['Employee']['id'] :''; ?>">
                                                <input type="text" class="form-control" id="emp_name" name="data[Employee][emp_name]" placeholder="Enter Employee Name" value="<?php echo isset($this->request->data['Employee']['emp_name']) ? $this->request->data['Employee']['emp_name'] :''; ?>"  onblur="societyEmployeeShortCode(this.value)" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">    
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="emp_code">Employee Code <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="emp_code" name="data[Employee][emp_code]" value="<?php echo isset($this->request->data['Employee']['emp_code']) ? $this->request->data['Employee']['emp_code'] :''; ?>"  readonly>
                                            </div>
                                        </div>
                                    </div>                                       
                                </div>
                                <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="emp_category_id">Employee Category <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <select name="data[Employee][emp_category_id]" id="emp_category_id" class="form-control input-group-lg"  required>
                                                    <option value="">Select Category</option>
                                                    <?php if(isset($employeeCategoryDropDownList) && count($employeeCategoryDropDownList) > 0) {
                                                    foreach($employeeCategoryDropDownList as $categoryId => $categoryName){?>
                                                    <?php if(isset($this->request->data['Employee']['emp_category_id']) && $this->request->data['Employee']['emp_category_id'] == $categoryId){?>
                                                    <option value="<?php echo $categoryId; ?>" selected><?php echo $categoryName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $categoryId; ?>"><?php echo $categoryName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="emp_sub_category_name">Employee Sub Category <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <select name="data[Employee][emp_sub_category_id]" id="emp_sub_category_id" class="form-control input-group-lg"  required>
                                                    <option value="">Select Category</option>
                                                    <?php if(isset($employeeSubCategoryDropDownList) && count($employeeSubCategoryDropDownList) > 0) {
                                                    foreach($employeeSubCategoryDropDownList as $subcategoryId => $subcategoryName){?>
                                                    <?php if(isset($this->request->data['Employee']['emp_sub_category_id']) && $this->request->data['Employee']['emp_sub_category_id'] == $subcategoryId){?>
                                                    <option value="<?php echo $subcategoryId; ?>" selected><?php echo $subcategoryName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $subcategoryId; ?>"><?php echo $subcategoryName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                            </select>
                                        </div>
                                    </div>
                                    </div>                                       
                                </div>
                                <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="joining_date">Joining Date<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="date" class="form-control" id="joining_date" name="data[Employee][joining_date]" value="<?php echo isset($this->request->data['Employee']['joining_date']) ? $this->request->data['Employee']['joining_date'] :''; ?>" required="">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group" style="margin-top:5px;">
                                        <label class="control-label col-md-3" for="gender">Gender <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <select name="data[Employee][gender]" id="gender" class="form-control input-group-lg">
                                                    <option value="">select</option>
                                                    <option <?php echo (isset($this->request->data['Employee']['gender']) && $this->request->data['Employee']['gender'] == "Male" ? 'selected' : ''); ?> value="Male">Male</option>
                                                    <option <?php echo (isset($this->request->data['Employee']['gender']) && $this->request->data['Employee']['gender'] == "Female" ? 'selected' : ''); ?> value="Female">Female</option>
                                             </select>
                                        </div>
                                    </div>
                                    </div>                                       
                                </div>
                                <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="date_of_birth">Date of Birth<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="date" class="form-control" id="date_of_birth" name="data[Employee][date_of_birth]" value="<?php echo isset($this->request->data['Employee']['date_of_birth']) ? $this->request->data['Employee']['date_of_birth'] :''; ?>" required="">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group" style="margin-top:5px;">
                                        <label class="control-label col-md-3" for="marrital_status">Martial Status <span class="required">*</span></label>
                                        <div class="col-md-9">
                                             <select name="data[Employee][marrital_status]" id="marrital_status" class="form-control input-group-lg">
                                                    <option value="">select</option>
                                                    <option <?php echo (isset($this->request->data['Employee']['marrital_status']) && $this->request->data['Employee']['marrital_status'] == "married" ? 'selected' : ''); ?> value="married">Marrired</option>
                                                    <option <?php echo (isset($this->request->data['Employee']['marrital_status']) && $this->request->data['Employee']['marrital_status'] == "unmarrired" ? 'selected' : ''); ?> value="unmarrired">Unmarrired</option>
                                                    <option <?php echo (isset($this->request->data['Employee']['marrital_status']) && $this->request->data['Employee']['marrital_status'] == "divorcee" ? 'selected' : ''); ?> value="divorcee">Divorcee</option>
                                             </select>
                                        </div>
                                    </div>
                                    </div>                                       
                                </div>
                                <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="date_of_leaving">Date of Leaving<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="date" class="form-control" id="date_of_leaving" name="data[Employee][date_of_leaving]" value="<?php echo isset($this->request->data['Employee']['date_of_leaving']) ? $this->request->data['Employee']['date_of_leaving'] :''; ?>" required="">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group" style="margin-top:5px;">
                                        <label class="control-label col-md-3" for="religion">Religion <span class="required">*</span></label>
                                        <div class="col-md-9">
                                             <select name="data[Employee][religion]" id="religion" class="form-control input-group-lg">
                                                    <option value="">select</option>
                                                    <option value="hindu">Hindu</option>
                                                    <option value="muslim">Muslim</option>
                                                    <option value="cathelic">Cathelic</option>
                                             </select>
                                        </div>
                                    </div>
                                    </div>                                       
                                </div>
                                 <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="qualification">Qualification <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="qualification" name="data[Employee][qualification]" placeholder="Enter Qualification" value="<?php echo isset($this->request->data['Employee']['qualification']) ? $this->request->data['Employee']['qualification'] :''; ?>"  required="">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for=" pan_no">PAN no. <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="emp_sub_category_name" name="data[Employee][pan_no]" placeholder="Enter PAN No." value="<?php echo isset($this->request->data['Employee']['pan_no']) ? $this->request->data['Employee']['pan_no'] :''; ?>" required="">
                                        </div>
                                    </div>
                                    </div>                                       
                                </div>
                                <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="gstin_no">GSTIN no. <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="emp_sub_category_name" name="data[Employee][gstin_no]" placeholder="Enter GSTIN No." value="<?php echo isset($this->request->data['Employee']['gstin_no']) ? $this->request->data['Employee']['gstin_no'] :''; ?>" required="">
                                        </div>
                                    </div>
                                    </div> 
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="emp_photo">Profile Picture</label>
                                        <div class="col-md-9">
                                            <input type="file" class="form-control" id="emp_photo" name="data[Employee][emp_photo]" value="">
                                            <div class="employeeProfilePic" style="border:1px solid #c8c8c8;height:100px;"><img src="<?php echo isset($this->request->data['Employee']['emp_photo']) ? $this->webroot.$this->request->data['Employee']['emp_photo'] :''; ?>" alt="No image added" style="width:131px;"></div>
                                        </div>                                        
                                    </div>
                                    </div>                                      
                                </div>
                                <div class="form-group mb-0">
                                     <div class="col-sm-offset-2 col-sm-10"> 
                                        <button type="submit" data-toggle="tooltip" data-original-title="Add / Update member details" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text"><?php if(isset($this->request->data['EmployeeCategory']['id']) && !empty($this->request->data['EmployeeCategory']['id'])) { ?>Update Category<?php }else{ ?> Add Category <?php } ?></span></button>                                        
                                       <a type="button" data-toggle="tooltip" data-original-title="Go to member list" class="btn btn-success btn-anim" href="<?php echo Router::url(array('controller' =>'societys_employees','action' =>'employee_details')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
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