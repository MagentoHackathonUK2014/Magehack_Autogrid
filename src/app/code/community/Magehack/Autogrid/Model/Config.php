<?php
class Magehack_Autogrid_Model_Config
{
    function getTables()
    {
        $tables = array();
            foreach (Mage::getConfig()->loadModulesConfiguration('table.xml')->getNode('tables')->asCanonicalArray() as $tableName => $tableParameters) {
            $tables[$tableName]= $tableParameters;
        }
        return $tables;
    }

}