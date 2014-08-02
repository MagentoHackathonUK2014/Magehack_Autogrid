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
class Magehack_Autogrid_Model_Config implements Magehack_Autogrid_Model_ConfigInterface
{
    /**
     * Config file name
     */
    const FILE = 'autogrid.xml';

    /**
     * Cache of prepared table grid data
     *
     * @var array
     */
    protected $_grids = array();

    /**
     * Cache of prepared table form data
     *
     * @var array
     */
    protected $_forms = array();

    /**
     * Merged autogrid XML config
     *
     * @var Mage_Core_Model_Config_Base
     */
    protected $_config;

    /**
     * List of possible column info keys.
     *
     * @var array
     */
    protected $_columnInfoKeys = array(
        'frontend_label',
        'backend_type',
        'frontend_model',
        'source_model',
        'backend_model',
        'filter',
        'sortable',
    );

    /**
     * List of possible field info keys.
     *
     * @var array
     */
    protected $_fieldInfoKeys = array(
        'frontend_label',
        'backend_type',
        'frontend_input',
        'frontend_class',
        'source_model',
        'backend_model',
        'is_required',
        'default_value',
        'is_unique',
        'validation_regex',
        'note',
        'is_visible',
        'disabled',
    );

    /**
     * DI setter method to provide method of passing in a loaded config model.
     *
     * @param Mage_Core_Model_Config_Base $config
     */
    public function setConfig(Mage_Core_Model_Config_Base $config)
    {
        $this->_config = $config;
    }

    /**
     * Return all identifiers from the config for easier looping
     *
     * @return mixed array of all table identifiers defined in the configs
     */
    public function getAllTableIds()
    {
        $tableIds = array_keys($this->_getConfig()->getNode('tables')->asArray());
        return $tableIds;
    }

    /**
     * Return the matching table ID if the specified artgument is a valid table id or table id URI.
     *
     * @param string $controller
     * @return string|false
     */
    public function getTableIdFromController($controller)
    {
        // only allow alphanumeric characters 
        if (! preg_match('#^[a-z][1-z0-9_]+$#i', $controller)) {
            return false;
        }
        $xpath = "tables/*/uri[text()='$controller']";
        $nodes = $this->_getConfig()->getNode()->xpath($xpath);
        if ($nodes) {
            // find matching table uri
            $tableId = $nodes[0]->xpath('..');
            $tableId = $tableId[0]->getName();
            return $tableId;
        } else {
            // find matching table id without an uri node
            $tableId = $controller;
            $tables = $this->getAllTableIds();
            if (in_array($tableId, $tables)) {
                $uri = $this->getTableUri($tableId);
                if ($uri === $tableId) {
                    // no uri configured
                    return $tableId;
                }
            }
        }
        return false;
    }

    /**
     * Return the table URI if configured, otherwise the table id
     *
     * @param string $tableId
     * @return mixed
     */
    public function getTableUri($tableId)
    {
        $path = "tables/$tableId/uri";
        if ($uri = $this->_getConfig()->getNode($path)) {
            return $uri;
        }
        return $tableId;
    }


    /**
     * Return the real table name or table alias for a given table identifier.
     *
     * The real table name has to be specified in autogrid.xml
     *
     * @param string $tableId XML identifier for the table
     * @return string|false
     */
    public function getTableName($tableId)
    {
        $path = "tables/$tableId/table";
        if ($tableName = $this->_getConfig()->getNode($path)) {
            return (string)$tableName;
        }
        return false;
    }

    /**
     * Return the table title if configured, otherwise an empty string
     *
     * @param string $tableId
     * @return string
     */
    public function getTableTitle($tableId)
    {
        return (string)$this->_getConfig()->getNode("tables/$tableId/title");
    }


    /**
     * Return the grid data for the given table id
     *
     * @param string $tableId XML identifier for the table
     * @return false|array
     */
    public function getGrid($tableId)
    {
        if (!isset($this->_grids[$tableId])) {
            /** @var Varien_Simplexml_Element $gridConfig */
            $gridConfig = $this->_getConfig()->getNode('tables/' . $tableId . '/grid');
            if (!$gridConfig) {
                $this->_grids[$tableId] = false;
            } else {
                $gridInfo = array();
                foreach ($gridConfig->asCanonicalArray() as $column => $info) {
                    if ($colDef = $this->_buildColumnInfo($column, $info)) {
                        $gridInfo[$column] = $colDef;
                    }
                }
                $this->_grids[$tableId] = $gridInfo;
            }
        }
        return $this->_grids[$tableId];
    }

