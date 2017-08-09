<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section;

use Magento\Mtf\Client\Locator;
use Magento\Ui\Test\Block\Adminhtml\Section;

/**
 * Category rules grid.
 */
class SmartCategoryGrid extends Section
{
    /**
     * New rule for matching products button locator.
     *
     * @var string
     */
    private $buttonAddCondition = 'button#add_new_rule_button';

    /**
     * New custom option row locator.
     *
     * @var string
     */
    private $newRuleRow = './/*[@id="smart_category_table"]/tbody/tr[%d]';

    /**
     * Fills in the form array of input data.
     *
     * @param array $rows
     * @return $this
     */
    public function fillConditions(array $rows)
    {
        $buttonElement = $this->browser->find($this->buttonAddCondition);

        foreach ($rows as $key => $row) {
            if (!empty($row)) {
                if (!isset($row['logic'])) {
                    $row['logic'] = 'AND';
                }
                $rootElement = $this->_rootElement->find(
                    sprintf($this->newRuleRow, $key + 1),
                    Locator::SELECTOR_XPATH
                );
                if (!$rootElement->isVisible()) {
                    $buttonElement->click();
                }

                if ($key > 0) {
                    $logicField = ['logic' => $rows[$key-1]['logic']];
                    $previousRootElement = $this->_rootElement->find(
                        sprintf($this->newRuleRow, $key),
                        Locator::SELECTOR_XPATH
                    );
                    $data = $this->dataMapping($logicField, null, true);
                    $this->_fill($data, $previousRootElement);
                }

                $data = $this->dataMapping($row, null);
                $this->_fill($data, $rootElement);
            }
        }

        return $this;
    }

    /**
     * Fixture mapping.
     *
     * @param array|null $fields
     * @param string|null $parent
     * @param bool $logic
     * @return array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function dataMapping(array $fields = null, $parent = null, $logic = false)
    {
        $dataForInsert = $fields;

        if (!$logic && isset ($dataForInsert['logic'])) {
            unset($dataForInsert['logic']);
        }

        return parent::dataMapping($dataForInsert, $parent);
    }
}
