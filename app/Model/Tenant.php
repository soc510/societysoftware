<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class Tenant extends AppModel {
    public $validate = array('phone' => array(
	'rule' => '/^([0-9]{1}[0-9]{9})$/',
    'message' => 'Enter a 10 digit number',
    'allowEmpty' => true
	));
     public $belongsTo = array(
        'Society' => array(
            'className' => 'Society',
            'foreignKey' => 'society_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Building' => array(
            'className' => 'Building',
            'foreignKey' => 'building_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ), 'Wing' => array(
            'className' => 'Wing',
            'foreignKey' => 'wing_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}

