<?php


class Magehack_Autogrid_Model_Table_Column_Source_Yesno
    extends Magehack_Autogrid_Model_Table_Column_Source_Abstract
{
    /**
     * @return void
     */
    function _loadOptions()
    {
        $helper = Mage::helper('magehack_autogrid');
        $this->_options = array(
            array('value' => 0, 'label' => $helper->__('No')),
            array('value' => 1, 'label' => $helper->__('Yes'))
        );
    }
}