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

class Magehack_Autogrid_Model_Resource_Table_Parser
    extends Mage_Core_Model_Resource_Db_Abstract
    implements Magehack_Autogrid_Model_Resource_Table_ParserInterface
{

    protected $_primaryKey = null;
    protected $_cols = array();
    protected $_title = '';

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
        $this->_cols = array();
        foreach ($struct as $name => $info) {
            //Mage::log($name."\n",null,'autogrid.log');
            $this->_cols[] = array($name=>$info['DATA_TYPE']);
        }
        $c = $ra->getConfig();
        $data = $ra->fetchRow("SELECT table_comment FROM INFORMATION_SCHEMA.TABLES WHERE table_schema=? AND table_name=?",array($c['dbname'],$tableName));
        if (isset($data['table_comment'])) $this->_title = $data['table_comment'];
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
     * @return array|array
     */
    public function getTableColumns()
    {
        return $this->_cols;
    }

    /**
     * @return array|string
     */
    public function getTableColumnByName($name)
    {
        if (isset($this->_cols[$name])) {
            return $this->_cols[$name];
        }
        return null;
    }

    /**
     * Retrieve the title
     * @return string
     */
    public function getTableTitle()
    {
        return $this->_title;
    }

}
