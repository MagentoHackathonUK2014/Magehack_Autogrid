<?php

class Magehack_Autogrid_Model_Table_Parser extends Mage_Core_Model_Abstract
{
    public function parseTable($tableName) {
        return Mage::getResourceModel('magehack_autogrid/table_parser')->parseTable($tableName);
    }

}