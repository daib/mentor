<?php
    namespace Model\DatabaseObject;

    use Zend\Db\Adapter\Adapter;
    use Model\DatabaseObject;

    class DatabaseObjectBlogList extends DatabaseObject
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

            $results = $this->getDb()->query($query, Adapter::QUERY_MODE_EXECUTE)->toArray();

            foreach($results as $result) {
                if( $result['profile_key'] == 'first_name' ) {
                    $this->_first_name = $result['profile_value'];
                }

                if( $result['profile_key'] == 'last_name' ) {
                    $this->_last_name = $result['profile_value'];
                }
            }
        }

        public function listBlogEntries($limit = 20)
        {
            $query = sprintf('select * from %s where user_id = %s order by ts_created desc limit %d',
                             $this->_table,
                             $this->_user_id,
                             $limit);

            $result = $this->getDb()->query($query, Adapter::QUERY_MODE_EXECUTE);
            return $result->fetchAll();
        }

        public function listBlogEntriesFromUsers($user_ids, $limit = 20)
        {
            $users = '(';
            foreach($user_ids  as $user_id) {
                $users = $users . "$user_id,";
            }

            $users = rtrim($users, ",");
            $users = $users . ")";

            $query = sprintf('select * from %s where user_id in %s order by ts_created desc limit %d',
                             $this->_table,
                             $users,
                            $limit);

            return $this->getDb()->query($query, Adapter::QUERY_MODE_EXECUTE)->toArray();
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
