<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class BillSettlementOrder extends AppModel {
    public $useTable = 'bill_settlement_order';
    public $primaryKey = 'id';
 
}