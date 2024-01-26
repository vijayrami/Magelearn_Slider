<?php

declare(strict_types=1);

namespace Magelearn\Slider\Model\Slide;

use DateTimeZone;
use Magelearn\Slider\Api\Data\SlideInterface;
use Magelearn\Slider\Api\SlideRepositoryInterface;
use Magelearn\Slider\Block\Slider;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Indexer\CacheContext;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class DateRangeChecker
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
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var CacheContext
     */
    private $cacheContext;

    /**
     * @var ManagerInterface
     */
    private $eventManager;

    /**
     * @var CacheInterface
     */
    private $cacheManager;

    /**
     * @param SlideRepositoryInterface $slideRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param TimezoneInterface $timezone
     * @param CacheContext $cacheContext
     * @param ManagerInterface $eventManager
     * @param CacheInterface $cacheManager
     */
    public function __construct(
        SlideRepositoryInterface $slideRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        TimezoneInterface $timezone,
        CacheContext $cacheContext,
        ManagerInterface $eventManager,
        CacheInterface $cacheManager
    ) {
        $this->slideRepository = $slideRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->timezone = $timezone;
        $this->cacheContext = $cacheContext;
        $this->eventManager = $eventManager;
        $this->cacheManager = $cacheManager;
    }

    /**
     * Change slides is_active value by date and invalidate cache
     *
     * @return void
     */
    public function execute()
    {
        $sliderIdsActivated = $this->activateSlides();
        $sliderIdsDeactivated = $this->deactivateSlides();

        $this->cleanCache(array_unique(array_merge($sliderIdsActivated, $sliderIdsDeactivated)));
    }

    /**
     * Find slides with is_active set to 0, that should be visible based on dates range, and activate those
     *
     * @return array IDs of sliders that were modified
     */
    private function activateSlides(): array
    {
        $sliderIds = [];

        $this->searchCriteriaBuilder->addFilter(SlideInterface::IS_ACTIVE, 0);

        $currentDate = $this->timezone->date();
        $currentDate->setTimezone(
            new DateTimeZone('UTC')
        );
        $today = $currentDate->format(DateTime::DATETIME_PHP_FORMAT);

        $this->searchCriteriaBuilder->addFilter(SlideInterface::DATE_FROM, $today, 'lteq');

        // filter group with OR inside
        $filter = $this->filterBuilder->setField(SlideInterface::DATE_TO)
            ->setConditionType('gteq')
            ->setValue($today)
            ->create();

        $filter2 = $this->filterBuilder->setField(SlideInterface::DATE_TO)
            ->setConditionType('null')
            ->setValue(true)
            ->create();

        $this->searchCriteriaBuilder->addFilters([$filter, $filter2]);

        $slides = $this->slideRepository->getList($this->searchCriteriaBuilder->create());

        if ($slides->getTotalCount() === 0) {
            return $sliderIds;
        }

        /** @var SlideInterface $slide */
        foreach ($slides->getItems() as $slide) {
            $slide->setIsActive(true);
            $this->slideRepository->save($slide);

            $sliderIds[] = (int)$slide->getSliderId();
        }

        return $sliderIds;
    }

    /**
     * Find slides with is_active set to 1, that should not be visible based on dates range, and deactivate those
     *
     * @return array IDs of sliders that were modified
     */
    private function deactivateSlides(): array
    {
        $sliderIds = [];

        $this->searchCriteriaBuilder->addFilter(SlideInterface::IS_ACTIVE, 1);

        $currentDate = $this->timezone->date();
        $currentDate->setTimezone(
            new DateTimeZone('UTC')
        );
        $today = $currentDate->format(DateTime::DATETIME_PHP_FORMAT);

        $this->searchCriteriaBuilder->addFilter(SlideInterface::DATE_TO, $today, 'lteq');

        $slides = $this->slideRepository->getList($this->searchCriteriaBuilder->create());

        if ($slides->getTotalCount() === 0) {
            return $sliderIds;
        }

        /** @var SlideInterface $slide */
        foreach ($slides->getItems() as $slide) {
            $slide->setIsActive(false);
            $this->slideRepository->save($slide);

            $sliderIds[] = (int)$slide->getSliderId();
        }

        return $sliderIds;
    }

    /**
     * @param int[] $sliderIds
     */
    private function cleanCache(array $sliderIds): void
    {
        if (count($sliderIds) === 0) {
            return;
        }

        $this->cacheContext->registerEntities(Slider::CACHE_TAG, $sliderIds);

        $this->eventManager->dispatch('clean_cache_by_tags', ['object' => $this->cacheContext]);
        $this->cacheManager->clean($this->cacheContext->getIdentities());
    }
}
