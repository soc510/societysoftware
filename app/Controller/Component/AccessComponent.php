<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * CakePHP Component
 * @author Suresh.Maharana
 */
App::uses('Component', 'Controller');

class AccessComponent extends Component {

    private $accessOn = array(
        'Admin'=>array(
            array('Controller' => 'users','Action' => 'login'),
            array('Controller' => 'users','Action' =>'home'),
            array('Controller' =>'users','Action'=>'logout'),
            array('Controller' =>'admin','Action'=>'dashboard'),
            array('Controller' =>'admin','Action'=>'add_society'),
            array('Controller' =>'admin','Action'=>'view_society')
            ),
           'Society' => array(
            array('Controller' => 'users','Action' => 'login'),
            array('Controller' => 'users','Action' =>'home'),    
            array('Controller'=> 'admin','Action'=>'dashboard'),
            array('Controller' => 'Users', 'Action' => 'home')
            ),
        'Reseller' => array(
            array('Controller' => 'users','Action' => 'login'),
            array('Controller' => 'users','Action' =>'home'),    
            array('Controller'=> 'admin','Action'=>'dashboard'),
            array('Controller' => 'Users', 'Action' => 'home')
            ),
        'Member' => array(
            array('Controller' => 'users','Action' => 'login'),
            array('Controller' => 'users','Action' =>'home'),    
            array('Controller'=> 'admin','Action'=>'dashboard'),
            array('Controller' => 'Users', 'Action' => 'home')
            ),
        );

    public function initialize(Controller $controller) {
        $this->controller = $controller;
    }

    public function isAllowed() {
        $validUrl = false;
        if ($this->controller->request->is('ajax')) {
            $validUrl = true;
        } else {
            $validUrl = true;
            $allowedActions = $this->accessOn[AuthComponent::user('role')];
            if (isset($allowedActions) && !empty($allowedActions)) {
                foreach ($allowedActions as $allowedAction) {
                    if (strtolower(trim($allowedAction['Controller'])) == strtolower(trim($this->controller->request->params['controller'])) && strtolower(trim($allowedAction['Action'])) == strtolower(trim($this->controller->request->params['action']))) {
                        $validUrl = true;
                        break;
                    }
                }
            }
        }
        
        if ($validUrl) {
            return $validUrl;
        } else {
            return $validUrl;
        }
    }
}
