<?php

namespace Magelearn\Slider\Block\Adminhtml\Slide\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Back
 * @package Magelearn\Slider\Block\Adminhtml\Slide\Edit
 */
class NewAction extends GenericButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Add new slide'),
            'on_click' => $this->getNewActionUrl(),
            'class' => 'primary',
            'sort_order' => 100
        ];
    }

    /**
     * @return string
     */
    public function getNewActionUrl()
    {
        return sprintf(
            "location.href = '%s';",
            $this->getUrl(
                '*/*/newAction',
                [
                    'slide_id' => 0,
                    'slider_id' => $this->getSliderId()
                ]
            )
        );
    }
}
