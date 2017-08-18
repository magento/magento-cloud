<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Handler\CustomerAddressAttribute;

use Magento\CustomerCustomAttributes\Test\Handler\CustomerCustomAttribute\Curl as ParentCurl;

/**
 * Curl handler for creating custom Customer Address Attribute.
 */
class Curl extends ParentCurl implements CustomerAddressAttributeInterface
{
    /**
     * Url for saving data.
     *
     * @var string
     */
    protected $saveUrl = 'admin/customer_address_attribute/save/back/edit/active_tab/general';
}
