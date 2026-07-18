<?php   
// echo "<pre>";
// print_r($societyMemberbillDataWithMonthName);exit;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Update Bill Dates</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <div class="col-md-12 left-section"> 
                                <form class="" method="post" id="billUpdateForm" name="billUpdateForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                    <div class="row">
                                        <div class="col-md-5 no-padding-right">  
                                            <div class="account-report-form-field-box">  
                                                    <div class="account-report-form-box-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Months&nbsp;&nbsp;&nbsp;&nbsp;</div>                                                                         
                                                    <select class="account-report-form-box-field-dropdown" id="" name="data[billMonthData][bill_month]" onchange="getBillDate(this.value)" >
                                                        <option value="">Select Month</option>
                                                        <?php 
                                                        
                                                        if(!empty($societyMemberbillDataWithMonthName)){ 
                                                            foreach($societyMemberbillDataWithMonthName as $billData){
                                                        ?>
                                                              <option value="<?php echo $billData['bill_month_id'].'-'.$billData['bill_frequency_id']; ?>"> <?php echo $billData['bill_month'];  ?></option>
                                                        <?php 
                                                            }      
                                                        }
                                                        ?>
                                                    </select>
                                                </div>                                                                     
                                            </div>
                                        <div class="col-md-3 nopadding">   
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">Bill Date &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="existingBillDate" name="data[billMonthData][existing_bill_date]" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">Bill Due &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="existingBillDueDate" name="data[billMonthData][existing_bill_due_date]" value="">
                                            </div>  
                                        </div>                                                            
                                    </div>  

                            </div> 
                        </div>
                    </div>
                </div>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Change Date to</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>   
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <div class="col-md-12 left-section"> 
                                    <div class="row">
                                        <div class="col-md-3 nopadding">   
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">Bill Date &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[billMonthData][new_bill_date]" value="<?php echo isset($postData['BankBook']['payment_date']) ? $postData['BankBook']['payment_date'] :'' ; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">Bill Due &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[billMonthData][new_due_date]" value="<?php echo isset($postData['BankBook']['payment_date_to']) ? $postData['BankBook']['payment_date_to'] :'' ; ?>">
                                            </div>  
                                        </div>                                                            
                                    </div>  
                                    <div class="row"> 
                                        <div class="col-md-3 nopadding">   
                                            <div class="account-report-form-field-box"> 
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>
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
</div>