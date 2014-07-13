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
 * Adminhtml_Autogrid Controller
 * @package Magehack_Autogrid
 */
class Magehack_Autogrid_Adminhtml_AutogridController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return (bool) Mage::getSingleton('admin/session')->isAllowed('magehack_autogrid');
    }

    /**
     * Init the table
     * @return Magehack_Autogrid_Model_Table The table
     */
    protected function _initTable()
    {
        if (!$id = Mage::helper('magehack_autogrid')->getTableId()) {
            return false;
        }

        $table = Mage::getModel('magehack_autogrid/table');
        $table->setAutoGridTableId($id);
        return $table;
    }
    
    /**
     * Demo action (just for testing purposes
     * 
     * @todo remove
     */
    public function demoAction()
    {
        Zend_Debug::dump(Mage::helper('magehack_autogrid')->getTableId());
        exit('yo man!');
    }

    /**
     * Display the grid page
     */
    public function indexAction()
    {
        $this->loadLayout(array('default', 'adminhtml_autogrid_index'));
        $this->renderLayout();
    }

    /**
     * Render and return the grid block only for ajac paging, filtering and sorting.
     */
    public function ajaxGridAction()
    {
        $this->loadLayout(array('adminhtml_autogrid_ajaxgrid'));
        $this->renderLayout();
    }

    /**
     * Create an object
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit an object
     */
    public function editAction()
    {
        // The table
        if (!$table = $this->_initTable()) {
            return $this->_forward('noroute');
        }
        Mage::register('current_autogrid_table', $table);

        // The object
        $object = Mage::getModel('magehack_autogrid/genericEntity')
            ->setAutoGridTableId($table->getAutoGridTableId())
            ->load($this->getRequest()->getParam('id', null))
        ;
        Mage::register('current_generic_entity', $object);

        // Layout
        $this->loadLayout(array('default', 'adminhtml_autogrid_edit'));

        // Title
        if ($object->getId()) {
            $this->_title($this->__('Edit'));
        } else {
            $this->_title($this->__('New'));
        }

        // Render
        $this->renderLayout();
    }

    /**
     * Save generic entity
     * @return void
     */
    public function saveAction()
    {
        // The table
        if (!$table = $this->_initTable()) {
            return $this->_forward('noroute');
        }

        // The object
        $object = Mage::getModel('magehack_autogrid/genericEntity')
            ->setAutoGridTableId($table->getAutoGridTableId())
            ->load($this->getRequest()->getParam('id', null))
        ;

        // Save it
        try {
            $object->addData($this->getRequest()->getPost());
            $object->save();
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($this->__('An error occurred.'));
            $this->_redirectReferer();
            return;
        }

        // Success
        $this->_getSession()->addSuccess($this->__('Entity saved successfully.'));

        // check if 'Save and Continue'
        if ($this->getRequest()->getParam('back')) {
            return $this->_redirect('*/*/edit', array('id' => $object->getId()));
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Delete generic entity
     * @return void
     */
    public function deleteAction()
    {
        // The table
        if (!$table = $this->_initTable()) {
            return $this->_forward('noroute');
        }
        Mage::register('current_autogrid_table', $table);

        // The object
        $object = Mage::getModel('magehack_autogrid/genericEntity')
            ->setAutoGridTableId($table->getAutoGridTableId())
            ->load($this->getRequest()->getParam('id', null))
        ;

        // No object?
        if (!$object->getId()) {
            $this->_getSession()->addError($this->__('Entity not found.'));
            $this->_redirectReferer();
            return;
        }

        // Delete it
        try {
            $object->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($this->__('An error occurred.'));
            $this->_redirectReferer();
            return;
        }

        // Success
        $this->_getSession()->addSuccess($this->__('Entity deleted successfully.'));
        $this->_redirect('*/*/index');
    }
}