<?php
/**
 * Sitewards_CmsTeaser_Block_Teaser
 *
 * Block for the teaser of a cms page
 *
 * @category    Sitewards
 * @package     Sitewards_CmsTeaser
 * @copyright   Copyright (c) 2013 Sitewards GmbH (http://www.sitewards.com/)
 */
class Sitewards_CmsTeaser_Block_Teaser extends Mage_Core_Block_Template
{
    /**
     * Load the appropriate cms page from the request uri
     *  - Assign image and alt text attributes
     *
     * @see Mage_Core_Block_Abstract::_prepareLayout()
     */
    public function _prepareLayout()
    {
        $aRequestUriParts = explode('/', Mage::app()->getRequest()->getRequestUri());
        $sCmsPageKey = $aRequestUriParts[count($aRequestUriParts) - 1];

        /* @var Mage_Cms_Model_Page $oCmsPage */
        $oCmsPage =  Mage::getModel('cms/page')->load($sCmsPageKey, 'identifier');
        if ($oCmsPage->getId()) {
            $sTeaserImgSrc = $oCmsPage->getTeaserImgSrc();
            $sTeaserImgAlt = $oCmsPage->getTeaserImgAlt();
            if (!empty($sTeaserImgSrc)) {
                $sTeaserImgSrc  = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $sTeaserImgSrc;
                $this->assign('sTeaserImgSrc', $sTeaserImgSrc);
                if (!empty($sTeaserImgAlt)) {
                    $this->assign('sTeaserImgAlt', $sTeaserImgAlt);
                } else {
                    $this->assign('sTeaserImgAlt', $oCmsPage->getTitle());
                }
            }
        }
    }
}
