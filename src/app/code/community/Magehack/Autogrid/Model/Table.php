<?php


class Magehack_Autogrid_Model_Table
{
    /**
     * @var string
     */
    protected $_autogridTableId;
    
    /**
     * @var Magehack_Autogrid_Model_Resource_Table_Column[]
     */
    protected $_columns = array();

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
    
    protected function _loadTableDataFromParser()
    {
        $columns = $this->_getTableParser()->getTableColumns();
        foreach ($columns as $column) {
            //$table->
        }
    }
    
    protected function _mergeTableDataFromConfig()
    {
        
    }

    /**
     * @return Magehack_Autogrid_Model_Resource_Table_Column[]
     */
    public function getColumns()
    {
        if (! isset($this->_columns)) {
            $this->_loadTableData();
        }
        return $this->_columns;
    }
} 