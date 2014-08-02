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
 * Grid containing all database tables
 *
 * @package Magehack_Autogrid
 */
class Magehack_Autogrid_Block_Adminhtml_Tables_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Prepare collection object
     *
     * @return Magehack_Autogrid_Model_Resource_Table_TableCollection The collection of tables in the database
     */
    public function getCollection()
    {
        if (!parent::getCollection()) {
            $collection = Mage::getResourceModel('magehack_autogrid/table_tableCollection');
            $this->setCollection($collection);
        }

        return parent::getCollection();
    }

    /**
     * Prepare columns
     *
     * @return Magehack_Autogrid_Block_Adminhtml_Tables_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('autogrid_table_id', [
            'header' => $this->__('Autogrid Table ID'),
            'type' => 'text',
            'getter' => 'getAutoGridTableId',
            'filter' => false,
            'sortable' => false
        ]);

        $this->addColumn('title', [
            'header' => $this->__('Title'),
            'type' => 'text',
            'getter' => 'getTitle',
            'renderer' => 'magehack_autogrid/adminhtml_grid_column_renderer_tablename',
            'filter' => false,
            'sortable' => false
        ]);

        return parent::_prepareColumns();
    }

    /**
     * Return row url for js event handlers
     *
     * @param Magehack_Autogrid_Model_Table $item
     * @return string
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/autogrid_tables/view/table/' . $item->getAutoGridTableId());
    }
}