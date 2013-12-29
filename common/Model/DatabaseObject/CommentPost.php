<?php
    namespace Model\DatabaseObject;

    class DatabaseObject_CommentPost extends DatabaseObject
    {
        public function __construct($db)
        {
            parent::__construct($db, 'comment_posts', 'post_id');

            $this->add('user_id');
            $this->add('topic_post_id');
            $this->add('parent_post_id');
            $this->add('value');
            $this->add('ts_created', time(), self::TYPE_TIMESTAMP);
        }

        public function loadForUserAndTopic($user_id, $topic_post_id)
        {
            $post_id = (int) $topic_post_id;
            $user_id = (int) $user_id;

            if ($post_id <= 0 || $user_id <= 0)
                return false;

            $query = sprintf(
                'select %s from %s where user_id = %d and topic_post_id = %d',
                join(', ', $this->getSelectFields()),
                $this->_table,
                $user_id,
                $topic_post_id
            );

            $result = $this->_db->query($query);
            return $result->fetchAll();
        }

        public function insertComment($user_id, $topic_post_id, $parent_post_id, $value) {
            $this->add('user_id', $user_id);
            $this->add('topic_post_id', $topic_post_id);
            $this->add('parent_post_id', $parent_post_id);
            $this->add('value', $value);
            $this->save(false);
        }
    }
?>
