<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Handler\GiftCardProduct;

use Magento\Catalog\Test\Handler\CatalogProductSimple\Curl as ProductCurl;
use Magento\GiftCard\Test\Fixture\GiftCardProduct;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Config\DataInterface;
use Magento\Mtf\System\Event\EventManagerInterface;

/**
 * Create new gift card product via curl.
 */
class Curl extends ProductCurl implements GiftCardProductInterface
{
    /**
     * @param DataInterface $configuration
     * @param EventManagerInterface $eventManager
     */
    public function __construct(DataInterface $configuration, EventManagerInterface $eventManager)
    {
        parent::__construct($configuration, $eventManager);

        $this->mappingData += [
            'giftcard_type' => [
                'Virtual' => 0,
                'Physical' => 1,
                'Combined' => 2,
            ],
            'website_id' => [
                'All Websites [USD]' => 0,
            ],
            'delete' => [
                'Yes' => 1,
                'No' => 0,
            ],
            'allow_open_amount' => [
                'Yes' => 1,
                'No' => 0,
            ],
            'use_config_allow_message' => [
                'Yes' => 1,
                'No' => 0,
            ],
            'use_config_is_redeemable' => [
                'Yes' => 1,
                'No' => 0,
            ],
            'use_config_lifetime' => [
                'Yes' => 1,
                'No' => 0,
            ],
            'use_config_email_template' => [
                'Yes' => 1,
                'No' => 0,
            ],
            'allow_message' => [
                'Yes' => 1,
                'No' => 0,
            ],
            'email_template' => [
                'Gift Card(s) Purchase (Default)' => 'giftcard_email_template',
            ]
        ];
    }

    /**
     * Prepare POST data for creating product request.
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    public function prepareData(FixtureInterface $fixture)
    {
        /** @var GiftCardProduct $fixture */
        $data = parent::prepareData($fixture);

        if (isset($data['product']['giftcard_amounts'])) {
            foreach ($data['product']['giftcard_amounts'] as $key => $amounts) {
                if (!isset($amounts['website_id'])) {
                    $data['product']['giftcard_amounts'][$key]['website_id'] = 0;
                }
            }
        }

        return $data;
    }
}
