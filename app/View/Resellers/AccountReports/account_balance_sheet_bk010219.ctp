<?php
error_reporting(0); 
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Balance Sheet</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="balanceSheetFrm" name="balanceSheetFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-12 member-tariff-left-box"> 
                                    <div class="row">                                                       
                                        <div class="col-md-4 nopadding1">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <div class="account-report-form-box-label">As On Date &nbsp;</div>  
                                                    <input type="date" name='to_date'  class="account-report-form-box-field-date-75" id=""  value="<?php echo isset($postData['to_date']) ? $postData['to_date'] :'' ; ?>">
                                                </div> 
                                            </div>
                                        </div>   
                                        <div class="col-md-8">                                
                                            <div class="account-report-form-field-box">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-sm btn-anim"><i class="icon-rocket"></i><span class="btn-text">Show</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_account_balancesheet');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                                <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>  
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-sm btn-anim" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>                                                         
                                    </div>  
                                </div> 
                            </form> 
                            <div class="clearfix"></div><br><br>
                            <div id="print_account_balancesheet">
                                <div class="print-account-balancesheet">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <div class="row1">
                                        <div class="report-bill">Balance Sheet </div>
                                        <div class="text-center"></div>
                                        <div class="report-bill-outer-section">
                                            <div class="table-wrap1">
                                                <table id="" class="table table-bordered padding-td-none padding-th-none" style='width:50%' >
                                                    <thead>
                                                        <tr>
                                                            <th>Previous Yr</th>
                                                            <th>Capital & Liabilities</th>
                                                            <th>Sch</th>
                                                            <th>Current Yr</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
							<?php $i = 1;foreach ($reportData['Liability'] as $key => $data) {

?>
							<tr id="">
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            
                                                        </tr>
                                                        <tr id="">
                                                            <td></td>
                                                            <td><strong><?php echo $key?></strong></td>
                                                            <td></td>
                                                            <td></td>
                                                            <!-- http://vedhtechnologies.com/elogics-staging -->
                                                        </tr>
							<?php foreach ($data as $ledgerData) {?>
                                                        <tr id="">
                                                            <td><?php echo $ledgerData['opening_amount']?></td>
                                                            <td>
<?php if (!is_numeric($ledgerData['id'])) { ?>
	<a href="javascript:void(0);" onclick="
  window.open('<?php echo $this->webroot; ?>account_reports/account_balance_dues_advance_from_member', 'window name', 'width=600,height=400,scrollbars=yes');"><?php echo $ledgerData['title']?></a>
<?php }
else {?>
 	<a href="javascript:void(0);" onclick="
  window.open('<?php echo $this->webroot; ?>account_reports/society_ledger_head_details/<?php echo $ledgerData["id"]?>', 'window name', 'width=600,height=400,scrollbars=yes');"><?php echo $ledgerData['title']?></a>
<?php } ?>
</td>
                                                            <td><?php echo $i?></td>
                                                            <td><?php echo abs($ledgerData['amount']);echo ($ledgerData['amount'] > 0)?' Cr.':' Dr.'?></td>
                                                        </tr>
                                                        <?php $i++;} }?>
							<tr id="">
                                                            <td><?php echo $totalPrevLiabilityAmount ?> Cr.</td>
                                                            <td>Total</td>
                                                            <td>&nbsp;</td>
                                                            <td><?php if ($totalLiabilityAmount < 0) echo abs($totalLiabilityAmount).' Dr.'; else echo $totalLiabilityAmount." Cr.";?></td>
                                                        </tr>          
						   </tbody>   
                                                </table> </div>
						<div class="table-wrap1" style="position:absolute;margin-left: 618px;top: 276px;width: 47%;">
<table id="" class="table table-bordered padding-td-none padding-th-none">
                                                    <thead>
                                                        <tr>
                                                            <th>Previous Yr</th>
                                                            <th>Property & Assets</th>
                                                            <th>Sch</th>
                                                            <th>Current Yr</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
							<?php foreach ($reportData['Asset'] as $key => $data) {

?>
							<tr id="">
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            
                                                        </tr>
                                                        <tr id="">
                                                            <td></td>
                                                            <td><strong><?php echo $key?></strong></td>
                                                            <td></td>
                                                            <td></td>
                                                            
                                                        </tr>
							<?php foreach ($data as $ledgerData) {?>
                                                        <tr id="">
                                                            <td><?php echo $ledgerData['opening_amount']?></td>
                                                            <td><?php if (!is_numeric($ledgerData['id'])) { ?>
	<a href="javascript:void(0);" onclick="
  window.open('<?php echo $this->webroot; ?>account_reports/account_balance_due_from_members', 'window name', 'width=600,height=400,scrollbars=yes');"><?php echo $ledgerData['title']?></a>
<?php }
else {?>
 	<a href="javascript:void(0);" onclick="
  window.open('<?php echo $this->webroot; ?>account_reports/society_ledger_head_details/<?php echo $ledgerData["id"]?>', 'window name', 'width=600,height=400,scrollbars=yes');"><?php echo $ledgerData['title']?></a>
<?php } ?></td>
                                                            <td><?php echo $i?></td>
                                                            <td><?php echo abs($ledgerData['amount']);echo ($ledgerData['amount'] > 0)?' Dr.':' Cr.'?></td>
                                                        </tr>
                                                        <?php $i++;} }?>
							<tr id="">
                                                            <td><?php echo $totalPrevAssetAmount ?>Dr.</td>
                                                            <td>Total</td>
                                                            <td>&nbsp;</td>
                                                            <td><?php if ($totalAssetAmount < 0) echo abs($totalAssetAmount).' Cr.'; else echo $totalPrevAssetAmount.' Dr.';  ?></td>
                                                        </tr>                                                   
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
            </div>
        </div>
    </div>
</div>
