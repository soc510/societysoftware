<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail',    'Network/Email');

class EmailsController extends AppController {

    public $components = ['Session'];
    
    public function demo() {
        echo "hi";
    }

    public function test() {

        App::uses('CakeEmail', 'Network/Email');

        $email = new CakeEmail();

        $email->transport('Smtp')
            ->config([
                'host'     => 'smtp.hostinger.com', // or ssl://localhost
                'port'     => 465,
                'username' => 'admin@easylogicstechnology.com',
                'password' => 'AdminEmail@#2026',
                'timeout'  => 30,
                'log'      => true
            ])
            ->from(['admin@easylogicstechnology.com' => 'EasyLogics'])
            ->to('deepbase.co@gmail.com')
            ->subject('Test Email from CakePHP 2.10')
            ->emailFormat('html');

        $this->autoRender = false;

        try {
            $email->send('This is a test email sent via SMTP.');
            echo "✅ Email sent successfully.";
        } catch (SocketException $e) {
            echo "❌ Email sending failed. Error: " . $e->getMessage();
        }

        die;
    }

}
