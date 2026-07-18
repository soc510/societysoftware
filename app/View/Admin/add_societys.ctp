<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Create Society Login</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                                
                            </div>
                            <form class="form-horizontal" method="post" id="SocietyLoginCredentialsForm" name="SocietyLoginCredentialsForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                <div class="form-group">
                                    <label class="col-sm-2 mb-10 control-label">Access Level <span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="access_level" onchange="selectAccesslevel(this.value)" name="data[Admin][access_level]" required="">
                                            <option value="">Select</option>
                                            <option <?php if (isset($singleSocietyRecord['User']['access_level']) && $singleSocietyRecord['User']['access_level'] == 2){?>selected<?php } ?> value="2">Society(Individual)</option>
                                            <option <?php if (isset($singleSocietyRecord['User']['access_level']) && $singleSocietyRecord['User']['access_level'] == 3){?>selected<?php } ?> value="3">Reseller</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-2" for="username_society">Username <span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="data[Admin][society_user_id]" value="<?php echo isset($singleSocietyRecord['User']['id']) ? $singleSocietyRecord['User']['id'] : '';?>">
                                        <input type="text" class="form-control" id="society_username" name="data[Admin][society_username]" placeholder="Society Username" value="<?php echo isset($singleSocietyRecord['User']['username']) ? $singleSocietyRecord['User']['username'] : '';?>" maxlength="75" required="">
                                    </div>
                                </div>
                                <?php if(!isset($singleSocietyRecord['User']['id'])) { ?>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-2" for="username_password"> Password: <span class="required">*</span></label>
                                    <div class="col-sm-10"> 
                                        <input type="password" class="form-control" id="pwd_hr" placeholder="Enter password" id="society_Password" name="data[Admin][society_Password]" value="<?php echo isset($singleSocietyRecord['User']['password']) ? $singleSocietyRecord['User']['password'] : '';?>" required="">
                                    </div>
                                </div>
                                <?php } ?>
                                 <div class="form-group access_level">
                                    <label class="control-label mb-10 col-sm-2" for="username_society">Society Name <span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="society_name" name="data[Admin][society_name]" placeholder="Society Name" onblur="societyShortCode(this.value);" value="<?php echo isset($singleSocietyRecord['Society'][0]['society_name']) ? $singleSocietyRecord['Society'][0]['society_name'] : '';?>" maxlength="75" required="">
                                    </div>
                                </div>
                                <div class="form-group access_level">
                                    <label class="control-label mb-10 col-sm-2" for="username_society">Society ShortCode <span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" readonly="readonly" class="form-control" id="society_code" name="data[Admin][society_code]" placeholder="Society ShortCode" value="<?php echo isset($singleSocietyRecord['Society'][0]['society_code']) ? $singleSocietyRecord['Society'][0]['society_code'] : '';?>" maxlength="75" required="">
                                    </div>
                                </div>
                                <div class="form-group access_level">
                                    <label class="control-label mb-10 col-sm-2" for="username_society">Enable Sms <span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="access_level"  name="data[Admin][sms-enbale]" required="">
                                            <option <?php if (isset($singleSocietyRecord['Society'][0]['enable_sms']) && $singleSocietyRecord['Society'][0]['enable_sms'] == 'N'){?>selected<?php } ?> value="N">No</option>
                                            <option <?php if (isset($singleSocietyRecord['Society'][0]['enable_sms']) && $singleSocietyRecord['Society'][0]['enable_sms'] == 'Y'){?>selected<?php } ?> value="Y">Yes</option>
                                        </select>
                                    </div>
                                </div>                                
                                <div class="form-group mb-0"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                         <?php if (isset($singleSocietyRecord['User']['id']) && $singleSocietyRecord['User']['id'] != '') { ?>    
                                        <button type="button" class="btn btn-success btn-anim" onclick="addUpdateSocietyLoginCredentials();"><i class="icon-rocket"></i><span class="btn-text">Update</span></button>
                                        <?php }else {?>
                                        <button type="button" class="btn btn-success btn-anim" onclick="addUpdateSocietyLoginCredentials();"><i class="icon-rocket"></i><span class="btn-text">Add</span></button>
                                        <?php }?>
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

