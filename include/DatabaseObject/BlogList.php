<?php
    class DatabaseObject_BlogList extends DatabaseObject
    {
        public $profile = null;

        private $_user_id = null;

        const STATUS_DRAFT = 'D';
        const STATUS_LIVE  = 'L';

        public function __construct($db, $uid)
        {
            parent::__construct($db, 'blog_posts', '*');
            $this->_user_id = $uid;
        }

        public function listBlogEntries()
        {
            $query = sprintf('select * from %s where user_id = %s',
                             $this->_table,
                             $this->_user_id);

            $result = $this->_db->query($query);
            return $result->fetchAll();
        }
    }
?>
