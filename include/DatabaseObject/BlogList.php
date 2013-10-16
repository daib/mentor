<?php
    class DatabaseObject_BlogList extends DatabaseObject
    {
        public $profile = null;

        private $_user_id = null;

        private $_first_name = null;
        private $_last_name = null;

        const STATUS_DRAFT = 'D';
        const STATUS_LIVE  = 'L';

        public function __construct($db, $uid)
        {
            parent::__construct($db, 'blog_posts', '*');
            $this->_user_id = $uid;

            $query = sprintf('select * from users_profile where user_id = %s',
                             $this->_user_id);

            $results = $this->_db->query($query)->fetchAll();

            foreach($results as $result) {
                if( $result['profile_key'] == 'first_name' ) {
                    $this->_first_name = $result['profile_value'];
                }

                if( $result['profile_key'] == 'last_name' ) {
                    $this->_last_name = $result['profile_value'];
                }
            }
        }

        public function listBlogEntries()
        {
            $query = sprintf('select * from %s where user_id = %s',
                             $this->_table,
                             $this->_user_id);

            $result = $this->_db->query($query);
            return $result->fetchAll();
        }

        public function getUserFirstName()
        {
            return $this->_first_name;
        }

        public function getUserLastName()
        {
            return $this->_last_name;
        }

    }
?>
