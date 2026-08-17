<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Bank Book</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <div class="col-md-12 left-section"> 
                                <form class="" method="post" id="bankBookFrm" name="bankBookFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                    <div class="row">
                                        <div class="col-md-5 no-padding-right">  
                                            <div class="account-report-form-field-box">  
                                                <div class="account-report-form-box-label">Bank Name &nbsp;</div>                                                                     
                                                <select class="account-report-form-box-field-dropdown-80" id="" name="data[BankBook][society_bank_id]" required="">
                                                    <option value="">Select Bank</option>
                                                            <?php
                                                            if (isset($societyBankLists) && count($societyBankLists) > 0) :
                                                            $s = 1;
                                                            foreach ($societyBankLists as $societyBankId => $societyBankName) {
                                                            if (isset($postData['BankBook']['society_bank_id']) && $postData['BankBook']['society_bank_id'] == $societyBankId) {
                                                            ?>
                                                    <option value="<?php echo $societyBankId; ?>" selected=""><?php echo $societyBankName; ?></option>
                                                                    <?php } else { ?>
                                                    <option value="<?php echo $societyBankId; ?>"><?php echo $societyBankName; ?></option>
                                                                <?php
                                                                $s++;
                                                                    }
                                                                } endif;
                                                            ?>  
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">   
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">For the Period &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[BankBook][payment_date]" value="<?php echo isset($postData['BankBook']['payment_date']) ? $postData['BankBook']['payment_date'] :'' ; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[BankBook][payment_date_to]" value="<?php echo isset($postData['BankBook']['payment_date_to']) ? $postData['BankBook']['payment_date_to'] :'' ; ?>">
                                            </div>  
                                        </div>                                                            
                                    </div>  
                                    <div class="row">
                                        <div class="col-md-3 nopadding">    
                                            <div class="account-report-form-field-box">  
                                                <div class="account-report-form-box-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Type &nbsp;&nbsp;&nbsp;&nbsp;</div>                                                                         
                                                <select class="account-report-form-box-field-dropdown" id="" name="data[BankBook][report_type]">
                                                    <option value="">select type</option>
                                                    <option value="Deposit" <?php if(isset($postData['BankBook']['report_type']) && $postData['BankBook']['report_type'] == 'Deposit') { echo 'selected';}?>>Deposit</option>
                                                    <option value="Withdrawal" <?php if(isset($postData['BankBook']['report_type']) && $postData['BankBook']['report_type'] == 'Withdrawal') { echo 'selected';}?>>Withdrawal</option>
                                                    <option value="Contra" <?php if(isset($postData['BankBook']['report_type']) && $postData['BankBook']['report_type'] == 'Contra') { echo 'selected';}?>>Contra</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 nopadding">   
                                            <div class="account-report-form-field-box">                               
                                                <div class="account-report-form-box-label">Operator &nbsp;&nbsp;</div>                                                                     
                                                <select class="account-report-form-box-field-dropdown" id="" name="data[BankBook][operator]">
                                                    <option value="">Select Operator</option>   
                                                <?php //if(isset($postData['BankBook']['operator']) && $postData['BankBook']['operator'] ==''){?>
                                                    <option value=">" <?php if(isset($postData['BankBook']['operator']) && $postData['BankBook']['operator'] == '>') { echo 'selected';}?>>></option>
                                                    <option value="<" <?php if(isset($postData['BankBook']['operator']) && $postData['BankBook']['operator'] == '<') { echo 'selected';}?>><</option>
                                                    <option value=">=" <?php if(isset($postData['BankBook']['operator']) && $postData['BankBook']['operator'] == '>=') { echo 'selected';}?>>>=</option>
                                                    <option value="<=" <?php if(isset($postData['BankBook']['operator']) && $postData['BankBook']['operator'] == '<=') { echo 'selected';}?>><=</option>
                                                    <option value="=" <?php if(isset($postData['BankBook']['operator']) && $postData['BankBook']['operator'] == '=') { echo 'selected';}?>>=</option>
                                                    <option value="<>" <?php if(isset($postData['BankBook']['operator']) && $postData['BankBook']['operator'] == '<>') { echo 'selected';}?>><></option>
                                                <?php //}?>
                                                </select>
                                            </div>    
                                        </div>   
                                        <div class="col-md-3 nopadding"> 
                                            <div class="account-report-form-field-box">                                 
                                                <div class="account-report-form-box-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Amount &nbsp;&nbsp;</div>                                                                     
                                                <input type="text" class="account-report-form-box-field-60 text-right" id="" name="data[BankBook][amount]" value="<?php echo isset($postData['BankBook']['amount']) ? $postData['BankBook']['amount'] :'' ; ?>">
                                            </div>   
                                        </div>                                     
                                        <div class="col-md-4 account-report-form"> 
                                            <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>      
                                            <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printAllReports('print_account_bank_book');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                            <?php /* onclick was empty - the button rendered but did nothing. Writes the
                                                     file directly, off the table rendered below, so it carries whatever
                                                     bank and date range is on screen. */ ?>
                                            <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="export_bank_book_pdf();"><i class="fa fa-file-pdf-o"></i><span class="btn-text">Export to PDF</span></a>
                                            <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
