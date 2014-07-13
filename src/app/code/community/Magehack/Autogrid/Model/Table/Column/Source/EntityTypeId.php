<?php


class Magehack_Autogrid_Model_Table_Column_Source_EntityTypeId
    extends Magehack_Autogrid_Model_Table_Column_Source_Abstract
{
    /**
     * Populate the $_options property
     *
     * @return null
     */
    function _loadOptions()
    {
        $collection = Mage::getResourceModel('eav/entity_type_collection');
        foreach ($collection as $id => $model) {
            $this->_options[] = array(
                'value' => $id,
                'label' => $model->getData('entity_type_code')
            );
        }
    }
}