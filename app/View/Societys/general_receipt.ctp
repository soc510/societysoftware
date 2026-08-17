<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">General Receipts Payments</h6>
                    </div>
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-plus" style="color:#fff;font-size:10px;"></i> Add General Receipts Payments', array('controller' => 'societys','action' => 'add_general_receipt'), array('class' => 'btn btn-primary','data-toggle' => 'tooltip', 'data-original-title' => 'Add Society Payments', 'escape' => false)); ?>
                     </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                   <div class="panel-body">
                       <div class="table-wrap">
                           <div class="table-responsive">
                               <table id="datable_1" class="dataTable table table-hover table-bordered padding-th-none padding-td-none">
                                   <thead>
                                       <tr>
                                           <th>#</th>
                                           <th>Received By</th>
                                           <th>Particulars</th>
                                           <th>Remark</th>
                                           <th>General Receipt No</th>
                                           <th>Payment Date</th>
                                           <th>Cheque Date</th>
                                           <th>Cheque No</th>
                                           <th>Amount</th>
                                           <th>Type</th>
                                           <th class="text-nowrap">Action</th>
                                       </tr>
                                   </thead>
                                   <tbody>                                        
                                       <?php if(isset($societyOtherIncomeData) && count($societyOtherIncomeData) > 0){
                                         $idCount = 1;
                                         foreach($societyOtherIncomeData as $otherIncomeData){
                                      ?>
                                           <tr id="<?php echo $idCount; ?>">
                                               <td class="text-center"><?php echo $idCount; ?></td>
                                               <td><?php echo isset($otherIncomeData['SocietyLedgerHeads']['title']) ? $otherIncomeData['SocietyLedgerHeads']['title'] :'' ; ?></td>
                                               <td><?php echo isset($otherIncomeData['SocietyOtherIncome']['title']) ? $otherIncomeData['SocietyOtherIncome']['title'] :'' ; ?></td>
                                               <td><?php echo isset($otherIncomeData['SocietyOtherIncome']['description']) ? $otherIncomeData['SocietyOtherIncome']['description'] :'' ; ?></td>
                                               <td class="text-center"><?php echo isset($otherIncomeData['SocietyOtherIncome']['general_receipt_number']) ? $otherIncomeData['SocietyOtherIncome']['general_receipt_number'] :'' ; ?></td>
                                               <td class="text-center"><?php echo isset($otherIncomeData['SocietyOtherIncome']['payment_date']) ? $utilObj->getFormatDate($otherIncomeData['SocietyOtherIncome']['payment_date'],'d/m/Y') :'' ; ?></td>
                                               <td class="text-center"><?php echo isset($otherIncomeData['SocietyOtherIncome']['cheque_date']) ? $utilObj->getFormatDate($otherIncomeData['SocietyOtherIncome']['cheque_date'],'d/m/Y') :'' ; ?></td>
                                               <td class="text-center"><?php echo isset($otherIncomeData['SocietyOtherIncome']['cheque_no']) ? $otherIncomeData['SocietyOtherIncome']['cheque_no'] :'' ; ?></td>
                                               <td class="text-right"><?php echo isset($otherIncomeData['SocietyOtherIncome']['amount_paid']) ? $otherIncomeData['SocietyOtherIncome']['amount_paid'] :'' ; ?></td>
                                               <td><?php echo isset($otherIncomeData['SocietyOtherIncome']['payment_mode']) ? $otherIncomeData['SocietyOtherIncome']['payment_mode'] :'' ; ?></td>
                                               <td class="text-nowrap">
                                                <a href="javascript:void(0);" class="mr-25" data-toggle="tooltip" data-original-title="Edit" onclick="editGeneralReceipt('<?php echo $otherIncomeData['SocietyOtherIncome']['id']; ?>');"><i class="fa fa-pencil text-inverse m-r-10"></i></a>
                                                <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' =>'delete_general_receipts', $otherIncomeData['SocietyOtherIncome']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$otherIncomeData['SocietyOtherIncome']['id'])); ?>
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

<!-- Modal for editing a general receipt -->
<div class="modal fade" id="general_receipt_edit_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title modal-title-color">Edit General Receipt <span id="general_receipt_edit_number"></span></h6>
            </div>
            <div class="modal-body modal-body-bg">
                <div id="general_receipt_edit_error"></div>
                <form method="post" id="general_receipt_edit_form" name="general_receipt_edit_form" autocomplete="off">
                    <input type="hidden" id="general_receipt_edit_id" name="data[SocietyOtherIncome][id]" value="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Type<span class="required">*</span></label>
                                <select class="form-control" id="general_receipt_edit_payment_mode" name="data[SocietyOtherIncome][payment_mode]" onchange="toggleGeneralReceiptChequeFields(this.value);">
                                    <option value="">Select payment mode</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 general_receipt_bank_field">
                            <div class="form-group">
                                <label class="control-label">By</label>
                                <select class="form-control" id="general_receipt_edit_society_bank_id" name="data[SocietyOtherIncome][society_bank_id]">
                                    <option value="">Select Bank</option>
                                    <?php
                                    if (isset($societyBankBalanceHeadsLists) && count($societyBankBalanceHeadsLists) > 0) :
                                        foreach ($societyBankBalanceHeadsLists as $societyLedgerHeadsId => $societyLedgerHeadsTitle) { ?>
                                        <option value="<?php echo $societyLedgerHeadsId; ?>"><?php echo $societyLedgerHeadsTitle; ?></option>
                                    <?php } endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">Received By<span class="required">*</span></label>
                                <select class="form-control" id="general_receipt_edit_ledger_head_id" name="data[SocietyOtherIncome][ledger_head_id]">
                                    <option value="">Select Ledger</option>
                                    <?php
                                    if (isset($societyOtherIncomeHeadsLists) && count($societyOtherIncomeHeadsLists) > 0) :
                                        foreach ($societyOtherIncomeHeadsLists as $societyLedgerHeadsId => $societyLedgerHeadsTitle) { ?>
                                        <option value="<?php echo $societyLedgerHeadsId; ?>"><?php echo $societyLedgerHeadsTitle; ?></option>
                                    <?php } endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Amount</label>
                                <input type="text" class="form-control numeric" id="general_receipt_edit_amount_paid" name="data[SocietyOtherIncome][amount_paid]" onkeyup="calculateGeneralReceiptNetAmount();" onchange="calculateGeneralReceiptNetAmount();">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">TDS Bank</label>
                                <select class="form-control" id="general_receipt_edit_tds_bank_id" name="data[SocietyOtherIncome][tds_bank_id]">
                                    <option value="">Select Tds</option>
                                    <?php
                                    if (isset($societyOtherIncomeHeadsLists) && count($societyOtherIncomeHeadsLists) > 0) :
                                        foreach ($societyOtherIncomeHeadsLists as $societyLedgerHeadsId => $societyLedgerHeadsTitle) { ?>
                                        <option value="<?php echo $societyLedgerHeadsId; ?>"><?php echo $societyLedgerHeadsTitle; ?></option>
                                    <?php } endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">TDS Amount</label>
                                <input type="text" class="form-control numeric" id="general_receipt_edit_tds_amount" name="data[SocietyOtherIncome][tds_amount]" onkeyup="calculateGeneralReceiptNetAmount();" onchange="calculateGeneralReceiptNetAmount();">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Net Amount</label>
                                <input type="text" class="form-control numeric" id="general_receipt_edit_net_amount" name="data[SocietyOtherIncome][net_amount]">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Payment Date<span class="required">*</span></label>
                                <input type="date" class="form-control" id="general_receipt_edit_payment_date" name="data[SocietyOtherIncome][payment_date]" min="<?= $financialYearStartDate; ?>" max="<?= $financialYearEndDate; ?>">
                            </div>
                        </div>
                        <div class="col-md-3 general_receipt_bank_field">
                            <div class="form-group">
                                <label class="control-label">Cheque Date</label>
                                <input type="date" class="form-control" id="general_receipt_edit_cheque_date" name="data[SocietyOtherIncome][cheque_date]" min="<?= $financialYearStartDate; ?>" max="<?= $financialYearEndDate; ?>">
                            </div>
                        </div>
                        <div class="col-md-3 general_receipt_bank_field">
                            <div class="form-group">
                                <label class="control-label">Cheque No</label>
                                <input type="text" class="form-control" id="general_receipt_edit_cheque_no" name="data[SocietyOtherIncome][cheque_no]">
                            </div>
                        </div>
                        <div class="col-md-3 general_receipt_bank_field">
                            <div class="form-group">
                                <label class="control-label">Bank Name</label>
                                <input type="text" class="form-control" id="general_receipt_edit_general_bank_name" name="data[SocietyOtherIncome][general_bank_name]">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Particulars</label>
                                <input type="text" class="form-control" id="general_receipt_edit_title" name="data[SocietyOtherIncome][title]">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Remark</label>
                                <input type="text" class="form-control" id="general_receipt_edit_description" name="data[SocietyOtherIncome][description]">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-icon left-icon btn-sm" onclick="updateGeneralReceipt();"><i class="fa fa-save"></i> Update General Receipt</button>
                <button type="button" class="btn btn-warning btn-icon left-icon btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function toggleGeneralReceiptChequeFields(paymentMode) {
        if (paymentMode == 'Bank') {
            $('.general_receipt_bank_field').show();
        } else {
            $('.general_receipt_bank_field').hide();
            $('#general_receipt_edit_society_bank_id').val('');
            $('#general_receipt_edit_cheque_date').val('');
            $('#general_receipt_edit_cheque_no').val('');
            $('#general_receipt_edit_general_bank_name').val('');
        }
    }

    function calculateGeneralReceiptNetAmount() {
        var amountPaid = parseFloat($('#general_receipt_edit_amount_paid').val());
        var tdsAmount = parseFloat($('#general_receipt_edit_tds_amount').val());
        amountPaid = isNaN(amountPaid) ? 0 : amountPaid;
        tdsAmount = isNaN(tdsAmount) ? 0 : tdsAmount;
        $('#general_receipt_edit_net_amount').val(amountPaid - tdsAmount);
    }

    function setGeneralReceiptDropdown(dropdownId, selectedValue) {
        $('#' + dropdownId).val(selectedValue);
        if ($('#' + dropdownId).val() === null) {
            $('#' + dropdownId).val('');
        }
    }

    function editGeneralReceipt(generalReceiptId) {
        showLoader();
        $.ajax({
            type: 'POST',
            url: webrootUrl + 'societys_ajax/get_general_receipt_details',
            data: {general_receipt_id: generalReceiptId},
            dataType: 'json',
            success: function (jsonData) {
                hideLoader();
                if (jsonData.error == 1) {
                    alert(jsonData.error_message);
                    return false;
                }
                var receiptData = jsonData.data;
                $('#general_receipt_edit_error').hide().html('');
                $('#general_receipt_edit_id').val(receiptData.id);
                $('#general_receipt_edit_number').html(receiptData.general_receipt_number ? '#' + receiptData.general_receipt_number : '');
                setGeneralReceiptDropdown('general_receipt_edit_payment_mode', receiptData.payment_mode);
                setGeneralReceiptDropdown('general_receipt_edit_society_bank_id', receiptData.society_bank_id);
                setGeneralReceiptDropdown('general_receipt_edit_ledger_head_id', receiptData.ledger_head_id);
                $('#general_receipt_edit_amount_paid').val(receiptData.amount_paid);
                setGeneralReceiptDropdown('general_receipt_edit_tds_bank_id', receiptData.tds_bank_id);
                $('#general_receipt_edit_tds_amount').val(receiptData.tds_amount);
                $('#general_receipt_edit_net_amount').val(receiptData.net_amount);
                $('#general_receipt_edit_payment_date').val(receiptData.payment_date);
                $('#general_receipt_edit_cheque_date').val(receiptData.cheque_date);
                $('#general_receipt_edit_cheque_no').val(receiptData.cheque_no);
                $('#general_receipt_edit_general_bank_name').val(receiptData.general_bank_name);
                $('#general_receipt_edit_title').val(receiptData.title);
                $('#general_receipt_edit_description').val(receiptData.description);
                toggleGeneralReceiptChequeFields(receiptData.payment_mode);
                $('#general_receipt_edit_modal').modal('show');
            },
            error: function () {
                hideLoader();
                alert('General receipt details could not be loaded. Please, try again.');
            }
        });
    }

    function updateGeneralReceipt() {
        if ($('#general_receipt_edit_ledger_head_id').val() == '') {
            alert('Please select the head in Received By.');
            return false;
        }
        if ($('#general_receipt_edit_payment_date').val() == '') {
            alert('Please select the payment date.');
            return false;
        }
        showLoader();
        $.ajax({
            type: 'POST',
            url: webrootUrl + 'societys_ajax/update_general_receipt',
            data: $('#general_receipt_edit_form').serialize(),
            dataType: 'json',
            success: function (jsonData) {
                hideLoader();
                if (jsonData.error == 1) {
                    showNotifyAlert(jsonData, 'general_receipt_edit_error');
                    return false;
                }
                $('#general_receipt_edit_modal').modal('hide');
                window.location.reload();
            },
            error: function () {
                hideLoader();
                alert('The general receipt could not be updated. Please, try again.');
            }
        });
    }
</script>