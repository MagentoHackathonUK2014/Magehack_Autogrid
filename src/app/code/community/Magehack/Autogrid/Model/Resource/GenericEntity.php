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

class Magehack_Autogrid_Model_Resource_GenericEntity
    extends Mage_Core_Model_Resource_Db_Abstract
{
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
        $this->_setResource('magehack_autogrid');
    }

    /**
     * Setter DI for config access class
     *
     * @param Magehack_Autogrid_Model_ConfigInterface $config
     */
    public function setConfig(Magehack_Autogrid_Model_ConfigInterface $config)
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
     * @param Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser
     */
    public function setTableParser(Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser)
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
            $this->_tableParser = Mage::getModel('magehack_autogrid/table_parser');
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
            $this->_tableConfigModel = Mage::getSingleton('magehack_autogrid/config');
        }
        return $this->_tableConfigModel;
    }

    /**
     * Set the identifier for the autogrid table to use.
     * 
     * This will initialize the resource model with the table
     * configured for this table id.
     *
     * @param $autoGridTableId
     */
    public function setAutoGridTableId($autoGridTableId)
    {
        $tableName = $this->_getConfig()->getTableName($autoGridTableId);
        $tableParser = $this->_getTableParser();
        $tableParser->init($this->getTable($tableName));
        $primaryKey = $tableParser->getPrimaryKey();
        $this->_init($tableName, $primaryKey);
    }
}