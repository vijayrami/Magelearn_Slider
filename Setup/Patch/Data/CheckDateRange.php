<?php

declare(strict_types=1);

namespace Magelearn\Slider\Setup\Patch\Data;

use Magelearn\Slider\Model\Slide\DateRangeChecker;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CheckDateRange implements DataPatchInterface
{
    /**
     * @var DateRangeChecker
     */
    private $dateRangeChecker;

    /**
     * @param DateRangeChecker $dateRangeChecker
     */
    public function __construct(
        DateRangeChecker $dateRangeChecker
    ) {
        $this->dateRangeChecker = $dateRangeChecker;
    }

    /**
     * @inheritDoc
     */
    public function apply(): void
    {
        $this->dateRangeChecker->execute();
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
