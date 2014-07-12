<?php

class Magehack_Autogrid_Model_Resource_Table_Parser extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('magehack_autogrid/parser', 'id');
    }

    public function parseTable($tableName) {
        $ra = $this->_getReadAdapter();
        $struct = $ra->describeTable($tableName);
        $table = Mage::getModel('magehack_autogrid/table');
        foreach ($struct as $name => $info) {
            Mage::log($name."\n",null,'autogrid.log');
//            $cell = Mage::getModel('magehack_autogrid/cell');
//            $cell->setName($name)
//                 ->setType($info['DATA_TYPE']);
//            $table->addCell($cell);
        }
        return $table;
    }
}