<?php /* Was a link to excels_report/download_bank_book_sheet, a separate
         server-side query that produced different figures from the screen
         (its own opening balance, and raw <br> tags in the particulars).
         Now exports the table rendered below, so Excel matches print and PDF. */ ?>
                                            <a data-toggle="tooltip" data-original-title="Export to Excel" class="btn btn-success btns" href="javascript:void(0);" onclick="export_bank_book_excel();"><i class="fa fa-download" style="color:#fff;font-size:10px;"></i> Export to Excel</a>
                                        </div>   
                                    </div>    
                                    <div class="row"> 
                                    </div>  
                                </form>  
                            </div> 
                            <!-- <div class="col-md-4 right-section">
                                 <div class="row">   
                                     <div class="right-title">Select (if required) </div> 
                                     <div class="col-md-12">
                                         <div class="account-report-form-field-box">
                                             <div class="account-report-form-box-label">Building &nbsp;</div>                                    
                                              <select class="account-report-form-box-field-dropdown" id="" name="" required="">
                                                 <option value="">Select Building</option>  
                                             </select> 
                                         </div>
                                     </div>  
                                     <div class="col-md-12">
                                         <div class="account-report-form-field-box">
                                             <div class="account-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                              <select class="account-report-form-box-field-dropdown" id="" name="" required="">
                                                 <option value="">Select Wing</option>  
                                             </select> 
                                         </div>
                                     </div> 
                                     <div class="clearfix"></div>
                                     <div class="col-md-6">
                                         <div class="account-report-form-field-box">
                                             <div class="account-report-form-box-label">Unit No &nbsp;&nbsp;</div>                                   
                                             <input type="text" class="account-report-form-box-field-50" id="" name="" value="">
                                         </div>
                                     </div>  
                                     <div class="col-md-6">
                                         <div class="account-report-form-field-box">
                                             <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                             <input type="text" class="account-report-form-box-field-50" id="" name="" value="">
                                         </div>
                                     </div> 
                                     <div class="clearfix"></div><br>
                                 </div>    
                             </div>    -->     

                            <div class="clearfix"></div>
                            <br><br><br>
                            <div id="print_account_bank_book">
                                <div class="print-bank-book">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="row1">
                                        <div class="report-bill">Bank Book of</div>
                                        <div class="report-bill"><?php echo isset($postData['BankBook']['society_bank_id']) ? $societyBankLists[$postData['BankBook']['society_bank_id']] : '';?></div>                                                             
                                        <div class="report-bill-outer-section">
                                            <div class="table-wrap1">
                                                <table id="" class="table table-bordered padding-td-none padding-th-none">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="3" class="text-center">Voucher</th>
                                                            <th rowspan="2" class="text-center">Particular</th>
                                                            <th style="width:5%;" rowspan="2" class="text-center">Cheque No</th>
                                                            <th style="width:10%;" rowspan="2" class="text-center">Deposit</th>
                                                            <th style="width:10%;" rowspan="2" class="text-center">Withdrawal</th>
                                                            <th style="width:10%;" rowspan="2" class="text-center">Balance </th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center">Date</th>
                                                            <th class="text-center">Type</th>
                                                            <th class="text-center">No</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php 
                                                        $balancePerRow = 0;
                                                        $totalDeposit = 0;
                                                        $totalWithdraw = 0;
                                                        // Carried separately from $totalDeposit: the opening balance belongs in
                                                        // the running balance, but adding it into $totalDeposit made the Total
                                                        // Deposit column report opening + deposits instead of the deposits.
                                                        $openingBal = 0;

                                                        if(isset($ledgerHeadDetails) && !empty($openingBalance)) {
                                                            $balancePerRow = $openingBalance;
                                                            $openingBal = $openingBalance;

                                                            if($openingBalance < 0) {
                                                                   $posNegSym = 'Cr';
                                                                } else { $posNegSym = 'Dr';}
                                                        ?>
                                                        <tr id="">
                                                            <?php /* Dated to the last transaction before this period, so the opening
                                                                     reads as "balance as it stood then" rather than the year start.
                                                                     Falls back to the year start when the opening is a carried-forward
                                                                     amount with no transaction behind it. */ ?>
                                                            <td><?php echo !empty($openingBalanceAsOfDate) ? $utilObj->getFormatDate($openingBalanceAsOfDate,'d/m/Y') : $this->Session->read('Auth.formated_year_start_date'); ?></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>Opening Balance</td>
                                                            <td></td>
                                                            <td class="text-right">0.00</td>
                                                            <td class="text-right">0.00</td>
                                                            <td class="text-right"><?php echo isset($openingBalance) ? number_format($openingBalance,2) :0;  ?><?php echo $posNegSym?></td>
                                                        </tr>
                                                        <?php } ?>        
                                                        <?php //TODO

                                                            if(isset($bankBookData) && count($bankBookData) > 0){
                                                            $idCount = 1;                                                
                                                            foreach($bankBookData as $paymentDate => $bankBookInfo){
                                                                foreach($bankBookInfo as $flagType => $bookData){

                                                                    if(isset($bookData) && count($bookData) > 0){
                                                                    foreach($bookData as $finalData){    

                                                                   $totalDeposit += $finalData['deposit'];
                                                                   $totalWithdraw += $finalData['withdrawal'];

                                                                   $balancePerRow = $balancePerRow + ($finalData['deposit']-$finalData['withdrawal']);
                                                             ?>
                                                        <tr id="" <?php if (isset($finalData['ledgerexists']) && $finalData['ledgerexists'] == 0){?>style=""<?php } ?>>
                                                            <?php 
                                                                $totalBalancePerRow = $openingBal + $totalDeposit - abs($totalWithdraw);
                                                                //echo $totalBal;
                                                                if($totalBalancePerRow < 0) {
                                                                   $posNeg = 'Cr';
                                                                } else { $posNeg = 'Dr';}
                                                                 $title  = isset($finalData['title']) ? $finalData['title'] :'';
                                                                $linkParticular  = isset($finalData['particulars']) ? $finalData['particulars'] :'';
                                                                // if (isset($finalData['link'])){
                                                                //         $linkParticular = '<a target="_blank" href="'.$finalData['link'].'">'.$linkParticular.'</a>';
                                                                // }
                                                            ?>
                                                            <td><?php echo isset($finalData['payment_date']) ? $utilObj->getFormatDate($finalData['payment_date'],'d/m/Y') : '';  ?></td>
                                                            <td><?php echo isset($finalData['payment_flag']) ? $finalData['payment_flag'] :'';  ?></td>
                                                            <td><?php echo $idCount;  ?></td>
                                                            <td class="db-part-link" data-href="<?= "https://easylogicstechnology.com".$finalData['link'] ?>"><?= $title ?> <br> <?= $linkParticular ?></a></td>
                                                            <td style="width:5%;" class="text-right"><?php echo isset($finalData['cheque_number']) ? $finalData['cheque_number'] :'';  ?></td>
                                                            <td style="width:10%;" class="text-right"><?php echo isset($finalData['deposit']) ? $finalData['deposit'] :'';  ?></td>
                                                            <td style="width:10%;" class="text-right"><?php echo isset($finalData['withdrawal']) ? $finalData['withdrawal'] :'';  ?></td>
                                                            <td style="width:10%;" class="text-right"><?php echo number_format((float)abs($totalBalancePerRow),2,'.',',').' '.$posNeg;  ?></td>
                                                        </tr>
                                                        <?php $idCount++; } } } } ?>
                                                        <tr id="" style="background-color: #DFDFDF;">
                                                            <?php 
                                                                $totalBal = $openingBal + $totalDeposit - abs($totalWithdraw);
                                                                //echo $totalBal;
                                                                if($totalBal < 0) {
                                                                   $posNeg = 'Cr';
                                                                } else { $posNeg = 'Dr';}
                                                            ?>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right">Total</td>
                                                            <td style="width:10%;" class="text-right"><?php echo number_format($totalDeposit,2,'.',',');  ?></td>
                                                            <td style="width:10%;" class="text-right"><?php echo number_format($totalWithdraw,2,'.',',');  ?></td>
                                                            <td style="width:10%;" class="text-right"><?php echo number_format((float)abs($totalBal),2,'.',',').' '.$posNeg; ?></td>
                                                        </tr>
                                                        <?php }else{?>
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


<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/report_export.js?version=<?php echo Configure::read('FileVersion');?>"></script>
<script type="text/javascript">
   // One plain ledger table rather than the two-column shape the balance sheet
   // and income & expenditure use; the shared exporter handles both.
   function export_bank_book_pdf(){
      reportExport.pdf('print_account_bank_book', 'BankBook.pdf');
   }
   function export_bank_book_excel(){
      reportExport.excel('print_account_bank_book', 'Bank Book', 'BankBook.xls');
   }
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll(".db-part-link").forEach(function(cell) {
    cell.addEventListener("click", function(e) {
      // If user clicks directly on the <a>, let the <a> handle navigation
      if (e.target.tagName.toLowerCase() === "a") {
        return;
      }

      // Get the data-href value
      const url = (cell.getAttribute("data-href") || "").trim();
      console.log("data-href:", url);

      // Validate URL (only allow http/https)
      if (/^https?:\/\//i.test(url)) {
        // Open in a new tab safely
        window.open(url, "_blank", "noopener,noreferrer");
      }
    });
  });
});
</script>
