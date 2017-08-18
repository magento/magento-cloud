<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Staging\Test\Fixture\Update;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Store\Test\Fixture\Website;
use Magento\Mtf\Util\Command\Cli\Cron;

class AssertProductSpecialPriceCorrectAfterUpdate extends AbstractConstraint
{
    public function processAssert(
        CatalogProductSimple $firstProduct,
        CatalogProductEdit $catalogProductEdit,
        BrowserInterface $browser,
        Cron $cron,
        CatalogProductView $catalogProductView
    ) {
        //Run the cron three times to force the update
        $cron->run();
        $cron->run();
        $cron->run();

        $catalogProductEdit->open(['id'=> $firstProduct->getId()]);
        $catalogProductEdit->getFormPageActions()->save();

        $browser->open($_ENV['app_frontend_url'] . $firstProduct->getUrlKey() . '.html');

        \PHPUnit_Framework_Assert::assertEquals(
            $firstProduct->getSpecialPrice(),
            $catalogProductView->getViewBlock()->getPriceBlock()->getSpecialPrice(),
            'First Product special price NOT equal to the special price passed in from fixture.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Special price is correct after the update is applied on a separate product.';
    }
}
