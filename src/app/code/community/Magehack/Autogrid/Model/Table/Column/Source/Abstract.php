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
 * Class Magehack_Autogrid_Model_Table_Column_Source_Abstract
 * 
 * If the source model should simply use a collection, just set the properties
 * - $_collection
 * - $_labelAttribute
 * - $_valueAttribute (defaults to getId())
 *
 * Its also possible to simply implement _loadOptions() to populate the
 * $_options array without using a collection at all.
 */
abstract class Magehack_Autogrid_Model_Table_Column_Source_Abstract
    implements Magehack_Autogrid_Model_Table_Column_SourceInterface
{
    /**
     * Array of options in source format
     * 
     * @var array
     */
    protected $_options;

    /**
     * The collection to use as a source
     * 
     * @var string
     */
    protected $_collection;

    /**
     * The attribute read as the label
     * 
     * @var string
     */
    protected $_labelAttribute;

    /**
     * The attribute read as the value. Defaults to getId() return value if not set
     * 
     * @var string
     */
    protected $_valueAttribute;

    /**
     * Return the options as a key => value array
     *
     * Example:
     *
     * array(value => 'The Label', ...)
     *
     * @return array
     */
    public function getGridOptionArray()
    {
        if (! isset($this->_options)) {
            $this->_loadOptions();
        }
        $flatOptions = array();
        foreach ($this->_options as $option) {
            $flatOptions[$option['value']] = $option['label'];
        }
        return $flatOptions;
    }

    /**
     * Return the options as a Magneto options array
     *
     * Example:
     *
     * array(
     *      array('value' => value, 'label' => 'The Label'),
     *      ...
     * )
     *
     * @return array
     */
    public function getFormOptionArray()
    {
        if (! isset($this->_options)) {
            $this->_loadOptions();
        }
        return $this->_options;
    }

    /**
     * Populate the $_options property
     * 
     * @return void
     */
    protected function _loadOptions()
    {
        $collection = $this->_getCollection();
        foreach ($collection as $id => $model) {
            if (isset($this->_valueAttribute)) {
                $value = $model->getData($this->_valueAttribute);
            } else {
                $value = $model->getId();
            }
            $label = $model->getData($this->_labelAttribute);
            $this->_options[] = array('value' => $value, 'label' => $label);
        }
    }
    
    protected function _getCollection()
    {
        if (! isset($this->_collection)) {
            Mage::throwException('No collection class set on ' . get_class($this));
        }
        $collection = Mage::getResourceModel($this->_collection);
        return $collection;
    }
}