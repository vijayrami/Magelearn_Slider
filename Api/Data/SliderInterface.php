<?php

namespace Magelearn\Slider\Api\Data;

interface SliderInterface
{
    public const SLIDER_ID     = 'slider_id';
    public const TITLE         = 'title';
    public const STATUS        = 'status';
    public const CREATED_AT    = 'created_at';

    /**
     * @return int
     */
    public function getSliderId();

    /**
     * @param int $sliderId
     * @return SliderInterface
     */
    public function setSliderId($sliderId): self;

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return SliderInterface
     */
    public function setTitle($title): self;

    /**
     * @return bool
     */
    public function getStatus();

    /**
     * @param bool $status
     * @return SliderInterface
     */
    public function setStatus($status): self;
}
