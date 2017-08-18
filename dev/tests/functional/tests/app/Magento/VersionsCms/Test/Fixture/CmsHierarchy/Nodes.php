<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\VersionsCms\Test\Fixture\CmsHierarchy;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Repository\RepositoryFactory;

/**
 * Prepare content for Cms Hierarchy Node
 */
class Nodes extends DataSource
{
    /**
     * Fixture factory
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Repository factory
     *
     * @var RepositoryFactory
     */
    protected $repositoryFactory;

    /**
     * @param RepositoryFactory $repositoryFactory
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data
     */
    public function __construct(
        RepositoryFactory $repositoryFactory,
        FixtureFactory $fixtureFactory,
        array $params,
        array $data = []
    ) {
        $this->fixtureFactory = $fixtureFactory;
        $this->repositoryFactory = $repositoryFactory;
        $this->params = $params;
        $this->data = $data;
    }

    /**
     * Return prepared data set
     *
     * Update CMS Hierarchy Node data (page_id, identifier, label).
     * Set appropriate data values from CMS page assigned to CMS Hierarchy Node.
     *
     * @param string $key [optional]
     * @return array|mixed
     */
    public function getData($key = null)
    {
        if (isset($this->data['dataset']) && isset($this->params['repository'])) {
            $this->data = $this->repositoryFactory->get($this->params['repository'])->get($this->data['dataset']);
        }

        foreach ($this->data as $nodeKey => $node) {
            if (isset($node['page_id']) && is_array($node['page_id'])) {
                foreach ($node['page_id'] as $page) {
                    if (isset($page['dataset'])) {
                        /** @var \Magento\Cms\Test\Fixture\CmsPage $cmsPage */
                        $cmsPage = $this->fixtureFactory->createByCode('cmsPage', ['dataset' => $page['dataset']]);
                        $cmsPage->persist();

                        $this->data[$nodeKey]['page_id'] = $cmsPage->getPageId();
                        $this->data[$nodeKey]['identifier'] = $cmsPage->getIdentifier();
                        $this->data[$nodeKey]['label'] = $cmsPage->getTitle();
                    }
                }
            }
        }

        return parent::getData($key);
    }
}
