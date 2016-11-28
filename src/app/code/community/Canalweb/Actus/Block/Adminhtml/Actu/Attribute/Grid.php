<?php
/**
 * Canalweb_Actus extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Canalweb
 * @package        Canalweb_Actus
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Actu attributes grid
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Adminhtml_Actu_Attribute_Grid extends Mage_Eav_Block_Adminhtml_Attribute_Grid_Abstract
{
    /**
     * Prepare actu attributes grid collection object
     *
     * @access protected
     * @return Canalweb_Actus_Block_Adminhtml_Actu_Attribute_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('canalweb_actus/actu_attribute_collection')
            ->addVisibleFilter();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare actu attributes grid columns
     *
     * @access protected
     * @return Canalweb_Actus_Block_Adminhtml_Actu_Attribute_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->addColumnAfter(
            'is_global',
            array(
                'header'   => Mage::helper('canalweb_actus')->__('Scope'),
                'sortable' => true,
                'index'    => 'is_global',
                'type'     => 'options',
                'options'  => array(
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE   =>
                        Mage::helper('canalweb_actus')->__('Store View'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>
                        Mage::helper('canalweb_actus')->__('Website'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL  =>
                        Mage::helper('canalweb_actus')->__('Global'),
                ),
                'align' => 'center',
            ),
            'is_user_defined'
        );
        return $this;
    }
}
