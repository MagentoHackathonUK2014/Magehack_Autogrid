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
     * @var string
     */
    protected $_autogridTableId;

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        // Partially initialization
        $this->_resources = Mage::getSingleton('core/resource');
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
     * Setter DI for table parser
     * 
     * @param Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser
     */
    public function setTableParser(Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser)
    {
        $this->_tableParser = $parser;
    }
    
    private function _getTableParser()
    {
        if (!isset($this->_tableParser)) {
            $this->_tableParser = Mage::getResourceModel('magehack_autogrid/table_parser');
        }
        return $this->_tableParser;
    }

    /**
     * @return Magehack_Autogrid_Model_Config
     * @throws Magehack_Autogrid_Exception_InitializationRequired
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
        $this->_autogridTableId = $autoGridTableId;
        $tableName = $this->_getConfig()->getTableName($autoGridTableId);
        $tableParser = $this->_getTableParser();
        $tableParser->init($this->getTable($tableName));
        $primaryKey = $tableParser->getPrimaryKey();
        $this->_resourceModel = null;
        $this->_init($tableName, $primaryKey);
    }

    private function _walkBackends(Magehack_Autogrid_Model_GenericEntity $object, $method)
    {
        $columns = $this->_getTableParser()->getTableColumns();
        foreach (array_keys($columns) as $column) {
            $info = $this->_getConfig()->getFieldInfo($this->_autogridTableId, $column);
            if (isset($info['backend_model']) && $info['backend_model']) {
                $backendModel = Mage::getModel($info['backend_model']);
                $backendModel->$method($object);
            }
        }
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $this->_walkBackends($object, 'afterLoad');
        return parent::_afterLoad($object);
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $this->_walkBackends($object, 'beforeSave');
        return parent::_beforeSave($object);
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $this->_walkBackends($object, 'afterSave');
        return parent::_afterSave($object);
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        $this->_walkBackends($object, 'beforeDelete');
        return parent::_beforeDelete($object);
    }

    protected function _afterDelete(Mage_Core_Model_Abstract $object)
    {
        $this->_walkBackends($object, 'afterDelete');
        return parent::_afterDelete($object);
    }
}