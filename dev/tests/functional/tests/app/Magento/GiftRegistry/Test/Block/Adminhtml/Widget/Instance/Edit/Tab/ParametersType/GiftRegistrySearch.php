<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Adminhtml\Widget\Instance\Edit\Tab\ParametersType;

use Magento\Widget\Test\Block\Adminhtml\Widget\Instance\Edit\Tab\ParametersType\ParametersForm;

/**
 * Filling Widget Options that have gift registry search type.
 */
class GiftRegistrySearch extends ParametersForm
{
    /**
     * Gift Registry Search grid block.
     *
     * @var string
     */
    protected $gridBlock = './ancestor::body//*[contains(@id, "options_fieldset")]//div[contains(@class, "main-col")]';
}
