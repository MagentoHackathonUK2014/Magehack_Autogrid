<?php

/**
 * Magento Hackathon 2014 UK
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Magehack
 * @package    Magehack_Autogrid
 * @copyright  Copyright (c) 2014 Magento community
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Magehack_Autogrid_Model_Resource_Table_Parser
    extends Mage_Core_Model_Resource_Db_Abstract
    implements Magehack_Autogrid_Model_Resource_Table_ParserInterface
{
    /**
     * Table name
     * 
     * @var string
     */
    protected $_tableName;

    /**
     * Name of the primary key column of the current table
     * 
     * @var string
     */
    protected $_primaryKey;

    /**
     * Description of the current table as returned by the read adapter
     * 
     * @var array
     */
    protected $_cols = array();

    /**
     * Either the table comment of the name of the table
     * 
     * @var string
     */
    protected $_title = '';

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_resources = Mage::getSingleton('core/resource');
    }

    /**
     * @param string $tableName
     * @return $this
     */
    public function init($tableName)
    {
        $tableName = $this->getTable($tableName);
        
        if ($this->_tableName != $tableName) {        
            $this->_tableName = $tableName;
    
            $adapter = $this->_getReadAdapter();
            $dbconfig = $adapter->getConfig();
            $tableInfo = $adapter->describeTable($tableName);
            $this->_cols = array();
    
            $select = $adapter->select()
                ->from('information_schema.COLUMNS', array('COLUMN_NAME', 'COLUMN_COMMENT'))
                ->where('TABLE_SCHEMA=?', $dbconfig['dbname'])
                ->where('TABLE_NAME=?', $tableName);
            $titles = $adapter->fetchPairs($select);
    
            foreach ($tableInfo as $name => $info) {
                $this->_cols[$name] = array('backend_type' => $info['DATA_TYPE'], 'title' => $titles[$name]);
                if ($info['PRIMARY'] && $info['PRIMARY_POSITION'] == 1) {
                    $this->_primaryKey = $name;
                }
            }
            $select = $adapter->select()
                ->from('information_schema.TABLES', array('TABLE_COMMENT'))
                ->where('TABLE_SCHEMA=?', $dbconfig['dbname'])
                ->where('TABLE_NAME=?', $tableName);
            $result = $adapter->fetchOne($select);
            $this->_title = $result ? $result : '';
        }
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    /**
     * @return array
     */
    public function getTableColumns()
    {
        return $this->_cols;
    }

    /**
     * @param string $name The column name
     * @return null|array
     */
    public function getTableColumnByName($name)
    {
        if (isset($this->_cols[$name])) {
            return $this->_cols[$name];
        }
        return null;
    }

    /**
     * Retrieve the table title
     * 
     * @return string
     */
    public function getTableTitle()
    {
        return $this->_title;
    }

}
