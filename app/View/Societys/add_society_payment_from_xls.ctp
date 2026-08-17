<div class="container-fluid">

    <div class="row">

        <div class="col-sm-12">

            <div class="panel panel-default card-view">

                <?php echo $this->Session->flash(); ?>

                <div class="panel-heading">

                    <div class="pull-left">

                        <h6 class="panel-title txt-dark">Import Society Payments</h6>

                    </div>

                    <div class="pull-right">

                             <a class='btn btn-success' href="<?php echo $this->Html->url(array('controller' => 'societys', 'action' => 'downloadSampleFileForSocietyPayment')); ?>"><i class="fa fa-download" style="color:#fff;font-size:10px;"></i>&nbsp;Download Sample Template with Ledger Heads</a>

                     </div>

                    <div class="clearfix"></div>

                </div>

                <div class="panel-wrapper collapse in">

                    <div class="panel-body">

<div class="content">

    <div class="upload-frm">
        <?php echo $this->Form->create('data',['type' => 'file'],array('class' => 'form-control','style' =>array('font-size:20px;width:50%'))); ?>
            <?php echo $this->Form->input('file', ['type' => 'file'],array('class' => 'form-control','style'=> array('width:50%;'))); echo "<br>";?>
            <?php echo $this->Form->button(__('Upload File'), ['type'=>'submit', 'class' => 'form-control btn btn-info','style'=> array('width:50%;color:#FFF')]); ?>
        <?php echo $this->Form->end(); ?>
    </div>

    <div class="clearfix"></div>
    <br>

    <?php if(isset($failedRows) && !empty($failedRows)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading" style="background:#f44336;color:#fff;">
            <div class="pull-left">
                <h6 class="panel-title" style="color:#fff;">Failed Records (<?php echo count($failedRows); ?>)
                <?php if(isset($savedCount) && $savedCount > 0) { ?>
                    &mdash; <?php echo $savedCount; ?> saved successfully
                <?php } ?>
                </h6>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-warning btn-sm" onclick="saveSelectedFailedRows();" style="margin-right:5px;">
                    <i class="fa fa-save"></i> Save Selected (After Date Correction)
                </button>
                <button type="button" class="btn btn-info btn-sm" onclick="downloadFailedAsExcel();">
                    <i class="fa fa-download"></i> Download Failed as Excel
                </button>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-condensed" id="failed_records_table" style="margin-bottom:0;">
                    <thead style="background:#fff3cd;">
                        <tr>
                            <th style="width:30px;"><input type="checkbox" id="select_all_failed" onclick="toggleAllFailed(this);"></th>
                            <th>Row</th>
                            <th>DebitTo</th>
                            <th>Payment Date</th>
                            <th>Payment Type</th>
                            <th>Amount</th>
                            <th>Tax Amount</th>
                            <th>Cheque No</th>
                            <th>Cheque Date</th>
                            <th>Particulars</th>
                            <th>Voucher No</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($failedRows as $idx => $fRow) {
                            $d = $fRow['data'];
                            $parsedDate = isset($d['parsed_payment_date']) ? $d['parsed_payment_date'] : (isset($d['payment_date']) ? $d['payment_date'] : '');
                            $dateForInput = '';
                            if(!empty($parsedDate)) {
                                $parsedDate = trim($parsedDate);
                                if(preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $parsedDate, $m)) {
                                    $dateForInput = sprintf('%s-%02d-%02d', $m[1], $m[2], $m[3]);
                                } elseif(preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $parsedDate, $m)) {
                                    $dateForInput = sprintf('%s-%02d-%02d', $m[3], $m[2], $m[1]);
                                } else {
                                    $ts = strtotime($parsedDate);
                                    if($ts) $dateForInput = date('Y-m-d', $ts);
                                }
                            }
                        ?>
                        <tr>
                            <td><input type="checkbox" class="failed_row_chk" data-idx="<?php echo $idx; ?>"></td>
                            <td><?php echo $fRow['row']; ?></td>
                            <td class="failed_debit_to"><?php echo isset($d['ledger_head_title']) ? htmlspecialchars($d['ledger_head_title']) : ''; ?></td>
                            <td><input type="date" class="form-control input-sm failed_payment_date" value="<?php echo htmlspecialchars($dateForInput); ?>" style="width:150px;"></td>
                            <td class="failed_payment_type"><?php echo isset($d['payment_type']) ? htmlspecialchars($d['payment_type']) : 'Bank'; ?></td>
                            <td><input type="number" class="form-control input-sm failed_amount" value="<?php echo isset($d['amount']) ? htmlspecialchars($d['amount']) : '0'; ?>" style="width:100px;" step="0.01"></td>
                            <td class="failed_tax_amount"><?php echo isset($d['tax_amount']) ? htmlspecialchars($d['tax_amount']) : '0'; ?></td>
                            <td class="failed_cheque_no"><?php echo isset($d['cheque_reference_number']) ? htmlspecialchars($d['cheque_reference_number']) : ''; ?></td>
                            <td><?php
                                $chqDate = isset($d['cheque_date']) ? $d['cheque_date'] : '';
                                $chqDateForInput = '';
                                if(!empty($chqDate)) {
                                    $chqDate = trim($chqDate);
                                    if(preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $chqDate, $cm)) {
                                        $chqDateForInput = sprintf('%s-%02d-%02d', $cm[1], $cm[2], $cm[3]);
                                    } elseif(preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $chqDate, $cm)) {
                                        $chqDateForInput = sprintf('%s-%02d-%02d', $cm[3], $cm[2], $cm[1]);
                                    } else {
                                        $chqTs = strtotime($chqDate);
                                        if($chqTs) $chqDateForInput = date('Y-m-d', $chqTs);
                                    }
                                }
                            ?><input type="date" class="form-control input-sm failed_cheque_date" value="<?php echo htmlspecialchars($chqDateForInput); ?>" style="width:150px;"></td>
                            <td class="failed_particulars"><?php echo isset($d['particulars']) ? htmlspecialchars($d['particulars']) : ''; ?></td>
                            <td class="failed_voucher_no"><?php echo isset($d['bill_voucher_number']) ? htmlspecialchars($d['bill_voucher_number']) : ''; ?></td>
                            <td><span class="label label-danger"><?php echo htmlspecialchars($fRow['reason']); ?></span></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Excel Template Instructions</h6>
        </div>
        <div class="panel-body">
            <p><strong>Steps to import society payments:</strong></p>
            <ol>
                <li>Click <strong>"Download Sample Template with Ledger Heads"</strong> to get the Excel template.</li>
                <li>The template has all your society's Debit To (Ledger Head) names pre-filled in Column A.</li>
                <li>Fill in: PaymentDate (calendar), PaymentType (Bank/Cash dropdown), PaymentBy (Bank/Cash account dropdown), Amount, and other details.</li>
                <li>Remove any rows you don't need (empty rows are skipped automatically).</li>
                <li>Upload the filled Excel file and click "Upload File".</li>
            </ol>
            <p><strong>Column Reference:</strong></p>
            <table class="table table-bordered table-condensed" style="width:auto;">
                <tr><th>Column</th><th>Description</th><th>Required</th></tr>
                <tr><td>DebitTo</td><td>Ledger Head name (must match exactly)</td><td>Yes</td></tr>
                <tr><td>PaymentDate</td><td>Payment date (DD/MM/YYYY) - calendar picker</td><td>Yes</td></tr>
                <tr><td>PaymentType</td><td>Bank or Cash - dropdown</td><td>No (defaults to Bank)</td></tr>
                <tr><td>PaymentBy</td><td>Bank/Cash account name - dropdown</td><td>No (auto-selects first)</td></tr>
                <tr><td>Amount</td><td>Payment amount</td><td>Yes</td></tr>
                <tr><td>TaxAmount</td><td>TDS / Tax amount</td><td>No</td></tr>
                <tr><td>Particulars</td><td>Payment particulars</td><td>No</td></tr>
                <tr><td>ChequeNo</td><td>Cheque reference number</td><td>No</td></tr>
                <tr><td>ChequeDate</td><td>Cheque date (DD/MM/YYYY) - calendar picker</td><td>No</td></tr>
                <tr><td>VoucherNo</td><td>Voucher number (auto-generated if blank)</td><td>No</td></tr>
                <tr><td>Notes</td><td>Remarks / notes</td><td>No</td></tr>
            </table>
            <p>The second sheet <strong>"Reference"</strong> lists all valid Ledger Head names, Bank accounts, and Cash accounts.</p>
        </div>
    </div>

