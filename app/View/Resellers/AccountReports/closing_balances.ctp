<?php 
error_reporting(0);
$NextyearString = ($yearDetails['startYr']+1).'-'.($yearDetails['endYr']+1);
$CurrentyearString = ($yearDetails['startYr']).'-'.($yearDetails['endYr']);
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
                                <div class="report-bill" id="msgDiv"></div>
                                <form method="post" id="frmForward">
                                <input type="hidden" name="societyId" id="societyId" value="<?php echo $societyDetails['Society']['id'];?>">
                                <div class="report-bill">Ledger Head Closing Balances</div>
                                <div class="report-bill">Financial Year <?=$CurrentyearString; ?></div>  
                                <div class="report-bill" style="text-align: right;"><input type="submit" id="btnForwardBalance" value="Forward Balances to <?=$NextyearString; ?>"></div>
                                <div class="report-bill" style="text-align: right;"><input type="button" id="btnForwardMemberBalance" value="Forward Member Balances to <?=$NextyearString;?>"></div>
                                <br>
                                <div class="report-bill-outer-section">
                                    <div class="table-wrap1">
                                        <table id="" class="table table-bordered1 padding-td-none padding-th-none" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Sr</th>    
                                                    <th class="text-center">Head Id</th>
                                                    <th class="text-center">Title</th>
                                                    <th class="text-center">Credit</th>
                                                    <th class="text-center">Debit</th>
                                                    <th class="text-center">Balance</th>
                                                </tr>                                                
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sr=0;
                                            foreach($ledgerClosingBalances as $id=>$details) {
                                                $sr++;
                                                
                                                if($details['closing_balance'] < 0) {
                                                    $flag = 'Dr';
                                                } else {
                                                    $flag = 'Cr';
                                                }
                                                
                                                $details['closing_balance'] = abs($details['closing_balance']);
                                                
                                                echo "<input type=\"hidden\" name=\"ledgerOpening[]\" value=\"ledger@$id@$details[closing_balance]\">";
                                            ?>
                                                <tr><td class="text-center border-top-bottom-bold"><?php echo $sr;?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $id;?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $details['title'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $details['closing_credit'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $details['closing_debit']?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $details['closing_balance'].' '.$flag;;?></td>
                                                </tr> 
                                            <?php } ?>                                                    
                                            </tbody>   
                                        </table> 
                                    </div>
                                </div> 
                                
                                <br>
                                <div class="report-bill">Member Closing Balances</div>
                                <div class="report-bill">Financial Year <?= $CurrentyearString;?></div>  
                                <div class="report-bill-outer-section">
                                    <div class="table-wrap1">
                                        <table id="" class="table table-bordered1 padding-td-none padding-th-none" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Sr</th>    
                                                    <th class="text-center">Member Id</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Principal</th>
                                                    <th class="text-center">Interest</th>
                                                    <th class="text-center">Tax</th>
                                                    <th class="text-center">Total Balance</th>
                                                </tr>                                                
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sr=0;
                                            foreach($memberClosingBalances as $id=>$details) {
                                                $sr++;
                                                
                                                echo "<input type=\"hidden\" name=\"memberOpening[]\" value=\"member@$id@$details[closing]\">";
                                            ?>
                                                <tr>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $sr;?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $id;?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $details['name'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $details['principal'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $details['interest'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $details['tax'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $details['closing'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $details['closing'];?></td>
                                                </tr> 
                                            <?php } ?>                                                    
                                            </tbody>   
                                        </table> 
                                    </div>
                                </div>
                                </form>
                            </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->webroot; ?>js/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(e) {
        
        $("#btnForwardMemberBalance").click(function() {
            // $.post("/closing_member_balance_forward.php", $("#frmForward").serializeArray())
             $.post("transferMemberClosingBalance", $("#frmForward").serializeArray())
            // Serialization looks good: name=textInNameInput&&telefon=textInPhoneInput etc
            .done(function(data) {
                $('#msgDiv').html('<b>'+data+'</b>');
            });

            return false;
        });

        $("#frmForward").submit(function() {
            var result = confirm('Click OK to forward balances');
            if(!result) {
                return false;
            }
            
/*            $.post("/closing_balance_forward.php", $("#frmForward").serializeArray())
            // Serialization looks good: name=textInNameInput&&telefon=textInPhoneInput etc
            .done(function(data) {
                $('#msgDiv').html('<b>'+data+'</b>');
            });*/
            
            $.post("/account_reports/ledger_data_transfer", $("#frmForward").serializeArray())
            // Serialization looks good: name=textInNameInput&&telefon=textInPhoneInput etc
            .done(function(data) {
                $('#msgDiv').html('<b>'+data+'</b>');
            });            

            return false;
        })
    });
</script>

