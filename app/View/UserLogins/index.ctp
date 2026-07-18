<div class="userLogins index">
    <?php $this->Html->addCrumb('Users', '/users');?>
    <?php $this->Html->addCrumb('Logins History');?>
    <h2><?php echo __('User Logins History'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('ipaddress'); ?></th>
            <th><?php echo $this->Paginator->sort('time'); ?></th>
        </tr>
        <?php foreach ($userLogins as $userLogin): ?>
            <tr>
                <td><?php echo h($userLogin['UserLogin']['ipaddress']); ?>&nbsp;</td>
                <td><?php echo h($userLogin['UserLogin']['time']); ?>&nbsp;</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?>	</p>
    <div class="paging">
        <?php
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
    </div>
</div>