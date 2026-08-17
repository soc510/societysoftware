<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Society Payments</h6>
                    </div>
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-plus" style="color:#fff;font-size:10px;"></i> Add payment', array('controller' => 'societys','action' => 'add_society_payments'), array('class' => 'btn btn-primary','data-toggle' => 'tooltip', 'data-original-title' => 'Add Society Payments', 'escape' => false)); ?>
                     </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                   <div class="panel-body">
                       <div class="row" style="margin-bottom:15px;">
                           <form method="post" id="filterFrm" autocomplete="off">
                               <div class="col-md-2">
                                   <label class="control-label">From Date</label>
                                   <input type="date" class="form-control input-sm" name="data[Filter][from_date]" id="from_date" value="<?php echo isset($this->request->data['Filter']['from_date']) ? $this->request->data['Filter']['from_date'] : ''; ?>">
                               </div>
                               <div class="col-md-2">
                                   <label class="control-label">To Date</label>
                                   <input type="date" class="form-control input-sm" name="data[Filter][to_date]" id="to_date" value="<?php echo isset($this->request->data['Filter']['to_date']) ? $this->request->data['Filter']['to_date'] : ''; ?>">
                               </div>
                               <div class="col-md-2">
                                   <label class="control-label">&nbsp;</label><br>
                                   <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-filter"></i> Filter</button>
                                   <a href="<?php echo $this->Html->url(array('controller' => 'societys','action' => 'society_payments')); ?>" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Reset</a>
                               </div>
                           </form>
                       </div>
                       <div class="table-wrap">
                           <div class="table-responsive">
                               <table id="datable_1" class="dataTable table table-hover table-bordered padding-th-none padding-td-none">
                                   <thead>
                                       <tr style="background:#009688;color:#fff;">
                                           <th style="color:#fff;">#</th>
                                           <th style="color:#fff;">Paid To</th>
                                           <th style="color:#fff;">Category</th>
                                           <th style="color:#fff;">Particulars</th>
                                           <th style="color:#fff;">Bill Voucher No</th>
                                           <th style="color:#fff;">Payment Date</th>
                                           <th style="color:#fff;">Cheque Date</th>
                                           <th style="color:#fff;">Clear Date</th>
                                           <th style="color:#fff;">Amount</th>
                                           <th style="color:#fff;">Cheque No.</th>
                                           <th style="color:#fff;">Tax Amount</th>
                                           <th style="color:#fff;">Total Amount</th>
                                           <th style="color:#fff;">Type</th>
                                           <th class="text-nowrap" style="color:#fff;">Action</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       <?php if(isset($societyPaymentData) && count($societyPaymentData) > 0){
                                         $idCount = 1;
                                         foreach($societyPaymentData as $payment){
                                            $payId = $payment['SocietyPayment']['id'];
                                      ?>
                                           <tr id="<?php echo $idCount; ?>">
                                               <td><?php echo $idCount; ?></td>
                                               <td><?php echo isset($payment['SocietyPayment']['title']) ? $payment['SocietyPayment']['title'] :'' ; ?></td>
                                               <td><?php echo $accountCategories[$payment['SocietyPayment']['account_category_id']]; ?></td>
                                               <td><?php echo isset($payment['SocietyPayment']['particulars']) ? $payment['SocietyPayment']['particulars'] :'' ; ?></td>
                                               <td><?php echo isset($payment['SocietyPayment']['bill_voucher_number']) ? $payment['SocietyPayment']['bill_voucher_number'] :'' ; ?></td>
                                               <td>
                                                   <input type="date" class="form-control input-sm inline-edit" data-id="<?php echo $payId; ?>" data-field="payment_date" value="<?php echo (isset($payment['SocietyPayment']['payment_date']) && $payment['SocietyPayment']['payment_date'] != '0000-00-00') ? $payment['SocietyPayment']['payment_date'] : ''; ?>" style="width:130px;"><span style="display:none;"><?php echo isset($payment['SocietyPayment']['payment_date']) ? $payment['SocietyPayment']['payment_date'] : ''; ?></span>
                                               </td>
                                               <td>
                                                   <input type="date" class="form-control input-sm inline-edit" data-id="<?php echo $payId; ?>" data-field="cheque_date" value="<?php echo (isset($payment['SocietyPayment']['cheque_date']) && $payment['SocietyPayment']['cheque_date'] != '0000-00-00') ? $payment['SocietyPayment']['cheque_date'] : ''; ?>" style="width:130px;"><span style="display:none;"><?php echo isset($payment['SocietyPayment']['cheque_date']) ? $payment['SocietyPayment']['cheque_date'] : ''; ?></span>
                                               </td>
                                               <td>
                                                   <input type="date" class="form-control input-sm inline-edit" data-id="<?php echo $payId; ?>" data-field="debited_date" value="<?php echo (isset($payment['SocietyPayment']['debited_date']) && $payment['SocietyPayment']['debited_date'] != '0000-00-00') ? $payment['SocietyPayment']['debited_date'] : ''; ?>" style="width:130px;"><span style="display:none;"><?php echo isset($payment['SocietyPayment']['debited_date']) ? $payment['SocietyPayment']['debited_date'] : ''; ?></span>
                                               </td>
                                               <td>
                                                   <input type="number" step="0.01" class="form-control input-sm inline-edit text-right" data-id="<?php echo $payId; ?>" data-field="amount" value="<?php echo isset($payment['SocietyPayment']['amount']) ? $payment['SocietyPayment']['amount'] :'' ; ?>" style="width:100px;"><span style="display:none;"><?php echo isset($payment['SocietyPayment']['amount']) ? $payment['SocietyPayment']['amount'] :'' ; ?></span>
                                               </td>
                                               <td>
                                                   <input type="text" class="form-control input-sm inline-edit" data-id="<?php echo $payId; ?>" data-field="cheque_reference_number" value="<?php echo isset($payment['SocietyPayment']['cheque_reference_number']) ? $payment['SocietyPayment']['cheque_reference_number'] :'' ; ?>" style="width:90px;"><span style="display:none;"><?php echo isset($payment['SocietyPayment']['cheque_reference_number']) ? $payment['SocietyPayment']['cheque_reference_number'] :'' ; ?></span>
                                               </td>
                                               <td>
                                                   <input type="number" step="0.01" class="form-control input-sm inline-edit text-right" data-id="<?php echo $payId; ?>" data-field="tax_amount" id="tax_amt_<?php echo $payId; ?>" value="<?php echo isset($payment['SocietyPayment']['tax_amount']) ? $payment['SocietyPayment']['tax_amount'] :'' ; ?>" style="width:80px;"><span style="display:none;"><?php echo isset($payment['SocietyPayment']['tax_amount']) ? $payment['SocietyPayment']['tax_amount'] :'' ; ?></span>
                                               </td>
                                               <td>
                                                   <input type="number" step="0.01" class="form-control input-sm text-right" id="total_amt_<?php echo $payId; ?>" value="<?php echo isset($payment['SocietyPayment']['total_amount']) ? $payment['SocietyPayment']['total_amount'] :'' ; ?>" style="width:100px;" readonly><span style="display:none;"><?php echo isset($payment['SocietyPayment']['total_amount']) ? $payment['SocietyPayment']['total_amount'] :'' ; ?></span>
                                               </td>
                                               <td><?php echo isset($payment['SocietyPayment']['payment_type']) ? $payment['SocietyPayment']['payment_type'] :'' ; ?></td>
                                               <td class="text-nowrap">
                                                <button type="button" class="btn btn-success btn-xs" onclick="saveRow('<?php echo $payId; ?>');" title="Save"><i class="fa fa-check"></i></button>
                                                <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys','action' => 'add_society_payments', $payment['SocietyPayment']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>
                                                <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' =>'delete_society_payments', $payment['SocietyPayment']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$payment['SocietyPayment']['id'])); ?>
                                               </td>
                                           </tr>
                                         <?php $idCount++; } }?>
                                 </tbody>
                               </table>
                           </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $(document).on('input', '.inline-edit[data-field="amount"], .inline-edit[data-field="tax_amount"]', function(){
        var id = $(this).data('id');
        var row = $(this).closest('tr');
        var amt = parseFloat(row.find('.inline-edit[data-field="amount"]').val()) || 0;
        var taxAmt = parseFloat($('#tax_amt_'+id).val()) || 0;
        var totalAmt = (amt - taxAmt).toFixed(2);
        $('#total_amt_'+id).val(totalAmt);
    });

    $(document).on('change', '.inline-edit:not([data-field="amount"]):not([data-field="tax_amount"])', function(){
        var el = $(this);
        var id = el.data('id');
        var field = el.data('field');
        var value = el.val();
        updateField(el, id, field, value);
    });
});

