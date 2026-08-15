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
