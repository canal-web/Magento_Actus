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
 * Actu comments controller
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Actu_Customer_CommentController extends Mage_Core_Controller_Front_Action
{
    /**
     * Action predispatch
     * Check customer authentication for some actions
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    /**
     * List comments
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('canalweb_actus/actu_customer_comment/');
        }
        if ($block = $this->getLayout()->getBlock('actu_customer_comment_list')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('My Actu Comments'));

        $this->renderLayout();
    }

    /**
     * View comment
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function viewAction()
    {
        $commentId = $this->getRequest()->getParam('id');
        $comment = Mage::getModel('canalweb_actus/actu_comment')->load($commentId);
        if (!$comment->getId() ||
            $comment->getCustomerId() != Mage::getSingleton('customer/session')->getCustomerId() ||
            $comment->getStatus() != Canalweb_Actus_Model_Actu_Comment::STATUS_APPROVED) {
            $this->_forward('no-route');
            return;
        }
        $actu = Mage::getModel('canalweb_actus/actu')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($comment->getActuId());
        if (!$actu->getId() || $actu->getStatus() != 1) {
            $this->_forward('no-route');
            return;
        }
        $stores = array(Mage::app()->getStore()->getId(), 0);
        if (count(array_intersect($stores, $comment->getStoreId())) == 0) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_comment', $comment);
        Mage::register('current_actu', $actu);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('canalweb_actus/actu_customer_comment/');
        }
        if ($block = $this->getLayout()->getBlock('customer_actu_comment')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Actu Comments'));
        $this->renderLayout();
    }
}
