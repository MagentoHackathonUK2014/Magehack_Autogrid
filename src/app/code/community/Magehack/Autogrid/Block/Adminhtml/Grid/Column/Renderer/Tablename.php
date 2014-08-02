<?php


class Magehack_Autogrid_Block_Adminhtml_Grid_Column_Renderer_Tablename
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        /** @var Magehack_Autogrid_Model_Table $row */
        $value = parent::render($row);
        if (! $value) {
            //$value = $row->getAutoGridTableId();
            //$value = ucwords(str_replace('_', ' ', $value));
        }
        return $value;
    }
}