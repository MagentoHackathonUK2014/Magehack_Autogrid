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

    protected $_sourModels;

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
                return false;
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
     * @return mixed
     */
    public function getOptions($tableId, $part)
    {
        if($node = $this->_config->getNode('tables/'.$tableId.'/'.$part.'/source_model')) {
            $data = explode('::', $node->__toString());
            return Mage::getModel($data[0])->{$data[1]}();
        }
    }


}