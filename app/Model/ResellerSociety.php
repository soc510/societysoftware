<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class ResellerSociety extends AppModel {

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Society' => array(
            'className' => 'Society',
            'foreignKey' => 'societie_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
