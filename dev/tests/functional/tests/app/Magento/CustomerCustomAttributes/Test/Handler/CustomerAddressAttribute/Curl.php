<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Handler\CustomerAddressAttribute;

use Magento\CustomerCustomAttributes\Test\Handler\CustomerCustomAttribute\Curl as CustomerCurl;

/**
 * Curl handler for creating custom CustomerAddressAttribute.
 */
class Curl extends CustomerCurl implements CustomerAddressAttributeInterface
{
    /**
     * Url for saving data.
     *
     * @var string
     */
    protected $saveUrl = 'admin/customer_address_attribute/save/back/edit/active_tab/general';
}
