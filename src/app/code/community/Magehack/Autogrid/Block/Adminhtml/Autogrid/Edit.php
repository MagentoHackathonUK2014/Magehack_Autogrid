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

/**
 * Autogrid Form Container
 * @package Magehack_Autogrid
 */
class Magehack_Autogrid_Block_Adminhtml_Autogrid_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Constructor Override
     * @return Magehack_Autogrid_Block_Adminhtml_Autogrid_Edit
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_blockGroup = 'magehack_autogrid';
        $this->_controller = 'adminhtml_autogrid';
        $this->_mode       = 'edit';

        $this->setFormActionUrl($this->getUrl('*/*/save'));

        // Save and continue edit button
        $this->_addButton('saveandcontinue', array(
                'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save',
        ), -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";

        return $this;
    }

    /**
     * The header
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->_getObject()->getId()) {
            $header = $this->__('Edit Record (ID %s)', $this->_getObject()->getId());
        } else {
            $header = 'New Record';
        }
        return $this->__($header);
    }

    /**
     * Retrieve the generic entity
     * @return Magehack_Autogrid_Model_GenericEntity
     */
    protected function _getObject()
    {
        return Mage::registry('current_generic_entity');
    }

}