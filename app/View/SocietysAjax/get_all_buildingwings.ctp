<?php
/*
 * To get all wings data
 * and open the template in the editor.
 */
if (isset($wingLists) && count($wingLists) > 0) {
        echo '<option value="">Select wings</option>';
        foreach ($wingLists as $wingID => $wingName) {
        ?>
        <option value="<?php echo $wingID; ?>"><?php echo $wingName; ?></option>
        <?php }
    } else { ?>
        <option value="">Wing not available</option>
<?php } ?>
