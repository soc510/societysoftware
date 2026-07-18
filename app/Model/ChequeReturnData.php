<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class ChequeReturnData extends AppModel {
    public $useTable = 'cheque_return_details';
    public $primaryKey = 'cr_id';
    
    /* public $hasOne = array(
        'MemberPayment' => array(
            'className' => 'MemberPayment',
            'foreignKey' => false,
            'conditions' => array('MemberPayment.cheque_reference_number = ChequeReturnData.cheque_no'),
            'fields' => '',
            'order' => ''
            )
        ) ;
    */ 
}