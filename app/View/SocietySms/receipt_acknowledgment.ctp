<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Payment</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="member-payments-form" method="post" id="member-payments-form" name="member-payments-form" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">From Date &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberPayment][from_date]" value="<?php echo isset($post['MemberPayment']['from_date']) ? $post['MemberPayment']['from_date'] :'' ; ?>">
                                            </div> 
                                        </div>                                            
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Till Date &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberPayment][till_date]" value="<?php echo isset($post['MemberPayment']['from_date']) ? $post['MemberPayment']['from_date'] :'' ; ?>">
                                            </div> 
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                                <a type="button" data-toggle="tooltip"  class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>    
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
                                         $tableCnt = count($societyMembersPayment);
                                        ?>
                                        <div class="report-bill"><h5>Payments From Member As  <?php echo $formatted = date('d/M/Y', strtotime($post['MemberPayment']['from_date']));?> - <?php echo $formatted = date('d/M/Y', strtotime($post['MemberPayment']['till_date']));?></h5></div>
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
                                                    foreach($societyMembersPayment as $key => $payment){ ?>
                                                    <tr>
                                                        <td><input type="checkbox"  name="checkbox_<?= $key; ?>" /></td>
                                                        <td><input type ="text" name="phone_<?= $key; ?>" value="<?php echo isset($payment['members']['member_phone']) ? $payments['members']['member_phone'] : '';  ?>"/></td>
                                                        <td><?php echo isset($payment['members']['flat_no']) ? $payment['members']['flat_no'] : '';  ?></td>
                                                        <td><?php echo isset($payment['members']['member_name']) ? $payment['members']['member_name'] : '';  ?></td></td>
                                                        <td><input type="hidden" id="dues_<?= $key; ?>" name="dues_<?= $key; ?>" value="<?php echo isset($payment['payments']['amount_paid']) ? $payment['payments']['amount_paid'] : '';?>"/> <?php echo isset($payment['payments']['amount_paid']) ? $payment['payments']['amount_paid'] : '';?></td>
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
            data : formData,
            success : function(msg){
                        alert(msg);
                       hideLoader()
            },
            error : function(){
                hideLoader()
            }
        });
    })
</script>