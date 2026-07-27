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
                            <a href="?t=1">Bank/Cash Difference</h6>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <br><br>
                            <div id="print_member_monthly_cotribution">
                            <div class="print-member-monthly-cotribution">
                                
                                <div style="padding:15px;">                            
                                    <table id="" class="table table-bordered padding-td-none padding-th-none">
                                        <thead>
                                            <tr>
                                                <th>Member</th>
                                                <th>Bill No</th>
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
                                                $c = count($billDetailArray[$member['Member']['id']]);
                                                $i=0;
                                                $totBill = $totContri = $totGst = $totInt = $totDiff = $totCr = 0;
                                                foreach ($billDetailArray[$member['Member']['id']] as $bill){
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
                                                <td ><?php echo $member['Member']['member_name'].' - '.$member['Member']['id'];?></td>
                                                <?php }else{ ?>
                                                <td></td>
                                                <?php } ?>
                                                <td><?php echo $bill['bill_no'];?></td>
                                                <td><?php echo number_format($bill['bill_amount'],2);?></td>
                                                <td><?php echo number_format($bill['contri'],2);?></td>
                                                <td><?php echo number_format($bill['gst'],2);?></td>                                                        <td><?php echo number_format($bill['interest'],2);?></td>  
                                                <td><?php echo number_format($bill['bill_amount'],2);?></td>
                                                <td><?php echo number_format($cr,2);?></td>
                                                <td <?php echo $f;?>><?php echo number_format($diff,2);?></td>
                                                
                                            </tr>    
                                            <?php $i++; } ?>
                                            <tr>
                                                <td colspan="2">Total</td>
                                                <td><?php echo number_format($totBill,2);?></td>
                                                <td><?php echo number_format($totContri,2);?></td>
                                                <td><?php echo number_format($totGst,2);?></td>
                                                <td><?php echo number_format($totInt,2);?></td>
                                                <td><?php echo number_format($totBill,2);?></td>
                                                <td><?php echo number_format($totCr,2);?></td>
                                                <td><?php echo number_format($totDiff,2);?></td>
                                            </tr>
                                            <?php } ?>         
                                            <tr ><td colspan="6">Total</td><td><?php echo number_format($totalDr,2);?></td><td><?php echo number_format($totalCr,2);?></td><td><?php echo 'Total Diff (<1):'.$totalDiffY.'  Total Diff(>=1)'.$totalDiffR;  ?></td></tr>
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