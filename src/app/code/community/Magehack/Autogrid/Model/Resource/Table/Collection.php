<?php


class Magehack_Autogrid_Model_Resource_Table_Collection
    extends Varien_Data_Collection
{
    protected $_itemObjectClass = 'Magehack_Autogrid_Model_Table';

    /**
     * @var Magehack_Autogrid_Model_Config
     */
    protected $_config;

    /**
     * Setter DI for config model
     *
     * @param Magehack_Autogrid_Model_ConfigInterface $config
     * @return $this
     */
    public function setConfig(Magehack_Autogrid_Model_ConfigInterface $config)
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * @return Magehack_Autogrid_Model_ConfigInterface
     */
    protected function _getConfig()
    {
        if (! isset($this->_config)) {
            $this->_config = Mage::getSingleton('magehack_autogrid/config');
        }
        return $this->_config;
    }

    /**
     * @inheritdoc
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        foreach ($this->_getConfig()->getTableIds() as $tableId) {
            $table = $this->_getTableInstance($tableId);
            $this->_addItem($table);
        }
    }

    /**
     * @param string $tableId
     * @return Magehack_Autogrid_Model_Table
     */
    protected function _getTableInstance($tableId)
    {
        $config = $this->_getConfig();
        
        /** @var Magehack_Autogrid_Model_Table $table */
        $table = $this->getNewEmptyItem();
        $table->setConfig($config);
        $table->setAutoGridTableId($tableId);
        return $table;
    }
}