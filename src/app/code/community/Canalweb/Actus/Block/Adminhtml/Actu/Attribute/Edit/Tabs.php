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
 * Adminhtml actu attribute edit page tabs
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Adminhtml_Actu_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('actu_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('canalweb_actus')->__('Attribute Information'));
    }

    /**
     * add attribute tabs
     *
     * @access protected
     * @return Canalweb_Actus_Adminhtml_Actu_Attribute_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            array(
                'label'     => Mage::helper('canalweb_actus')->__('Properties'),
                'title'     => Mage::helper('canalweb_actus')->__('Properties'),
                'content'   => $this->getLayout()->createBlock(
                    'canalweb_actus/adminhtml_actu_attribute_edit_tab_main'
                )
                ->toHtml(),
                'active'    => true
            )
        );
        $this->addTab(
            'labels',
            array(
                'label'     => Mage::helper('canalweb_actus')->__('Manage Label / Options'),
                'title'     => Mage::helper('canalweb_actus')->__('Manage Label / Options'),
                'content'   => $this->getLayout()->createBlock(
                    'canalweb_actus/adminhtml_actu_attribute_edit_tab_options'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }
}
