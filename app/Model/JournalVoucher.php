<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class JournalVoucher extends AppModel {
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

