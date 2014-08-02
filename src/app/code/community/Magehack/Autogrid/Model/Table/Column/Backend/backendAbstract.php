<?php

/**
 * Do nothing in the abstract hook methods.
 * 
 * Class Magehack_Autogrid_Model_Table_Column_Backend_Abstract
 */
class Magehack_Autogrid_Model_Table_Column_Backend_Abstract
    implements Magehack_Autogrid_Model_Table_Column_BackendInterface
{
    public function afterLoad(Mage_Core_Model_Abstract $object) {}

    public function beforeSave(Mage_Core_Model_Abstract $object) {}
    
    public function afterSave(Mage_Core_Model_Abstract $object) {}
    
    public function beforeDelete(Mage_Core_Model_Abstract $object) {}
    
    public function afterDelete(Mage_Core_Model_Abstract $object) {}
} 