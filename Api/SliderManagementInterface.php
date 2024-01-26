<?php

namespace Magelearn\Slider\Api;

use Magelearn\Slider\Api\Data\SlideInterface;

/**
 * @api
 */
interface SliderManagementInterface
{
    /**
     * @param int $sliderId
     * @param bool $onlyEnabled
     * @param bool $sorted
     *
     * @return SlideInterface[]
     */
    public function getSlides(int $sliderId, bool $onlyEnabled = false, bool $sorted = false): array;
}
