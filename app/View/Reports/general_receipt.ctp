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
                                                <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys','action' => 'add_general_receipt', $otherIncomeData['SocietyOtherIncome']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
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