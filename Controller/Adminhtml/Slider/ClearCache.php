<?php

namespace Magelearn\Slider\Controller\Adminhtml\Slider;

use Magelearn\Slider\Block\Slider;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Cms\Helper\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Result\PageFactory;
use Magento\PageCache\Model\Cache\Type;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Index
 * @package Magelearn\Slider\Controller\Adminhtml\Slider
 */
class ClearCache extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Magelearn_Slider::slider';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Type
     */
    private $fullPageCache;

    /**
     * @var mixed
     */
    private $scopeConfig;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig = null
    ) {
        parent::__construct($context);
        $this->scopeConfig  = $scopeConfig ?: ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        $this->storeManager = $storeManager;
    }

    public function execute()
    {
        // clean all sliders cache
        $this->getCache()->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG, [Slider::CACHE_TAG]);
        $this->messageManager->addSuccessMessage(__('All sliders cache cleared'));

        foreach ($this->storeManager->getStores() as $store) {
            $storeHomepageId = $this->scopeConfig->getValue(Page::XML_PATH_HOME_PAGE, ScopeInterface::SCOPE_STORE,
                $store->getCode());
            $this->getCache()->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG,
                [\Magento\Cms\Model\Page::CACHE_TAG . '_' . $storeHomepageId]);
            $this->messageManager->addSuccessMessage(__('Homepage cache cleared for %1', $store->getCode()));
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('slider/*/');
    }

    private function getCache()
    {
        if (!$this->fullPageCache) {
            $this->fullPageCache = ObjectManager::getInstance()->get(Type::class);
        }
        return $this->fullPageCache;
    }
}
