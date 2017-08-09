<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section;

use Magento\Mtf\Client\Locator;
use Magento\Ui\Test\Block\Adminhtml\Section;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Element\SmartCategorySwitcherElement;

/**
 * Category rules block.
 */
class SmartCategoryBlock extends Section
{
    /**
     * Smart category switcher element locator.
     *
     * @var string
     */
    private $smartCategorySwitcherElement = 'input#catalog_category_smart_category_onoff';

    /**
     * Category rules block locator.
     *
     * @var string
     */
    private $smartCategoryGrid = '#smart_category_table_wrapper';

    /**
     * Turns on/off matching products by rule.
     *
     * @param string $value
     * @return $this
     */
    public function setMatchProductsByRuleValue($value)
    {
        $this->_rootElement->find(
            $this->smartCategorySwitcherElement,
            Locator::SELECTOR_CSS,
            SmartCategorySwitcherElement::class
        )->setValue($value);

        return $this;
    }

    /**
     * Returns category rules grid.
     *
     * @return SmartCategoryGrid
     */
    public function getSmartCategoryGrid()
    {
        return $this->blockFactory->create(
            SmartCategoryGrid::class,
            ['element' => $this->_rootElement->find($this->smartCategoryGrid)]
        );
    }
}
