<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class Building extends AppModel {

	public $validate = array(
        'num_flats' => array(
            'numeric' => array(
            'rule' => array('numeric'),
            'message' => 'Flat No can only be numeric.'
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            )
        ));
     public function beforeSave($options = array()) {
     	//print_r($this->data);die;
     }
    public $belongsTo = array(
		'Society' => array(
			'className' => 'Society',
			'foreignKey' => 'society_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

