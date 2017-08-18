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

/**
 * Assert that product price is correct in preview according to specified update date.
 */
class AssertProductPricesCorrectInBackendPreview extends AbstractConstraint
{
    /**
     * Assert that product price is correct in preview according to specified update date.
     *
     * @param CatalogProductEdit $catalogProductEdit
     * @param CatalogProductSimple $product
     * @param StagingUpdatePreview $stagingUpdatePreview
     * @param BrowserInterface $browser
     * @param array $updates
     * @param array $prices
     * @return void
     */
    public function processAssert(
        CatalogProductEdit $catalogProductEdit,
        CatalogProductSimple $product,
        StagingUpdatePreview $stagingUpdatePreview,
        BrowserInterface $browser,
        array $updates,
        array $prices
    ) {
        $catalogProductEdit->open(['id' => $product->getId()]);
        for ($i = 0; $i < count($updates); $i++) {
            $catalogProductEdit->getProductScheduleBlock()->previewUpdate($updates[$i]->getName());
            $browser->selectWindow();
            $stagingUpdatePreview->getPreviewOptionsBlock()->switchDate($updates[$i]->getStartTime());
            $stagingUpdatePreview->getPreviewOptionsBlock()->clickPreviewInCalendar();
            $actualPrice = $stagingUpdatePreview->getProductInfoBlock()->getPrice();
            $browser->closeWindow();
            \PHPUnit_Framework_Assert::assertEquals(
                $prices[$i],
                $actualPrice,
                $updates[$i]->getName() . ' expected update price is not correct.'
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'All update prices are correct.';
    }
}
