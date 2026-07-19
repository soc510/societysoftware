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
                    <div class="clearfix"></div>ETS
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
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_account_balancesheet', 'landscape');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                                <?php /* onclick was empty, then briefly the same as Print Friendly - which only opened the print dialog, so the button never produced a file. It now writes the PDF straight out. */ ?>
                                                <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="export_product_pdf();"><i class="fa fa-file-pdf-o"></i><span class="btn-text">Export to PDF</span></a>
                                                <?php
                                                /*
                                                 * The server-side export (ExcelsReportController::download_account_balance_sheet)
                                                 * is hidden, not removed.
                                                 *
                                                 * It is a hand-copied fork of account_balance_sheet() that has drifted badly:
                                                 * none of its fourteen ledger queries filter on financial_year_id, so it sums
                                                 * every year at once, and its dues/advance figures come from getMembersDueAmount(),
                                                 * which has no society filter, no year filter and no date bounds. On a society
                                                 * with five years of vouchers that is a 15x overstatement, and the dues column
                                                 * can include other societies' members.
                                                 *
                                                 * Use the "Export Excel" button instead - it exports the table rendered above,
                                                 * so it always agrees with what is on screen. Re-enable this only once the
                                                 * fork shares the page's data builder.
                                                 */
                                                ?>
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-sm btn-anim" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                              <div ><button type="button" class="btn btn-primary" onclick="export_product_excel()" value="Export" >Export  Excel</button></div>
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
                                                                     $openingPr = number_format($ledgerHeadDetails['openingAmount'],2).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($txnAmount)) {
                                                                     $txnPr = number_format(abs($txnAmount),2).' '.$txnFlag;
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
                                                                        $headPr = number_format(abs($ledgerHeadTotal),2).' '.$headFlag;
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
                                                                     $openingPr = number_format(abs($advanceOpening),2).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($advanceTxn)) {
                                                                     $txnPr = number_format(abs($advanceTxn),2).' '.$txnFlag;
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
                                                                     $openingPr = number_format(abs($totalLiaOpening),2).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($totalLiaTxn)) {
                                                                     $txnPr = number_format(abs($totalLiaTxn),2).' '.$txnFlag;
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
                                                                 $openFlag = 'Dr';
                                                                 
                                                                 $ledgerHeadDetails['openingAmount'] = empty($ledgerHeadDetails['openingAmount']) ? 0 : trim($ledgerHeadDetails['openingAmount']);
                                                                 $ledgerHeadDetails['txnAmount'] = empty($ledgerHeadDetails['txnAmount']) ? 0 : trim($ledgerHeadDetails['txnAmount']);

                                                                 $forYearBalance = $ledgerHeadDetails['txnAmount']-$ledgerHeadDetails['openingAmount'];
                                                                   // echo "<br>".$ledgerHeadDetails['txnAmount']."-".$ledgerHeadDetails['openingAmount']." ".$forYearBalance;

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
                                                                     $openingPr = number_format($ledgerHeadDetails['openingAmount'],2).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($txnAmount)) {
                                                                     $txnPr = number_format(abs($txnAmount),2).' '.$txnFlag;
                                                                 }
                                                                 
                                                                 echo "<tr>
                                                                        <td>$openingPr</td>
                                                                        <td>"; ?>
                                                                 
                                                                <a href="javascript:void(0);" onclick="window.open('<?php echo $this->webroot; ?>account_reports/society_ledger_head_details/<?php echo $ledgerHeadId?>', 'window name', 'width=600,height=400,scrollbars=yes');">
                                                                <?php echo $ledgerHeadDetails['title']; ?></a>    
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
                                                                        $headPr = number_format(abs($ledgerHeadTotal),2).' '.$headFlag;
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
                                                                     $openingPr = number_format(abs($duesOpening),2).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($duesTxn)) {
                                                                     $txnPr = number_format(abs($duesTxn),2).' '.$txnFlag;
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
                                                                     $openingPr = number_format(abs($totalAssetOpening),2).' '.$openFlag;
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($totalAssetTxn)) {
                                                                     $txnPr = number_format(abs($totalAssetTxn),2).' '.$txnFlag;
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
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
<script>
   /*
    * Exports whatever the page is currently showing.
    *
    * This used to look up 'export_to_excel_balance_Sheet', an id that exists
    * nowhere in this view, so getElementById returned null and the next line
    * fell through to an undefined global - the button silently did nothing.
    * The real container is 'print_account_balancesheet' (line 58), the same one
    * Print Friendly uses.
    *
    * Reading the rendered table is deliberate: the figures come from whatever
    * the page computed, so the spreadsheet can never drift away from the screen
    * the way the server-side export in ExcelsReportController has.
    */
   var isAmount = function (v) {
      return /^-?[\d,]+(\.\d+)?\s*(Cr|Dr)?$/i.test(String(v).trim()) && /\d/.test(String(v));
   };

   /*
    * Both exports need the same reading of the report - the two halves as plain
    * grids, which rows are headings, where each half starts. Worked out once
    * here so the spreadsheet and the PDF can never drift apart.
    * Returns null and explains itself if the page has nothing to export yet.
    */
   function balanceSheetLayout(){
      var source = document.getElementById('print_account_balancesheet');
      if (!source) {
         alert('Balance sheet not loaded yet - press Show first.');
         return null;
      }
      /*
       * The first table on the page is only a layout shell: a single <tr> whose
       * two cells each hold a real table - liabilities left, assets right.
       * Passing that shell to table_to_book flattened each nested table into one
       * spreadsheet cell on row 1, then repeated both further down, stacked
       * rather than side by side. So read the two inner tables and lay them out
       * the way the page shows them.
       */
      var inner = source.querySelectorAll('.table-wrap1 > table > tbody > tr > td > table');
      if (!inner.length) {
         alert('Nothing to export - press Show to load the balance sheet.');
         return null;
      }

      var grids = [], widths = [], height = 0, i, r, c, w;
      for (i = 0; i < inner.length; i++) {
         // header:1 keeps it as plain rows; colspan/rowspan are already expanded.
         var grid = XLSX.utils.sheet_to_json(XLSX.utils.table_to_sheet(inner[i]),
                                             {header: 1, raw: false, defval: ''});
         for (w = 0, r = 0; r < grid.length; r++) {
            if (grid[r].length > w) { w = grid[r].length; }
         }
         grids.push(grid);
         widths.push(w);
         if (grid.length > height) { height = grid.length; }
      }

      // sheet_to_json leaves a hole where a cell was empty, and String(undefined)
      // is "undefined" - which would read as content in the tests below.
      var cellAt = function (half, row, col) {
         var cells = grids[half][row];
         var value = cells ? cells[col] : '';
         return (value === undefined || value === null) ? '' : String(value);
      };

      // Lay the halves out left to right with one blank column between them, so
      // the right half starts in the same column even where a row on the left is
      // short. halfOf maps an output column back to its half, -1 being a spacer.
      var halfOf = [], offsetOf = [], columns = 0;
      for (i = 0; i < grids.length; i++) {
         if (i) { halfOf.push(-1); columns++; }
         offsetOf.push(columns);
         for (w = 0; w < widths[i]; w++) { halfOf.push(i); columns++; }
      }

      // Bold is decided per half rather than per row: the two sides carry their
      // section headings on different rows, so a single verdict for the whole row
      // would either bold figures on one side or miss a heading on the other.
      var boldAt = [];
      for (i = 0; i < grids.length; i++) {
         boldAt.push([]);
         for (r = 0; r < height; r++) {
            var filled = 0, label = '', total = false;
            for (c = 0; c < widths[i]; c++) {
               var cell = cellAt(i, r, c).trim();
               if (cell !== '') {
                  filled++;
                  if (label === '') { label = cell; }
                  // Any column, not just the first - the total row opens with
                  // last year's figure and carries the word in column two.
                  if (/^total$/i.test(cell)) { total = true; }
               }
            }
            // The first two rows are the column headings; a row carrying just a
            // label is a section heading; the closing total is bold as well.
            boldAt[i].push(r < 2 || total || (filled === 1 && !isAmount(label)));
         }
      }

      // Society name, registration number, address, report title - the same
      // block the page prints above the table. Flagged bold or not.
      var banner = [];
      var h5 = source.querySelector('h5');
      if (h5 && h5.textContent.trim()) { banner.push([h5.textContent.trim(), true]); }
      var addr = source.querySelectorAll('.report-address-heading');
      for (i = 0; i < addr.length; i++) {
         if (addr[i].textContent.trim()) { banner.push([addr[i].textContent.trim(), false]); }
      }
      var reportTitle = source.querySelector('.report-bill');
      if (reportTitle && reportTitle.textContent.trim()) {
         banner.push([reportTitle.textContent.trim(), true]);
      }

      return {
         grids: grids, widths: widths, height: height, columns: columns,
         halfOf: halfOf, offsetOf: offsetOf, boldAt: boldAt,
         cellAt: cellAt, banner: banner,
         // The value at an output column, '' for the spacer between the halves.
         valueAt: function (row, col) {
            var half = halfOf[col];
            return half < 0 ? '' : cellAt(half, row, col - offsetOf[half]);
         },
         boldFor: function (row, col) {
            var half = halfOf[col];
            return half >= 0 && boldAt[half][row];
         }
      };
   }

   function export_product_excel(){
      var layout = balanceSheetLayout();
      if (!layout) { return; }
      var columns = layout.columns, height = layout.height, banner = layout.banner;
      var halfOf = layout.halfOf, offsetOf = layout.offsetOf;
      var i, r, c;

      /*
       * Written as SpreadsheetML 2003 rather than a real .xlsx.
       *
       * The free build of SheetJS cannot write cell styles at all - borders,
       * bold and alignment are paid features - and this report is read as a
       * document, so the ruled layout matters. An HTML table sent as ms-excel
       * was tried first and Excel dropped most of the CSS; SpreadsheetML states
       * every border and alignment outright, so what Excel shows is what is
       * written here. Excel may warn that the extension does not match the
       * contents; that is expected, answer Yes.
       *
       * The figures still come from the table rendered above, so they stay in
       * step with the screen. Do not swap this for
       * ExcelsReportController::download_account_balance_sheet, which is prettier
       * but filters on no financial year at all and so adds every year together.
       */
      var xml = function (v) {
         return String(v).replace(/&/g, '&amp;').replace(/</g, '&lt;')
                         .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
      };
      var box = '<Borders>' +
                ['Left', 'Right', 'Top', 'Bottom'].map(function (side) {
                   return '<Border ss:Position="' + side +
                          '" ss:LineStyle="Continuous" ss:Weight="1"/>';
                }).join('') +
                '</Borders>';

      var out = [];
      // Built from pieces on purpose: the servers run with short_open_tag on,
      // where an angle bracket followed by a question mark opens PHP mode. Spelt
      // out in one string, the xml declaration below is read as PHP and the whole
      // view dies with "unexpected 'version'". Local PHP has the setting off, so
      // that failure only ever shows up once deployed. Keep it split, and keep
      // this comment free of that character pair too.
      out.push('<' + '?xml version="1.0"?' + '>');
      out.push('<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' +
               ' xmlns:o="urn:schemas-microsoft-com:office:office"' +
               ' xmlns:x="urn:schemas-microsoft-com:office:excel"' +
               ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">');
      out.push('<Styles>');
      out.push('<Style ss:ID="Default" ss:Name="Normal"><Font ss:FontName="Calibri" ss:Size="11"/>' +
               '<Alignment ss:Vertical="Center"/></Style>');
      out.push('<Style ss:ID="ttl"><Font ss:FontName="Calibri" ss:Size="14" ss:Bold="1"/>' +
               '<Alignment ss:Horizontal="Center"/></Style>');
      out.push('<Style ss:ID="sub"><Alignment ss:Horizontal="Center"/></Style>');
      out.push('<Style ss:ID="hd"><Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1"/>' +
               '<Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>' + box + '</Style>');
      out.push('<Style ss:ID="tx">' + box + '</Style>');
      out.push('<Style ss:ID="nm"><Alignment ss:Horizontal="Right"/>' + box + '</Style>');
      out.push('<Style ss:ID="bt"><Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1"/>' + box + '</Style>');
      out.push('<Style ss:ID="bn"><Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1"/>' +
               '<Alignment ss:Horizontal="Right"/>' + box + '</Style>');
      out.push('<Style ss:ID="gap"/>');
      out.push('</Styles>');
      out.push('<Worksheet ss:Name="Balance Sheet"><Table>');

      // Column two of each half carries the ledger head and needs the room; the
      // rest hold figures, and the spacer between the halves stays narrow.
      for (c = 0; c < columns; c++) {
         var within = halfOf[c] < 0 ? -1 : c - offsetOf[halfOf[c]];
         out.push('<Column ss:AutoFitWidth="0" ss:Width="' +
                  (within < 0 ? 18 : (within === 1 ? 200 : 95)) + '"/>');
      }

      var row = function (cells) { out.push('<Row>' + cells.join('') + '</Row>'); };
      var cell = function (style, value, across) {
         return '<Cell ss:StyleID="' + style + '"' +
                (across ? ' ss:MergeAcross="' + across + '"' : '') +
                '><Data ss:Type="String">' + xml(value) + '</Data></Cell>';
      };

      for (i = 0; i < banner.length; i++) {
         row([cell(banner[i][1] ? 'ttl' : 'sub', banner[i][0], columns - 1)]);
      }
      row([]);

      for (r = 0; r < height; r++) {
         var cells = [];
         for (c = 0; c < columns; c++) {
            if (halfOf[c] < 0) { cells.push(cell('gap', '')); continue; }
            var value = layout.valueAt(r, c);
            var bold = layout.boldFor(r, c);
            // Values stay strings: as numbers Excel would drop the Cr/Dr suffix
            // that says which side of the ledger a figure sits on.
            var style = r < 2 ? 'hd'
                      : isAmount(value) ? (bold ? 'bn' : 'nm')
                      : (bold ? 'bt' : 'tx');
            cells.push(cell(style, value));
         }
         row(cells);
      }

      out.push('</Table>');
      out.push('<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">' +
               '<PageSetup><Layout x:Orientation="Landscape"/>' +
               '<PageMargins x:Left="0.4" x:Right="0.4" x:Top="0.5" x:Bottom="0.5"/></PageSetup>' +
               '<FitToPage/><Print><FitWidth>1</FitWidth><FitHeight>0</FitHeight>' +
               '<ValidPrinterInfo/><Scale>100</Scale></Print>' +
               '</WorksheetOptions>');
      out.push('</Worksheet></Workbook>');

      var link = document.createElement('a');
      link.href = URL.createObjectURL(new Blob(['\ufeff' + out.join('')],
                                               {type: 'application/vnd.ms-excel'}));
      link.download = 'BalanceSheet.xls';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      URL.revokeObjectURL(link.href);
   }

   /*
    * "Export To PDF" used to call printAllReports, which only opened the print
    * dialog and left the operator to pick "Save as PDF" - so the button did not
    * do what it said. This writes the file directly.
    *
    * autoTable draws real text rather than a screenshot of the page, so the
    * figures stay selectable and searchable in the PDF and the file stays small.
    */
   function export_product_pdf(){
      if (!window.jspdf || !window.jspdf.jsPDF) {
         alert('PDF library did not load - check the connection and reload the page.');
         return;
      }
      var layout = balanceSheetLayout();
      if (!layout) { return; }

      var doc = new window.jspdf.jsPDF({orientation: 'landscape', unit: 'mm', format: 'a4'});
      var pageWidth = doc.internal.pageSize.getWidth();
      var y = 12;

      for (var b = 0; b < layout.banner.length; b++) {
         doc.setFont('helvetica', layout.banner[b][1] ? 'bold' : 'normal');
         doc.setFontSize(layout.banner[b][1] ? 12 : 9);
         doc.text(String(layout.banner[b][0]), pageWidth / 2, y, {align: 'center'});
         y += layout.banner[b][1] ? 6 : 5;
      }

      var rowValues = function (r) {
         var cells = [];
         for (var c = 0; c < layout.columns; c++) { cells.push(layout.valueAt(r, c)); }
         return cells;
      };
      var head = [], body = [], r;
      for (r = 0; r < 2 && r < layout.height; r++) { head.push(rowValues(r)); }
      for (r = 2; r < layout.height; r++) { body.push(rowValues(r)); }

      // The ledger head needs the width; figures and the spacer do not.
      var columnStyles = {};
      for (var c = 0; c < layout.columns; c++) {
         var half = layout.halfOf[c];
         var within = half < 0 ? -1 : c - layout.offsetOf[half];
         columnStyles[c] = within < 0 ? {cellWidth: 4}
                         : within === 1 ? {cellWidth: 'auto'}
                         : {cellWidth: 22, halign: 'right'};
      }

      doc.autoTable({
         head: head,
         body: body,
         startY: y + 1,
         theme: 'grid',
         margin: {left: 8, right: 8},
         styles: {fontSize: 7, cellPadding: 1.2, lineColor: [0, 0, 0], lineWidth: 0.1,
                  textColor: [0, 0, 0], overflow: 'linebreak'},
         headStyles: {fillColor: [235, 235, 235], textColor: [0, 0, 0],
                      fontStyle: 'bold', halign: 'center'},
         columnStyles: columnStyles,
         didParseCell: function (data) {
            if (data.section !== 'body') { return; }
            // +2 because the body starts after the two heading rows.
            if (layout.boldFor(data.row.index + 2, data.column.index)) {
               data.cell.styles.fontStyle = 'bold';
            }
            if (layout.halfOf[data.column.index] < 0) {
               // The spacer between the halves is a gap, not a column.
               data.cell.styles.lineWidth = 0;
            }
         }
      });

      doc.save('BalanceSheet.pdf');
   }
</script>