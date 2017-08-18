<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Logging\Test\Block\Adminhtml;

use Magento\Mtf\Block\Block;

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
     * Get Admin User Data from grid
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
     * Check if Logging Details Grid visible
     *
     * @return bool
     */
    public function isLoggingDetailsGridVisible()
    {
        return $this->_rootElement->find($this->loggingDetailsGrid)->isVisible();
    }
}
