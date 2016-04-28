<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Block\Adminhtml\Promo\Quote\Edit\Tab;

use Magento\Backend\Test\Block\Widget\Tab;

/**
 * Class Banners
 * 'Related Banners' tab on Catalog Price Rule form
 */
class Banners extends Tab
{
    /**
     * Banners grid locator
     *
     * @var string
     */
    protected $bannersGrid = '#related_salesrule_banners_grid';

    /**
     * Get banners grid on Cart Price Rules form
     *
     * @return \Magento\Banner\Test\Block\Adminhtml\Promo\Quote\Edit\Tab\BannersGrid
     */
    public function getBannersGrid()
    {
        return $this->blockFactory->create(
            'Magento\Banner\Test\Block\Adminhtml\Promo\Quote\Edit\Tab\BannersGrid',
            ['element' => $this->_rootElement->find($this->bannersGrid)]
        );
    }
}
