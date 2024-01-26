<?php

namespace Magelearn\Slider\Controller\Adminhtml\Slider;

use Magelearn\Slider\Api\Data\SliderInterface;
use Magelearn\Slider\Api\Data\SliderInterfaceFactory;
use Magelearn\Slider\Api\SliderRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Model\Exception;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Save
 * @package Magelearn\Slider\Controller\Adminhtml\Slider
 */
class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magelearn_Slider::slider';

    /**
     * @var SliderFactory
     */
    private $sliderFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * Save constructor.
     * @param Context $context
     * @param SliderInterfaceFactory\ $sliderAnemicModelFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        SliderRepositoryInterface $sliderRepository,
        SliderInterfaceFactory $sliderFactory,
        DataObjectHelper $dataObjectHelper,
        StoreManagerInterface $storeManager
    ) {
        $this->sliderRepository = $sliderRepository;
        $this->sliderFactory    = $sliderFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->storeManager     = $storeManager;
        parent::__construct($context);
    }


    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if ($data) {

            $sliderAnemicModel = $this->sliderFactory->create();

            try {
                if ($data[SliderInterface::SLIDER_ID]) {
                    $sliderAnemicModel = $this->sliderRepository->getById($data[SliderInterface::SLIDER_ID]);
                } else {
                    unset($data[SliderInterface::SLIDER_ID]);
                }

                $this->dataObjectHelper->populateWithArray(
                    $sliderAnemicModel,
                    $data,
                    SliderInterface::class
                );

                $this->sliderRepository->save($sliderAnemicModel);

                $this->messageManager->addSuccessMessage(__('Slider has been saved.'));
            } catch (\Exception $e) {

                $this->messageManager->addErrorMessage(__("Something went wrong: %1", $e->getMessage()));
            }

            $this->_getSession()->setFormData($data);

            if ($this->getRequest()->getParam('back')) {
                return $this->_redirect('slider/slider/edit',
                    ['slider_id' => $sliderAnemicModel->getSliderId(), '_current' => true]);
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('slider/*/');
    }
}
