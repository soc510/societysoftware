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
                                        <?php /* Only "As On Date" used to be here, and the controller ignored it -
                                                 both ends came from the session financial year, so every run covered
                                                 the whole year whatever was picked. Both ends are read now; leave
                                                 either blank and that end falls back to the financial year. */ ?>
                                        <div class="col-md-3 nopadding1">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-field-box">
                                                    <div class="account-report-form-box-label">From &nbsp;</div>
                                                    <?php /* Defaults to the selected financial year: 2025-2026 opens as
                                                             01-04-2025 to 31-03-2026. Auth.year_start_date is already Y-m-d,
                                                             which is what the date input needs. */ ?>
                                                    <input type="date" name='from_date' class="account-report-form-box-field-date-50" id="" value="<?php echo !empty($postData['from_date']) ? $postData['from_date'] : $this->Session->read('Auth.year_start_date'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding1">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-field-box">
                                                    <div class="account-report-form-box-label">To &nbsp;</div>
                                                    <input type="date" name='to_date'  class="account-report-form-box-field-date-50" id=""  value="<?php echo !empty($postData['to_date']) ? $postData['to_date'] : $this->Session->read('Auth.year_end_date'); ?>">
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
                                        <?php /* Was col-md-6 next to a single date box. With From and To both
                                                 present the row came to 15 of 12 columns and the buttons dropped
                                                 below, so the button strip takes the full width of its own line. */ ?>
                                        <div class="col-md-12">
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
                                                <?php /* Was wrapped in its own <div>, which is block-level and dropped the
                                                         button onto a second line; inline like the rest keeps the strip on one row. */ ?>
                                                <a type="button" data-toggle="tooltip" data-original-title="Export to Excel" class="btn btn-success btn-sm btn-anim" href="javascript:void(0);" onclick="export_product_excel();"><i class="fa fa-download"></i><span class="btn-text">Export Excel</span></a>
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
                                        <?php
                                            // Shows the period the report covers, inside the print block so it
                                            // carries into Print Friendly and the PDF. Falls back to the selected
                                            // financial year when a run left an end blank.
                                            $bsFrom = !empty($postData['from_date']) ? $postData['from_date'] : $this->Session->read('Auth.year_start_date');
                                            $bsTo   = !empty($postData['to_date'])   ? $postData['to_date']   : $this->Session->read('Auth.year_end_date');
                                            if (!empty($bsFrom) && !empty($bsTo)) {
                                                echo '<div class="report-bill">' . date('d-M-Y', strtotime($bsFrom)) . ' to ' . date('d-M-Y', strtotime($bsTo)) . '</div>';
                                            }
                                        ?>
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
                                                                 
                                                                 /* Share Capital used to be skipped here, so the head printed its
                                                                    own row but its opening balance and movement were left out of the
                                                                    liability totals - the sheet then came up short by exactly that
                                                                    head and could not agree with the asset side. It is a liability
                                                                    like any other and belongs in the total; $totalLiaOpening and
                                                                    $totalLiaTxn are added to in only two places (here and Advance
                                                                    From Members), so counting it here cannot double it. */
                                                                 $totalLiaOpening += $ledgerHeadDetails['openingAmount'];
                                                                 $totalLiaTxn += $txnAmount;
                                                                                                                                  
                                                                 $openingPr = '';
                                                                 if(!empty($ledgerHeadDetails['openingAmount'])) {
                                                                     $openingPr = number_format($ledgerHeadDetails['openingAmount'],2);
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($txnAmount)) {
                                                                     $txnPr = number_format(abs($txnAmount),2);
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
                                                                        $headPr = number_format(abs($ledgerHeadTotal),2);
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
                                                                     $openingPr = number_format(abs($advanceOpening),2);
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($advanceTxn)) {
                                                                     $txnPr = number_format(abs($advanceTxn),2);
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
                                                                     $openingPr = number_format(abs($totalLiaOpening),2);
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($totalLiaTxn)) {
                                                                     $txnPr = number_format(abs($totalLiaTxn),2);
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
                                                                     $openingPr = number_format($ledgerHeadDetails['openingAmount'],2);
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($txnAmount)) {
                                                                     $txnPr = number_format(abs($txnAmount),2);
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
                                                                        $headPr = number_format(abs($ledgerHeadTotal),2);
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
                                                                     $openingPr = number_format(abs($duesOpening),2);
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($duesTxn)) {
                                                                     $txnPr = number_format(abs($duesTxn),2);
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
                                                                     $openingPr = number_format(abs($totalAssetOpening),2);
                                                                 }
                                                                 
                                                                 $txnPr = '';
                                                                 if(!empty($totalAssetTxn)) {
                                                                     $txnPr = number_format(abs($totalAssetTxn),2);
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
                                <?php
                                    // Built the signature block as a table rather than a Bootstrap row.
                                    // The col-md-* columns collapsed to full width in the print window,
                                    // stacking the three signatures; a table keeps them side by side and
                                    // evenly spaced on screen and in print alike.
                                    $resellerName = trim((isset($resellerDetails['firstname']) ? $resellerDetails['firstname'] : '') . ' '
                                                       . (isset($resellerDetails['lastname']) ? $resellerDetails['lastname'] : ''));
                                    $resellerRole = isset($resellerDetails['job_role']) ? trim($resellerDetails['job_role']) : '';
                                    // Only add the parentheses when there is a role - an empty reseller was
                                    // printing a bare "()".
                                    if ($resellerName !== '' && $resellerRole !== '') { $resellerName .= ' (' . $resellerRole . ')'; }
                                    elseif ($resellerRole !== '') { $resellerName = '(' . $resellerRole . ')'; }
                                ?>
                                <table style="width:100%; margin-top:15px; border:0;">
                                    <?php if ($resellerName !== '') { ?>
                                    <tr><td colspan="4" style="text-align:left; border:0;"><?php echo h($resellerName); ?></td></tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="4" style="text-align:center; border:0;"><?php echo isset($societyDetails['Society']['society_name']) ? h($societyDetails['Society']['society_name']) : ''; ?></td>
                                    </tr>
                                    <tr><td colspan="4" style="border:0;">&nbsp;</td></tr>
                                    <tr><td colspan="4" style="border:0;">&nbsp;</td></tr>
                                    <tr>
                                        <td style="text-align:left; border:0;">License No : <?php echo isset($resellerDetails['license_no']) ? h($resellerDetails['license_no']) : ''; ?></td>
                                        <td style="text-align:center; border:0;">Chairman</td>
                                        <td style="text-align:center; border:0;">Secretary</td>
                                        <td style="text-align:right; border:0;">Treasurer</td>
                                    </tr>
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
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/report_export.js?version=<?php echo Configure::read('FileVersion');?>"></script>
<script>
   // The report reading, the SpreadsheetML writer and the autoTable PDF all
   // live in js/report_export.js so the income & expenditure statement, which
   // has the same two-column shape, runs the identical code rather than a copy.
   reportExport.alignOnReady('print_account_balancesheet');

   function export_product_excel(){
      reportExport.excel('print_account_balancesheet', 'Balance Sheet', 'BalanceSheet.xls');
   }

   function export_product_pdf(){
      reportExport.pdf('print_account_balancesheet', 'BalanceSheet.pdf');
   }
</script>
