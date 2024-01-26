<?php

declare(strict_types=1);

namespace Magelearn\Slider\Setup\Patch\Data;

use Magelearn\Slider\Api\Data\SlideInterface;
use Magelearn\Slider\Ui\Component\Slide\DataProvider;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class RemoveMediaPathFromDb implements DataPatchInterface
{
    private const MEDIA_PATH = 'slider/images/upload/';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    public function apply(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $table = $this->moduleDataSetup->getTable('magelearn_slider_slider_item');

        $select = $connection->select()
            ->from($table, array_merge([SlideInterface::SLIDE_ID], DataProvider::IMAGE_PARAMS));

        $rows = $connection->fetchAll($select);

        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $data = [];

                foreach (DataProvider::IMAGE_PARAMS as $imageParam) {
                    $data[$imageParam] = $row[$imageParam] ? str_replace(self::MEDIA_PATH, '', $row[$imageParam]) : null;
                }

                $connection->update($table, $data, ['slide_id = ?' => $row[SlideInterface::SLIDE_ID]]);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }
}
