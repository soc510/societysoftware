<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <br>
                            <div id="print_dues_from_member">
                                <div class="print-dues-from-member">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="report-bill">Closing Balance 2020 vs Opening Balance 2021</div>
                                    <div class="report-bill"></div>  
                                    <div class="report-bill-outer-section no-border-thead">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered1 padding-td-none padding-th-none">
                                                <thead>
                                                    <tr>
                                                        <th style="width:5%;" class="text-center">Sr.</th>
                                                        <th style="width:5%;" class="text-center">Unit No.</th>
                                                        <th class="text-center">Member Name</th>
                                                        
                                                        <th style="width:20%;" class="text-right">Closing Balance 2021</th>
                                                        <th style="width:20%;" class="text-right">Opening Balance 2021</th>
                                                        <th style="width:20%;" class="text-right">Differnce</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                   <tr id="">
                                                        <td colspan="6" class="border-none">Building : <?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></td>
                                                    </tr>
                                                    <tr id="">
                                                        <td colspan="6" class="border-none"><span style="display: block;">Wing : <?php echo isset($societyDetails['Wing']['wing_name']) ? $societyDetails['Wing']['wing_name'] : ''; ?></span></td>
                                                    </tr> 
                                                <?php //TODO
                                                if(isset($societyMemberDetails) && count($societyMemberDetails) > 0){
                                                $idCount = 1;
                                                
                                               
                                                foreach($societyMemberDetails as $memberDetails){     
                                                    ?>
                                                    <tr id="<?php echo $idCount;  ?>">
                                                        <td><?php echo $idCount;  ?></td>
                                                        <td><?php echo isset($memberDetails['flat_no']) ? $memberDetails['flat_no'] : '';  ?></td>
                                                        <td><?php echo isset($memberDetails['member_prefix']) ? $memberDetails['member_prefix'].$memberDetails['member_name'] : '';  ?> <?php echo isset($memberDetails['member_name']) ? $memberDetails['member_name'] : '';  ?></td>  
                                                        <td class="text-right"><?php echo $memberDetails['closing2018'].$memberDetails['closingCrDr'];  ?></td>
                                                        <td class="text-right"><?php echo $memberDetails['opening2019'].$memberDetails['openingCrDr'];  ?></td>
                                                        
                                                        <td class="text-right"><?php echo $memberDetails['difference'].$memberDetails['differenceCrDr'];  ?></td>
                                                    </tr>      
                                                <?php $idCount++; }?> 
                                                    
                                                    
                                                    <tr id="">
                                                        <td class="text-right" colspan="3" class="border-none">Total</td>
                                                        <td class="text-right"><?php echo number_format($closingTotalAmount,2,'.',',');  ?><?php echo ($closingTotalAmount > 0) ? ' Dr' : ' Cr';?></td>
                                                        <td class="text-right"><?php echo number_format($openingTotalAmount,2,'.',',');  ?><?php echo ($openingTotalAmount > 0) ? ' Dr' : ' Cr';?></td>
                                                        
                                                        <td class="text-right"><?php echo number_format($differenceTotalAmount,2,'.',',');  ?><?php echo ($differenceTotalAmount > 0) ? ' Dr' : ' Cr';?></td>
                                                    </tr>    
                                                 <?php }else{?>
                                                    <tr><td colspan="6"><center>Record not found.</center></td></tr>
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