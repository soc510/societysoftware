<?php

App::uses('AppController', 'Controller');

/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */

   use Cake\Core\Plugin;
   use Cake\Routing\RouteBuilder;
   use Cake\Routing\Router;


class ResellersController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator', 'Session', 'RequestHandler','Util');
    public $helpers = array('Js');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'society_dashboard';

    }

    public function dashboard() {        
        //echo $this->Session->read('Auth.User.username');
        //die;
    }
    
    public function index(){        
    }
    
    public function update_profiles() {
        $this->loadModel('User');
        $this->loadModel('Country');
        $this->loadModel('State');
        $resellerId = $this->Session->read('Auth.User.id');
        if (!$this->User->exists($resellerId)) {
            throw new NotFoundException(__('Invalid reseller'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['Reseller']['user_id'] = $resellerId;
            $options = array('conditions' => array('Reseller.user_id' => $resellerId));
            $sellerInfo = $this->Reseller->find('first', $options);
            if (isset($sellerInfo) && !empty($sellerInfo)) {
                $editID = isset($sellerInfo['Reseller']['id']) ? $sellerInfo['Reseller']['id'] : '';
                $this->Reseller->id = $editID;
            }
            
            if ($this->Reseller->save($this->request->data)) {
                $this->Session->setFlash(__('The reseller profile info has been saved.'), 'success');
            } else {
                $this->Session->setFlash(__('The reseller could not be saved. Please, try again.'), 'error');
            }
        } else {
            $options = array('conditions' => array('Reseller.user_id' => $resellerId));
            $this->request->data = $this->Reseller->find('first', $options);
        }
        $countryList = $this->Country->find('list', array('fields' => array('country_id', 'country_name'), 'order' => array('Country.country_name' => 'asc')));
        $stateList = $this->State->find('list', array('fields' => array('state_id', 'state_name'), 'order' => array('State.state_name' => 'asc')));
        //print_r($stateList);die;
        
        $this->set(compact('countryList', 'stateList'));
    }

    public function assigned_societys($resellerID = null){
        $this->loadModel('FinancialYearMaster');
      
        $resellerID = AuthComponent::user('id');
        if($resellerID == ''){
            throw new NotFoundException(__('Invalid request'));
        }
        $this->loadModel('ResellerSociety');
        $conditions = array('ResellerSociety.reseller_id' => $resellerID);
        $fields = array('User.id','User.username','User.password','Society.user_id','Society.society_name','Society.society_code','Society.udate');
        $assignedSocietysList = $this->ResellerSociety->find('all',array('fields'=>$fields,'conditions' =>$conditions,'order' => array('society_name' => 'asc')));
        if ($this->request->is(array('post', 'put'))) {
            $resellerData = $this->Session->read('Auth.User');
            $societyId = $this->request->data['society_id'];
            $yearId = $this->request->data['finacial_year_id'];
            $financialYears = $this->FinancialYearMaster->find('first',array('conditions' => array('FinancialYearMaster.is_active' => 1,'FinancialYearMaster.id'=>$yearId)));
            if(!empty($financialYears)){
                $fromDate = $financialYears['FinancialYearMaster']['year_start_date'];
                $toDate = $financialYears['FinancialYearMaster']['year_end_date'];
                $yearId = $financialYears['FinancialYearMaster']['id'];
                $this->Session->write('Auth.year_id',$yearId);
                $this->Session->write('Auth.year_start_date',$fromDate);
                $this->Session->write('Auth.year_end_date',$toDate);
                $this->Session->write('Auth.formated_year_start_date',date('d/m/Y',strtotime($fromDate)));
                $this->Session->write('Auth.formated_year_end_date',date('d/m/Y',strtotime($toDate)));
                $this->Session->write('Auth.start_year',date('Y',strtotime($fromDate)));
                $this->Session->write('Auth.end_year',date('Y',strtotime($toDate)));                
                $this->Session->write('Auth.financial_years_arr',array($fromDate,$toDate));
            }
        

            $sql = "select id,society_code,society_name,user_id as username from societies where id = $societyId";
            $societyData = $this->getRawQueryResult($sql);
            if(!empty($societyData)){
                $societyUserData = [
                    'id' => $societyData[0]['societies']['id'],
                    'username' => $societyData[0]['societies']['username'],
                    'society_name' => $societyData[0]['societies']['society_name'],
                    'role' => 'Society',
                    'access_level' => 2
                    ];
                $this->Session->write('Auth.User',$societyUserData);
                if(empty($this->Session->read('Auth.Reseller'))){
                    $this->Session->write('Auth.reseller_Flag',1);
                    $this->Session->write('Auth.Reseller',$resellerData);    
                }
            }

            $this->redirect(array('controller' => 'societys','action'=>'dashboard'));
        }

        $this->set(compact('assignedSocietysList'));
        
        //get all assigned societies list by admin
    }
    
    public function create_society(){

            $checkUserNameFlag = true;
            
            $this->loadModel('User');

            $userModelObj = ClassRegistry::init('User');

            $aData['username'] = isset($this->request->data['Society']['society_name']) ? $this->request->data['Society']['society_name'] : '';
            $aData['password'] = '12345' ;  // default password set 
            $aData['cdate'] = $this->Util->getDateTime();
            $aData['added_by'] = $this->Session->read('Auth.User.id');
            $aData['access_level'] = 2; // society level access
            $aData['role'] =  $this->Util->getUserRole($aData['access_level']) ;
            $aData['status'] = Configure::read('Active');
            $aData['udate'] = $this->Util->getDateTime();


            if ($userModelObj->isUniqueUsername($aData)) {
                $checkUserNameFlag = false;
            }

            if ($checkUserNameFlag) {
                $userSaveData = $this->User->save($aData);

                if ($userSaveData) {
                    $societyUserID = $userSaveData['User']['id'] ;
                    $this->insertIntoSocietys($societyUserID);
                }

            }
    }
    
   public function insertIntoSocietys($societyUserID = null) {
       $this->loadModel('Society');
       $this->loadModel('ResellerSociety');
        $response = array();
        $response['error'] = 1;
        $response['error_message'] = 'Opps! Somthing went wrong.';
        $resellerId = $this->Session->read('Auth.User.id');

        if ($this->request->data) {
            $this->request->data['Society']['user_id'] = $societyUserID;
            $this->request->data['Society']['id'] = $societyUserID;
            $saveUser = $this->Society->save($this->request->data['Society']);
            if ($saveUser) {
                
                $conditions = array('ResellerSociety.societie_id' => $societyUserID, 'ResellerSociety.reseller_id' => $resellerId, 'ResellerSociety.added_by' => $resellerId);
                $resellerData = [
                    'societie_id' => $societyUserID,
                    'user_id' => $societyUserID,
                    'reseller_id' => $resellerId,
                    'added_by' => $resellerId,
                    'cdate' =>$this->Util->getDateTime()
                    
                    ];
                    
                if (!$this->ResellerSociety->hasAny($conditions)) {
                    $this->ResellerSociety->save($resellerData);
                }
                
                $this->Session->setFlash(__('Society Created Successfully.'),'success');
                $this->redirect(array('action' => 'create_society'));
            }
            else{
                $this->Session->setFlash(__('Failed To Add Society Created.'),'error');
                $this->redirect(array('action' => 'create_society'));
            }
        }
    } 

    public function society_finance_year_mapping() {
           $this->loadModel('FinancialYearMaster');
           $this->loadModel('SocietyFinancialYearMapping');
           
           $this->loadModel('Society');
           $resellerId = $this->Session->read('Auth.User.id');
           $financialYears = $this->FinancialYearMaster->find('all',array('conditions' => array('FinancialYearMaster.is_active',1)));
           
           $sql = "select soc.id as id,soc.society_name from reseller_societies rs join societies soc on rs.societie_id = soc.id where reseller_id = $resellerId and soc.status = 1";
           $db = ConnectionManager::getDataSource('default');
           $societydata = $db->query($sql);

        if ($this->request->is(array('post', 'put'))) {
            $societyId = $this->request->data['society_id'];
            $yearId = $this->request->data['financial_year_id'];
            $insertData = [
                'society_id' => $societyId,
                'year_id' => $yearId
            ];
            
            if ($this->SocietyFinancialYearMapping->save($insertData)) {
                $this->Session->setFlash(__('Society Mapped Successfully.'),'success');
            }
            else{
                $this->Session->setFlash(__('Failed To Mapped Society.'),'error');
            }
            
        }

        $this->request->data['year_list'] = $financialYears;
        $this->set(compact('societydata'));
  }
  
  function getAssignedsociety(){
           $this->loadModel('Society');
           $searchText = $this->request->query('search');
           $resellerId = $this->Session->read('Auth.User.id');
           $queryString = "";
           if(!empty($searchText)){
               $queryString = " and society_name like '%$searchText%'";
           }
           $sql = "select id,society_name as text from societies where user_id = $resellerId".$queryString;
           $db = ConnectionManager::getDataSource('default');
           $societydata = $db->query($sql);  
           $result = [];
           if(!empty($societydata)){
               foreach($societydata as $data){
                   array_push($result,$data['societies']);
               }
           }
      
      /* $result = [
          [
              'id' => 1,
              'text' =>'option 1'
          ],
          [
              'id' => 1,
              'text' =>'option 1'              
          ],
          ];*/
      
      echo json_encode(array('results'=>$result));
      die;
  }
  
  public function getAssignedYear(){
      $this->loadModel('SocietyFinancialYearMapping');
      $this->loadModel('FinancialYearMaster');
      $searchId = $this->request->query('soc_id');
      $societyFilterYear = $this->SocietyFinancialYearMapping->find('all',array('conditions'=> array('SocietyFinancialYearMapping.society_id'=> $searchId)));
      $yearId  = [];
      if(!empty($societyFilterYear)){
            foreach($societyFilterYear as $yeardata){
                array_push($yearId,$yeardata['SocietyFinancialYearMapping']['year_id']);
            }
      }
      $financialYears = $this->FinancialYearMaster->find('all',array('conditions' => array('FinancialYearMaster.is_active' => 1,'FinancialYearMaster.id' => $yearId)));
      echo json_encode($financialYears);die;
  }
  
  public function getRawQueryResult($sql){
    $db = ConnectionManager::getDataSource('default');
    return $db->query($sql);
  }

    public function manage_users() {
        $this->loadModel('User');
        $this->loadModel('ResellerSubUser');
        $resellerId = $this->Session->read('Auth.User.id');

        $subUsers = $this->ResellerSubUser->find('all', array(
            'conditions' => array('ResellerSubUser.reseller_id' => $resellerId),
            'contain' => array('User'),
            'order' => array('ResellerSubUser.created' => 'DESC')
        ));

        if (empty($subUsers)) {
            $subUserIds = array();
            $subUsers = array();
        } else {
            $subUserIds = array_map(function($s) { return $s['ResellerSubUser']['user_id']; }, $subUsers);
        }

        $userMap = array();
        if (!empty($subUserIds)) {
            $users = $this->User->find('all', array(
                'conditions' => array('User.id' => $subUserIds),
                'fields' => array('User.id', 'User.username', 'User.status'),
                'recursive' => -1
            ));
            foreach ($users as $u) {
                $userMap[$u['User']['id']] = $u['User'];
            }
        }

        $this->set(compact('subUsers', 'userMap'));
    }

    public function add_user() {
        $this->loadModel('User');
        $this->loadModel('ResellerSubUser');
        $resellerId = $this->Session->read('Auth.User.id');

        if ($this->request->is('post')) {
            $username = trim($this->request->data['User']['username']);
            $password = $this->request->data['User']['password'];

            if (empty($username) || empty($password)) {
                $this->Session->setFlash(__('Username and Password are required.'), 'error');
                return $this->redirect(array('action' => 'add_user'));
            }

            $existing = $this->User->find('first', array(
                'conditions' => array('User.username' => $username),
                'recursive' => -1
            ));
            if (!empty($existing)) {
                $this->Session->setFlash(__('This username already exists. Please choose a different username.'), 'error');
                return $this->redirect(array('action' => 'add_user'));
            }

            $this->User->create();
            $userData = array('User' => array(
                'username' => $username,
                'password' => $password,
                'role' => 'SubReseller',
                'access_level' => 3,
                'added_by' => $resellerId,
                'cdate' => date('Y-m-d H:i:s'),
                'udate' => date('Y-m-d H:i:s'),
                'status' => 1
            ));

            if ($this->User->save($userData)) {
                $newUserId = $this->User->getLastInsertID();

                $this->ResellerSubUser->create();
                $permData = array('ResellerSubUser' => array(
                    'reseller_id' => $resellerId,
                    'user_id' => $newUserId,
                    'can_add' => isset($this->request->data['Permission']['can_add']) ? 1 : 0,
                    'can_edit' => isset($this->request->data['Permission']['can_edit']) ? 1 : 0,
                    'can_delete' => isset($this->request->data['Permission']['can_delete']) ? 1 : 0,
                    'status' => 1,
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s')
                ));
                $this->ResellerSubUser->save($permData);

                $this->Session->setFlash(__('User created successfully.'), 'success');
                return $this->redirect(array('action' => 'manage_users'));
            } else {
                $this->Session->setFlash(__('Failed to create user. Please try again.'), 'error');
            }
        }
    }

    public function edit_user($id = null) {
        $this->loadModel('User');
        $this->loadModel('ResellerSubUser');
        $resellerId = $this->Session->read('Auth.User.id');

        if (empty($id)) {
            $this->Session->setFlash(__('Invalid user.'), 'error');
            return $this->redirect(array('action' => 'manage_users'));
        }

        $subUser = $this->ResellerSubUser->find('first', array(
            'conditions' => array('ResellerSubUser.id' => $id, 'ResellerSubUser.reseller_id' => $resellerId),
            'recursive' => -1
        ));
        if (empty($subUser)) {
            $this->Session->setFlash(__('User not found.'), 'error');
            return $this->redirect(array('action' => 'manage_users'));
        }

        $userData = $this->User->find('first', array(
            'conditions' => array('User.id' => $subUser['ResellerSubUser']['user_id']),
            'fields' => array('User.id', 'User.username', 'User.status'),
            'recursive' => -1
        ));

        if ($this->request->is(array('post', 'put'))) {
            $updateData = array();
            if (!empty($this->request->data['User']['password'])) {
                $updateData['User']['password'] = $this->request->data['User']['password'];
            }
            if (isset($this->request->data['User']['status'])) {
                $updateData['User']['status'] = $this->request->data['User']['status'];
            }
            $updateData['User']['udate'] = date('Y-m-d H:i:s');

            if (!empty($updateData)) {
                $this->User->id = $subUser['ResellerSubUser']['user_id'];
                $this->User->save($updateData);
            }

            $this->ResellerSubUser->id = $id;
            $permData = array('ResellerSubUser' => array(
                'can_add' => isset($this->request->data['Permission']['can_add']) ? 1 : 0,
                'can_edit' => isset($this->request->data['Permission']['can_edit']) ? 1 : 0,
                'can_delete' => isset($this->request->data['Permission']['can_delete']) ? 1 : 0,
                'modified' => date('Y-m-d H:i:s')
            ));
            $this->ResellerSubUser->save($permData);

            $this->Session->setFlash(__('User updated successfully.'), 'success');
            return $this->redirect(array('action' => 'manage_users'));
        }

        $this->set(compact('subUser', 'userData'));
    }

    public function delete_user($id = null) {
        $this->loadModel('User');
        $this->loadModel('ResellerSubUser');
        $resellerId = $this->Session->read('Auth.User.id');

        $subUser = $this->ResellerSubUser->find('first', array(
            'conditions' => array('ResellerSubUser.id' => $id, 'ResellerSubUser.reseller_id' => $resellerId),
            'recursive' => -1
        ));
        if (empty($subUser)) {
            $this->Session->setFlash(__('User not found.'), 'error');
            return $this->redirect(array('action' => 'manage_users'));
        }

        $this->User->id = $subUser['ResellerSubUser']['user_id'];
        $this->User->saveField('status', 0);

        $this->ResellerSubUser->id = $id;
        $this->ResellerSubUser->saveField('status', 0);

        $this->Session->setFlash(__('User deactivated successfully.'), 'success');
        return $this->redirect(array('action' => 'manage_users'));
    }

}