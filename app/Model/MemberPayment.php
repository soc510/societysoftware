<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class MemberPayment extends AppModel {    
    public $belongsTo = array(
        'Member' => array(
            'className' => 'Member',
            'foreignKey' => 'member_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Society' => array(
            'className' => 'Society',
            'foreignKey' => 'society_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}