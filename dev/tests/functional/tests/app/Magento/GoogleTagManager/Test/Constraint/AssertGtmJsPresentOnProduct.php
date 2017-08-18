<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GoogleTagManager\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Class AssertGtmJsPresentOnProduct
 * Checks that Google Tag Manager code appears on the product page
 */
class AssertGtmJsPresentOnProduct extends AbstractAssertForm
{
    /**
     * Cms index
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Catalog category view page
     *
     * @var CatalogCategoryView
     */
    protected $catalogCategoryView;

    /**
     * Browser interface
     *
     * @var BrowserInterface
     */
    protected $browser;

    /**
     * @param array $products
     * @param CmsIndex $cmsIndex
     * @param CatalogCategoryView $catalogCategoryView
     * @param BrowserInterface $browser
     * @return void
     */
    public function processAssert(
        $products,
        CmsIndex $cmsIndex,
        CatalogCategoryView $catalogCategoryView,
        BrowserInterface $browser
    ) {
        $product = $products['product'];
        $promotionsProduct = $products['promoted_product'];
        $this->cmsIndex = $cmsIndex;
        $this->catalogCategoryView = $catalogCategoryView;
        $this->openProduct($product);
        $html = $browser->getHtmlSource();
        $gtmVariations = [
            "productDetail" => $product,
            '\[.catalog.product.related.\]' => $promotionsProduct,
            '\[.product.info.upsell.\]' => $promotionsProduct
        ];
        foreach ($gtmVariations as $gtmType => $gtmProduct) {
            $tagManagerData = $this->getTagManagerData($gtmProduct);
            $regex = "|(?s)(?<=" . $gtmType . ").+" . implode('.+', $tagManagerData) . "[^}]+|";
            \PHPUnit_Framework_Assert::assertRegExp($regex, $html);
        }
    }

    protected function openProduct($product)
    {
        $category = $product->getCategoryIds()[0];
        $this->cmsIndex->open();
        $this->cmsIndex->getTopmenu()->selectCategoryByName($category);
        $this->catalogCategoryView->getListProductBlock()->getProductItem($product)->open();
    }

    protected function getTagManagerData($product)
    {
        $gtmData = [
            'id',
            $product->getSku(),
            'name',
            $product->getName(),
            #@TODO wait for fix results absence of getCurrentCategory() method
            #'category',
            #$product->getCategoryIds()[0]
        ];
        return $gtmData;
    }

    /**
     * Return string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Product page HTML data is equal to data passed from dataset.';
    }
}
