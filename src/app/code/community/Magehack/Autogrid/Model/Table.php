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

class Magehack_Autogrid_Model_Table extends Varien_Object
{
    /**
     * @var string
     */
    protected $_autogridTableId;

    /**
     * @var Magehack_Autogrid_Model_ConfigInterface
     */
    protected $_config;

    /**
     * @var Magehack_Autogrid_Model_Resource_Table_ParserInterface
     */
    protected $_tableParser;

    /**
     * @var Magehack_Autogrid_Helper_Data
     */
    protected $_helper;

    /**
     * @var Magehack_Autogrid_Model_Table_Column[]
     */
    protected $_columns = array();

    /**
     * The table title
     * 
     * @var string
     */
    protected $_title;

    /**
     * @var bool
     */
    protected $_isLoaded = false;

    /**
     * @var string
     */
    protected $_primaryKey;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getAutoGridTableId();
    }

    /**
     * Setter DI Method for grid table id
     * 
     * @param string $autoGridTableId
     * @return $this
     */
    public function setAutoGridTableId($autoGridTableId)
    {
        $this->_autogridTableId = $autoGridTableId;
        return $this;
    }

    /**
     * Setter DI for config model
     * 
     * @param Magehack_Autogrid_Model_ConfigInterface $config
     * @return $this
     */
    public function setConfig(Magehack_Autogrid_Model_ConfigInterface $config)
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * @return Magehack_Autogrid_Model_ConfigInterface
     */
    protected function _getConfig()
    {
        if (! isset($this->_config)) {
            $this->_config = Mage::getSingleton('magehack_autogrid/config');
        }
        return $this->_config;
    }

    /**
     * Setter DI for table parser
     * 
     * @param Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser
     * @return $this
     */
    public function setTableParser(Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser)
    {
        $this->_tableParser = $parser;
        return $this;
    }

    /**
     * Setter DI for helper
     * 
     * @param Magehack_Autogrid_Helper_Data $helper
     * @return $this
     */
    public function setHelper(Magehack_Autogrid_Helper_Data $helper)
    {
        $this->_helper = $helper;
        return $this;
    }

    /**
     * @return Magehack_Autogrid_Model_Resource_Table_ParserInterface
     */
    protected function _getTableParser()
    {
        if (! isset($this->_tableParser)) {
            $this->_tableParser = Mage::getResourceModel('magehack_autogrid/table_parser');
        }
        return $this->_tableParser;
    }

    /**
     * @return Magehack_Autogrid_Helper_Data
     */
    protected function _getHelper()
    {
        if (! isset($this->_helper)) {
            $this->_helper = Mage::helper('magehack_autogrid');
        }
        return $this->_helper;
    }

    /**
     * @return bool
     */
    protected function _isLoaded()
    {
        return $this->_isLoaded;
    }

    /**
     * @return string
     */
    public function getAutoGridTableId()
    {
        return $this->_autogridTableId;
    }

    /**
     * Load the table data from the table parser and the config and merge them together
     * 
     * @throws Magehack_Autogrid_Exception_InitializationRequired
     */
    protected function _loadTableData()
    {
        $tableId = $this->getAutoGridTableId();
        if (! $tableId) {
            $helper = $this->_getHelper();
            $message = $helper->__('No autogrid id set on table!');
            throw new Magehack_Autogrid_Exception_InitializationRequired($message);
        }
        $tableName = $this->_getTableName($tableId);
        $this->_getTableParser()->init($tableName);
        $this->_loadTableDataFromParser();
        $this->_mergeTableDataFromConfig();
        $this->_isLoaded = true;
    }

    /**
     * Create a column instance and inject all required data
     * 
     * @param $name
     * @return Magehack_Autogrid_Model_Table_ColumnInterface
     */
    protected function _getColumnInstance($name)
    {
        $parser = $this->_getTableParser();
        $config = $this->_getConfig();
        $tableId = $this->getAutoGridTableId();
        $tableName = $this->_getTableName($tableId);
        
        $parser->init($tableName);

        /** @var Magehack_Autogrid_Model_Table_ColumnInterface $column */
        $column = Mage::getModel('magehack_autogrid/table_column');
        $column->setTableParser($parser);
        $column->setConfig($config);
        $column->setAutogridTableId($tableId);
        $column->setColumnName($name);

        return $column;
    }

    /**
     * Return the table name for the specified autogrid table id.
     *
     * If no table name for the specified id is found in the autogrid
     * configuration, return the table id.
     * This is used for the grid of all tables.  
     * 
     * @param string $tableId
     * @return string
     */
    protected function _getTableName($tableId)
    {
        $tableName = $this->_getConfig()->getTableName($tableId);
        if (false === $tableName) {
            $tableName = $tableId;
        }
        return $tableName;
    }

    /**
     * Populate the $_columns array from the information returned by the parser 
     */
    protected function _loadTableDataFromParser()
    {
        $columns = $this->_getTableParser()->getTableColumns();
        foreach ($columns as $name => $columnInfo) {
            $this->_columns[$name] = $this->_getColumnInstance($name);
        }
        $this->_title = $this->_getTableParser()->getTableTitle();
        $this->_primaryKey = $this->_getTableParser()->getPrimaryKey();
    }

    /**
     * Update the table data
     */
    protected function _mergeTableDataFromConfig()
    {
        $config = $this->_getConfig();
        $tableId = $this->getAutoGridTableId();
        if ($title = $config->getTableTitle($tableId)) {
            $this->_title = $title;
        }
        if (! $this->_title) {
            $this->_title = $this->_getTableName($tableId);
        }
    }

    /**
     * @return bool
     */
    public function isValidTable()
    {
        $tableId = $this->getAutoGridTableId();
        // Don't use $this->_getTableName on purpose here
        $tableName = trim($this->_getConfig()->getTableName($tableId));
        return '' !== $tableName;
    }

    /**
     * Return all collumns
     * 
     * @return Magehack_Autogrid_Model_Table_ColumnInterface[]
     */
    public function getAllColumns()
    {
        if (! $this->_isLoaded()) {
            $this->_loadTableData();
        }
        return $this->_columns;
    }

    /**
     * Return all the columns that are visible in the grid
     * 
     * @return Magehack_Autogrid_Model_Table_ColumnInterface[]
     */
    public function getGridColumns()
    {
        $visibleColumns = array();
        foreach ($this->getAllColumns() as $column) {
            if ($column->isInGrid()) {
                $visibleColumns[] = $column;
            }
        }
        return $visibleColumns;
    }

    /**
     * Return all the columns that are visible in the form
     * 
     * @return Magehack_Autogrid_Model_Table_ColumnInterface[]
     */
    public function getFormColumns()
    {
        $visibleColumns = array();
        foreach ($this->getAllColumns() as $column) {
            if ($column->isInForm()) {
                $visibleColumns[] = $column;
            }
        }
        return $visibleColumns;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if (! $this->_isLoaded()) {
            $this->_loadTableData();
        }
        return $this->_title;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        if (! $this->_isLoaded()) {
            $this->_loadTableData();
        }
        $tableId = $this->getAutoGridTableId();
        $uri = $this->_getConfig()->getTableUri($tableId);
        return $uri;
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        if (! $this->_isLoaded()) {
            $this->_loadTableData();
        }
        return $this->_primaryKey;
    }
}