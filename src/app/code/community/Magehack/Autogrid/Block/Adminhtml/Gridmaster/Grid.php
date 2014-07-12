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
 * Grid of grids
 * @package Magehack_Autogrid
 */
class Magehack_Autogrid_Block_Adminhtml_Gridmaster_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     * Get collection object
     * @return Varien_Data_Collection The collection of grids
     */
    public function getCollection()
    {
        if (!parent::getCollection()) {
            $collection = Mage::getResourceModel('magehack_autogrid/table_collection');
            $this->setCollection($collection);
        }

        return parent::getCollection();
    }

    /**
     * Prepare columns
     * @return Mbiz_Tmp_Block_Adminhtml_Foo_Grid
     */
    protected function _prepareColumns()
    {

        $this->addColumn('autogrid_table_id', [
            'header'   => Mage::helper('magehack_autogrid')->__('ID'),
            'type'     => 'text',
            'getter'   => 'getAutoGridTableId',
            'filter'   => false,
            'sortable' => false
        ]);

        $this->addColumn('title', [
            'header'   => Mage::helper('magehack_autogrid')->__('Title'),
            'type'     => 'text',
            'getter'   => 'getTitle',
            'filter'   => false,
            'sortable' => false
        ]);

        return parent::_prepareColumns();
    }

    /**
     * Return row url for js event handlers
     *
     * @param Mage_Catalog_Model_Product|Varien_Object
     * @return string
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('adminhtml/autogrid_' . $item->getId() . '/index');
    }

// Monsieur Biz Tag NEW_METHOD

}