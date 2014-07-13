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
     * @param $columnName string - column name from mysql
     */
    public function setName($columnName);

    /**
     * @param $columnType string - mysql type as string
     */
    public function setType($columnType);

    /**
     * Returns the id (first parameter of addField) for setting up a form field
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the input type (second parameter of addField) for setting up a form field
     *
     * @return string
     */
    public function getFormInputType();
    
    /**
     * This is set internally by default or from the config but you can set it using this method if you like
     * @param $formInputType string - form type for pasing to addfield()
     *	 @return Magehack_Autogrid_Model_Column ie $this
     */
    public function setFormInputType($formInputType);

    /**
     * Returns the info array (third parameter of addField) for setting up a form field
     *
     * @return array
     */
    public function getFormInfo();
    public function getFormName();
    public function setFormName($formName);

    /**
     * Returns the info array (second parameter of addColumn) for setting up a grid column
     *
     * @return array
     */
    public function getGridInfo();


    
    
    public function isInGrid();
    public function isInForm();
    public function setAutogridTableId($id);
    public function getAutogridTableId($id);

    /**
     *
     * Returns the id (first parameter of addColumn() for setting up an admin grid column
     *
     * @return string
     */
    public function getGridColumnId();

     /**
     * @param $gridColumnId string - sets the id (first parameter of addColumn() for setting up an admin grid column
     */
     public function setGridColumnId($gridColumnId);
    
}