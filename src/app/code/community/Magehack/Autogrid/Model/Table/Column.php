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
class Magehack_Autogrid_Model_Table_Column implements Magehack_Autogrid_Model_Table_ColumnInterface
{
    /**
     * @var Magehack_Autogrid_Model_ConfigInterface
     */
    protected $_config;

    /**
     * @var Magehack_Autogrid_Model_Resource_Table_ParserInterface
     */
    protected $_tableParser;

    /**
     * The autogrid table id the column is associated with
     * 
     * @var string
     */
    protected $_autoGridTableId;

    /**
     * The database table column name the autogrid table column is associated with
     * 
     * @var string
     */
    protected $_columnName;

    /**
     * Raw column data for this column as returned by the table parser
     * 
     * @var array
     */
    protected $_columnData;

    /**
     * DI setter method for config class
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
     * DI setter method for table parser
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
     * Set the autogrid table id the column is associated with
     *
     * @param string $tableId
     * @return $this
     */
    public function setAutogridTableId($tableId)
    {
        $this->_autoGridTableId = $tableId;
        return $this;
    }

    /**
     * Return the autogrid table id
     *
     * @return string
     */
    public function getAutogridTableId()
    {
        return $this->_autoGridTableId;
    }

    /**
     * Set the database table column name the autogrid table column is associated with
     *
     * @param string $columnName
     * @return $this
     */
    public function setColumnName($columnName)
    {
        $this->_columnName = $columnName;
        return $this;
    }

    /**
     * Returns the name ot the associated database column
     *
     * @return string
     */
    public function getColumnName()
    {
        return $this->_columnName;
    }

    /**
     * Return the merged column data from the table parser and config.
     * 
     * Build the table data only once on first call.
     * 
     * @return array
     */
    private function _getColumnData()
    {
        if (is_null($this->_columnData)) {
            $data = $this->_getColumnDataFromParser();
        }
        return $this->_columnData;
    }

    /**
     * Return data from table parser, also does some validation.
     * 
     * This method should only be called once as its result is not cached.
     * 
     * @return array|null
     * @throws Magehack_Autogrid_Exception_InvalidColumnName
     * @throws Magehack_Autogrid_Exception_InitializationRequired
     */
    private function _getColumnDataFromParser()
    {
        $name = $this->getColumnName();
        $helper = Mage::helper('magehack_autogrid');
        if (! $name) {
            $message = $helper->__('No column name set on table column model');
            throw new Magehack_Autogrid_Exception_InitializationRequired($message);
        }
        $data = $this->_tableParser->getTableColumnByName($this->getColumnName());
        if (! $data) {
            $message = $helper->__('Table name %s not found by parser', $this->getColumnName());
            throw new Magehack_Autogrid_Exception_InvalidColumnName($message);
        }
        return $data;
    }
    
    
}

