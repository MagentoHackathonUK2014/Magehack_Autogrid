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

class Magehack_Autogrid_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_tableId;

    /**
     * Set the table ID
     * 
     * @param string $tableId The table ID
     * @return Magehack_Autogrid_Controller_Router
     */
    public function setTableId($tableId)
    {
        $this->_tableId = $tableId;
        return $this;
    }

    /**
     * Retrieve the table ID
     * 
     * @return string
     */
    public function getTableId()
    {
        return $this->_tableId;
    }

    /**
     * @param string $tableId
     * @return Magehack_Autogrid_Model_Table
     */
    public function getCurrentTable()
    {
        $tableId = $this->getTableId();
        $tableModel = Mage::getModel('magehack_autogrid/table');
        $tableModel->setAutoGridTableId($tableId);
        return $tableModel;
    }
}
