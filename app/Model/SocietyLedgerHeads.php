<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class SocietyLedgerHeads extends AppModel {

    public $hasOne = array(
        'SocietyTariffOrder' => array(
            'className' => 'SocietyTariffOrder',
            'foreignKey' => 'ledger_head_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
     public $belongsTo = array(
        'AccountHead' => array(
            'className' => 'AccountHead',
            'foreignKey' => 'account_head_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ), 'AccountCategory' => array(
            'className' => 'AccountCategory',
            'foreignKey' => 'account_category_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
         'SocietyHeadSubCategory' => array(
            'className' => 'SocietyHeadSubCategory',
            'foreignKey' => 'society_head_sub_category_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}

