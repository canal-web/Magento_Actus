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
 * Actu widget block
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Actu_Widget_View extends Mage_Core_Block_Template implements
    Mage_Widget_Block_Interface
{
    protected $_htmlTemplate = 'canalweb_actus/actu/widget/view.phtml';

    /**
     * Prepare a for widget
     *
     * @access protected
     * @return Canalweb_Actus_Block_Actu_Widget_View
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $actuId = $this->getData('actu_id');
        if ($actuId) {
            $actu = Mage::getModel('canalweb_actus/actu')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($actuId);
            if ($actu->getStatus()) {
                $this->setCurrentActu($actu);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
