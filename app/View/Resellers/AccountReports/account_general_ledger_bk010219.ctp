<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">General Ledger</h6>
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
                                        <div class="col-md-2 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Report &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-65" id="" name="data[SocietyPayment][report_type]">
                                                    <option value="">Select</option>  
                                                    <option value="Detail" <?php if(isset($postData['SocietyPayment']['report_type']) && $postData['SocietyPayment']['report_type']=='Detail'){echo 'selected';} ?>>Detail</option>  
                                                    <option value="Summary" <?php if(isset($postData['SocietyPayment']['report_type']) && $postData['SocietyPayment']['report_type']=='Summary'){echo 'selected';} ?>>Summary</option>  
                                                </select> 
                                            </div> 
                                        </div>      
                                        <div class="col-md-3 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Ledger For &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-65" id="" name="data[SocietyPayment][ledger_for]">
                                                    <option value="">Select</option>  
                                                    <option value="Particular Subgroup" <?php if(isset($postData['SocietyPayment']['ledger_for']) && $postData['SocietyPayment']['ledger_for']=='Particular Subgroup'){echo 'selected';} ?>>Particular Subgroup</option>  
                                                    <option value="All" <?php if(isset($postData['SocietyPayment']['ledger_for']) && $postData['SocietyPayment']['ledger_for']=='Bank'){echo 'All';} ?>>All</option>  
                                                    <option value="All(Excluding Bank,Cash,Member,Tariff)" <?php if(isset($postData['SocietyPayment']['ledger_for']) && $postData['SocietyPayment']['ledger_for']=='All(Excluding Bank,Cash,Member,Tariff)'){echo 'selected';} ?>>All(Excluding Bank,Cash,Member,Tariff)</option>  
                                                </select> 
                                            </div> 
                                        </div>  
                                        <div class="col-md-3 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">A/c Name &nbsp;</div>  
                                                <input type="text" class="account-report-form-box-field-70" id="" name="data[SocietyPayment][account_name]" value="<?php echo isset($postData['SocietyPayment']['account_name']) ? $postData['SocietyPayment']['account_name'] :'' ; ?>">
                                            </div> 
                                        </div>                                                       
                                        <div class="col-md-2 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <div class="account-report-form-box-label">From &nbsp;</div>  
                                                    <input type="date" class="account-report-form-box-field-date-75" id="" name="data[SocietyPayment][payment_date]" value="<?php echo isset($postData['SocietyPayment']['payment_date']) ? $postData['SocietyPayment']['payment_date'] :'' ; ?>">
                                                </div> 
                                            </div>
                                        </div>                                           
                                        <div class="col-md-2 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <div class="account-report-form-box-label">To &nbsp;</div>  
                                                    <input type="date" class="account-report-form-box-field-date-75" id="" name="data[SocietyPayment][payment_date_to]" value="<?php echo isset($postData['SocietyPayment']['payment_date_to']) ? $postData['SocietyPayment']['payment_date_to'] :'' ; ?>">
                                                </div> 
                                            </div>
                                        </div>                                                      
                                    </div>  
                                    <div class="clearfix"></div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="account-report-form-field-box">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Show</span></button>
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_general_ledger');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                                <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>                                          
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btn-sm" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>      
                                    </div>
                                </div> 
                            </form> 

                            <div class="clearfix"></div>
                            <br><br><br>
                            <div id="print_general_ledger">
                                <div class="print-general-ledger">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="row1">
                                        <div class="report-bill">General Ledger</div>
                                        <div class="report-bill"></div>
                                        <div class="text-center"></div>
                                        <div class="report-bill-outer-section">
                                            <div class="table-wrap1">
                                                <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                    <thead>
                                                        <tr>
                                                            <th width="12%" rowspan="2" class="text-center">Date</th>
                                                            <th colspan="2" class="text-center">Reference Type</th>
                                                            <th colspan="2"  class="text-center">Cheque</th>
                                                            <th rowspan="2" class="text-center">Debit</th>
                                                            <th rowspan="2" class="text-center">Credit</th>
                                                            <th rowspan="2" class="text-center">Balance</th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center">Type</th>
                                                            <th class="text-center">No.</th>
                                                            <th class="text-center">Type</th>
                                                            <th class="text-center">No.</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                <?php //TODO
                                                if(isset($societyLedgerData) && count($societyLedgerData) > 0){
                                                $idCount = 1;
                                                $paymentTotalAmount = 0;
                                                foreach($societyLedgerData as $paymentDate => $bankBookInfo){
                                                  foreach($bankBookInfo as $flagType => $bookData){ 
                                                        if(isset($bookData) && count($bookData) > 0){
                                                        foreach($bookData as $finalData){    
                                                    $paymentTotalAmount += isset($paymentData['SocietyPayment']['amount']) && !empty($paymentData['SocietyPayment']['amount']) ? $paymentData['SocietyPayment']['amount'] * 1 : 0.00;
                                                 ?>
                                                        <tr id="">
                                                            <td><?php echo isset($finalData['payment_date']) ? $finalData['payment_date'] : '';  ?></td>
                                                            <td><?php echo isset($finalData['particulars']) ? $finalData['particulars'] :'';  ?></td>
                                                            <td class="text-center"><?php echo $idCount;  ?></td>
                                                            <td><?php echo isset($finalData['payment_type']) ? $finalData['payment_type'] :'';  ?></td>
                                                            <td><?php echo isset($finalData['cheque_number']) ? $finalData['cheque_number'] :'-';  ?></td>
                                                            <td class="text-right"><?php echo isset($finalData['deposit']) ? $finalData['deposit'] :'';  ?></td>
                                                            <td class="text-right"><?php echo isset($finalData['withdrawal']) ? $finalData['withdrawal'] :'';  ?></td>
                                                            <td class="text-right"><?php echo isset($finalData['deposit']) ? $finalData['deposit'] :'';  ?></td>
                                                        </tr>
                                                <?php $idCount++; } } } } }else{?>
                                                        <tr><td colspan="8"><center>Payment has not made for selected bank. </center></td></tr>
                                                <?php }?>
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