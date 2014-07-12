<?php


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
        /** @var Magehack_Autogrid_Model_Config $config */
        //$config = 
        
        $this->_headerText = $this->__('List Distributors');
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