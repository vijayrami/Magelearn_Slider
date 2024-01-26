<?php

namespace Magelearn\Slider\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface SliderSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get sliders list.
     *
     * @return \Magelearn\Slider\Api\Data\SliderInterface[]
     */
    public function getItems();

    /**
     * Set sliders list.
     *
     * @param \Magelearn\Slider\Api\Data\SliderInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
