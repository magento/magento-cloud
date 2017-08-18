<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Block;

use Magento\Mtf\Block\Block;

/**
 * Banner block in Banner widget on frontend.
 */
class Banners extends Block
{
    /**
     * Banner text css selector.
     *
     * @var string
     */
    protected $bannerText = '.banner-item';

    /**
     * Return Banner content.
     *
     * @return array
     */
    public function getBannerText()
    {
        $banners = $this->_rootElement->getElements($this->bannerText);
        $bannersText = [];
        foreach ($banners as $banner) {
            $bannersText[] = $banner->getText();
        }

        return $bannersText;
    }
}
