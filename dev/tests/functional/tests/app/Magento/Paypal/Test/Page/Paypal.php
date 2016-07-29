<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Paypal\Test\Page;

use Magento\Paypal\Test\Block;
use Magento\Mtf\Factory\Factory;
use Magento\Mtf\Page\Page;

/**
 * Paypal page.
 */
class Paypal extends Page
{
    /**
     * URL for customer login.
     */
    const MCA = 'paypal';

    /**
     * Link for customer login.
     *
     * @var string
     */
    protected $billingModuleBlock = '#billingModule';

    /**
     * Form for customer login.
     *
     * @var string
     */
    protected $loginBlock = '#loginBox';

    /**
     * Form for customer login.
     *
     * @var string
     */
    protected $expressBlock = '#main';

    /**
     * Paypal review block.
     *
     * @var string
     */
    protected $reviewBlock = '#reviewModule';

    /**
     * Paypal main panel block.
     *
     * @var string
     */
    protected $panelMainBlock = '#panelMain';

    /**
     * Init page. Set page url.
     *
     * @return void
     */
    protected function initUrl()
    {
        $this->url = 'https://www.sandbox.paypal.com/cgi-bin/';
    }

    /**
     * Get login block.
     *
     * @return \Magento\Paypal\Test\Block\Login
     */
    public function getLoginBlock()
    {
        return Factory::getBlockFactory()->getMagentoPaypalLogin($this->browser->find($this->loginBlock));
    }

    /**
     * Get login block.
     *
     * @return \Magento\Paypal\Test\Block\LoginExpress
     */
    public function getLoginExpressBlock()
    {
        return Factory::getBlockFactory()->getMagentoPaypalLoginExpress($this->browser->find($this->expressBlock));
    }

    /**
     * Get review block.
     *
     * @return \Magento\Paypal\Test\Block\Review
     */
    public function getReviewBlock()
    {
        return Factory::getBlockFactory()->getMagentoPaypalReview($this->browser->find($this->reviewBlock));
    }

    /**
     * Get review block.
     *
     * @return \Magento\Paypal\Test\Block\ReviewExpress
     */
    public function getReviewExpressBlock()
    {
        return Factory::getBlockFactory()->getMagentoPaypalReviewExpress($this->browser->find($this->expressBlock));
    }

    /**
     * Get billing module block.
     *
     * @return \Magento\Paypal\Test\Block\Billing
     */
    public function getBillingBlock()
    {
        return Factory::getBlockFactory()->getMagentoPaypalBilling($this->browser->find($this->billingModuleBlock));
    }

    /**
     * Get main panel block.
     *
     * @return \Magento\Paypal\Test\Block\MainPanel
     */
    public function getMainPanelBlock()
    {
        return Factory::getBlockFactory()->getMagentoPaypalMainPanel($this->browser->find($this->panelMainBlock));
    }
}
