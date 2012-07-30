<?php

/**
 * User's controller
 *
 * @author Ionut Cristian Ciuperca <ionut.ciuperca@gmail.com>
 */
require_once SYSTEMFOLDER . '/controller.php';
require_once SYSTEMFOLDER . '/session.php';
require_once SYSTEMFOLDER . '/url.php';

require_once MODELS . '/user_model.php';

class User extends Controler {

    private $userModel;
    private $config;

    public function __construct() {
        parent::__construct();

        $this->userModel = new User_Model();
        $config = new Config();
        $this->config = $config->getSection('url');
    }

    public function loginAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userdata = $this->userModel->checkPass($username, $password);
            if ($userdata === false) {

                $this->session->setFlash('error', 'Wrong username or password');
                Url::redirect('/user/login');
            } else {

                $this->session->setItem('user', $userdata);
                $this->session->setFlash('success', 'You have logged in.');
                Url::redirect($this->config['default_url']);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data['title'] = 'User login';
            $hasError = $this->session->hasFlash('error');
            if($hasError) {
                $data['errors'] = $this->session->getFlash('error');
            }
            $this->render('app/templates/users/login.php', $data);
        }
    }

    public function logoutAction() {
        if($this->session->itemExists('user')) {
            $this->session->removeItem('user');
        }
        Url::redirect($this->config['default_url']);
    }

}

?>
