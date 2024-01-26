<?php

namespace Magelearn\Slider\Api;

use Magelearn\Slider\Api\Data\SlideInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface SlideRepositoryInterface
{
    /**
     * Saves Slide
     *
     * @param SlideInterface $entity
     * @return SlideInterface
     * @throws CouldNotSaveException
     */
    public function save(SlideInterface $entity): SlideInterface;

    /**
     * Retrieves Slide by slide_id
     *
     * @param int $id
     * @return SlideInterface
     * @throws NoSuchEntityException
     */
    public function getById($id): SlideInterface;

    /**
     * Retrieves slide matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Deletes slide
     *
     * @param SlideInterface $entity
     * @return void
     * @throws CouldNotDeleteException
     */
    public function delete(SlideInterface $entity): void;

    /**
     * Deletes slide by slide_id
     *
     * @param int $id
     * @return void
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById($id): void;
}
