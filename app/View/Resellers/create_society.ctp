
<style>
.validation {
color:red;
}
    
</style>
<div class="container-fluid">
<div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="dash-prameter-lists pull-left">
                   <div class="dash-top-header-menu-list">
                     <a href="#">Ledger Heads</a>
                     <a href="#">Flat / Shop Detail</a>
                     <a href="#">Bill</a>
                     <a href="#">Member Receipts</a>
                     <a href="#">Payment Entry</a>
                     <a href="#">Generate Bill</a>
                   </div>
               </div>   
                </div>
        </div>
    <h6><?php echo $this->Session->flash(); ?></h6>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Create Society Details</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">

                            </div>
                            <form class="form-horizontal" method="post" id="SocietyCreateForm" name="SocietyCreateForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-6">    
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="username_society">Society Name <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="society_name" name="data[Society][society_name]"  maxlength="100" placeholder="Society Name" onblur="societyShortCode(this.value);" value="<?php echo isset($singleSocietyRecord['Society']['society_name']) ? $singleSocietyRecord['Society']['society_name'] : ''; ?>" required="">
                                                <span id="society_name_error" style="display:none" class ="validation">society name is required</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">    
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="username_society">ShortCode</label>
                                            <div class="col-md-9">
                                                <input type="text" readonly="readonly" class="form-control" id="society_code" name="data[Society][society_code]" placeholder="Society ShortCode" value="<?php echo isset($singleSocietyRecord['Society']['society_code']) ? $singleSocietyRecord['Society']['society_code'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="registration_no">Registration No.</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="registration_no" name="data[Society][registration_no]" placeholder="Registration_no" value="<?php echo isset($singleSocietyRecord['Society']['registration_no']) ? $singleSocietyRecord['Society']['registration_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="registration_date">Registration Date</label>
                                            <div class="col-md-9">
                                                <input type="date" class="form-control" id="registration_date" name="data[Society][registration_date]" placeholder="Registration Date" value="<?php echo isset($singleSocietyRecord['Society']['registration_date']) ? $singleSocietyRecord['Society']['registration_date'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="service_tax_no">Service Tax No.</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="service_tax_no" name="data[Society][service_tax_no]" placeholder="Service Tax No." value="<?php echo isset($singleSocietyRecord['Society']['service_tax_no']) ? $singleSocietyRecord['Society']['service_tax_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="address">Address<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="address" name="data[Society][address]" placeholder="Address" value="<?php echo isset($singleSocietyRecord['Society']['address']) ? $singleSocietyRecord['Society']['address'] : ''; ?>" required="">
                                                <span id="address_error" style="display:none" class ="validation">Address is required</span>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="telephone_no">Telephone No</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="telephone_no" name="data[Society][telephone_no]" placeholder="Telephone No" value="<?php echo isset($singleSocietyRecord['Society']['telephone_no']) ? $singleSocietyRecord['Society']['telephone_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">  
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="fax_no">Fax no.</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="fax_no" name="data[Society][fax_no]" placeholder="Fax no." value="<?php echo isset($singleSocietyRecord['Society']['fax_no']) ? $singleSocietyRecord['Society']['fax_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="email_id">Email Id</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="email_id" name="data[Society][email_id]" placeholder="Email Id"  value="<?php echo isset($singleSocietyRecord['Society']['email_id']) ? $singleSocietyRecord['Society']['email_id'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="url">Society Url</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="url" name="data[Society][url]" placeholder="Society Url" data-parsley-type="url" maxlength="50" value="<?php echo isset($singleSocietyRecord['Society']['url']) ? $singleSocietyRecord['Society']['url'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="tan_no">Tan No.</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="tan_no" name="data[Society][tan_no]" placeholder="Tan No." value="<?php echo isset($singleSocietyRecord['Society']['tan_no']) ? $singleSocietyRecord['Society']['tan_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="pan_no">Pan No.</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="pan_no" name="data[Society][pan_no]" placeholder="Pan No."  maxlength="20" value="<?php echo isset($singleSocietyRecord['Society']['pan_no']) ? $singleSocietyRecord['Society']['pan_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="circle">Circle</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="circle" name="data[Society][circle]" placeholder="Circle" value="<?php echo isset($singleSocietyRecord['Society']['circle']) ? $singleSocietyRecord['Society']['circle'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="gstin_no">GSTIN No.</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="gstin_no" name="data[Society][gstin_no]" placeholder="GSTIN No." data-parsley-type="gst_no" maxlength="20" value="<?php echo isset($singleSocietyRecord['Society']['gstin_no']) ? $singleSocietyRecord['Society']['gstin_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="cgst_no">CGST No.</label>
                                            <div class="col-md-9">     
                                                <input type="text" class="form-control" id="cgst_no" name="data[Society][cgst_no]" placeholder="CGST No" value="<?php echo isset($singleSocietyRecord['Society']['cgst_no']) ? $singleSocietyRecord['Society']['cgst_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="igst_no">IGST No.</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="igst_no" name="data[Society][igst_no]" placeholder="IGST No" value="<?php echo isset($singleSocietyRecord['Society']['igst_no']) ? $singleSocietyRecord['Society']['igst_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="is_conveyance">Is Conveyance</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="data[Society][is_conveyance]">
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="conveynace_date">conveynace_date</label>
                                            <div class="col-md-9">
                                                <input type="date" class="form-control" id="conveynace_date" name="data[Society][conveynace_date]" placeholder="conveynace_date" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="financial_year">Financial Year</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="data[Society][financial_year]">
                                                </select>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="authorised_person">Authorised Person</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="authorised_person" name="data[Society][authorised_person]" placeholder="Name of Authorised Person" value="">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="is_conveyance">Status</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="data[Society][status]">
                                                    <option value="0">InActive</option>
                                                    <option value="1" selected="selected" >Active</option>
                                                </select>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" data-toggle="tooltip" data-original-title="Update society details" class="btn btn-success btn-anim" onclick="createSocietyValidation()"><i class="icon-rocket"></i><span class="btn-text">Save</span></button>
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

<script>
   
    
</script>