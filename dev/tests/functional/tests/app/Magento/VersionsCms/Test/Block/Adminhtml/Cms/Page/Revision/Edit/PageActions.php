<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Block\Adminhtml\Cms\Page\Revision\Edit;

use Magento\Backend\Test\Block\FormPageActions;

/**
 * PageActions for the role edit page.
 */
class PageActions extends FormPageActions
{
    /**
     * "Preview" button.
     *
     * @var string
     */
    protected $previewButton = '[data-ui-id="revision-info-preview-button"]';

    /**
     * "Publish" button.
     *
     * @var string
     */
    protected $publishButton = '#publish_button';

    /**
     * Click 'Publish' button.
     *
     * @return void
     */
    public function publish()
    {
        $this->_rootElement->find($this->publishButton)->click();
    }

    /**
     * "Save in a new version" button.
     *
     * @var string
     */
    protected $newVersionButton = '[data-ui-id="revision-info-new-version-button"]';

    /**
     * Selector for confirm.
     *
     * @var string
     */
    protected $confirmModal = '._show[data-role=modal]';

    /**
     * Click on Preview button.
     *
     * @return void
     */
    public function preview()
    {
        $this->_rootElement->find($this->previewButton)->click();
    }

    /**
     * Save revision as new version.
     *
     * @param string $versionName
     * @return void
     */
    public function saveInNewVersion($versionName)
    {
        $this->_rootElement->find($this->newVersionButton)->click();
        $element = $this->browser->find($this->confirmModal);
        /** @var \Magento\Ui\Test\Block\Adminhtml\Modal $modal */
        $modal = $this->blockFactory->create('Magento\Ui\Test\Block\Adminhtml\Modal', ['element' => $element]);
        $modal->setAlertText($versionName);
        $modal->acceptAlert();
    }
}
