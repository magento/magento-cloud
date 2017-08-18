<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerImportExport\Model\Import;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class CustomAddressTest
 *
 */
class CustomAddressTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Address entity adapter instance
     *
     * @var Address
     */
    private $entityAdapter;

    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    private $attributeManagement;

    /**
     * Init new instance of address entity adapter
     */
    protected function setUp()
    {
        $this->attributeManagement = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Eav\Api\AttributeRepositoryInterface::class
        );
        $this->entityAdapter = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerImportExport\Model\Import\Address::class
        );
    }

    /**
     * Test import data validation for address with custom attribute
     *
     * @magentoDataFixture Magento/CustomerCustomAttributes/_files/address_multi_attribute.php
     * @magentoDataFixture Magento/Customer/_files/import_export/customers_for_address_import.php
     */
    public function testValidateImportData()
    {
        // set behaviour
        $this->entityAdapter->setParameters(
            ['behavior' => \Magento\ImportExport\Model\Import::BEHAVIOR_ADD_UPDATE]
        );

        // set fixture CSV file
        $sourceFile = __DIR__ . '/_files/address_with_multiselect_attribute.csv';
        $tempFile = __DIR__ . '/_files/temp_address_csv.csv';

        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var \Magento\Framework\Filesystem $filesystem */
        $filesystem = $objectManager->create(\Magento\Framework\Filesystem::class);
        $sourceFileContents = $filesystem->getDirectoryRead(DirectoryList::ROOT)->readFile($sourceFile);
        $attribute = $this->attributeManagement->get('customer_address', 'multi_select_attribute_code');
        $options = $this->entityAdapter->getAttributeOptions($attribute, ['country_id']);
        $tempFileContents = str_replace('{multiselect}', implode(',', array_keys($options)), $sourceFileContents);
        $directoryWrite = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        try {
            $directoryWrite->writeFile($tempFile, $tempFileContents);
            $validateResult = $this->entityAdapter->setSource(
                \Magento\ImportExport\Model\Import\Adapter::findAdapterFor($tempFile, $directoryWrite)
            )
                ->validateData()
                ->hasToBeTerminated();

            $this->assertFalse($validateResult);
        } finally {
            $directoryWrite->delete($tempFile);
        }
    }
}
