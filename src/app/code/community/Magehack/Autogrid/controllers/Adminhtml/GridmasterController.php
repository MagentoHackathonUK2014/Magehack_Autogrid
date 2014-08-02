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
 * Adminhtml All configured autogrid tables controller
 * 
 * @package Magehack_Autogrid
 */
class Magehack_Autogrid_Adminhtml_GridmasterController extends Mage_Adminhtml_Controller_Action
{

    /**
     * Pre dispatch
     */
    public function preDispatch()
    {
        // Title
        $this->_title($this->__('Autogrid'));

        return parent::preDispatch();
    }

    /**
     * Show all the grids ;)
     */
    public function indexAction()
    {
        // Title
        $this->_title($this->__('All Autogrid Tables'));

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }

}