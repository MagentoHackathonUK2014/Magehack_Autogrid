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
    /**
     * Return the real table name or table alias for a given table identifier.
     * 
     * The returned table name should NOT be resolved via core/resource->getTableName.
     * 
     * @param string $tableId The XML identifier for the auto grid table.
     * @return string
     */
    public function getTableName($tableId);

    /**
     * Return the grid for backend grid creation
     *
     * @param string $tableId XML identifier for the table
     * @return mixed
     */
    public function getGrid($tableId);

    /**
     * Return the grid for backend form creation
     *
     * @param string $tableId XML identifier for the table
     * @return mixed
     */
    public function getForm($tableId);


    /**
     * Return the source model for grid or form
     *
     * @param string $tableId XML identifier for the table
     * @param $part grid|form for which part the source model should be
     * @return mixed
     */
    public function getSourceModel($tableId, $part);

    /**
     * Return all identifiers from the config for easier looping
     *
     * @return mixed array of all tableidentifiers defined in the configs
     */
    public function getTableIds();
    
     /** Return the table title if configured, otherwise an empty string
     * 
     * @param string $tableId
     * @return string
     */
    public function getTableTitle($tableId);
} 