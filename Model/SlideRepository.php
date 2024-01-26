<?php

namespace Magelearn\Slider\Model;

use Exception;
use Magelearn\Slider\Api\Data\SlideInterface;
use Magelearn\Slider\Api\Data\SlideInterfaceFactory;
use Magelearn\Slider\Api\Data\SlideSearchResultsInterfaceFactory;
use Magelearn\Slider\Api\SlideRepositoryInterface;
use Magelearn\Slider\Model\ResourceModel\Slide as SlideResource;
use Magelearn\Slider\Model\ResourceModel\Slide\CollectionFactory as SlideCollectionFactory;
use Magelearn\Slider\Model\Slide\FileInfo;
use Magelearn\Slider\Ui\Component\Slide\DataProvider;
use Magelearn\Slider\Model\ImageUploader;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class SlideRepository implements SlideRepositoryInterface
{
    /** @var SlideResource */
    private $slideResource;

    /** @var SlideInterfaceFactory */
    private $slideFactory;

    /** @var SlideCollectionFactory */
    private $slideCollectionFactory;

    /** @var SlideSearchResultsInterfaceFactory */
    private $searchResultsFactory;

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;

    /** @var ImageUploader */
    private $imageUploader;

    /** @var FileInfo */
    private $fileInfo;

    /** @var SlideInterface[] */
    private $entityById = [];
    
    protected $logger;

    /**
     * @param SlideResource $slideResource
     * @param SlideInterfaceFactory $slideFactory
     * @param SlideCollectionFactory $slideCollectionFactory
     * @param SlideSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ImageUploader $imageUploader
     * @param FileInfo $fileInfo
     */
    public function __construct(
        SlideResource $slideResource,
        SlideInterfaceFactory $slideFactory,
        SlideCollectionFactory $slideCollectionFactory,
        SlideSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        ImageUploader $imageUploader,
        FileInfo $fileInfo,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->slideResource = $slideResource;
        $this->slideFactory = $slideFactory;
        $this->slideCollectionFactory  = $slideCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->imageUploader = $imageUploader;
        $this->fileInfo = $fileInfo;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function save(SlideInterface $entity): SlideInterface
    {
        try {
            $imagesData = $entity->getData();
            foreach (DataProvider::IMAGE_PARAMS as $imageParam) {
                $entity->setData($imageParam, null);
            }

            $this->slideResource->save($entity);

            $this->processImages($entity, $imagesData);
        } catch (Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function getById($id): SlideInterface
    {
        if (isset($this->entityById[$id])) {
            return $this->entityById[$id];
        }

        $entity = $this->slideFactory->create();
        $this->slideResource->load($entity, $id);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(__('The slide with the "%1" ID doesn\'t exist.', $id));
        }

        $this->entityById[$id] = $entity;

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        /** @var \Magelearn\Slider\Model\ResourceModel\Slide\Collection $collection */
        $collection = $this->slideCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(SlideInterface $entity): void
    {
        try {
            $this->slideResource->delete($entity);
        } catch (Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }

        unset($this->entityById[$entity->getSlideId()]);
    }

    /**
     * @inheritDoc
     */
    public function deleteById($id): void
    {
        $entity = $this->getById($id);

        $this->delete($entity);
    }

    /**
     * @param SlideInterface|\Magelearn\Slider\Model\Slide $entity
     * @param array $data
     * @throws LocalizedException
     */
    private function processImages(SlideInterface $entity, array $data)
    {
        $imageUploaderBasePath = $this->imageUploader->getBasePath();
        foreach (DataProvider::IMAGE_PARAMS as $key) {
            if (isset($data[$key]) && (is_array($data[$key]) || is_string($data[$key]))) {
                $val = $data[$key];
                if (is_array($data[$key])) {
                    if (!empty($data[$key]['delete'])) {
                        $val = null;
                    } elseif (isset($data[$key][0]['name'], $data[$key][0]['tmp_name'])) {
                        $imagePrefix = $entity->getId() . '/' . $key;
                        $this->imageUploader->setBasePath($imageUploaderBasePath . '/' . $imagePrefix);

                        $image = $data[$key][0]['name'];
                        $image = $this->imageUploader->moveFileFromTmp($image);
                        $val = $imagePrefix . '/' . $image;

                        $this->imageUploader->setBasePath($imageUploaderBasePath);
                    } elseif (isset($data[$key][0]['url'])) {
                        $val = $this->fileInfo->getImagePathFromUrl($data[$key][0]['url']);
                    }
                }

                $entity->setData($key, $val);
            } else {
                $entity->setData($key, null);
            }
        }

        $this->slideResource->save($entity);
    }
}
