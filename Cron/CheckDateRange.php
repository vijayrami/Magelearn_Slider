<?php

declare(strict_types=1);

namespace Magelearn\Slider\Cron;

use Magelearn\Slider\Model\Slide\DateRangeChecker;

class CheckDateRange
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
     * Change slides is_active value by date and invalidate cache
     *
     * @return void
     */
    public function execute()
    {
        $this->dateRangeChecker->execute();
    }
}
