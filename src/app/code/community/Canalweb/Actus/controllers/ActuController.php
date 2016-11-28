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
 * Actu front contrller
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_ActuController extends Mage_Core_Controller_Front_Action
{

    /**
      * default action
      *
      * @access public
      * @return void
      * @author Ultimate Module Creator
      */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('canalweb_actus/actu')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('canalweb_actus')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'actus',
                    array(
                        'label' => Mage::helper('canalweb_actus')->__('Actus'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('canalweb_actus/actu')->getActusUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('canalweb_actus/actu/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('canalweb_actus/actu/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('canalweb_actus/actu/meta_description'));
        }
        $this->renderLayout();
    }

    /**
     * init Actu
     *
     * @access protected
     * @return Canalweb_Actus_Model_Actu
     * @author Ultimate Module Creator
     */
    protected function _initActu()
    {
        $actuId   = $this->getRequest()->getParam('id', 0);
        $actu     = Mage::getModel('canalweb_actus/actu')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($actuId);
        if (!$actu->getId()) {
            return false;
        } elseif (!$actu->getStatus()) {
            return false;
        }
        return $actu;
    }

    /**
     * view actu action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function viewAction()
    {
        $actu = $this->_initActu();
        if (!$actu) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_actu', $actu);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('actus-actu actus-actu' . $actu->getId());
        }
        if (Mage::helper('canalweb_actus/actu')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('canalweb_actus')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'actus',
                    array(
                        'label' => Mage::helper('canalweb_actus')->__('Actus'),
                        'link'  => Mage::helper('canalweb_actus/actu')->getActusUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'actu',
                    array(
                        'label' => $actu->getTitle(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $actu->getActuUrl());
        }
        if ($headBlock) {
            if ($actu->getMetaTitle()) {
                $headBlock->setTitle($actu->getMetaTitle());
            } else {
                $headBlock->setTitle($actu->getTitle());
            }
            $headBlock->setKeywords($actu->getMetaKeywords());
            $headBlock->setDescription($actu->getMetaDescription());
        }
        $this->renderLayout();
    }

    /**
     * actus rss list action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function rssAction()
    {
        if (Mage::helper('canalweb_actus/actu')->isRssEnabled()) {
            $this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
            $this->loadLayout(false);
            $this->renderLayout();
        } else {
            $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            $this->getResponse()->setHeader('Status', '404 File not found');
            $this->_forward('nofeed', 'index', 'rss');
        }
    }

    /**
     * Submit new comment action
     * @access public
     * @author Ultimate Module Creator
     */
    public function commentpostAction()
    {
        $data   = $this->getRequest()->getPost();
        $actu = $this->_initActu();
        $session    = Mage::getSingleton('core/session');
        if ($actu) {
            if ($actu->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() ||
                    Mage::getStoreConfigFlag('canalweb_actus/actu/allow_guest_comment'))) {
                    $comment  = Mage::getModel('canalweb_actus/actu_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setActuId($actu->getId())
                                ->setStatus(Canalweb_Actus_Model_Actu_Comment::STATUS_PENDING)
                                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                ->setStores(array(Mage::app()->getStore()->getId()))
                                ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        } catch (Exception $e) {
                            $session->setActuCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    } else {
                        $session->setActuCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        } else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                } else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            } else {
                $session->addError($this->__('This actu does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }
}
