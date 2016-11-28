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
 * Actu list block
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Actu_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $actus = Mage::getResourceModel('canalweb_actus/actu_collection')
                         ->setStoreId(Mage::app()->getStore()->getId())
                         ->addAttributeToSelect('*')
                         ->addAttributeToFilter('status', 1);
        $actus->setOrder('title', 'asc');
        $this->setActus($actus);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Canalweb_Actus_Block_Actu_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'canalweb_actus.actu.html.pager'
        )
        ->setCollection($this->getActus());
        $this->setChild('pager', $pager);
        $this->getActus()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
