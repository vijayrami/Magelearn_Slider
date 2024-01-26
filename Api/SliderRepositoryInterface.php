<?php

namespace Magelearn\Slider\Api;

use Magelearn\Slider\Api\Data\SliderInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface SliderRepositoryInterface
{
    public function save(SliderInterface $slider): SliderInterface;

    public function getById($sliderId): SliderInterface;

    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    public function delete(SliderInterface $slider): void;

    public function deleteById($sliderId): void;
}
