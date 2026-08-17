<!-- Member Receipt Modal  -->
<div class="modal fade" id="member_receipt_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: fit-content;margin-left:-20% !important">
            <div class="modal-header modal-header-bg" >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title modal-title-color">Member Receipt</h6>
            </div>
            <div class="modal-body modal-body-bg">                
                <form class="member_receipt_payments" method="post" id="member_receipt_payments" name="memberPaymentForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                    <div class="row">
                        <!--<div class="col-md-3 padding-0">
                            <div class="form-group">
                                <label for="" class="control-label">Payment Date <span class="required">*</span></label>                               
                                <input type="date" class="form-control" id="payment_date" name="data[MemberPayment][payment_date]" value="<?php echo $financialStartEndDate['firstDate']; ?>" required="">
                            </div>
                        </div>-->
                        <!-- <div class="col-md-3">    
                                <div class="form-group">
                                    <label class="control-label" for="society_bank_id">Payment Mode</label>
                                    <select class="form-control" name="data[MemberPayment][payment_mode]" id="reciept_payment_mode" class="modal-society-payment-field-dropdown-60" onchange="hideChequeInputFields(this.value,'reciept_society_bank_id');" required>
                                        <option value="">Select payment mode</option>    
                                        <option value="2" <?php // if(isset($this->request->data['SocietyPayment']['payment_type']) && $this->request->data['SocietyPayment']['payment_type'] == 'Bank') { echo 'selected';}?>>Bank</option>   
                                        <option value="1" <?php // if(isset($this->request->data['SocietyPayment']['payment_type']) && $this->request->data['SocietyPayment']['payment_type'] == 'Cash') { echo 'selected';}?>>Cash</option>   
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-3">    
                                <div class="form-group">
                                    <label class="control-label" for="society_bank_id">Society Bank</label>
                                    <div class="">
                                        <select class="form-control" id="reciept_society_bank_id" name="data[MemberPayment][society_bank_id]" onchange="setPaymentMode()" required="">
                                            <option value="">Select Bank</option>
                                            <?php
                                            //if (isset($societyBankLists) && count($societyBankLists) > 0) :
                                            //$s = 1;
                                            //foreach ($societyBankLists as $societyBankId => $societyBankName) {
                                               // if (isset($this->request->data['MemberPayment']['society_bank_id']) && $this->request->data['MemberPayment']['society_bank_id'] == $societyBankId) {
                                                    ?>
                                            <option value="<?php// echo $societyBankId; ?>" selected=""><?php //echo $societyBankName; ?></option>
                                            <?php // } else { ?>
                                            <option value="<?php // echo $societyBankId; ?>"><?php // echo $societyBankName; ?></option>
                                                <?php
                                                //$s++;
                                                   //  }
                                                // } endif;
                                            ?>  
                                        </select>  
                                    </div>
                                    <input type="hidden" name="data[MemberPayment][payment_mode]" id="payment_mode" value="">
                                </div>
                        </div>
                        <div class="col-md-3 padding-0">
                            <div class="form-group">
                                <label for="" class="control-label">Building Name</label>
                                <select name="data[Member][building_id]" id="member_building_id" onchange="getAllBuildingWings(this.value, '#member_receipts_wing_id');" class="form-control">
                                        <option value="">Select building</option>
                                            <?php // if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                        //foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                            <?php //if(isset($societyMemberLists['Member']['building_id']) && $societyMemberLists['Member']['building_id'] == $buildingID){?>
                                        <option value="<?php //echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                             <?php //}else {?>
                                        <option value="<?php //echo $buildingID; ?>"><?php //echo $buildingName; ?></option>
                                            <?php //}?>
                                            <?php //} }?>
                                    </select>  
                            </div>
                        </div>
                        <div class="col-md-3 padding-2">
                            <div class="form-group">
                                <label for="" class="control-label">Wing Name</label>                               
                                <select name="data[Member][wing_id]" id="member_receipts_wing_id" class="form-control">
                                        <option value="">Select building</option>
                                        <?php // if(isset($societyMemberLists['Wing']['id']) && $societyMemberLists['Wing']['id']){?>
                                        <option value="<?php // echo $societyMemberLists['Wing']['id']; ?>"><?php // echo $societyMemberLists['Wing']['wing_name']; ?></option>
                                        <?php // } ?>
                                </select>                                     
                            </div>
                        </div>
                    </div>  -- >
                    <div class="row">
                        <!--<div class="col-md-3 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Cheque Date</label>
                                <input type="date" class="form-control" id="cheque_date" name="data[MemberPayment][cheque_date]" value="<?php echo $financialStartEndDate['firstDate']; ?>">
                            </div>
                        </div>
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Bank Slip No</label>
                                <input type="text" class="form-control" id="bank_slip_no" name="data[MemberPayment][bank_slip_no]" placeholder="Bank Slip No.">
                            </div>
                        </div>-->
                        <div class="col-md-12 padding-0">
                            <!-- <button type="button" onclick="loadSocietyMemberPaymentReciepts();" class="btn btn-info btn-anim btn-sm modal-form-btn">Show members</button> -->
                            <button type="button" onclick="saveSocietyMemberPaymentReciepts();" class="btn btn-success btn-anim btn-sm modal-form-btn">Save Member Payment</button>
                            <!--<button type="button" class="btn btn-warning btn-anim btn-sm modal-form-btn">Close</button>
                            <button type="button" class="btn btn-success btn-anim btn-sm modal-form-btn">User Input</button>-->
                        </div>
                    </div>
                    <div class="row" id="society_member_payment_reciepts">
                            
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
loadSocietyMemberPaymentReciepts();
function setSocietyBankDataInBulkPay(index,value){
    var selectedPayment = $('#payment_method_'+index+' option:selected').text();
    var id = "society_bank_"+index;
    if(selectedPayment == 'NEFT') selectedPayment = 'Bank';
    changeDropDownByPaymentMode(selectedPayment,id);
}
function setPaymentMode(){
    var text = $("#society_bank_id option:selected").text();    
    $("#payment_mode").val(text);
}

function removeErrorMsg(currentIdText,index){
    var amountPaid = $('#amount_paid_'+index).val();
    if(amountPaid != ''){
        $('#bank_selection_error_'+index).html('');
    }
}

$(window).load(function(){
    $('#society_member_payment_reciepts table tbody td input').on('keypress',function(){
        var idStr = $(this).attr('id');
        var splitId = idStr.split('_');
        $('#bank_selection_error_'+splitId[2]).html('please select society bank');
    });    
});

</script>
