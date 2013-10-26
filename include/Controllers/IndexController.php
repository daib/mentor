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

            if(Zend_Auth::getInstance()->hasIdentity()) {   //logged in
                if($uid == null) {
                    $status = 'owner';
                    $uid = $this->identity->user_id;
                } else {
                    $to_id = $uid;
                    $from_id = $this->identity->user_id;

                    $relation = new DatabaseObject_Relation($this->db);

                    $status = $relation->checkStatus($from_id, $to_id);
                }

                $bl = new DatabaseObject_BlogList($this->db, $uid);
                $full_name = $bl->getUserFirstName(). ' ' . $bl->getUserLastName();

                $this->view->assign('blog_url', '?uid=' . $uid);
                $this->view->assign('relation_status', $status);
                $this->view->assign('full_name', $full_name);
                $this->view->assign('uid', $uid);
            }
        }
    }
?>
