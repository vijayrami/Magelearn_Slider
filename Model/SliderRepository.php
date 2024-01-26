<?php

namespace Magelearn\Slider\Model;

use Magelearn\Slider\Api\Data\SliderInterface;
use Magelearn\Slider\Api\Data\SliderInterfaceFactory;
use Magelearn\Slider\Api\Data\SliderSearchResultsInterfaceFactory;
use Magelearn\Slider\Api\SliderRepositoryInterface;
use Magelearn\Slider\Model\ResourceModel\Slider\CollectionFactory as SliderCollectionFactory;
use Magelearn\Slider\Model\SliderFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

class SliderRepository implements SliderRepositoryInterface
{
    /** @var \Magelearn\Slider\Model\Slider */
    private $sliderServiceModel;

    /** @var SliderCollectionFactory */
    private $sliderCollectionFactory;

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;

    /** @var SliderSearchResultsInterfaceFactory */
    private $searchResultsFactory;

    /** @var SliderInterfaceFactory */
    private $sliderAnemicModelFactory;

    /**
     * SliderRepository constructor.
     * @param SliderFactory $sliderFactory
     * @param SliderInterfaceFactory $sliderAnemicModelFactory
     * @param SliderCollectionFactory $sliderCollectionFactory
     * @param SliderSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        SliderFactory $sliderFactory,
        SliderInterfaceFactory $sliderAnemicModelFactory,
        SliderCollectionFactory $sliderCollectionFactory,
        SliderSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->sliderServiceModel       = $sliderFactory->create();
        $this->sliderAnemicModelFactory = $sliderAnemicModelFactory;
        $this->sliderCollectionFactory  = $sliderCollectionFactory;
        $this->collectionProcessor      = $collectionProcessor;
        $this->searchResultsFactory     = $searchResultsFactory;
    }

    /**
     * @param SliderInterface $slider
     * @return SliderInterface
     * @throws \Exception
     */
    public function save(SliderInterface $slider): SliderInterface
    {
        return $this->sliderServiceModel->save($slider);
    }

    /**
     * @param $sliderId
     * @return SliderInterface
     */
    public function getById($sliderId): SliderInterface
    {
        return $this->sliderServiceModel->load($sliderId);
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        /** @var \Magelearn\Slider\Model\ResourceModel\Slider\Collection $collection */
        $collection = $this->sliderCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * @param SliderInterface $slider
     * @throws \Exception
     */
    public function delete(SliderInterface $slider): void
    {
        $this->sliderServiceModel->delete($slider);
    }

    /**
     * @param $sliderId
     * @throws \Exception
     */
    public function deleteById($sliderId): void
    {
        /** @var SliderInterface $anemicModel */
        $anemicModel = $this->sliderAnemicModelFactory->create();
        $anemicModel->setSliderId($sliderId);

        $this->sliderServiceModel->delete($anemicModel);
    }
}
