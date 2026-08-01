<?php



App::uses('AppController', 'Controller');

App::uses('CakeEmail', 'Network/Email');



/**

 * Users Controller

 *

 * @property User $User

 * @property PaginatorComponent $Paginator

 * @property SessionComponent $Session

 */









class UsersController extends AppController {



    /**

     * Components

     *

     * @var array

     */

    public $components = array('Paginator', 'Session', 'RequestHandler');

    

    public function beforeFilter() {

        parent::beforeFilter();

        $this->Auth->autoRedirect = false;

        $this->Auth->allow('login','forgot_password','change_password','home');

    }
    
    function change_society(){
        $resellerData = $this->Session->read('Auth.Reseller');
        $this->Session->write('Auth.User',$resellerData);
        $this->redirect(array('controller' => 'resellers', 'action' => 'assigned_societys'));
    }



    public function login() {

        $this->Session->write('Auth.dbConfig','default');

        //$this->autoRender = false;

        $this->Session->write('Auth.reseller_Flag', 0);

        if ($this->Session->read('Auth.User.role') == 'Admin') {

            $this->redirect(array('controller' => 'admin', 'action' => 'dashboard'));

        } else if ($this->Session->read('Auth.User.role') == 'Society') {

            $this->redirect(array('controller' => 'societys', 'action' => 'dashboard'));

        } else if ($this->Session->read('Auth.User.role') == 'Reseller') {

            $this->redirect(array('controller' => 'resellers', 'action' => 'dashboard'));

        } else if ($this->Session->read('Auth.User.role') == 'Member') {

            $this->loadModel('Member');

            $memberData = $this->Member->find('first', array('conditions' => array('Member.user_id' => $this->Session->read('Auth.User.id'))));

            $this->Session->write('Auth.member', $memberData['Member']);

            $this->Session->write('Auth.User.id', $memberData['Member']['society_id']);

            $this->redirect(array('controller' => 'societys_members', 'action' => 'dashboard'));

        }



        if ($this->request->is('post')) {

            // $userName = $this->request->data['User']['username'];
            // $password = $this->request->data['User']['password'];
            // echo $userName.'--'.$password;exit;
            // $this->request->data['User']['username'] = str_replace(' ','',trim($userName)) ; 
             // $this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);// str_replace(' ','',trim($password)) ;  
                /*print_r($this->request->data);

            var_dump($this->Auth->login());exit;*/

            if ($this->Auth->login()) {
                $loginInfo = array('user_id' => $this->Auth->user('id'), 'ipaddress' => $this->request->clientIp(), 'time' => date('Y-m-d H:i:s'));

                $this->User->UserLogin->save($loginInfo);

                //echo $this->Auth->User('role');die;

                $this->Session->setFlash(__('Welcome, ' . $this->Auth->user('username')), 'info');

                if ($this->Auth->User('role') == "Admin") {

                    $this->redirect($this->Auth->redirectUrl(array('controller' => 'admin', 'action' => 'dashboard')));

                } else if ($this->Auth->User('role') == "Society") {

                    $this->redirect($this->Auth->redirectUrl(array('controller' => 'societys', 'action' => 'dashboard')));

                } else if ($this->Auth->User('role') == "Reseller") {
                    $this->redirect(array('controller' => 'resellers', 'action' => 'dashboard'));

                } else if ($this->Auth->User('role') == "Member") {

                    $this->loadModel('Member');

                    $memberData = $this->Member->find('first', array('conditions' => array('Member.user_id' => $this->Session->read('Auth.User.id'))));

                    $this->Session->write('Auth.member', $memberData['Member']);

                    $this->Session->write('Auth.User.id', $memberData['Member']['society_id']);

                    $this->redirect($this->Auth->redirectUrl(array('controller' => 'societys_members', 'action' => 'dashboard')));

                } else {

                    $this->redirect($this->Auth->redirectUrl());

                }

            } else {

                $this->Session->setFlash(__('Invalid username or password'), 'error');

                $this->redirect(array("controller" => "users", "action" => "home"));

                $this->set('error', 'Invalid username or password');

            }

        }

        $this->redirect(array("controller" => "users", "action" => "home"));

    }



    public function logout() {
        $this->Session->destroy();
        $this->redirect($this->Auth->logout());

    }



    /**

     * index method

     *

     * @return void

     */

    public function index() {

        $this->User->recursive = 0;

        $this->set('users', $this->Paginator->paginate());

    }

    

    public function home() {

        $this->layout = 'home';

        $this->set('glanceStats', $this->getGlanceStats());

    }

    /**
     * Live "at a glance" counters for the public landing page.
     * Uses the same logic as the admin dashboard (AdminController::dashboard)
     * so the numbers stay in sync with what admins see. Cached for an hour
     * so the public page never runs these COUNTs on every hit.
     */
    private function getGlanceStats() {

        $stats = Cache::read('home_glance_stats');

        if (is_array($stats)) {
            return $stats;
        }

        $this->loadModel('Member');
        $this->loadModel('Building');
        $this->loadModel('Wing');
        $this->loadModel('MemberBillGenerate');

        $active = Configure::read('Active');

        $stats = array(
            'societies' => (int)$this->User->find('count', array('conditions' => array('User.access_level' => Configure::read('AccessLevel.society')), 'recursive' => -1)),
            'members'   => (int)$this->Member->find('count', array('conditions' => array('Member.status' => $active), 'recursive' => -1)),
            'bills'     => (int)$this->MemberBillGenerate->find('count', array('recursive' => -1)),
            'resellers' => (int)$this->User->find('count', array('conditions' => array('User.access_level' => Configure::read('AccessLevel.reseller')), 'recursive' => -1)),
            'buildings' => (int)$this->Building->find('count', array('recursive' => -1)),
            'wings'     => (int)$this->Wing->find('count', array('recursive' => -1)),
        );

        Cache::write('home_glance_stats', $stats);

        return $stats;
    }

    

