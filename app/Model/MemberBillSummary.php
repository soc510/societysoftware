<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class MemberBillSummary extends AppModel {
    public $belongsTo = array(
        'Society' => array(
            'className' => 'Society',
            'foreignKey' => 'society_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),'Member' => array(
            'className' => 'Member',
            'foreignKey' => 'member_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}

