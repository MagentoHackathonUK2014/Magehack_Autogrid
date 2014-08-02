<?php


interface Magehack_Autogrid_Model_Table_Column_SourceInterface
{
    /**
     * Return the options as a key => value array
     * 
     * Example:
     * 
     * array(value => 'The Label', ...)
     * 
     * @return array
     */
    public function getGridOptionArray();

    /**
     * Return the options as a Magneto options array
     * 
     * Example:
     * 
     * array(
     *      array('value' => value, 'label' => 'The Label'),
     *      ...
     * )
     * 
     * @return array
     */
    public function getFormOptionArray();
}