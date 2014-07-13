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

interface Magehack_Autogrid_Model_Table_ColumnInterface
{
    /**
     * Set the autogrid table id the column is associated with
     * 
     * @param string $tableId
     * @return $this
     */
    public function setAutogridTableId($tableId);

    /**
     * Return the autogrid table id
     *
     * @return string
     */
    public function getAutogridTableId();
    
    /**
     * Set the database table column name the autogrid table column is associated with
     * 
     * @param string $columnName
     * @return $this
     */
    public function setColumnName($columnName);

    /**
     * Returns the name ot the associated database column
     *
     * @return string
     */
    public function getColumnName();

    /**
     * Return whether the column should be visible in a grid
     *
     * @return bool
     */
    public function isInGrid();

    /**
     * Returns the id (first parameter of addColumn() for setting up an admin grid column
     *
     * @return string
     */
    public function getGridColumnId();

    /**
     * Returns the info array (second parameter of addColumn) for setting up a grid column
     *
     * @return array
     */
    public function getGridInfo();

    /**
     * Return whether the column should be visible as a field in a form
     *
     * @return bool
     */
    public function isInForm();

    /**
     * Return the form field element id (forst paramet
     *
     * @return string
     */
    public function getFormFieldId();

    /**
     * Returns the form element input type (e.g. text or select)
     *
     * @return string
     */
    public function getFieldInputType();

    /**
     * Returns the field info array (third parameter of addField) for setting up a form field
     *
     * @return array
     */
    public function getFormFieldInfo();

    /**
     * DI setter method for table parser
     * 
     * @param Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser
     * @return $this
     */
    public function setTableParser(Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser);

    /**
     * DI setter method for config class
     * 
     * @param Magehack_Autogrid_Model_ConfigInterface $config
     * @return $this
     */
    public function setConfig(Magehack_Autogrid_Model_ConfigInterface $config);
}