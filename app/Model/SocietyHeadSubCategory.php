<?php

App::uses('AppModel', 'Model');

/**
 * Role Model
 *
 * @property User $User
 */
class SocietyHeadSubCategory extends AppModel {
    public $belongsTo = array(
        'AccountHead' => array(
            'className' => 'AccountHead',
            'foreignKey' => 'account_head_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ), 'AccountCategory' => array(
            'className' => 'AccountCategory',
            'foreignKey' => 'account_category_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}
