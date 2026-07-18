<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view bg-white">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Generated Bills</h6>
                    </div>
  <div class="clearfix"></div>

                            <form class="" method="post" id="advanceReceivedFromMemberFrm" name="advanceReceivedFromMemberFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">From&nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][from_date]" value="<?php echo isset($from_date) ? $from_date :'' ; ?>">
                                            </div> 
                                        </div>                                            
                                        <div class="col-md-6 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-box-label">To &nbsp;</div>  
                                               <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][to_date]" value="<?php echo isset($to_date) ? $to_date :'' ; ?>">

                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket" style="float: left;margin-left: 3%;"></i><span class="btn-text">Submit</span></button>
                                            </div>

                                            </div>





                                        </div>
                                     

                                                                                                     
                                    </div>  
                                    

                                    <div class="clearfix"></div>
                                  
                                </div> 
                            
                                  
                            </form>  



            <div class="pull-right">
                         <form action="/society_bills/delete_all_bills_payments" name="post_5f548edc8582f929887250" id="post_5f548edc8582f929887250" style="display:none;" method="post"><input name="_method" value="POST" type="hidden"></form><a href="#" class="btn btn-danger" data-toggle="tooltip" data-original-title="Delete all payments and bills" style="margin-right:10px;" onclick="if (confirm(&quot;Are you sure you want to delete # %s?&quot;)) { document.post_5f548edc8582f929887250.submit(); } event.returnValue = false; return false;"><i class="fa fa-trash-o" style="color:#fff;font-size:18px;"></i> Delete all bills and payments</a>
                                             </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datable_1" class="table table-striped table-bordered padding-th-none padding-td-none">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Bill No</th>
                                            <th>Bill Generate Date</th>
                                            <th>Bill Type</th>
                                            <th>Month</th>
                                            <th>Member Name</th>
                                            <th>Monthly Amount</th>
                                            <th>Opening Principal Arrears</th>
                                            <th>Opening Int. Arrears</th>
                                            <th>Total Tax</th>
                                            <th>Monthly Principal Amount</th>
                                            <th>Int. On Due </th>
                                            <!--<th>Monthly Bill Amount</th>-->
                                            <th>Amt. Payble</th>
                                            <th>Principal Paid</th>
                                            <th>Int. Paid</th>
                                            <th>Principal bal.</th>
                                            <th>Int bal</th>
                                            <th>Bal. Amt</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>   
                                                                 
                            <?php if(isset($memberBillSummaryDataArr) && count($memberBillSummaryDataArr) > 0){
                                         $idCount = 1;
                                         foreach($memberBillSummaryDataArr as $BillSummaryData){
                                          
                                      ?>
                                        <tr id="<?php echo $idCount; ?>">
                                            <td><?php echo $idCount; ?></td>
                                            <td><?php echo isset($BillSummaryData['bill_no']) ? $BillSummaryData['bill_no'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['bill_generated_date']) ? $BillSummaryData['bill_generated_date'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['bill_type']) ? $BillSummaryData['bill_type'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['month']) ? $utilObj->getMonthWordFormat($BillSummaryData['month']) :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['member_name']) ? $BillSummaryData['member_name'].'('.$BillSummaryData['flat_no'].')' :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['monthly_amount']) ? $BillSummaryData['monthly_amount'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['op_principal_arrears']) ? $BillSummaryData['op_principal_arrears'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['op_interest_arrears']) ? $BillSummaryData['op_interest_arrears'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['tax_total']) ? $BillSummaryData['tax_total'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['monthly_principal_amount']) ? $BillSummaryData['monthly_principal_amount'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['interest_on_due_amount']) ? $BillSummaryData['interest_on_due_amount'] :'' ; ?></td>
                                           <!-- <td><?php echo isset($BillSummaryData['monthly_bill_amount']) ? $BillSummaryData['monthly_bill_amount'] :'' ; ?></td>-->
                                            <td><?php echo isset($BillSummaryData['amount_payable']) ? $BillSummaryData['amount_payable'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['principal_paid']) ? $BillSummaryData['principal_paid'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['interest_paid']) ? $BillSummaryData['interest_paid'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['principal_balance']) ? $BillSummaryData['principal_balance'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['interest_balance']) ? $BillSummaryData['interest_balance'] :'' ; ?></td>
                                            <td><?php echo isset($BillSummaryData['balance_amount']) ? $BillSummaryData['balance_amount'] :'' ; ?></td>
                                            <td class="text-nowrap">
                                            <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'society_bills', 'action' => 'upadate_last_member_bill', $BillSummaryData['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                            <?php  //echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_generated_bills', $BillSummaryData['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$BillSummaryData['id'])); ?>
                                            </td>
                                        </tr>
                                         <?php $idCount++;}}?>
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