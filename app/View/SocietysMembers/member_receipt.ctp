<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Receipt</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                <form class="member_receipt_payments" method="post" id="member_receipt_payments" name="memberPaymentForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                    <div class="row">
                       
                        <div class="col-md-12 padding-0">

                            <!--button type="button" onclick="saveSocietyMemberPaymentReciepts();" class="btn btn-success btn-anim btn-sm modal-form-btn">Save Member Payment</button-->
                            <button type="button" onclick="#" class="btn btn-success btn-anim btn-sm modal-form-btn">Save Member Payment</button>
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

function setRemarkText(selectedValue,index){
    if(selectedValue == 'add'){
        $('#textremarks'+index).show();
        $('#textremarks'+index).val('');
        $('#selectremarks'+index).hide();
    }
    else if(selectedValue != '-1'){
        $('#textremarks'+index).show();
        $('#textremarks'+index).val(selectedValue);
        $('#selectremarks'+index).hide();
        
    }
}

function checkAndResetToList(index){
    let remarkTextVal = $('#textremarks'+index).val();
    if(remarkTextVal == ''){
         $('#selectremarks'+index).show();
         $('#textremarks'+index).hide();
         $('#selectremarks'+index).val('-1');
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
