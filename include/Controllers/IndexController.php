<?php
    class IndexController extends CustomControllerAction
    {
        public function init()
        {
            parent::init();
            $this->breadcrumbs->addStep('Account', $this->getUrl(null, 'account'));
            $this->breadcrumbs->addStep('Blog',
                                        $this->getUrl(null, 'blog'));

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
$app_key = '49e26cb8e9dde3dfc009';
$app_secret = 'YOUR_APP_SECRET';
$app_id = 'YOUR_APP_ID';

$pusher = new Pusher($app_key, $app_secret, $app_id);
$data = array('message' => 'This is an HTML5 Realtime Push Notification!');
$pusher->trigger('my_notifications', 'my-event', $data);
        }
    }
?>
