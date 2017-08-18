<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Eway\Test\Block\Form;

use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Mtf\Client\ElementInterface;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Payment\Test\Block\Form\PaymentCc;

/**
 * Form for filling credit card data for eWay Shared Pages.
 */
class EwaySharedPagesCc extends PaymentCc
{
    /**
     * Eway frame selector.
     *
     * @var string
     */
    private $ewayIframe = '#eway-payment-window';

    /**
     * 'Pay Now' button selector.
     *
     * @var string
     */
    private $payNowButton = '#EWAYPayNowButton';

    /**
     * Switch to the form frame, fill form and switch back. {@inheritdoc}
     *
     * @param FixtureInterface $creditCard
     * @param SimpleElement|null $element
     * @return $this
     */
    public function fill(FixtureInterface $creditCard, SimpleElement $element = null)
    {
        $iframeRootElement = $this->switchToEwayFrame();
        parent::fill($creditCard, $iframeRootElement);
        $iframeRootElement->find($this->payNowButton)->click();
        $this->browser->switchToFrame();
        return $this;
    }

    /**
     * Change the focus to a eWay frame.
     *
     * @return ElementInterface
     */
    private function switchToEwayFrame()
    {
        $iframeLocator = $this->browser->find($this->ewayIframe)->getLocator();
        $this->browser->switchToFrame($iframeLocator);
        return $this->browser->find('body');
    }
}
