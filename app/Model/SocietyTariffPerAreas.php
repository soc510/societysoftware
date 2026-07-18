<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class SocietyTariffPerAreas extends AppModel {
    public $belongsTo = array(
        'SocietyLedgerHeads' => array(
            'className' => 'SocietyLedgerHeads',
            'foreignKey' => 'ledger_head_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ));
}

