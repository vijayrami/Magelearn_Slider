<?php

namespace Magelearn\Slider\Block\Adminhtml\Slide\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Reset
 * @package Magelearn\Slider\Block\Adminhtml\Slide\Edit
 */
class Reset extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 20
        ];
    }
}
