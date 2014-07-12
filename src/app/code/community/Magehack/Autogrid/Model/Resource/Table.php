<?php


class Magehack_Autogrid_Model_Resource_Table
    extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('magehack_autogrid/dummy', 'id');
    }
} 