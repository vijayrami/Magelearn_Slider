<?php

declare(strict_types=1);

namespace Magelearn\Slider\Controller\Adminhtml\Slide\Image;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;
use Magelearn\Slider\Model\ImageUploader;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Upload extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magelearn_Slider::slide';

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;
    
    protected $logger;

    /**
     * @param Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param ImageUploader $imageUploader
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ImageUploader $imageUploader,
        JsonFactory $resultJsonFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context, $coreRegistry);
        $this->imageUploader = $imageUploader;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $imageId = $this->_request->getParam('param_name', 'image');

        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultJsonFactory->create()->setData($result);
    }
}
