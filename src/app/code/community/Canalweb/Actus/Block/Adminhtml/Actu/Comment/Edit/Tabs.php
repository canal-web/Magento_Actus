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
 * Actu comment admin edit tabs
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Adminhtml_Actu_Comment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('actu_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('canalweb_actus')->__('Actu Comment'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Canalweb_Actus_Block_Adminhtml_Actu_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_actu_comment',
            array(
                'label'   => Mage::helper('canalweb_actus')->__('Actu comment'),
                'title'   => Mage::helper('canalweb_actus')->__('Actu comment'),
                'content' => $this->getLayout()->createBlock(
                    'canalweb_actus/adminhtml_actu_comment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_actu_comment',
                array(
                    'label'   => Mage::helper('canalweb_actus')->__('Store views'),
                    'title'   => Mage::helper('canalweb_actus')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'canalweb_actus/adminhtml_actu_comment_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve comment
     *
     * @access public
     * @return Canalweb_Actus_Model_Actu_Comment
     * @author Ultimate Module Creator
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }
}
