<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Block\Adminhtml\Customersegment\Edit\Tab;

use Magento\Backend\Test\Block\Widget\Tab;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Factory\Factory;

/**
 * Class Conditions
 * Segment Conditions actions block
 */
class Conditions extends Tab
{
    /**
     * Backend abstract block
     *
     * @var string
     */
    protected $templateBlock = './ancestor::body';

    /**
     * Condition selector
     *
     * @var string
     */
    protected $conditionSelector = 'conditions__1__new_child';

    /**
     * Condition value selector
     *
     * @var string
     */
    protected $conditionValueSelector = 'conditions__1--1__value';

    /**
     * Get backend abstract block
     *
     * @return \Magento\Backend\Test\Block\Template
     */
    protected function getTemplateBlock()
    {
        return Factory::getBlockFactory()->getMagentoBackendTemplate(
            $this->_rootElement->find($this->templateBlock, Locator::SELECTOR_XPATH)
        );
    }

    /**
     * Add Customer Group Condition
     *
     * @param string $type
     * @param string $value
     * @return void
     */
    public function addCustomerGroupCondition($type, $value)
    {
        // click the add new plus image
        $this->_rootElement->find('img.rule-param-add.v-middle')->click();
        $this->getTemplateBlock()->waitLoader();
        // select the condition
        $this->_rootElement->find($this->conditionSelector, Locator::SELECTOR_ID, 'select')->setValue($type);
        $this->getTemplateBlock()->waitLoader();
        // click the ellipsis
        $this->_rootElement->find('//a[contains(text(),"...")]', Locator::SELECTOR_XPATH)->click();
        $this->getTemplateBlock()->waitLoader();
        // select the condition value
        $this->_rootElement->find($this->conditionValueSelector, Locator::SELECTOR_ID, 'select')->setValue($value);
    }
}
