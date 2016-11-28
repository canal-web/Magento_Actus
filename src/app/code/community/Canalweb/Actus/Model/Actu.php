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
 * Actu model
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Model_Actu extends Mage_Catalog_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'canalweb_actus_actu';
    const CACHE_TAG = 'canalweb_actus_actu';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'canalweb_actus_actu';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'actu';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('canalweb_actus/actu');
    }

    /**
     * before save actu
     *
     * @access protected
     * @return Canalweb_Actus_Model_Actu
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the actu details page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getActuUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('canalweb_actus/actu/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('canalweb_actus/actu/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('canalweb_actus/actu/view', array('id'=>$this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author Ultimate Module Creator
     */
    public function checkUrlKey($urlKey, $active = true)
    {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * get the actu Content (full)
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getContent()
    {
        $content = $this->getData('content');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($content);
        return $html;
    }

    /**
     * save actu relation
     *
     * @access public
     * @return Canalweb_Actus_Model_Actu
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * get attribute text value
     *
     * @access public
     * @param $attributeCode
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAttributeText($attributeCode)
    {
        $text = $this->getResource()
            ->getAttribute($attributeCode)
            ->getSource()
            ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
    }

    /**
     * check if comments are allowed
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAllowComments()
    {
        if ($this->getData('allow_comment') == Canalweb_Actus_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == Canalweb_Actus_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('canalweb_actus/actu/allow_comment');
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        $values['in_rss'] = 1;
        $values['allow_comment'] = Canalweb_Actus_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }
    
}
