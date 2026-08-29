<?php

App::uses('AppController', 'Controller');

class RegistersController extends AppController {

    public $uses = array();

    public function index() {
        $this->redirect(array('action' => 'fd_register'));
    }

    public function fd_register() {
    }

    public function shares_register() {
    }

    public function lien_register() {
    }

    public function nominee_register() {
    }

    public function form_i() {
    }

    public function form_j() {
    }
}
