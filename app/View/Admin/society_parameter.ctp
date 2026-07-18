<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view bg-white">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Society Parameter</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in ">
                    <div class="panel-body">
                        <div class="table-wrap col-md-4">
                            <div class="table-responsive ">
                                <table id="datable_1" class="table display table-bordered table-striped padding-td-none padding-th-none">
                                    <thead>
                                        <tr>
                                            <th>Billing Frequency</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($billingFrequencyData) && count($billingFrequencyData) > 0) :
                                            $s = 1;
                                            foreach($billingFrequencyData as $bfData){      
                                        ?>
                                        <tr id="society_<?php echo $s; ?>">
                                            <td><?php echo isset($bfData['BillingFrequency']['frequency_type']) ? $bfData['BillingFrequency']['frequency_type'] : ''; ?></td>
                                           
                                        </tr>
                                        </tr>
                                        <?php $s++; } endif;?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="table-wrap col-md-4">
                            <div class="table-responsive">
                                <table id="datable_1" class="table display table-bordered table-striped padding-td-none padding-th-none">
                                    <thead>
                                        <tr>
                                            <th>Interest Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($interestTypeData) && count($interestTypeData) > 0) :
                                            $s = 1;
                                            foreach($interestTypeData as $itData){      
                                        ?>
                                        <tr id="society_<?php echo $s; ?>">
                                            <td><?php echo isset($itData['InterestType']['interest_type']) ? $itData['InterestType']['interest_type'] : ''; ?></td>
                                        </tr>
                                        </tr>
                                        <?php $s++; } endif;?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-wrap col-md-4">
                            <div class="table-responsive">
                                <table id="datable_1" class="table display table-bordered table-striped padding-td-none padding-th-none" >
                                    <thead>
                                        <tr>
                                            <th>Tarrif Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($tariffTypeData) && count($tariffTypeData) > 0) :
                                            $s = 1;
                                            foreach($tariffTypeData as $tffData){      
                                        ?>
                                        <tr id="society_<?php echo $s; ?>">
                                            <td><?php echo isset($tffData['TariffType']['tariff_type']) ? $tffData['TariffType']['tariff_type'] : ''; ?></td>                                           
                                        </tr>
                                        </tr>
                                        <?php $s++; } endif;?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        
                        <div class="table-wrap col-md-4">
                            <div class="table-responsive">
                                <table id="datable_1" class="table display table-bordered table-striped padding-td-none padding-th-none" >
                                    <thead>
                                        <tr>
                                            <th>Account Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($accountCategoryData) && count($accountCategoryData) > 0) :
                                            $s = 1;
                                            foreach($accountCategoryData as $acccategoryData){      
                                        ?>
                                        <tr id="society_<?php echo $s; ?>">
                                            <td><?php echo isset($acccategoryData['AccountCategory']['title']) ? $acccategoryData['AccountCategory']['title'] : ''; ?></td>                                           
                                        </tr>
                                        </tr>
                                        <?php $s++; } endif;?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-wrap col-md-4">
                            <div class="table-responsive">
                                <table id="datable_1" class="table display table-bordered table-striped padding-td-none padding-th-none" >
                                    <thead>
                                        <tr>
                                            <th>Account Heads</th>
                                            <th>Transaction Type</th>    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($accountHeadData) && count($accountHeadData) > 0) :
                                            $s = 1;
                                            foreach($accountHeadData as $headData){      
                                        ?>
                                        <tr id="society_<?php echo $s; ?>">
                                            <td><?php echo isset($headData['AccountHead']['title']) ? $headData['AccountHead']['title'] : ''; ?></td>                                           
                                            <td><?php echo isset($headData['AccountHead']['transaction_type']) ? $headData['AccountHead']['transaction_type'] : ''; ?></td>                                               
                                        </tr>
                                        </tr>
                                        <?php $s++; } endif;?>   
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