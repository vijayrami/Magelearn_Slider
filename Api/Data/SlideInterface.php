<?php

namespace Magelearn\Slider\Api\Data;

interface SlideInterface
{
    public const SLIDE_ID           = 'slide_id';
    public const SLIDER_ID          = 'slider_id';
    public const STATUS             = 'status';
    public const DATE_FROM          = 'date_from';
    public const DATE_TO            = 'date_to';
    public const IS_ACTIVE          = 'is_active';
    public const POSITION           = 'position';
    public const TITLE              = 'title';
    public const URL                = 'url';
    public const IMAGE_THUMBNAIL    = 'image_thumbnail';
    public const IMAGE_MOBILE       = 'image_mobile';
    public const IMAGE_SMALL        = 'image_small';
    public const IMAGE_MEDIUM       = 'image_medium';
    public const CREATED_AT         = 'created_at';
    
    public const MEDIA_SUBDIRECTORY_PATH = 'slider/images/upload/';
    
    public const VIDEO = 'video';
    public const VIDEO_CONTENT = 'video_content';
    public const CONTENT = 'content';
    
    public const IS_ACTIVE_COUNTDOWN = 'is_active_countdown';
    public const COUNTDOWN_DATE_FROM = 'countdown_date_from';
    public const COUNTDOWN_DATE_TO = 'countdown_date_to';
    public const COUNTDOWN_COLOR = 'countdown_color';
    public const COUNTDOWN_BACKGROUND_COLOR = 'countdown_background_color';
    
    public const SHOW_DAILY_DEAL = 'show_daily_deal';
    public const DAILY_DEAL_PRODUCT_ID   = 'daily_deal_product_id';
    public const DAILY_DEAL_COLOR = 'daily_deal_color';
    public const DAILY_DEAL_BACKGROUND_COLOR  = 'daily_deal_background_color';
    public const DAILY_DEAL_TOP = 'daily_deal_top';
    public const DAILY_DEAL_LEFT = 'daily_deal_left';

    /**
     * @return int
     */
    public function getSlideId();

    /**
     * @param int $slideId
     * @return SlideInterface
     */
    public function setSlideId($slideId): self;

    /**
     * @return int
     */
    public function getSliderId();

    /**
     * @param int $sliderId
     * @return SlideInterface
     */
    public function setSliderId($sliderId): self;

    /**
     * @return bool
     */
    public function getStatus();

    /**
     * @param bool $status
     * @return SlideInterface
     */
    public function setStatus($status): self;

    /**
     * @return string
     */
    public function getDateFrom();

    /**
     * @param string $date
     * @return SlideInterface
     */
    public function setDateFrom($date): self;

    /**
     * @return string
     */
    public function getDateTo();

    /**
     * @param string $date
     * @return SlideInterface
     */
    public function setDateTo($date): self;

    /**
     * @return bool
     */
    public function getIsActive();

    /**
     * @param bool $value
     * @return SlideInterface
     */
    public function setIsActive(bool $value): self;

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param int $position
     * @return SlideInterface
     */
    public function setPosition($position): self;

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return SlideInterface
     */
    public function setTitle($title): self;

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     * @return SlideInterface
     */
    public function setUrl($url): self;

    /**
     * @return string
     */
    public function getImageThumbnail();

    /**
     * @param string $filename
     * @return SlideInterface
     */
    public function setImageThumbnail($filename): self;

    /**
     * @return string
     */
    public function getImageMobile();

    /**
     * @param string $filename
     * @return SlideInterface
     */
    public function setImageMobile($filename): self;

    /**
     * @return string
     */
    public function getImageSmall();

    /**
     * @param string $filename
     * @return SlideInterface
     */
    public function setImageSmall($filename): self;

    /**
     * @return string
     */
    public function getImageMedium();

    /**
     * @param string $filename
     * @return SlideInterface
     */
    public function setImageMedium($filename): self;
    
    /**
     * @return int
     */
    public function getVideo();
    
    /**
     * @param string $video
     * @return SlideInterface
     */
    public function setVideo(string $video): self;
    
    /**
     * @return mixed
     */
    public function getVideoContent();
    
    /**
     * @param string|null $videoContent
     *
     * @return $this
     */
    public function setVideoContent(?string $videoContent): self;
    
    /**
     * @return bool
     */
    public function isActiveCountdown();
    
    /**
     * @param bool $isActiveCountdown
     * @return SlideInterface
     */
    public function setIsActiveCountdown(bool $isActiveCountdown): self;
    
    /**
     * @return string
     */
    public function getContent();
    
    /**
     * @param string|null $content
     *
     * @return $this
     */
    public function setContent(?string $content): self;
    
    /**
     * @return string
     */
    public function getCountdownDateFrom();
    
    /**
     * @param string $countdownDateFrom
     *
     * @return SlideInterface
     */
    public function setCountdownDateFrom(string $countdownDateFrom);
    
    /**
     * @return string
     */
    public function getCountdownDateTo();
    
    /**
     * @param string $countdownDateTo
     *
     * @return SlideInterface
     */
    public function setCountdownDateTo(string $countdownDateTo);
    
    /**
     * @return string
     */
    public function getCountdownColor();
    
    /**
     * @param string $countdownColor
     *
     * @return SlideInterface
     */
    public function setCountdownColor(string $countdownColor);
    
    /**
     * @return string
     */
    public function getCountdownBackgroundColor();
    
    /**
     * @param string $countdownBackgroundColor
     *
     * @return SlideInterface
     */
    public function setCountdownBackgroundColor(string $countdownBackgroundColor);
    
    /**
     * @return bool
     */
    public function hasShowDailyDeal();
    
    /**
     * @param bool $status
     *
     * @return SlideInterface
     */
    public function setShowDailyDeal(bool $status);
    
    /**
     * @return string|null
     */
    public function getDailyDealProductId();
    
    /**
     * @param null|string $dailyDealId
     *
     * @return SlideInterface
     */
    public function setDailyDealProductId(?string $dailyDealId);
    
    /**
     * @return string
     */
    public function getDailyDealColor();
    
    /**
     * @param null|string $color
     *
     * @return SlideInterface
     */
    public function setDailyDealColor(?string $color);
    
    /**
     * @return string
     */
    public function getDailyDealBackgroundColor();
    
    /**
     * @param null|string $color
     *
     * @return SlideInterface
     */
    public function setDailyDealBackgroundColor(?string $color);
    
    /**
     * @return string
     */
    public function getDailyDealTop();
    
    /**
     * @param null|string $top
     *
     * @return SlideInterface
     */
    public function setDailyDealTop(?string $top);
    
    /**
     * @return string
     */
    public function getDailyDealLeft();
    
    /**
     * @param null|string $left
     *
     * @return SlideInterface
     */
    public function setDailyDealLeft(?string $left);
}
