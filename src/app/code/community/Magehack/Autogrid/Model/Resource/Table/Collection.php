<?php


class Magehack_Autogrid_Model_Resource_Table_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('magehack_autogrid/table');
    }

} 