<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <div id="print_member_list">
                                <div id="print_member-list"> 
                                    <div class="bill-outer-border">
                                        <div class="row">
                                            <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                            <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                            <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                        </div>
                                        <br>
                                        <div class="row1">
                                            <div class="bill">List of Member</div>
                                            <div class="row1">
                                                <div style="padding: 0px;">
                                                    <div class="table-wrap">
                                                        <div class="table-responsive1">
                                                            <table id="datable_1" class="table padding-td-none padding-th-none table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="2%" class="text-center">Sr.</th>
                                                                        <th width="6%" class="text-center">Unit No.</th>
                                                                        <th class="text-center">Member Name</th>
                                                                        <th class="text-center">Associate Member</th>
                                                                        <th class="text-center">Occupant</th>
                                                                        <th class="text-center">Phone No.</th>
                                                                        <th width="8%" class="text-center">Area</th>
                                                                    </tr>
                                                                </thead>
                                                                <tr id="">
                                                                    <td class="border-none">Building</td>
                                                                    <td colspan="6" class="border-none">BHOOMI PLAZA PREMISES CO-OP SOCIETY LTD</td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td class="border-none">Wing : - </td>
                                                                    <td colspan="6" class="border-none">1</td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td>1</td>
                                                                    <td>1</td>
                                                                    <td>Smt. ZAVER BHARAT GADA</td>
                                                                    <td>Self</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-center">36.95</td>
                                                                </tr>
                                                                <tr id="">
                                                                    <td>1</td>
                                                                    <td>1</td>
                                                                    <td>Smt. ZAVER BHARAT GADA</td>
                                                                    <td>Self</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-center">36.95</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <center>
                                <button type="button" class="btn btn-success btn-icon left-icon" onclick="printAllReports('print_member_list');"><i class="fa fa-print"></i><span> Print</span></button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>