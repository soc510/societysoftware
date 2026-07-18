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
                        <h6 class="panel-title txt-dark">Trial Balancee</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <div class="col-md-12 left-section"> 
                                <form class="" method="post" id="trialBalanceFrm" name="trialBalanceFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                    <div class="row">
                                        
                                        <div class="col-md-3 nopadding">   
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">For the Period &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[TrialBalance][payment_date]" value="<?php echo isset($postData['TrialBalance']['payment_date']) ? $postData['TrialBalance']['payment_date'] :'' ; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[TrialBalance][payment_date_to]" value="<?php echo isset($postData['TrialBalance']['payment_date_to']) ? $postData['TrialBalance']['payment_date_to'] :'' ; ?>">
                                            </div>  
                                        </div>   
                                        <div class="col-md-4 account-report-form"> 
                                            <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                       
                                        </div>
                                    </div>  
                                    <div class="row">                                                        
                                    </div>  
                                </form>  
                            </div>
                            <div id="print_trial_balance">
                            <div class="print-trial-balance">
                                <div class="row">
                                    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                </div>
                                <br>
                                <div class="report-bill">Trial Balance</div>
                                <?php if ($postData['TrialBalance']['payment_date'] != '' && $postData['TrialBalance']['payment_date_to'] != ''){?>
                                <div class="report-bill">For the period <?php echo isset($postData['TrialBalance']['payment_date']) ? $utilObj->getFormatDate($postData['TrialBalance']['payment_date'],'d/m/Y') : '';  ?> to <?php echo isset($postData['TrialBalance']['payment_date_to']) ? $utilObj->getFormatDate($postData['TrialBalance']['payment_date_to'],'d/m/Y') : '';  ?></div>
                                <?php } elseif ($postData['TrialBalance']['payment_date'] != ''){?>
                                <div class="report-bill">For Date <?php echo isset($postData['TrialBalance']['payment_date']) ? $utilObj->getFormatDate($postData['TrialBalance']['payment_date'],'d/m/Y') : '';  ?></div>
                                <?php } elseif ($postData['TrialBalance']['payment_date_to'] != ''){?>
                                <div class="report-bill">For Date <?php echo isset($postData['TrialBalance']['payment_date_to']) ? $utilObj->getFormatDate($postData['TrialBalance']['payment_date_to'],'d/m/Y') : '';  ?></div>
                                <?php } else { ?>
                                <div class="report-bill">As on Date <?php echo date('d/m/Y');?></div>  
                                <div class="report-bill">Financial Year 2021-2022</div> 
                                <?php } ?>
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
                                                // echo "<pre>";
                                                // print_r($trialBalanceHeads[$id]['ledgers']);exit;
                                            foreach($trialBalanceHeads[$id]['ledgers'] as $tmpKey=>$headDetails) { 

                                                //---------------Today Add------------------------>                                                
                                                
                                                if($headCatDetails['category_id']==3){
                                                $headDetails['closing']['credit'] = $headDetails['closing']['credit']- $headDetails['opening']['credit'];
                                                }
                                                if($headCatDetails['category_id']==4){
                                                $headDetails['closing']['debit'] = $headDetails['closing']['debit']- $headDetails['opening']['debit'];
                                                }
                                                //----------------Today Add------------------------>



                                                if($societyId == 22 && $headDetails['details']['title'] == 'Interest Collection') { $headDetails['transactions']['credit'] += 97.63;  $headDetails['closing']['credit'] += 97.63;}
                                                $ledgerTotalArray['opening']['credit'] += $headDetails['opening']['credit'];
                                                $ledgerTotalArray['opening']['debit'] += $headDetails['opening']['debit'];
                                                
                                                $ledgerTotalArray['transactions']['credit'] += $headDetails['transactions']['credit'];
                                                $ledgerTotalArray['transactions']['debit'] += $headDetails['transactions']['debit'];
                                                
                                                $ledgerTotalArray['closing']['credit'] += $headDetails['closing']['credit'];
                                                $ledgerTotalArray['closing']['debit'] += $headDetails['closing']['debit'];
                                            ?>
                                                <tr>
                                                    <td><?php echo $headDetails['details']['title'];?></td>
                                                    <td id="<?php echo $headCatDetails['category_id'];?>" class="text-center border-top-bottom-bold">

                                                    <!----------------Today Add------------------------>
                                                    <?php 
                                                        if($headCatDetails['category_id']==4 && $ledgerTotalArray['opening']['debit'] != ''){
                                                            echo $headDetails['opening']['debit']=0.00;
                                                            $ledgerTotalArray['opening']['debit']=0.00;
                                                        } else {
                                                            echo number_format($headDetails['opening']['debit'],2); 
                                                        }
                                                    ?>
                                                    
                                                    <!------------------------------------------------>

                                                    </td>

                                                    <td class="text-center border-top-bottom-bold">
                                                    <!----------------Today Add------------------------>
                                                    <?php 
                                                    if($headCatDetails['category_id']==3 && $ledgerTotalArray['opening']['credit'] != ''){
                                                    echo $headDetails['opening']['credit']=0.00;
                                                    } else {
                                                    echo number_format($headDetails['opening']['credit'],2); 
                                                    }
                                                    ?>
                                                    <!---------------------------------------------->



                                                    </td>
                                                    <td class="text-center border-top-bottom-bold"><?php if($ledgerTotalArray['transactions']['debit'] != '') { echo number_format($headDetails['transactions']['debit'],2); }else{ echo 0.00;}?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php if($ledgerTotalArray['transactions']['credit'] != '') { echo number_format($headDetails['transactions']['credit'],2); }else{ echo 0.00;}?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php if($ledgerTotalArray['closing']['debit'] != '') { echo number_format($headDetails['closing']['debit'],2); }else{ echo 0.00;}?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php if($ledgerTotalArray['closing']['credit'] != '') { echo number_format($headDetails['closing']['credit'],2); }else{ echo 0.00;}?></td>
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

                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['opening']['debit'])) { echo number_format($ledgerTotalArray['opening']['debit'],2); }else{ echo 0.00;}?></td>


                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['opening']['credit'])) { echo number_format($ledgerTotalArray['opening']['credit'],2); }else{ echo 0.00;}?></td>



                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['transactions']['debit'])) { echo number_format($ledgerTotalArray['transactions']['debit'],2); }else{ echo 0.00;}?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['transactions']['credit'])) { echo number_format($ledgerTotalArray['transactions']['credit'],2); }else{ echo 0.00;}?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['closing']['debit'])) { echo number_format($ledgerTotalArray['closing']['debit'],2); }else{ echo 0.00;}?></td>
                                                    <td style="font-weight:bold;" class="text-center border-top-bottom-bold"><?php if(!empty($ledgerTotalArray['closing']['credit'])) { echo number_format($ledgerTotalArray['closing']['credit'],2); }else{ echo 0.00;}?></td>
                                                </tr>
                                                <!--<tr>
                                                    <td style="font-weight:bold;" colspan="7"></td>
                                                </tr>-->
                                            <?php } ?>    
                                            <tfoot id=""> 
                                                <tr>                                                                       
                                                    <tr>
													<?php /*if($societyId == 22) { $grandTotalArray['transactions']['credit'] += 97.63; $grandTotalArray['closing']['credit'] +=97.63; }*/ ?>
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