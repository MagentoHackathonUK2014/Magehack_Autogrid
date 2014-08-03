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

class Magehack_Autogrid_Model_Table_Column_Source_Yesno
    extends Magehack_Autogrid_Model_Table_Column_Source_Abstract
{
    /**
     * @return void
     */
    protected function _loadOptions()
    {
        $helper = Mage::helper('magehack_autogrid');
        $this->_options = array(
            array('value' => 0, 'label' => $helper->__('No')),
            array('value' => 1, 'label' => $helper->__('Yes'))
        );
    }
}