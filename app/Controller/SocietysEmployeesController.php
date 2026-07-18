<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SocietysEmployeesController extends AppController {

    public $components = array('Paginator', 'Session', 'RequestHandler', 'Util','SocietyBill');
    public $helpers = array('Js');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'society_dashboard';
        $this->loadModel('EmployeeSubCategory');
        $this->loadModel('EmployeeCategory');
        $this->loadModel('Employee');
        $this->loadAllNavigationModals();
    }

    public function dashboard() {
        //echo $this->Session->read('Auth.User.username');
        //die;
    }
    
    function loadAllNavigationModals(){
        $this->SocietyBill->getSocietyBuildingLists();
        $this->SocietyBill->getSocietyLedgerHeadsDetails();
        $this->SocietyBill->navigateSocietyMemberDetails();
        $this->SocietyBill->loadAllNavigationModalsDropdowns();
    }

    public function index() {
        
    }

    public function employee_details() {
        $societyloginID = $this->Session->read('Auth.User.id');
        $buildingEmployeeData = $this->Employee->find('all', array('conditions' => array('Employee.status' => Configure::read('Active'),'Employee.society_id' => $societyloginID), 'fields' => array('Employee.emp_name','Employee.id','Employee.joining_date','Employee.marrital_status', 'Employee.religion','Employee.date_of_leaving','EmployeeCategory.emp_category_name'), 'order' => array('Employee.emp_name' => 'asc')));
        $this->set(compact('buildingEmployeeData'));
    }
    
    public function add_employee_details($employeeId = NULL) {
        $societyloginID = $this->Session->read('Auth.User.id');

        if ($this->request->is(array('get'))) {
            $this->request->data = $this->Employee->find('first', array('conditions' => array('Employee.id' => $employeeId)));
        }
        if ($this->request->is('post', 'put')) {
            $this->request->data['Employee']['society_id'] = $this->Session->read('Auth.User.id');
            $postData['society_id'] = isset($societyloginID) ? $societyloginID : 0;
            $postData['id'] = isset($this->request->data['Employee']['id']) ? $this->request->data['Employee']['id'] : '';
            $postData['emp_name'] = isset($this->request->data['Employee']['emp_name']) ? $this->request->data['Employee']['emp_name'] : '';
            $postData['emp_code'] = isset($this->request->data['Employee']['emp_code']) ? $this->request->data['Employee']['emp_code'] : '';
            $postData['emp_category_id'] = isset($this->request->data['Employee']['emp_category_id']) ? $this->request->data['Employee']['emp_category_id'] : '';
            $postData['emp_sub_category_id'] = isset($this->request->data['Employee']['emp_sub_category_id']) ? $this->request->data['Employee']['emp_sub_category_id'] : 0;
            $postData['joining_date'] = isset($this->request->data['Employee']['joining_date']) ? $this->request->data['Employee']['joining_date'] : 0;
            $postData['gender'] = isset($this->request->data['Employee']['gender']) ? $this->request->data['Employee']['gender'] : '';
            $postData['date_of_birth'] = isset($this->request->data['Employee']['date_of_birth']) ? $this->request->data['Employee']['date_of_birth'] : '';
            $postData['marrital_status'] = isset($this->request->data['Employee']['marrital_status']) ? $this->request->data['Employee']['marrital_status'] : '';
            $postData['date_of_leaving'] = isset($this->request->data['Employee']['date_of_leaving']) ? $this->request->data['Employee']['date_of_leaving'] : 0;
            $postData['religion'] = isset($this->request->data['Employee']['religion']) ? $this->request->data['Employee']['religion'] : '';
            $postData['qualification'] = isset($this->request->data['Employee']['qualification']) ? $this->request->data['Employee']['qualification'] : '';
            $postData['pan_no'] = isset($this->request->data['Employee']['pan_no']) ? $this->request->data['Employee']['pan_no'] : '';
            $postData['gstin_no'] = isset($this->request->data['Employee']['gstin_no']) ? $this->request->data['Employee']['gstin_no'] : '';
            $postData['udate'] = $this->Util->getDateTime();
            if ($this->Employee->save($postData)) {
                if (isset($this->request->data['Employee']['emp_photo']['name']) && !empty($this->request->data['Employee']['emp_photo']['name'])) {
                    $lastInsertId = $this->Employee->getInsertID();
                    $errorResponce = $this->saveUpdateEmployeeProfilePhoto($this->request->data['Employee']['emp_photo'], $lastInsertId);
                    if ($errorResponce != '') {
                        $this->Session->setFlash(__($errorResponce), 'error');
                        $this->redirect(array('controller' => 'societys_employees', 'action' => 'employee_details'));
                    }
                }
                $this->Session->setFlash(__('Society employee data saved successfully.'), 'success');
                $this->redirect(array('controller' => 'societys_employees', 'action' => 'employee_details'));
            } else {
                $this->Session->setFlash(__('The society employee could not be saved .'), 'error');
            }
        }
        $employeeCategoryDropDownList = $this->EmployeeCategory->find('list', array('conditions' => array('EmployeeCategory.status' => Configure::read('Active'), 'EmployeeCategory.society_id' => $societyloginID), 'fields' => array('EmployeeCategory.id', 'EmployeeCategory.emp_category_name')));
        $employeeSubCategoryDropDownList = $this->EmployeeSubCategory->find('list', array('conditions' => array('EmployeeSubCategory.status' => Configure::read('Active'), 'EmployeeSubCategory.society_id' => $societyloginID), 'fields' => array('EmployeeSubCategory.id', 'EmployeeSubCategory.emp_sub_category_name')));
        $this->set(compact('employeeCategoryDropDownList', 'employeeSubCategoryDropDownList'));
    }

    public function employee_categories($employeeCategoryId = null) {
        $societyloginID = $this->Session->read('Auth.User.id');
        $emplopyeeCategoryList = $this->EmployeeCategory->find('all', array('conditions' => array('EmployeeCategory.status' => Configure::read('Active'), 'EmployeeCategory.society_id' => $societyloginID), 'fields' => array('EmployeeCategory.society_id', 'EmployeeCategory.id', 'EmployeeCategory.emp_category_name'), 'order' => array('EmployeeCategory.emp_category_name' => 'asc')));
        $message = "added";
        if ($this->request->is(array('get'))) {
            $this->request->data = $this->EmployeeCategory->find('first', array('fields' => array('EmployeeCategory.society_id', 'EmployeeCategory.id', 'EmployeeCategory.emp_category_name'), 'conditions' => array('EmployeeCategory.id' => $employeeCategoryId)));
        }
        if ($this->request->is('post','put')) {
            $this->request->data['EmployeeCategory']['id'] = isset($this->request->data['EmployeeCategory']['id']) ? $this->request->data['EmployeeCategory']['id'] : null;
            $this->request->data['EmployeeCategory']['society_id'] = $societyloginID;
            $this->request->data['EmployeeCategory']['cdate'] = $this->Util->getDateTime();
            $this->request->data['EmployeeCategory']['udate'] = $this->Util->getDateTime();
            if ($this->EmployeeCategory->save($this->request->data)) {
                $this->Session->setFlash(__('The Category has been ' . $message . '.'), 'success');
            } else {
                $this->Session->setFlash(__('The Category could not be saved. Please, try again.'), 'error');
            }
            $this->redirect(array('action' => 'employee_categories'));
        }
        $this->set(compact('emplopyeeCategoryList'));
    }

    public function employee_sub_categories($employeeSubCategoryId = null) {
        $societyloginID = $this->Session->read('Auth.User.id');
        $employeeCategoryData = $this->EmployeeCategory->find('list', array('conditions' => array('EmployeeCategory.status' => Configure::read('Active'), 'EmployeeCategory.society_id' => $societyloginID), 'fields' => array('EmployeeCategory.id', 'EmployeeCategory.emp_category_name')));
        $this->set('employeeCategoryData', $employeeCategoryData);
        $employeeSubCategoryList = $this->EmployeeSubCategory->find('all', array('conditions' => array('EmployeeSubCategory.status' => Configure::read('Active'), 'EmployeeSubCategory.society_id' => $societyloginID), 'fields' => array('EmployeeSubCategory.id', 'EmployeeCategory.emp_category_name', 'EmployeeSubCategory.society_id', 'EmployeeSubCategory.emp_category_id', 'EmployeeSubCategory.emp_sub_category_name'), 'order' => array('EmployeeSubCategory.emp_sub_category_name' => 'asc')));
        $message = "added";
        if ($this->request->is(array('get'))) {
            $this->request->data = $this->EmployeeSubCategory->find('first', array('fields' => array('EmployeeSubCategory.society_id', 'EmployeeSubCategory.id', 'EmployeeSubCategory.emp_category_id', 'EmployeeSubCategory.emp_sub_category_name'), 'conditions' => array('EmployeeSubCategory.id' => $employeeSubCategoryId)));
        }

        if ($this->request->is('post', 'put')) {
            $this->request->data['EmployeeSubCategory']['id'] = isset($this->request->data['EmployeeSubCategory']['id']) ? $this->request->data['EmployeeSubCategory']['id'] : null;
            $this->request->data['EmployeeSubCategory']['society_id'] = $societyloginID;
            $this->request->data['EmployeeSubCategory']['cdate'] = $this->Util->getDateTime();
            $this->request->data['EmployeeSubCategory']['udate'] = $this->Util->getDateTime();
            if ($this->EmployeeSubCategory->save($this->request->data)) {
                $this->Session->setFlash(__('The Sub-Category has been ' . $message . '.'), 'success');
            } else {
                $this->Session->setFlash(__('The Sub-Category could not be saved. Please, try again.'), 'error');
            }
            $this->redirect(array('action' => 'employee_sub_categories'));
        }
        $this->set(compact('employeeSubCategoryList'));
    }
    
    public function delete_employee_categories($employeeCategoryId = null) {
        if (!$this->EmployeeCategory->exists($employeeCategoryId)) {
            throw new NotFoundException(__('Requested Employee Categories is not found.'));
        }
        $this->request->allowMethod('post', 'delete');
        $this->EmployeeCategory->id = $employeeCategoryId;
        if ($this->EmployeeCategory->saveField('status', Configure::read('InActive'))) {
            $this->Session->setFlash(__('The Employee Categories has been deleted.'), 'success');
        } else {
            $this->Session->setFlash(__('The Employee Categories could not be deleted. Please, try again.'), 'error');
        }
        return $this->redirect(array('action' => 'employee_categories'));
    }
    
    public function delete_employee_sub_categories($employeeSubCategoryId=null){
        if (!$this->EmployeeSubCategory->exists($employeeSubCategoryId)) {
            throw new NotFoundException(__('Requested Employee Categories is not found.'));
        }
        $this->request->allowMethod('post', 'delete');
        $this->EmployeeSubCategory->id = $employeeSubCategoryId;
        if ($this->EmployeeSubCategory->saveField('status', Configure::read('InActive'))) {
            $this->Session->setFlash(__('The Employee Categories has been deleted.'), 'success');
        } else {
            $this->Session->setFlash(__('The Employee Categories could not be deleted. Please, try again.'), 'error');
        }
        return $this->redirect(array('action' => 'employee_categories'));
    }
    
    public function saveUpdateEmployeeProfilePhoto($filesData, $societyEmployeeId) {
        $target_dir = "societyEmployees/";
        $error = '';
        if (isset($filesData['tmp_name']) && !empty($filesData['tmp_name'])) {
            $target_file = $target_dir . basename($filesData["name"]);
            $imgExt = pathinfo($target_file, PATHINFO_EXTENSION);
            $uniqueSavename = uniqid(rand()) . '.' . $imgExt;
            $fileName = isset($uniqueSavename) ? $societyEmployeeId . '_' . $uniqueSavename : $societyEmployeeId . '_' . trim($filesData["name"]);
            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
            $imageSize = $filesData["size"];
            $tmpDir = $filesData["tmp_name"];

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777);
            }
            if (in_array($imgExt, $valid_extensions)) {
                if ($imageSize < 5000000) {
                    move_uploaded_file($tmpDir, $target_dir . $fileName);
                    $this->Employee->updateAll(array("Employee.emp_photo" => "'" . $target_dir . $fileName . "'"), array("Employee.id" => $societyEmployeeId));
                } else {
                    $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }
            } else {
                $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }
        return $error;
    }
}
