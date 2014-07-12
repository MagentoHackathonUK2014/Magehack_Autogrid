<?php


class Magehack_Autogrid_Model_Resource_Table
    extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * The
     *
     * @var string
     */
    protected $_autoGridTable;

    /**
     * @var Magehack_Autogrid_Model_Config
     */
    protected $_tableConfigModel;

    /**
     * @var Magehack_Autogrid_Model_Resource_Table_Parser
     */
    protected $_tableParser;

    /**
     * @var Magehack_Autogrid_Helper_Data
     */
    protected $_helper;

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        // no initialization here
    }

    /**
     * Setter DI for config access class
     *
     * @param Magehack_Autogrid_Model_Config $config
     */
    public function setConfig(Magehack_Autogrid_Model_Config $config)
    {
        $this->_tableConfigModel = $config;
    }

    /**
     * Setter DI for helper
     * 
     * @param Magehack_Autogrid_Helper_Data $helper
     */
    public function setHelper(Magehack_Autogrid_Helper_Data $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * Setter DI for table parser
     * 
     * @param Magehack_Autogrid_Model_Resource_Table_Parser $parser
     */
    public function setTableParser(Magehack_Autogrid_Model_Resource_Table_Parser $parser)
    {
        $this->_tableParser = $parser;
    }

    /**
     * @return Magehack_Autogrid_Helper_Data
     */
    private function _getHelper()
    {
        if (! isset($this->_helper)) {
            $this->_helper = Mage::helper('magehack_autogrid');
        }
        return $this->_helper;
    }
    
    private function _getTableParser()
    {
        if (!isset($this->_tableParser)) {
            $helper = $this->_getHelper();
            $message = $helper->__('Table parser config model not set');
            throw new Magehack_Autogrid_Model_Exception_InitializationRequired($message);
        }
        return $this->_tableParser;
    }

    /**
     * @return Magehack_Autogrid_Model_Config
     * @throws Magehack_Autogrid_Model_Exception_InitializationRequired
     */
    private function _getConfig()
    {
        if (!isset($this->_tableConfigModel)) {
            $helper = $this->_getHelper();
            $message = $helper->__('Autogrid config model not set');
            throw new Magehack_Autogrid_Model_Exception_InitializationRequired($message);
        }
        return $this->_tableConfigModel;
    }

    /**
     * Set the identifier for the autogrid table to use.
     *
     * @param $tableId
     */
    public function setAutoGridTable($tableId)
    {
        $this->_autoGridTable = $tableId;
        $tableName = $this->_getConfig()->getTableName($tableId);
        $tableParser = $this->_getTableParser();
        $tableParser->init($tableName);
        $primaryKey = $tableParser->getPrimaryKey();
        $this->_init($tableName, $primaryKey);
    }
}