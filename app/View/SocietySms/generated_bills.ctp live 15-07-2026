<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Generated Member Bills</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
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
                                         $tableCnt = count($societyBillPayable);
                                        ?>
                                        <div class="report-bill"><h5> Member Bill </h5></div>
                                        <div class="report-bill"></div>  
                                        <div class="report-bill-outer-section no-border-thead">
                                            <div class="table-wrap1">
                                                <input type="hidden" name='table-count' value ="<?= $tableCnt; ?>">
                                                <table id="" class="table table-bordered1 padding-td-none padding-th-none">
                                                    <tr>
                                                        <td><input id="selectAll" type="checkbox" /></td>
                                                        <th>Bill geneated date</th>
                                                        <th>Mobile No</th>
                                                        <th>Flat No.</th>
                                                        <th>Member Name</th>
                                                        <th>Dues </th>
                                                        <th>Final Text </th>
                                                    </tr>
                                                    <?php 
                                                    foreach($societyBillPayable as $key => $payment){ ?>
                                                    <tr>
                                                        <td><input type="checkbox"  name="checkbox_<?= $key; ?>" /></td>
                                                        <td><input type="hidden" name="bill_due_date_<?= $key;?>" value="<?= $payment['bills']['bill_due_date']; ?>" /><input type="hidden" name="bill_generated_date_<?= $key;?>" value="<?= $payment['bills']['bill_generated_date']; ?>" /><? echo  isset($payment['bills']['bill_generated_date']) ?  date('d-m-Y',strtotime($payment['bills']['bill_generated_date'])) : ''; ?></td>
                                                        <td><input type ="text" name="phone_<?= $key; ?>" value="<?php echo isset($payment['members']['member_phone']) ? $payment['members']['member_phone'] : '';  ?>"/></td>
                                                        <td><input type="hidden" name="flat_no_<?= $key;?>" value="<?= $payment['members']['flat_no']; ?>" /><?php echo isset($payment['members']['flat_no']) ? $payment['members']['flat_no'] : '';  ?></td>
                                                        <td><?php echo isset($payment['members']['member_name']) ? $payment['members']['member_name'] : '';  ?></td>
                                                        <td><input type="hidden" id="dues_<?= $key; ?>" name="dues_<?= $key; ?>" value="<?php echo isset($payment['bills']['amount_payable']) ? $payment['bills']['amount_payable'] : '';?>"/> <?php echo isset($payment['bills']['amount_payable']) ? $payment['bills']['amount_payable'] : '';?></td>
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
        let formData = $('#member-due-form :input').serializeArray();
        $.ajax({
            url : 'set_data_for_dues',
            type : 'post',
            data :  {'formdata' : JSON.stringify(formData)},
            success : function(msg){
                alert(msg);
                hideLoader();
            },
            error : function(){
                hideLoader();
            }
        });
        
    })
</script>