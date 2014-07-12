<?php

class Magehack_Autogrid_Block_Adminhtml_Widget_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        $collection = Mage::helper('magehack_autogrid')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }


    protected function _prepareColumns()
    {
        $table = Mage::helper('magehack_autogrid')->getCurrentTable();

        foreach ($table->getCells() as $cell) {
            $this->addColumn($cell->getName(), $cell->getGridInfo());
        }

        return parent::_prepareColumns();
    }

}