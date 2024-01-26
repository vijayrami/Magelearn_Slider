<?php

namespace Magelearn\Slider\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Slider
 * @package Magelearn\Slider\Model\ResourceModel
 */
class Slider extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('magelearn_slider_slider', 'slider_id');
    }
}
