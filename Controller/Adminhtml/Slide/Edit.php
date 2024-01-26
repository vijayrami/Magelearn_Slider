<?php

declare(strict_types=1);

namespace Magelearn\Slider\Controller\Adminhtml\Slide;

use Magelearn\Slider\Api\SlideRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 * @package Magelearn\Slider\Controller\Adminhtml\Slide
 */
class Edit extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Magelearn_Slider::slide';

    /**
     * @var SlideRepositoryInterface
     */
    private $slideRepository;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param SlideRepositoryInterface $slideRepository
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        SlideRepositoryInterface $slideRepository,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->slideRepository = $slideRepository;
        $this->resultPageFactory = $pageFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('slide_id');
        if ($id) {
            $slideModel = $this->slideRepository->getById($id);
            $pageTitle = __('Edit slide "%1"', $slideModel->getTitle());
        } else {
            $pageTitle = __('Add new slide');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend($pageTitle);

        return $resultPage;
    }
}
