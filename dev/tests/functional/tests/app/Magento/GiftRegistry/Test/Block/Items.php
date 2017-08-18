<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Class Items
 * Frontend gift registry items
 */
class Items extends Block
{
    /**
     * Product name selector in registry items grid
     *
     * @var string
     */
    protected $productName = '//tr[//a[contains(text(), "%s")]]';

    /**
     * Product quantity selector in registry items grid
     *
     * @var string
     */
    protected $productQty = '[//input[@value="%s"]]';

    /**
     * Update GiftRegistry button selector
     *
     * @var string
     */
    protected $updateGiftRegistry = '.action.update';

    /**
     * Item row selector
     *
     * @var string
     */
    protected $itemRow = '//tr[td[contains(@class,"product") and a[contains(.,"%s")]]]';

    /**
     * Info message selector
     *
     * @var string
     */
    protected $infoMessage = '//div[contains(@class, "message info")]/span';

    /**
     * Is product with appropriate quantity visible in gift registry items grid
     *
     * @param InjectableFixture $product
     * @param string|null $qty
     * @return bool
     */
    public function isProductInGrid(InjectableFixture $product, $qty = null)
    {
        $name = $product->getName();
        $productNameSelector = sprintf($this->productName, $name);
        $selector = $qty === null ? $productNameSelector : $productNameSelector . sprintf($this->productQty, $qty);
        return $this->_rootElement->find($selector, Locator::SELECTOR_XPATH)->isVisible();
    }

    /**
     * Update GiftRegistry
     *
     * @return void
     */
    public function updateGiftRegistry()
    {
        $this->_rootElement->find($this->updateGiftRegistry)->click();
    }

    /**
     * Get info message
     *
     * @return string
     */
    public function getInfoMessage()
    {
        return $this->_rootElement->find($this->infoMessage, Locator::SELECTOR_XPATH)->getText();
    }

    /**
     * Get Gift Registry item form block
     *
     * @param CatalogProductSimple $item
     * @return \Magento\GiftRegistry\Test\Block\Items\ItemForm
     */
    protected function getItemForm(CatalogProductSimple $item)
    {
        return $this->blockFactory->create(
            \Magento\GiftRegistry\Test\Block\Items\ItemForm::class,
            ['element' => $this->_rootElement->find(sprintf($this->itemRow, $item->getName()), Locator::SELECTOR_XPATH)]
        );
    }

    /**
     * Fill Gift Registry item form
     *
     * @param CatalogProductSimple $item
     * @param array $updateOptions
     * @return void
     */
    public function fillItemForm(CatalogProductSimple $item, $updateOptions)
    {
        $this->getItemForm($item)->fillForm($updateOptions);
    }

    /**
     * Get Gift Registry item form data
     *
     * @param CatalogProductSimple $item
     * @return array
     */
    public function getItemData(CatalogProductSimple $item)
    {
        return $this->getItemForm($item)->getData();
    }
}
