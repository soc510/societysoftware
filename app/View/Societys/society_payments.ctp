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
                       <div class="table-wrap">
                           <div class="table-responsive">
                               <table id="datable_1" class="dataTable table table-hover table-bordered padding-th-none padding-td-none">
                                   <thead>
                                       <tr>
                                           <th>#</th>
                                           <th>Paid To</th>
                                           <th>Category</th>
                                           <th>Particulars</th>
                                           <th>Bill Voucher No</th>
                                           <th>Payment Date</th>
                                           <th>Cheque Date</th>
                                           <th>Amount</th>
                                           <th>Cheque No.</th>
                                           <th>Tax Amount</th>
                                           <th>Total Amount</th>
                                           <th>Type</th>
                                           <th class="text-nowrap">Action</th>
                                       </tr>
                                   </thead>
                                   <tbody>                                        
                                       <?php if(isset($societyPaymentData) && count($societyPaymentData) > 0){
                                         $idCount = 1;
                                         foreach($societyPaymentData as $payment){
                                      ?>
                                           <tr id="<?php echo $idCount; ?>">
                                               <td><?php echo $idCount; ?></td>
                                               <td><?php echo isset($payment['SocietyPayment']['title']) ? $payment['SocietyPayment']['title'] :'' ; ?></td>
                                               <td><?php echo $accountCategories[$payment['SocietyPayment']['account_category_id']]; ?></td>
                                               <td><?php echo isset($payment['SocietyPayment']['particulars']) ? $payment['SocietyPayment']['particulars'] :'' ; ?></td>
                                               <td><?php echo isset($payment['SocietyPayment']['bill_voucher_number']) ? $payment['SocietyPayment']['bill_voucher_number'] :'' ; ?></td>
                                               <td><?php echo isset($payment['SocietyPayment']['payment_date']) ? $utilObj->getFormatDate($payment['SocietyPayment']['payment_date'],'d/m/Y') :'' ; ?></td>
                                               <td><?php echo isset($payment['SocietyPayment']['cheque_date']) ? $utilObj->getFormatDate($payment['SocietyPayment']['cheque_date'],'d/m/Y') :'' ; ?></td>
                                               <td class="text-right"><?php echo isset($payment['SocietyPayment']['amount']) ? $payment['SocietyPayment']['amount'] :'' ; ?></td>
                                               <td><?php echo isset($payment['SocietyPayment']['cheque_reference_number']) ? $payment['SocietyPayment']['cheque_reference_number'] :'' ; ?></td>
                                               <td class="text-right"><?php echo isset($payment['SocietyPayment']['tax_amount']) ? $payment['SocietyPayment']['tax_amount'] :'' ; ?></td>
                                               <td class="text-right"><?php echo isset($payment['SocietyPayment']['total_amount']) ? $payment['SocietyPayment']['total_amount'] :'' ; ?></td>
                                               <td><?php echo isset($payment['SocietyPayment']['payment_type']) ? $payment['SocietyPayment']['payment_type'] :'' ; ?></td>
                                               <td class="text-nowrap">
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
