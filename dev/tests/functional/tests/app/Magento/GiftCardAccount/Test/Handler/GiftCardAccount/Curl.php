<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Handler\GiftCardAccount;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlInterface;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Curl for creating gift card account.
 */
class Curl extends AbstractCurl implements GiftCardAccountInterface
{
    /**
     * Data mapping.
     *
     * @var array
     */
    protected $mappingData = [
        'status' => [
            'Yes' => 1,
            'No' => 1,
        ],
        'is_redeemable' => [
            'Yes' => 1,
            'No' => 1,
        ],
    ];

    /**
     * Active tab info link.
     *
     * @var string
     */
    protected $activeTabInfo = 'admin/giftcardaccount/save/active_tab/info/';

    /**
     * Gift card account generate link.
     *
     * @var string
     */
    protected $generate = 'admin/giftcardaccount/generate/';

    /**
     * Create gift card account.
     *
     * @param FixtureInterface|null $fixture [optional]
     * @return array
     * @throws \Exception
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $data = $this->replaceMappingData($fixture->getData());
        $data['website_id'] = $fixture->getDataFieldConfig('website_id')['source']->getWebsite()->getWebsiteId();

        $url = $_ENV['app_backend_url'] . $this->activeTabInfo;
        $generateCode = $_ENV['app_backend_url'] . $this->generate;
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->addOption(CURLOPT_HEADER, 1);
        $curl->write($generateCode, [], CurlInterface::GET);
        $curl->read();
        $curl->write($url, $data);
        $content = $curl->read();

        if (!strpos($content, 'data-ui-id="messages-message-success"')) {
            throw new \Exception("Product creation by curl handler was not successful! Response: $content");
        }

        preg_match('`<tr data-role=\"row\".*?<td data-column=\"code\".*?>(.*?)<`mis', $content, $res);
        $curl->close();
        return ['code' => trim($res[1])];
    }
}
