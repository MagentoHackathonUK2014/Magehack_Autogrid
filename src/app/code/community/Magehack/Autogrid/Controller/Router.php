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

class Magehack_Autogrid_Controller_Router extends Mage_Core_Controller_Varien_Router_Admin
{
    const PART_FRONTNAME = 0;
    const PART_TABLE_ID = 1;
    const PART_ACTION = 2;
    
    /**
     * Initialize Controller Router
     *
     * @param Varien_Event_Observer $observer
     */
    public function initControllerRouters(Varien_Event_Observer $observer)
    {
        /* @var $front Mage_Core_Controller_Varien_Front */
        $front = $observer->getData('front');

        // Add our router
        $front->addRouter('magehack_autogrid', $this);
    }
    
    /**
     * Match the request
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @return boolean
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        /** @var Mage_Core_Controller_Request_Http $request */
        $path = explode('/', trim($request->getPathInfo(), '/'));
        if (! $this->_isAdminhtmlRequest($request, $path)) {
            return false;
        }

        $tableId = $this->_getTableIdFromRequest($request, $path);
        if (! $tableId) {
            return false;
        }

        $helper = Mage::helper('magehack_autogrid');
        $helper->setTableId($tableId);

        $frontName = (string)Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName');
        $request->setModuleName($frontName);
        $request->setControllerName('autogrid');
        $request->setRoutingInfo(array('requested_controller' => $path[self::PART_TABLE_ID]));

        return true;
    }

    /**
     * Check whether the current request is for the adminhtml front name.
     * 
     * @param Zend_Controller_Request_Http $request
     * @param array $path
     * @return bool
     */
    private function _isAdminhtmlRequest(Zend_Controller_Request_Http $request, array $path)
    {
        if ($request->getModuleName() == 'adminhtml') {
            return true;
        }
        // Routes not gathered, can't use: $frontName = $this->getFrontNameByRoute('adminhtml');
        $frontName = (string)Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName');
        if (isset($path[self::PART_FRONTNAME]) && $frontName === $path[self::PART_FRONTNAME]) {
            return true;
        }
        return false;
    }

    /**
     * Return the table id specified by the controller part of the current request or false.
     * 
     * @param Zend_Controller_Request_Http $request
     * @param array $path
     * @return false|string
     */
    private function _getTableIdFromRequest(Zend_Controller_Request_Http $request, array $path)
    {
        $controller = $request->getControllerName();
        if (! $controller && isset($path[self::PART_TABLE_ID])) {
            $controller = $path[self::PART_TABLE_ID];
        }
        if ($controller) {
            $config = Mage::getSingleton('magehack_autogrid/config');
            $tableId = $config->getTableIdFromController($controller);
            return $tableId;
        }
        return false;
    }
}
