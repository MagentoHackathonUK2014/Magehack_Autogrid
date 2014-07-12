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

class Magehack_Autogrid_Model_Resource_GenericEntity_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * @var string
     */
    protected $_autogridTableId;
    
    /**
     * Modify standard constructor to not directly initialize the resource model.
     * This will be done when init() is called with the autogrid table id.
     * 
     * @param Mage_Core_Model_Resource_Db_Abstract|null $resource 
     * @see Magehack_Autogrid_Model_Resource_GenericEntity_Collection::init()
     */
    public function __construct($resource = null)
    {
        // Do not call the parent __construct() method.
        
        $this->_construct();
        $this->_resource = $resource;
        
        // The following is done in init().
        // Its left here just as a reference in case the parent changes in future.
        //$this->setConnection($this->getResource()->getReadConnection());
        //$this->_initSelect();
    }
    
    protected function _construct()
    {
        $this->_init('magehack_autogrid/genericEntity');
    }

    /**
     * @return string
     */
    public function getAutoGridTableId()
    {
        return $this->_autogridTableId;
    }

    /**
     * Initialize the collection's resource to point to the specified table.
     * 
     * @param string $autoGridTableId
     */
    public function setAutoGridTableId($autoGridTableId)
    {
        $this->_autogridTableId = $autoGridTableId;
        
        /** @var Magehack_Autogrid_Model_Resource_GenericEntity $resource */
        $resource = $this->getResource();
        $resource->setAutoGridTableId($autoGridTableId);

        // Initalize the resource model
        $this->setConnection($this->getResource()->getReadConnection());
        $this->_initSelect();
    }

    public function getNewEmptyItem()
    {
        $item = parent::getNewEmptyItem();
        $item->setAutoGridTableId($this->getAutoGridTableId());
    }


}