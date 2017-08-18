<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Block\Adminhtml\Banner;

use Magento\Mtf\Client\Locator;

/**
 * Class BannerForm
 * Backend banner form
 */
class BannerForm extends \Magento\Banner\Test\Block\Adminhtml\Banner\BannerForm
{
    /**
     * Locator for customer segment
     *
     * @var string
     */
    protected $useSegment = '[name="use_customer_segment"]';

    /**
     * Locator for apply banner to the Selected Customer Segments
     *
     * @var string
     */
    protected $customerSegmentOptions = '[name="customer_segment_ids[]"] option';

    /**
     * Check whether customer segment is available on Banner form
     *
     * @param string $customerSegment
     * @return bool
     */
    public function isCustomerSegmentVisible($customerSegment)
    {
        $this->_rootElement->find($this->useSegment, Locator::SELECTOR_CSS, 'select')->setValue('Specified');
        $segments = $this->_rootElement->getElements($this->customerSegmentOptions, Locator::SELECTOR_CSS);
        foreach ($segments as $segment) {
            if ($customerSegment == $segment->getText()) {
                return true;
            }
        }
        return false;
    }
}
