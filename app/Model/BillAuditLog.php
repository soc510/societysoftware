<?php
App::uses('AppModel', 'Model');

class BillAuditLog extends AppModel {
    public $useTable = 'bill_audit_logs';

    public $belongsTo = array(
        'Society' => array(
            'className' => 'Society',
            'foreignKey' => 'society_id'
        ),
        'Member' => array(
            'className' => 'Member',
            'foreignKey' => 'member_id'
        )
    );
}
