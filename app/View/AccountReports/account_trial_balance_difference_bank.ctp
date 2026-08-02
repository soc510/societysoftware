<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="col-sm-6">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Bank/Cash Difference</h6>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="pull-right">
                            <?php /* Same unclosed-anchor bug as the member-difference page: </h6>
                                     for </a> left the link wrapping the filter form. */ ?>
                            <a href="?t=0">Member Bill Difference</a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                              
                            <div class="clearfix"></div>
                            <br><br><br>
                            <div id="print_account_cah_book">
                                <div class="print-cash-book">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="row1">
                                        <div class="report-bill"></div>
                                        <div class="report-bill-outer-section">
                                            <div class="table-wrap1">
                                                <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                    <thead>
                                                        <tr>
                                                            <th colspan="3" class="text-center">Voucher</th>
                                                            <th rowspan="2" class="text-center">Particular</th>
                                                            <th   rowspan="2" class="text-center">DrAmount</th>
                                                            <th   rowspan="2" class="text-center">CrAmount</th>
                                                            <th rowspan="2" class="text-center">Difference</th>
                                                            
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center">Ledger Name</th>
                                                            <th  class="text-center">Date</th>
                                                            <th  class="text-center">No</th>
                                                        </tr>

                                                    </thead>
                                                    <tbody>
                                                    <?php 
                                                    $balancePerRow = 0;
                                                    $totalDeposit = 0;
                                                    $totalWithdraw = 0;
                                                    //echo '<pre>';print_r($bookData);
                                                    ?>
                                                    <?php //TODO
                                                    foreach ($bookData as $lname => $cashBookData){
                                                        
                                                    
                                                        if(isset($cashBookData) && count($cashBookData) > 0){
                                                        $idCount = 1;
                                                        foreach($cashBookData as $paymentDate => $bankBookInfo){
                                                            foreach($bankBookInfo as $flagType => $bookData){

                                                                if(isset($bookData) && count($bookData) > 0){
                                                                foreach($bookData as $finalData){  
                                                                    
                                                                    
                                                         ?>
                                                        <tr id="" >
                                                            <?php 
                                                                $totalBal = $totalDeposit - abs($totalWithdraw);
                                                                //echo $totalBal;
                                                                if($totalBal < 0) {
                                                                    $posNeg = 'Cr';
                                                                } else { $posNeg = 'Dr';}                                                               
                                                                $linkParticular  = isset($finalData['particulars']) ? $finalData['particulars'] :'';;
                                                                if (isset($finalData['link'])){
                                                                        $linkParticular = '<a target="_blank" href="'.$finalData['link'].'">'.$linkParticular.'</a>';
                                                                }
                                                            ?>
                                                            <td class="text-center"><?php echo $lname;  ?></td>
                                                            <td><?php echo isset($finalData['payment_date']) ? $utilObj->getFormatDate($finalData['payment_date'],'d/m/Y') : '';  ?></td>
                                                            
                                                            <td class="text-center"><?php echo (isset($finalData['voucher_no']) && $finalData['voucher_no'] !== '' && $finalData['voucher_no'] !== null) ? $finalData['voucher_no'] : $idCount;  ?></td>
                                                            <td><?php echo $linkParticular;  ?></td>
                                                            <td class="text-right"><?php  if (!is_array($finalData['deposit'])){  echo $finalData['deposit']; $dramt = $finalData['deposit'];}else { $dramt =0; ?>
                                                                <table class="table table-bordered padding-td-none padding-th-none">
                                                                    <tr>
                                                                        <th class="text-center">Ledger Name</th>
                                                                        <th class="text-center">Particular</th>
                                                                        <th class="text-center">Amount</th>
                                                                    </tr>
                                                                    <?php foreach ($finalData['deposit'] as $arr){ ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo  $arr['ledger_head'];?></td>
                                                                        <td class="text-center"><?php echo  $arr['particular'];?></td>
                                                                        <td class="text-center"><?php $dramt += $arr['amount']; echo  $arr['amount'];?></td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                </table>
                                                            <?php } ?>
                                                            </td>
                                                            <td class="text-right"><?php if (!is_array($finalData['withdrawal'])){ $cramt = $finalData['withdrawal'];  echo $finalData['withdrawal']; }else { $cramt = 0; ?>
                                                                <table class="table table-bordered padding-td-none padding-th-none">
                                                                    <tr>
                                                                        <th class="text-center">Ledger Name</th>
                                                                        <th class="text-center">Particular</th>
                                                                        <th class="text-center">Amount</th>
                                                                    </tr>
                                                                    <?php foreach ($finalData['withdrawal'] as $arr){?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo  $arr['ledger_head'];?></td>
                                                                        <td class="text-center"><?php echo  $arr['particular'];?></td>
                                                                        <td class="text-center"><?php $cramt += $arr['amount']; echo  $arr['amount'];?></td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                </table>
                                                            <?php } ?>
                                                            </td>
                                                            <td><?php echo $cramt - $dramt; ?></td>
                                                            
                                                        </tr>
                                                        <?php $idCount++; } } } } ?> 
                                                                
                                                        <?php } ?>
                                                        <?php } ?>
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