    /**
     * Return the form data for the given table id
     *
     * @param string $tableId XML identifier for the table
     * @return false array
     */
    public function getForm($tableId)
    {
        if (!isset($this->_forms[$tableId])) {
            $formConfig = $this->_getConfig()->getNode('tables/' . $tableId . '/form');
            if (!$formConfig) {
                $this->_forms[$tableId] = false;
            } else {
                $formInfo = array();
                foreach ($formConfig->asCanonicalArray() as $field => $info) {
                    if ($fieldDef = $this->_buildFormInfo($field, $info)) {
                        $formInfo[$field] = $fieldDef;
                    }
                }
                $this->_forms[$tableId] = $formInfo;
            }
        }
        return $this->_forms[$tableId];
    }

    /**
     * Return info for a specific grid column
     *
     * @param $tableId string An autogrid XML table identifier
     * @param $column string Column name
     * @return null|array
     */
    public function getColumnInfo($tableId, $column)
    {
        $gridInfo = $this->getGrid($tableId);
        if (!isset($gridInfo[$column]) || !$gridInfo[$column]) {
            // Column is not listed in the configuration.
            // Apply possible defaults
            $default = $this->_buildColumnInfo($column, array());
            if ($default) {
                $this->_grids[$tableId][$column] = $default;
                $gridInfo[$column] = $default;
            }
        }
        return isset($gridInfo[$column]) ? $gridInfo[$column] : null;
    }

    /**
     * Return info for a specific form field
     *
     * @param $tableId string An autogrid XML table identifier
     * @param $field string Field name
     * @return null|array
     */
    public function getFieldInfo($tableId, $field)
    {
        $formInfo = $this->getForm($tableId);
        if (!isset($formInfo[$field]) || !$formInfo[$field]) {
            // Column is not listed in the configuration.
            // Apply possible defaults
            $default = $this->_buildFormInfo($field, array());
            if ($default) {
                $this->_forms[$tableId][$field] = $default;
                $formInfo[$field] = $default;
            }
        }
        return isset($formInfo[$field]) ? $formInfo[$field] : null;
    }

    /**
     * Build array with grid columns
     *
     * Any key specified in the configuration is kept intact.
     * Any default values based on the column name should be merged in if
     * not specified in the autogrid config.
     *
     * Default values are checked for any key in $colInfo that is empty
     * and for every name in the $this->_columnInfoKeys array.
     *
     * @param string $colName
     * @param array $colInfo Column info from XML
     * @return array
     */
    protected function _buildColumnInfo($colName, array $colInfo)
    {
        $result = $this->_prepareColFieldInfo($colName, $colInfo, $this->_columnInfoKeys);
        $result['type'] = $this->_getColumnType($colName, $result);
        if ('options' == $result['type']) {
            $result['options'] = $this->_getGridOptionsFromSource($result);
        }
        if (!isset($result['index']) && !isset($result['getter'])) {
            $result['index'] = $colName;
        }
        if (!isset($result['header'])) {
            $result['header'] = ucwords(str_replace('_', ' ', $colName));
        }
        if (isset($result['filter']) && ! $result['filter']) {
            $result['filter'] = false; // cast to boolean false
        }
        if (isset($result['sortable']) && ! $result['sortable']) {
            $result['sortable'] = false; // cast to boolean false
        }
        return $result;
    }

