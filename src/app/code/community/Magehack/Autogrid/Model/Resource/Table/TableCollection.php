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

class Magehack_Autogrid_Model_Resource_Table_TableCollection
    extends Magehack_Autogrid_Model_Resource_Table_Collection
{
    /**
     * @inheritdoc
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if (!$this->isLoaded()) {
            $readAdapter = Mage::getSingleton("core/resource")->getConnection('core/read');
            $dbconfig = $readAdapter->getConfig();
            
            $select = $readAdapter->select()
                ->from('information_schema.TABLES', array('TABLE_NAME'))
                ->where('TABLE_SCHEMA=?', $dbconfig['dbname']);

            $res = $readAdapter->fetchCol($select);
            foreach ($res as $tableName) {
                $table = $this->_getTableInstance($tableName);
                $this->_addItem($table);
            }
            $this->_setIsLoaded();
        }
    }

}