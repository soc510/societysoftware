<?php error_reporting(0);ini_set("display_errors",0);?>
<?php
// Escape a value for a single-quoted JS string that then becomes HTML.
// A member or ledger name with an apostrophe used to end the cloneDiv
// string early - a SyntaxError that killed the whole script, so
// add_component() never defined and Add More did nothing.
$jsText = function ($s) {
    return strtr((string) $s, array(
        '\\' => '\\\\',
        "'"  => "\\'",
        "\r" => '',
        "\n" => ' ',
        '<'  => '\\x3C',
    ));
};
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Journal Voucher</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
<form class="" method="post" id="jvForm" name="chequeClearDateFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
			<input type="hidden"  class="account-report-form-box-field-date" id="" name="data[JournalVoucher][voucher_no]" value="<?php echo isset($voucherNo) ? $voucherNo :''; ?>">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <!--div class="col-md-3 nopadding">
                                <div class="society-report-form-field-box">    
                                    <div class="society-report-form-box-label">VOUCHER NO.<?php echo $voucherNo?></div>                                                                     
                                    <input type="hidden"  class="account-report-form-box-field-date" id="" name="data[JournalVoucher][voucher_no]" value="<?php echo isset($voucherNo) ? $voucherNo :''; ?>">
                                </div>
                            </div-->
                            
                            <div class="row">
                                
                                    <div class="col-md-3 padding-left-10px">
                                        <div class="society-report-form-field-box">    
                                            <div class="society-report-form-box-label">Voucher Date</div>                                                                     
                                            <input type="date" class="account-report-form-box-field-date" id="" name="data[JournalVoucher][voucher_date]" min="<?= $financialYearStartDate; ?>" max="<?= $financialYearEndDate; ?>" value="<?php echo isset($postData['JournalVoucher']['voucher_date']) ? $postData['JournalVoucher']['voucher_date'] :$utilObj->getFormatDate(); ?>" required="">
                                        </div>
                                    </div>

		Principal Arrears&nbsp;&nbsp;<input type="radio" id="payment_type" name="data[payment][type][]" value="Principal Arrears">
		Interest Arrears&nbsp;&nbsp;<input type="radio" id="payment_type" name="data[payment][type][]" value="Interest Arrears">
		Tax Arrears&nbsp;&nbsp;<input type="radio" id="payment_type" name="data[payment][type][]" value="Tax Arrears">
		
                                    <div class="col-md-3 nopadding">
                                        <div class="society-report-form-field-box">    
                                            <div class="society-report-form-box-label">Notes</div>                                                                     
                                            <textarea class="account-report-form-box-field-date" id="" name="data[JournalVoucher][note]" value=""><?php echo isset($postData[0]['JournalVoucher']['note']) ? $postData[0]['JournalVoucher']['note'] :''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="table-wrap" >
                                        <div class="table-responsive1" >
