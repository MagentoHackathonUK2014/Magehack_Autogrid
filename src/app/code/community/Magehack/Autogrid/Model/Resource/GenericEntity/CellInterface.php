<?php

interface Magehack_Autogrid_Model_Resource_Table_CellInterface
{
    /**
     * @param $cellName string - column name from mysql
     */
    public function setName($cellName);

    /**
     * @param $cellType string - mysql type as string
     */
    public function setType($cellType);

    /**
     *
     * Returns the id (first parameter of addField) for setting up a form field
     *
     * @return array
     */
    public function getName();

    /**
     *
     * Returns the input type (second parameter of addField) for setting up a form field
     *
     * @return string
     */
    public function getFormInputType();

    /**
     *
     * Returns the info array (third parameter of addField) for setting up a form field
     *
     * @return array
     */
    public function getFormInfo();

    /**
     *
     * Returns the info array (second parameter of addColumn) for setting up a grid column
     *
     * @return array
     */
    public function getGridInfo();


}