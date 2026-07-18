<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Reset Password</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in ">
                    <form  method="post" id="societyChangePassword" name="societyChangePassword" autocomplete="off" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="col-md-4" style="border:1px solid #cfcfcf;padding:5px;">
                                <div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="interest_rate" class="control-label">Old Password</label>                                            
                                                <input type="hidden" name="data[SocietyParameter][id]" value="<?php echo isset($this->request->data['SocietyParameter']['id']) ? $this->request->data['SocietyParameter']['id'] : ''; ?>">
                                                <input type="password" class="form-control" id="title" name="data[User][old_password]" value="<?php echo isset($this->request->data['SocietyParameter']['interest_rate']) ? $this->request->data['SocietyParameter']['interest_rate'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="interest_rate" class="control-label">New Password</label>                                            
                                                <input type="password" class="form-control" id="title" name="data[User][new_password]" value="<?php echo isset($this->request->data['SocietyParameter']['igst_tax_per']) ? $this->request->data['SocietyParameter']['igst_tax_per'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="interest_rate" class="control-label">Confirm Password</label>                                            
                                                <input type="password" class="form-control" id="title" name="data[User][confirm_password]" value="<?php echo isset($this->request->data['SocietyParameter']['interest_rate']) ? $this->request->data['SocietyParameter']['sgst_tax_per'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <div class="col-sm-offset-2 col-sm-10"> 
                                                <button type="submit" data-toggle="tooltip" data-original-title="Update Parameters" class="btn btn-warning btn-anim" onclick=""><i class="icon-rocket"></i><span class="btn-text">Reset Password</span></button>                                                                       
                                            </div>
                                        </div>
                                    </div>
                                </div>						
                            </div>
                        </div>                    
                    </form>
                </div>	
            </div>
        </div>
    </div>
</div>