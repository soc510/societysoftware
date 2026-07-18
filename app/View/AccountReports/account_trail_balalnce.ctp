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
                                <div class="report-bill">Trail Balance</div>
                                <div class="report-bill">As on Date 31/03/2018</div>  
                                <div class="report-bill">Financial Year 2016-2017</div>  
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
                                                <tr>
                                                    <td style="font-weight:bold;" colspan="7">Accounting And Auditing Charges</td>
                                                </tr>
                                                <tr>
                                                    <td>Accounting charges</td>
                                                    <td class="text-right">46,303.00</td>
                                                    <td class="text-right">46,303.00</td>
                                                    <td class="text-right">46,303.00</td>
                                                    <td class="text-right">46,303.00</td>
                                                    <td class="text-right">46,303.00</td>
                                                    <td class="text-right">46,303.00</td>
                                                </tr> 
                                                <tr>
                                                    <td></td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">0.00</td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">0.00</td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">46,303.00</td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">0.00</td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">46,303.00</td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">0.00</td>
                                                </tr> 
                                                <tr>
                                                    <td class="border-top-bold font-weight-bold" colspan="7">Accrued Interest</td>
                                                </tr>
                                                <tr>
                                                    <td>Accrued Interest</td>
                                                    <td class="text-right">22,742.00</td>
                                                    <td class="text-right">46,303.00</td>
                                                    <td class="text-right">46,303.00</td>
                                                    <td class="text-right">46,303.00</td>
                                                    <td class="text-right">46,303.00</td>
                                                    <td class="text-right">46,303.00</td>
                                                </tr> 
                                                <tr>
                                                    <td></td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">22,742.00</td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">0.00</td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">0.00</td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">0.00</td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">22,742.00</td>
                                                    <td style="font-weight:bold;" class="text-right border-top-bottom-bold">0.00</td>
                                                </tr> 
                                            <tfoot id=""> 
                                                <tr>                                                                       
                                                    <td style="font-weight:bold;">Grand Total:</td>
                                                    <td class="text-right" style="font-weight:bold;"> 6,910,225.00</td>
                                                    <td class="text-right" style="font-weight:bold;"></td>
                                                    <td class="text-right" style="font-weight:bold;"> 65,696,964.43</td>
                                                    <td class="text-right" style="font-weight:bold;"></td>
                                                    <td class="text-right" style="font-weight:bold;"> 26,696,152.20</td>
                                                    <td class="text-right" style="font-weight:bold;"></td>
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