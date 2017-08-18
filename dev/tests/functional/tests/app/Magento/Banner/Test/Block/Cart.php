<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Banner\Test\Block;

use Magento\Banner\Test\Fixture\Banner;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Client\Locator;

/**
 * Shopping cart block.
 */
class Cart extends \Magento\Checkout\Test\Block\Cart
{
    /**
     * Widget Banner CSS selector.
     *
     * @var string
     */
    protected $widgetBanner = '//div[contains(@class, "block-banners")]/ul/li/div[contains(text(),"%s")]';

    /**
     * Header welcome message selector.
     *
     * @var string
     */
    protected $welcome = '.welcome';

    /**
     * Check Widget Banners.
     *
     * @param Banner $banner
     * @param Customer|null $customer
     * @return bool
     */
    public function checkWidgetBanners(Banner $banner, Customer $customer = null)
    {
        if ($customer !== null) {
            $browser = $this->browser;
            $welcome = $this->welcome;
            $browser->waitUntil(
                function () use ($browser, $welcome, $customer) {
                    $text = $browser->find($welcome)->getText();
                    return strpos($text, $customer->getFirstname()) ? true : null;
                }
            );
        }

        return $this->_rootElement
            ->find(sprintf($this->widgetBanner, $banner->getStoreContents()['value_0']), Locator::SELECTOR_XPATH)
            ->isVisible();
    }
}
