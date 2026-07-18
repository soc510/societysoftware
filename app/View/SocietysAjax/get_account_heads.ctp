<?php
/*
 * To get all wings data
 * and open the template in the editor.
 */
if (isset($accountHeadLists) && count($accountHeadLists) > 0) {
        echo '<option value="">Select Account Head</option>';
        foreach ($accountHeadLists as $accountHeadID => $accountHeadName) {
        ?>
        <option value="<?php echo $accountHeadID; ?>"><?php echo $accountHeadName; ?></option>
        <?php }
    } else { ?>
        <option value="">Account Head not available</option>
<?php } ?>
