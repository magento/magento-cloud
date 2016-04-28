<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Paypal\Test\Block\Form\PayflowAdvanced;

use Magento\Mtf\Block\Mapper;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Block\BlockFactory;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Payment\Test\Block\Form\Cc;

/**
 * Class CcAdvanced
 * Card Verification frame block
 */
class CcAdvanced extends Cc
{
    /**
     * 'Pay Now' button
     *
     * @var string
     */
    protected $continue = '#btn_pay_cc';

    /**
     * Payflow Advanced iFrame locator
     *
     * @var string
     */
    protected $payflowAdvancedFrame = "#payflow-advanced-iframe";

    /**
     * @constructor
     * @param SimpleElement $element
     * @param BlockFactory $blockFactory
     * @param Mapper $mapper
     * @param BrowserInterface $browser
     */
    public function __construct(
        SimpleElement $element,
        BlockFactory $blockFactory,
        Mapper $mapper,
        BrowserInterface $browser
    ) {
        parent::__construct($element, $blockFactory, $mapper, $browser);
        $this->browser->switchToFrame(new Locator($this->payflowAdvancedFrame));
    }

    /**
     * Press "Continue" button
     *
     * @return void
     */
    public function pressContinue()
    {
        $this->_rootElement->find($this->continue)->click();
    }
}
