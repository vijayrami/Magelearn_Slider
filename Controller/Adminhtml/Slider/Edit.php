<?php

namespace Magelearn\Slider\Controller\Adminhtml\Slider;

use Magelearn\Slider\Api\Data\SliderInterfaceFactory;
use Magelearn\Slider\Api\SliderRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 * @package Magelearn\Slider\Controller\Adminhtml\Slider
 */
class Edit extends Action
{

    public const ADMIN_RESOURCE = 'Magelearn_Slider::slider';

    /**
     * @var SliderInterfaceFactory
     */
    private $sliderRepository;
    /**
     * @var PageFactory
     */
    private $resultPageFactory;
    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var SliderInterfaceFactory
     */
    private $sliderFactory;

    /**
     * Edit constructor.
     * @param Context $context
     * @param SliderRepositoryInterface $sliderRepository
     * @param SliderInterfaceFactory $sliderFactory
     * @param Registry $registry
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        SliderRepositoryInterface $sliderRepository,
        SliderInterfaceFactory $sliderFactory,
        Registry $registry,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->sliderRepository  = $sliderRepository;
        $this->coreRegistry      = $registry;
        $this->resultPageFactory = $pageFactory;
        $this->sliderFactory     = $sliderFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('slider_id');
        if ($id) {
            $sliderAnemicModel = $this->sliderRepository->getById($id);
        } else {
            $sliderAnemicModel = $this->sliderFactory->create();
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend($sliderAnemicModel->getSliderId() ? $sliderAnemicModel->getTitle() : __('Add new slider'));

        return $resultPage;
    }
}
