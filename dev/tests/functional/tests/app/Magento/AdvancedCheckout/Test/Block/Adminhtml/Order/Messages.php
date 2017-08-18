<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Block\Adminhtml\Order;

/**
 * Backend order messages block.
 */
class Messages extends \Magento\Backend\Test\Block\Messages
{
    /**
     * Backend order error message selector.
     *
     * @var string
     */
    protected $errorMessage = '.message-error, #order-errors span.title';

    /**
     * Backend order notice message selector.
     *
     * @var string
     */
    protected $noticeMessage = '.message-notice';
}
