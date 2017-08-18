<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Fixture\Banner;

/**
 * Prepare content for banner.
 */
class Content extends \Magento\Cms\Test\Fixture\CmsPage\Content
{
    /**
     * Prepare source data for banner content.
     *
     * @return void
     */
    protected function prepareSourceData()
    {
        foreach ($this->data as $key => $storeValue) {
            if (isset($storeValue['widget']['dataset']) && isset($this->params['repository'])) {
                $this->data[$key]['widget']['dataset'] =
                    $this->repositoryFactory->get($this->params['repository'])->get($storeValue['widget']['dataset']);
                $this->data[$key] = array_merge(
                    $this->data[$key],
                    $this->prepareWidgetData($this->data[$key]['widget'])
                );
            } else {
                $this->data[$key] = $storeValue;
            }
        }
    }
}
