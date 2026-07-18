<?php
/*
 * To get all wings data
 * and open the template in the editor.
 */
if (isset($accountHeadCategoryLists) && count($accountHeadCategoryLists) > 0) {
        //echo '<option value="">Select account category</option>';
        foreach ($accountHeadCategoryLists as $wingID => $accountHeadCategoryData) {
        ?>
        <option value="<?php echo $accountHeadCategoryData['AccountCategory']['id']; ?>"><?php echo $accountHeadCategoryData['AccountCategory']['title']; ?></option>
        <?php }
    } else { ?>
        <option value="">Account category not available</option>
<?php } ?>
