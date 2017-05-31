<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Logging\Test\Block\Adminhtml;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Class Details
 * Backend admin logging details block
 */
class Details extends Block
{
    /**
     * Element locator to select admin user data
     *
     * @var string
     */
    protected $adminUserData = '#log_details_fieldset > table > tbody tr:nth-child(%d) > td';

    /**
     * Element locator to select logging details block
     *
     * @var string
     */
    protected $loggingDetailsGrid = '#loggingDetailsGrid';

    /**
     * Field value xpath locator for a specified field name in 'Value After Change' column in logging details block
     *
     * @var string
     */
    protected $valueForField = '//*[@id=\'loggingDetailsGrid_table\']//dt[text()=\'%s\']/following-sibling::dd[1]';

    /**
     * Field name xpath locator in 'Value After Change' column in logging details block
     *
     * @var string
     */
    protected $fieldName = '//*[@id=\'loggingDetailsGrid_table\']//dt[text()=\'%s\']';

    /**
     * Field value xpath locator in 'Value After Change' column in logging details block
     *
     * @var string
     */
    protected $fieldValue = '//*[@id=\'loggingDetailsGrid_table\']//dd[text()=\'%s\']';

    /**
     * Get Admin User Data from grid.
     *
     * @return array
     */
    public function getData()
    {
        $fields = [
            1 => 'aggregated_information',
            2 => 'user_id',
            3 => 'user',
        ];
        foreach ($fields as $key => $value) {
            $data[$value] = $this->_rootElement->find(sprintf($this->adminUserData, $key))->getText();
        }
        $data['user_id'] = (isset($data['user_id'])) ? substr($data['user_id'], 1) : null;
        return $data;
    }

    /**
     * Check if Logging Details Grid visible.
     *
     * @return bool
     */
    public function isLoggingDetailsGridVisible()
    {
        return $this->_rootElement->find($this->loggingDetailsGrid)->isVisible();
    }

    /**
     * Get field value for specified field in Logging Details Grid Table.
     *
     * @param string $field
     * @return string | null
     */
    public function getChangeAfterFieldValue($field)
    {
        $result = null;
        try {
            $result = $this->_rootElement->find(
                sprintf($this->valueForField, $field),
                Locator::SELECTOR_XPATH
            )->getText();
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
        }
        return $result;
    }

    /**
     * Check if a field name is visible in Logging Details Grid Table.
     *
     * @param string $field
     * @return bool
     */
    public function isChangeAfterFieldNameVisible($field)
    {
        return $this->_rootElement->find(sprintf($this->fieldName, $field), Locator::SELECTOR_XPATH)->isVisible();
    }

    /**
     * Check if a field value is visible in Logging Details Grid Table.
     *
     * @param string $value
     * @return bool
     */
    public function isChangeAfterFieldValueVisible($value)
    {
        return $this->_rootElement->find(sprintf($this->fieldValue, $value), Locator::SELECTOR_XPATH)->isVisible();
    }
}
