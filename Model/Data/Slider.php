<?php

namespace Magelearn\Slider\Model\Data;

use Magelearn\Slider\Api\Data\SliderInterface;
use Magento\Framework\Model\AbstractModel;

class Slider extends AbstractModel implements SliderInterface
{
    /**
     * @return int
     */
    public function getSliderId()
    {
        return $this->_getData(self::SLIDER_ID);
    }

    /**
     * @param int $sliderId
     * @return SliderInterface
     */
    public function setSliderId($sliderId): SliderInterface
    {
        return $this->setData(self::SLIDER_ID, $sliderId);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_getData(self::TITLE);
    }

    /**
     * @param string $title
     * @return SliderInterface
     */
    public function setTitle($title): SliderInterface
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->_getData(self::STATUS);
    }

    /**
     * @param bool $status
     * @return SliderInterface
     */
    public function setStatus($status): SliderInterface
    {
        return $this->setData(self::STATUS, $status);
    }
}
