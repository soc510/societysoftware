<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Employee Details</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <form class="form-horizontal" method="post" id="employeeDetailsForm" name="employeeDetailsForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="emp_name">Employee Name <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="emp_name" name="data['Employee']['emp_name']" placeholder="Enter Employee Name" value=""  required="">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="emp_code">Employee Code <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="emp_code" name="data['Employee']['emp_code']" value="1"  disabled>
                                        </div>
                                    </div>
                                    </div>                                       
                                </div>
                                <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="emp_category_id">Staff Category <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="emp_category_id" name="data['Employee']['emp_category_id']" placeholder="Enter Staff Category" value=""  required="">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="emp_sub_category_name">Sub Category <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="emp_sub_category_name" name="data['Employee']['emp_sub_category_name']" placeholder="Enter Sub Category" value="" required="">
                                        </div>
                                    </div>
                                    </div>                                       
                                </div>

                                <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="joining_date">Joining Date<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="date" class="form-control" id="joining_date" name="data['Employee']['joining_date']" value="" required="">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group" style="margin-top:5px;">
                                        <label class="control-label col-md-3" for="gender">Gender <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <label class="radio-inline">
                                             <input type="radio" name="gender" id="gender">Male
                                            </label>
                                            <label class="radio-inline">
                                              <input type="radio" name="gender" id="gender">Female
                                            </label>
                                        </div>
                                    </div>
                                    </div>                                       
                                </div>

                                <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="date_of_birth">Date of Birth<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="date" class="form-control" id="date_of_birth" name="data['Employee']['date_of_birth']" value="" required="">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group" style="margin-top:5px;">
                                        <label class="control-label col-md-3" for="marrital_status">Martial Status <span class="required">*</span></label>
                                        <div class="col-md-9">
                                             <select name="data['Employee']['marrital_status']" id="marrital_status" class="form-control input-group-lg">
                                                    <option value="">select</option>
                                                    <option value="married">Marrired</option>
                                                    <option value="unmarrired">Unmarrired</option>
                                                    <option value="divorcee">Divorcee</option>
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
                                            <input type="date" class="form-control" id="date_of_leaving" name="data['Employee']['date_of_leaving']" value="" required="">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group" style="margin-top:5px;">
                                        <label class="control-label col-md-3" for="religion">Religion <span class="required">*</span></label>
                                        <div class="col-md-9">
                                             <select name="data['Employee']['religion']" id="religion" class="form-control input-group-lg">
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
                                            <input type="text" class="form-control" id="qualification" name="data['Employee']['qualification']" placeholder="Enter Qualification" value=""  required="">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for=" pan_no">PAN no. <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="emp_sub_category_name" name="data['Employee']['pan_no']" placeholder="Enter PAN No." value="" required="">
                                        </div>
                                    </div>
                                    </div>                                       
                                </div>

                                <div class="row">
                                    
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="gstin_no">GSTIN no. <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="emp_sub_category_name" name="data['Employee']['gstin_no']" placeholder="Enter GSTIN No." value="" required="">
                                        </div>
                                    </div>
                                    </div> 
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="emp_photo">Profile Picture<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="file" class="form-control" id="emp_photo" name="data['Employee']['emp_photo']" value="" required="">
                                            <div class="employeeProfilePic" style="border:1px solid red;height:100px;">image will display here</div>
                                        </div>                                        
                                    </div>
                                    </div>                                      
                                </div>

                                <div class="form-group mb-0">
                                     <div class="col-sm-offset-2 col-sm-10"> 
                                        <button type="button" data-toggle="tooltip" data-original-title="Show" class="btn btn-success btn-anim" onclick=""><i class="icon-rocket"></i><span class="btn-text">Show</span></button>                                        
                                        <button type="button" data-toggle="tooltip" data-original-title="Cancel" class="btn btn-success btn-anim" onclick=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></button>                                        
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

