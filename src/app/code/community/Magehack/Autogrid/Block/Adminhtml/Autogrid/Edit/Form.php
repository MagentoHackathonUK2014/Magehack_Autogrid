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
 * Autogrid Form
 * @package Magehack_Autogrid
 */
class Magehack_Autogrid_Block_Adminhtml_Autogrid_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form before rendering HTML
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $helper = Mage::helper('magehack_autogrid');
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getData('action'),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));

        $entity = Mage::registry('current_generic_entity');
        $form->setData('data_object', $entity);

        $fieldset = $form->addFieldset('general', array(
            'legend' => $helper->__('Table Fields')
        ));
        $table = $helper->getCurrentTable();

        /** @var Magehack_Autogrid_Model_Table_ColumnInterface $field */
        foreach ($table->getFormColumns() as $field) {
            $fieldset->addField($field->getFormFieldId(), $field->getFieldInputType(), $field->getFormFieldInfo());
        }

        $form->setData('use_container', true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Initialize form fields values.
     * 
     * Method will be called after prepareForm and can be used for field values initialization.
     * 
     * @return $this
     */
    protected function _initFormValues()
    {
        $entity = Mage::registry('current_generic_entity');
        $this->getForm()->setValues($entity->getData());

        return $this;
    }
}