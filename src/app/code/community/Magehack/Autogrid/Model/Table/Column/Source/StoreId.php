<?php


class Magehack_Autogrid_Model_Table_Column_Source_StoreId
    extends Magehack_Autogrid_Model_Table_Column_Source_Abstract
{
    protected $_collection = 'core/store_collection';
    protected $_labelAttribute = 'name';

    protected function _getCollection()
    {
        /** @var Mage_Core_Model_Resource_Store_Collection $collection */
        $collection = parent::_getCollection();
        $collection->setLoadDefault(true);
        return $collection;
    }


}