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
 * Actu admin edit tabs
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Adminhtml_Actu_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('actu_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('canalweb_actus')->__('Actu Information'));
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Canalweb_Actus_Block_Adminhtml_Actu_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $actu = $this->getActu();
        $entity = Mage::getModel('eav/entity_type')
            ->load('canalweb_actus_actu', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->addFieldToFilter(
            'attribute_code',
            array(
                'nin' => array('meta_title', 'meta_description', 'meta_keywords')
            )
        );
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'info',
            array(
                'label'   => Mage::helper('canalweb_actus')->__('Actu Information'),
                'content' => $this->getLayout()->createBlock(
                    'canalweb_actus/adminhtml_actu_edit_tab_attributes'
                )
                ->setAttributes($attributes)
                ->toHtml(),
            )
        );
        $seoAttributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId())
            ->addFieldToFilter(
                'attribute_code',
                array(
                    'in' => array('meta_title', 'meta_description', 'meta_keywords')
                )
            );
        $seoAttributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'meta',
            array(
                'label'   => Mage::helper('canalweb_actus')->__('Meta'),
                'title'   => Mage::helper('canalweb_actus')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'canalweb_actus/adminhtml_actu_edit_tab_attributes'
                )
                ->setAttributes($seoAttributes)
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve actu entity
     *
     * @access public
     * @return Canalweb_Actus_Model_Actu
     * @author Ultimate Module Creator
     */
    public function getActu()
    {
        return Mage::registry('current_actu');
    }
}
