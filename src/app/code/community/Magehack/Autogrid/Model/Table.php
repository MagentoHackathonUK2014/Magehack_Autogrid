<?php


class Magehack_Autogrid_Model_Table
    extends Varien_Object
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
     * @var Magehack_Autogrid_Model_Resource_Table_Column[]
     */
    protected $_columns = array();

    /**
     * The table title
     * 
     * @var string
     */
    protected $_title;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getAutogridTableId();
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
     * @return string
     */
    public function getAutogridTableId()
    {
        return $this->_autogridTableId;
    }

    /**
     * Load the table data from the table parser and the config and merge them together
     * 
     * @throws Magehack_Autogrid_Model_Exception_InitializationRequired
     */
    protected function _loadTableData()
    {
        $tableId = $this->getAutogridTableId();
        if (! $tableId) {
            $helper = $this->_getHelper();
            $message = $helper->__('No autogrid id set on table!');
            throw new Magehack_Autogrid_Model_Exception_InitializationRequired($message);
        }
        $this->_loadTableDataFromParser();
        $this->_mergeTableDataFromConfig();
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
        $tableId = $this->getAutogridTableId();

        /** @var Magehack_Autogrid_Model_Table_ColumnInterface $column */
        $column = Mage::getModel('magehack_autogrid/table_column');
        $column->setTableParser($parser);
        $column->setConfig($config);
        $column->setAutogridTableId($tableId);
        $column->setColumnName($name);

        return $column;
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
    }

    /**
     * Update the table data
     */
    protected function _mergeTableDataFromConfig()
    {
        $config = $this->_getConfig();
        $tableId = $this->getAutogridTableId();
        if ($title = $config->getTableTitle($tableId)) {
            $this->_title = $title;
        }
        if (! $this->_title) {
            $this->_title = $config->getTableName($tableId);
        }
    }

    /**
     * @return Magehack_Autogrid_Model_Table_ColumnInterface[]
     */
    public function getAllColumns()
    {
        if (! isset($this->_columns)) {
            $this->_loadTableData();
        }
        return $this->_columns;
    }

    /**
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
}