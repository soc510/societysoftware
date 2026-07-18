<div class="col-md-12">
    <div class="table-wrap">
        <div class="table-responsive">
            <table class="table table-bordered padding-td-none padding-th-none">
                <thead>
                    <tr>
                        <th>Ledger Head</th>
                        <th>Unit/Area <span class="required">*</span></th>
                        <th>Rate</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($societyLedgerHeadList) && count($societyLedgerHeadList) > 0) :
                        $s = 1;
                        foreach ($societyLedgerHeadList as $societyLedgerHeadData) {?>
                            <tr>
                                <td><?php echo isset($societyLedgerHeadData['SocietyLedgerHeads']['title'])? $societyLedgerHeadData['SocietyLedgerHeads']['title'] :'';?> </td>
                                <td>
                                    <select class="form-control" name="data[SupplementaryBillGenerate][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>][rateUnitType]">
                                            <option value="">Select Rate/Unit</option>                                           
                                            <option value="Actual">Actual</option>                                           
                                            <option value="Area">Area</option>                                           
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="ledger_head_id_<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>" name="data[SupplementaryBillGenerate][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>][rate]" class="memberTariffCount text-right"  style="">
                                </td>
                                <td>
                                    <input type="text" id="ledger_head_id_<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>" name="data[SupplementaryBillGenerate][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>][description]" class="memberTariffCount text-right"  style="">
                                </td>
                            </tr>
                        <?php $s++;} endif;?>
                </tbody>
            </table>
        </div>             
    </div>	
</div>                  