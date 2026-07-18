<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Emails.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/*need to add username/email */
?>

<p>
    <?php
    echo '<h2> Comment History : </h2><br>';
 ?>
 <dl class="commentbox">
            <?php  foreach ($comment as $value) { ?>
        <dt>
        <div class="name"><b><?php echo h($value['User']['firstname'] . " " . $value['User']['lastname']); ?></b>
            <span class="date">on <b><?php echo h($this->Time->format($value['RequestComment']['created'], '%m-%d-%Y  %H:%M')); ?></b></span></div>
        <div class="comment_txt" style="background: none repeat scroll 0 0 rgb(244, 244, 244);"><?php echo h($value['RequestComment']['comment']); ?></div>

        </dt><hr>
            <?php } ?>
    </dl>
</p>