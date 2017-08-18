<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Block\Adminhtml\Widget\Instance\Edit\Tab\ParametersType;

use Magento\Banner\Test\Block\Adminhtml\Banner\Grid;
use Magento\Widget\Test\Block\Adminhtml\Widget\Instance\Edit\Tab\ParametersType\ParametersForm;
use Magento\Mtf\Client\Element;

/**
 * Filling Widget Options that have banner rotator type.
 */
class BannerRotator extends ParametersForm
{
    /**
     * Banner Rotator grid block.
     *
     * @var string
     */
    protected $gridBlock = '#bannerGrid';

    /**
     * Path to grid.
     *
     * @var string
     */
    protected $pathToGrid = \Magento\Banner\Test\Block\Adminhtml\Banner\Grid::class;

    /**
     * Select node on widget options tab.
     *
     * @param array $entities
     * @return void
     */
    protected function selectEntity(array $entities)
    {
        foreach ($entities['value'] as $entity) {
            /** @var Grid $bannerRotatorGrid */
            $bannerRotatorGrid = $this->blockFactory->create(
                $this->pathToGrid,
                ['element' => $this->_rootElement->find($this->gridBlock)]
            );
            $bannerRotatorGrid->searchAndSelect(['banner' => $entity->getName()]);
        }
    }
}
