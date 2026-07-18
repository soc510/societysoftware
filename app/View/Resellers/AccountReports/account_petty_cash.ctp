<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Petty Cash</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="memberMonthlyControFrm" name="memberMonthlyControFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-12 member-tariff-left-box"> 
                                    <div class="row">                                                       
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <div class="account-report-form-box-label">For The Month of &nbsp;</div>  
                                                    <select class="account-report-form-box-field-dropdown-50" id="" name="" required="">
                                                    <option value="">Select</option>  
                                                    <option value="">Apr</option>  
                                                    <option value="">May</option>  
                                                    <option value="">Jun</option>  
                                                    <option value="">Jul</option>  
                                                    <option value="">Aug</option>  
                                                    <option value="">Sep</option>  
                                                    <option value="">Oct</option>  
                                                    <option value="">Nov</option>  
                                                    <option value="">Dec</option>  
                                                    <option value="">Jan</option>  
                                                    <option value="">Feb</option>  
                                                    <option value="">Mar</option>  
                                                </select> 
                                                </div> 
                                            </div>
                                        </div>   
                                        <div class="col-md-3">                                
                                            <div class="account-report-form-field-box">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-sm btn-anim"><i class="icon-rocket"></i><span class="btn-text">Show</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-sm btn-anim" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
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