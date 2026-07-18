<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class AccountHead extends AppModel {
    public $hasMany = array(
        'SocietyHeadSubCategory' => array(
            'className' => 'SocietyHeadSubCategory',
            'foreignKey' => 'account_head_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ));
}

