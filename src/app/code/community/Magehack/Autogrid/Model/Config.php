<?php
class Magehack_Autogrid_Model_Config implements Magehack_Autogrid_Model_ConfigInterface
{
    function getTables()
    {
        $tables = array();
            foreach (Mage::getConfig()->loadModulesConfiguration('autogrid.xml')->getNode('tables')->asCanonicalArray() as $tableName => $tableParameters) {
            $tables[$tableName]= $tableParameters;
        }
        return $tables;
    }

    function getTableName($configKey)
    {
        return Mage::getConfig()->loadModulesConfiguration('autogrid.xml')->getNode('tables/'.$configKey.'/table')->__toString();

    }

}