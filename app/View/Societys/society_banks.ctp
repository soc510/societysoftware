<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Society Banks</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <div class="row">
                                <form class="" method="post" id="societyBankFrm" name="societyBankFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                    <div class="col-md-3 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="title">Bank Name</label>
                                            <select class="form-control" id="bank_ledger_head_id" name="data[SocietyBank][bank_ledger_head_id]" required>
                                                <option value="">Select Bank</option>
                                               <?php
                                                if (isset($societyBankLists) && count($societyBankLists) > 0) :
                                                $s = 1;
                                                foreach ($societyBankLists as $societyBankId => $societyBankName) {
                                                    if (isset($this->request->data['SocietyBank']['bank_ledger_head_id']) && $this->request->data['SocietyBank']['bank_ledger_head_id'] == $societyBankId) {
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
                                    <div class="col-md-3 padding-0">
                                        <label class="control-label" for="slip_no">Account Number</label>
                                        <input type="text" class="form-control" id="account_no" name="data[SocietyBank][account_no]" value="<?php echo isset($postData['SocietyBank']['account_no']) ? $postData['SocietyBank']['account_no'] :'' ; ?>">
                                    </div> 
                                    <div class="col-md-3 padding-0"> 
                                        <label class="control-label" for="slip_no">Branch</label>
                                        <input type="text" class="form-control" id="account_no" name="data[SocietyBank][branch]" value="<?php echo isset($postData['SocietyBank']['branch']) ? $postData['SocietyBank']['branch'] :'' ; ?>">
                                    </div> 
                                   
                                    <div class="clearfix"></div>
                                    <div class="form-group mb-0">
                                        <div class="col-sm-offset-2 col-sm-10"> 
                                            <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Add Bank Details</span></button>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="table-wrap">
                                        <div class="table-responsive1">
                                            <table class="table table-striped table-bordered mb-0 padding-td-none padding-th-none">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Bank Name</th>
                                                        <th>Branch</th>
                                                        <th>Account No</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php //TODO
                                                        if(isset($societyBankData) && count($societyBankData) > 0){
                                                        $sr = 1;
                                                        
                                                        foreach($societyBankData as $key => $bankDataTemp){    
                                                            $bankData = $bankDataTemp['SocietyBank'];
                                                            $bankName = $societyBankLists[$bankData['bank_ledger_head_id']];
                                                         ?>
                                                        <tr id="">
                                                            <td><?php echo $sr;  ?></td>
                                                            <td><?php echo isset($bankName) ? $bankName :'';  ?></td>
                                                            <td><?php echo isset($bankData['branch']) ? $bankData['branch'] :'';  ?></td>
                                                            <td><?php echo isset($bankData['account_no']) ? $bankData['account_no'] :'';  ?></td>
                                                            <td>Edit / Delete</td>                                                            
                                                        </tr>
                                                        <?php $sr++; } }else{?>
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