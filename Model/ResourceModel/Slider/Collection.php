<?php

namespace Magelearn\Slider\Model\ResourceModel\Slider;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Magelearn\Slider\Model\ResourceModel\Slider
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'slider_id';

    protected function _construct()
    {
        $this->_init(\Magelearn\Slider\Model\Slider::class, \Magelearn\Slider\Model\ResourceModel\Slider::class);
    }

    /** switch to data models */
    protected final function _afterLoad()
    {
        parent::_afterLoad();

        $anemicItems = [];

        /** @var \Magelearn\Slider\Model\Slider $item */
        foreach ($this->_items as $item) {
            $anemicItems[] = $item->getDataModel();
        }

        $this->_items = $anemicItems;

        return $this;
    }
}
