
<div id="print_bank_reconciliation">
    <div class="print-bank-reconciliation">                                        
        <div class="row">
            <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
        </div>
        <br>
        <div class="report-bill"><?= $bankName; ?></div>
        <br>
        <div class="report-bill">Bank Reconciliation Statement</div>
        <div class="report-bill"><?php if ($dates['payment_date'] != '' || $dates['payment_date_to'] != ''){ ?>From <?php echo  ($dates['payment_date'] != '') ? date("d-m-Y", strtotime($dates['payment_date'])) : '';?> To <?php echo  ($dates['payment_date_to'] != '') ? date("d-m-Y", strtotime($dates['payment_date_to'])) : '';?><?php } ?></div>
        <div class="report-bill"></div>
        <div class="simple-border"></div>
            <br>
        <div class="col-md-12">
            <div class="row">
                <div class="table-wrap1">
                    <table id="" class="table table-bordered padding-td-none padding-th-none" >
                        <thead>
                            <tr>
                                <th>Voucher No.</th>
                                <th>Voucher Date</th>
                                <th>Cheque No.</th>
                                <th>Particulars</th>
                                <th>Amount</th>
                            </tr>
                        </thead>   
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-right"><label class="text-right font-weight-bold txt-black">Balance As Per Bank Book</label>
                                <?php $bankBal = ($cashBookData['OpeningBalance'] + ($cashBookData['CashDeposit']+$cashBookData['ChequeDeposit']+$cashBookData['TransferDeposit'])) - ($cashBookData['CashWithdraw']+$cashBookData['ChequeWithdraw']+$cashBookData['TransferWithdraw']); ?>
                                <td class="text-right"><?php echo isset($bankBal) ? number_format($bankBal,2,'.',',') :0;  ?></td>
                            </tr>
                            <tr>
                                <td colspan="5"><label>Add : Cheques Issued But Not Presented</label></td>
                            </tr>
                            <?php 
                            $totWithdraw=0;
                            if (count($cashBookData['UnclearedWithdrawArr'])){
                                $i=1;
                                foreach ($cashBookData['UnclearedWithdrawArr'] as $data){
                            ?>
                            <tr>
                                <td><?php echo $data['voucher_no'];//$i;?></td>
                                <td><?php echo $data['payment_date'];?></td>
                                <td><?php echo $data['cheque_no'];?></td>
                                <td><?php echo $data['particulars'];?></td>
                                <td class="text-right"><?php echo $data['amount'];?></td>
                            </tr>
                            <?php 
                                    $totWithdraw += $data['amount'];
                                    $i++;
                                }
                            } 
                            ?>
                            <tr>
                                <td colspan="4" class="text-right"><label class="text-right font-weight-bold txt-black">Total...</label></td>
                                <td class="text-right"><?php echo isset($totWithdraw) ? number_format($totWithdraw,2,'.',',') :0;  ?></td>
                            </tr>
                            <tr>
                                <td colspan="5"><label>Less : Cheques Deposited But Not Credited</label></td>
                            </tr>
                            <?php 
                            $totDeposit=0;
                            if (count($cashBookData['UnclearedDepositArr'])){
                                $i=1;
                                foreach ($cashBookData['UnclearedDepositArr'] as $data){
                            ?>
                            <tr>
                                <td><?php echo $data['voucher_no'];//$i;?></td>
                                <td><?php echo $data['payment_date'];?></td>
                                <td><?php echo $data['cheque_no'];?></td>
                                <td><?php echo $data['particulars'];?></td>
                                <td class="text-right"><?php echo $data['amount'];?></td>
                            </tr>
                            <?php 
                                    $totDeposit += $data['amount'];
                                    $i++;
                                }
                            } 
                            ?>
                            <tr>
                                <td colspan="4" class="text-right"><label class="text-right font-weight-bold txt-black">Total...</label></td>
                                <td class="text-right"><?php echo isset($totDeposit) ? number_format($totDeposit,2,'.',',') :0;  ?></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right"><label class="text-right font-weight-bold txt-black">Simple Balance As Per Bank Statement</label>
                                <?php $bal = $bankBal - $cashBookData['UnclearedDeposit'] + $cashBookData['UnclearedWithdraw'];?>
                                <td class="text-right"><?php echo isset($bal) ? number_format($bal,2,'.',',') :0;  ?></td>
                            </tr>
                            
                            
                        </tbody>
                    </table>
                </div>
                
                <div class="clearfix"></div>
                <div class="simple-border"></div>
            </div>
        </div>
    </div>
</div>
