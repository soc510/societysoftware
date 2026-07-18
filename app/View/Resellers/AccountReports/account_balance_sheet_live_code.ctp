<?php
error_reporting(0); 
?>
<style>
    .clsHead {
        font-weight:600;
    }
</style>
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
                                        <div class="col-md-3 nopadding1">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <div class="account-report-form-box-label">As On Date &nbsp;</div>  
                                                    <input type="date" name='to_date'  class="account-report-form-box-field-date-50" id=""  value="<?php echo isset($postData['to_date']) ? $postData['to_date'] :'' ; ?>">
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding1">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <input type="checkbox" name='set_order'   id="set_order"  value="1" <?php echo (isset($postData['set_order']) && $postData['set_order'] == '1') ? 'checked' :'' ; ?>>
                                                    <div class="account-report-form-box-label">Set Order of Ledger Heads&nbsp;&nbsp;</div>  
                                                    <input type="hidden" name="order_liabilities" id="order_liabilities" value="<?php echo isset($postData['order_liabilities']) ? $postData['order_liabilities'] :'' ; ?>" /><input type="hidden" name="order_assets" id="order_assets" value="<?php echo isset($postData['order_assets']) ? $postData['order_assets'] :'' ; ?>" />
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="col-md-6">                                
                                            <div class="account-report-form-field-box">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-sm btn-anim"><i class="icon-rocket"></i><span class="btn-text">Show</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_account_balancesheet');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                                <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>  
                                                <?php echo $this->Html->link('<i class="fa fa-download" style="color:#fff;font-size:10px;"></i> Export to Excel', array('controller'=>'excels_report','action' => 'download_account_balance_sheet','?' => array('to_date' => isset($postData['to_date']) ? base64_encode($postData['to_date']) : '','set_order' => isset($postData['set_order']) ? base64_encode($postData['set_order']) : '','order_liabilities' => isset($postData['order_liabilities']) ? base64_encode($postData['order_liabilities']) : '','order_assets' => isset($postData['order_assets']) ? base64_encode($postData['order_assets']) : '')), array('class' => 'btn btn-success btns','data-toggle' => 'tooltip', 'data-original-title' => 'download account balance sheet','escape' => false,'target' => '_blank')); ?> 
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
                                            <div class="table-wrap1"><table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                <tr>
                                                    <td width="50%" valign="top">
                                                        <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                            <thead>
                                                                    <tr>
                                                                        <th>Previous Yr</th>
                                                                        <th rowspan="2">Liabilities</th>
                                                                        <th colspan="2">Current Yr</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>RS. PS.</td>
                                                                        <td>RS. PS.</td>
                                                                        <td>RS. PS.</td>
                                                                    </tr>
                                                                </thead>                                                                
                                                         <?php
                                                         
                                                            $diffLiability = 0;
                                                            $diffAsset = 0;
                                              
                                                            $countLiability = 0;
                                                            foreach($societyHeadSubCategory['Liability'] as $headCategory=>$headCategoryDetails) {
                                                                $countLiability += count($headCategoryDetails['heads'])+2;
                                                            }
                                                            
                                                            $countAsset = 0;
                                                            foreach($societyHeadSubCategory['Asset'] as $headCategory=>$headCategoryDetails) {
                                                                $countAsset += count($headCategoryDetails['heads'])+2;
                                                            }
                                                            
                                                            if($countLiability > $countAsset) {
                                                                $diffAsset = $countLiability-$countAsset;
                                                            } else if($countLiability < $countAsset) {
                                                                $countLiability = $countAsset-$countLiability;
                                                            }
                                                            
                                                         $totalLiaOpening = 0;
                                                         $totalLiaTxn = 0;
                                                         $totalLiaBalance = 0;
                                                             

                                                         foreach($societyHeadSubCategory['Liability'] as $headCategory=>$headCategoryDetails) {
                                                             echo "<tr>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                    </tr>";
                                                             
                                                             echo "<tr>
                                                                        <td></td>
                                                                        <td style='font-weight:600;'>$headCategory</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>";
                                                             
                                                             $ledgerCnt = count($headCategoryDetails['heads']);
                                                             $ledgerSr = 1;
                                                             
                                                             $ledgerHeadTotal = 0;
                                                             
                                                             foreach($headCategoryDetails['heads'] as $ledgerHeadId=>$ledgerHeadDetails) {
                                                                 $openFlag = 'Cr';
                                                                 if($ledgerHeadDetails['openingAmount'] < 0) {
                                                                     $openFlag = 'Dr';
                                                                 }
                                                                 
                                                                 $txnFlag = 'Cr';
                                                                 
                                                                 $txnAmount = $ledgerHeadDetails['openingAmount']+$ledgerHeadDetails['txnAmount'];
                                                                 $ledgerHeadTotal += $txnAmount;
                                                                 
                                                                 if($txnAmount < 0) {
                                                                     $txnFlag = 'Dr';
                                                                 }
                                                                 
                                                                 $lastFlag = 'Cr';
                                                                 if($forYearBalance < 0) {
                                                                     $lastFlag = 'Dr';
                                                                 }
                                                                 
                                                                 if($headCategory != 'Share Capital') {
                                                                    $totalLiaOpening += $ledgerHeadDetails['openingAmount'];
                                                                    $totalLiaTxn += $txnAmount;
                                                                 }
                                                                                                                                  
                                                                 $openingPr = '';
                                                                 if(!empty($ledgerHeadDetails['openingAmount'])) {
                                                                     $openingPr = number_format($ledgerHeadDetails['openingAmount']).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($txnAmount)) {
                                                                     $txnPr = number_format(abs($txnAmount)).' '.$txnFlag;
                                                                 }
                                                                 
                                                                 
                                                                 echo "<tr>
                                                                        <td>$openingPr</td>
                                                                        <td>"; ?>
                                                                 
                                                                <a href="javascript:void(0);" onclick="
  window.open('<?php echo $this->webroot; ?>account_reports/society_ledger_head_details/<?php echo $ledgerHeadId?>', 'window name', 'width=600,height=400,scrollbars=yes');">
                                                                <?php echo $ledgerHeadDetails[title]; ?></a>    
                                                                 <?php echo "</td>
                                                                        <td>$txnPr</td>";
                                                                 
                                                                 if($ledgerSr == $ledgerCnt) {
                                                                     $headFlag = 'Cr';
                                                                     if($ledgerHeadTotal < 0) {
                                                                        $headFlag = 'Dr';
                                                                     }
                                                                 
                                                                    $headPr = '';
                                                                    if(!empty($ledgerHeadTotal)) {
                                                                        //echo $ledgerHeadTotal.'<br>';
                                                                        $headPr = number_format(abs($ledgerHeadTotal)).' '.$headFlag;
                                                                    }
                                                                 
                                                                    echo "<td style='font-weight:600;'>$headPr</td>";
                                                                 } else {
                                                                     echo "<td>&nbsp;</td>";
                                                                 }
                                                                 
                                                                 echo "</tr>";
                                                                 
                                                                 $ledgerSr++;
                                                             }
                                                         }
                                                         
                                                         // Advance From members
                                                         echo "<tr>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                    </tr>";
                                                         
                                                         if(!empty($accountDuesMemberSummary['advance'])) {
                                                             $advanceOpening = abs($accountDuesMemberSummary['advance']['opening_amount']);
                                                             $advanceTxn = abs($accountDuesMemberSummary['advance']['txn_amount']);
                                                             
                                                             //echo $advanceTxn.'<br>';
                                                             
                                                             $totalLiaOpening += $advanceOpening;
                                                             $totalLiaTxn += $advanceTxn;
                                                                                                                              
                                                                 if($advanceOpening >= 0) {
                                                                     $openFlag = 'Cr';
                                                                 } else {
                                                                    $openFlag = 'Dr'; 
                                                                 }
                                                                 
                                                                 
                                                                 if($advanceTxn >= 0) {
                                                                     $txnFlag = 'Cr';
                                                                 } else {
                                                                    $txnFlag = 'Dr'; 
                                                                 }
                                                                 
                                                                 $lastFlag = 'Cr';
                                                                 if($advanceBal < 0) {
                                                                     $lastFlag = 'Dr';
                                                                 }
                                                                 
                                                                 $openingPr = '';
                                                                 if(!empty($advanceOpening)) {
                                                                     $openingPr = number_format(abs($advanceOpening)).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($advanceTxn)) {
                                                                     $txnPr = number_format(abs($advanceTxn)).' '.$txnFlag;
                                                                 }
                                                                 
                                                             echo "<tr>
                                                                        <td>$openingPr</td>
                                                                        <td style='font-weight:600;'>";
                                                             ?>
                                                             <a href="javascript:void(0);" onclick="
  window.open('<?php echo $this->webroot; ?>account_reports/account_balance_dues_advance_from_member', 'window name', 'width=600,height=400,scrollbars=yes');">Advance From Members</a>
                                                        
                                                             <?php echo "</td>
                                                                        <td>$txnPr</td>
                                                                        <td style='font-weight:600;'>$txnPr</td>
                                                                    </tr>";
                                                         } else {
                                                             echo "<tr>
                                                                        <td>0</td>
                                                                        <td style='font-weight:600;'>Advance From Members</td>
                                                                        <td>0</td>
                                                                        <td>0</td>
                                                                    </tr>";
                                                         }
                                                         
                                                         for($i=0;$i<$diffLiability;$i++) {
                                                                echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                                                         }
                                                         
                                                         
                                                                 if($totalLiaOpening >= 0) {
                                                                     $openFlag = 'Cr';
                                                                 } else {
                                                                     $openFlag = 'Dr';
                                                                 }
                                                                 
                                                                 
                                                                 if($totalLiaTxn >= 0) {
                                                                     $txnFlag = 'Cr';
                                                                 } else {
                                                                     $txnFlag = 'Dr';
                                                                 }
                                                                 
                                                                 if($totalLiaBalance >= 0) {
                                                                     $lastFlag = 'Cr';
                                                                 } else {
                                                                     $lastFlag = 'Dr';
                                                                 }
                                                         
                                                         echo "<tr>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                    </tr>";
                                                         
                                                                 $openingPr = '';
                                                                 if(!empty($totalLiaOpening)) {
                                                                     $openingPr = number_format(abs($totalLiaOpening)).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($totalLiaTxn)) {
                                                                     $txnPr = number_format(abs($totalLiaTxn)).' '.$txnFlag;
                                                                 }
                                                                 
                                                         echo "<tr>
                                                                        <td style='font-weight:600;'>$openingPr</td>
                                                                        <td style='font-weight:600;'>Total</td>
                                                                        <td style='font-weight:600;'></td>
                                                                        <td style='font-weight:600;'>$txnPr</td>";
                                                         ?>
                                                        </table>
                                                    </td>
                                                    <td width="50%" valign="top">
                                                        <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                            <thead>
                                                                    <tr>
                                                                        <th>Previous Yr</th>
                                                                        <th rowspan="2">Assets</th>
                                                                        <th colspan="2">Current Yr</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>RS. PS.</td>
                                                                        <td>RS. PS.</td>
                                                                        <td>RS. PS.</td>
                                                                    </tr>
                                                                </thead>
                                                                
                                                        <?php
                                                         $totalAssetOpening = 0;
                                                         $totalAssetTxn = 0;
                                                         $totalAssetBalance = 0;
                                                         
                                                         foreach($societyHeadSubCategory['Asset'] as $headCategory=>$headCategoryDetails) {
                                                             echo "<tr>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                    </tr>";
                                                             
                                                             echo "<tr>
                                                                        <td></td>
                                                                        <td style='font-weight:600;'>$headCategory</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>";
                                                             
                                                             $ledgerCnt = count($headCategoryDetails['heads']);
                                                             $ledgerSr = 1;
                                                             
                                                             $ledgerHeadTotal = 0;
                                                             
                                                             foreach($headCategoryDetails['heads'] as $ledgerHeadId=>$ledgerHeadDetails) {
                                                                 /*if($ledgerHeadId == 1204) {
                                                                     echo '<pre>'; print_r($ledgerHeadDetails);
                                                                 }*/

                                                            
                                                                 $forYearBalance = $ledgerHeadDetails['txnAmount']-$ledgerHeadDetails['openingAmount'];
                                                                 
                                                                 $openFlag = 'Dr';
                                                        

                                  if(!empty($societyHeadSubCategory['Asset']['Cash Balance']['heads'][$ledgerHeadId]['title'])) {
                                           
                                           $txnAmount = $ledgerHeadDetails['txnAmount'] - $ledgerHeadDetails['openingAmount'];
                                           $ledgerHeadTotal += $txnAmount;

                                  }  else {
                                           $txnAmount = $ledgerHeadDetails['openingAmount']-$ledgerHeadDetails['txnAmount'];
                                           $ledgerHeadTotal += $txnAmount;
                                  	
                                  }     



                                                                 
                                                                 if($txnAmount >= 0) {
                                                                     $txnFlag = 'Dr';
                                                                 } else {
                                                                     $txnFlag = 'Cr';
                                                                 }
                                                                 
                                                                 if($forYearBalance >= 0) {
                                                                     $lastFlag = 'Dr';
                                                                 } else {
                                                                     $lastFlag = 'Cr';
                                                                 }
                                                                 
                                                                 $totalAssetOpening += $ledgerHeadDetails['openingAmount'];
                                                                 $totalAssetTxn += $txnAmount;
                                                                                                                                  
                                                                 $openingPr = '';
                                                                 if(!empty($ledgerHeadDetails['openingAmount'])) {
                                                                     $openingPr = number_format($ledgerHeadDetails['openingAmount']).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($txnAmount)) {
                                                                     $txnPr = number_format(abs($txnAmount)).' '.$txnFlag;
                                                                 }
                                                                 
                                                                 echo "<tr>
                                                                        <td>$openingPr</td>
                                                                        <td>"; ?>
                                                                 
                                                                <a href="javascript:void(0);" onclick="
  window.open('<?php echo $this->webroot; ?>account_reports/society_ledger_head_details/<?php echo $ledgerHeadId?>', 'window name', 'width=600,height=400,scrollbars=yes');">
                                                                <?php echo $ledgerHeadDetails[title]; ?></a>    
                                                                 <?php echo "</td>
                                                                        <td>$txnPr</td>";
                                                                 
                                                                 if($ledgerSr == $ledgerCnt) {
                                                                     if($ledgerHeadTotal >= 0) {
                                                                        $headFlag = 'Dr';
                                                                     } else {
                                                                         $headFlag = 'Cr';
                                                                     }
                                                                 
                                                                    $headPr = '';
                                                                    if(!empty($ledgerHeadTotal)) {
                                                                        $headPr = number_format(abs($ledgerHeadTotal)).' '.$headFlag;
                                                                    }
                                                                 
                                                                    echo "<td style='font-weight:600;'>$headPr</td>";
                                                                 } else {
                                                                     echo "<td>&nbsp;</td>";
                                                                 }
                                                                 
                                                                 echo "</tr>";
                                                                 
                                                                 $ledgerSr++;
                                                             }
                                                         }
                                                         
                                                         
                                                         // Dues from members
                                                         echo "<tr>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                    </tr>";
                                                    
                                                         if(!empty($accountDuesMemberSummary['dues'])) {
                                                             $duesOpening = $accountDuesMemberSummary['dues']['opening_amount'];
                                                             $duesTxn = $accountDuesMemberSummary['dues']['txn_amount'];
                                                           
                                                             $duesBal = $duesTxn;
                                                             
                                                             
                                                             $totalAssetOpening += $duesOpening;
                                                             $totalAssetTxn += $duesTxn;
                                                                 
                                                                $openFlag = 'Dr';
                                                                 
                                                                 $txnFlag = 'Cr';
                                                                 if($duesTxn >= 0) {
                                                                     $txnFlag = 'Dr';
                                                                 }
                                                                 
                                                                 
                                                                 $openingPr = '';
                                                                 if(!empty($duesOpening)) {
                                                                     $openingPr = number_format(abs($duesOpening)).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($duesTxn)) {
                                                                     $txnPr = number_format(abs($duesTxn)).' '.$txnFlag;
                                                                 }
                                                             
                                                             
                                                             echo "<tr>
                                                                        <td>$openingPr</td>
                                                                        <td style='font-weight:600;'>";
                                                             ?>
                                                             <a href="javascript:void(0);" onclick="
  window.open('<?php echo $this->webroot; ?>account_reports/account_balance_due_from_members', 'window name', 'width=600,height=400,scrollbars=yes');">Dues From Members</a>
                                                        
                                                             <?php echo "</td>
                                                                        <td id='hi'>$txnPr</td>
                                                                        <td style='font-weight:600;'>$txnPr</td>
                                                                    </tr>";
                                                         }  else {
                                                             echo "<tr>
                                                                        <td>0</td>
                                                                        <td style='font-weight:600;'>Dues From Members</td>
                                                                        <td>0</td>
                                                                        <td>0</td>
                                                                    </tr>";
                                                         }
                                                         
                                                         for($i=0;$i<$diffAsset;$i++) {
                                                                echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                                                         }
                                                         
                                                                $openFlag = 'Dr';
                                                                
                                                                
                                                                 if($totalAssetTxn >= 0) {
                                                                     $txnFlag = 'Dr';
                                                                 } else {
                                                                     $txnFlag = 'Cr';
                                                                 }
                                                                 
                                                                 $lastFlag = 'Dr';
                                                                 if($forYearBalance >= 0) {
                                                                     $lastFlag = 'Cr';
                                                                 }
                                                         
                                                         echo "<tr>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                    </tr>";
                                                         
                                                                 $openingPr = '';
                                                                 if(!empty($totalAssetOpening)) {
                                                                     $openingPr = number_format(abs($totalAssetOpening)).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($totalAssetTxn)) {
                                                                     $txnPr = number_format(abs($totalAssetTxn)).' '.$txnFlag;
                                                                 }
                                                                 
                                                         echo "<tr>
                                                                        <td style='font-weight:600;'>$openingPr</td>
                                                                        <td style='font-weight:600;'>Total</td>
                                                                        <td style='font-weight:600;'></td>
                                                                        <td style='font-weight:600;'>$txnPr</td>";
                                                         ?>                                                                
                                                        </table>
                                                    </td>
                                                </tr>
                                                </table>
                                                
                                            </div>
                                        </div>                                
                                    </div> 
                                    <br>
                                <div class="row">
                                    <div class="col-md-6 col-xs-6 text-left"><?php echo isset($resellerDetails['firstname']) ? $resellerDetails['firstname'] : ''; echo ' ';echo isset($resellerDetails['lastname']) ? $resellerDetails['lastname'] : ''; echo " ($resellerDetails[job_role]) "; ?></div>
                                    <div class="col-md-6 col-xs-3 text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></div>
                                    <div class="clearfix"></div><br><br>
                                    <div class="col-md-2 col-xs-3 text-left">License No : </div>
                                    <div class="col-md-2 col-xs-3 text-left"><?php echo isset($resellerDetails['license_no']) ? $resellerDetails['license_no'] : ''; ?></div>
                                    <div class="col-md-2 col-xs-3 text-center">&nbsp;</div>
                                    
                                    <div class="col-md-2 col-xs-3 text-center">Chairman</div>
                                    <div class="col-md-2 col-xs-3 text-center">Secretary</div>
                                    <div class="col-md-2 col-xs-3 text-center">Treasurer</div>
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


<div class="modal fade" id="sort_heads_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title modal-title-color">Sort Ledger Heads</h6>
            </div>
            <div class="modal-body modal-body-bg">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-1">&nbsp;</div>
                        <div class="col-md-5">
                            <h5>Sort Liability Heads</h5>
                            <table class="table table-bordered sorted_table_Liability">  
                                <tbody id="sorted_table_Liability">
                                    <?php foreach ($societyHeads['Liability'] as $k => $v){ ?>
                                    <tr>
                                        <td><?php echo $v;?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>Reserve Fund</td>                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-5">
                            <h5>Sort Asset Heads</h5>
                            <table class="table table-bordered sorted_table_asset">  
                                <tbody id="sorted_table_Asset">
                                    <?php foreach ($societyHeads['Asset'] as $k => $v){ ?>
                                    <tr>
                                        <td><?php echo $v;?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>Loans & Advances</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-1">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-11"><br />&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="submitSort();">Done</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>