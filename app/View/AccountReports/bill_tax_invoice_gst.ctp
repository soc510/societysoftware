<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <form class="" method="post" id="societyReportBillFrm" name="societyReportBillFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-9 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4">    
                                            <div class="society-report-form-box-label">Report Type &nbsp;</div>                                                                     
                                            <input type="text" class="society-report-form-box-field-date" id="" name="" value="Bill With Receipt Tabular" disabled>
                                        </div>
                                        <div class="col-md-4">    
                                            <div class="society-report-form-box-label">From &nbsp;</div>                                                                     
                                            <select class="society-report-form-box-field-dropdown" id="" name="data[MemberBillSummary][month]">
                                                <option value="">Select Month</option>
                                                    <?php if(isset($billingFrequenciesArray) && count($billingFrequenciesArray) > 0) {
                                                        foreach($billingFrequenciesArray as $MonthID => $MonthName){?>
                                                        <?php if(isset($postData['MemberTariff']['month']) && $postData['MemberTariff']['month'] == $MonthID){?>
                                                <option selected="" value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                                        <?php }else{?>
                                                <option value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                                        <?php } }?>
                                                     <?php }?>
                                            </select> 
                                        </div>
                                        <div class="col-md-4">    
                                            <div class="society-report-form-box-label">To &nbsp;</div>                                                                     
                                            <select class="society-report-form-box-field-dropdown" id="" name="data[MemberBillSummary][month_to]">
                                                <option value="">Select Month</option>
                                                    <?php if(isset($billingFrequenciesArray) && count($billingFrequenciesArray) > 0) {
                                                        foreach($billingFrequenciesArray as $MonthID => $MonthName){?>
                                                        <?php if(isset($postData['MemberTariff']['month_to']) && $postData['MemberTariff']['month_to'] == $MonthID){?>
                                                <option selected="" value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                                        <?php }else{?>
                                                <option value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                                        <?php } }?>
                                                     <?php }?>
                                            </select> 
                                        </div>   
                                        <div class="clearfix"></div>
                                        <div class="col-md-4 margin-top-2">    
                                            <div class="society-report-form-box-label">Unit Area &nbsp;</div>                                                                     
                                            <select class="society-report-form-box-field-dropdown" id="" name="data[MemberBillSummary][unit_area]">
                                                <option value="">Select Unit Area</option>
                                                    
                                                <option selected="selected" value="SqFt">Sq. Ft.</option>
                                                <option value="SqMtr">Sq. Mtr.</option>
                                            </select> 
                                        </div>
                                    </div>  <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="">Duplicate Copy
                                            </label>                                                                  
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="">Processed By
                                            </label>                                                                   
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="">Ledger Balance
                                            </label>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6" style="border:1px solid #d9d9d9;">    
                                            <p style="font-weight: 500;color:#333;">Define</p>                            
                                            <div class="col-md-6 nopadding">
                                                <div class="society-report-form-field-box">
                                                    <div class="society-report-form-box-label">Bill Date &nbsp;</div>                                   
                                                    <input type="date" class="society-report-form-box-field-date-70" id="" name="data[MemberBillSummary][bill_date]" value="">
                                                </div>
                                            </div>  
                                            <div class="col-md-6 nopadding">
                                                <div class="society-report-form-field-box">
                                                    <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                    <input type="date" class="society-report-form-box-field-70" id="" name="data[MemberBillSummary][bill_date_to]" value="">
                                                </div>
                                            </div> 
                                            <div class="clearfix"></div>   
                                            <div class="col-md-6 nopadding">
                                                <div class="society-report-form-field-box">
                                                    <div class="society-report-form-box-label">Bill No &nbsp;&nbsp;&nbsp;&nbsp;</div>                                   
                                                    <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberBillSummary][bill_no]" value="">
                                                </div>
                                            </div>  
                                            <div class="col-md-6 nopadding">
                                                <div class="society-report-form-field-box">
                                                    <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                    <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberBillSummary][bill_no_to]" value="">
                                                </div>
                                            </div> 
                                        </div>   
                                        <div class="col-md-6"  style="border:1px solid #d9d9d9;">  
                                            <p style="font-weight: 500;color:#333;">Receipt Period </p>                             
                                            <div class="col-md-6 nopadding">
                                                <div class="society-report-form-field-box">
                                                    <div class="society-report-form-box-label">From &nbsp;&nbsp;</div>                                   
                                                    <input type="date" class="society-report-form-box-field-70" id="" name="data[MemberBillSummary][from_date]" value="">
                                                </div>
                                            </div>  
                                            <div class="col-md-6 nopadding">
                                                <div class="society-report-form-field-box">
                                                    <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                    <input type="date" class="society-report-form-box-field-70" id="" name="data[MemberBillSummary][to_date]" value="">
                                                </div>
                                            </div>                                            
                                        </div>  
                                        <label class="checkbox-inline" style="overfow:hidden;margin:8px;">
                                            <input type="checkbox" value="">Print Only Non-email Members
                                        </label>
                                    </div><br>
                                    <div class="form-group society-report-form">
                                        <center><button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                            <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a></center> 
                                            <button type="submit" value="email" name="data[MemberBillSummary][submit]" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Email</span></button>                                                                                                                                
                                    </div>
                                </div> 
                                <div class="col-md-3 right-section">
                                    <div class="row">   
                                        <div class="right-title">Select if required </div> 
                                        <div class="col-md-12">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Building &nbsp;</div>                                    
                                                <select name="data[MemberBillSummary][building_id]" id="wingBuildingName" class="society-report-form-box-field-dropdown" onchange="getAllBuildingWings(this.value, '#society_bill_report');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($this->request->data['MemberBillSummary']['building_id']) && $this->request->data['MemberBillSummary']['building_id'] == $buildingID){?>
                                                    <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                                </select>
                                            </div>
                                        </div>  
                                        <div class="col-md-12">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                                <select class="society-report-form-box-field-dropdown" id="society_bill_report" name="data[MemberBillSummary][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Unit No &nbsp;</div>                                   
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberBillSummary][flat_no]" value="">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberBillSummary][flat_no_to]" value="">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div><br>
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