<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Handler\Reward;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Curl creation of reward points.
 */
class Curl extends AbstractCurl implements RewardInterface
{
    /**
     * Post request for creating reward points.
     *
     * @param FixtureInterface $fixture
     * @return array
     * @throws \Exception
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $data = $this->prepareData($fixture);

        $url = $_ENV['app_backend_url'] . 'customer/index/save/id/' . $data['customer']['entity_id'] . '/';
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->addOption(CURLOPT_HEADER, 0);
        $curl->write($url, $data);
        $response = $curl->read();
        $curl->close();

        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            throw new \Exception(
                "Adding reward points by curl handler was not successful! Response: $response"
            );
        }
    }

    /**
     * Prepare data for applying reward points to customer.
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    protected function prepareData($fixture)
    {
        /** @var \Magento\Reward\Test\Fixture\Reward $fixture */
        $customer = $fixture->getDataFieldConfig('customer_id')['source']->getCustomer();
        /** @var \Magento\Customer\Test\Fixture\Customer $customer */
        $data['customer']['entity_id'] = $customer->getId();
        $data['reward']['points_delta'] = $fixture->getPointsDelta();
        if ($fixture->hasData('store_id')) {
            $data['reward']['store_id'] = $fixture->getData('store_id');
        }

        return $data;
    }
}
