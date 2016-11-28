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
 * Admin search model
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Model_Adminhtml_Search_Actu extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Canalweb_Actus_Model_Adminhtml_Search_Actu
     * @author Ultimate Module Creator
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('canalweb_actus/actu_collection')
            ->addAttributeToFilter('title', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $actu) {
            $arr[] = array(
                'id'          => 'actu/1/'.$actu->getId(),
                'type'        => Mage::helper('canalweb_actus')->__('Actu'),
                'name'        => $actu->getTitle(),
                'description' => $actu->getTitle(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/actus_actu/edit',
                    array('id'=>$actu->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
