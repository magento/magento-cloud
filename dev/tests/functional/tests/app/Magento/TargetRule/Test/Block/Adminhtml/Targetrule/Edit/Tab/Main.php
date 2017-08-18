<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\Block\Adminhtml\Targetrule\Edit\Tab;

use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Main target rules properties edit form.
 */
class Main extends \Magento\Backend\Test\Block\Widget\Tab
{
    /**
     * Get data of tab.
     *
     * @param array|null $fields
     * @param SimpleElement|null $element
     * @return array
     */
    public function getFieldsData($fields = null, SimpleElement $element = null)
    {
        $data = $this->dataMapping($fields);
        $customerSegmentValue = $this->_getData([$data['use_customer_segment']])[0];
        if ($customerSegmentValue == 'All') {
            unset($data['customer_segment_ids']);
        }
        return $this->_getData($data, $element);
    }
}