    /**
     * Build array with form fields
     *
     * Any key specified in the configuration is kept intact.
     * Any default values based on the field name should be merged in if
     * not specified in the autogrid config.
     *
     * Default values are checked for any key in $fieldInfo that is empty
     * and for every name in the $this->_fieldInfoKeys array.
     *
     * @param string $fieldName
     * @param array $fieldInfo Field info from XML
     * @return array
     */
    protected function _buildFormInfo($fieldName, array $fieldInfo)
    {
        $result = $this->_prepareColFieldInfo($fieldName, $fieldInfo, $this->_fieldInfoKeys);
        $result['frontend_input'] = $this->_getFrontendInputType($fieldName, $result);
        if (in_array($result['frontend_input'], array('select', 'multiselect'))) {
            $result['values'] = $this->_getFormOptionsFromSource($result);
        }
        elseif ($result['frontend_input'] == 'date') {
            if (! isset($result['format'])) {
                $result['format'] = Mage::app()->getLocale()->getDateFormat(
                    Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
                );
            }
            if (! isset($result['image'])) {
                $result['image'] = Mage::getDesign()->getSkinUrl('images/grid-cal.gif');
            }
            if (! isset($result['class'])) {
                $result['class'] = 'validate-date validate-date-range date-range-custom_theme-to';
            }
        }
        if (! isset($result['name'])) {
            $result['name'] = $fieldName;
        }
        if (!isset($result['label'])) {
            $result['label'] = ucwords(str_replace('_', ' ', $fieldName));
        }
        return $result;
    }

    /**
     * Build col or field definition array from config values with merged default values.
     *
     * This method is shared for grid column info and form field info.
     *
     * @param string $name
     * @param array $info
     * @param array $defaultKeys
     * @return array
     */
    private function _prepareColFieldInfo($name, array $info, array $defaultKeys)
    {
        $result = array();
        foreach ($info as $key => $value) {
            if (!$value) {
                $default = $this->getColumnInfoDefault($name, $key);
                if (null !== $default) {
                    $value = $default;
                }
            } 
            if (null !== $value) {
                $result[$key] = $value;
            }
        }
        foreach ($defaultKeys as $key) {
            if (!isset($result[$key])) {
                $default = $this->getColumnInfoDefault($name, $key);
                if (null !== $default) {
                    $result[$key] = $default;
                }
            }
        }
        return $result;
    }

    /**
     * Return the default value for a given column info key and column name.
     *
     * NOTE: This information is fetched from the merged config.xml files, NOT the autogrid.xml!
     *
     * @param string $colName
     * @param string $key
     * @return null|string
     */
    public function getColumnInfoDefault($colName, $key)
    {
        $path = "adminhtml/autogrid/column_defaults/$colName/$key";
        $default = Mage::getConfig()->getNode($path);
        if ($default !== false) {
            return (string)$default;
        }
        return null;
    }

    /**
     * Get all table configurations in one go.
     *
     * @return Mage_Core_Model_Config_Base
     */
    private function _getConfig()
    {
        if (is_null($this->_config)) {
            $this->_config = Mage::getConfig()->loadModulesConfiguration(self::FILE);
        }

        return $this->_config;
    }

    /**
     * Figure out and return the type for the given column.
     *
     * @param string $colName
     * @param array $info
     * @return string
     */
    protected function _getColumnType($colName, array $info)
    {
        if (isset($info['type'])) {
            return $info['type'];
        }
        if (isset($info['source_model'])) {
            return 'options';
        }
        if (isset($info['backend_type']) && 'int' == $info['backend_type']) {
            return 'numeric';
        }
        if (in_array($colName, array('entity_id', 'id'))) {
            return 'numeric';
        }
        return 'text';
    }

    /**
     * Figure out and return the input type for the given field.
     *
     * @param string $fieldName
     * @param array $info
     * @return string
     */
    protected function _getFrontendInputType($fieldName, array $info)
    {
        if (isset($info['frontend_input'])) {
            return $info['frontend_input'];
        }
        if (isset($info['source_model'])) {
            return 'select';
        }
        if (in_array($fieldName, array('created_at', 'updated_at', 'date'))) {
            return 'date';
        }
        if ('entity_id' === $fieldName) {
            return 'label';
        }
        return 'text';
    }

    /**
     * Return an grid options array for the source model
     *
     * @param array $info
     * @return array
     */
    protected function _getGridOptionsFromSource(array $info)
    {
        if (isset($info['source_model'])) {
            /** @var Magehack_Autogrid_Model_Table_Column_SourceInterface $source */
            $source = Mage::getModel($info['source_model']);
            return $source->getGridOptionArray();
        }
        return array();
    }

    /**
     * Return a form options array for the source model
     *
     * @param array $info
     * @return array
     */
    protected function _getFormOptionsFromSource(array $info)
    {
        if (isset($info['source_model'])) {
            /** @var Magehack_Autogrid_Model_Table_Column_SourceInterface $source */
            $source = Mage::getModel($info['source_model']);
            return $source->getFormOptionArray();
        }
        return array();
    }
}