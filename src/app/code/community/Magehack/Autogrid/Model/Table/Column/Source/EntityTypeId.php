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

class Magehack_Autogrid_Model_Table_Column_Source_EntityTypeId
    extends Magehack_Autogrid_Model_Table_Column_Source_Abstract
{
    /**
     * Populate the $_options property
     *
     * @return null
     */
    function _loadOptions()
    {
        /** @var Mage_Eav_Model_Resource_Entity_Type_Collection $collection */
        $collection = Mage::getResourceModel('eav/entity_type_collection');
        /** @var Mage_Eav_Model_Entity_Type $model */
        foreach ($collection as $id => $model) {
            $this->_options[] = array(
                'value' => $id,
                'label' => $model->getData('entity_type_code')
            );
        }
    }
}