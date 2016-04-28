<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Handler\VersionsCmsPage;

/**
 * Curl handler for creating Cms page.
 */
class Curl extends \Magento\Cms\Test\Handler\CmsPage\Curl implements VersionsCmsPageInterface
{
    /**
     * Mapping values for data.
     *
     * @var array
     */
    protected $additionalMappingData = [
        'is_active' => [
            'Published' => 1,
            'Disabled' => 0,
        ],
        'under_version_control' => [
            'Yes' => 1,
            'No' => 0,
        ],
    ];

    /**
     * Url for save cms page
     *
     * @var string
     */
    protected $url = 'admin/cms_page/save/back/edit/active_tab/main_section/';
}
