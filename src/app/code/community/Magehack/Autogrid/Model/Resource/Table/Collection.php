<?php
/**
 * Magento Hackathon 2014 UK
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Magehack
 * @package    Magehack_Autogrid
 * @copyright  Copyright (c) 2014 Magento community
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

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
        if (!$this->isLoaded()) {
            foreach ($this->_getConfig()->getAllTableIds() as $tableId) {
                $table = $this->_getTableInstance($tableId);
                $this->_addItem($table);
            }
            $this->_setIsLoaded();
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