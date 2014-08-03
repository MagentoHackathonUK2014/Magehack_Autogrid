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

class Magehack_Autogrid_Model_Table_Column_Backend_CreatedAt
    extends Magehack_Autogrid_Model_Table_Column_BackendAbstract
{
    public function beforeSave(Mage_Core_Model_Abstract $object)
    {
        if ($object->isObjectNew()) {
            $now = Varien_Date::now();
            $object->setData($this->getColumnName(), $now);
        }
        parent::beforeSave($object);
    }

} 