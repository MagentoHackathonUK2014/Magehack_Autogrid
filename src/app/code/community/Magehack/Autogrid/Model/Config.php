<?php
class Magehack_Autogrid_Model_Config implements Magehack_Autogrid_Model_ConfigInterface
{

    /**
     * @var array
     */
    protected $_forms = array();
    /**
     * @var array
     */
    protected $_grids = array();
    /**
     * @var array
     */
    protected $_tableNames = array();
    /**
     * @var
     */
    protected $_config;

    function __construct()
    {
        $this->_config = Mage::getConfig()->loadModulesConfiguration('autogrid.xml');
    }


    /**
     * Get all table configurations in one go.
     *
     * @return array
     */
    function getTables()
    {
        $tables = array();
            foreach (Mage::getConfig()->loadModulesConfiguration('autogrid.xml')->getNode('tables')->asCanonicalArray() as $tableName => $tableParameters) {
            $tables[$tableName]= $tableParameters;
        }
        return $tables;
    }

    /**
     * Return the real table name or table alias for a given table identifier.
     *
     * The real table name has to be specified in autogrid.xml to be taken care of.
     *
     * @param string $tableId XML identifier for the table
     * @return mixed
     */
    function getTableName($tableId)
    {
        if (!isset($this->_tableNames[$tableId])) {
            if (!$this->_config->getNode('tables/'.$tableId.'/table')) {
                Mage::log('Tablename is missing for: '.$tableId);
            }
            $this->_tableNames[$tableId] = $this->_config->getNode('tables/' . $tableId . '/table')->__toString();
        }
        return $this->_tableNames[$tableId];
    }

    /**
     * Return the grid for backend grid creation
     *
     * @param string $tableId XML identifier for the table
     * @return mixed
     */
    public function getGrid($tableId)
    {
        if (!isset($this->_grids[$tableId])) {
            if (!$this->_config->getNode('tables/'.$tableId.'/grids')) {
                Mage::log(Mage::helper('magehack_autogrid')->__('No grid information specified for: '.$this->getTableName($tableId)));
            }
            foreach ($this->_config->getNode('tables/' . $tableId . '/grid') as $gridElement) {
                $this->_grids[$tableId]= $gridElement->asCanonicalArray();
            }
        }
        return $this->_grids[$tableId];
    }

    /**
     * Return the grid for backend form creation
     *
     * @param string $tableId XML identifier for the table
     * @return mixed
     */
    public function getForm($tableId)
    {
            if (!isset($this->_forms[$tableId])) {
                if (!$this->_config->getNode('tables/'.$tableId.'/form')) {
                    Mage::log(Mage::helper('magehack_autogrid')->__('No form information specified for: '.$this->getTableName($tableId)));
                }
                $this->_forms[$tableId] = $this->_config->getNode('tables/' . $tableId . '/form')->asCanonicalArray();
            }
            return $this->_forms[$tableId];
    }

}