<?php

declare(strict_types=1);

namespace Magelearn\Slider\Model;

use Magelearn\Slider\Api\Data\SlideInterface;
use Magelearn\Slider\Api\SlideRepositoryInterface;
use Magelearn\Slider\Api\SliderManagementInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class SliderManagement implements SliderManagementInterface
{
    /**
     * @var SlideRepositoryInterface
     */
    private $slideRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    public function __construct(
        SlideRepositoryInterface $slideRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->slideRepository       = $slideRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder      = $sortOrderBuilder;
    }

    /**
     * @inheritDoc
     */
    public function getSlides(int $sliderId, bool $onlyEnabled = false, bool $sorted = false): array
    {
        $this->searchCriteriaBuilder->addFilter(SlideInterface::SLIDER_ID, $sliderId);

        if ($onlyEnabled) {
            //$this->searchCriteriaBuilder->addFilter(SlideInterface::IS_ACTIVE, 1);
            $this->searchCriteriaBuilder->addFilter(SlideInterface::STATUS, 1);
        }

        if ($sorted) {
            $sortOrder = $this->sortOrderBuilder->setField(SlideInterface::POSITION)->setAscendingDirection()->create();
            $this->searchCriteriaBuilder->addSortOrder($sortOrder);
        }

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $slides         = $this->slideRepository->getList($searchCriteria);

        return $slides->getItems();
    }
}
