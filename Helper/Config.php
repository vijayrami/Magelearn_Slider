<?php

declare(strict_types=1);

namespace Magelearn\Slider\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    private const XML_PATH_MODULE_ENABLED   = 'magelearn_slider/general/is_enabled';
    private const XML_PATH_AUTOPLAY         = 'magelearn_slider/display_settings/autoplay';
    private const XML_PATH_ACCESSIBILITY         = 'magelearn_slider/display_settings/accessibility';
    private const XML_PATH_AUTOPLAY_SPEED   = 'magelearn_slider/display_settings/autoplay_speed';
    private const XML_PATH_SPEED   = 'magelearn_slider/display_settings/speed';
    private const XML_PATH_PAUSE_ON_FOCUS   = 'magelearn_slider/display_settings/pause_on_focus';
    private const XML_PATH_PAUSE_ON_HOVER   = 'magelearn_slider/display_settings/pause_on_hover';
    private const XML_PATH_PAUSE_ON_DOTS_HOVER   = 'magelearn_slider/display_settings/pause_on_dots_hover';
    private const XML_PATH_SHOW_ARROWS      = 'magelearn_slider/display_settings/show_arrows';
    private const XML_PATH_INFINITE_LOOP    = 'magelearn_slider/display_settings/infinite_loop';
    private const XML_PATH_SHOW_DOTS        = 'magelearn_slider/display_settings/show_dots';
    private const XML_PATH_SLIDES_TO_SHOW   = 'magelearn_slider/display_settings/slides_to_show';
    private const XML_PATH_SLIDES_TO_SCROLL = 'magelearn_slider/display_settings/slides_to_scroll';
    private const XML_PATH_LAZY_LOAD = 'magelearn_slider/display_settings/lazy_load';
    private const XML_PATH_CSS_EASE = 'magelearn_slider/display_settings/css_ease';

    /** @var RequestInterface $request */
    private $request;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
        $this->request = $context->getRequest();
    }

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_MODULE_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getSliderId()
    {
        return (int) $this->request->getParam('slider_id');
    }

    /**
     * @return bool
     */
    public function isSliderEnabled(): bool
    {
        return $this->isModuleEnabled();
    }

    /**
     * @return bool
     */
    public function isAutoplayEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_AUTOPLAY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getAutoplaySpeed(): int
    {
        return (int) $this->scopeConfig->getValue(
            static::XML_PATH_AUTOPLAY_SPEED,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * @return int
     */
    public function getSpeed(): int
    {
        return (int) $this->scopeConfig->getValue(
            static::XML_PATH_SPEED,
            ScopeInterface::SCOPE_STORE
            );
    }

    /**
     * @return bool
     */
    public function isSlidePausedOnFocus(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_PAUSE_ON_FOCUS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isSlidePausedOnHover(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_PAUSE_ON_HOVER,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * @return bool
     */
    public function isSlidePauseOnDotsHover(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_PAUSE_ON_DOTS_HOVER,
            ScopeInterface::SCOPE_STORE
            );
    }

    /**
     * @return bool
     */
    public function isShowingArrowsEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_ARROWS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isInfiniteLoopEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_INFINITE_LOOP,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isShowingDotsEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_DOTS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getNumberOfSlidesToShow(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_SLIDES_TO_SHOW,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getNumberOfSlidesToScroll(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_SLIDES_TO_SCROLL,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * @return string
     */
    public function getLazyLoad(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_LAZY_LOAD,
            ScopeInterface::SCOPE_STORE
            );
    }
    
    /**
     * @return string
     */
    public function getCssEase(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CSS_EASE,
            ScopeInterface::SCOPE_STORE
            );
    }
    
    /**
     * @return bool
     */
    public function getAccessibility(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ACCESSIBILITY,
            ScopeInterface::SCOPE_STORE
            );
    }
}
