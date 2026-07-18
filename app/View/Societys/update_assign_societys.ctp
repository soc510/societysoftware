<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Update Profile</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">

                            </div>
                            <form class="form-horizontal" method="post" id="SocietyUpdateForm" name="SocietyUpdateForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="username_society">First Name <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="" name="" placeholder="" value=""  required="">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="username_society">Society ShortCode <span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" readonly="readonly" class="form-control" id="society_code" name="data[Society][society_code]" placeholder="Society ShortCode" value="<?php echo isset($singleSocietyRecord['Society']['society_code']) ? $singleSocietyRecord['Society']['society_code'] : ''; ?>"  required="">
                                        </div>
                                    </div>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="registration_no">Registration_no<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="registration_no" name="data[Society][registration_no]" placeholder="Registration_no" value="<?php echo isset($singleSocietyRecord['Society']['registration_no']) ? $singleSocietyRecord['Society']['registration_no'] : ''; ?>"  required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="registration_date">Registration Date<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="date"  class="form-control" id="registration_date" name="data[Society][registration_date]" placeholder="Registration Date" value="<?php echo isset($singleSocietyRecord['Society']['registration_date']) ? $singleSocietyRecord['Society']['registration_date'] : ''; ?>"  required="">
                                        </div>
                                    </div>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="service_tax_no">Service Tax No</label>
                                            <div class="col-md-9">
                                                <input type="text"  class="form-control" id="service_tax_no" name="data[Society][service_tax_no]" placeholder="Service Tax No" value="<?php echo isset($singleSocietyRecord['Society']['service_tax_no']) ? $singleSocietyRecord['Society']['service_tax_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3" for="address">Address<span class="required">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="address" name="data[Society][address]" placeholder="Address" value="<?php echo isset($singleSocietyRecord['Society']['address']) ? $singleSocietyRecord['Society']['address'] : ''; ?>"  required="">
                                        </div>
                                    </div>
                                    </div>    
                                </div>
                                <div class="row">
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="telephone_no">Telephone No<span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="text"  class="form-control" id="telephone_no" name="data[Society][telephone_no]" placeholder="Telephone No" value="<?php echo isset($singleSocietyRecord['Society']['telephone_no']) ? $singleSocietyRecord['Society']['telephone_no'] : ''; ?>"  required="">
                                                </div>
                                            </div>
                                      </div>
                                      <div class="col-md-6">  
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="fax_no">Fax no </label>
                                            <div class="col-md-9">
                                                <input type="text"  class="form-control" id="fax_no" name="data[Society][fax_no]" placeholder="Fax no" value="<?php echo isset($singleSocietyRecord['Society']['fax_no']) ? $singleSocietyRecord['Society']['fax_no'] : ''; ?>">
                                            </div>
                                        </div>
                                     </div>
                                 </div>
                                <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="email_id">Email Id<span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text"  class="form-control" id="email_id" name="data[Society][email_id]" placeholder="Email Id" data-parsley-type="email" maxlength="50" value="<?php echo isset($singleSocietyRecord['Society']['email_id']) ? $singleSocietyRecord['Society']['email_id'] : ''; ?>"  required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="url">Society Url </label>
                                            <div class="col-md-9">
                                                <input type="text"  class="form-control" id="url" name="data[Society][url]" placeholder="Society Url" data-parsley-type="url" maxlength="50" value="<?php echo isset($singleSocietyRecord['Society']['url']) ? $singleSocietyRecord['Society']['url'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                                <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="tan_no">Tan No</label>
                                            <div class="col-md-9">
                                                <input type="text"  class="form-control" id="tan_no" name="data[Society][tan_no]" placeholder="Tan No" value="<?php echo isset($singleSocietyRecord['Society']['tan_no']) ? $singleSocietyRecord['Society']['tan_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="pan_no">Pan No</label>
                                            <div class="col-md-9">
                                                <input type="text"  class="form-control" id="pan_no" name="data[Society][pan_no]" placeholder="Pan No" data-parsley-type="pan" maxlength="20" value="<?php echo isset($singleSocietyRecord['Society']['pan_no']) ? $singleSocietyRecord['Society']['pan_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="circle">Circle</label>
                                            <div class="col-md-9">
                                                <input type="text"  class="form-control" id="circle" name="data[Society][circle]" placeholder="Circle" value="<?php echo isset($singleSocietyRecord['Society']['circle']) ? $singleSocietyRecord['Society']['circle'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="gstin_no">Gstin No</label>
                                            <div class="col-md-9">
                                                <input type="text"  class="form-control" id="gstin_no" name="data[Society][gstin_no]" placeholder="Circle" data-parsley-type="gst_no" maxlength="20" value="<?php echo isset($singleSocietyRecord['Society']['gstin_no']) ? $singleSocietyRecord['Society']['gstin_no'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div> 
                                 </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="is_conveyance">is_conveyance</label>
                                            <div class="col-md-9">
                                                <input type="text"  class="form-control" id="is_conveyance" name="data[Society][is_conveyance]" placeholder="is_conveyance" value="<?php echo isset($singleSocietyRecord['Society']['is_conveyance']) ? $singleSocietyRecord['Society']['is_conveyance'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="conveynace_date">conveynace_date</label>
                                            <div class="col-md-9">
                                                <input type="date"  class="form-control" id="conveynace_date" name="data[Society][conveynace_date]" placeholder="conveynace_date" value="<?php echo isset($singleSocietyRecord['Society']['conveynace_date']) ? $singleSocietyRecord['Society']['conveynace_date'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                                <div class="form-group mb-0">
                                     <div class="col-sm-offset-2 col-sm-10">
                                         <?php echo $this->Html->link('<span class="btn-text">Cancel</span>',array('controller' => 'admin', 'action' => 'dashboard'), array('class' => 'btn btn-default', 'data-toggle' => 'tooltip', 'data-original-title' => 'Go to view socity details', 'escape' => false)); ?> 
                                        <?php if (isset($singleSocietyRecord['User']['id']) && $singleSocietyRecord['User']['id'] != '') { ?>    
                                            <button type="button" data-toggle="tooltip" data-original-title="Update society details" class="btn btn-success btn-anim" onclick="updateSocietyDetailsBySocietyId();"><i class="icon-rocket"></i><span class="btn-text">Update</span></button>
                                        <?php } else { ?>
                                            <button type="button" data-toggle="tooltip" data-original-title="Update society details" class="btn btn-success btn-anim" onclick="updateSocietyDetailsBySocietyId();"><i class="icon-rocket"></i><span class="btn-text">Update</span></button>
                                        <?php } ?>
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

