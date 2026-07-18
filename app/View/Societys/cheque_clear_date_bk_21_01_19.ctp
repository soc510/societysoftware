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
                                            <label class="control-label" for="title">Bank Name</label>
                                            <select class="form-control" id="society_bank_id" name="data[MemberPayment][society_bank_id]" required>
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
                                   <div class="col-md-2 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="">From</label>
                                          <input type="date" class="form-control"name="data[MemberPayment][payment_date]" value="<?php echo isset($postData['MemberPayment']['payment_date']) ? $postData['MemberPayment']['payment_date'] :'' ; ?>">                                                                     
                                        </div>
                                    </div>
                                   <div class="col-md-2 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="">To</label>
                                           <input type="date" class="form-control"name="data[MemberPayment][payment_date_to]" value="<?php echo isset($postData['MemberPayment']['payment_date_to']) ? $postData['MemberPayment']['payment_date_to'] :'' ; ?>">                                     
                                        </div>
                                    </div>
                                   <div class="col-md-2 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="">Clear Date</label>
                                            <input type="date" class="form-control"name="data[MemberPayment][clear_date]" value="<?php echo isset($postData['MemberPayment']['clear_date']) ? $postData['MemberPayment']['clear_date'] :'' ; ?>">                                     
                                        </div>
                                    </div>
                                   <div class="col-md-2 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="">Type</label>                                           
                                            <select class="form-control" id="" name="data[MemberPayment][reciept_type]">
                                                <option value="">select Type</option>    
                                                <option value="Receipt" <?php if(isset($postData['MemberPayment']['reciept_type']) && $postData['MemberPayment']['reciept_type'] == 'Receipt') { echo 'selected';}?>>Receipt</option>
                                                <option value="Payment" <?php if(isset($postData['MemberPayment']['reciept_type']) && $postData['MemberPayment']['reciept_type'] == 'Payment'){echo 'selected';}?>>Payment</option>                                          
                                            </select>                                        
                                        </div>
                                    </div>
                                   <div class="col-md-1 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="">status</label>                                           
                                            <select class="cheque-clear-form-box-field-dropdown" id="" name="data[MemberPayment][status]">
                                                <option value="">select</option>
                                                <option value="Cleared" <?php if(isset($postData['MemberPayment']['status']) && $postData['MemberPayment']['status'] == 'Cleared') { echo 'selected';}?>>Cleared</option>
                                                <option value="Uncleared" <?php if(isset($postData['MemberPayment']['status']) && $postData['MemberPayment']['status'] == 'Uncleared'){echo 'selected';}?>>Uncleared</option>                                                                                            
                                            </select>                                        
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mb-0">
                                        <div class="col-sm-offset-2 col-sm-10"> 
                                            <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Load Payment/Receipt</span></button>                                        
                                            <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-warning  btn-sm"><i class="icon-rocket"></i><span class="btn-text"> Update Clear Date</span></button>                                                                            
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
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
                                                        foreach($cashBookData as $paymentDate => $finalData){    
                                                            $paymentTotalAmount += isset($paymentData['SocietyPayment']['amount']) && !empty($paymentData['SocietyPayment']['amount']) ? $paymentData['SocietyPayment']['amount'] * 1 : 0.00;
                                                         ?>
                                                        <tr id="">
                                                            <td><?php echo isset($finalData['voucher_no']) ? $finalData['voucher_no'] : '';  ?></td>
                                                            <td><?php echo isset($finalData['payment_date']) ? $finalData['payment_date'] :'';  ?></td>
                                                            <td><?php echo isset($finalData['cheque_number']) ? $finalData['cheque_number'] :'';  ?></td>
                                                            <td><?php echo isset($finalData['cheque_date']) ? $finalData['cheque_date'] :'';  ?></td>
                                                            <td><input type="date" class="form-control"name="data[ClearChequePayment][<?php echo isset($finalData['id']) ? $finalData['id'] : '';  ?>][cleared_date]" value="<?php echo isset($finalData['cleared_date']) ? $utilObj->getFormatDate($finalData['cleared_date']) :'' ; ?>" required=""></td>
                                                            <td><?php echo isset($finalData['deposit']) ? $finalData['deposit'] :'';  ?></td>
                                                            <td><?php echo isset($finalData['withdrawal']) ? $finalData['withdrawal'] :'';  ?></td>
                                                        </tr>
                                                        <?php $idCount++; } }else{?>
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