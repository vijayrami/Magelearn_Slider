<?php

declare(strict_types=1);

namespace Magelearn\Slider\Controller\Adminhtml\Slide;

use Exception;
use Magelearn\Slider\Api\SlideRepositoryInterface;
use Magelearn\Slider\Model\ResourceModel\Slide\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magelearn_Slider::slide';

    /**
     * @var SlideRepositoryInterface
     */
    private $slideRepository;

    /**
     * @var CollectionFactory
     */
    private $slideCollectionFactory;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @param Context $context
     * @param SlideRepositoryInterface $slideRepository
     * @param CollectionFactory $slideCollectionFactory
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        SlideRepositoryInterface $slideRepository,
        CollectionFactory $slideCollectionFactory,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->slideRepository = $slideRepository;
        $this->slideCollectionFactory = $slideCollectionFactory;
        $this->filter = $filter;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $slideCollectionSize = $this->removeElements();
            $this->messageManager->addSuccessMessage(
                __('A total of %1 slide(s) have been deleted.', $slideCollectionSize)
            );
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong: %1', $e->getMessage()));
        }

        return $this->_redirect($this->_redirect->getRefererUrl());
    }

    /**
     * @return int
     * @throws LocalizedException
     */
    private function removeElements(): int
    {
        $slideCollection     = $this->filter->getCollection($this->slideCollectionFactory->create());
        $slideCollectionSize = $slideCollection->getSize();

        foreach ($slideCollection as $slideAnemicModel) {
            $this->slideRepository->delete($slideAnemicModel);
        }

        return $slideCollectionSize;
    }
}
