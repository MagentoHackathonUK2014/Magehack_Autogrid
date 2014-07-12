<?php

class Magehack_Autogrid_Model_Resource_Table_Parser
    extends Mage_Core_Model_Resource_Db_Abstract
    implements Magehack_Autogrid_Model_Resource_Table_ParserInterface
{

    protected $_primaryKey = null;
    protected $_cells = array();
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('magehack_autogrid/parser', 'id');
    }

    /**
     * @param string $tableName
     * @return $this|void
     */
    public function init($tableName)
    {
        $ra = $this->_getReadAdapter();
        $struct = $ra->describeTable($tableName);
        $this->_cells = array();
        foreach ($struct as $name => $info) {
            Mage::log($name."\n",null,'autogrid.log');
            $cell = Mage::getModel('magehack_autogrid/cell');
            $cell->setName($name)
                 ->setType($info['DATA_TYPE']);
            $this->_cells[] = $cell;
        }
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    /**
     * @return array|Magehack_Autogrid_Model_Table_Cell
     */
    public function getTableColumns()
    {
        return $this->_cells;
    }

}