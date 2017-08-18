<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Block\Adminhtml\Preview;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Products block on category page in preview.
 */
class ProductsList extends Block
{
    /**
     * Iframe CSS selector.
     *
     * @var string
     */
    private $iFrame = 'iframe[data-role="preview-iframe"]';

    /**
     * View product link Xpath selector.
     *
     * @var string
     */
    private $productSelector = '//a[contains(text(), "%s")]';

    /**
     * Open product.
     *
     * @param string $productName
     * @return void
     */
    public function openProduct($productName)
    {
        $this->waitForElementVisible($this->iFrame);
        $this->browser->switchToFrame(new Locator($this->iFrame));
        $this->browser->find(sprintf($this->productSelector, $productName), Locator::SELECTOR_XPATH)->click();
        $this->browser->selectWindow();
    }
}
