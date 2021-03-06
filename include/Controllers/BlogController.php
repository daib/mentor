<?php
    class BlogController extends CustomControllerAction
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
           
            if($this->identity != null) { //logged in
                if($uid == null || $uid == $this->identity->user_id) {
                    #get a list of entries
                    $uid = $this->identity->user_id;

                    $post = new DatabaseObject_BlogPost($this->db);

                    $bl = new DatabaseObject_BlogList($this->db, $uid);
                    $entries = $bl->listBlogEntries();
                    $items = array();

                    foreach ($entries as $entry) {
                        $url = $this->getUrl('preview') . '?id=' . $entry['post_id'] . '&uid=' . $uid;
                        $load_blog = $post->loadForUser($this->identity->user_id, $entry['post_id']);
                        $items[] = array('title' => $post->profile->title, 'url' => $url, 'content' => $post->profile->content, 'ts' => $entry['ts_created']); #intval($entry['post_id']);
                    }

                    $this->view->assign('items', $items);
                    $this->view->assign('isowner', true);
                }

                if($uid != null) {
                    $from_id = $uid;
                    $to_id = $this->identity->user_id; 

                    $relation = new DatabaseObject_Relation($this->db);
                    $status = $relation->checkStatus($from_id, $to_id);

                    if($status == 'requested') {
                        $bl = new DatabaseObject_BlogList($this->db, $uid);
                        $entries = $bl->listBlogEntries();
                        $items = array();

                        foreach ($entries as $entry) {
                            $url = $this->getUrl('preview') . '?id=' . $entry['post_id'] . '&uid=' . $uid;
                            $title = $entry['url'];
                            $items[] = array('title' => $title, 'url' => $url, 'ts' => $entry['ts_created']); #intval($entry['post_id']);
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
            
            if($uid ==  null || $uid == $this->identity->user_id) {
                $uid = $this->identity->user_id;
            }
            else {
                $relation = new DatabaseObject_Relation($this->db);
                $status = $relation->checkStatus($uid, $this->identity->user_id);
                if($status == 'nonexisted') {
                    return;
                }
            }

            $post = new DatabaseObject_BlogPost($this->db);
            $load_blog = $post->loadForUser($uid, $post_id);
            $cp = new DatabaseObject_CommentPost($this->db);
            $comments = $cp->loadForUserAndTopic($uid, $post_id);

            if(!$load_blog) {
                $this->_redirect($this->getUrl());
            }
            $this->breadcrumbs->addStep($post->profile->title);

            $this->view->post = $post;
            $this->view->comments = $comments;
        }

        public function commentAction() {
            $request = $this->getRequest();
            $post_id = (int) $request->getPost('post_id');
            $comment = $request->getPost('comment');

            $cp = new DatabaseObject_CommentPost($this->db);
            $cp->insertComment($this->identity->user_id, $post_id, null, $comment); 
            $comment_id = -1;
            if($cp->getId() > 0) 
                $comment_id = $cp->getId();
            $arr = array('comment_id' => $comment_id, 'ts' => $cp->__get('ts_created'));
            echo json_encode($arr); 
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
