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
     * Combined column data from table parser and config model 
     * 
     * @var array
     */
    protected $_columnData;

    /**
     * Combined form field data from table parser and config model
     * 
     * @var array
     */
    protected $_fieldData;

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
            $this->_validateInitialization();
            $columnInfo = $this->_getColumnDataFromParser();
            $configInfo = $this->_getColumnDataFromConfig();
            $this->_columnData = $this->_mergeColumnData($columnInfo, $configInfo);
        }
        return $this->_columnData;
    }

    /**
     * Return the merged field data from the table parser and config.
     * 
     * Build the table data only once on first call.
     * 
     * @return array
     */
    private function _getFieldData()
    {
        if (is_null($this->_fieldData)) {
            $this->_validateInitialization();
            $columnInfo = $this->_getColumnDataFromParser();
            $configInfo = $this->_getFieldDataFromConfig();
            $this->_fieldData = $this->_mergeColumnData($columnInfo, $configInfo);
            if ($this->getColumnName() == $this->_tableParser->getPrimaryKey()) {
                $this->_fieldData['frontend_input'] = 'hidden';
            }
        }
        return $this->_fieldData;
    }

    /**
     * Return data from table parser, also does some validation.
     * 
     * This method should only be called once as its result is not cached.
     * 
     * @return array|null
     */
    private function _getColumnDataFromParser()
    {
        $name = $this->getColumnName();
        $data = $this->_tableParser->getTableColumnByName($name);
        return $data;
    }

    /**
     * Return the column data from the autogrid config model
     */
    private function _getColumnDataFromConfig()
    {
        $name = $this->getColumnName();
        $data = $this->_config->getColumnInfo($this->getAutogridTableId(), $name);
        return $data;
    }

    /**
     * Return the field data from the autogrid config model
     */
    private function _getFieldDataFromConfig()
    {
        $name = $this->getColumnName();
        $data = $this->_config->getFieldInfo($this->getAutogridTableId(), $name);
        return $data;
    }

    /**
     * Merge the column definitions from table and config, the latter overriding values from the former. 
     * 
     * @param array $columnInfo Column definition from the table parser
     * @param array $configInfo Column definition from the config model
     * @return array
     */
    private function _mergeColumnData(array $columnInfo, array $configInfo)
    {
        $merged = array_merge($columnInfo, $configInfo);
        return $merged;
    }

    /**
     * @throws Magehack_Autogrid_Exception_InvalidColumnName
     * @throws Magehack_Autogrid_Exception_InitializationRequired
     */
    private function _validateInitialization()
    {
        $helper = Mage::helper('magehack_autogrid');
        $name = $this->getColumnName();
        if (! $name) {
            $message = $helper->__('No column name set on table column model');
            throw new Magehack_Autogrid_Exception_InitializationRequired($message);
        }
        if (! $this->_config) {
            $message = $helper->__('No config model set on table column model');
            throw new Magehack_Autogrid_Exception_InitializationRequired($message);
        }
        if (! $this->_tableParser) {
            $message = $helper->__('No table parser set on table column model');
            throw new Magehack_Autogrid_Exception_InitializationRequired($message);
        }
        $data = $this->_tableParser->getTableColumnByName($this->getColumnName());
        if (! $data) {
            $message = $helper->__('Table name %s not found by parser', $this->getColumnName());
            throw new Magehack_Autogrid_Exception_InvalidColumnName($message);
        }
    }

    /**
     * Return whether the column should be visible in a grid
     *
     * @return bool
     */
    public function isInGrid()
    {
        $info = $this->_getColumnData();
        return $this->_isVisible($info);
    }

    /**
     * Return whether the column should be visible as a field in a form
     *
     * @return bool
     */
    public function isInForm()
    {
        $info = $this->_getFieldData();
        return $this->_isVisible($info);
    }

    /**
     * Return whether the column or field should be visible.
     * Only if the is_visible key is set to a false value or to
     * the string "false" is a column or field invisible.
     *
     * @param array $info
     * @return bool
     */
    public function _isVisible(array $info)
    {
        if (! isset($info['is_visible'])) {
            return true;
        }
        if ($info['is_visible']) {
            return true;
        }
        return 'false' !== $info['is_visible'];
    }

    /**
     * Returns the form element input type (e.g. text or select)
     *
     * @return string
     */
    public function getFieldInputType()
    {
        $info = $this->_getFieldData();
        return $info['frontend_input'];
    }

    /**
     * Return the form field element id (forst paramet
     *
     * @return string
     */
    public function getFormFieldId()
    {
        return $this->getColumnName();
    }

    /**
     * Returns the field info array (third parameter of addField) for setting up a form field
     *
     * @return array
     */
    public function getFormFieldInfo()
    {
        $info = $this->_getFieldData();
        return $info;
    }

    /**
     * Returns the id (first parameter of addColumn() for setting up an admin grid column
     *
     * @return string
     */
    public function getGridColumnId()
    {
        return $this->getColumnName();
    }

    /**
     * Returns the info array (second parameter of addColumn) for setting up a grid column
     *
     * @return array
     */
    public function getGridInfo()
    {
        $info = $this->_getColumnData();
        return $info;
    }
}

