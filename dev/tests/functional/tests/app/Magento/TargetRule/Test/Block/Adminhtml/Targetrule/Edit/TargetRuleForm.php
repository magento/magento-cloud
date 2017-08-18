<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\Block\Adminhtml\Targetrule\Edit;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Backend\Test\Block\Widget\FormTabs;

/**
 * Class TargetRuleForm
 * Target rule form on backend target rule page
 */
class TargetRuleForm extends FormTabs
{
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
        foreach ($replace as $tabName => $fields) {
            foreach ($fields as $key => $pairs) {
                if (isset($tabs[$tabName][$key])) {
                    $tabs[$tabName][$key]['value'] = str_replace(
                        array_keys($pairs),
                        array_values($pairs),
                        $tabs[$tabName][$key]['value']
                    );
                }
            }
        }
        return $tabs;
    }
}
