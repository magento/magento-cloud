<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Handler\BannerWidget;

/**
 * Curl handler for creating widgetInstance/frontendApp.
 */
class Curl extends \Magento\Widget\Test\Handler\Widget\Curl
{
    /**
     * Mapping values for data.
     *
     * @var array
     */
    protected $additionalMappingData = [
        'code' => [
            'Banner Rotator' => 'magento_banner',
        ],
        'template' => [
            'Banner Block Template' => 'widget/block.phtml',
        ],
        'display_mode' => [
            'Specified Banners' => 'fixed',
            'Shopping Cart Promotions Related' => 'salesrule',
            'Catalog Promotions Related' => 'catalogrule'
        ],
    ];

    /**
     * Prepare widget Frontend options.
     *
     * @param array $data
     * @return array
     */
    protected function prepareParameters(array $data)
    {
        $data = parent::prepareParameters($data);
        $data['parameters']['unique_id'] = md5(microtime(1));

        return $data;
    }

    /**
     * Prepare entity parameters data.
     *
     * @param array $data
     * @return array
     */
    protected function prepareEntity(array $data)
    {
        if (isset($data['parameters']['entities'])) {
            $data['parameters']['banner_ids'] = $data['parameters']['entities'][0]->getBannerId();
            unset($data['parameters']['entities']);
        }

        return $data;
    }
}
