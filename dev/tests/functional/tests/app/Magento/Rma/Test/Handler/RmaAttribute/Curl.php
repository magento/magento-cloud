<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Handler\RmaAttribute;

use Magento\Catalog\Test\Handler\CatalogProductAttribute\Curl as ProductAttributeCurl;

/**
 * Create new Rma Attribute via curl
 */
class Curl extends ProductAttributeCurl implements RmaAttributeInterface
{
    /**
     * Relative action path with parameters.
     *
     * @var string
     */
    protected $urlActionPath = 'admin/rma_item_attribute/save/back/edit';

    /**
     * Message for Exception when was received not successful response.
     *
     * @var string
     */
    protected $responseExceptionMessage = 'Rma Attribute creating by curl handler was not successful!';

    /**
     * Mapping values for data.
     *
     * @var array
     */
    protected $mappingData = [
        'frontend_input' => [
            'Text Field' => 'text',
            'Text Area' => 'textarea',
            'Dropdown' => 'select',
            'Yes/No' => 'boolean',
            'File (attachment)'  => 'file',
            'Image File' => 'media_image',
        ],
        'is_required' => [
            'Yes' => 1,
            'No' => 0,
        ],
        'is_visible' => [
            'Yes' => 1,
            'No' => 0,
        ],
        'input_validation' => [
            'None' => null,
            'Alphanumeric' => 'alphanumeric',
            'Numeric Only' => 'numeric',
            'Alpha Only' => 'alpha',
            'URL' => 'url',
            'Email' => 'email',
        ],
        'input_filter' => [
            'None' => null,
            'Strip HTML Tags' => 'striptags',
            'Escape HTML Entities' => 'escapehtml',
        ],
        'default_value_yesno' => [
            'Yes' => 1,
            'No' => 0,
        ],
    ];
}
