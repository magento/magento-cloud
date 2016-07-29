<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Config\Test\Handler\Curl;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Curl handler for persisting Magento configuration.
 */
class ApplyConfig extends Curl
{
    /**
     * Post request for each fixture section.
     *
     * @param FixtureInterface $fixture
     * @return mixed|void
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $sections = $fixture->getData()['sections'];
        $fields = ['groups' => []];
        foreach ($sections as $section => $data) {
            $fields['groups'] = $data['groups'];
            $url = $_ENV['app_backend_url'] .
                'admin/system_config/save/section/' . $section . '/';
            $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
            $curl->write($url, $fields);
            $curl->read();
            $curl->close();
        }
    }
}