function updateField(el, id, field, value){
    el.css('border-color','#f0ad4e');
    $.ajax({
        type: "POST",
        url: webrootUrl + 'societys_ajax/updateSocietyPaymentField',
        data: {id: id, field: field, value: value},
        dataType: 'json',
        success: function(resp){
            if(resp.error == 0){
                el.css('border-color','#5cb85c');
                setTimeout(function(){ el.css('border-color',''); }, 1500);
            } else {
                el.css('border-color','#d9534f');
                alert(resp.error_message);
            }
        },
        error: function(){
            el.css('border-color','#d9534f');
            alert('Error updating field.');
        }
    });
}

function saveRow(id){
    var row = $('tr').find('.inline-edit[data-id="'+id+'"]').first().closest('tr');
    var fields = row.find('.inline-edit[data-id="'+id+'"]');
    var amt = parseFloat(row.find('.inline-edit[data-field="amount"]').val()) || 0;
    var taxAmt = parseFloat($('#tax_amt_'+id).val()) || 0;
    var totalAmt = (amt - taxAmt).toFixed(2);
    $('#total_amt_'+id).val(totalAmt);

    var saveCount = 0;
    var totalFields = fields.length + 1;
    var hasError = false;

    fields.each(function(){
        var el = $(this);
        var field = el.data('field');
        var value = el.val();
        el.css('border-color','#f0ad4e');
        $.ajax({
            type: "POST",
            url: webrootUrl + 'societys_ajax/updateSocietyPaymentField',
            data: {id: id, field: field, value: value},
            dataType: 'json',
            success: function(resp){
                saveCount++;
                if(resp.error == 0){
                    el.css('border-color','#5cb85c');
                    setTimeout(function(){ el.css('border-color',''); }, 1500);
                } else {
                    hasError = true;
                    el.css('border-color','#d9534f');
                }
                if(saveCount >= totalFields && !hasError){
                    alert('Saved successfully!');
                }
            },
            error: function(){ hasError = true; el.css('border-color','#d9534f'); saveCount++; }
        });
    });

    $.ajax({
        type: "POST",
        url: webrootUrl + 'societys_ajax/updateSocietyPaymentField',
        data: {id: id, field: 'total_amount', value: totalAmt},
        dataType: 'json',
        success: function(resp){
            saveCount++;
            if(saveCount >= totalFields && !hasError){
                alert('Saved successfully!');
            }
        },
        error: function(){ hasError = true; saveCount++; }
    });
}
</script>
