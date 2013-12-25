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
                
                #find all friends
                $relation = new DatabaseObject_Relation($this->db);
                $friends = $relation->getFriends($this->identity->user_id);
                array_push($friends, $this->identity->user_id);

                #get a list of entries
                $post = new DatabaseObject_BlogPost($this->db);

                $bl = new DatabaseObject_BlogList($this->db, $this->identity->user_id);
                $entries = $bl->listBlogEntriesFromUsers($friends);
                $items = array();

                $user_profile = new Profile_User($this->db);

                foreach ($entries as $entry) {
                    $user_id = $entry['user_id'];
                    $user_profile->setUserId($user_id);
                    $user_profile->load();

                    $url = $this->getUrl('preview', 'blog') . '?id=' . $entry['post_id'] . '&uid=' . $user_id;
                    $load_blog = $post->loadForUser($entry['user_id'], $entry['post_id']);
                    $items[]  = array('title' => $post->profile->title, 'url' => $url, 'content' => $post->profile->content, 'user' => $user_profile->__get('first_name') . ' '  . $user_profile->__get('last_name') , 'ts' => $entry['ts_created']); 
                }

                $this->view->assign('items', $items);
            }


        }
    }
?>
