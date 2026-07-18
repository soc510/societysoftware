<?php error_reporting(0) ?>

<html>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Income Expenditure Statement</h6>
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
                                    <div class="text-center"></div>
                                    <div class="report-bill-outer-section">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                <thead>
                                                    <tr>
                                                        <th>Previous Yr.(Rs.)</th>
                                                        <th>Expenditure</th>
                                                        <th>Current Yr.(Rs.)</th>
                                                        <th>Previous Yr.(Rs.)</th>
                                                        <th>Income</th>
                                                        <th>Current Yr.(Rs.)</th>
                                                    </tr>
                                                </thead>
<tbody>
<?php foreach($reportData['Expense'] as $key => $data) { ?>
                                                    <tr id="">
                                                        <td class="text-right"><?php echo number_format($reportData['Expense'][$key]['openingAmount'],2); ?></td>
                                                        <td><?php echo $reportData['Expense'][$key]['title'] ?></td>
                                                        <td class="text-right"><?php echo number_format($reportData['Expense'][$key]['amount'],2); ?></td>
                                                        <td class="text-right"><?php if (isset($reportData['Income'][$key]['openingAmount'])) 
								echo number_format($reportData['Income'][$key]['openingAmount'],2); ?>
							</td>
<td ><?php if (isset($reportData['Income'][$key]['title']))echo $reportData['Income'][$key]['title'] ?></td>
<td  class="text-right"><?php if (isset($reportData['Income'][$key]['amount']))
echo number_format($reportData['Income'][$key]['amount'],2);?></td>
                                                    </tr>

<?php } ?>                                  
                                                    <tr id="">
                                                        <td class="text-right"><?php echo number_format($previousExpense,2);?></td>
                                                        <td><strong>Excess of Income Over Expenditure</strong></td><!-- when Excess of income greater than expenses  -->
                                                        <td class="text-right"><?php if ($excess > 0) echo number_format(abs($excess),2);else echo "0.00";?></td>
                                                        <td class="text-right"><?php echo number_format($previousIncome,2);?></td>
                                                        <td><strong>Excess of Expenditure Over Income</strong></td> <!-- when Excess of expenses greater than income  -->                                                  
                                                        <td class="text-right"><?php if ($excess < 0) echo number_format(abs($excess),2);else echo "0.00" ?></td>
                                                    </tr>
                                                    <tr id="">
                                                        <td class="text-right">0.00</td>
                                                        <td>Total</td>
                                                        <td class="text-right"><?php echo number_format(abs($totalExpenses),2); ?></td>
                                                        <td class="text-right">0.00</td>
                                                        <td>Total</td>                                                    
                                                        <td class="text-right"><?php echo number_format(abs($totalIncome),2); ?></td>
                                                    </tr>




                                             </tbody>   
                                            </table> 
                                        </div>
                                    </div>                                
                                </div> <br>
                                <div class="row">
                                    <div class="col-md-6 col-xs-6 text-center"></div>
                                    <div class="col-md-6 col-xs-3 text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></div>
                                    <div class="clearfix"></div><br><br>
                                    <div class="col-md-6 col-xs-3 text-center"></div>
                                    <div class="col-md-2 col-xs-3 text-center">Chairmen</div>
                                    <div class="col-md-2 col-xs-3 text-center">Secretary</div>
                                    <div class="col-md-2 col-xs-3 text-center">Member</div>
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
