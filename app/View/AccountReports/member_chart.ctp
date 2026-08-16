<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Chart</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="memberChartFrm" method="post" id="memberChartFrm" name="memberChartFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                <div class="col-md-8 member-tariff-left-box">
                                    <div class="row">
                                        <div class="col-md-3 no-padding-right">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">From Date &nbsp;</div>
                                                <input type="date" name="data[MemberChart][from_date]" class="account-report-form-box-field-date-75" value="<?php echo isset($postData['MemberChart']['from_date']) ? $postData['MemberChart']['from_date'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3 no-padding-right">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">To Date &nbsp;</div>
                                                <input type="date" name="data[MemberChart][to_date]" class="account-report-form-box-field-date-75" value="<?php echo isset($postData['MemberChart']['to_date']) ? $postData['MemberChart']['to_date'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3 no-padding-right">
                                            <div class="account-report-form-field-box">
                                                <select class="account-report-form-box-field-dropdown-80" name="data[MemberChart][bill_type]">
                                                    <option value="reg" <?php if (!isset($postData['MemberChart']['bill_type']) || $postData['MemberChart']['bill_type'] == 'reg') echo 'selected'; ?>>Regular</option>
                                                    <option value="sup" <?php if (isset($postData['MemberChart']['bill_type']) && $postData['MemberChart']['bill_type'] == 'sup') echo 'selected'; ?>>Supplementary</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printAllReports('print_member_chart','landscape');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>
                                                <button type="button" class="btn btn-primary" onclick="export_member_chart_excel()">Export Excel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 right-section">
                                    <div class="row">
                                        <div class="right-title">Select (if required) </div>
                                        <div class="col-md-12">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Building &nbsp;</div>
                                                <select class="account-report-form-box-field-dropdown" name="data[MemberChart][building_id]" onchange="getAllBuildingWings(this.value, '#member_chart_wing');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($postData['MemberChart']['building_id']) && $postData['MemberChart']['building_id'] == $buildingID){?>
                                                    <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                                <select class="account-report-form-box-field-dropdown" id="member_chart_wing" name="data[MemberChart][wing_id]">
                                                    <option value="">Select Wing</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Unit No &nbsp;&nbsp;</div>
                                                <input type="text" class="account-report-form-box-field-50" name="data[MemberChart][flat_no]" value="<?php echo isset($postData['MemberChart']['flat_no']) ? $postData['MemberChart']['flat_no'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>
                                                <input type="text" class="account-report-form-box-field-50" name="data[MemberChart][flat_no_to]" value="<?php echo isset($postData['MemberChart']['flat_no_to']) ? $postData['MemberChart']['flat_no_to'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>
                            <div class="clearfix"></div><br><br>
                            <div id="print_member_chart">
                                <div id="export_to_excel_member_chart">
                                    <br>
                                    <div class="row">
                                        <div class="report-bill-outer-section">
                                            <div class="table-wrap table-responsive">
                                                <table id="member_chart_table" class="table padding-td-none padding-th-none table-bordered">
                                                    <thead>
                                                        <tr style="border: solid; border-color: whitesmoke;"><th colspan="<?php echo count(isset($uniqueLedgerHeads) ? $uniqueLedgerHeads : array()) + 7; ?>" style="background: #f5f5f5; text-align:center;"><h5><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5></th></tr>
                                                        <tr style="border: solid; border-color: whitesmoke;"><th colspan="<?php echo count(isset($uniqueLedgerHeads) ? $uniqueLedgerHeads : array()) + 7; ?>" style="background: #f5f5f5; text-align:center;"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></th></tr>
                                                        <tr style="border: solid; border-color: whitesmoke;"><th colspan="<?php echo count(isset($uniqueLedgerHeads) ? $uniqueLedgerHeads : array()) + 7; ?>" style="background: #f5f5f5; text-align:center;"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></th></tr>
                                                        <tr style="border: solid; border-color: whitesmoke;"><th colspan="<?php echo count(isset($uniqueLedgerHeads) ? $uniqueLedgerHeads : array()) + 7; ?>" style="background: #f5f5f5; text-align:center;"><strong>MEMBER CHART</strong></th></tr>
                                                        <tr style="background:#d9edf7;">
                                                            <th class="text-center" width="3%">Sr.</th>
                                                            <th class="text-center" width="6%">Unit No.</th>
                                                            <th class="text-center">Op. Balance</th>
                                                            <?php if(isset($uniqueLedgerHeads)) foreach($uniqueLedgerHeads as $lhId => $lhTitle) { ?>
                                                                <th class="text-center"><?php echo $lhTitle; ?></th>
                                                            <?php } ?>
                                                            <th class="text-center">Interest</th>
                                                            <th class="text-center">GST Amt</th>
                                                            <th class="text-center">Total</th>
                                                            <th class="text-center">Received</th>
                                                            <th class="text-center">Cl. Balance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $sr = 1;
                                                    $grandOpBal = $grandInterest = $grandGst = $grandTotal = $grandReceived = $grandClosing = 0;
                                                    $grandTariffTotals = array();

                                                    if(isset($memberChartData) && !empty($memberChartData)) {
                                                        foreach($memberChartData as $memberId => $mData) {
                                                            $grandOpBal += $mData['opening_balance'];
                                                            $grandInterest += $mData['interest'];
                                                            $grandGst += $mData['gst'];
                                                            $grandTotal += $mData['total_billed'];
                                                            $grandReceived += $mData['received'];
                                                            $grandClosing += $mData['closing_balance'];
                                                    ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $sr; ?></td>
                                                            <td class="text-center"><?php echo $mData['flat_no']; ?></td>
                                                            <td class="text-right"><?php echo number_format($mData['opening_balance'], 2); ?></td>
                                                            <?php if(isset($uniqueLedgerHeads)) foreach($uniqueLedgerHeads as $lhId => $lhTitle) {
                                                                $amt = isset($mData['tariffs'][$lhId]) ? $mData['tariffs'][$lhId] : 0;
                                                                if (!isset($grandTariffTotals[$lhId])) $grandTariffTotals[$lhId] = 0;
                                                                $grandTariffTotals[$lhId] += $amt;
                                                            ?>
                                                                <td class="text-right"><?php echo number_format($amt, 2); ?></td>
                                                            <?php } ?>
                                                            <td class="text-right"><?php echo number_format($mData['interest'], 2); ?></td>
                                                            <td class="text-right"><?php echo number_format($mData['gst'], 2); ?></td>
                                                            <td class="text-right"><strong><?php echo number_format($mData['total_billed'], 2); ?></strong></td>
                                                            <td class="text-right"><?php echo number_format($mData['received'], 2); ?></td>
                                                            <td class="text-right"><strong><?php echo number_format($mData['closing_balance'], 2); ?></strong></td>
                                                        </tr>
                                                    <?php
                                                            $sr++;
                                                        }
                                                    }
                                                    ?>
                                                    </tbody>
                                                    <?php if(isset($memberChartData) && !empty($memberChartData)) { ?>
                                                    <tfoot>
                                                        <tr style="font-weight:bold; background:#f5f5f5;">
                                                            <td colspan="2" class="text-right"><strong>Grand Total</strong></td>
                                                            <td class="text-right"><?php echo number_format($grandOpBal, 2); ?></td>
                                                            <?php if(isset($uniqueLedgerHeads)) foreach($uniqueLedgerHeads as $lhId => $lhTitle) { ?>
                                                                <td class="text-right"><?php echo number_format(isset($grandTariffTotals[$lhId]) ? $grandTariffTotals[$lhId] : 0, 2); ?></td>
                                                            <?php } ?>
                                                            <td class="text-right"><?php echo number_format($grandInterest, 2); ?></td>
                                                            <td class="text-right"><?php echo number_format($grandGst, 2); ?></td>
                                                            <td class="text-right"><?php echo number_format($grandTotal, 2); ?></td>
                                                            <td class="text-right"><?php echo number_format($grandReceived, 2); ?></td>
                                                            <td class="text-right"><?php echo number_format($grandClosing, 2); ?></td>
                                                        </tr>
                                                    </tfoot>
                                                    <?php } ?>
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

<script>
function export_member_chart_excel() {
    var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    tab_text += '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
    tab_text += '<x:Name>Member Chart</x:Name>';
    tab_text += '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
    tab_text += '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
    tab_text += "<table border='1'>" + $('#export_to_excel_member_chart table').html() + "</table>";
    tab_text += '</body></html>';

    var data_type = 'data:application/vnd.ms-excel';
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
        if (window.navigator.msSaveBlob) {
            var blob = new Blob([tab_text], { type: "application/csv;charset=utf-8;" });
            navigator.msSaveBlob(blob, 'Member_Chart.xls');
        }
    } else {
        var blob2 = new Blob([tab_text], { type: "application/csv;charset=utf-8;" });
        var filename = 'Member_Chart_' + new Date().toISOString().slice(0, 10) + '.xls';
        var elem = window.document.createElement('a');
        elem.href = window.URL.createObjectURL(blob2);
        elem.download = filename;
        document.body.appendChild(elem);
        elem.click();
        document.body.removeChild(elem);
    }
}
</script>
