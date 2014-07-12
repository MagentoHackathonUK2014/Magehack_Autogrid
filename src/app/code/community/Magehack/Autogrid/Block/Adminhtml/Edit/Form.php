<?php

class Magehack_Autogrid_Block_Adminhtml_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Preparing form
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save'),
                'method' => 'post',
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        $helper = Mage::helper('magehack_autogrid');

        $fieldset = $form->addFieldset('form1', array(
            'legend' => $helper->_('Form'),
            'class' => 'fieldset-wide'
        ));

        $table = $helper->getCurrentTable();

        foreach ($table->getCells() as $cell) {
            $fieldset->addField($cell->getName(), $cell->getFormInputType(), $cell->getFormInfo());
        }

        if (Mage::registry('current_autogrid_object')) {
            $form->setValues(Mage::registry('current_autogrid_object')->getData());
        }

        return parent::_prepareForm();
    }
}
