<?php
    class BlogmanagerController extends CustomControllerAction
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
           
            if($this->identity != null) { //logged in
                if($uid == null || $uid == $this->identity->user_id) {
                    #get a list of entries
                    $uid = $this->identity->user_id;
                    $bl = new DatabaseObject_BlogList($this->db, $uid);
                    $entries = $bl->listBlogEntries();
                    $items = array();

                    foreach ($entries as $entry) {
                        $url = $this->getUrl('preview') . '?id=' . $entry['post_id'] . '&uid=' . $uid;
                        $title = $entry['url'];
                        $items[] = array('title' => $title, 'url' => $url); #intval($entry['post_id']);
                    }

                    $this->view->assign('items', $items);
                    $this->view->assign('isowner', true);
                }

                if($uid != null) {
                    $from_id = $uid;
                    $to_id = $this->identity->user_id; 

                    $relation = new DatabaseObject_Relation($this->db);
                    $status = $relation->checkStatus($from_id, $to_id);
                    echo $status;

                    if($status == 'requested') {
                        $bl = new DatabaseObject_BlogList($this->db, $uid);
                        $entries = $bl->listBlogEntries();
                        $items = array();

                        foreach ($entries as $entry) {
                            $url = $this->getUrl('preview') . '?id=' . $entry['post_id'] . '&uid=' . $uid;
                            $title = $entry['url'];
                            $items[] = array('title' => $title, 'url' => $url); #intval($entry['post_id']);
                        }
                        $this->view->assign('items', $items);
                    }

                    $this->view->assign('isowner', false);
                }
            }
        }

        public function editAction()
        {
            $request = $this->getRequest();
            $post_id = (int) $this->getRequest()->getQuery('id');

            $fp = new FormProcessor_BlogPost($this->db,
                                             $this->identity->user_id,
                                             $post_id);

            if ($request->isPost()) {
                if ($fp->process($request)) {
                    $url = $this->getUrl('preview') . '?id=' . $fp->post->getId();
                    $this->_redirect($url);
                }
            }

            if ($fp->post->isSaved()) {
                $this->breadcrumbs->addStep(
                    'Preview Post: ' . $fp->post->profile->title,
                    $this->getUrl('preview') . '?id=' . $fp->post->getId()
                );
                $this->breadcrumbs->addStep('Edit Blog Post');
            }
            else
                $this->breadcrumbs->addStep('Create a New Blog Post');

            $this->view->fp = $fp;
        }

        public function previewAction()
        {
            $post_id = (int) $this->getRequest()->getQuery('id');
            $uid = (int) $this->getRequest()->getQuery('uid');
            
            if($uid ==  null || $uid ==$this->identity->user_id) {
                $post = new DatabaseObject_BlogPost($this->db);
                if (!$post->loadForUser($this->identity->user_id, $post_id))
                    $this->_redirect($this->getUrl());

                $this->breadcrumbs->addStep('Preview Post: ' . $post->profile->title);

                $this->view->post = $post;
            }
            else {
                echo "Access denied";
            }
        }

        public function setstatusAction()
        {
            $request = $this->getRequest();
            $post_id = (int) $request->getPost('id');

            $post = new DatabaseObject_BlogPost($this->db);
            if (!$post->loadForUser($this->identity->user_id, $post_id))
                $this->_redirect($this->getUrl());

            // URL to redirect back to
            $url = $this->getUrl('preview') . '?id=' . $post->getId();

            if ($request->getPost('edit')) {
                $this->_redirect($this->getUrl('edit') . '?id=' . $post->getId());
            }
            else if ($request->getPost('publish')) {
                $post->sendLive();
                $post->save();

                $this->messenger->addMessage('Post sent live');
            }
            else if ($request->getPost('unpublish')) {
                $post->sendBackToDraft();
                $post->save();

                $this->messenger->addMessage('Post unpublished');
            }
            else if ($request->getPost('delete')) {
                $post->delete();

                // Preview page no longer exists for this page so go back to index
                $url = $this->getUrl();

                $this->messenger->addMessage('Post deleted');
            }

            $this->_redirect($url);
        }
    }
?>
