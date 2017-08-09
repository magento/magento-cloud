<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Element;

use Magento\Mtf\Client\Element\SwitcherElement;

/**
 * Toggle smart category switcher element in the backend.
 * Switches value between YES and NO.
 */
class SmartCategorySwitcherElement extends SwitcherElement
{
    /**
     * XPath locator of the parent container.
     *
     * @var string
     */
    protected $parentContainer = 'parent::div[@class="admin__actions-switch"]';
}
