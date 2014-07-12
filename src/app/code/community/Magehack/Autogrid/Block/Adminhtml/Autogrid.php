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

class Magehack_Autogrid_Block_Adminhtml_Autogrid
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'magehack_autogrid';
        $this->_controller = 'adminhtml_autogrid';
        
        /** @var Magehack_Autogrid_Helper_Data $helper */
        $helper = Mage::helper('magehack_autogrid');
        $tableId = $helper->getTableId();
        if (! $tableId) {
            Mage::throwException($this->__('No autogrid table id specified'));
        }
        $table =  Mage::getModel('magehack_autogrid/table');
                    
        $this->_headerText = $this->__('Table "%s"', $table->getTitle());
        $this->_addButtonLabel = $this->__('Add New');

        parent::_construct();
    }

    /**
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
} 