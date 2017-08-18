<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdminGws\Test\Constraint;

use Magento\AdminGws\Test\Fixture\AdminGwsRole;
use Magento\AdminGws\Test\Fixture\AdminGwsRole\GwsStoreGroups;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\User\Test\Fixture\User;

/**
 * Assert that filter of SalesGrid has only related StoreGroup list.
 */
class AssertAdminGwsSalesGrid extends AbstractConstraint
{
    /**
     * Assert that filter of SalesGrid grid has only related StoreGroup list.
     *
     * @param OrderIndex $orderIndex
     * @param User $user
     * @return void
     */
    public function processAssert(
        OrderIndex $orderIndex,
        User $user
    ) {
        /** @var AdminGwsRole $role */
        $role = $user->getDataFieldConfig('role_id')['source']->getRole();
        $storeGroups = $role->getDataFieldConfig('gws_store_groups')['source']->getStoreGroups();
        $storeGroupNames = [];

        foreach ($storeGroups as $storeGroup) {
            /** @var GwsStoreGroups $storeGroup */
            $storeGroupNames[] = $storeGroup->getName();
        }

        $this->objectManager->create(\Magento\User\Test\TestStep\LoginUserOnBackendStep::class, ['user' => $user])
            ->run();
        $orderIndex->open();
        \PHPUnit_Framework_Assert::assertEquals(
            $storeGroupNames,
            $orderIndex->getSalesOrderGrid()->getPurchasePointStoreGroups()
        );
    }

    /**
     * Return string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Filter of SalesGrid grid has only related StoreGroup list.';
    }
}
