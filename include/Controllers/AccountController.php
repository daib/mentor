<?php
    class AccountController extends CustomControllerAction
    {
        public function init()
        {
            parent::init();
            $this->breadcrumbs->addStep('Account', $this->getUrl(null, 'account'));
            $this->identity = Zend_Auth::getInstance()->getIdentity();
        }

        public function indexAction()
        {
            // nothing to do here, index.tpl will be displayed
        }

        public function avatarAction()
        {
            $dest_dir = "uploads/";
 
            /* Uploading Document File on Server */
            $upload = new Zend_File_Transfer_Adapter_Http();
            $upload->setDestination($dest_dir)
                         ->addValidator('Count', false, 1)
                         ->addValidator('Size', false, 1048576)
                         ->addValidator('Extension', false, 'jpg,png,gif,pdf');
            $files = $upload->getFileInfo();
 
            // debug mode [start]
            echo '<hr />
                            <pre>';
            print_r($files);
            echo '  </pre>
                        <hr />';
            // debug mode [end]
 
            try
            {
                // upload received file(s)
                $upload->receive();
            }
            catch (Zend_File_Transfer_Exception $e)
            {
                $e->getMessage();
                exit;
            }
 
            $mime_type = $upload->getMimeType('doc_path');
            $fname = $upload->getFileName('doc_path');
            $size = $upload->getFileSize('doc_path');
            $file_ext = $this->getFileExtension($fname);
            $new_file = $dest_dir.md5(mktime()).'.'.$file_ext;
 
            $filterFileRename = new Zend_Filter_File_Rename(
                array(
                    'target' => $new_file, 'overwrite' => true
            ));
 
            $filterFileRename->filter($fname);
 
            if (file_exists($new_file))
            {
                $request = $this->getRequest();
                $caption = $request->getParam('caption');
 
                $html = 'Orig Filename: '.$fname.'<br />';
                $html .= 'New Filename: '.$new_file.'<br />';
                $html .= 'File Size: '.$size.'<br />';
                $html .= 'Mime Type: '.$mime_type.'<br />';
                $html .= 'Caption: '.$caption.'<br />';
            }
            else
            {
                $html = 'Unable to upload the file!';
            }
        }
        
        public function friendAction()
        {
            $to_id = (int) $this->getRequest()->getPost('uid');
            $from_id = $this->identity->user_id;

            $relation = new DatabaseObject_Relation($this->db);
            $status = $relation->checkStatus($from_id, $to_id);

            if($status == 'nonexisted') {
                $relation->makeRequest($from_id, $to_id);
            }
        }


        public function registerAction()
        {
            $request = $this->getRequest();

            $fp = new FormProcessor_UserRegistration($this->db);
            $validate = $request->isXmlHttpRequest();

            if ($request->isPost()) {
                if ($validate) {
                    $fp->validateOnly(true);
                    $fp->process($request);
                }
                else if ($fp->process($request)) {
                    $session = new Zend_Session_Namespace('registration');
                    $session->user_id = $fp->user->getId();
                    $this->_redirect($this->getUrl('registercomplete'));
                }
            }

            if ($validate) {
                $json = array(
                    'errors' => $fp->getErrors()
                );
                $this->sendJson($json);
            }
            else {
                $this->breadcrumbs->addStep('Create an Account');
                $this->view->fp = $fp;
            }
        }

        public function registercompleteAction()
        {
            // retrieve the same session namespace used in register
            $session = new Zend_Session_Namespace('registration');

            // load the user record based on the stored user ID
            $user = new DatabaseObject_User($this->db);
            if (!$user->load($session->user_id)) {
                $this->_forward('register');
                return;
            }

            $this->breadcrumbs->addStep('Create an Account',
                                        $this->getUrl('register'));
            $this->breadcrumbs->addStep('Account Created');
            $this->view->user = $user;
        }

        public function loginAction()
        {
            // if a user's already logged in, send them to their account home page
            $auth = Zend_Auth::getInstance();

            if ($auth->hasIdentity())
                $this->_redirect($this->getUrl());

            $request = $this->getRequest();

            // determine the page the user was originally trying to request
            $redirect = $request->getPost('redirect');
            if (strlen($redirect) == 0)
                $redirect = $request->getServer('REQUEST_URI');
            if (strlen($redirect) == 0)
                $redirect = $this->getUrl();

            // initialize errors
            $errors = array();

            // process login if request method is post
            if ($request->isPost()) {

                // fetch login details from form and validate them
                $username = $request->getPost('username');
                $password = $request->getPost('password');

                if (strlen($username) == 0)
                    $errors['username'] = 'Required field must not be blank';
                if (strlen($password) == 0)
                    $errors['password'] = 'Required field must not be blank';

                if (count($errors) == 0) {

                    // setup the authentication adapter
                    $adapter = new Zend_Auth_Adapter_DbTable($this->db,
                                                             'users',
                                                             'username',
                                                             'password',
                                                             'md5(?)');

                    $adapter->setIdentity($username);
                    $adapter->setCredential($password);

                    // try and authenticate the user
                    $result = $auth->authenticate($adapter);

                    if ($result->isValid()) {
                        $user = new DatabaseObject_User($this->db);
                        $user->load($adapter->getResultRowObject()->user_id);

                        // record login attempt
                        $user->loginSuccess();

                        // create identity data and write it to session
                        $identity = $user->createAuthIdentity();
                        $auth->getStorage()->write($identity);

                        // send user to page they originally request
                        $this->_redirect($redirect);
                    }

                    // record failed login attempt
                    DatabaseObject_User::LoginFailure($username,
                                                      $result->getCode());
                    $errors['username'] = 'Your login details were invalid';
                }
            }

            $this->breadcrumbs->addStep('Login');
            $this->view->errors = $errors;
            $this->view->redirect = $redirect;
        }

        public function logoutAction()
        {
            Zend_Auth::getInstance()->clearIdentity();
            $this->_redirect($this->getUrl('login'));
        }

        public function fetchpasswordAction()
        {
            // if a user's already logged in, send them to their account home page
            if (Zend_Auth::getInstance()->hasIdentity())
                $this->_redirect($this->getUrl());

            $errors = array();

            $action = $this->getRequest()->getQuery('action');

            if ($this->getRequest()->isPost())
                $action = 'submit';

            switch ($action) {
                case 'submit':
                    $username = trim($this->getRequest()->getPost('username'));
                    if (strlen($username) == 0) {
                        $errors['username'] = 'Required field must not be blank';
                    }
                    else {
                        $user = new DatabaseObject_User($this->db);
                        if ($user->load($username, 'username')) {
                            $user->fetchPassword();
                            $url = $this->getUrl('fetchpassword') . '?action=complete';
                            $this->_redirect($url);
                        }
                        else
                            $errors['username'] = 'Specified user not found';
                    }
                    break;

                case 'complete':
                    // nothing to do
                    break;

                case 'confirm':
                    $id = $this->getRequest()->getQuery('id');
                    $key = $this->getRequest()->getQuery('key');

                    $user = new DatabaseObject_User($this->db);
                    if (!$user->load($id))
                        $errors['confirm'] = 'Error confirming new password';
                    else if (!$user->confirmNewPassword($key))
                        $errors['confirm'] = 'Error confirming new password';

                    break;
            }

            $this->breadcrumbs->addStep('Login', $this->getUrl('login'));
            $this->breadcrumbs->addStep('Fetch Password');
            $this->view->errors = $errors;
            $this->view->action = $action;
        }

        public function detailsAction()
        {
            $auth = Zend_Auth::getInstance();

            $fp = new FormProcessor_UserDetails($this->db,
                                                $auth->getIdentity()->user_id);

            if ($this->getRequest()->isPost()) {
                if ($fp->process($this->getRequest())) {
                    $auth->getStorage()->write($fp->user->createAuthIdentity());
                    $this->_redirect($this->getUrl('detailscomplete'));
                }
            }

            $this->breadcrumbs->addStep('Your Account Details');
            $this->view->fp = $fp;
        }

        public function detailscompleteAction()
        {
            $user = new DatabaseObject_User($this->db);
            $user->load(Zend_Auth::getInstance()->getIdentity()->user_id);

            $this->breadcrumbs->addStep('Your Account Details',
                                        $this->getUrl('details'));
            $this->breadcrumbs->addStep('Details Updated');
            $this->view->user = $user;
        }
    }
?>
