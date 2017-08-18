<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Block\Adminhtml\Preview;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Block with categories in product update campaign preview.
 */
class Navigation extends Block
{
    /**
     * Iframe CSS selector.
     *
     * @var string
     */
    private $iFrame = 'iframe[data-role="preview-iframe"]';

    /**
     * Category link Xpath selector.
     *
     * @var string
     */
    private $categorySelector = '//li/a/span[contains(text(), "%s")]';

    /**
     * Open category.
     *
     * @param string $categoryName
     * @return void
     */
    public function openCategory($categoryName)
    {
        $this->waitForElementVisible($this->iFrame);
        $this->browser->switchToFrame(new Locator($this->iFrame));
        $this->browser->find(sprintf($this->categorySelector, $categoryName), Locator::SELECTOR_XPATH)->click();
        $this->browser->selectWindow();
    }
}
