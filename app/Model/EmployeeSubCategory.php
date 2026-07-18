<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class EmployeeSubCategory extends AppModel {   
    public $belongsTo = array(
		'EmployeeCategory' => array(
			'className' => 'EmployeeCategory',
			'foreignKey' => 'emp_category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

