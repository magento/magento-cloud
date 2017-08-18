<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reminder\Test\Block\Adminhtml\Reminder;

/**
 * Page actions block on reminder view page(backend).
 */
class FormPageActions extends \Magento\Backend\Test\Block\FormPageActions
{
    /**
     * Locator for "Run Now" button.
     *
     * @var string
     */
    protected $runNowButton = '#run_now';

    /**
     * Selector for confirm.
     *
     * @var string
     */
    protected $confirmModal = '._show[data-role=modal]';

    /**
     * Click on "Run Now" button.
     *
     * @return void
     */
    public function runNow()
    {
        $this->_rootElement->find($this->runNowButton)->click();
        $element = $this->browser->find($this->confirmModal);
        /** @var \Magento\Ui\Test\Block\Adminhtml\Modal $modal */
        $modal = $this->blockFactory->create(\Magento\Ui\Test\Block\Adminhtml\Modal::class, ['element' => $element]);
        $modal->acceptAlert();
    }
}
