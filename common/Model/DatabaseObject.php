<?php
    /**
     * DatabaseObject
     *
     * Abstract class used to easily manipulate data in a database table
     * via simple load/save/delete methods
     */

    namespace Model;

    use Zend\Db\Adapter\Adapter;
    use Zend\Db\Sql\Sql;

    abstract class DatabaseObject 
    {
        const TYPE_TIMESTAMP = 1;
        const TYPE_BOOLEAN   = 2;

        protected static $types = array(self::TYPE_TIMESTAMP, self::TYPE_BOOLEAN);

        private $_db = null;
        private $_id = null;
        private $_properties = array();


        protected $_table = '';
        protected $_idField = '';

        public function __construct(Adapter $adapter, $table, $idField)
        {
            $this->_table = $table;
            $this->_idField = $idField;
            
            $this->_db = $adapter;
        }

        public function load($id, $field = null)
        {
            if (strlen($field) == 0)
                $field = $this->_idField;

            if ($field == $this->_idField) {
                $id = (int) $id;
                if ($id <= 0)
                    return false;
            }

            $query = sprintf('select %s from %s where %s = %s',
                             join(', ', $this->getSelectFields()),
                             $this->_table,
                             $field,
                             $this->_db->getPlatform()->quoteValue($id));

            return $this->_load($query);
        }

        protected function getSelectFields($prefix = '')
        {
            $fields = array($prefix . $this->_idField);
            foreach ($this->_properties as $k => $v)
                $fields[] = $prefix . $k;

            return $fields;
        }

        protected function _load($query)
        {
            $rows = $this->_db->query($query, Adapter::QUERY_MODE_EXECUTE)->toArray();
            $row = $rows[0];
            if (!$row)
                return false;

            $this->_init($row);

            $this->postLoad();

            return true;
        }

        public function _init($row)
        {
            foreach ($this->_properties as $k => $v) {
                $val = $row[$k];

                switch ($v['type']) {
                    case self::TYPE_TIMESTAMP:
                        if (!is_null($val))
                            $val = strtotime($val);
                        break;
                    case self::TYPE_BOOLEAN:
                        $val = (bool) $val;
                        break;
                }

                $this->_properties[$k]['value'] = $val;
            }
            $this->_id = (int) $row[$this->_idField];
        }

        protected function getWhereClause() {
            return sprintf('%s = %d', $this->_idField, $this->getId());
        }

        protected function getWhereMap() {
            return array($this->_idField => $this->getId());
        }

        public function save($useTransactions = true)
        {
            $update = $this->isSaved();

            if ($useTransactions)
                $this->_db->getDriver()->getConnection()->beginTransaction();

            if ($update)
                $commit = $this->preUpdate();
            else
                $commit = $this->preInsert();

            if (!$commit) {
                if ($useTransactions)
                    $this->_db->rollback();
                return false;
            }

            $row = array();

            foreach ($this->_properties as $k => $v) {
                if ($update && !$v['updated'])
                    continue;

                switch ($v['type']) {
                    case self::TYPE_TIMESTAMP:
                        if (!is_null($v['value'])) {
                            if ($this->_db instanceof Zend_Db_Adapter_Pdo_Pgsql)
                                $v['value'] = date('Y-m-d H:i:sO', $v['value']);
                            else
                                $v['value'] = date('Y-m-d H:i:s', $v['value']);
                        }
                        break;

                    case self::TYPE_BOOLEAN:
                        $v['value'] = (int) ((bool) $v['value']);
                        break;
                }

                $row[$k] = $v['value'];
            }

            if (count($row) > 0) {
                // perform insert/update
                if ($update) {
                    $sql = new Sql($this->_db);
                    $update = $sql->update();
                    $update->table($this->_table);
                    $update->set($row);
                    $update->where($this->getWhereMap());
                    $statement = $sql->prepareStatementForSqlObject($update);

                    $result = 0;
                    try {
                        $result = $statement->execute();        // works fine
                    } catch (\Exception $e) {
                        die('Error: ' . $e->getMessage());
                    }
                    if (empty($result)) {                       // not sure if this is applicable??
                        die('Zero rows affected');
                    }
                }
                else {
                    $sql = new Sql($this->_db);
                    $insert = $sql->insert(); 
                    $insert->into($this->_table);
                    $insert->values($row); 
                    $insert->columns(array_keys($row));
                    $statement = $sql->prepareStatementForSqlObject($insert);

                    $result = 0;
                    try {
                        $result = $statement->execute();        // works fine
                    } catch (\Exception $e) {
                        die('Error: ' . $e->getMessage());
                    }
                    if (empty($result)) {                       // not sure if this is applicable??
                        die('Zero rows affected');
                    }
                    $this_id = $this->_db->getDriver()->getLastGeneratedValue();
                }
            }

            // update internal id

            if ($commit) {
                if ($update)
                    $commit = $this->postUpdate();
                else
                    $commit = $this->postInsert();
            }

            if ($useTransactions) {
                if ($commit)
                    $this->_db->getDriver()->getConnection()->commit();
                else
                    $this->_db->getDriver()->getConnection()->rollback();
            }

            return $commit;
        }

        public function delete($useTransactions = true)
        {
            if (!$this->isSaved())
                return false;

                if ($useTransactions)
                $this->_db->getDriver()->getConnection()->beginTransaction();

            $commit = $this->preDelete();

            if ($commit) {
                $this->_db->delete($this->_table, $this->getWhereClause());
            }
            else {
                if ($useTransactions)
                    $this->_db->getDriver()->getConnection()->rollback();
                return false;
            }

            $commit = $this->postDelete();

            $this->_id = null;

            if ($useTransactions) {
                if ($commit)
                    $this->_db->commit();
                else
                    $this->_db->rollback();
            }

            return $commit;
        }

        public function isSaved()
        {
            return $this->getId() > 0;
        }

        public function getId()
        {
            return (int) $this->_id;
        }

        public function getDb()
        {
            return $this->_db;
        }

        public function __set($name, $value)
        {
            if (array_key_exists($name, $this->_properties)) {
                $this->_properties[$name]['value'] = $value;
                $this->_properties[$name]['updated'] = true;
                return true;
            }

            return false;
        }

        public function __get($name)
        {
            return array_key_exists($name, $this->_properties) ? $this->_properties[$name]['value'] : null;
        }

        protected function add($field, $default = null, $type = null)
        {
            $this->_properties[$field] = array('value'   => $default,
                                               'type'    => in_array($type, self::$types) ? $type : null,
                                               'updated' => false);
        }

        protected function preInsert()
        {
            return true;
        }

        protected function postInsert()
        {
            return true;
        }

        protected function preUpdate()
        {
            return true;
        }

        protected function postUpdate()
        {
            return true;
        }

        protected function preDelete()
        {
            return true;
        }

        protected function postDelete()
        {
            return true;
        }

        protected function postLoad()
        {
            return true;
        }

        public static function BuildMultiple($db, $class, $data)
        {
            $ret = array();

            if (!class_exists($class))
                throw new Exception('Undefined class specified: ' . $class);

            $testObj = new $class($db);

            if (!$testObj instanceof DatabaseObject)
                throw new Exception('Class does not extend from DatabaseObject');

            foreach ($data as $row) {
                $obj = new $class($db);
                $obj->_init($row);

                $ret[$obj->getId()] = $obj;
            }

            return $ret;
        }
    }
?>