<?php if (empty($voucherNo)) {?>
<div class="row">
                              <!-- border:2px solid red; -->
                                 <div style='margin-left:14px;margin-bottom:8px;margin-top:10px;' class="col-md-12" >
				    <div class="col-md-4"  style='background: #d2d2d2;'><label class="control-label"><strong>Type</strong></label></div>
                                    <div class="col-md-3"  style='background: #d2d2d2;'><label class="control-label"><strong>Account Name</strong></label></div>
                                  
                                    <div class="col-md-2"  style='background: #d2d2d2;'><label class="control-label"><strong>Dr. Amount</strong></label></div>
                                    
                                    <div class="col-md-2"  style='background: #d2d2d2;'>  <label class="control-label"><strong>Cr. Amount</strong></label></div>
                                 </div>
                              
				<div class="col-md-12" >
					<div class="<?php echo $comp_hidden; ?> components" id="components_1">
	<div class="portlet light bordered">
		<div class="form-group">
		     <div class="component-list" id="component-list1">
			<div class="input-group component-input">
				<div class="col-md-2">
					<select class="form-control1" id="jvType1" onchange="disableInputs(this)"  name="data[JournalVoucher][type][]">
                                        	<option value="Credit">Credit</option>
<option value='Debit'>Debit</option>
                                        </select>
				</div>
				<div class="col-md-4">
					<select class="form-control select2" id="id" name="data[JournalVoucher][jv_member_ledger_head_id][]">
                                                                
                                                                <optgroup label="Society Members List">
                                                                    <?php
                                                                    if (isset($memberListForJournalVouchers) && count($memberListForJournalVouchers) > 0) :
                                                                        $s = 1;
                                                                        foreach ($memberListForJournalVouchers as $societyMemberId => $societyMemberName){
                                                                            if (isset($postData['JournalVoucher']['jv_credit_member_head_id']) && $postData['JournalVoucher']['jv_credit_member_head_id'] == $societyMemberId) {
                                                                              ?>
                                                                    <option value="<?php echo $societyMemberId; ?>" selected=""><?php echo $societyMemberName." -- ".$flatNoList[$societyMemberId]; ?></option>
                                                                                                <?php } else { ?>
                                                                    <option value="member-<?php echo $societyMemberId; ?>"><?php echo "$societyMemberName"." -- ".$flatNoList[$societyMemberId]; ?></option>
                                                                             <?php
                                                                                $s++;
                                                                            }
                                                                        } endif;
                                                                    ?>  
                                                                </optgroup>
                                                                <optgroup label="Society Old Members List">
                                                                    <?php
                                                                        foreach ($OldmemberListForJournalVouchers  as $societyMembers){
                                                                            $societyMemberId = $societyMembers['member_identifications']['member_id'];
                                                                            $societyMemberName = $societyMembers['member_identifications']['third_member'];
                                                                            $flatNo = $societyMembers['member_identifications']['flat_no'];
                                                                              ?>
                                                                    <option value="<?php echo "member-$societyMemberId-old"; ?>"><?php echo $societyMemberName." -- ".$flatNo; ?></option>
                                                                    <?php } ?>
                                                                </optgroup>                                                                   
								
                                                                <optgroup label="Society Ledger List">
                                                                    <?php
                                                                        if (isset($ledgerHeadForJournalVouchers) && count($ledgerHeadForJournalVouchers) > 0) :
                                                                            $s = 1;
                                                                            foreach ($ledgerHeadForJournalVouchers as $ledgerId => $ledgerName){
                                                                                if (isset($postData['JournalVoucher']['jv_credit_ledger_head_id']) && $postData['JournalVoucher']['jv_credit_ledger_head_id'] == $ledgerId) {
                                                                                  ?>
                                                                        <option value="<?php echo $ledgerId; ?>" selected=""><?php echo $ledgerName; ?></option>
                                                                                                    <?php } else { ?>
                                                                        <option value="ledger-<?php echo $ledgerId; ?>"><?php echo "$ledgerName"; ?></option>
                                                                                 <?php
                                                                                $s++;
                                                                            }
                                                                        } endif;
                                                                    ?>
                                                                </optgroup>
                                                            </select>
                                                
				</div>			
				<div class="col-md-3">
					<input type="text"  id="debit1" name="data[JournalVoucher][jv_amount_debit][]" class="debit form-control component" placeholder="0.00" value='0' readonly />
				</div>
				<div class="col-md-3">
					<input type="text"  id="credit1" name="data[JournalVoucher][jv_amount_credit][]" class="credit form-control marks"  placeholder="0.00" value='0' />
				</div>
				<span class="input-group-btn" style='visibility:hidden'>
					<button class="btn btn-danger btn-remove-component" type="button"><span class="glyphicon glyphicon-remove"></span></button>
				</span>
				<br/>
			</div>
		</div> 
		<?php /* Add More sits on the totals line rather than above it: the button
		         and the running figures are one strip of controls under the entry
		         rows, and stacking them left a band of empty space that pushed the
		         balance note down onto Submit.

		         Column widths mirror the entry rows above - 2/4/3/3 - so the two
		         figures land directly under the Dr. and Cr. inputs they add up. */ ?>
		<div class="row" id="jv-totals" style="margin-top:12px;">
			<div class="col-md-2">
				<button type="button" class="btn btn-success btn-sm btn-add-component" data-btn-id="1" onclick="add_component(this);"><span class="glyphicon glyphicon-plus"></span> Add More</button>
			</div>
			<div class="col-md-4" style="text-align:right;padding-right:15px;font-weight:bold;line-height:30px;">Total</div>
			<div class="col-md-3" style="font-weight:bold;line-height:30px;"><span id="jv-total-debit">0.00</span></div>
			<div class="col-md-3" style="font-weight:bold;line-height:30px;"><span id="jv-total-credit">0.00</span></div>
		</div>
		<div class="row">
			<div class="col-md-12" style="padding-top:6px;padding-bottom:4px;">
				<span id="jv-balance-note"></span>
			</div>
		</div>


	</div>		
	</div>


</div></div></div><?php } elseif (!empty($voucherNo)) {?>

					    
                                            <table  <?php if (empty($postData[0]['JournalVoucher']['voucher_no'])) echo "style='display:block'"; ?>  class="table table-bordered padding-td-none padding-th-none">
                                                <thead>
                                                    <tr>
                                                        <th>Sr No.</th>
                                                        <th>Type</th>
                                                        <th>Account Name</th>
                                                        <th>DR. AMOUNT</th>
                                                        <th>CR. AMOUNT</th>
                                                    </tr>
                                                </thead>
						
                                                <tbody>
						<?php foreach ($postData as $key => $value ) {  								$dbRecordId = $postData[$key]['JournalVoucher']['id'];
							$jvType = $postData[$key]['JournalVoucher']['jv_type'];
							if ($jvType == 'Credit'){
								$jvMemberId = $postData[$key]['JournalVoucher']['jv_credit_member_head_id'];
								$jvLedgerId = $postData[$key]['JournalVoucher']['jv_credit_ledger_head_id'];
							}
							else {
								$jvMemberId = $postData[$key]['JournalVoucher']['jv_debit_member_head_id'];
								$jvLedgerId = $postData[$key]['JournalVoucher']['jv_debit_ledger_head_id'];
							}
						?>
                                                    <tr>
                                                        <td class="text-center" style="width:5%;"><?php echo $key+1?></td>
                                                        <td class="text-center" style="width:5%;">
                                                            <select class="form-control1" onchange="disableInputs(this)" id="jvType<?php echo $key+1?>" name="data[<?php echo $dbRecordId?>][JournalVoucher][jv_type]">
                                                                <option value="Credit" <?php echo !empty($jvType) &&  $jvType == "Credit" ? "selected" :''; ?> >Credit</option>
								<option value="Debit" <?php echo !empty($jvType) && $jvType == "Debit" ? "selected" :''; ?>>Debit</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control select2" id="id" name="data[<?php echo $dbRecordId?>][JournalVoucher][jv_member_head_id]">
                                                                <option value="">Select Members</option>
                                                                <optgroup label="Society Members List">
                                                                    <?php
                                                                    if (isset($memberListForJournalVouchers) && count($memberListForJournalVouchers) > 0) :
                                                                        $s = 0;
                                                                        foreach ($memberListForJournalVouchers as $societyMemberId => $societyMemberName){
                                                                            if (!empty($jvMemberId) && $jvMemberId == $societyMemberId) {
                                                                              ?>
                                                                    <option value="<?php echo $societyMemberId; ?>" selected=""><?php echo $societyMemberName." -- ".$flatNoList[$societyMemberId]; ?></option>
                                                                                                <?php } else { ?>
                                                                    <option value="<?php echo $societyMemberId; ?>"><?php echo "$societyMemberName"." -- ".$flatNoList[$societyMemberId]; ?></option>
                                                                             <?php
                                                                                $s++;
                                                                            }
                                                                        } endif;
                                                                    ?>  
                                                                </optgroup>
                                                                <optgroup label="Society Old Members List">
                                                                    <?php
                                                                        foreach ($OldmemberListForJournalVouchers  as $societyMembers){
                                                                            $societyMemberId = $societyMembers['member_identifications']['member_id'];
                                                                            $societyMemberName = $societyMembers['member_identifications']['third_member'];
                                                                            $flatNo = $societyMembers['member_identifications']['flat_no'];
                                                                              ?>
                                                                    <option value="<?php echo "member-$societyMemberId-old"; ?>" ><?php echo $societyMemberName." -- ".$flatNo; ?></option>
                                                                    <?php } ?>
                                                                </optgroup>                                                                
                                                            </select>
                                                            <select class="form-control select2" id="id" name="data[<?php echo $dbRecordId?>][JournalVoucher][jv_ledger_head_id]">
                                                                <option value="">Select Ledger</option>
                                                                <optgroup label="Society Ledger List">
                                                                    <?php
                                                                        if (isset($ledgerHeadForJournalVouchers) && count($ledgerHeadForJournalVouchers) > 0) :
                                                                            $s = 1;
                                                                            foreach ($ledgerHeadForJournalVouchers as $ledgerId => $ledgerName){
                                                                                if (!empty($jvLedgerId) && $jvLedgerId == $ledgerId) {
                                                                                  ?>
                                                                        <option value="<?php echo $ledgerId; ?>" selected=""><?php echo $ledgerName; ?></option>
                                                                                                    <?php } else { ?>
                                                                        <option value="<?php echo $ledgerId; ?>"><?php echo "$ledgerName"; ?></option>
                                                                                 <?php
                                                                                $s++;
                                                                            }
                                                                        } endif;
                                                                    ?>
                                                                </optgroup>
                                                            </select>
                                                        </td>
                                                        <td>
								
								<input type="text" class="debit account-report-form-box-field-date" id="debit<?php echo $key+1?>" name="data[<?php echo $dbRecordId?>][JournalVoucher][jv_amount_debited]" value="<?php echo isset($postData[$key]['JournalVoucher']['jv_amount_debited']) ? $postData[$key]['JournalVoucher']['jv_amount_debited'] :'0.00'; ?>" <?php if ($postData[$key]['JournalVoucher']['jv_type'] == 'Credit') echo "disabled='disabled'"?>>
							</td>
                                                        <td><input type="text" class="credit account-report-form-box-field-date" id="credit<?php echo $key+1?>" name="data[<?php echo $dbRecordId?>][JournalVoucher][jv_amount_credited]" value="<?php echo isset($postData[$key]['JournalVoucher']['jv_amount_credited']) ? $postData[$key]['JournalVoucher']['jv_amount_credited'] :'0.00'; ?>" <?php if ($postData[$key]['JournalVoucher']['jv_type'] == 'Debit') echo "disabled='disabled'"?>></td>
                                                    </tr>
                                                    
<?php } ?>                                                </tbody>
                                            </table>
<?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-1 nopadding"></div>  
                                    <div class="clearfix"></div><br>
                                    <div class="col-md-12 account-report-form"> 
					
                                        <button type="button" onclick='validate()' data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button> 
                                        <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a> 
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="table-responsive">
                                    <table id="datable_1" class="dataTable table table-hover table-bordered padding-th-none padding-td-none">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>VOUCHER NO.</th>
                                                <th>VOUCHER DATE</th>
                                                <th> TYPE</th>
                                                <th>DR. ACCOUNT NAME</th>
                                                <th>DR. AMOUNT</th>
                                                <th>CR. ACCOUNT NAME</th>
                                                <th>CR. AMOUNT</th>
                                                <th>Notes</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                        
                                    <?php if(isset($journalVoucherData) && count($journalVoucherData) > 0){
                                      $idCount = 1;
                                      foreach($journalVoucherData as $jVoucherData){
                                   ?>
                                            <tr id="<?php echo $idCount; ?>">
                                                <td><?php echo $idCount; ?></td>
                                                <td><?php echo isset($jVoucherData['JournalVoucher']['voucher_no']) ? $jVoucherData['JournalVoucher']['voucher_no'] :'' ; ?></td>
                                                <td><?php echo isset($jVoucherData['JournalVoucher']['voucher_date']) && !empty($jVoucherData['JournalVoucher']['voucher_date']) ? $utilObj->getFormatDate($jVoucherData['JournalVoucher']['voucher_date'],'d/m/Y'):'' ; ?></td>
                                                <td><?php echo isset($jVoucherData['JournalVoucher']['jv_type']) && !empty($jVoucherData['JournalVoucher']['jv_type']) ? $jVoucherData['JournalVoucher']['jv_type'] :'' ; ?></td>
                                                <td><?php echo isset($jVoucherData['JournalVoucher']['debit_title']) ? $jVoucherData['JournalVoucher']['debit_title'] : ''; ?></td>
                                                <td><?php echo isset($jVoucherData['JournalVoucher']['jv_amount_debited']) ? $jVoucherData['JournalVoucher']['jv_amount_debited'] :'' ; ?></td>
                                                <td><?php echo isset($jVoucherData['JournalVoucher']['creadit_title']) ? $jVoucherData['JournalVoucher']['creadit_title'] : ''; ?></td>
                                                <td><?php echo isset($jVoucherData['JournalVoucher']['jv_amount_credited']) ? $jVoucherData['JournalVoucher']['jv_amount_credited'] :'' ; ?></td>
                                                <td><?php echo isset($jVoucherData['JournalVoucher']['note']) ? $jVoucherData['JournalVoucher']['note'] :'' ; ?></td>
                                                <td class='text-nowrap'>
                                                    <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'reports', 'action' => 'journal_vouchers', $jVoucherData['JournalVoucher']['voucher_no']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                                    <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_journal_voucher', $jVoucherData['JournalVoucher']['voucher_no']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$jVoucherData['JournalVoucher']['voucher_no'])); ?>
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
<script src="/js/jquery.min.js"></script>
<script src="/vendors_js/select2.full.min.js"></script>
<script src="/js/init.js"></script>
<script type="text/javascript">
$(document).ready(function() {
		$("#id").select2();
	});
//$(function(){
			$('.btn-remove-component').click(function(){
				$(this).closest('.component-input').remove();			
					
				});

//		});

function add_component(obj)
			{
				var id =$(obj).attr("data-btn-id");
				//console.log(id);
				var index = $('.component-input').length + 1;

				var y = id-1;

var cloneDiv = '<div class="input-group component-input">'+
			'<div class="col-md-2">'+
					'<select onchange="disableInputs(this)" class="form-control1" id="jvType'+index+'" name="data[JournalVoucher][type][]">'+
                                        	'<option value="Credit">Credit</option><option value="Debit">Debit</option></select></div>'+
			'<div class="col-md-4">'+
					'<select class="form-control select2" id="id" name="data[JournalVoucher][jv_member_ledger_head_id][]">'+
                                                                '<option value="">Select Members</option>'+
                                                                '<optgroup label="Society Members List">'+
                                                                    <?php
                                                                    if (isset($memberListForJournalVouchers) && count($memberListForJournalVouchers) > 0) :
                                                                        $s = 1;
                                                                        foreach ($memberListForJournalVouchers as $societyMemberId => $societyMemberName){
                                                                            if (isset($postData['JournalVoucher']['jv_credit_member_head_id']) && $postData['JournalVoucher']['jv_credit_member_head_id'] == $societyMemberId) {
                                                                              ?>
                                                                    '<option value="<?php echo $societyMemberId; ?>" selected=""><?php echo $jsText($societyMemberName." -- ".$flatNoList[$societyMemberId]); ?></option>'+
                                                                                                <?php } else { ?>
                                                                    '<option value="member-<?php echo $societyMemberId; ?>"><?php echo $jsText($societyMemberName." -- ".$flatNoList[$societyMemberId]); ?></option>'+
                                                                             <?php
                                                                                $s++;
                                                                            }
                                                                        } endif;
                                                                    ?>  
                                                                '</optgroup>'+
                                                                '<optgroup label="Society Old Members List">'+
                                                                    <?php
                                                                        foreach ($OldmemberListForJournalVouchers  as $societyMembers){
                                                                            $societyMemberId = $societyMembers['member_identifications']['member_id'];
                                                                            $societyMemberName = $societyMembers['member_identifications']['third_member'];
                                                                            $flatNo = $societyMembers['member_identifications']['flat_no'];
                                                                              ?>
                                                                    '<option value="<?php echo "member-$societyMemberId-old"; ?>"><?php echo $jsText($societyMemberName." -- ".$flatNo); ?></option>'+
                                                                    <?php } ?>
                                                                '</optgroup>'+                                                                                            
                                                                '<option value="">Select Ledger</option>'+
                                                                '<optgroup label="Society Ledger List">'+
                                                                    <?php
                                                                        if (isset($ledgerHeadForJournalVouchers) && count($ledgerHeadForJournalVouchers) > 0) :
                                                                            $s = 1;
                                                                            foreach ($ledgerHeadForJournalVouchers as $ledgerId => $ledgerName){
                                                                                if (isset($postData['JournalVoucher']['jv_credit_ledger_head_id']) && $postData['JournalVoucher']['jv_credit_ledger_head_id'] == $ledgerId) {
                                                                                  ?>
                                                                        '<option value="<?php echo $ledgerId; ?>" selected=""><?php echo $jsText($ledgerName); ?></option>'+
                                                                                                    <?php } else { ?>
                                                                        '<option value="ledger-<?php echo $ledgerId; ?>"><?php echo $jsText($ledgerName); ?></option>'+
                                                                                 <?php
                                                                                $s++;
                                                                            }
                                                                        } endif;
                                                                    ?>
                                                                '</optgroup>'+
                                                            '</select>'+
				'</div>'+			
				'<div class="col-md-3">'+
				'	<input type="text"  id="debit'+index+'" name="data[JournalVoucher][jv_amount_debit][]" class="debit form-control component" placeholder="0.00" readonly value="0"/>'+
				'</div>'+
				'<div class="col-md-3">'+
				'	<input type="text" id="credit'+index+'" name="data[JournalVoucher][jv_amount_credit][]" class="credit form-control marks"  placeholder="0.00" value="0"/>'+
				'</div>'+
				'<span class="input-group-btn" style="visibility:hidden">'+
				'	<button class="btn btn-danger btn-remove-component" type="button"><span class="glyphicon glyphicon-remove"></span></button>'+
				'</span>'+
				'<br/>'+
			'</div>';
				$('#component-list'+id).append(cloneDiv);

				// A new row starts at 0, so the totals do not move - but the
				// display would go stale after a row is removed without this.
				if (typeof jvUpdateTotals === 'function') { jvUpdateTotals(); }
			}

function disableInputs(obj){
	var value = obj.value;
	
	var id= obj.id.replace('jvType',''); 
	if (value == 'Credit') { 
		$('#debit'+id).attr('readonly', true);
		$('#debit'+id).val(0);
		$('#credit'+id).attr('readonly', false); }
	else if (value == 'Debit') {
		$('#credit'+id).attr('readonly', true)
		$('#credit'+id).val(0);
		$('#debit'+id).attr('readonly', false); }

	// This zeroes the other side, which changes the totals - and .val() does
	// not raise the change event the totals listen for.
	if (typeof jvUpdateTotals === 'function') { jvUpdateTotals(); }
}
/*
 * One place that adds the rows up, used by both the totals display and by
 * validate(). Two separate additions could disagree, and the whole point of
 * showing a total is that it says what Submit is about to decide on.
 *
 * parseFloat, not parseInt: parseInt('5000.50') is 5000, so two sides that
 * differ only in paise used to read as balanced.
 */
function jvSums(){
	var sum = function (selector) {
		var total = 0;
		$(selector).each(function () {
			var value = parseFloat($(this).val());
			if (!isNaN(value)) { total += value; }
		});
		return total;
	};
	return {debit: sum('.debit'), credit: sum('.credit')};
}

function jvUpdateTotals(){
	var sums = jvSums();
	// Rounded before comparing: 0.1 + 0.2 is not 0.3 in floating point, and a
	// voucher that looks balanced on screen must not be rejected on submit.
	var difference = Math.round((sums.debit - sums.credit) * 100) / 100;

	$('#jv-total-debit').text(sums.debit.toFixed(2));
	$('#jv-total-credit').text(sums.credit.toFixed(2));

	var note = $('#jv-balance-note');
	if (sums.debit === 0 && sums.credit === 0) {
		note.text('').css('color', '');
	} else if (difference === 0) {
		note.text('Debit and credit match.').css('color', '#3c763d');
	} else {
		note.text('Out of balance by ' + Math.abs(difference).toFixed(2) +
		          ' - ' + (difference > 0 ? 'credit' : 'debit') + ' is short.')
		    .css('color', '#a94442');
	}
}

// Rows are added after page load, so bind on the container rather than on the
// inputs themselves.
$(document).on('input change keyup', '.debit, .credit', jvUpdateTotals);
$(document).ready(jvUpdateTotals);

function validate(){

	var sums = jvSums();
	var debitSum = sums.debit;
	var creditSum = sums.credit;
	var difference = Math.round((debitSum - creditSum) * 100) / 100;

	jvUpdateTotals();

	if ((debitSum !== 0) && (creditSum !== 0) && (difference === 0))
		$('#jvForm').submit();
	else
	{
		alert('Please check the credit and debit amount and then submit the form');
		return false;
	}
}
</script>
