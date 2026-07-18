<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class MemberBillSettlement extends AppModel {
    public $belongsTo = array(
        'Member' => array(
            'className' => 'Member',
            'foreignKey' => 'member_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),'MemberBillSummary' => array(
            'className' => 'MemberBillSummary',
            'foreignKey' => 'bill_summary_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),'MemberPayment' => array(
            'className' => 'MemberPayment',
            'foreignKey' => 'payment_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}

