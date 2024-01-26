<?php

namespace Magelearn\Slider\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Slide
 * @package Magelearn\Slider\Model\ResourceModel
 */
class Slide extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('magelearn_slider_slider_item', 'slide_id');
    }
}