</div>

                    </div>

                </div>

              </div>

	</div>

    </div>

</div>

<script>
function toggleAllFailed(el) {
    var checkboxes = document.querySelectorAll('.failed_row_chk');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = el.checked;
    }
}

function ddmmyyyyToYmd(dateStr) {
    if (!dateStr) return '';
    var parts = dateStr.split('/');
    if (parts.length === 3) return parts[2] + '-' + parts[1] + '-' + parts[0];
    return dateStr;
}

function getSelectedRows() {
    var rows = [];
    var checkboxes = document.querySelectorAll('.failed_row_chk:checked');
    for (var i = 0; i < checkboxes.length; i++) {
        var tr = checkboxes[i].closest('tr');
        rows.push({
            debit_to: tr.querySelector('.failed_debit_to').innerText.trim(),
            payment_date: tr.querySelector('.failed_payment_date').value.trim(),
            payment_type: tr.querySelector('.failed_payment_type').innerText.trim() || 'Bank',
            amount: tr.querySelector('.failed_amount').value.trim(),
            tax_amount: tr.querySelector('.failed_tax_amount').innerText.trim(),
            cheque_no: tr.querySelector('.failed_cheque_no').innerText.trim(),
            cheque_date: tr.querySelector('.failed_cheque_date').value.trim(),
            particulars: tr.querySelector('.failed_particulars').innerText.trim(),
            voucher_no: tr.querySelector('.failed_voucher_no').innerText.trim()
        });
    }
    return rows;
}

