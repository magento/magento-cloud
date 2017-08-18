<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Handler\Invitation;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Class Curl.
 * Create invitation.
 */
class Curl extends AbstractCurl implements InvitationInterface
{
    /**
     * Url to save invitation.
     *
     * @var string
     */
    protected $url = 'invitations/index/save';

    /**
     * Post request for creating invitation.
     *
     * @param FixtureInterface $fixture
     * @throws \Exception
     * @return array
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $url = $_ENV['app_backend_url'] . $this->url;
        $data = $this->replaceMappingData($this->prepareData($fixture));
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->write($url, $data);
        $response = $curl->read();

        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            throw new \Exception("Invitation creation by curl handler was not successful! Response: $response");
        }
        $curl->close();
    }

    /**
     * Prepare POST data for creating invitation request.
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    protected function prepareData(FixtureInterface $fixture)
    {
        $data = $fixture->getData();
        /** @var \Magento\Invitation\Test\Fixture\Invitation $fixture */
        $store = $fixture->getDataFieldConfig('store_id')['source']->getStore()->getStoreId();
        $group = $fixture->getDataFieldConfig('group_id')['source']->getCustomerGroup()->getCustomerGroupId();
        $data['store_id'] = $store;
        $data['group_id'] = $group;
        $data['email'] = implode("\n", $data['email']);

        return $data;
    }
}
