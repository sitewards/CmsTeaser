Sitewards CmsTeaser
==========================

Adds Cms template with teaser in header.

Features
------------------
* Added Teaser Information section in Design Tab of Cms Page create/edit form
* Added new cms template "1 column with teaser"
* if teaser image is downloaded and template "1 column with teaser" is selected,
    your cms page will contain teaser image in its header.

File list
------------------
* app\code\community\Sitewards\CmsTeaser\Block\Adminhtml\Cms\Page\Edit\Form.php
    * Add the enctype of 'multipart/form-data' to allow for image uploading via the cms page form
* app\code\community\Sitewards\CmsTeaser\Block\Adminhtml\Image\Preview.php
    * Block for the teaser image preview on cms sites
* app\code\community\Sitewards\CmsTeaser\Block\Teaser.php
    * Load the appropriate cms page from the request uri.
    * Assign image and alt text attributes
* app\code\community\Sitewards\CmsTeaser\etc\config.xml
    * Set-up block declaration
        * Rewrite cms_page_edit_form
    * Set-up model declaration
    * Set-up helper declaration
    * Set-up resources
    * Add new page template
    * Set-up layout
    * Set-up event observers for
        * adminhtml_cms_page_edit_tab_design_prepare_form
        * cms_page_save_before
    * Set-up translations
        * aAdminhtml
* app\code\community\Sitewards\CmsTeaser\Helper\Data.php
    * Define teaser image media directory
* app\code\community\Sitewards\CmsTeaser\Model\Observer.php
    * Observes the following events
       * adminhtml_cms_page_edit_tab_design_prepare_form
         Add a new field set into the design tab
       * cms_page_save_before
         Upload/delete teaser image files
* app\code\community\Sitewards\CmsTeaser\sql\sitewards_cms_teaser_setup\mysql4-install-1.0.0.php
    * add two additional fields to cms_page table
* app\design\adminhtml\default\default\template\sitewards\cmsteaser\image\preview.phtml
    * Teaser image preview for cms page create/add page
* app\design\frontend\base\default\layout\sitewards\cmsteaser.xml
    * Define teaser block for "1 column with teaser"
* app\design\frontend\base\default\template\sitewards\cmsteaser\page\1columns-teaser.phtml
    * one column template with teaser in the header
* app\design\frontend\base\default\template\sitewards\cmsteaser\teaser.phtml
    * contains teaser html
* app\etc\modules\Sitewards_CmsTeaser.xml
    * Activate module
    * Specify community code pool
    * Set-up dependencies
        * Mage_Cms
* app\locale\de_DE\Sitewards_CmsTeaser.csv
    * Extension translation
