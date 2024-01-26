<?php

namespace Magelearn\Slider\Block\Adminhtml\Slide\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Delete
 * @package Magelearn\Slider\Block\Adminhtml\Slide\Edit
 */
class Delete extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];

        if ($this->getId()) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this slide?')
                    . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 30,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['slide_id' => $this->getId(), 'slider_id' => $this->getSliderId()]);
    }
}
