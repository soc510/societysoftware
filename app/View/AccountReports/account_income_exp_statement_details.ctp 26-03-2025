<?php error_reporting(0) ?>

<html>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Income Expenditure Statement Details</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="memberMonthlyControFrm" name="memberMonthlyControFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-12 member-tariff-left-box"> 
                                    <div class="row">                                                       
                                        <div class="col-md-2 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <div class="account-report-form-box-label">From &nbsp;</div>  
                                                    <input type="date" name='from_date' class="account-report-form-box-field-date-75"  value="<?php echo isset($postData['from_date']) ? $postData['from_date'] :'' ; ?>">
                                                </div> 
                                            </div>
                                        </div>                                           
                                        <div class="col-md-2 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <div class="account-report-form-box-label">To &nbsp;</div>  
                                                    <input type="date" name='to_date'  class="account-report-form-box-field-date-75" id=""  value="<?php echo isset($postData['to_date']) ? $postData['to_date'] :'' ; ?>">
                                                </div> 
                                            </div>
                                        </div>   
                                        <div class="col-md-8">                                
                                            <div class="account-report-form-field-box">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-sm btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Show</span></button>  
                                            <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_income_exp_statement');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                            <!--    <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>                                        -->
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-sm btn-anim btn-sm" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>                                                         
                                    </div>  
                                </div> 
                            </form> 
                            <div class="clearfix"></div>
                            <br><br><br>
                            <div id="print_income_exp_statement">
                            <div class="print-income-exp-statement">
                                <div class="row">
                                    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                </div>
                                <br>
                                <div class="row1">
                                    <div class="report-bill">Income & Expenditure Statement</div>
                                    <?php if ($postData['TrialBalance']['from_date'] != '' && $postData['TrialBalance']['to_date'] != ''){?>
                                <div class="report-bill">For the period <?php echo isset($postData['TrialBalance']['from_date']) ? $utilObj->getFormatDate($postData['TrialBalance']['from_date'],'d/m/Y') : '';  ?> to <?php echo isset($postData['TrialBalance']['to_date']) ? $utilObj->getFormatDate($postData['TrialBalance']['to_date'],'d/m/Y') : '';  ?></div>
                                <?php } elseif ($postData['TrialBalance']['from_date'] != ''){?>
                                <div class="report-bill">For Date <?php echo isset($postData['TrialBalance']['from_date']) ? $utilObj->getFormatDate($postData['TrialBalance']['from_date'],'d/m/Y') : '';  ?></div>
                                <?php } elseif ($postData['TrialBalance']['payment_date_to'] != ''){?>
                                <div class="report-bill">For Date <?php echo isset($postData['TrialBalance']['to_date']) ? $utilObj->getFormatDate($postData['TrialBalance']['to_date'],'d/m/Y') : '';  ?></div>
                                <?php } else { ?>
                                <div class="report-bill">As on Date <?php echo date('d/m/Y');?></div>  
                                <?php
                                $db = $this->Session->read('Auth.dbConfig');
                                if ($db == 'default'){
                                    $currentyear = '2019-2020';
                                } else {
                                    $currentyear = '2018-2019';
                                }
                                ?>
                                <div class="report-bill">Financial Year <?php echo $currentyear;?></div> 
                                <?php } ?>
                                    <div class="text-center"></div>
                                    <div class="report-bill-outer-section">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                <tr>
                                                    <td style="vertical-align:baseline !important;" >
                                                        <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                            <thead>
                                                                <?php 
                                                            $overIncome = 0;
                                                            $overExpense = 0;
                                                            $diffExpense = 0;
                                                            $diffIncome = 0;
                                                        
                                                            $countExpenses = $expIndex + count($expenseHeadsWithSubCat);
                                                            $countIncome = $incomeIdex + count($incomHeadsWithSubCat);
                                                            
                                                            $totalOpeningExpenses;
                                                            $totalOpeningIncome;
                                                            
                                                            $diffAmt = $totalOpeningIncome - $totalOpeningExpenses;
                                                            if($diffAmt > 0){
                                                                $balanceIncomeOverExp = $diffAmt;    
                                                                $finalOpeningIncom = $diffAmt;  
                                                                $balExpOverIncom = 0;
                                                                $finalOpeningIncom = $totalOpeningIncome - $diffAmt;                                                                
                                                            }
                                                            else{
                                                            $balanceIncomeOverExp = 0;
                                                            $balExpOverIncom = abs($diffAmt);
                                                            $finalOpeningIncom = $balExpOverIncom + $totalOpeningIncome;                                                                
                                                            }
                                                            
                                                            ?>

                                                                    <tr>
                                                                        <th>Previous Yr.(Rs.)</th>
                                                                        <th>Expenditure</th>
                                                                        <th>Current Yr.(Rs.)</th>
                                                                        <th>Total</th>
                                                                    </tr>
                                                                </thead>
                                                            <?php 

                                                            if($countIncome > $countExpenses) {
                                                                $diffExpense = $countIncome-$countExpenses;
                                                            } else if($countIncome < $countExpenses) {
                                                                $diffIncome = $countExpenses-$countIncome;
                                                            }
                                                            // echo $diffExpense.'--'.$diffIncome;
                                                            $totalAmountExpenditure=0;
                                                            $i = 0;
                                                            foreach($expenseHeadsWithSubCat as $expenseSubCatHeads){ ?>
                                                            <tr id="">
                                                                    <td></td>
                                                                    <td><strong><h6><?php echo $expenseSubCatHeads[0]['sub_category']; ?></h6></strong></td>
                                                                    <td id="12"></td>
                                                                    <td></td>
                                                            </tr>                                                                                                                
                                                                
                                                            <?php 
                                                            $expCatWisetrnxAmt = 0;
                                                            $expEleCount = count($expenseSubCatHeads);
                                                            foreach($expenseSubCatHeads as $expHeadId => $expHeadData) { 
                                                                $totalAmountExpenditure = $totalAmountExpenditure+$expHeadData['txnAmount'];
                                                                $expCatWisetrnxAmt = $expCatWisetrnxAmt + $expHeadData['txnAmount'];
                                                                ?>
                                                                <tr id="">
                                                                        <td><?php echo $expHeadData['openingAmount']; ?></td>
                                                                        <td><b></b><?php echo $expHeadData['title']; ?></b></td>
                                                                        <td id="12"><?php echo $expHeadData['txnAmount']; ?></td>
                                                                        <?php echo (($expEleCount-1) == $expHeadId) ?  "<td> $expCatWisetrnxAmt </td>" :  "<td></td>";  ?>
                                                                </tr>
                                                            <?php } 
                                                            }
                                                            
                                                            
                                                            for($i=0;$i<$diffExpense;$i++) { 
                                                                echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                                                            }
                                                            ?>
                                                            <tr id="">
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                            </tr>   
                                                            <tr id="">
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td id="totalamountexpenditure"><?php echo number_format(abs($totalAmountExpenditure),2);?></td>
                                                            </tr>   
                                                            <tr id="">
                                                                        <td><?php echo $balanceIncomeOverExp; ?></td>
                                                                        <td><strong>Excess of Income Over Expenditure</strong></td>
                                                                        <td></td>
                                                                        <td id="over_expenditure"></td>
                                                            </tr>
                                                            <tr id="">
                                                                        <td><?php echo $totalOpeningExpenses;?></td>
                                                                        <td><strong>Total</strong></td>
                                                                        <td></td>
                                                                        <td id="subtotal"><?php echo number_format(($totalExpenses),2);//echo number_format(($totalExpenses+$overIncome),2); ?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td>
                                                        <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                            <thead>
                                                                    <tr>
                                                                        <th>Previous Yr.(Rs.)</th>
                                                                        <th>Income</th>
                                                                        <th>Current Yr.(Rs.)</th>
                                                                        <th>Total</th>
                                                                    </tr>
                                                                </thead>
                                                            <?php 
                                                            $totalAmountIncome=0;
                                                            $j = 0;
                                                            foreach($incomHeadsWithSubCat as $catIndex => $incomeHeadsArr){ 
                                                            ?>
                                                                <tr id="">
                                                                        <td></td>
                                                                        <td><strong><h6><?php echo $incomeHeadsArr[0]['sub_category']; ?></h6></strong></td>
                                                                        <td id="4545"></td>
                                                                        <td></td>
                                                                </tr>                                                                
                                                                <?php
                                                                $catWisetrnxAmt = 0;
                                                                $eleCount = count($incomeHeadsArr);
                                                                foreach($incomeHeadsArr as $headId => $headData) { $j++;
                                                                    $totalAmountIncome = $totalAmountIncome + $headData['txnAmount'];
                                                                    $ledgerId = $headData['sub_category_id']; 
                                                                    $catWisetrnxAmt = $catWisetrnxAmt +  $headData['txnAmount'];
                                                                    ?>
                                                                    
                                                                    <tr id="">
                                                                            <td><?php echo $headData['openingAmount']; ?></td>
                                                                            <td><?php echo $headData['title']; ?></td>
                                                                            <td id="4545"><?php echo $headData['txnAmount']; ?></td>
                                                                            <?php echo (($eleCount-1) == $headId) ?  "<td> $catWisetrnxAmt </td>" :  "<td></td>";  ?>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } 

                                                            for($i=0;$i<$diffIncome;$i++) {
                                                                echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                                                            }
                                                            ?>
                                                            <tr id="">
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                            </tr>    
                                                            <tr id="">
                                                                        <td><?php echo $totalOpeningIncome;?></td>
                                                                        <td>&nbsp;</td>
                                                                        <td>&nbsp;</td>
                                                            </tr>                                                               
                                                            <tr id="">
                                                                        <td><?php echo $balExpOverIncom; ?></td>
                                                                        <td><strong>Excess of Expenditure Over Income</strong></td>
                                                                        <td></td>
                                                                        <td><b><?php if (($totalExpenses-$totalIncome) > 0) { $overExpense = abs($totalExpenses-$totalIncome); echo number_format(abs($totalExpenses-$totalIncome),2); } else { echo "0.00"; } ?></b></td>
                                                            </tr>
                                                            <tr id="">
                                                                        <td><?php  echo $finalOpeningIncom;?></td>
                                                                        <td><strong>Total</strong></td>
                                                                        <td></td>                                                                        
                                                                        <td id="totalamountincome"><?php echo number_format(($totalAmountIncome),2); //echo number_format(($totalIncome+$overExpense),2); ?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>    
                                           
                                        </div>
                                    </div>                                
                                </div> <br>
                                <div class="row">
                                    <div class="col-md-6 col-xs-6 text-center"></div>
                                    <div class="col-md-6 col-xs-3 text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></div>
                                    <div class="clearfix"></div><br><br>
                                    <div class="col-md-6 col-xs-3 text-center"></div>
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
</html>
<script>
var totalamountincome =  $('#totalamountincome').text();    
$('#subtotal').text(totalamountincome);
var subtotalval = $('#subtotal').text().split(",").join("");
var totalamountexpenditure  = $('#totalamountexpenditure').text().split(",").join("");
var total_val = Number(subtotalval)-Number(totalamountexpenditure);
total_val = total_val.toLocaleString();

$('#over_expenditure').text(total_val+".00");
</script>
