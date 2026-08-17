<?php
App::uses('AppModel', 'Model');

class ResellerSubUser extends AppModel {
    public $useTable = 'reseller_sub_users';

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );
}
