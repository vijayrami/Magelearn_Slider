<?php

declare(strict_types=1);

namespace Magelearn\Slider\Controller\Adminhtml\Slider;

use Exception;
use Magelearn\Slider\Api\SliderRepositoryInterface;
use Magelearn\Slider\Model\ResourceModel\Slider\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magelearn_Slider::slider';

    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;

    /**
     * @var CollectionFactory
     */
    private $sliderCollectionFactory;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @param Context $context
     * @param SliderRepositoryInterface $sliderRepository
     * @param CollectionFactory $sliderCollectionFactory
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        SliderRepositoryInterface $sliderRepository,
        CollectionFactory $sliderCollectionFactory,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->sliderRepository = $sliderRepository;
        $this->sliderCollectionFactory = $sliderCollectionFactory;
        $this->filter = $filter;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $slidersCollectionSize = $this->removeElements();
            $this->messageManager->addSuccessMessage(
                __('A total of %1 slider(s) have been deleted.', $slidersCollectionSize)
            );
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong: %1', $e->getMessage()));
        }

        return $this->_redirect('*/*/');
    }


    /**
     * @return int
     * @throws LocalizedException
     */
    private function removeElements(): int
    {
        $slidersCollection = $this->filter->getCollection($this->sliderCollectionFactory->create());
        $slidersCollectionSize = $slidersCollection->getSize();

        foreach ($slidersCollection as $sliderAnemicModel) {
            $this->sliderRepository->delete($sliderAnemicModel);
        }

        return $slidersCollectionSize;
    }
}
