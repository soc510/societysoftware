<?php echo $this->element('dashboard_header'); ?>
<?php echo $this->element('dashboard_navigation'); ?>
<?php echo $this->fetch('content'); ?>
<?php echo $this->element('dashboard_footer'); ?>
<?php echo $this->element('generate_bill_modals'); ?>
<?php echo $this->element('society_ledger_heads_modal'); ?>
<?php echo $this->element('society_flat_shop_details'); ?>
<?php echo $this->element('society_bill_details_modal'); ?>
<?php echo $this->element('society_member_receipts_modal'); ?>
<?php echo $this->element('society_payment_entry_modal'); ?>
<?php echo $this->element('print_member_bill_modal'); ?>
<?php echo $this->element('email_bill_modal'); ?>
<?php if(!empty($this->params['action']) && $this->params['controller'] == 'societys' || $this->params['controller'] == 'societys_members'){?>
<?php echo $this->element('upload_csv_modal'); ?>
<?php }?>    
