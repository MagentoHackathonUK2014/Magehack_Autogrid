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
interface Magehack_Autogrid_Model_ConfigInterface
{
    const GRID = 'grid';
    const FORM = 'form';

    /**
     * Return all identifiers from the config for easier looping
     *
     * @return array All table identifiers defined in the configs
     */
    public function getAllTableIds();

    /**
     * Return the matching table ID if the specified artgument is a valid table id or table id URI.
     * 
     * @param string $controller
     * @return string|false
     */
    public function getTableIdFromController($controller);

    /**
     * Return the table URI if configured, otherwise the table id
     * 
     * @param string $tableId
     * @return mixed
     */
    public function getTableUri($tableId);
    
    /**
     * Return the real table name or table alias for a given table identifier.
     *
     * The returned table name should NOT be resolved via core/resource->getTableName.
     *
     * @param string $tableId The XML identifier for the auto grid table
     * @return string|false
     */
    public function getTableName($tableId);

    /**
     * Return the table title if configured, otherwise an empty string
     *
     * @param string $tableId The XML identifier for the auto grid table
     * @return string
     */
    public function getTableTitle($tableId);

    /**
     * Return the default value for a given column info key and column name.
     *
     * NOTE: This information is fetched from the merged config.xml files, NOT the autogrid.xml!
     *
     * @param string $colName
     * @param string $key For example source_model or frontend_input...
     * @return bool|string
     */
    public function getColumnInfoDefault($colName, $key);

    /**
     * Return the grid-related data from XML for backend grid creation
     *
     * @param string $tableId The XML identifier for the auto grid table
     * @return null|array
     */
    public function getGrid($tableId);

    /**
     * Return the form-related data from XML for backend form creation
     *
     * @param string $tableId The XML identifier for the auto grid table
     * @return null|array
     */
    public function getForm($tableId);

    /**
     * Return info for a specific grid column
     * 
     * @param $tableId string An autogrid XML table identifier
     * @param $column string Column name
     * @return null|array
     */
    public function getColumnInfo($tableId, $column);

    /**
     * Return info for a specific form field
     * 
     * @param $tableId string An autogrid XML table identifier
     * @param $field string Field name
     * @return null|array
     */
    public function getFieldInfo($tableId, $field);
}