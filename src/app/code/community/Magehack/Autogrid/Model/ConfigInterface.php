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

    /**
     * Return the grid for backend grid creation
     *
     * @param string $tableId XML identifier for the table
     * @return mixed
     */
    public function getGrid($tableId);

    /**
     * Return the grid for backend form creation
     *
     * @param string $tableId XML identifier for the table
     * @return mixed
     */
    public function getForm($tableId);


    /**
     * Return the source model for grid or form
     *
     * @param string $tableId XML identifier for the table
     * @param $part grid|form for which part the source model should be
     * @return mixed
     */
    public function getSourceModel($tableId, $part);
} 