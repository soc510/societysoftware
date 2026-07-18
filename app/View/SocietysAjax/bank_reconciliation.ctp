<div id="print_bank_reconciliation">
    <div class="print-bank-reconciliation">                                        
        <div class="row">
            <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
        </div>
        <br>
        <div class="report-bill"><?= $bankName; ?></div>
        <br>
        <div class="report-bill">Bank Reconciliation 150</div>
        <div class="report-bill"><?php if ($dates['payment_date'] != '' || $dates['payment_date_to'] != ''){ ?>From <?php echo  ($dates['payment_date'] != '') ? date("d-m-Y", strtotime($dates['payment_date'])) : '';?> To <?php echo  ($dates['payment_date_to'] != '') ? date("d-m-Y", strtotime($dates['payment_date_to'])) : '';?><?php } ?></div>
        <div class="report-bill"></div>
        <div class="simple-border"></div>
            <br>
        <div class="col-md-12">
            <div class="row">
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Opening Balance</label>
                    <div class="col-md-2 col-xs-2 text-right font-weight-bold txt-black" style="border-top:2px solid #000;border-bottom:2px solid #000;"><?php echo isset($cashBookData['OpeningBalance']) ? number_format($cashBookData['OpeningBalance'],2,'.',',') :0;  ?></div>
                    
                    <div class="clearfix"></div>
                </div>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Deposits by Cash</label>
                    <div class="col-md-2 col-xs-2 text-right"><?php echo isset($cashBookData['CashDeposit']) ? number_format($cashBookData['CashDeposit'],2,'.',',') :0;  ?></div>
                    
                </div>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Deposits by Cheque</label>
                    <div class="col-md-2 col-xs-2 text-right"><?php echo isset($cashBookData['ChequeDeposit']) ? number_format($cashBookData['ChequeDeposit'],2,'.',',') :0;  ?></div>
                    
                </div>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Deposits by Transfer</label>
                    <div class="col-md-2 col-xs-2 text-right"><?php echo isset($cashBookData['TransferDeposit']) ? number_format($cashBookData['TransferDeposit'],2,'.',',') :0;  ?></div>
                    
                </div>
                <?php $totalDeposit = (isset($cashBookData['CashDeposit']) && isset($cashBookData['ChequeDeposit'])) ? ($cashBookData['CashDeposit']+$cashBookData['ChequeDeposit']+$cashBookData['TransferDeposit']) :0; ?>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Total Deposits</label>
                    <div class="col-md-2 col-xs-2 text-right font-weight-bold txt-black" style="border-top:2px solid #000;border-bottom:2px solid #000;"><?php echo number_format($totalDeposit,2,'.',',');  ?></div>
                    
                </div>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Withdrawls by Cash</label>
                    <div class="col-md-2 col-xs-2 text-right"><?php echo isset($cashBookData['CashWithdraw']) ? number_format($cashBookData['CashWithdraw'],2,'.',',') :0;  ?></div>
                    
                </div>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Withdrawls by Cheque</label>
                    <div class="col-md-2 col-xs-2 text-right"><?php echo isset($cashBookData['ChequeWithdraw']) ? number_format($cashBookData['ChequeWithdraw'],2,'.',',') :0;  ?></div>
                    
                </div>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Withdrawls by Transfer</label>
                    <div class="col-md-2 col-xs-2 text-right"><?php echo isset($cashBookData['TransferWithdraw']) ? number_format($cashBookData['TransferWithdraw'],2,'.',',') :0;  ?></div>
                    
                </div>
                <?php $totalWithdraw = (isset($cashBookData['CashWithdraw']) && isset($cashBookData['ChequeWithdraw'])) ? ($cashBookData['CashWithdraw']+$cashBookData['ChequeWithdraw']+$cashBookData['TransferWithdraw']) :0; ?>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Total Withdrawls</label>
                    <div class="col-md-2 col-xs-2 text-right font-weight-bold txt-black" style="border-top:2px solid #000;border-bottom:2px solid #000;"><?php echo number_format($totalWithdraw,2,'.',',');  ?></div>
                    
                </div>
                <?php $bankBal = ($cashBookData['OpeningBalance'] + ($cashBookData['CashDeposit']+$cashBookData['ChequeDeposit']+$cashBookData['TransferDeposit'])) - ($cashBookData['CashWithdraw']+$cashBookData['ChequeWithdraw']+$cashBookData['TransferWithdraw']); ?>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Balance As Per Bank Book</label>
                    <div class="col-md-2 col-xs-2 text-right"><?php echo isset($bankBal) ? number_format($bankBal,2,'.',',') :0;  ?></div>
                    
                </div>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Cheques Issued But Not Presented</label>
                    <div class="col-md-2 col-xs-2 text-right"><?php echo isset($cashBookData['UnclearedWithdraw']) ? number_format($cashBookData['UnclearedWithdraw'],2,'.',',') :0;  ?></div>
                </div>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Cheques Deposited But Not Credited</label>
                    <div class="col-md-2 col-xs-2 text-right"><?php echo isset($cashBookData['UnclearedDeposit']) ? number_format($cashBookData['UnclearedDeposit'],2,'.',',') :0;  ?></div>
                </div>
                <?php $bal = $bankBal - $cashBookData['UnclearedDeposit'] + $cashBookData['UnclearedWithdraw'];?>
                <div class="">
                    <label class="col-md-6 col-xs-6 text-right font-weight-bold txt-black">Simple Balance As Per Bank Statement</label>
                    <div class="col-md-2 col-xs-2 text-right font-weight-bold txt-black" style="border-top:2px solid #000;border-bottom:2px solid #000;"><?php echo isset($bal) ? number_format($bal,2,'.',',') :0;  ?></div>
                </div>
                <div class="clearfix"></div>
                <div class="simple-border"></div>
            </div>
        </div>
    </div>
</div>
