<table id="datable_4" class="table padding-td-none padding-th-none">
    <thead>
        <tr>
            <?php if(isset($memberTariffHeader) && !empty($memberTariffHeader)){
                foreach($memberTariffHeader as $headerValue){ ?>
                <th width="3%"><?php echo wordwrap($headerValue,5,"<br>\n"); ?></th>
            <?php  } }?>
        </tr>
    </thead>
    <tbody id="member_tarrif_data">
        <?php 
            if (isset($memberTariffDataArra) && count($memberTariffDataArra) > 0) :
            $s = 1;
            $totalTarrifAmount = 0;
            foreach ($memberTariffDataArra as $MemberID => $mTariffData) {
        ?>
        <tr>
            <td><?php echo  $s; ?></td>
            <td><?php echo  $mTariffData['member_name']; ?></td>
            <td><?php echo  $mTariffData['flat_no']; ?></td>
                    <?php foreach($mTariffData['ledgerData'] as $ledgerHeadId =>$fData){
                        $ledgerAmount = isset($fData[$ledgerHeadId]) ? $fData[$ledgerHeadId] : 0;
                        $totalTarrifAmount += $ledgerAmount;
                        echo "<td><input type=\"text\" style=\"width:70px;\" id=\"member_tariff_$MemberID$ledgerHeadId\" name=\"data[MemberTariff][$MemberID][$ledgerHeadId][$ledgerHeadId]\" class=\"memberTariffCount text-right\" value=\"$ledgerAmount\"></td>";
                    }?>
            <td><input type="text" id="ledger_head_id_<?php echo $MemberID;?>" name="" class="memberTariffCount text-right" value="<?php echo $totalTarrifAmount; ?>" style="width:70px;"></td>
        </tr>                                                       
            <?php $s++; $totalTarrifAmount = 0;} ?>
            <tr>
                <td></td>
                <td>Grand Total</td>
                <td></td>
                        <?php foreach($memberTariffTotalArra as $ledgerHeadId =>$fData){
                            $ledgerAmount = isset($fData) ? $fData : 0;
                            $totalTarrifAmount += $ledgerAmount;
                            echo "<td><input type=\"text\" style=\"width:70px;\" id=\"tot_member_tariff_$ledgerHeadId\" name=\"tot_data\" class=\"totmemberTariffCount text-right\" value=\"$ledgerAmount\" disabled></td>";
                        }?>
                <td><input type="text" id="tot_ledger_head_id" name="" class="memberTariffCount text-right" value="<?php echo $totalTarrifAmount; ?>" style="width:70px;" disabled></td>
            </tr>
            <?php endif;?>
              
    </tbody>
</table>