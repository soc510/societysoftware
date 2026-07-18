<?php 
error_reporting(0);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <div id="print_trial_balance">
                            <div class="print-trial-balance">
                                <div class="row">
                                    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                </div>
                                <br>
                                <div class="report-bill">Trial Balance</div>
                                <div class="report-bill">As on Date <?php echo date('d/m/Y');?></div>  
                                <div class="report-bill">Financial Year 2018-2019</div>  
                                <div class="report-bill-outer-section">
                                    <div class="table-wrap1">
                                        <table id="" class="table table-bordered1 padding-td-none padding-th-none" >
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" class="text-center">Particulars</th>
                                                    <th colspan="2" width="20%" class="text-center">Opening Balance</th>
                                                    <th colspan="2" width="20%" class="text-center">Transaction</th>
                                                    <th colspan="2" width="20%" class="text-center">Closing Balance</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Dr</th>
                                                    <th class="text-center">Cr</th>
                                                    <th class="text-center">Dr</th>
                                                    <th class="text-center">Cr</th>
                                                    <th class="text-center">Dr</th>
                                                    <th class="text-center">Cr</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $grandTotalArray = array();
                                            foreach($societyHeadSubCategoryArray as $id=>$headCatDetails) {
                                                if(empty($trialBalanceHeads[$id])) {
                                                    continue;
                                                }
                                                $headCatTitle = $headCatDetails['title'];
                                            ?>
                                                <tr>
                                                    <td style="font-weight:bold;" colspan="7"><?php echo $headCatTitle;?></td>
                                                </tr>
                                            <?php
                                            $ledgerTotalArray = array();
                                            
                                            foreach($trialBalanceHeads[$id]['ledgers'] as $tmpKey=>$headDetails) { 
                                                $ledgerTotalArray['opening']['credit'] += $headDetails['opening']['credit'];
                                                $ledgerTotalArray['opening']['debit'] += $headDetails['opening']['debit'];
                                                
                                                $ledgerTotalArray['transactions']['credit'] += $headDetails['transactions']['credit'];
                                                $ledgerTotalArray['transactions']['debit'] += $headDetails['transactions']['debit'];
                                                
                                                $ledgerTotalArray['closing']['credit'] += $headDetails['closing']['credit'];
                                                $ledgerTotalArray['closing']['debit'] += $headDetails['closing']['debit'];
                                            ?>
                                                <tr>
                                                    <td><?php echo $headDetails['details']['title'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php if($ledgerTotalArray['opening']['debit'] != '') { echo number_format($headDetails['opening']['debit'],2); }?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php if($ledgerTotalArray['opening']['credit'] != '') { echo number_format($headDetails['opening']['credit'],2); }?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php if($ledgerTotalArray['transactions']['debit'] != '') { echo number_format($headDetails['transactions']['debit'],2); }?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php if($ledgerTotalArray['transactions']['credit'] != '') { echo number_format($headDetails['transactions']['credit'],2); }?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php if($ledgerTotalArray['closing']['debit'] != '') { echo number_format($headDetails['closing']['debit'],2); }?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php if($ledgerTotalArray['closing']['credit'] != '') { echo number_format($headDetails['closing']['credit'],2); }?></td>
                                                </tr> 
                                            <?php } 
                                                $grandTotalArray['opening']['credit'] += $ledgerTotalArray['opening']['credit'];
                                                $grandTotalArray['opening']['debit'] += $ledgerTotalArray['opening']['debit'];
                                                
                                                $grandTotalArray['transactions']['credit'] += $ledgerTotalArray['transactions']['credit'];
                                                $grandTotalArray['transactions']['debit'] += $ledgerTotalArray['transactions']['debit'];
                                                
                                                $grandTotalArray['closing']['credit'] += $ledgerTotalArray['closing']['credit'];
                                                $grandTotalArray['closing']['debit'] += $ledgerTotalArray['closing']['debit'];
                                            ?>
                                                <tr>
                                                    <td style="font-weight:bold;" class="text-center border-bottom-bold">&nbsp;</td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['opening']['debit'])) { echo number_format($ledgerTotalArray['opening']['debit'],2); }?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['opening']['credit'])) { echo number_format($ledgerTotalArray['opening']['credit'],2); }?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['transactions']['debit'])) { echo number_format($ledgerTotalArray['transactions']['debit'],2); }?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['transactions']['credit'])) { echo number_format($ledgerTotalArray['transactions']['credit'],2); }?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['closing']['debit'])) { echo number_format($ledgerTotalArray['closing']['debit'],2); }?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['closing']['credit'])) { echo number_format($ledgerTotalArray['closing']['credit'],2); }?></td>
                                                </tr>
                                                <!--<tr>
                                                    <td style="font-weight:bold;" colspan="7"></td>
                                                </tr>-->
                                            <?php } ?>    
                                            <tfoot id=""> 
                                                <tr>                                                                       
                                                    <tr>
                                                    <td style="font-weight:bold;" class="border-bottom-bold">Grand Total:</td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($grandTotalArray['opening']['debit'])) { echo number_format($grandTotalArray['opening']['debit'],2); }?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($grandTotalArray['opening']['credit'])) { echo number_format($grandTotalArray['opening']['credit'],2); }?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($grandTotalArray['transactions']['debit'])) { echo number_format($grandTotalArray['transactions']['debit'],2); }?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($grandTotalArray['transactions']['credit'])) { echo number_format($grandTotalArray['transactions']['credit'],2); }?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($grandTotalArray['closing']['debit'])) { echo number_format($grandTotalArray['closing']['debit'],2); }?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($grandTotalArray['closing']['credit'])) { echo number_format($grandTotalArray['closing']['credit'],2); }?></td>
                                                </tr>
                                                </tr>
                                            </tfoot>
                                            </tbody>   
                                        </table> 
                                    </div>
                                </div> 
                            </div>  
                            </div>  
                            <br>
                            <center>
                                <button type="button" class="btn btn-success btn-icon left-icon" onclick="printAllReports('print_trial_balance');"><i class="fa fa-print"></i><span> Print</span></button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>