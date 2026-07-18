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

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Update Society Details</h6>
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
                                            <label class="control-label col-md-3" for="username_society">Society Name <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="hidden" id="society_user_id" value="<?php echo isset($singleSocietyRecord['Society']['user_id']) ? $singleSocietyRecord['Society']['user_id'] : '';?>">
                                                <input type="hidden" name="data[Society][id]" value="<?php echo isset($singleSocietyRecord['Society']['id']) ? $singleSocietyRecord['Society']['id'] : '';?>">
                                                <input type="text" class="form-control" id="society_name" name="data[Society][society_name]"  maxlength="100" placeholder="Society Name" onblur="societyShortCode(this.value);" value="<?php echo isset($singleSocietyRecord['Society']['society_name']) ? $singleSocietyRecord['Society']['society_name'] : ''; ?>" required="">
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
    </div>
</div>

