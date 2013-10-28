<?php
/**
 * Sitewards_CmsTeaser_Model_Observer
 *  Class that observes the following events
 *  - adminhtml_cms_page_edit_tab_design_prepare_form
 *    Add a new field set into the design tab
 *  - cms_page_save_before
 *    upload/delete teaser image files
 *
 * @category    Sitewards
 * @package     Sitewards_CmsTeaser
 * @copyright   Copyright (c) 2013 Sitewards GmbH (http://www.sitewards.com/)
 */
class Sitewards_CmsTeaser_Model_Observer
{
    /**
     * Observe the action adminhtml_cms_page_edit_tab_design_prepare_form
     *  - Update the form to include a new fieldset for the teaser image upload
     * @param Varien_Event_Observer $oObserver
     */
    public function adminhtmlCmsPageEditTabDesignPrepareForm(Varien_Event_Observer $oObserver)
    {
        /* @var Varien_Data_Form $oForm */
        $oForm = $oObserver->getData('form');

        /* @var Sitewards_CmsTeaser_Helper_Data $oCmsTeaserHelper */
        $oCmsTeaserHelper = Mage::helper('sitewards_cmsteaser');

        /* @var Mage_Cms_Model_Page $oCmsPage */
        $oCmsPage = Mage::registry('cms_page');
        $oHeaderFieldset = $oForm->addFieldset(
            'header_fieldset',
            array(
                'legend'   => $oCmsTeaserHelper->__('Teaser Information'),
                'class'    => 'fieldset-wide',
                'disabled' => false
            )
        );

        $sCmsTeaserImg = $oCmsPage->getTeaserImgSrc();
        $sMediaUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $sCmsTeaserImgPath = empty($sCmsTeaserImg) ? null : $sMediaUrl . $sCmsTeaserImg;

        $oImagePreview = Mage::getBlockSingleton('sitewards_cmsteaser/adminhtml_image_preview')
            ->setImageUrl($sCmsTeaserImgPath);

        $oHeaderFieldset->addField(
            'teaser_img_src',
            'imagefile',
            array(
                'name'               => 'teaser_img_src',
                'label'              => $oCmsTeaserHelper->__('Teaser Image Source'),
                'after_element_html' => $oImagePreview->toHtml()
            )
        );

        $oHeaderFieldset->addField(
            'teaser_img_alt',
            'text',
            array(
                'name'  => 'teaser_img_alt',
                'label' => $oCmsTeaserHelper->__('Teaser Image Alt Text')
            )
        );
    }

    /**
     * Observe the action cms_page_prepare_save
     *  - Delete the file and update the attribute with delete is requested
     *  - Upload the and update the page attribute when an upload is required
     * @param Varien_Event_Observer $oObserver
     */
    public function cmsPageSaveBefore(Varien_Event_Observer $oObserver)
    {
        /* @var $oCmsPage Mage_Cms_Model_Page */
        $oCmsPage = $oObserver->getObject();
        $this->uploadImage($oCmsPage);
    }

    /**
     * Using the object passed in,
     * Upload the new image and set the attribute against the object
     *
     * @param Mage_Cms_Model_Page $oCmsPage
     */
    protected function uploadImage($oCmsPage)
    {
        /* @var Mage_Core_Controller_Request_Http $oRequest */
        $oRequest = Mage::app()->getRequest();

        $bImgDelete = $oRequest->getParam('teaser_img_src_delete');
        if ($bImgDelete == true) {
            $sPossibleFile = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) .
                DS . $oCmsPage->getData('teaser_img_src');
            if (is_file($sPossibleFile)) {
                unlink($sPossibleFile);
            }
            $oCmsPage->setData('teaser_img_src', null);
        } else {
            if (isset($_FILES['teaser_img_src'])) {
                $aFileInformation = $_FILES['teaser_img_src'];

                $oCmsPage->setData('teaser_img_src', $oCmsPage->getOrigData('teaser_img_src'));
                if (!empty($aFileInformation['name']) && (file_exists($aFileInformation['tmp_name']))) {
                    try {
                        /* @var Varien_File_Uploader $oUploader */
                        $oUploader = new Varien_File_Uploader('teaser_img_src');
                        $oUploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
                        $oUploader->setAllowRenameFiles(true);
                        $oUploader->setFilesDispersion(false);

                        $sNewFilePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS .
                            Sitewards_CmsTeaser_Helper_Data::S_TESEAR_IMG_DIR . DS;

                        $oUploader->save($sNewFilePath, $aFileInformation['name']);
                        $sUploadedFileName = $oUploader->getUploadedFileName();

                        $oCmsPage->setData(
                            'teaser_img_src',
                            Sitewards_CmsTeaser_Helper_Data::S_TESEAR_IMG_DIR . DS . $sUploadedFileName
                        );
                    } catch (Exception $oUploadException) {
                        Mage::throwException($oUploadException->getMessage());
                    }
                }
            }
        }
    }
}
