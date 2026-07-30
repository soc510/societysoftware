<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="col-sm-6">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Member Bill Difference</h6>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="pull-right">
                            <?php /* Closed with </h6> instead of </a>, so the anchor stayed open and
                                     wrapped the whole filter form - clicking any field, including the
                                     date box, followed this link to ?t=1 and reloaded the page. */ ?>
                            <a href="?t=1">Bank/Cash Difference</a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form method="post" action="<?php echo $this->Html->url(array('controller'=>'account_reports','action'=>'account_trial_balance_difference')); ?>">
                              <div class="row" style="padding:10px 15px;">
                                <div class="col-md-2"><label>From Date</label>
                                  <input type="date" name="data[from_date]" class="form-control" value="<?php echo isset($postData['from_date']) ? h($postData['from_date']) : ''; ?>"></div>
                                <div class="col-md-2"><label>To Date</label>
                                  <input type="date" name="data[to_date]" class="form-control" value="<?php echo isset($postData['to_date']) ? h($postData['to_date']) : ''; ?>"></div>
                                <div class="col-md-4"><label>Member</label>
                                  <select name="data[member_id]" class="form-control">
                                    <option value="">All Members</option>
                                    <?php if (isset($memberDropdownList)) { foreach ($memberDropdownList as $__mId => $__mName) { ?>
                                    <option value="<?php echo h($__mId); ?>" <?php echo (isset($postData['member_id']) && $postData['member_id'] == $__mId) ? 'selected="selected"' : ''; ?>><?php echo h($__mName); ?></option>
                                    <?php } } ?>
                                  </select></div>
                                <div class="col-md-3"><label>Difference</label>
                                  <select name="data[diff_filter]" class="form-control">
                                    <option value="">All</option>
                                    <option value="diff" <?php echo (isset($postData['diff_filter']) && $postData['diff_filter']=='diff') ? 'selected="selected"' : ''; ?>>Only with Difference</option>
                                    <option value="nodiff" <?php echo (isset($postData['diff_filter']) && $postData['diff_filter']=='nodiff') ? 'selected="selected"' : ''; ?>>No Difference</option>
                                  </select></div>
                                <div class="col-md-2"><label>&nbsp;</label><br>
                                  <button type="submit" class="btn btn-success">Submit</button></div>
                              </div>
                            </form>
                            <br><br>
                            <div id="print_member_monthly_cotribution">
                            <div class="print-member-monthly-cotribution">
                                
                                <div style="padding:15px;">                            
                                    <table id="" class="table table-bordered padding-td-none padding-th-none">
                                        <thead>
                                            <tr>
                                                <th>Member</th>
                                                <th>Bill No</th>
                                                <th>Month</th>
                                                <th>Bill Amount</th>
                                                <th>Contribution</th>
                                                <th>GST</th>
                                                <th>Interest</th>
                                                <th>DrAmount</th>
                                                <th>CrAmount</th>
                                                <th>Difference</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                            $totalDiffR = 0; $totalDiffY=0; $totalCr=0;$totalDr=0;
                                            foreach($memberDetails as $member) { 
                                                $__allBills = $billDetailArray[$member['Member']['id']];
                                                $__shownBills = array();
                                                foreach ($__allBills as $__b) {
                                                    $__d = ($__b['contri'] + $__b['gst'] + $__b['interest']) - $__b['bill_amount'];
                                                    $__isDiff = abs($__d) >= 1;
                                                    if (isset($diffFilter) && $diffFilter == 'diff' && !$__isDiff) { continue; }
                                                    if (isset($diffFilter) && $diffFilter == 'nodiff' && $__isDiff) { continue; }
                                                    $__shownBills[] = $__b;
                                                }
                                                if (empty($__shownBills)) { continue; }
                                                $c = count($__shownBills);
                                                $i=0;
                                                $totBill = $totContri = $totGst = $totInt = $totDiff = $totCr = 0;
                                                foreach ($__shownBills as $bill){
                                                    $totBill +=$bill['bill_amount'];
                                                    $totContri += $bill['contri'];
                                                    $totGst += $bill['gst'];
                                                    $totInt += $bill['interest'];
                                                    $cr = $bill['contri']+$bill['gst']+$bill['interest'];
                                                    $totCr += $cr;
                                                    $diff = $cr-$bill['bill_amount'];
                                                    $totDiff += $diff;
                                                    
                                                    $totalDr += $bill['bill_amount'];
                                                    $totalCr += $cr;
                                                    
                                                    $f='';
                                                    
                                                // Half a paisa: below this the Difference cell prints 0.00 anyway, so
                                                // flagging it only paints rows yellow over float noise.
                                                if (abs($diff) >= 0.005 && abs($diff) < 1){ $f='style="background-color:yellow;"';$totalDiffY += $diff;}
                                                    elseif(abs($diff) >= 1){ $f='style="background-color:red;"'; $totalDiffR += $diff;}
                                        ?>
                                            
                                            <tr id="">
                                                <?php if ($i==0){?>
                                                <td ><?php echo $member['Member']['member_name'].' - '.$member['Member']['id'].' - '.$member['Member']['flat_no'];?></td>
                                                <?php }else{ ?>
                                                <td></td>
                                                <?php } ?>
                                                <td><?php echo $bill['bill_no'];?></td>
                                                <td><?php echo (isset($bill['bill_month']) && $bill['bill_month'] >= 1 && $bill['bill_month'] <= 12) ? date('F', mktime(0,0,0,$bill['bill_month'],10)) : (isset($bill['bill_month']) ? $bill['bill_month'] : ''); ?></td>
                                                <td><?php echo number_format($bill['bill_amount'],2);?></td>
                                                <td><?php echo number_format($bill['contri'],2);?></td>
                                                <td><?php echo number_format($bill['gst'],2);?></td>                                                        <td><?php echo number_format($bill['interest'],2);?></td>  
                                                <td><?php echo number_format($bill['bill_amount'],2);?></td>
                                                <td><?php echo number_format($cr,2);?></td>
                                                <td <?php echo $f;?>><?php echo number_format($diff,2);?></td>
                                                
                                            </tr>    
                                            <?php $i++; } ?>
                                            <tr>
                                                <td colspan="3">Total</td>
                                                <td><?php echo number_format($totBill,2);?></td>
                                                <td><?php echo number_format($totContri,2);?></td>
                                                <td><?php echo number_format($totGst,2);?></td>
                                                <td><?php echo number_format($totInt,2);?></td>
                                                <td><?php echo number_format($totBill,2);?></td>
                                                <td><?php echo number_format($totCr,2);?></td>
                                                <td><?php echo number_format($totDiff,2);?></td>
                                            </tr>
                                            <?php } ?>         
                                            <tr ><td colspan="7">Total</td><td><?php echo number_format($totalDr,2);?></td><td><?php echo number_format($totalCr,2);?></td><td><?php echo 'Total Diff (<1):'.$totalDiffY.'  Total Diff(>=1)'.$totalDiffR;  ?></td></tr>
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