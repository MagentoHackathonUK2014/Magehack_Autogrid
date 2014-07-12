<?php


interface Magehack_Autogrid_Model_Resource_Table_ParserInterface
{
    /**
     * @param string $tableName
     * @return void
     */
    public function init($tableName);

    /**
     * Return the primary key column name.
     * 
     * @return string
     */
    public function getPrimaryKey();

    /**
     * Return array of of cell instances.
     * 
     * @return Magehack_Autogrid_Model_Table_Cell[] (This class name is not final)
     */
    public function getTableColumns();
} 