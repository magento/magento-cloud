<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Staging\Test\Fixture\Update;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Store\Test\Fixture\Website;
use Magento\Mtf\Util\Command\Cli\Cron;

/**
 * Assert that product info is correct in frontend according to specified update time and website.
 */
class AssertProductCorrectAfterUpdate extends AbstractConstraint
{
    /**
     * Assert that product info is correct in frontend according to specified update time and website.
     *
     * @param CatalogProductSimple $product
     * @param Update $update
     * @param CatalogProductView $catalogProductView
     * @param Cron $cron
     * @param BrowserInterface $browser
     * @param string $expectedName
     * @param Website|null $customWebsite [optional]
     * @param float|null $expectedPrice [optional]
     * @param CatalogProductSimple|null $productUpdate [optional]
     * @return void
     */
    public function processAssert(
        CatalogProductSimple $product,
        Update $update,
        CatalogProductView $catalogProductView,
        Cron $cron,
        BrowserInterface $browser,
        $expectedName,
        Website $customWebsite = null,
        $expectedPrice = null,
        CatalogProductSimple $productUpdate = null
    ) {
        // Wait for product update time comes
        sleep(60);
        // Run cron twice to force the update
        $cron->run();
        $cron->run();
        $websiteCode = $customWebsite ? 'websites/' . $customWebsite->getCode() . '/' : '';
        $browser->open($_ENV['app_frontend_url'] . $websiteCode . $product->getUrlKey() . '.html');

        \PHPUnit_Framework_Assert::assertEquals(
            $expectedPrice ? $expectedPrice : $productUpdate->getPrice(),
            $catalogProductView->getViewBlock()->getPriceBlock()->getPrice(),
            $update->getName() . ' expected price is not correct.'
        );

        \PHPUnit_Framework_Assert::assertEquals(
            $expectedName,
            $catalogProductView->getViewBlock()->getProductName(),
            $expectedName . ' expected name is not correct.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Expected product info is correct.';
    }
}
