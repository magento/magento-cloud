<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GoogleTagManager\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Class AssertGtmJsPresentOnHome
 * Checks that Google Tag Manager code appears on the home page
 */
class AssertGtmJsPresentOnHome extends AbstractAssertForm
{
    /**
     * Cms index
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Browser interface
     *
     * @var BrowserInterface
     */
    protected $browser;

    /**
     * @param string $configData
     * @param CmsIndex $cmsIndex
     * @param BrowserInterface $browser
     * @param FixtureFactory $fixture
     * @return void
     */
    public function processAssert(
        $configData,
        CmsIndex $cmsIndex,
        BrowserInterface $browser,
        FixtureFactory $fixture
    ) {
        $gtmConfigData = $fixture->createByCode('configData', ['dataset' => $configData])->getData('section');
        $containerId = $gtmConfigData['google/analytics/container_id']['value'];
        $regex = "|(?s)(?<=<!-- GOOGLE TAG MANAGER -->).+" .
            $containerId . ".+(?!.*<!-- END GOOGLE TAG MANAGER -->).*$|";
        $cmsIndex->open();
        $html = $browser->getHtmlSource();
        \PHPUnit_Framework_Assert::assertRegExp($regex, $html);
    }

    /**
     * Return string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Home page HTML data is equal to data passed from dataset.';
    }
}
