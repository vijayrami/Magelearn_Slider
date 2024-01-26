<?php

namespace Magelearn\Slider\Controller\Adminhtml\Slide;

use Magelearn\Slider\Api\SlideRepositoryInterface;
use Magento\Backend\App\{Action, Action\Context};
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class Delete
 * @package Magelearn\Slider\Controller\Adminhtml\Slide
 */
class Delete extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Magelearn_Slider::slide';

    /**
     * @var SlideRepositoryInterface
     */
    private $slideRepository;

    /**
     * Delete constructor.
     * @param Context $context
     * @param SlideRepositoryInterface $slideRepository
     */
    public function __construct(
        Context $context,
        SlideRepositoryInterface $slideRepository
    ) {
        parent::__construct($context);
        $this->slideRepository = $slideRepository;
    }

    /**
     * @param $id
     */
    protected function removeSlide($id)
    {
        try {
            $this->slideRepository->deleteById($id);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('slide_id');
        $this->removeSlide($id);
        $this->messageManager->addSuccessMessage(__('Slide has been deleted.'));

        return $this->_redirect('*/*/', ['slider_id' => $this->getRequest()->getParam('slider_id')]);
    }
}
