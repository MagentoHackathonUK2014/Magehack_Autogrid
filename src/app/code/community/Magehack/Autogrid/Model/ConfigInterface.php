<?php


interface Magehack_Autogrid_Model_ConfigInterface
{
    /**
     * Return the real table name or table alias for a given table identifier.
     * 
     * The returned table name should NOT be resolved via core/resource->getTableName.
     * 
     * @param string $tableId The XML identifier for the auto grid table.
     * @return string
     */
    public function getTableName($tableId);
} 