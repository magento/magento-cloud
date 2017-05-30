<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Constraint;

use Magento\Catalog\Test\Constraint\AssertProductDuplicateForm;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductIndex;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Assert form data equals fixture data.
 */
class AssertGiftCardDuplicateForm extends AssertProductDuplicateForm
{
    /**
     * Assert form data equals duplicate gift card data.
     *
     * @param FixtureInterface $product
     * @param CatalogProductIndex $productGrid
     * @param CatalogProductEdit $productPage
     * @return void
     */
    public function processAssert(
        FixtureInterface $product,
        CatalogProductIndex $productGrid,
        CatalogProductEdit $productPage
    ) {
        $filter = ['sku' => $product->getSku() . '-1'];
        $productGrid->open();
        $productGrid->getProductGrid()->searchAndOpen($filter);

        $formData = $productPage->getProductForm()->getData($product);
        $fixtureData = $this->prepareFixtureData($product->getData());

        $errors = $this->verifyData($fixtureData, $formData);
        \PHPUnit_Framework_Assert::assertEmpty($errors, $errors);
    }
}
