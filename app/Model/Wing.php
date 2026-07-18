<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class Wing extends AppModel {
    public $belongsTo = array(
		'Society' => array(
			'className' => 'Society',
			'foreignKey' => 'society_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),'Building' => array(
			'className' => 'Building',
			'foreignKey' => 'building_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

