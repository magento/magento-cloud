<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Test\Block\Adminhtml\Category\Edit\Section\Permission;

use Magento\Mtf\Block\Form;

/**
 * Category permission block on backend.
 */
class Permission extends Form
{
    /**
     * Fill category permissions.
     *
     * @param array $fields
     * @return void
     */
    public function fillOption(array $fields)
    {
        $mapping = $this->dataMapping($fields);
        $isWebsiteVisible = $this->_rootElement->find(
            $mapping['website']['selector'],
            $mapping['website']['strategy']
        )->isVisible();
        if (!$isWebsiteVisible) {
            unset($mapping['website']);
        }
        $this->_fill($mapping);
    }
}
