<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\Fixture;

use Magento\Mtf\ObjectManager;

/**
 * Fixture with all necessary data for order creation on backend
 * and existing customer
 *
 */
class OrderWithCustomer extends Order
{
    /**
     * {@inheritdoc}
     */
    public function persist()
    {
        parent::persist();
        $this->customer = ObjectManager::getInstance()->create(
            'Magento\Customer\Test\Fixture\Customer',
            ['dataset' => 'backend_customer']
        );
        $this->customer->persist();
    }
}
