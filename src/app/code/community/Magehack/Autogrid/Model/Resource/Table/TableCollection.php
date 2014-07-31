<?php


class Magehack_Autogrid_Model_Resource_Table_TableCollection
    extends Magehack_Autogrid_Model_Resource_Table_Collection
{
    /**
     * @inheritdoc
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if (!$this->isLoaded()) {
            $readAdapter = Mage::getSingleton("core/resource")->getConnection('core/read');
            $dbconfig = $readAdapter->getConfig();
            
            $select = $readAdapter->select()
                ->from('information_schema.TABLES', array('TABLE_NAME'))
                ->where('TABLE_SCHEMA=?', $dbconfig['dbname']);

            $res = $readAdapter->fetchCol($select);
            foreach ($res as $tableName) {
                $table = $this->_getTableInstance($tableName);
                $this->_addItem($table);
            }
            $this->_setIsLoaded();
        }
    }

}