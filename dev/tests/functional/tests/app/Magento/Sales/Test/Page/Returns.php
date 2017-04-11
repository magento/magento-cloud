<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\Page;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Factory\Factory;
use Magento\Mtf\Page\Page;

/**
 * View returns page.
 */
class Returns extends Page
{
    /**
     * URL for returns page.
     */
    const MCA = 'sales/guest/returns';

    /**
     * Form wrapper selector.
     *
     * @var string
     */
    protected $blockSelector = 'div.order-items.returns';

    /**
     * Message selector.
     *
     * @var string
     */
    protected $messageSelector = '.page.messages .messages .message';

    /**
     * Init page. Set page url.
     *
     * @return void
     */
    protected function initUrl()
    {
        $this->url = $_ENV['app_frontend_url'] . self::MCA;
    }

    /**
     * Get returns block.
     *
     * @return \Magento\Rma\Test\Block\Returns\Returns
     */
    public function getReturnsReturnsBlock()
    {
        return Factory::getBlockFactory()->getMagentoRmaReturnsReturns(
            $this->browser->find($this->blockSelector)
        );
    }

    /**
     * Get global messages block.
     *
     * @return \Magento\Backend\Test\Block\Messages
     */
    public function getMessagesBlock()
    {
        return Factory::getBlockFactory()->getMagentoBackendMessages(
            $this->browser->find($this->messageSelector, Locator::SELECTOR_CSS)
        );
    }
}
