<?php


class Magehack_Autogrid_Model_Table_Column_Backend_CreatedAt
    extends Magehack_Autogrid_Model_Table_Column_BackendAbstract
{
    public function beforeSave(Mage_Core_Model_Abstract $object)
    {
        if ($object->isObjectNew()) {
            $now = Varien_Date::now();
            $object->setData($this->getColumnName(), $now);
        }
        parent::beforeSave($object);
    }

} 