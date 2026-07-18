<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Cheque Clear Dates</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <div class="row">
                                <form class="" method="post" id="chequeClearDateFrm" name="chequeClearDateFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                    <div class="col-md-2 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="">Type</label>                                           
                                            <select  class="form-control" id="" name="data[MemberPayment][reciept_type]">
                                                <option value="">Select Type</option>    
                                                <option value="Receipt" <?php if(isset($postData['MemberPayment']['reciept_type']) && $postData['MemberPayment']['reciept_type'] == 'Receipt') { echo 'selected';}?>>Receipt</option>
                                                <option value="Payment" <?php if(isset($postData['MemberPayment']['reciept_type']) && $postData['MemberPayment']['reciept_type'] == 'Payment'){echo 'selected';}?>>Payment</option>
                                                <option value="General" <?php if(isset($postData['MemberPayment']['reciept_type']) && $postData['MemberPayment']['reciept_type'] == 'General'){echo 'selected';}?>>General Receipt</option>
                                            </select>                                        
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="title">Bank Name</label>
                                            <select class="form-control" id="society_bank_id" name="data[MemberPayment][society_bank_id]" >
                                                <option value="">Select Bank</option>
                                               <?php
                                                if (isset($societyBankLists) && count($societyBankLists) > 0) :
                                                $s = 1;
                                                foreach ($societyBankLists as $societyBankId => $societyBankName) {
                                                    if (isset($this->request->data['MemberPayment']['society_bank_id']) && $this->request->data['MemberPayment']['society_bank_id'] == $societyBankId) {
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
                                    <div class="col-md-1 padding-0"> 
                                        <label class="control-label" for="slip_no">Slip No</label>
                                        <input type="text" class="form-control" id="bill_no" name="data[MemberPayment][bill_no]" value="<?php echo isset($postData['MemberPayment']['bill_no']) ? $postData['MemberPayment']['bill_no'] :'' ; ?>">                                        
                                    </div> 
                                    <div class="col-md-1 padding-0"> 
                                        <label class="control-label" for="cheque_no">Cheque No</label>
                                        <input type="text" class="form-control" id="cheque_no" name="data[MemberPayment][cheque_no]" value="<?php echo isset($postData['MemberPayment']['cheque_no']) ? $postData['MemberPayment']['cheque_no'] :'' ; ?>">                                        
                                    </div> 
                                   <div class="col-md-2 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="">From</label>
                                          <input type="date" class="form-control"name="data[MemberPayment][payment_date]" id="payment_date" value="<?php echo isset($postData['MemberPayment']['payment_date']) ? $postData['MemberPayment']['payment_date'] :'' ; ?>">                                                                     
                                        </div>
                                    </div>
                                   <div class="col-md-2 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="">To</label>
                                           <input type="date" class="form-control"name="data[MemberPayment][payment_date_to]" id="payment_date_to" value="<?php echo isset($postData['MemberPayment']['payment_date_to']) ? $postData['MemberPayment']['payment_date_to'] :'' ; ?>">                                     
                                        </div>
                                    </div>
                                   <div class="col-md-2 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="">Clear Date</label>
                                            <input type="date" class="form-control"name="data[MemberPayment][clear_date]" value="<?php echo isset($postData['MemberPayment']['clear_date']) ? $postData['MemberPayment']['clear_date'] :'' ; ?>">                                     
                                        </div>
                                    </div>
                                   
                                   <div class="col-md-1 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="">Status</label>                                           
                                            <select class="form-control" id="" name="data[MemberPayment][status]">
                                                <option value="">Select</option>
                                                <option value="Cleared" <?php if(isset($postData['MemberPayment']['status']) && $postData['MemberPayment']['status'] == 'Cleared') { echo 'selected';}?>>Cleared</option>
                                                <option value="Uncleared" <?php if(isset($postData['MemberPayment']['status']) && $postData['MemberPayment']['status'] == 'Uncleared'){echo 'selected';}?>>Uncleared</option>                                                                                            
                                            </select>                                        
                                        </div>
                                    </div>
                                    <div class="col-md-4 padding-0">
                                        <div class="form-group ma-20">  
                                            <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm" ><i class="icon-rocket"></i><span class="btn-text">Load Payment/Receipt</span></button>&nbsp;<button id="btnreconciliation" type="button" data-toggle="tooltip" data-original-title="" class="btn btn-success  btn-sm" ><i class="icon-rocket"></i><span class="btn-text"> Reconciliation</span></button>     
                                                                                                                        
                                        </div>
                                    </div>
                                </form>    
                                <div class="clearfix"></div>
                                
                                <form class="" method="post" id="updateChequeClearDateFrm" name="updateChequeClearDateFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                    <div class="col-md-2 ma-15 pull-right">
                                        <input type="hidden" name="update_type" id="update_type" value="<?php if(isset($postData['MemberPayment']['reciept_type'])) { echo $postData['MemberPayment']['reciept_type'];}else{echo '';}?>" />
                                        <button type="button" data-toggle="tooltip" data-original-title="" class="btn btn-warning  btn-sm <?php if(isset($postData['MemberPayment']['reciept_type'])) { echo '';}else{echo 'disabled';}?> " onclick="submitChequeClearForm()"><i class="icon-rocket"></i><span class="btn-text"> Update Clear Date</span></button>
                                    </div>
                                    <br>
                                    <div class="table-wrap">
                                        <div class="table-responsive1">
                                            <table class="table table-striped table-bordered mb-0 padding-td-none padding-th-none">
                                                <thead>
                                                    <tr>
                                                        <th>Voucher No.</th>
                                                        <th>Date</th>
                                                        <th>Cheque No.</th>
                                                        <th>Cheque Date</th>
                                                        <th>Cleared Date</th>
                                                        <th>Deposit</th>
                                                        <th>Withdraw</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php //TODO
                                                        if(isset($cashBookData) && count($cashBookData) > 0){
                                                        $idCount = 1;
                                                        $paymentTotalAmount = 0;
                                                        $totalDeposit = 0;
                                                        $totalWithdraw = 0;
                                                        
                                                        foreach($cashBookData as $paymentDate => $finalData){    
                                                            $paymentTotalAmount += isset($paymentData['SocietyPayment']['amount']) && !empty($paymentData['SocietyPayment']['amount']) ? $paymentData['SocietyPayment']['amount'] * 1 : 0.00;
                                                            $totalDeposit +=$finalData['deposit'];
                                                            $totalWithdraw +=$finalData['withdrawal'];
                                                         ?>
                                                        <tr id="">
                                                            <td><?php echo isset($finalData['voucher_no']) ? $finalData['voucher_no'] : '';  ?></td>
                                                            <td><?php echo isset($finalData['payment_date']) ? $utilObj->getFormatDate($finalData['payment_date'],'d-m-Y') :'';  ?></td>
                                                            <td><?php echo isset($finalData['cheque_number']) ? $finalData['cheque_number'] :'';  ?></td>
                                                            <td><?php echo isset($finalData['cheque_date']) ? $utilObj->getFormatDate($finalData['cheque_date'],'d-m-Y') :'';  ?></td>
                                                            <td><input type="date" class="form-control" name="data[ClearChequePayment][<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>][cleared_date]" id="cleared_date_<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>" value="<?php echo (isset($finalData['cleared_date']) && $finalData['cleared_date'] != '0000-00-00') ? $utilObj->getFormatDate($finalData['cleared_date']) :'' ; ?>" ><input type="hidden" class="form-control" name="data[ClearChequePayment][<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>][cheque_date]" id="cheque_date_<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>" value="<?php echo isset($finalData['cheque_date']) ? $finalData['cheque_date'] :'';  ?>" ></td>
                                                            <td><?php echo isset($finalData['deposit']) ? $finalData['deposit'] :'';  ?></td>
                                                            <td><?php echo isset($finalData['withdrawal']) ? $finalData['withdrawal'] :'';  ?></td>
                                                        </tr>
                                                        <?php $idCount++; } ?>
                                                        <tr id="">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td style="font-weight:bold;">Total</td>
                                                            <td style="font-weight:bold;"><?php echo number_format($totalDeposit);  ?></td>
                                                            <td style="font-weight:bold;"><?php echo number_format($totalWithdraw);  ?></td>
                                                        </tr>
                                                        <?php } else {?>
                                                        <tr><td colspan="8"><center>Payment has not made for selected bank. </center></td></tr>
                                                        <?php }?>
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
<div class="modal fade" id="reconciliationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Bank Reconciliation&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title=""  class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printReconcile('print_bank_reconciliation');"><i class="fa fa-print"></i><span class="btn-text"> Print </span></a></h5>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="reconciliationModalBody">
        ...
      </div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>-->
    </div>
  </div>
</div>
<script>
    window.onload = function () {
        document.getElementById("btnreconciliation").onclick = submitReconciliation;
    };
    function submitReconciliation(){    
        if (document.getElementById("society_bank_id").value == '') {
            alert("Please select a Bank Name");
            return false;
        } else {
            var society_bank_id = document.getElementById("society_bank_id").value;
            var payment_date = document.getElementById("payment_date").value;
            var payment_date_to = document.getElementById("payment_date_to").value;
            var postData = 'society_bank_id='+society_bank_id+'&payment_date='+payment_date+'&payment_date_to='+payment_date_to;
            //$('#reconciliationModalBody').html(postData);
            //            $('#reconciliationModal').modal('show');
            $.ajax({
                type: "POST",
                url: webrootUrl + 'societys_ajax/bank_reconciliation',
                data: postData,
                dataType: 'html',
                success: function (resp) {
                    hideLoader();
                    if (resp) {
                        $('#reconciliationModalBody').html(resp);
                        $('#reconciliationModal').modal('show');
                    }
                }
            });
        }
    }
    function submitChequeClearForm(){
        var chk = true;
        $('[id^=cleared_date_]').each(function () {
            clearedDate = $(this).val();
            if (clearedDate != '') {
                id = $(this).attr('id');
                sid = id.split('cleared_date_');
                cheque_id = 'cheque_date_'+sid[1];
                chequeDate = $('#'+cheque_id).val();
                //alert(chequeDate);alert(clearedDate);
                var x = new Date(chequeDate);
                var y = new Date(clearedDate);
                if (x.getTime() > y.getTime()) {
                    chk = false;
                }
            }
        });
        if(chk) {
        $.ajax({
            type: "POST",
            url: webrootUrl + 'societys_ajax/updateChequeClearedDates',
            data: $('#updateChequeClearDateFrm').serialize(),
            dataType: 'json',
            success: function (jsonData) {
                hideLoader();
                if (jsonData.error == 1) {
                    alert(jsonData.error_message);
                    return false;
                } else {
                    alert(jsonData.error_message);
                    return false;
                }
            }
        });
        }else{
            alert("Please ensure Cleared date should be greater than Cheque date");
            return false;
    }
    }
    function printReconcile(el){           
        $('#reconciliationModal').modal('hide');
        var restorepage = document.body.innerHTML;
        var printAllReports =  document.getElementById(el).innerHTML;
        document.body.innerHTML = printAllReports;
        window.print();
        document.body.innerHTML = restorepage;
         
        window.location.reload();
    }
</script>