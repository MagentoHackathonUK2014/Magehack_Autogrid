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

    protected $_sourceModels;

    protected $_tables;

    public function __construct()
    {
        $this->_config = Mage::getConfig()->loadModulesConfiguration('autogrid.xml');
    }


    /**
     * Get all table configurations in one go.
     *
     * @return array
     */
    public function getTables()
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
    public function getTableName($tableId)
    {
        if (!isset($this->_tableNames[$tableId])) {
            if (!$this->_config->getNode('tables/'.$tableId.'/table')) {
//                Mage::log('Tablename is missing for: '.$tableId);
                return $tableId;
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
                return false;
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
                    return false;
                }
                $this->_forms[$tableId] = $this->_config->getNode('tables/' . $tableId . '/form')->asCanonicalArray();
            }
            return $this->_forms[$tableId];
    }

    /**
     * Return the source model for grid or form
     *
     * @param string $tableId XML identifier for the table
     * @param $part grid|form for which part the source model should be
     * @return mixed
     */
    public function getSourceModel($tableId, $part)
    {
        if($node = $this->_config->getNode('tables/'.$tableId.'/'.$part.'/source_model')) {
            return Mage::getModel(substr($node->__toString(), 0, strpos($node->__toString(), '::')));
        }
        return false;
    }

    /**
     * Return Source Model Options based on Model and specified Methods
     *
     * @param string $tableId XML identifier for the table
     * @param string $part grid|form for which part the source model should be
     * @param string $columnId column-identifier
     * @return mixed
     */
    public function getOptions($tableId, $part, $columnId)
    {

        if($node = $this->_config->getNode('tables/'.$tableId.'/'.$part.'/columns/'.$columnId.'/source_model')) {
            $data = explode('::', $node->__toString());
            if (empty($data[1]) && $part == 'grid') {
                $data[1] = 'getFlatOptionArray';
            } elseif (empty($data[1]) && $part == 'form') {
                $data[1] = 'getSourceOptionsArray';
            }
            return Mage::getSingleton($data[0])->{$data[1]}();
        }
        return false;
    }

    /**
     * Return all identifiers from the config for easier looping
     *
     * @return mixed array of all tableidentifiers defined in the configs
     */
    public function getTableIds()
    {
        if (!isset($this->_tables)) {

            foreach ($this->_config->getNode('tables')->asArray() as $tableId => $table) {
                $this->_tables[] = $tableId;
            }
        }
        return $this->_tables;
    }

    /**
     * Return the table title if configured, otherwise an empty string
     *
     * @param string $tableId
     * @return string
     */
    public function getTableTitle($tableId)
    {
        if($node = $this->_config->getNode('tables/'.$tableId.'/title')) {
            return (string) $node;
        }
        return false;
    }

    /**
     * If a default source model is specified for the given column name, return that config value.
     *
     * NOTE: This config value comes from the regular (config.xml) config, not the autogrid.xml.
     *
     * @param string $columnName
     * @return string|false
     */
    public function getDefaultSourceModel($columnName)
    {
        $path = 'adminhtml/autogrid/column_type_defaults/source_model';
        $sourceModelClass = Mage::getConfig()->getNode($path);
        if (! $sourceModelClass) {
            return false;
        }
        return (string) $sourceModelClass;
    }
}