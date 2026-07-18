<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class SocietyPayment extends AppModel {

    public $belongsTo = array(
        'Society' => array(
            'className' => 'Society',
            'foreignKey' => 'society_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'SocietyLedgerHeads' => array(
            'className' => 'SocietyLedgerHeads',
            'foreignKey' => 'ledger_head_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}