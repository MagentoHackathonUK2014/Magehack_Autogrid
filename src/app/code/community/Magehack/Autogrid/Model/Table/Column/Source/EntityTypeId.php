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
        /** @var Mage_Eav_Model_Resource_Entity_Type_Collection $collection */
        $collection = Mage::getResourceModel('eav/entity_type_collection');
        /** @var Mage_Eav_Model_Entity_Type $model */
        foreach ($collection as $id => $model) {
            $this->_options[] = array(
                'value' => $id,
                'label' => $model->getData('entity_type_code')
            );
        }
    }
}