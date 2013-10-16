<?php
    class IndexController extends CustomControllerAction
    {
        public function init()
        {
            parent::init();
            $this->breadcrumbs->addStep('Account', $this->getUrl(null, 'account'));
            $this->breadcrumbs->addStep('Blog Manager',
                                        $this->getUrl(null, 'blogmanager'));

            $this->identity = Zend_Auth::getInstance()->getIdentity();
        }

        public function indexAction()
        {
            $uid = (int) $this->getRequest()->getQuery('uid');
            if($uid == null && $this->identity !=  null)
                $uid = $this->identity->user_id;
            if($uid != null) {
                $bl = new DatabaseObject_BlogList($this->db, $uid);
                $full_name = $bl->getUserFirstName(). ' ' . $bl->getUserLastName();
                $this->view->assign('full_name', $full_name);
            }
        }
    }
?>
