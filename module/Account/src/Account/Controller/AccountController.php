<?php

namespace Account\Controller;

use Zend\View\Model\ViewModel;
use Controller\CustomControllerAction;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Model\DatabaseObject\DatabaseObjectUser as User;

class AccountController extends CustomControllerAction 
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        $uid = (int) $this->getRequest()->getQuery('uid');

        if($this->_auth->hasIdentity()) {   //logged in
        }

        return new ViewModel(array(
            'authenticated' => false,
            'section' => 'home',
        ));
    }

    public function loginAction()
    {
        // if a user's already logged in, send them to their account home page
        if ($this->_auth->hasIdentity())
            return $this->redirect()->toRoute('home');

        $request = $this->getRequest();

        // determine the page the user was originally trying to request
        $redirect = $request->getPost('redirect');
        //if (strlen($redirect) == 0)
            //$redirect = $request->getServer('REQUEST_URI');
        if (strlen($redirect) == 0)
            $redirect = 'home';

        // initialize errors
        $errors = array('username' => "", 'password' => "");

        // process login if request method is post
        if ($request->isPost()) {

            // fetch login details from form and validate them
            $username = $request->getPost('username');
            $password = $request->getPost('password');

            if (strlen($username) == 0)
                $errors['username'] = 'Required field must not be blank';
            if (strlen($password) == 0)
                $errors['password'] = 'Required field must not be blank';

            if (strlen($username) != 0 and strlen($password) != 0) {
                // setup the authentication adapter
                $authAdapter = new AuthAdapter($this->_db,
                               'users',
                               'username',
                               'password',
                               'md5(?)');

                $authAdapter->setIdentity($username);
                $authAdapter->setCredential($password);

                // try and authenticate the user
                $result = $this->_auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    $user = new User($this->_db);
                    $user->load($authAdapter->getResultRowObject()->user_id);

                    // record login attempt
                    $user->loginSuccess();

                    // create identity data and write it to session
                    $identity = $user->createAuthIdentity();
                    $this->_auth->getStorage()->write($identity);

                    // send user to page they originally request
                    return $this->redirect()->toRoute($redirect);
                }

                // record failed login attempt
                User::LoginFailure($username, $result->getCode());
                $errors['username'] = 'Your login details were invalid';
            }
        }

        return new ViewModel(array(
            'authenticated' => false,
            'section' => 'home',
            'error' => $errors,
            'redirect' => $redirect,
        ));
    }

    public function logoutAction()
    {
        $this->_auth->clearIdentity();

        return $this->redirect()->toRoute('home');
    }

    public function fetchpasswordAction()
    {
    }
}
