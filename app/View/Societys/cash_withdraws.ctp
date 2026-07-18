<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Bank Deposit/Contra/Withdraw</h6>
                    </div>
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-plus" style="color:#fff;font-size:10px;"></i> Add Deposit/Contra/Withdraw', array('controller' => 'societys','action' => 'add_cash_withdraws'), array('class' => 'btn btn-primary','data-toggle' => 'tooltip', 'data-original-title' => 'Add Deposit/Contra/Withdraw', 'escape' => false)); ?>
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
                                           <th>Date</th>
                                           <th>Type</th>
                                           <th>Bank</th>
                                           <th>Amount</th>
                                           <th>Cheque no</th>
                                           <th>Particulars</th>
                                           <th>Narration</th>
                                           <th class="text-nowrap">Action</th>
                                       </tr>
                                   </thead>
                                   <tbody>                                        
                                       <?php if(isset($cashWithdrawData) && count($cashWithdrawData) > 0){
                                         $idCount = 1;
                                         foreach($cashWithdrawData as $withdraw){
                                      ?>
                                           <tr id="<?php echo $idCount; ?>">
                                               <td><?php echo $idCount; ?></td>
                                               <td><?php echo isset($withdraw['CashWithdraw']['payment_date']) ? $utilObj->getFormatDate($withdraw['CashWithdraw']['payment_date'],'d/m/Y') :'' ; ?></td>
                                               <td><?php echo isset($withdraw['CashWithdraw']['txn_type']) ? ucfirst($withdraw['CashWithdraw']['txn_type']) :'' ; ?></td>
                                               <td><?php echo isset($withdraw['SocietyLedgerHeads']['title']) ? $withdraw['SocietyLedgerHeads']['title'] :'' ; ?></td>
                                               <td><?php echo isset($withdraw['CashWithdraw']['amount']) ? $withdraw['CashWithdraw']['amount'] :'' ; ?></td>
                                               <td><?php echo isset($withdraw['CashWithdraw']['cheque_no']) ? $withdraw['CashWithdraw']['cheque_no'] :'' ; ?></td>
                                               <td><?php echo isset($withdraw['CashWithdraw']['particulars']) ? $withdraw['CashWithdraw']['particulars'] :'' ; ?></td>
                                               <td><?php echo isset($withdraw['CashWithdraw']['narration']) ? $withdraw['CashWithdraw']['narration'] :'' ; ?></td>
                                               <td class="text-nowrap">
                                                <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys','action' => 'add_cash_withdraws', $withdraw['CashWithdraw']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                                <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' =>'delete_cash_withdraws', $withdraw['CashWithdraw']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$withdraw['CashWithdraw']['id'])); ?>
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
