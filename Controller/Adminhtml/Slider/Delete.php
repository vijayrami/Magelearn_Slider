<?php

namespace Magelearn\Slider\Controller\Adminhtml\Slider;

use Magelearn\Slider\Api\SliderRepositoryInterface;
use Magelearn\Slider\Model\SliderFactory;
use Magento\Backend\App\{Action, Action\Context};

/**
 * Class Delete
 * @package Magelearn\Slider\Controller\Adminhtml\Slider
 */
class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Magelearn_Slider::slider';

    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;

    /**
     * Delete constructor.
     * @param Context $context
     * @param SliderRepositoryInterface $sliderRepository
     */
    public function __construct(
        Context $context,
        SliderRepositoryInterface $sliderRepository
    ) {
        parent::__construct($context);
        $this->sliderRepository = $sliderRepository;
    }

    /**
     * @param $id
     */
    protected function removeSlider($id)
    {
        try {
            $this->sliderRepository->deleteById($id);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('slider_id');
        $this->removeSlider($id);
        $this->messageManager->addSuccessMessage(__('Slider has been deleted.'));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('slider/*/');
    }
}
