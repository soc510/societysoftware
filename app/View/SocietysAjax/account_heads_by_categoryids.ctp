<?php
/*
 * To get all wings data
 * and open the template in the editor.
 */
if (isset($accountHeadsLists) && count($accountHeadsLists) > 0) {
        //echo '<option value="">Select Account Head</option>';
        foreach ($accountHeadsLists as $key => $accountHeadsListsData) {
        ?>
        <option value="<?php echo $accountHeadsListsData['AccountHead']['id']; ?>"><?php echo $accountHeadsListsData['AccountHead']['title']; ?></option>
        <?php }
    } else { ?>
        <option value="">Account head not available</option>
<?php } ?>
