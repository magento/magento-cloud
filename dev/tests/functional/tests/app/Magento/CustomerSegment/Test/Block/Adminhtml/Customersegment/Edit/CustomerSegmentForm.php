<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Block\Adminhtml\Customersegment\Edit;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Backend\Test\Block\Widget\FormTabs;
use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;
use Magento\CustomerSegment\Test\Block\Adminhtml\Customersegment\Edit\Tab\MatchedCustomers;

/**
 * Class CustomerSegmentForm
 * Backend CustomerSegment form
 */
class CustomerSegmentForm extends FormTabs
{
    /**
     * Add button
     *
     * @var string
     */
    protected $addButton = '.rule-param-new-child a';

    /**
     * Locator for Customer Segment Conditions
     *
     * @var string
     */
    protected $conditionFormat = '//*[@id="conditions__1__new_child"]//option[contains(@value,"%s")]';

    /**
     * Get number of customer on navigation tabs
     *
     * @return int
     */
    public function getNumberOfCustomersOnTabs()
    {
        $customerLink = $this->_rootElement
            ->find($this->getTabs()['matched_customers']['selector'], Locator::SELECTOR_CSS)
            ->getText();
        preg_match('`\((\d*?)\)`', $customerLink, $customersCount);
        return (int) $customersCount[1];
    }

    /**
     * Get Matched Customers tab
     *
     * @return MatchedCustomers
     */
    public function getMatchedCustomers()
    {
        return $this->getTab('matched_customers');
    }

    /**
     * Fill form with tabs
     *
     * @param FixtureInterface $fixture
     * @param SimpleElement|null $element
     * @param array|null $replace
     * @return $this
     */
    public function fill(FixtureInterface $fixture, SimpleElement $element = null, array $replace = null)
    {
        $tabs = $this->getFixtureFieldsByContainers($fixture);
        if ($replace) {
            $tabs = $this->prepareData($tabs, $replace);
        }
        return $this->fillTabs($tabs, $element);
    }

    /**
     * Replace placeholders in each values of data
     *
     * @param array $tabs
     * @param array $replace
     * @return array
     */
    protected function prepareData(array $tabs, array $replace)
    {
        foreach ($tabs as $tabName => $fields) {
            foreach ($fields as $key => $pairs) {
                if (isset($replace[$tabName])) {
                    $tabs[$tabName][$key]['value'] = str_replace(
                        array_keys($replace[$tabName]),
                        array_values($replace[$tabName]),
                        $pairs['value']
                    );
                }
            }
        }
        return $tabs;
    }

    /**
     * Check if customer attribute is available in conditions of customer segment
     *
     * @param CustomerCustomAttribute $customerAttribute
     * @return bool
     */
    public function isAttributeInConditions(CustomerCustomAttribute $customerAttribute)
    {
        $this->_rootElement->find($this->addButton, Locator::SELECTOR_CSS)->click();
        return $this->_rootElement->find(
            sprintf($this->conditionFormat, $customerAttribute->getAttributeCode()),
            Locator::SELECTOR_XPATH
        )->isVisible();
    }
}
