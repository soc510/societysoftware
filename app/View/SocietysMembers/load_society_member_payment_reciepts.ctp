<div class="col-sm-12">
    <div class="table-wrap">
        <div class="table-responsive">
            <table id="datable_4" class="dataTable table table-bordered padding-td-none padding-th-none">
                <thead>
                    <tr>
                        <!--<th>Building</th>
                        <th>Wing</th>-->
                        <th>UnitNo</th>
                        <th>Member</th>
                        <th>Paymet Mode</th>                                                
                        <th>Amount</th>
                        <th>Receipt Date</th>
                        <th>ChequeNo</th>
                        <th>ChequeDate</th>
                        <th>Society Bank</th>                         
                        <th>MemberBank</th>
                        <!--th>Branch</th>
                        <th>MICR Code</th-->
                        <th>Balance Amount</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($societyMemberLists) && count($societyMemberLists) > 0) :
                        $s = 1;
                        foreach ($societyMemberLists as $societyMemberId => $societyMemberName) {
                            echo "<!--";
                            print_r($societyMemberName);
                            echo "-->";
                                                        $memberId = $societyMemberName['Member']['id'];
                        ?>
                            <tr>
                                <!--<td><?php echo isset($societyMemberName['Building']['building_name'])? $societyMemberName['Building']['building_name'] :'';?> </td>
                                <td><?php echo isset($societyMemberName['Wing']['wing_name'])? $societyMemberName['Wing']['wing_name'] :'';?> </td>-->
                                <td><?php echo isset($societyMemberName['Member']['flat_no'])? $societyMemberName['Member']['flat_no'] :'';?> </td>
                                <td><input type="hidden"  name="data[MemberPayments][<?php echo $s; ?>][member_id]" value="<?php echo isset($societyMemberName['Member']['id'])? $societyMemberName['Member']['id'] :'';?>"><?php echo isset($societyMemberName['Member']['member_name'])? $societyMemberName['Member']['member_name'] :'';?></td>
                                <td>
                                    <select onchange="setSocietyBankDataInBulkPay('<?php echo $s; ?>',this.value)" id="payment_method_<?= $s;?>"  class="numeric td-editable form-control numeric-field" name="data[MemberPayments][<?php echo $s; ?>][reciept_payment_mode]" id="reciept_payment_mode" style="width:150px;">
                                        <option value="">Select payment mode</option>    
                                        <option value="3" selected="selected" >Bank</option>   
                                        <option value="2">NEFT</option>   
                                        <option value="1">Cash</option>   
                                    </select>
                                </td>                                                                
                                <td><input type="text"  class="numeric td-editable form-control numeric-field" id="amount_paid_<?= $s;?>" name="data[MemberPayments][<?php echo $s; ?>][amount_paid]" data-parsley-type="digits"></td>
                                <td><input type="date" class="td-editable form-control" id="credited_date" name="data[MemberPayments][<?php echo $s; ?>][payment_date]" value="<?php echo isset($this->request->data['MemberPayment']['payment_date'])? $this->request->data['MemberPayment']['payment_date'] :date('Y-m-d');?>"></td>
                                <td><input type="text" class="numeric td-editable form-control numeric-field" id="cheque_reference_number" name="data[MemberPayments][<?php echo $s; ?>][cheque_reference_number]" data-parsley-type="number"></td>
                                <td><input type="date" class="td-editable form-control" id="credited_date" name="data[MemberPayments][<?php echo $s; ?>][cheque_date]" value="<?php echo isset($this->request->data['MemberPayment']['cheque_date'])? $this->request->data['MemberPayment']['cheque_date'] :'';?>"></td>
                                <td>
                                    <select id="<?php echo 'society_bank_'.$s; ?>"  onchange="removeErrorMsg('<?php echo 'society_bank_'.$s; ?>','<?= $s; ?>')"   name="data[MemberPayments][<?php echo $s; ?>][society_bank_id]" style="width:150px;">
                                        <option value="" selected>Select Bank</option>
                                            <?php
                                            foreach($bankLists as $id => $name) { ?>
                                                <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                                            <?php }    
                                            ?>  
                                    </select>
                                    <span style="color:red;" id="bank_selection_error_<?= $s;?>"></span>
                                </td>                                                                
                                <td>
                                    <select id="society_bank_id form-control" name="data[MemberPayments][<?php echo $s; ?>][member_bank_id]" style="width:150px;">
                                        <option value="" selected>Select Bank</option>
                                            <?php
                                            foreach ($societyBankLists as $bankDetails) { ?>
                                                <option value="<?php echo $bankDetails['Bank']['id']; ?>"><?php echo $bankDetails['Bank']['bank_name']; ?></option>
                                            <?php }    
                                            ?>  
                                    </select>
                                </td>
                                <!--td><input type="text" class="td-editable form-control" id="email" name="data[MemberPayments][<?php //echo $s; ?>][member_bank_branch]" data-parsley-type="onlyLetterNumber"></td>
                                <td><input type="text" class="td-editable form-control" id="email" name="data[MemberPayments][<?php //echo $s; ?>][member_bank_ifsc]" data-parsley-type="ifsc"></td-->
                                 <td><?php if(!empty($memberBalanceAmount)){ echo $memberBalanceAmount[$memberId] ?? '';} ?></td>
                                 <td style="width:40% !important;"><select class=" form-control" id="selectremarks<?= $s;?>" onchange="setRemarkText(this.value,'<?= $s; ?>')" >
                                     <option value="-1">Select Remarks</option>
                                     <option value="add">Add New</option>
                                     <?php 
                                     if(!empty($memberPaymentRemarks)){
                                         $memberremarks = $memberPaymentRemarks[$memberId];
                                         foreach($memberremarks as $data){ $narration = $data['narration'];?>
                                             <option value="<?= $narration ?>"><?= $narration ?></option>
                                        <?php }
                                     }
                                     ?>
                                     </select>
                                     <input style="display:none;" value="" name="data[MemberPayments][<?php echo $s; ?>][narration]" type="text" id="textremarks<?= $s;?>" class="form-control" onkeyup="checkAndResetToList('<?= $s;?>')" />
                                 </td>
                            </tr>
                        <?php $s++;} endif;?>
                </tbody>
            </table>
        </div>             
    </div>	
</div>                  