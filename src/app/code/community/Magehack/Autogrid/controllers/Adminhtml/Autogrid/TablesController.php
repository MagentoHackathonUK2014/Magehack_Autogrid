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
 * Adminhtml autogrid all tables grid controller
 * 
 * @package Magehack_Autogrid
 */
class Magehack_Autogrid_Adminhtml_Autogrid_TablesController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Pre dispatch
     */
    public function preDispatch()
    {
        // Title
        $this->_title($this->__('Autogrid Table List'));

        return parent::preDispatch();
    }

    /**
     * Show all the grids ;)
     */
    public function indexAction()
    {
        // Title
        $this->_title($this->__('All Magento Tables'));

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Display grid for selected table
     */
    public function viewAction()
    {
        // @todo $table = $this->_initTable
        $this->_title($this->__('Table "%s"', '[TABLE TITLE]'));
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function editAction()
    {
        Mage::throwException('@todo: implement');
    }
    
    public function saveAction()
    {
        Mage::throwException('@todo: implement');
    }
    
    public function deleteAction()
    {
        Mage::throwException('@todo: implement');
    }

    /**
     * Is allowed?
     * 
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')
            ->isAllowed('system/autogrid_tables');
    }

}