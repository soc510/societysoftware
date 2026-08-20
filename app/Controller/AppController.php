<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

class AppController extends Controller {

    public $components = array(
        'Session',
        'Auth' => array(
            'logoutRedirect' => array('controller' => 'users', 'action' => 'home'),
            'authError' => 'You must be logged in to view this page.',
            'loginError' => 'Invalid Username or Password entered, please try again.'
        ),
        'Access',
        'Menu'
    );

    public function beforeFilter() {
        $this->Auth->allow('login');
        if ($this->Auth->loggedIn()) {
            if ($this->Access->isAllowed() === false) {
                //$this->redirect($this->Auth->logout());
            }
        }

        $userId = AuthComponent::user('id');
        $this->loadModel('society');
        $societyData = $this->society->find('first', array('conditions' => array('user_id' => $userId)));
        $this->set('menus', $this->Menu->menuItems(AuthComponent::user('role'), null, $societyData));
        $this->Auth->allow(['privacy_policy', 'refund_policy', 'termandcondition']);

        $__role = AuthComponent::user('role');
        if ($this->Auth->loggedIn() && ($__role == 'Society' || $__role == 'Member') && !$this->Session->read('Auth.year_id')) {
            $__sid = $this->Session->read('Auth.User.id');
            if (!empty($__sid)) {
                App::uses('ClassRegistry', 'Utility');
                $__fyMaster = ClassRegistry::init('FinancialYearMaster');
                $__fyMapping = ClassRegistry::init('SocietyFinancialYearMapping');
                $__today = date('Y-m-d');
                $__assignedIds = $__fyMapping->find('list', array(
                    'conditions' => array('SocietyFinancialYearMapping.society_id' => $__sid, 'SocietyFinancialYearMapping.is_active' => 1),
                    'fields' => array('SocietyFinancialYearMapping.year_id', 'SocietyFinancialYearMapping.year_id'),
                    'recursive' => -1
                ));
                if (!empty($__assignedIds)) {
                    $__curFy = $__fyMaster->find('first', array(
                        'conditions' => array(
                            'FinancialYearMaster.id' => array_values($__assignedIds),
                            'FinancialYearMaster.is_active' => 1,
                            'FinancialYearMaster.year_start_date <=' => $__today,
                            'FinancialYearMaster.year_end_date >=' => $__today
                        ),
                        'recursive' => -1
                    ));
                    if (empty($__curFy)) {
                        $__curFy = $__fyMaster->find('first', array(
                            'conditions' => array(
                                'FinancialYearMaster.id' => array_values($__assignedIds),
                                'FinancialYearMaster.is_active' => 1
                            ),
                            'order' => array('FinancialYearMaster.year_start_date' => 'desc'),
                            'recursive' => -1
                        ));
                    }
                    if (!empty($__curFy)) {
                        $__from = $__curFy['FinancialYearMaster']['year_start_date'];
                        $__to = $__curFy['FinancialYearMaster']['year_end_date'];
                        $this->Session->write('Auth.year_id', $__curFy['FinancialYearMaster']['id']);
                        $this->Session->write('Auth.year_start_date', $__from);
                        $this->Session->write('Auth.year_end_date', $__to);
                        $this->Session->write('Auth.formated_year_start_date', date('d/m/Y', strtotime($__from)));
                        $this->Session->write('Auth.formated_year_end_date', date('d/m/Y', strtotime($__to)));
                        $this->Session->write('Auth.start_year', date('Y', strtotime($__from)));
                        $this->Session->write('Auth.end_year', date('Y', strtotime($__to)));
                        $this->Session->write('Auth.financial_years_arr', array($__from, $__to));
                    }
                }
            }
        }
    }

    public function isAuthorized($user) {
        return true;
    }

    public function forceSSL() {
        return $this->redirect('https://' . env('SERVER_NAME') . $this->here);
    }
}
