<?php


class Magehack_Autogrid_Model_Resource_Table_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Modify standard constructor to not directly initialize the resource model.
     * This will be done when init() is called with the autogrid table id.
     * 
     * @param Mage_Core_Model_Resource_Db_Abstract|null $resource 
     * @see Magehack_Autogrid_Model_Resource_Table_Collection::init()
     */
    public function __construct($resource = null)
    {
        // Do not call the parent __construct() method.
        
        $this->_construct();
        $this->_resource = $resource;
        
        // The following is done in init()
        //$this->setConnection($this->getResource()->getReadConnection());
        //$this->_initSelect();
    }
    
    protected function _construct()
    {
        $this->_init('magehack_autogrid/table');
    }
    
    public function init($tableId)
    {
        /** @var Magehack_Autogrid_Model_Resource_Table $resource */
        $resource = $this->getResource();
        $resource->setAutoGridTableId($tableId);

        // Initalize the resource model
        $this->setConnection($this->getResource()->getReadConnection());
        $this->_initSelect();
    }
}