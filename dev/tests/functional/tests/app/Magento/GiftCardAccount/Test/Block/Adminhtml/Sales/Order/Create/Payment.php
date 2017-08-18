<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Block\Adminhtml\Sales\Order\Create;

use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;
use Magento\Backend\Test\Block\Template;

/**
 * Gift card account block in order new page.
 */
class Payment extends Form
{
    /**
     * Backend abstract block.
     *
     * @var string
     */
    protected $templateBlock = './ancestor::body';

    /**
     * Selector for "Add Gift Card" button.
     *
     * @var string
     */
    protected $addGiftCardButton = "//button[preceding::input[contains(@id, 'giftcardaccount')]]";

    /**
     * Click "Add Gift Card" button.
     *
     * @return void
     */
    protected function clickAddGiftCard()
    {
        $this->_rootElement->find($this->addGiftCardButton, Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Apply gift card account on order new page.
     *
     * @param GiftCardAccount $giftCard
     * @return void
     */
    public function applyGiftCardAccount(GiftCardAccount $giftCard)
    {
        $this->fill($giftCard);
        $this->clickAddGiftCard();
        $this->getTemplateBlock()->waitLoader();
    }

    /**
     * Get backend abstract block.
     *
     * @return Template
     */
    protected function getTemplateBlock()
    {
        return $this->blockFactory->create(
            \Magento\Backend\Test\Block\Template::class,
            ['element' => $this->_rootElement->find($this->templateBlock, Locator::SELECTOR_XPATH)]
        );
    }
}
