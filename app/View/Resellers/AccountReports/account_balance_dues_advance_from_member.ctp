<?php ?>
<div class="container-fluid">
    <div class="row">
<table><tbody>
<tr>
	<th>Sr. no</th>
	<th>Flat No</th>
	<th>Member Name</th>
	<th>Current Year</th>
	<th>Previous Year</th>
</tr>
                                                        <?php //TODO
                                                            if(isset($accountDuesAdvanceMemberSummary) && count($accountDuesAdvanceMemberSummary) > 0){
                                                            $idCount = 1;
                                                            $duesAdvanceTotalAmount = 0;$duesPrevAmount = 0;
                                                            foreach($accountDuesAdvanceMemberSummary as $accountDuesAdvanceMemberInfo){
                                                            $duesAdvanceTotalAmount += isset($accountDuesAdvanceMemberInfo['total_dues_amount']) && !empty($accountDuesAdvanceMemberInfo['total_dues_amount']) ? $accountDuesAdvanceMemberInfo['total_dues_amount'] * 1 : 0.00;
								$duesPrevAmount+=isset($accountDuesAdvanceMemberInfo['opening_balance']) && !empty($accountDuesAdvanceMemberInfo['opening_balance']) ? $accountDuesAdvanceMemberInfo['opening_balance'] * 1 : 0.00;
							    
                                                         ?>
                                                        <tr id="<?php echo $idCount;  ?>">
                                                            <td><?php echo $idCount;  ?></td>
                                                            <td><?php echo isset($accountDuesAdvanceMemberInfo['flat_no']) ? $accountDuesAdvanceMemberInfo['flat_no'] : '';  ?></td>
                                                            <td><?php echo isset($accountDuesAdvanceMemberInfo['member_prefix']) ? $accountDuesAdvanceMemberInfo['member_prefix'].$accountDuesAdvanceMemberInfo['member_name'] : '';  ?> <?php echo isset($accountDuesAdvanceMemberInfo['Member']['member_name']) ? $accountDuesAdvanceMemberInfo['Member']['member_name'] : '';  ?></td>                                                    
                                                            <td class="text-right"><?php echo isset($accountDuesAdvanceMemberInfo['total_dues_amount']) ? $accountDuesAdvanceMemberInfo['total_dues_amount'] : '';  ?></td>
<td class="text-right"><?php echo isset($accountDuesAdvanceMemberInfo['opening_balance']) ? $accountDuesAdvanceMemberInfo['opening_balance'] : '';  ?></td>
                                                        </tr>      
                                                        <?php $idCount++; }?> 
                                                        <tr id="">
                                                            <td class="text-right" colspan="3" class="border-none">Total</td>
                                                            <td class="text-right"><?php echo number_format($duesAdvanceTotalAmount,2,'.',','); ?></td>
<td class="text-right"><?php echo number_format($duesPrevAmount,2,'.',',');  ?></td>
 
                                                        </tr>                                                        
                                                        <tr id="">
                                                            <td class="text-right" colspan="3"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?> Total</td>
                                                            <td class="text-right"><?php echo number_format($duesAdvanceTotalAmount,2,'.',','); ?></td>
<td class="text-right"><?php echo number_format($duesPrevAmount,2,'.',',');  ?></td>
 
                                                        </tr>                                                        
                                                        <tr id="">
                                                            <td class="text-right" colspan="3">Grand Total</td>
                                                            <td class="text-right"><?php echo number_format($duesAdvanceTotalAmount,2,'.',','); ?></td>
<td class="text-right"><?php echo number_format($duesPrevAmount,2,'.',',');  ?></td>
 
                                                        </tr> 
                                                        <?php }else{?>
                                                        <tr><td colspan="11"><center>Record not found.</center></td></tr>
                                                        <?php }?>
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
