<?php
    class DatabaseObject_Relation extends DatabaseObject
    {

        protected $_idField2 = '';
        protected $_id2 = 0;

        public function __construct($db)
        {
            parent::__construct($db, 'relations', 'from_user');

            $this->_idField2 = 'to_user';
            
            $this->add('to_user');
            $this->add('status', 'r');
            $this->add('ts_requested', time(), self::TYPE_TIMESTAMP);
            $this->add('ts_response', null, self::TYPE_TIMESTAMP);
        }

        public function checkStatus($from_id, $to_id) {
            if($this->load($from_id, 'from_user', $to_id, 'to_user')) {
                if($this->_properties['status']['value'] == 'f') {
                    return 'friended';
                } else {
                    return 'requested';
                }
            } else {
                return 'nonexisted';
            }
        }

        public function makeRequest($from_id, $to_id) {
            $this->add('from_user', $from_id);
            $this->add('to_user', $to_id);
            $this->__set('ts_requested', time());
            return $this->save(false);
        }

        public function getFriends($user_id) {
            $user_id = (int) $user_id;
            if ($user_id <= 0)
                return null;
            $query = sprintf(
                'select from_user from %s where to_user = %d',
                $this->_table,
                $user_id
            );

            $result = $this->_db->query($query);
            $rows = $result->fetchAll();
            $friends = array();

            foreach($rows as $row) {
                array_push($friends, $row['from_user']); 
            }

            return $friends;
        }

        protected function getWhereClause() {
            return sprintf("%s = %d and %s = %d", $this->_idField, $this->getId(), $this->_idField2, $this->getId2());
        }

 
        public function isSaved() {
            return $this->getId() != null && $this->getId2() != null;
        }

        public function _init($row)
        {
            parent::_init($row);
            $this->_id2 = (int) $row[$this->_idField2];
        }

        
        public function load($id, $field = null, $id2 = null, $field2 = null)
        {
            if($id2 != null) {
                if (strlen($field) == 0)
                    $field = $this->_idField;

                if (strlen($field2) == 0)
                    $field2 = $this->_idField2;

                $query = sprintf('select %s from %s where %s = %d and %s = %d',
                             join(', ', $this->getSelectFields()),
                             $this->_table,
                             $field, $id,
                             $field2, $id2);

                return $this->_load($query);
            } else {
                return parent::load($id, $field);
            }

        }
    }
?>
