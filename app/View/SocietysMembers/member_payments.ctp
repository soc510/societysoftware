<?php $paymentModeArray = array(1=>'Cash',3=>'Cheque',2=>'NEFT',4=>'Other');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Payments</h6>
                    </div>                    
                    <div class="pull-right">

   
                <div class="dropdown">
<?php
if($_SERVER['REMOTE_ADDR']=='203.192.245.33'){
?>
                    <!-- Single button -->
                    <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fa fa-download" style="color:#fff;font-size:10px;"></i>
                    Download CSV Fsile
                    <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" data-dropdown-in="bounceIn" data-dropdown-out="bounceOut">
                    <li><a href="javascript:void(0);" onclick="downloadMemberListCSV();">Download Member List</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:void(0);" onclick="downloadMemberPaymentCSV();">Download Payment Entry</a></li>
                    </ul>
          
<?php 
}
?>




                        <?php echo $this->Html->link('<i class="fa fa-plus" style="color:#fff;font-size:10px;"></i> Make Payment', array('controller' => 'SocietysMembers','action' => 'add_member_payment'), array('class' => 'btn btn-primary','data-toggle' => 'tooltip', 'data-original-title' => 'Add member identities','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                          </div>    
                     </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="datable_1" class="dataTable table table-hover table-bordered padding-th-none padding-td-none">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Member Name</th>
                                        <th>Flat/Shop No</th>
                                        <th>Receipt Id</th>
                                        <!--<th>Bill No</th> --> 
                                       <!-- <th>Month</th>-->
                                        <th width="10%">Date</th>
                                        <!--<th width="10%">Amount Payable</th>-->
                                        <th>Amount Paid</th>
                                        <!--<th>Balance Amount</th>-->
                                        <th>Society Bank</th>                                            
                                        <th>Payment Mode</th>                                           
                                        <th>Ref. No</th>
                                        <th>Bill Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>   
                                
                                    <?php if(isset($memberPaymentData) && count($memberPaymentData) > 0){
                                      $idCount = 1;
                                      foreach($memberPaymentData as $PaymentData){
                                   ?>
                                    <tr id="<?php echo $idCount; ?>">
                                        <td><?php echo $idCount; ?></td>
                                        <td><?php echo isset($PaymentData['Member']['member_name']) ? $PaymentData['Member']['member_prefix'].' '.$PaymentData['Member']['member_name']:'' ; ?></td>
                                        <td><?php echo isset($PaymentData['Member']['flat_no']) ? $PaymentData['Member']['flat_no'] :'' ; ?></td>
                                        <td><?php echo isset($PaymentData['MemberPayment']['receipt_id']) ? $PaymentData['MemberPayment']['receipt_id'] :'' ; ?></td>
                                        <!-- <td><?php echo isset($PaymentData['MemberPayment']['bill_generated_id']) ? $PaymentData['MemberPayment']['bill_generated_id'] :'' ; ?></td>-->
                                        <!-- <td><?php echo isset($PaymentData['MemberPayment']['bill_month']) && !empty($PaymentData['MemberPayment']['bill_month']) ? $societyBillObj->monthWordFormatByBillingFrequency($PaymentData['MemberPayment']['bill_month']) :'' ; ?></td>-->
                                        <td><?php echo isset($PaymentData['MemberPayment']['payment_date']) ? $PaymentData['MemberPayment']['payment_date'] :'' ; ?></td>
                                     <!--   <td><?php echo isset($PaymentData['MemberPayment']['amount_payable']) ? $PaymentData['MemberPayment']['amount_payable'] :'' ; ?></td>-->
                                        <td><?php echo isset($PaymentData['MemberPayment']['amount_paid']) ? $PaymentData['MemberPayment']['amount_paid'] :'' ; ?></td>
                                        <!--<td><?php echo isset($PaymentData['MemberPayment']['balance_amount']) ? $PaymentData['MemberPayment']['balance_amount'] :'' ; ?></td>-->
                                        <?php if(isset($PaymentData['MemberPayment']['payment_mode']) && $PaymentData['MemberPayment']['payment_mode'] == Configure::read('PaymentMode.Cash')) { ?>
                                        <td><?php echo isset($PaymentData['MemberPayment']['society_bank_id']) ? $societyCashBalanceHeadsLists[$PaymentData['MemberPayment']['society_bank_id']] :'' ; ?></td>
                                        <?php } else { ?>
                                        <td><?php echo isset($PaymentData['MemberPayment']['society_bank_id']) ? $societyBankBalanceHeadsLists[$PaymentData['MemberPayment']['society_bank_id']] :'' ; ?></td>
                                        <?php } ?>
                                        <td><?php echo isset($PaymentData['MemberPayment']['payment_mode']) ? $paymentModeArray[$PaymentData['MemberPayment']['payment_mode']] :'' ; ?></td>
                                        <td><?php echo isset($PaymentData['MemberPayment']['cheque_reference_number']) ? $PaymentData['MemberPayment']['cheque_reference_number'] :'' ; ?></td>
                                        <td><?php echo isset($PaymentData['MemberPayment']['bill_type']) ? $PaymentData['MemberPayment']['bill_type'] :'' ; ?></td>
                                        <td class='text-nowrap'><a href='javascript:void(0);' class='mr-25' data-toggle='tooltip' data-original-title='Edit' onclick='editMemberPaymentDetails(<?php echo isset($PaymentData['MemberPayment']['id']) ? $PaymentData['MemberPayment']['id'] :'' ; ?>);'><i class='fa fa-pencil text-inverse m-r-10'></i></a>
<?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_member_payments', $PaymentData['MemberPayment']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete member payment with receipt# %s?',$PaymentData['MemberPayment']['receipt_id'])); ?>
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
