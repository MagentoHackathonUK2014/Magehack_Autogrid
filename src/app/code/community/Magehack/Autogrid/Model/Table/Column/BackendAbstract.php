<?php

/**
 * Do nothing in the hook methods by default
 * 
 * Class Magehack_Autogrid_Model_Table_Column_BackendAbstract
 */
class Magehack_Autogrid_Model_Table_Column_BackendAbstract
{
    /**
     * @var string
     */
    private $_columnName;

    /**
     * @param string $col
     * @return $this
     */
    public function setColumnName($col)
    {
        $this->_columnName = $col;
        return $this;
    }

    /**
     * @return string
     */
    public function getColumnName()
    {
        return $this->_columnName;
    }
    
    public function afterLoad(Mage_Core_Model_Abstract $object) {}

    public function beforeSave(Mage_Core_Model_Abstract $object) {}
    
    public function afterSave(Mage_Core_Model_Abstract $object) {}
    
    public function beforeDelete(Mage_Core_Model_Abstract $object) {}
    
    public function afterDelete(Mage_Core_Model_Abstract $object) {}
} 