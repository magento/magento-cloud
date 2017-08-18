<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Test\Block\Adminhtml\Category\Edit\Section;

use Magento\Mtf\Client\Element\SimpleElement;
use Magento\CatalogPermissions\Test\Block\Adminhtml\Category\Edit\Section\Permission\Permission;
use Magento\Mtf\Client\ElementInterface;
use Magento\Mtf\Client\Locator;
use Magento\Ui\Test\Block\Adminhtml\Section;

/**
 * Category permissions section block on category edit page.
 */
class CategoryPermissions extends Section
{
    /**
     * Selector for 'New Permission' button.
     *
     * @var string
     */
    protected $addNewPermission = 'button[data-ui-id="category-permissions-row-add-button"]';

    /**
     * Category permissions locator.
     *
     * @var string
     */
    protected $categoryPermissions = './/div[@class="box items"]';

    /**
     * Selector for permission content.
     *
     * @var string
     */
    protected $permissionContent = './/div[contains(@class, "option-box")][%d]//table';

    /**
     * Locator for permission row.
     *
     * @var string
     */
    protected $permissionRow = './/div[contains(@class, "option-box")][1]';

    /**
     * Get category permission block.
     *
     * @param ElementInterface $element
     * @return Permission
     */
    private function getCategoryPermissionBlock(ElementInterface $element)
    {
        return $this->blockFactory->create(
            \Magento\CatalogPermissions\Test\Block\Adminhtml\Category\Edit\Section\Permission\Permission::class,
            [
                'element' => $element->find($this->permissionRow, Locator::SELECTOR_XPATH)
            ]
        );
    }

    /**
     * Fill category permissions.
     *
     * @param array $fields
     * @param SimpleElement|null $element
     * @return $this
     */
    public function setFieldsData(array $fields, SimpleElement $element = null)
    {
        $context = ($element === null) ?
            $this->_rootElement->find($this->categoryPermissions, Locator::SELECTOR_XPATH) : $element;
        foreach ($fields['category_permissions']['value']['permissions'] as $key => $categoryPermission) {
            $count = $key + 1;
            $itemOption = $context->find(sprintf($this->permissionContent, $count), Locator::SELECTOR_XPATH);
            if (!$itemOption->isVisible()) {
                $this->_rootElement->find($this->addNewPermission)->click();
            }
            $this->getCategoryPermissionBlock($context)->fillOption($categoryPermission);
        }
        return $this;
    }
}