function saveSelectedFailedRows() {
    var rows = getSelectedRows();
    if (rows.length === 0) {
        alert('Please select at least one row.');
        return;
    }

    var emptyDates = false;
    for (var i = 0; i < rows.length; i++) {
        if (!rows[i].payment_date || rows[i].payment_date === '') {
            emptyDates = true;
            break;
        }
    }
    if (emptyDates) {
        alert('Please correct all Payment Dates before saving.');
        return;
    }

    if (!confirm('Save ' + rows.length + ' selected record(s) with corrected dates?')) return;

    showLoader();
    $.ajax({
        type: 'POST',
        url: webrootUrl + 'societys_ajax/saveFailedPayments',
        data: JSON.stringify({rows: rows}),
        contentType: 'application/json',
        dataType: 'json',
        success: function(resp) {
            hideLoader();
            if (resp.status === 'success') {
                alert(resp.saved + ' record(s) saved successfully.' + (resp.failed > 0 ? ' ' + resp.failed + ' still failed.' : ''));
                if (resp.saved > 0) {
                    var checkboxes = document.querySelectorAll('.failed_row_chk:checked');
                    for (var i = 0; i < checkboxes.length; i++) {
                        checkboxes[i].closest('tr').remove();
                    }
                }
            } else {
                alert('Error: ' + (resp.message || 'Unknown error'));
            }
        },
        error: function() {
            hideLoader();
            alert('Server error. Please try again.');
        }
    });
}

function downloadFailedAsExcel() {
    var checkboxes = document.querySelectorAll('.failed_row_chk:checked');
    if (checkboxes.length === 0) {
        checkboxes = document.querySelectorAll('.failed_row_chk');
        if (checkboxes.length === 0) {
            alert('No failed records to download.');
            return;
        }
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = true;
        }
        document.getElementById('select_all_failed').checked = true;
    }

    var csv = 'DebitTo,PaymentDate,PaymentType,Amount,TaxAmount,ChequeNo,ChequeDate,Particulars,VoucherNo,Reason\n';
    for (var i = 0; i < checkboxes.length; i++) {
        var tr = checkboxes[i].closest('tr');
        var debitTo = tr.querySelector('.failed_debit_to').innerText.trim().replace(/,/g, ' ');
        var payDate = tr.querySelector('.failed_payment_date').value;
        var payType = tr.querySelector('.failed_payment_type').innerText.trim();
        var amount = tr.querySelector('.failed_amount').value.trim();
        var taxAmt = tr.querySelector('.failed_tax_amount').innerText.trim();
        var chequeNo = tr.querySelector('.failed_cheque_no').innerText.trim();
        var chequeDate = tr.querySelector('.failed_cheque_date').value.trim();
        var particulars = tr.querySelector('.failed_particulars').innerText.trim().replace(/,/g, ' ');
        var voucherNo = tr.querySelector('.failed_voucher_no').innerText.trim();
        var reason = tr.querySelector('.label-danger').innerText.trim().replace(/,/g, ' ');
        csv += '"' + debitTo + '","' + payDate + '","' + payType + '",' + amount + ',' + taxAmt + ',"' + chequeNo + '","' + chequeDate + '","' + particulars + '","' + voucherNo + '","' + reason + '"\n';
    }

    var blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'failed_payments.csv';
    link.click();
}
</script>
