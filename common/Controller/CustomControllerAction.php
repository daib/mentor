<?php
    namespace Controller;

    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\Authentication\AuthenticationService;
    use Zend\Authentication\Storage\Session as SessionStorage;
    use Zend\Config\Reader\Ini;
    use Zend\Db\Adapter\Adapter;

    class CustomControllerAction extends AbstractActionController 
    {
        protected $_auth, $_identity, $_db;

        public function __construct()
        {
            $this->_auth = new AuthenticationService();
            $this->_auth->setStorage(new SessionStorage('mentorNetwork'));

            $this->_identity = $this->_auth->getIdentity();
            $ini_reader = new Ini();
            $config   = $ini_reader->fromFile(__DIR__ . '/../../settings.ini');

            // connect to the database
            $params = array('driver' => $config['development']['database']['type'],
                            'hostname'     => $config['development']['database']['hostname'],
                            'username' => $config['development']['database']['username'],
                            'password' => $config['development']['database']['password'],
                            'database'   => $config['development']['database']['database']);

            $this->_db = new Adapter($params);
        }
/*
        public $db;
        public $breadcrumbs;
        public $messenger;

        public function init()
        {
            $this->db = Zend_Registry::get('db');

            $this->breadcrumbs = new Breadcrumbs();
            $this->breadcrumbs->addStep('Home', $this->getUrl(null, 'index'));

            $this->messenger = $this->_helper->_flashMessenger;
        }

        public function getUrl($action = null, $controller = null)
        {
            $url  = rtrim($this->getRequest()->getBaseUrl(), '/') . '/';
            $url .= $this->_helper->url->simple($action, $controller);

            return $url;
        }

        public function preDispatch()
        {
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                $this->view->authenticated = true;
                $this->view->identity = $auth->getIdentity();
            }
            else
                $this->view->authenticated = false;
        }

        public function postDispatch()
        {
            $this->view->breadcrumbs = $this->breadcrumbs;
            $this->view->title = $this->breadcrumbs->getTitle();

            $this->view->messages = $this->messenger->getMessages();
        }

        public function sendJson($data)
        {
            $this->_helper->viewRenderer->setNoRender();

            $this->getResponse()->setHeader('content-type', 'application/json');
            echo Zend_Json::encode($data);
        }

        protected function _initSessions()
        {
            Zend_Session::start();
        }
*/
    }
?>
