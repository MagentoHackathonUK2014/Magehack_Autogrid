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

/**
 * Container of the grid of grids
 * @package Magehack_Autogrid
 */
class Magehack_Autogrid_Block_Adminhtml_Gridmaster extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     * Constructor Override
     * 
     * @return Magehack_Autogrid_Controller_Router
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_blockGroup = 'magehack_autogrid';
        $this->_controller = 'adminhtml_gridmaster';
        $this->_headerText = $this->__('All Autogrid Tables');

        return $this;
    }

    /**
     * Prepare Layout
     * 
     * @return Magehack_Autogrid_Controller_Router
     */
    protected function _prepareLayout()
    {
        $this->_removeButton('add');
        return parent::_prepareLayout();
    }

// Monsieur Biz Tag NEW_METHOD

}