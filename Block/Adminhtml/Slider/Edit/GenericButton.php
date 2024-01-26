<?php

namespace Magelearn\Slider\Block\Adminhtml\Slider\Edit;

use Magento\{Backend\Block\Widget\Context, Framework\App\RequestInterface, Framework\UrlInterface};

/**
 * Class GenericButton
 * @package Magelearn\Slider\Block\Adminhtml\Slider\Edit
 */
class GenericButton
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * GenericButton constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->request    = $context->getRequest();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->request->getParam('slider_id');
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
