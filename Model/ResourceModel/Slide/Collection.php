<?php

declare(strict_types=1);

namespace Magelearn\Slider\Model\ResourceModel\Slide;

use Magelearn\Slider\Model\ResourceModel\Slide as SlideResource;
use Magelearn\Slider\Model\Slide;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(Slide::class, SlideResource::class);
        $this->_setIdFieldName('slide_id');
    }
}
