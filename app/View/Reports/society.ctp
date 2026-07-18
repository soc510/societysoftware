<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <?php echo $this->Session->flash(); ?>
              <!--  <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Society Reports 
                            <select name="accountReport" style="height:30px;width:250px;margin-left:20px;">
                                <option value="">Select Report</option>
                                <option value="members_list">List of Members</option>
                                <option value="tenants_list">List of Tenants</option>
                                <option value="bill">Bill</option>
                                <option value="receipt_voucher">Receipt Voucher</option>
                                <option value="outstanding_bills">Outstanding Bills</option>
                                <option value="payment_voucher">Payment Voucher</option>
                                <option value="memberwise_tariff">Memberwise Tariff</option>
                                <option value="member_passbook">Member Passbook</option>								
                            </select>
                        </h6>						
                    </div>                    
                </div>-->
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="button-list mt-25">
                            <a type="button" data-toggle="tooltip" data-original-title="List Of Member" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'reports','action' =>'society_report_list_of_member')); ?>"><span class="btn-text">List Of Member</span></a>
                            <a type="button" data-toggle="tooltip" data-original-title="List Of Tenant" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'reports','action' =>'society_report_list_of_tenant')); ?>"><span class="btn-text">List Of Tenant</span></a>
                            <a type="button" data-toggle="tooltip" data-original-title="Bill" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'reports','action' =>'bill_with_receipt')); ?>"><span class="btn-text">Bill with Receipt</span></a>
                            <a type="button" data-toggle="tooltip" data-original-title="Receipt Voucher" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'reports','action' =>'society_receipt_voucher')); ?>"><span class="btn-text">Receipt Voucher</span></a>
                            <a type="button" data-toggle="tooltip" data-original-title="Outstanding Bills" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'reports','action' =>'society_outstanding_bills')); ?>"><span class="btn-text">Outstanding Bills</span></a>
                            <a type="button" data-toggle="tooltip" data-original-title="Payment Voucher" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'reports','action' =>'society_payment_voucher')); ?>"><span class="btn-text">Payment Voucher</span></a>
                            <a type="button" data-toggle="tooltip" data-original-title="Memberwise Tariff" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'reports','action' =>'society_memberwise_tariff')); ?>"><span class="btn-text">Memberwise Tariff</span></a>
                            <a type="button" data-toggle="tooltip" data-original-title="Member Passbook" class="btn btn-default" target="_blank" href="<?php echo Router::url(array('controller' =>'reports','action' =>'society_member_passbook')); ?>"><span class="btn-text">Member Passbook</span></a>  
                            <!--<a type="button" data-toggle="tooltip" data-original-title="List Of Member" class="btn btn-default" href="<?php echo Router::url(array('controller' =>'reports','action' =>'society_outstanding_bills_report')); ?>"><span class="btn-text">Outstanding Bills Report</span></a>
                            <a type="button" data-toggle="tooltip" data-original-title="List Of Member" class="btn btn-default" href="<?php echo Router::url(array('controller' =>'reports','action' =>'society_payment_voucher_bill')); ?>"><span class="btn-text">Payment Voucher Report</span></a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
