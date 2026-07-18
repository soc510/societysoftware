<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Dues From Member</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="duesFromMemberFrm" method="post" id="duesFromMemberFrm" name="duesFromMemberFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">As on Date &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberPayment][payment_date]" value="<?php echo isset($postData['MemberPayment']['payment_date']) ? $postData['MemberPayment']['payment_date'] :'' ; ?>">
                                            </div> 
                                        </div>                                            
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-box-label">Type &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-80" id="" name="data[MemberPayment][report_type]">
                                                    <option value="">Select</option>  
                                                    <option value="Summary" <?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Summary'){echo 'selected';}?>>Summary</option>  
                                                    <option value="Detail"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Detail'){echo 'selected';}?>>Detail</option>  
                                                    <option value="Dues-Advance"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Dues-Advance'){echo 'selected';}?>>Dues-Advance</option> 
                                                    <option value="With Transaction"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='With Transaction'){echo 'selected';}?>>With Transaction</option> 
                                                    <option value="Regular"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Regular'){echo 'selected';}?>>Regular</option>  
                                                    <option value="Supplementary"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Supplementary'){echo 'selected';}?>>Supplementary</option> 
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">   
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Operator &nbsp;</div>                                                                     
                                                <select class="account-report-form-box-field-dropdown-65" id="" name="data[MemberPayment][operator]">
                                                    <option value="">Select Operator</option>   
                                                    <?php //if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] ==''){?>
                                                    <option value=">" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '>') { echo 'selected';}?>>></option>
                                                    <option value="<" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '<') { echo 'selected';}?>><</option>
                                                    <option value=">=" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '>=') { echo 'selected';}?>>>=</option>
                                                    <option value="<=" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '<=') { echo 'selected';}?>><=</option>
                                                    <option value="=" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '=') { echo 'selected';}?>>=</option>
                                                    <option value="<>" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '<>') { echo 'selected';}?>><></option>
                                                    <?php //}?>
                                                </select> 
                                            </div>
                                        </div>

                                        <div class="col-md-2 nopadding">
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-box-label">Amount &nbsp;</div>  
                                                <input type="text" class="account-report-form-box-field-50 text-right" id="" name="data[MemberPayment][amount]" value="<?php echo isset($postData['MemberPayment']['amount']) ? $postData['MemberPayment']['amount'] :'' ; ?>">
                                            </div> 
                                        </div>  


                                        <div class="col-md-6 nopadding">   
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Old Record &nbsp; &nbsp;</div>                                                                     
                                            <select class="account-report-form-box-field-dropdown-40" id="" name="data[MemberPayment][member_record]">
                                                    <!--<option value="">Select</option>  -->
                                            <option value="Current" <?php if (isset($postData['MemberPayment']['member_record']) && $postData['MemberPayment']['member_record'] == 'Current') {
                                            echo 'selected';
                                            } ?>>Current Member</option>  

                                            <option value="Old" <?php if (isset($postData['MemberPayment']['member_record']) && $postData['MemberPayment']['member_record'] == 'Old') {
                                            echo 'selected';
                                            } ?>>Old Member</option>  
                
                                            </select> 


                                            </div>
                                        </div>


                                                                 
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>    
                                    </div>
                                </div> 
                                <div class="col-md-4 right-section">
                                    <div class="row">   
                                        <div class="right-title">Select (if required) </div> 
                                        <div class="col-md-12">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Building &nbsp;</div>                                    
                                                <select class="account-report-form-box-field-dropdown" id="" name="data[MemberPayment][building_id]" onchange="getAllBuildingWings(this.value, '#dues_from_member');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($postData['MemberPayment']['building_id']) && $postData['MemberPayment']['building_id'] == $buildingID){?>
                                                    <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                                    <?php }?>
                                                    <?php } }?> 
                                                </select> 
                                            </div>
                                        </div>  
                                        <div class="col-md-12">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                                <select class="account-report-form-box-field-dropdown" id="dues_from_member" name="data[MemberPayment][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Unit No &nbsp;&nbsp;</div>                                   
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberPayment][flat_no]" value="<?php echo isset($postData['MemberPayment']['flat_no']) ? $postData['MemberPayment']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberPayment][flat_no_to]" value="<?php echo isset($postData['MemberPayment']['flat_no_to']) ? $postData['MemberPayment']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                    </div>    
                                </div>
                            </form> 
                            <div class="clearfix"></div>
                            <br><br><br>
                            <form id="member-due-form">
                                <div class="row">
                                    <div class="col-md-8" class="form-group">                                
                                            <textarea class="form-control" rows="3" id ="message-text" ></textarea> 
                                        </div>
                                        <div class="col-md-4">
                                            <a type="button" id="dues-show-all"   class="btn btn-success btn-anim btns" ><i class="icon-rocket"></i><span class="btn-text">Show</span></a>                                                                                
                                            <a type="button" id="send-message"   class="btn btn-success btn-anim btns" ><i class="icon-rocket"></i><span class="btn-text">Send Message</span></a>                                                                                
                                        </div>
                                </div>                            
                                <div id="print_dues_from_member">
                                    <div class="print-dues-from-member">
                                        <div class="row">
                                            <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                            <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                            <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                        </div>
                                        <br>
                                        <?php 
                                         $tableCnt = count($accountDuesMemberSummary);
                                        ?>
                                        <div class="report-bill"><h5>Dues From Member As  <?php echo $formatted = date('d/M/Y', strtotime($_POST['data']['MemberPayment']['payment_date']));?></h5></div>
                                        <div class="report-bill"></div>  
                                        <div class="report-bill-outer-section no-border-thead">
                                            <div class="table-wrap1">
                                                <input type="hidden" name='table-count' value ="<?= $tableCnt; ?>">
                                                <table id="" class="table table-bordered1 padding-td-none padding-th-none">
                                                    <tr>
                                                        <td><input id="selectAll" type="checkbox" /></td>
                                                        <th>Mobile No</th>
                                                        <th>Flat No.</th>
                                                        <th>Member Name</th>
                                                        <th>Dues </th>
                                                        <th>Final Text </th>
                                                    </tr>
                                                    <?php 
                                                    foreach($accountDuesMemberSummary as $key => $accountDuesMemberInfo){ ?>
                                                    <tr>
                                                        <td><input type="checkbox"  name="checkbox_<?= $key; ?>" /></td>
                                                        <td><input type ="text" name="phone_<?= $key; ?>" value="<?php echo isset($accountDuesMemberInfo['phone']) ? $accountDuesMemberInfo['phone'] : '';  ?>"/></td>
                                                        <td><?php echo isset($accountDuesMemberInfo['flat_no']) ? $accountDuesMemberInfo['flat_no'] : '';  ?></td>
                                                        <td><?php echo isset($accountDuesMemberInfo['member_name']) ? $accountDuesMemberInfo['member_name'] : '';  ?></td></td>
                                                        <td><input type="hidden" id="dues_<?= $key; ?>" name="dues_<?= $key; ?>" value="<?php echo isset($accountDuesMemberInfo['total_dues_amount']) ? $accountDuesMemberInfo['total_dues_amount'] : '';?>"/> <?php echo isset($accountDuesMemberInfo['total_dues_amount']) ? $accountDuesMemberInfo['total_dues_amount'] : '';?></td>
                                                        <td><input type="hidden" name="final_input_text_<?= $key; ?>" id="final_input_text_<?= $key; ?>" value="" /> <span id ="final_text_<?= $key; ?>"></span></td>
                                                    </tr>
                                                    <?php } ?>
                                                </table>
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
<script>
    $('#selectAll').click(function(e){
       var table = $(e.target).closest('table'); 
       $('td input:checkbox',table).prop('checked',this.checked);
    });
    $('#dues-show-all').click(function(e){
        var table = $(e.target).closest('table'); 
        var msgtext = $('textarea#message-text').val();
        var tableCount = "<?= $tableCnt; ?>";
        for(var i=0 ; i < tableCount ; i++){
            var tdVal = $('#dues_'+i).val();
            if(msgtext != undefined || msgtext != ''){
                $('#final_text_'+i).html(msgtext+':'+tdVal);
                $('#final_input_text_'+i).val(msgtext+':'+tdVal);
            }
            
        }
    });
    $('#send-message').click(function(e){
        showLoader();
        var formData = $('#member-due-form').serializeArray();
        $.ajax({
            url : 'set_data_for_dues',
            type : 'post',
            data : {'formdata' : JSON.stringify(formData)},
            success : function(msg){
                hideLoader();
                alert(msg);
            },
            error : function(){
                hideLoader();
            }
        });
    })
</script>