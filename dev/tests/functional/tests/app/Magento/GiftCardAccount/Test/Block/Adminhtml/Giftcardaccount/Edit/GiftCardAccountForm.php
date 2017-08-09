<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Block\Adminhtml\Giftcardaccount\Edit;

use Magento\Backend\Test\Block\Widget\FormTabs;

/**
 * Gift card account edit page block.
 */
class GiftCardAccountForm extends FormTabs
{
    /**
     * Return gift card account Id.
     *
     * @return string
     */
    public function getGiftCardAccountId()
    {
        $id = '';
        if (preg_match('/\/id\/(?<id>\d+)(?:\/)?/', $this->browser->getUrl(), $matches)) {
            $id = $matches['id'];
        }

        return $id;
    }
}
