<?php
    class DatabaseObject_Relations extends DatabaseObject
    {

        protected $_idField2 = '';
        public function __construct($db)
        {
            parent::__construct($db, 'relations', 'from_user');
            $_idField2 = 'to_user';
            
            $this->add('to_user');
            $this->add('status', 'r');
            $this->add('ts_requested', time(), self::TYPE_TIMESTAMP);
            $this->add('ts_response', null, self::TYPE_TIMESTAMP);
        }

        public function load($id, $field = null, $id2 = null, $field2 = null)
        {
            if($id2 != null) {
                if (strlen($field) == 0)
                    $field = $this->_idField;

                if (strlen($field2) == 0)
                    $field2 = $this->_idField2;

                $query = sprintf('select %s from %s where %s = ? and %s = ?',
                             join(', ', $this->getSelectFields()),
                             $this->_table,
                             $field,
                             $field2);

                $query = $this->_db->quoteInto($query, $id);
                $query = $this->_db->quoteInto($query, $id2);
                return $this->_load($query);
            } else {
                return parent::load($id, $field);
            }

        }

    }
?>
