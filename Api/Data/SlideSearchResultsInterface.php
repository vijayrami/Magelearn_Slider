<?php

namespace Magelearn\Slider\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface SlideSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get slides list.
     *
     * @return \Magelearn\Slider\Api\Data\SlideInterface[]
     */
    public function getItems();

    /**
     * Set slides list.
     *
     * @param \Magelearn\Slider\Api\Data\SlideInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
