<?php

namespace Magelearn\Slider\Controller\Adminhtml\Slide;

use Exception;
use Magelearn\Slider\Api\Data\SlideInterface;
use Magelearn\Slider\Api\Data\SlideInterfaceFactory;
use Magelearn\Slider\Api\SlideRepositoryInterface;
use Magelearn\Slider\Model\Slide;
use Magelearn\Slider\Ui\Component\Slide\DataProvider;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magelearn_Slider::slide';

    /**
     * @var SlideInterfaceFactory
     */
    private $slideFactory;

    /**
     * @var SlideRepositoryInterface
     */
    private $slideRepository;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    
    protected $logger;

    /**
     * @param Context $context
     * @param SlideInterfaceFactory $slideFactory
     * @param SlideRepositoryInterface $slideRepository
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        SlideInterfaceFactory $slideFactory,
        SlideRepositoryInterface $slideRepository,
        DataPersistorInterface $dataPersistor,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->slideFactory     = $slideFactory;
        $this->slideRepository  = $slideRepository;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
    }

    /**
     * Save slide
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            if (empty($data[SlideInterface::SLIDE_ID])) {
                $data[SlideInterface::SLIDE_ID] = null;
            }

            /** @var SlideInterface|Slide $model */
            $model = $this->slideFactory->create();
            $id = $this->_request->getParam(SlideInterface::SLIDE_ID);

            if ($id) {
                try {
                    $model = $this->slideRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This slide no longer exists.'));

                    return $resultRedirect->setPath('*/*/');
                }
            }
            if(isset($data['daily_deal_product_id']) && is_array($data['daily_deal_product_id']) && !empty($data['daily_deal_product_id'])) {
                $data['daily_deal_product_id'] = trim(implode(",", $data['daily_deal_product_id']), ",");
            }
            $model->setData($data);

            try {
                $slide = $this->slideRepository->save($model);
                $this->messageManager->addSuccessMessage(__('Slide has been saved.'));
                $this->dataPersistor->clear(DataProvider::DATA_PERSISTOR_KEY);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', [
                        SlideInterface::SLIDE_ID => $slide->getSlideId(),
                        SlideInterface::SLIDER_ID => $slide->getSliderId()
                    ]);
                }

                return $resultRedirect->setPath('*/*/', [
                    SlideInterface::SLIDER_ID => $slide->getSliderId()
                ]);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Slide.'));
            }

            $this->dataPersistor->set(DataProvider::DATA_PERSISTOR_KEY, $data);

            return $resultRedirect->setPath('*/*/edit', [
                SlideInterface::SLIDE_ID => $id,
                SlideInterface::SLIDER_ID => $model->getSliderId()
            ]);
        }

        return $this->_redirect('*/*/', ['slider_id' => $data['slider_id']]);
    }
}
