<div class="container-fluid pt-10">
    <div class="row">
            <?php echo $this->Session->flash(); ?>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="dash-prameter-lists pull-left">
                <div class="dash-top-header-menu-list">
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#society_ledger_heads_modal');"><i class="fa fa-check-square-o"></i> Ledger Heads</a>
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#flat_shop_details');"><i class="fa fa-list"></i> Flat / Shop Detail</a>
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#bill_modal');"><i class="fa fa-file-text-o"></i> Bill</a>
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#member_receipt_modal');"><i class="fa fa-outdent"></i> Member Receipts</a>
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#payment_entry_modal');"><i class="fa fa-rupee"></i> Payment Entry</a>
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#Member_Generate_Bill');"><i class="fa fa-th-list"></i> Generate Bill</a>
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#print_bill_modal');"><i class="fa fa-print"></i> Print Bill</a>
                </div>
            </div>   
        </div>
    </div>
    <!-- Row -->
    <div class="row mt-50">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="sm-data-box bg-red">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                        <span class="txt-light block counter"><span class="counter-anim"><?php echo isset($societyFlatsSummary['flatCount']) ? $societyFlatsSummary['flatCount'] : 0; ?></span></span>
                                        <span class="weight-500 uppercase-font txt-light block font-13">Total Flats</span>
                                    </div>
                                    <div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
                                        <i class="fa fa-building-o txt-light data-right-rep-icon font-50"></i>
                                    </div>
                                </div>	
                                <div class="row"> 
                                    <div class="col-xs-6 text-center">
                                        <span class="block txt-light font-15">Commercial</span>
                                        <span class="block txt-dark weight-500 font-20"><?php echo isset($societyFlatsSummary['commercialFlats']) ? $societyFlatsSummary['commercialFlats'] : 0; ?></span>
                                    </div>
                                    <div class="col-xs-6 text-center">
                                        <span class="block txt-light font-15">Residential</span>
                                        <span class="block txt-dark weight-500 font-20"><?php echo isset($societyFlatsSummary['residentialCount']) ? $societyFlatsSummary['residentialCount'] : 0; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="sm-data-box bg-yellow">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
                                        <span class="txt-light block counter"><span class="counter-anim"></span></span>
                                        <span class="weight-500 uppercase-font txt-light block">Collection This Year</span>
                                    </div>
                                    <div class="col-xs-4 text-center  pl-0 pr-0 data-wrap-right">
                                        <i class="fa fa-rupee txt-light data-right-rep-icon  font-50"></i>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>	
                                <div class="row"> 
                                    <div class="col-xs-6 text-center">
                                        <span class="block txt-light font-15">Cash</span>
                                        <span class="block txt-dark weight-500 font-20"><?php echo isset($societyCollectionSummary['cashPayment']) ? $societyCollectionSummary['cashPayment'] : 0; ?></span>
                                    </div>
                                    <div class="col-xs-6 text-center">
                                        <span class="block txt-light font-15">Bank</span>
                                        <span class="block txt-dark weight-500 font-20"><?php echo isset($societyCollectionSummary['bankPayment']) ? $societyCollectionSummary['bankPayment'] : 0; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="sm-data-box bg-green" style="height:150px;">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
                                        <span class="txt-light block counter">
                                            <span class="counter-anim"></span></span>
                                        <span class="weight-500 uppercase-font txt-light block">Total Outstanding this Year</span>
                                    </div>
                                    <div class="col-xs-4 text-center pl-0 pr-0 data-wrap-right">
                                        <i class="fa fa-file-text-o txt-light data-right-rep-icon font-50"></i>
                                    </div>
                                </div>	
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="sm-data-box bg-blue" style="height:150px;">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                        <span class="txt-light block counter">
                                            <span class="counter-anim"></span></span>
                                        <span class="weight-500 uppercase-font txt-light block">Total Expenses this Year</span>
                                    </div>
                                    <div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
                                        <i class="zmdi zmdi-redo txt-light data-right-rep-icon font-50"></i>
                                    </div>
                                </div>	
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->

    <br>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="panel panel-default card-view bg-white">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Due From Member</h6>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="pull-left inline-block full-screen">
                            <i class="zmdi zmdi-fullscreen"></i>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div  class="panel-body row pa-0">                        
                        <table class="table  table-fixed table-striped table-bordered padding-td-none padding-th-none">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Flat No</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php 
                                    if(isset($getDuesFromMemberDetails) && count($getDuesFromMemberDetails) > 0){
                                    $idCount = 1;                                                 
                                    foreach($getDuesFromMemberDetails as $amountDuesDetails){
                                ?>
                                <tr id="<?php echo $idCount; ?>">
                                    <td><?php echo $idCount; ?></td>
                                    <td><?php echo isset($amountDuesDetails['MemberBillSummary']['flat_no']) ? $amountDuesDetails['MemberBillSummary']['flat_no']: '';  ?></td>
                                    <td><?php echo isset($amountDuesDetails['Member']['member_name']) ? $amountDuesDetails['Member']['member_name']: '';  ?></td>
                                    <td class="text-right"><?php echo isset($amountDuesDetails['MemberBillSummary']['amount_payable']) ? $utilObj->CreditDebitAmountCheck($amountDuesDetails['MemberBillSummary']['amount_payable']): '';  ?></td>
                                </tr>
                                 <?php $idCount++;}
                                 if($idCount > 5){
                                        echo "<tr id=\"$idCount\"><td class=\"text-center\" colspan=\"4\">".$this->Html->link('Click here',array('controller'=>'account_reports','action'=>'account_dues_from_member'),array('style'=>'color:#0f4fa8;font-size:13px;','escape'=>false))." to view due from members.</td></tr>";
                                        }
                                    }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

       <!-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="panel panel-default card-view bg-white">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Bill Ledger Summary</h6>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="pull-left inline-block full-screen">
                            <i class="zmdi zmdi-fullscreen"></i>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div  class="panel-body row pa-0">
                        <table class="table table-striped table-bordered padding-td-none padding-th-none">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Collection</th>
                                    <th>Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>CIDCO</td>
                                    <td>500</td>
                                    <td>200</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Parking</td>
                                    <td>1000</td>
                                    <td>300</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Maintenance</td>
                                    <td>5500</td>
                                    <td>1500</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>-->

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="panel panel-default card-view bg-white">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Expenses Summary</h6>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="pull-left inline-block full-screen">
                            <i class="zmdi zmdi-fullscreen"></i>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div  class="panel-body row pa-0">
                        <table class="table table-striped table-bordered padding-td-none padding-th-none">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php 
                                    if(isset($getSocietyExpensesDetails) && count($getSocietyExpensesDetails) > 0){
                                    $idCount = 1;                                                 
                                    foreach($getSocietyExpensesDetails as $expensesDetails){
                                ?>
                                <tr id="<?php echo $idCount; ?>">
                                    <td><?php echo $idCount; ?></td>
                                    <td><?php echo isset($expensesDetails['SocietyPayment']['particulars']) ? $expensesDetails['SocietyPayment']['particulars']: '';  ?></td>
                                    <td class="text-right"><?php echo isset($expensesDetails['SocietyPayment']['amount']) ? $utilObj->CreditDebitAmountCheck($expensesDetails['SocietyPayment']['amount']): '';  ?></td>
                                </tr>
                                <?php $idCount++;}
                                        if($idCount > 5){
                                        echo "<tr id=\"$idCount\"><td class=\"text-center\" colspan=\"4\">".$this->Html->link('Click here',array('controller'=>'societys','action'=>'society_payments'),array('style'=>'color:#0f4fa8;font-size:13px;','escape'=>false))." to view all payments.</td></tr>";
                                        }
                                    }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="panel panel-default card-view bg-white">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Bill Summary</h6>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="inline-block full-screen">
                            <i class="zmdi zmdi-fullscreen"></i>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div  class="panel-body row pa-0">
                        <table class="table table-striped table-bordered padding-td-none padding-th-none">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Month</th>
                                    <th>Amount</th>
                                    <th>Collection</th>
                                    <th>Dues</th>
                                    <th>Society Name</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php if(isset($dashboardsocietyDetails) && count($dashboardsocietyDetails) > 0) {
                                $idCount = 1;
                                foreach($dashboardsocietyDetails as $societyData){
                                ?>
                                <tr>
                                    <td><?php echo $idCount; ?></td>
                                    <td><?php echo isset($societyData['monthName']) ? $societyData['monthName'] : ''; ?></td>
                                    <td class="text-right"><?php echo isset($billSummaryData['amount']) ? $utilObj->CreditDebitAmountCheck($billSummaryData['amount']) : ''; ?></td>
                                    <td class="text-right"><?php echo isset($billSummaryData['collectionAmount']) ? $utilObj->CreditDebitAmountCheck($billSummaryData['collectionAmount']) : '';?></td>
                                    <td class="text-right"><?php echo isset($billSummaryData['dueAmount']) ? $utilObj->CreditDebitAmountCheck($billSummaryData['dueAmount']) : ''; ?></td>
                                    <td class="text-right"><?php echo isset($societiesData['Society Name']) ? $utilObj->CreditDebitAmountCheck($societiesData['Society Name']) : ''; ?></td>
                                </tr>
                            <?php $idCount++;}}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>    

    <div class="row">

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="panel panel-default card-view bg-white">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Latest Receipts</h6>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="pull-left inline-block full-screen">
                            <i class="zmdi zmdi-fullscreen"></i>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div  class="panel-body row pa-0">
                        <table class="table table-striped table-bordered padding-td-none padding-th-none">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Flat</th>
                                    <th>Name</th>
                                    <th>Receipt</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php 
                                    if(isset($getReceiptDetails) && count($getReceiptDetails) > 0){
                                    $idCount = 1;                                                 
                                    foreach($getReceiptDetails as $receiptDetails){
                                ?>
                                <tr id="<?php echo $idCount; ?>">
                                    <td><?php echo $idCount; ?></td>
                                    <td><?php echo isset($receiptDetails['Member']['flat_no']) ? $receiptDetails['Member']['flat_no']: '';  ?></td>
                                    <td><?php echo isset($receiptDetails['Member']['member_name']) ? $receiptDetails['Member']['member_name']: '';  ?></td>
                                    <td><?php echo isset($receiptDetails['MemberPayment']['bill_generated_id']) ? $receiptDetails['MemberPayment']['bill_generated_id']: '';  ?></td>
                                    <td class="text-right"><?php echo isset($receiptDetails['MemberPayment']['amount_paid']) ? $utilObj->CreditDebitAmountCheck($receiptDetails['MemberPayment']['amount_paid']): '';  ?></td>
                                </tr>
                                <?php $idCount++;}
                                if($idCount > 5){
                                        echo "<tr id=\"$idCount\"><td class=\"text-center\" colspan=\"4\">".$this->Html->link('Click here',array('controller'=>'societys_members','action'=>'member_payments'),array('style'=>'color:#0f4fa8;font-size:13px;','escape'=>false))." to view all latest receipts.</td></tr>";
                                        }
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="panel panel-default card-view bg-white">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Latest Payments</h6>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="pull-left inline-block full-screen">
                            <i class="zmdi zmdi-fullscreen"></i>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div  class="panel-body row pa-0">
                        <table class="table table-striped table-bordered padding-td-none padding-th-none">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Vendor</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>                            
                            <tbody>
                                 <?php 
                                    if(isset($getSocietyLatestPaymentDetails) && count($getSocietyLatestPaymentDetails) > 0){
                                    $idCount = 1;                                                 
                                    foreach($getSocietyLatestPaymentDetails as $latestPaymentDetails){
                                ?>
                                <tr id="<?php echo $idCount; ?>">
                                    <td><?php echo $idCount; ?></td>
                                    <td><?php echo isset($latestPaymentDetails['SocietyPayment']['payment_date']) ? $latestPaymentDetails['SocietyPayment']['payment_date']: '';  ?></td>                                    
                                    <td><?php echo isset($latestPaymentDetails['SocietyLedgerHeads']['title']) ? $latestPaymentDetails['SocietyLedgerHeads']['title']: '';  ?></td>
                                    <td class="text-right"><?php echo isset($latestPaymentDetails['SocietyPayment']['amount']) ? $utilObj->CreditDebitAmountCheck($latestPaymentDetails['SocietyPayment']['amount']): '';  ?></td>
                                </tr>
                                <?php $idCount++;}
                                if($idCount > 5){
                                        echo "<tr id=\"$idCount\"><td class=\"text-center\" colspan=\"4\">".$this->Html->link('Click here',array('controller'=>'Societys','action'=>'society_payments'),array('style'=>'color:#0f4fa8;font-size:13px;','escape'=>false))." to view all latest payments.</td></tr>";
                                        }
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Row -->
    <!--<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default card-view panel-refresh">
                <div class="refresh-container">
                    <div class="la-anim-1"></div>
                </div>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">social campaigns</h6>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="pull-left inline-block refresh mr-15">
                            <i class="zmdi zmdi-replay"></i>
                        </a>
                        <a href="#" class="pull-left inline-block full-screen mr-15">
                            <i class="zmdi zmdi-fullscreen"></i>
                        </a>
                        <div class="pull-left inline-block dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="zmdi zmdi-more-vert"></i></a>
                            <ul class="dropdown-menu bullet dropdown-menu-right"  role="menu">
                                <li role="presentation"><a href="javascript:void(0)" role="menuitem"><i class="icon wb-reply" aria-hidden="true"></i>Edit</a></li>
                                <li role="presentation"><a href="javascript:void(0)" role="menuitem"><i class="icon wb-share" aria-hidden="true"></i>Delete</a></li>
                                <li role="presentation"><a href="javascript:void(0)" role="menuitem"><i class="icon wb-trash" aria-hidden="true"></i>New</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body row pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Campaign</th>
                                            <th>Client</th>
                                            <th>Changes</th>
                                            <th>Budget</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="txt-dark weight-500">Facebook</span></td>
                                            <td>Beavis</td>
                                            <td><span class="txt-success"><i class="zmdi zmdi-caret-up mr-10 font-20"></i><span>2.43%</span></span></td>
                                            <td>
                                                <span class="txt-dark weight-500">$1478</span>
                                            </td>
                                            <td>
                                                <span class="label label-primary">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="txt-dark weight-500">Youtube</span></td>
                                            <td>Felix</td>
                                            <td><span class="txt-success"><i class="zmdi zmdi-caret-up mr-10 font-20"></i><span>1.43%</span></span></td>
                                            <td>
                                                <span class="txt-dark weight-500">$951</span>
                                            </td>
                                            <td>
                                                <span class="label label-danger">Closed</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="txt-dark weight-500">Twitter</span></td>
                                            <td>Cannibus</td>
                                            <td><span class="txt-danger"><i class="zmdi zmdi-caret-down mr-10 font-20"></i><span>-8.43%</span></span></td>
                                            <td>
                                                <span class="txt-dark weight-500">$632</span>
                                            </td>
                                            <td>
                                                <span class="label label-default">Hold</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="txt-dark weight-500">Spotify</span></td>
                                            <td>Neosoft</td>
                                            <td><span class="txt-success"><i class="zmdi zmdi-caret-up mr-10 font-20"></i><span>7.43%</span></span></td>
                                            <td>
                                                <span class="txt-dark weight-500">$325</span>
                                            </td>
                                            <td>
                                                <span class="label label-default">Hold</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="txt-dark weight-500">Instagram</span></td>
                                            <td>Hencework</td>
                                            <td><span class="txt-success"><i class="zmdi zmdi-caret-up mr-10 font-20"></i><span>9.43%</span></span></td>
                                            <td>
                                                <span class="txt-dark weight-500">$258</span>
                                            </td>
                                            <td>
                                                <span class="label label-primary">Active</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>	
                    </div>	
                </div>
            </div>
        </div>
    </div>-->
</div>