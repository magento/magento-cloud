<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Cybersource\Test\Block\Sandbox;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Secure Acceptance Profiles > Manage Profiles > Active Profiles block.
 */
class Profile extends Block
{
    /**
     * Profile entry selector in profiles table.
     *
     * @var string
     */
    protected $profileSelector = ".//td[contains(text(), '%s')]";

    /**
     * Select profile in profiles table.
     *
     * @param string $profileName
     * @return void
     */
    public function selectProfile($profileName)
    {
        $this->_rootElement->find(
            sprintf($this->profileSelector, $profileName),
            Locator::SELECTOR_XPATH
        )->click();
    }
}
