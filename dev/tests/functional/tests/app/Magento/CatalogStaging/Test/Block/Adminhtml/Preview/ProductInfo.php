<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Block\Adminhtml\Preview;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Product info block on product page in preview.
 */
class ProductInfo extends Block
{
    /**
     * Product price CSS selector.
     *
     * @var string
     */
    private $productPrice = '.product-info-price .price';

    /**
     * Iframe CSS selector.
     *
     * @var string
     */
    private $iFrame = 'iframe[data-role="preview-iframe"]';

    /**
     * Returns product price.
     *
     * @return float
     */
    public function getPrice()
    {
        $this->waitForElementVisible($this->iFrame);
        $this->browser->switchToFrame(new Locator($this->iFrame));
        return (float)preg_replace("/[^\-\.0-9]/", "", $this->browser->find($this->productPrice)->getText());
    }
}
