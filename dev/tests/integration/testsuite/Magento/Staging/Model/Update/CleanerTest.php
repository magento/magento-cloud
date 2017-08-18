<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Staging\Model\Update;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Staging\Api\Data\UpdateInterface;
use Magento\Staging\Api\UpdateRepositoryInterface;
use Magento\Staging\Model\VersionHistoryInterface;
use Magento\TestFramework\Helper\Bootstrap;

class CleanerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Cleaner
     */
    private $cleaner;

    /**
     * @var UpdateRepositoryInterface
     */
    private $updateRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var VersionHistoryInterface
     */
    private $versionHistory;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();

        $this->versionHistory = $this->objectManager->get(VersionHistory::class);
        $this->searchCriteriaBuilder = $this->objectManager->get(SearchCriteriaBuilder::class);
        $this->updateRepository = $this->objectManager->get(UpdateRepositoryInterface::class);
        $this->cleaner = $this->objectManager->create(Cleaner::class);
    }

    /**
     * Checks a case when cleaner executed to remove rollbacks in the past.
     *
     * @covers \Magento\Staging\Model\Update\Cleaner::execute
     * @magentoDataFixture Magento/Staging/_files/cleaner.php
     */
    public function testRemoveOutdatedRollbacks()
    {
        $this->versionHistory->setCurrentId(strtotime('+1 hour'));
        $this->cleaner->execute();

        $this->searchCriteriaBuilder->addFilter('start_time', null, 'notnull');
        $items = $this->updateRepository->getList($this->searchCriteriaBuilder->create())
            ->getItems();
        $nameList = array_map(function (UpdateInterface $update) {
            return $update->getName();
        }, $items);

        self::assertEquals(
            [
                'Update 1',
                'Update 2',
                'Rollback for "Update 2"',
            ],
            array_values($nameList)
        );
    }
}
