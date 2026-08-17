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
                                            <select  class="form-control" id="receipttype" name="data[MemberPayment][reciept_type]">
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
                                    <div class="col-md-6 padding-0">
                                        <div class="form-group ma-20">  
                                            <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm" ><i class="icon-rocket"></i><span class="btn-text">Load Payment/Receipt</span></button>&nbsp;<button id="btnreconciliation" type="button" data-toggle="tooltip" data-original-title="" class="btn btn-success  btn-sm" ><i class="icon-rocket"></i><span class="btn-text"> Reconciliation</span></button>&nbsp;<button id="btnreconciliationdetail" type="button" data-toggle="tooltip" data-original-title="" class="btn btn-success  btn-sm" ><i class="icon-rocket"></i><span class="btn-text"> Detail Reconciliation</span></button>    
                                                                                                                        
                                        </div>
                                    </div>
                                </form>    
                                <div class="clearfix"></div>
                                
                                <form class="" method="post" id="updateChequeClearDateFrm" name="updateChequeClearDateFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                    <input type="hidden" name="update_type" id="update_type" value="<?php if(isset($postData['MemberPayment']['reciept_type'])) { echo $postData['MemberPayment']['reciept_type'];}else{echo '';}?>" />
                                    <div class="row" style="margin:15px 0;">
                                        <div class="col-md-2 pull-right" style="text-align:right;">
                                            <button type="button" data-toggle="tooltip" data-original-title="" class="btn btn-warning btn-sm" onclick="submitChequeClearForm()" style="margin-top:22px;"><i class="icon-rocket"></i><span class="btn-text"> Update Clear Date</span></button>
                                        </div>
                                        <div class="col-md-3 pull-right">
                                            <label class="control-label" style="color:#fff;background:#673ab7;padding:3px 8px;border-radius:3px;display:block;font-size:12px;">All Clear Date</label>
                                            <div class="input-group input-group-sm">
                                                <input type="date" class="form-control input-sm" id="all_clear_date" style="border:2px solid #673ab7;">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-sm" style="background:#673ab7;color:#fff;border:2px solid #673ab7;" onclick="applyAllClearDate();">Apply</button>
                                                    <button type="button" class="btn btn-sm" style="background:#e91e63;color:#fff;border:2px solid #e91e63;" onclick="applyAllUnclearDate();">Unclear</button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 pull-right">
                                            <label class="control-label" style="color:#fff;background:#e91e63;padding:3px 8px;border-radius:3px;display:block;font-size:12px;">Search By Amount</label>
                                            <input type="text" class="form-control input-sm" id="amount_filter" placeholder="Amount..." onkeyup="debounceFilter();" style="border:2px solid #e91e63;">
                                        </div>
                                        <div class="col-md-2 pull-right">
                                            <label class="control-label" style="color:#fff;background:#009688;padding:3px 8px;border-radius:3px;display:block;font-size:12px;">Search By Cheque No</label>
                                            <input type="text" class="form-control input-sm" id="cheque_no_filter" placeholder="Cheque No..." onkeyup="debounceFilter();" style="border:2px solid #009688;">
                                        </div>
                                    </div>
                                    <div class="table-wrap">
                                        <div class="table-responsive1">
                                            <table class="table table-striped table-bordered mb-0 padding-td-none padding-th-none" id="cheque_clear_table">
                                                <thead>
                                                    <tr>
                                                        <th>Flat No.</th>
                                                        <th>Voucher No.</th>
                                                        <th>Date</th>
                                                        <th>Cheque No.</th>
                                                        <th>Cheque Date</th>
                                                        <th>Cleared Date</th>
                                                        <th>Deposit</th>
                                                        <th>Withdraw</th>
                                                        <th style="width:40px;text-align:center;"><input type="checkbox" id="select_all" onclick="toggleSelectAll(this);"></th>
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
                                                        <tr id="" class="data-row">
                                                            <td><?php echo isset($finalData['flat_no']) ? $finalData['flat_no'] : '';  ?></td>
                                                            <td><?php echo isset($finalData['voucher_no']) ? $finalData['voucher_no'] : '';  ?></td>
                                                            <td><?php echo isset($finalData['payment_date']) ? $utilObj->getFormatDate($finalData['payment_date'],'d-m-Y') :'';  ?></td>
                                                            <td><input type="text" class="form-control input-sm" name="data[ClearChequePayment][<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>][cheque_number]" id="cheque_number_<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>" value="<?php echo isset($finalData['cheque_number']) ? $finalData['cheque_number'] :'';  ?>" style="width:100px;"></td>
                                                            <td><input type="date" class="form-control" name="data[ClearChequePayment][<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>][cheque_date]" id="cheque_date_<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>" value="<?php echo (isset($finalData['cheque_date']) && $finalData['cheque_date'] != '0000-00-00') ? $utilObj->getFormatDate($finalData['cheque_date']) :'';  ?>" ></td>
                                                            <td><input type="date" class="form-control" name="data[ClearChequePayment][<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>][cleared_date]" id="cleared_date_<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>" value="<?php echo (isset($finalData['cleared_date']) && $finalData['cleared_date'] != '0000-00-00') ? $utilObj->getFormatDate($finalData['cleared_date']) :'' ; ?>" ></td>
                                                            <td><?php echo isset($finalData['deposit']) ? $finalData['deposit'] :'';  ?></td>
                                                            <td><?php echo isset($finalData['withdrawal']) ? $finalData['withdrawal'] :'';  ?></td>
                                                            <td style="text-align:center;"><input type="checkbox" class="row-checkbox" data-id="<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>" onclick="toggleRowCheckbox();"></td>
                                                        </tr>
                                                        <?php $idCount++; } ?>
                                                        <tr id="">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td style="font-weight:bold;">Total</td>
                                                            <td style="font-weight:bold;"><?php echo number_format($totalDeposit);  ?></td>
                                                            <td style="font-weight:bold;"><?php echo number_format($totalWithdraw);  ?></td>
                                                            <td></td>
                                                        </tr>
                                                        <?php } else {?>
                                                        <tr><td colspan="9"><center>Payment has not made for selected bank. </center></td></tr>
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
        document.getElementById("btnreconciliationdetail").onclick = submitReconciliationDetail;
    };

    function toggleSelectAll(source) {
        var checkboxes = document.querySelectorAll('.row-checkbox');
        for (var i = 0; i < checkboxes.length; i++) {
            var row = checkboxes[i].closest('tr');
            if (row && row.style.display !== 'none') {
                checkboxes[i].checked = source.checked;
            }
        }
    }

    function toggleRowCheckbox() {
        var total = document.querySelectorAll('.row-checkbox').length;
        var checked = document.querySelectorAll('.row-checkbox:checked').length;
        document.getElementById('select_all').checked = (total > 0 && total === checked);
    }

    function applyAllClearDate() {
        var allDate = document.getElementById('all_clear_date').value;
        if (allDate === '') {
            alert('Please select a date first');
            return;
        }
        var checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Please select at least one entry');
            return;
        }
        for (var i = 0; i < checkedBoxes.length; i++) {
            var id = checkedBoxes[i].getAttribute('data-id');
            var clearedInput = document.getElementById('cleared_date_' + id);
            if (clearedInput) {
                clearedInput.value = allDate;
            }
        }
    }

    function applyAllUnclearDate() {
        var checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Please select at least one entry');
            return;
        }
        for (var i = 0; i < checkedBoxes.length; i++) {
            var id = checkedBoxes[i].getAttribute('data-id');
            var clearedInput = document.getElementById('cleared_date_' + id);
            if (clearedInput) {
                clearedInput.value = '';
            }
        }
    }

    function submitReconciliation(){
        if (document.getElementById("society_bank_id").value == '') {
            alert("Please select a Bank Name");
            return false;
        } else {
            var society_bank_id = document.getElementById("society_bank_id").value;
            var payment_date = document.getElementById("payment_date").value;
            var payment_date_to = document.getElementById("payment_date_to").value;
            var postData = 'society_bank_id='+society_bank_id+'&payment_date='+payment_date+'&payment_date_to='+payment_date_to;
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
    function submitReconciliationDetail(){
        if (document.getElementById("society_bank_id").value == '') {
            alert("Please select a Bank Name");
            return false;
        } else {
            var society_bank_id = document.getElementById("society_bank_id").value;
            var payment_date = document.getElementById("payment_date").value;
            var payment_date_to = document.getElementById("payment_date_to").value;
            var postData = 'society_bank_id='+society_bank_id+'&payment_date='+payment_date+'&payment_date_to='+payment_date_to;
            $.ajax({
                type: "POST",
                url: webrootUrl + 'societys_ajax/bank_reconciliation_detail',
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
        if (document.getElementById("receipttype").value == '') {
            alert("Please select a Type");
            return false;
        } else {
           var receipttype = document.getElementById("receipttype").value;
           $('#update_type').val(receipttype);
        }
        $('[id^=cleared_date_]').each(function () {
            clearedDate = $(this).val();
            if (clearedDate != '') {
                id = $(this).attr('id');
                sid = id.split('cleared_date_');
                cheque_id = 'cheque_date_'+sid[1];
                chequeDate = $('#'+cheque_id).val();
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
    var filterTimer = null;
    function debounceFilter(){
        if(filterTimer) clearTimeout(filterTimer);
        filterTimer = setTimeout(function(){ filterTable(); }, 300);
    }
    function filterTable(){
        var chequeNo = document.getElementById('cheque_no_filter').value.trim().toLowerCase();
        var amount = document.getElementById('amount_filter').value.trim();
        var rows = document.querySelectorAll('#cheque_clear_table tbody tr.data-row');
        for(var i = 0; i < rows.length; i++){
            var cells = rows[i].cells;
            var chequeInput = cells[3].querySelector('input');
            var rowChequeNo = chequeInput ? chequeInput.value.trim().toLowerCase() : '';
            var rowDeposit = cells[6].textContent.trim();
            var rowWithdraw = cells[7].textContent.trim();
            var chequeMatch = (chequeNo === '' || rowChequeNo.indexOf(chequeNo) !== -1);
            var amountMatch = true;
            if(amount !== ''){
                amountMatch = (rowDeposit.indexOf(amount) !== -1 || rowWithdraw.indexOf(amount) !== -1);
            }
            rows[i].style.display = (chequeMatch && amountMatch) ? '' : 'none';
        }
    }
</script>