<?php
/**
 * Sitewards_CmsTeaser_Block_Adminhtml_Cms_Page_Edit_Form
 *  Class to update the enctype for the edit page
 *
 * @category    Sitewards
 * @package     Sitewards_CmsTeaser
 * @copyright   Copyright (c) 2013 Sitewards GmbH (http://www.sitewards.com/)
 */
class Sitewards_CmsTeaser_Block_Adminhtml_Cms_Page_Edit_Form extends Mage_Adminhtml_Block_Cms_Page_Edit_Form
{
    /**
     * Add the enctype of 'multipart/form-data' to allow for image uploading via the cms page form
     *
     * @see Mage_Adminhtml_Block_Cms_Page_Edit_Form::_prepareForm()
     */
    protected function _prepareForm()
    {
        $oForm = new Varien_Data_Form(
            array(
                'id'        => 'edit_form',
                'action'    => $this->getData('action'),
                'method'    => 'post',
                'enctype'   => 'multipart/form-data'
            )
        );
        $oForm->setUseContainer(true);
        $this->setForm($oForm);
    }
}
