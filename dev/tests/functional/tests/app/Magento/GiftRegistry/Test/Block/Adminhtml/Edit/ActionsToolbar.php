<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Adminhtml\Edit;

use Magento\Mtf\Block\Block;

/**
 * Class ActionsToolbar
 * Gift registry backend actions block
 */
class ActionsToolbar extends Block
{
    /**
     * "Delete Registry" button
     *
     * @var string
     */
    protected $deleteRegistry = '.delete';

    /**
     * Selector for confirm.
     *
     * @var string
     */
    protected $confirmModal = '.confirm._show[data-role=modal]';

    /**
     * Click on "Delete Registry" button
     *
     * @return void
     */
    public function delete()
    {
        $this->_rootElement->find($this->deleteRegistry)->click();
        $element = $this->browser->find($this->confirmModal);
        /** @var \Magento\Ui\Test\Block\Adminhtml\Modal $modal */
        $modal = $this->blockFactory->create(\Magento\Ui\Test\Block\Adminhtml\Modal::class, ['element' => $element]);
        $modal->acceptAlert();
    }
}
