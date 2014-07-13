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
            $ra = Mage::getSingleton("core/resource")->getConnection('core/read');
            $dbconfig = $ra->getConfig();

            $res = $ra->fetchAll("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?",$dbconfig['dbname']);
            foreach ($res as $data) {
                if ($data['TABLE_NAME'])
                $table = $this->_getTableInstance($data['TABLE_NAME']);
                $this->_addItem($table);
            }
            $this->_setIsLoaded();
        }
    }

}