<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Handler\CustomerBalance;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Curl handler for creating customer balance through customer form backend.
 */
class Curl extends AbstractCurl implements CustomerBalanceInterface
{
    /**
     * Post request for creating customer balance backend.
     *
     * @param FixtureInterface|null $fixture
     * @return void
     * @throws \Exception
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $data = $this->prepareData($fixture);
        $url = $_ENV['app_backend_url'] . 'customer/index/save/id/' . $data['customer']['entity_id'] . '/';
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->write($url, $data);
        $response = $curl->read();
        $curl->close();

        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            throw new \Exception(
                "Adding customer balance by curl handler was not successful! Response: $response"
            );
        }
    }

    /**
     * Prepare data from text to values.
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    protected function prepareData($fixture)
    {
        /** @var \Magento\CustomerBalance\Test\Fixture\CustomerBalance $fixture */
        $customer = $fixture->getDataFieldConfig('customer_id')['source']->getCustomer();
        /** @var \Magento\Store\Test\Fixture\Website $website */
        $website = $fixture->getDataFieldConfig('website_id')['source']->getWebsite();
        /** @var \Magento\Customer\Test\Fixture\Customer $customer */
        $data['customer']['entity_id'] = $customer->getId();
        $data['customerbalance']['amount_delta'] = $fixture->getBalanceDelta();
        $data['customerbalance']['website_id'] = $website->getWebsiteId();
        $data['customerbalance']['comment'] = $fixture->getAdditionalInfo();

        return $data;
    }
}
