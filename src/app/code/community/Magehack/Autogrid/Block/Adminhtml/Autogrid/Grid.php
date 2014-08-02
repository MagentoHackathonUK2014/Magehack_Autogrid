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
 * Grid of a generic entity representing an autogrid table
 * 
 * Class Magehack_Autogrid_Block_Adminhtml_Autogrid_Grid
 */
class Magehack_Autogrid_Block_Adminhtml_Autogrid_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * @var string
     */
    private $_tableId;

    /**
     * @var Magehack_Autogrid_Model_Table
     */
    private $_tableModel;
    
    private function _getTableId()
    {
        if (! isset($this->_tableId)) {
            $this->_tableId = Mage::helper('magehack_autogrid')->getTableId();
        }
        return $this->_tableId;
    }
    
    private function _getTableModel()
    {
        if (! isset($this->_tableModel)) {
            $this->_tableModel = Mage::helper('magehack_autogrid')->getCurrentTable();
        }
        return $this->_tableModel;
    }
    
    protected function _prepareCollection()
    {
        $tableId = $this->_getTableId();
        
        $collection = Mage::getResourceModel('magehack_autogrid/genericEntity_collection');
        $collection->setAutoGridTableId($tableId);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $table = $this->_getTableModel();

        /** @var Magehack_Autogrid_Model_Table_ColumnInterface $column */
        foreach ($table->getGridColumns() as $column) {
            $this->addColumn($column->getGridColumnId(), $column->getGridInfo());
        }
        $this->addColumn('action',
            array(
                'header' => $this->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => $this->__('Edit'),
                        'url' => array('base'=> '*/*/edit'),
                        'field' => 'id'
                    )),
                'filter' => false,
                'sortable' => false,
                'is_system' => true,
            ));
        return parent::_prepareColumns();
    }

    /**
     * Return row url for js event handlers
     *
     * @param Varien_Object $item
     * @return string
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/*/edit', array('id' => $item->getId()));
    }
}