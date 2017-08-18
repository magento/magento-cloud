<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Handler\Curl;

use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Curl handler for updating a banner
 *
 */
class UpdateBanner extends CreateBanner
{
    /**
     * Post request for updating banner
     *
     * @param FixtureInterface $fixture [optional]
     * @throws \Exception
     * @return null|string banner_id
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $response = $this->postRequest($fixture);
        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            throw new \Exception('Banner update by curl handler was not successful! Response: ' . $response);
        }
    }
}