    public function aboutus() {

        $this->layout = 'aboutus';

    }

    

    public function setMenu(){

        $selectedID = '';

        if($this->RequestHandler->isAjax()){

            $this->autoRender = false;

            $selectedID = $this->request->query('selected');

            $this->Session->write('Auth.User.selectedMenu','#menu-'.$selectedID);

        }

        die;

    }

    

    function getMenu() {

        $this->layout = 'ajax';



        if($this->request->is('ajax')) {

            $selectedMenu = $this->Session->read('Auth.User.selectedMenu');



            if(empty($selectedMenu)) {

                $selectedMenu = '#menu-1';

            }

            echo json_encode($selectedMenu);

        }

        die;

    }

    

    public function auto_login() {

        $this->Session->write('Auth.dbConfig','default');

        $this->autoRender = false;

        $this->User->id = AuthComponent::user('id');

        $this->Session->write('Auth.reseller_username', AuthComponent::user('username'));

        $this->Session->write('Auth.reseller_password', $this->User->field('password'));

        $this->Session->write('Auth.reseller_Flag', 1);

        if ($this->Auth->login()) {

            $this->Session->delete('Auth.User');

        }

        if ($this->request->is('post')) {

            if (isset($this->request->data['societyAutoLogin']['loginFlag'])) {

                if ($this->request->data['societyAutoLogin']['loginFlag'] == 1) {

                    $this->Session->write('Auth.reseller_Flag', 0);

                }

            }

            if (isset($this->request->data['User']['username']) && !empty($this->request->data['User']['username'])) {

                $userLoginInfo = $this->User->identifyUser($this->request->data);

                if ($this->Auth->login($userLoginInfo['User'])) {

                    if ($this->Auth->User('role') == "Admin") {

                        $this->redirect($this->Auth->redirectUrl(array('controller' => 'admin', 'action' => 'dashboard')));

                    } else if ($this->Auth->User('role') == 'Society') {

                        $this->redirect($this->Auth->redirectUrl(array('controller' => 'societys', 'action' => 'dashboard')));

                    } else if ($this->Auth->User('role') == 'Member') {

                        $this->redirect($this->Auth->redirectUrl(array('controller' => 'societys_members', 'action' => 'dashboard')));

                    } else if ($this->Auth->User('role') == 'Reseller') {

                        $this->redirect(array('controller' => 'resellers', 'action' => 'assigned_societys'));

                    } else {

                        $this->redirect($this->Auth->redirectUrl());

                    }

                }

            }

        }

    }


    public function login201819() {
        $this->Session->write('Auth.dbConfig','database201920');
        $this->autoRender = false;


        $this->User->id = AuthComponent::user('id');
        $this->Session->write('Auth.reseller_username', AuthComponent::user('username'));
        $this->Session->write('Auth.reseller_password', $this->User->field('password'));
        $this->Session->write('Auth.reseller_Flag', 1);
        if ($this->Auth->login()) {
          //  $this->Session->delete('Auth.User');
}
        if ($this->request->is('post')) {
            if (isset($this->request->data['societyAutoLogin']['loginFlag'])) {
                if ($this->request->data['societyAutoLogin']['loginFlag'] == 1) {
                    $this->Session->write('Auth.reseller_Flag', 0);
                }
            }
            if (isset($this->request->data['User']['username']) && !empty($this->request->data['User']['username'])) {
                $userLoginInfo = $this->User->identifyUser($this->request->data);
    
                if ($this->Auth->login($userLoginInfo['User'])) {
    
                    if ($this->Auth->User('role') == "Admin") {
                        $this->redirect($this->Auth->redirectUrl(array('controller' => 'admin', 'action' => 'dashboard')));
                    } else if ($this->Auth->User('role') == 'Society') {
                        $this->redirect($this->Auth->redirectUrl(array('controller' => 'societys', 'action' => 'dashboard')));
                    } else if ($this->Auth->User('role') == 'Reseller') {
    
                        $this->redirect(array('controller' => 'resellers', 'action' => 'assigned_societys'));
                    } else {
                        $this->redirect($this->Auth->redirectUrl());
                    }
                }
            }
        }
    }
    
    public function switchlogin() {
        Configure::write('debug', 1);
        $db = $this->Session->read('Auth.dbConfig');
        if ($db == 'default'){
            $this->Session->write('Auth.dbConfig','database201920');
        } else {
            $this->Session->write('Auth.dbConfig','default');
        }
        
        $this->autoRender = false;
        $this->User->id = AuthComponent::user('id');
        $username = AuthComponent::user('username');
        $password = $this->User->field('password');
        if ($this->Auth->login()) {
            $this->Session->delete('Auth.User');
        }
        if ($username != '') {
            $postData['User'] = array('username'=>$username,'password'=>$password);
            $userLoginInfo = $this->User->identifyUser($postData);
            if ($this->Auth->login($userLoginInfo['User'])) {
                if ($this->Auth->User('role') == "Admin") {
                    $this->redirect($this->Auth->redirectUrl(array('controller' => 'admin', 'action' => 'dashboard')));
                } else if ($this->Auth->User('role') == 'Society') {
                    $this->redirect($this->Auth->redirectUrl(array('controller' => 'societys', 'action' => 'dashboard')));
                } else if ($this->Auth->User('role') == 'Reseller') {
                    $this->redirect(array('controller' => 'resellers', 'action' => 'assigned_societys'));
                } else {
                    $this->redirect($this->Auth->redirectUrl());
                }
            }
            
        }
    }





}

