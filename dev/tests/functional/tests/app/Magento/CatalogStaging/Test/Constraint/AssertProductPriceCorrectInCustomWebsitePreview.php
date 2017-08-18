<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Staging\Test\Page\Adminhtml\StagingUpdatePreview;
use Magento\Staging\Test\Fixture\Update;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Store\Test\Fixture\Store;

/**
 * Assert that product price is correct in custom website preview according to specified update date.
 */
class AssertProductPriceCorrectInCustomWebsitePreview extends AbstractConstraint
{
    /**
     * Assert that product price is correct in custom website preview according to specified update date.
     *
     * @param CatalogProductEdit $catalogProductEdit
     * @param CatalogProductSimple $product
     * @param Update $anotherWebsitePreview
     * @param StagingUpdatePreview $stagingUpdatePreview
     * @param Store $store
     * @param BrowserInterface $browser
     * @param float $storeViewPrice
     * @return void
     */
    public function processAssert(
        CatalogProductEdit $catalogProductEdit,
        CatalogProductSimple $product,
        Update $anotherWebsitePreview,
        StagingUpdatePreview $stagingUpdatePreview,
        Store $store,
        BrowserInterface $browser,
        $storeViewPrice
    ) {
        $catalogProductEdit->open(['id' => $product->getId()]);
        $catalogProductEdit->getProductScheduleBlock()->previewUpdate($anotherWebsitePreview->getName());
        $browser->selectWindow();
        $stagingUpdatePreview->getPreviewOptionsBlock()->switchScope($store->getName());
        $stagingUpdatePreview->getPreviewOptionsBlock()->clickPreviewInScope();
        $browser->selectWindow();
        $actualPrice = $stagingUpdatePreview->getProductInfoBlock()->getPrice();
        $browser->closeWindow();
        \PHPUnit_Framework_Assert::assertEquals(
            $storeViewPrice,
            $actualPrice,
            $anotherWebsitePreview->getName() . ' expected update price is not correct.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Expected update price is correct.';
    }
}
