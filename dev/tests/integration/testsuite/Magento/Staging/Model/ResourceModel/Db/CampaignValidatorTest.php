<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Staging\Model\ResourceModel\Db;

use Magento\TestFramework\Helper\Bootstrap;

class CampaignValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CampaignValidator
     */
    private $model;

    protected function setUp()
    {
        $this->model = Bootstrap::getObjectManager()
            ->create(CampaignValidator::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Staging/_files/staging_entity.php
     * @magentoDataFixture Magento/Staging/_files/staging_update.php
     * @dataProvider getIntersectingVersionsDataProvider
     *
     * @param array $expectedValue
     * @param int $createdIn
     * @param int|null $updatedIn
     */
    public function testGetIntersectingVersions($expectedValue, $createdIn, $updatedIn)
    {
        $reflectionClass = new \ReflectionClass($this->model);
        $method = $reflectionClass->getMethod('getIntersectingVersions');
        $method->setAccessible(true);
        $this->assertEquals(
            $expectedValue,
            $method->invoke(
                $this->model,
                'Magento\CatalogRule\Api\Data\RuleInterface',
                1,
                $createdIn,
                $updatedIn
            )
        );
    }

    /**
     * Data provider for testGetIntersectingVersions
     *
     * @return array
     */
    public function getIntersectingVersionsDataProvider()
    {
        return [
            'permanent_starts_inside_temporary' => [[200], 250, null],
            'temporary_campaigns_are_intersected' => [[200], 150, 250],
            'permanent_starts_between_temporary_campaigns' => [[], 450, null],
            'temporary_campaigns_composed_inside_other_temporary' => [[200], 250, 280],
        ];
    }
}
