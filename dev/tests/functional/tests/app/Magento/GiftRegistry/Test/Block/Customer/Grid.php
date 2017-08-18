<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Customer;

use Magento\Backend\Test\Block\Widget\Grid as AbstractGrid;
use Magento\GiftRegistry\TEst\Fixture\GiftRegistry;
use Magento\Mtf\Client\Locator;

/**
 * Class Grid
 * Frontend gift registry grid
 */
class Grid extends AbstractGrid
{
    /**
     * Gift registry event selector in grid
     *
     * @var string
     */
    protected $eventSelector = '.event[title*="%s"]';

    /**
     * Gift registry event action selector in grid
     *
     * @var string
     */
    protected $eventActionSelector = '//tr[td[contains(.,"%s")]]//a[contains(.,"%s")]';

    /**
     * Selector for confirm.
     *
     * @var string
     */
    protected $confirmModal = '._show[data-role=modal]';

    /**
     * Is visible gift registry in grid
     *
     * @param GiftRegistry $giftRegistry
     * @return bool
     */
    public function isGiftRegistryInGrid(GiftRegistry $giftRegistry)
    {
        return $this->_rootElement->find(sprintf($this->eventSelector, $giftRegistry->getTitle()))->isVisible();
    }

    /**
     * Click to action in appropriate event
     *
     * @param string $event
     * @param string $action
     * @param bool $acceptAlert [optional]
     * @return void
     */
    public function eventAction($event, $action, $acceptAlert = false)
    {
        $selector = sprintf($this->eventActionSelector, $event, $action);
        $this->_rootElement->find($selector, Locator::SELECTOR_XPATH)->click();
        if ($acceptAlert) {
            $element = $this->browser->find($this->confirmModal);
            /** @var \Magento\Ui\Test\Block\Adminhtml\Modal $modal */
            $modal = $this->blockFactory->create(
                \Magento\Ui\Test\Block\Adminhtml\Modal::class,
                ['element' => $element]
            );
            $modal->acceptAlert();
        }
    }
}
