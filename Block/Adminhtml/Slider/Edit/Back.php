<?php

namespace Magelearn\Slider\Block\Adminhtml\Slider\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Back
 * @package Magelearn\Slider\Block\Adminhtml\Slider\Edit
 */
class Back extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => $this->getBackUrl(),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return sprintf("location.href = '%s';", $this->getUrl('*/*/'));
    }
}
