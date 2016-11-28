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
 * Actu RSS block
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Actu_Rss extends Mage_Rss_Block_Abstract
{
    /**
     * Cache tag constant for feed reviews
     *
     * @var string
     */
    const CACHE_TAG = 'block_html_actus_actu_rss';

    /**
     * constructor
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->setCacheTags(array(self::CACHE_TAG));
        /*
         * setting cache to save the rss for 10 minutes
         */
        $this->setCacheKey('canalweb_actus_actu_rss');
        $this->setCacheLifetime(600);
    }

    /**
     * toHtml method
     *
     * @access protected
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _toHtml()
    {
        $url    = Mage::helper('canalweb_actus/actu')->getActusUrl();
        $title  = Mage::helper('canalweb_actus')->__('Actus');
        $rssObj = Mage::getModel('rss/rss');
        $data  = array(
            'title'       => $title,
            'description' => $title,
            'link'        => $url,
            'charset'     => 'UTF-8',
        );
        $rssObj->_addHeader($data);
        $collection = Mage::getModel('canalweb_actus/actu')->getCollection()
            ->addFieldToFilter('status', 1)
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('in_rss', 1)
            ->setOrder('created_at');
        $collection->load();
        foreach ($collection as $item) {
            $description = '<p>';
            $description .= '<div>'.
                Mage::helper('canalweb_actus')->__('Title').': 
                '.$item->getTitle().
                '</div>';
            $description .= '<div>'.
                Mage::helper('canalweb_actus')->__('Short content').': 
                '.$item->getShortcontent().
                '</div>';
            if ($item->getImage()) {
                $description .= '<div>';
                $description .= Mage::helper('canalweb_actus')->__('Image');
                $description .= '<img src="'.Mage::helper('canalweb_actus/actu_image')->init($item, 'image')->resize(75).'" alt="'.$this->escapeHtml($item->getTitle()).'" />';
                $description .= '</div>';
            }
            $description .= '</p>';
            $data = array(
                'title'       => $item->getTitle(),
                'link'        => $item->getActuUrl(),
                'description' => $description
            );
            $rssObj->_addEntry($data);
        }
        return $rssObj->createRssXml();
    }
}
