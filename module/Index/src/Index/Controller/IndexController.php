<?php

namespace Index\Controller;

use Zend\View\Model\ViewModel;
use Controller\CustomControllerAction;
use Model\DatabaseObject\DatabaseObjectRelation as Relation;
use Model\DatabaseObject\DatabaseObjectBlogList as BlogList;
use Model\DatabaseObject\DatabaseObjectBlogPost as BlogPost;
use Model\Profile\ProfileUser;

class IndexController extends CustomControllerAction 
{
    public function __construct()
    {
        parent::__construct();
        $this->_identity = $this->_auth->getStorage()->read();
    }

    public function indexAction()
    {
        $uid = (int) $this->getRequest()->getQuery('uid');
        $status = "";
        $full_name = "";
        $authenticated = false;
        $items = array();

        if($this->_auth->hasIdentity() && $this->_identity->user_id == null)
            $this->_auth->clearIdentity();

        if($this->_auth->hasIdentity()) {   //logged in
            $authenticated = true;
            if($uid == null) {
                $uid = $this->_identity->user_id;
            } else {
                $to_id = $uid;
                $from_id = $this->_identity->user_id;

                $relation = new Relation($this->_db);

                $status = $relation->checkStatus($from_id, $to_id);
            }

            $bl = new BlogList($this->_db, $uid);
            $full_name = $bl->getUserFirstName(). ' ' . $bl->getUserLastName();
            
            #find all friends
            $relation = new Relation($this->_db);
            $friends = $relation->getFriends($this->_identity->user_id);
            array_push($friends, $this->_identity->user_id);

            #get a list of entries
            $post = new BlogPost($this->_db);

            $bl = new BlogList($this->_db, $this->_identity->user_id);
            $entries = $bl->listBlogEntriesFromUsers($friends);

            $user_profile = new ProfileUser($this->_db);

            foreach ($entries as $entry) {
                $user_id = $entry['user_id'];
                $user_profile->setUserId($user_id);
                $user_profile->load();

                $url = $this->url()->fromRoute('blog', array('action' => 'view', 'id' => $entry['post_id']));
                $load_blog = $post->loadForUser($entry['user_id'], $entry['post_id']);
                $items[]  = array('title' => $post->profile->title, 'url' => $url, 'post_id' => $entry['post_id'], 'content' => $post->profile->content, 'user' => $user_profile->__get('first_name') . ' '  . $user_profile->__get('last_name') , 'ts' => $entry['ts_created']);
            }
            
        } else
            $this->redirect()->toRoute('account', array('action' => 'login'));

        return new ViewModel(array(
            'authenticated' => $authenticated,
            'section' => 'home',
            'full_name' => $full_name,
            'uid' => $uid,
            'items' => $items,
            'relation_status' => $status,
        ));
    }
}
