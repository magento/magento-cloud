<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Handler\GiftCardProduct;

use Magento\Catalog\Test\Handler\CatalogProductSimple\Webapi as SimpleProductWebapi;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Mtf\Config\DataInterface;
use Magento\Mtf\Fixture\InjectableFixture;
use Magento\Mtf\System\Event\EventManagerInterface;
use Magento\Mtf\Util\Protocol\CurlTransport\WebapiDecorator;

/**
 * Create new GiftCard product via webapi.
 */
class Webapi extends SimpleProductWebapi implements GiftCardProductInterface
{
    /**
     * @constructor
     * @param DataInterface $configuration
     * @param EventManagerInterface $eventManager
     * @param WebapiDecorator $webapiTransport
     * @param Curl $handlerCurl
     */
    public function __construct(
        DataInterface $configuration,
        EventManagerInterface $eventManager,
        WebapiDecorator $webapiTransport,
        Curl $handlerCurl
    ) {
        parent::__construct($configuration, $eventManager, $webapiTransport, $handlerCurl);
    }

    /**
     * Prepare data for creating product request.
     *
     * @return void
     */
    protected function prepareData()
    {
        parent::prepareData();
        $this->prepareGiftCardAmounts();
    }

    /**
     * Preparation of gift cart amounts data.
     *
     * @return void
     */
    protected function prepareGiftCardAmounts()
    {
        if (isset($this->fields['product']['giftcard_amounts'])) {
            $this->fields['product']['extension_attributes']['giftcard_amounts'] =
                $this->fields['product']['giftcard_amounts'];
            unset($this->fields['product']['giftcard_amounts']);
        }
    }
}
