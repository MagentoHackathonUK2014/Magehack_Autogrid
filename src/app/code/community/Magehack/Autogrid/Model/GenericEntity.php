<?php


class Magehack_Autogrid_Model_GenericEntity extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('magehack_autogrid/genericEntity');
    }

    /**
     * Initialize the models resource model with the table for the specified autogrid id.
     * 
     * @param string $autoGridTableId
     * @return $this
     */
    public function setAutoGridTableId($autoGridTableId)
    {
        $this->getResource()->setAutoGridTableId($autoGridTableId);
        return $this;
    }
}