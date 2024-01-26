<?php

declare(strict_types=1);

namespace Magelearn\Slider\Model;

use Magelearn\Slider\Api\Data\SlideInterface;
use Magelearn\Slider\Model\ResourceModel\Slide as SlideResource;
use Magento\Framework\DataObject;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Slide extends AbstractModel implements SlideInterface
{
    /** @var EventManager */
    private EventManager $eventManager;
    
    /**
     * Slide constructor.
     *
     * @param Context               $context
     * @param Registry              $registry
     * @param EventManager          $eventManager
     * @param AbstractResource|null $resource
     * @param AbstractDb|null       $resourceCollection
     * @param array                 $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        EventManager $eventManager,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
        ) {
            parent::__construct($context, $registry, $resource, $resourceCollection, $data);
            
            $this->eventManager = $eventManager;
    }

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(SlideResource::class);
    }

    /**
     * @return int
     */
    public function getSlideId()
    {
        return $this->_getData(self::SLIDE_ID);
    }

    /**
     * @param int $slideId
     * @return SlideInterface
     */
    public function setSlideId($slideId): SlideInterface
    {
        return $this->setData(self::SLIDE_ID, $slideId);
    }

    /**
     * @return int
     */
    public function getSliderId()
    {
        return $this->_getData(self::SLIDER_ID);
    }

    /**
     * @param int $sliderId
     * @return SlideInterface
     */
    public function setSliderId($sliderId): SlideInterface
    {
        return $this->setData(self::SLIDER_ID, $sliderId);
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
     * @return SlideInterface
     */
    public function setStatus($status): SlideInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @return string
     */
    public function getDateFrom()
    {
        return $this->_getData(self::DATE_FROM);
    }

    /**
     * @param string $date
     * @return SlideInterface
     */
    public function setDateFrom($date): SlideInterface
    {
        return $this->setData(self::DATE_FROM, $date);
    }

    /**
     * @return string
     */
    public function getDateTo()
    {
        return $this->_getData(self::DATE_TO);
    }

    /**
     * @param string $date
     * @return SlideInterface
     */
    public function setDateTo($date): SlideInterface
    {
        return $this->setData(self::DATE_TO, $date);
    }

    /**
     * @inheritDoc
     */
    public function getIsActive(): bool
    {
        return (bool)$this->_getData(self::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive(bool $value): SlideInterface
    {
        return $this->setData(self::IS_ACTIVE, $value);
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->_getData(self::POSITION);
    }

    /**
     * @param int $position
     * @return SlideInterface
     */
    public function setPosition($position): SlideInterface
    {
        return $this->setData(self::POSITION, $position);
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
     * @return SlideInterface
     */
    public function setTitle($title): SlideInterface
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_getData(self::URL);
    }

    /**
     * @param string $url
     * @return SlideInterface
     */
    public function setUrl($url): SlideInterface
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @return string
     */
    public function getImageThumbnail()
    {
        return $this->_getData(self::IMAGE_THUMBNAIL);
    }

    /**
     * @param string $filename
     * @return SlideInterface
     */
    public function setImageThumbnail($filename): SlideInterface
    {
        return $this->setData(self::IMAGE_THUMBNAIL, $filename);
    }

    /**
     * @return string
     */
    public function getImageMobile()
    {
        return $this->_getData(self::IMAGE_MOBILE);
    }

    /**
     * @param string $filename
     * @return SlideInterface
     */
    public function setImageMobile($filename): SlideInterface
    {
        return $this->setData(self::IMAGE_MOBILE, $filename);
    }

    /**
     * @return string
     */
    public function getImageSmall()
    {
        return $this->_getData(self::IMAGE_SMALL);
    }

    /**
     * @param string $filename
     * @return SlideInterface
     */
    public function setImageSmall($filename): SlideInterface
    {
        return $this->setData(self::IMAGE_SMALL, $filename);
    }

    /**
     * @return string
     */
    public function getImageMedium()
    {
        return $this->_getData(self::IMAGE_MEDIUM);
    }

    /**
     * @param string $filename
     * @return SlideInterface
     */
    public function setImageMedium($filename): SlideInterface
    {
        return $this->setData(self::IMAGE_MEDIUM, $filename);
    }
    
    /** @inheritDoc */
    public function getVideo()
    {
        return (int) $this->_getData(self::VIDEO);
    }
    
    /** @inheritDoc */
    public function setVideo(string $video): SlideInterface
    {
        return $this->setData(self::VIDEO, $video);
    }
    
    /** @inheritDoc */
    public function getVideoContent()
    {
        return $this->_getData(self::VIDEO_CONTENT);
    }
    
    /** @inheritDoc */
    public function setVideoContent(?string $videoContent): SlideInterface
    {
        return $this->setData(self::VIDEO_CONTENT, $videoContent);
    }
    
    /** @inheritDoc */
    public function isActiveCountdown()
    {
        return (bool) $this->_getData(self::IS_ACTIVE_COUNTDOWN);
    }
    
    /** @inheritDoc */
    public function setIsActiveCountdown(bool $isActiveCountdown): SlideInterface
    {
        return $this->setData(self::IS_ACTIVE_COUNTDOWN, $isActiveCountdown);
    }
    
    /** @inheritDoc */
    public function getContent()
    {
        return $this->_getData(self::CONTENT);
    }
    
    /** @inheritDoc */
    public function setContent($content): SlideInterface
    {
        return $this->setData(self::CONTENT, $content);
    }
    
    /** @inheritDoc */
    public function getCountdownDateFrom()
    {
        return $this->_getData(self::COUNTDOWN_DATE_FROM);
    }
    
    /** @inheritDoc */
    public function setCountdownDateFrom(string $countdownDateFrom): SlideInterface
    {
        return $this->setData(self::COUNTDOWN_DATE_FROM, $countdownDateFrom);
    }
    
    /** @inheritDoc */
    public function getCountdownDateTo()
    {
        return $this->_getData(self::COUNTDOWN_DATE_TO);
    }
    
    /** @inheritDoc */
    public function setCountdownDateTo(string $countdownDateTo): SlideInterface
    {
        return $this->setData(self::COUNTDOWN_DATE_TO, $countdownDateTo);
    }
    
    /** @inheritDoc */
    public function getCountdownColor()
    {
        return $this->_getData(self::COUNTDOWN_COLOR);
    }
    
    /** @inheritDoc */
    public function setCountdownColor(string $countdownColor): SlideInterface
    {
        return $this->setData(self::COUNTDOWN_COLOR, $countdownColor);
    }
    
    /** @inheritDoc */
    public function getCountdownBackgroundColor()
    {
        return $this->_getData(self::COUNTDOWN_BACKGROUND_COLOR);
    }
    
    /** @inheritDoc */
    public function setCountdownBackgroundColor(string $countdownBackgroundColor): SlideInterface
    {
        return $this->setData(self::COUNTDOWN_BACKGROUND_COLOR, $countdownBackgroundColor);
    }
    
    /** @inheritDoc */
    public function hasShowDailyDeal()
    {
        return (bool) $this->_getData(self::SHOW_DAILY_DEAL);
    }
    
    /** @inheritDoc */
    public function setShowDailyDeal(bool $status): SlideInterface
    {
        return $this->setData(self::SHOW_DAILY_DEAL, $status);
    }
    
    /** @inheritDoc */
    public function getDailyDealProductId()
    {
        return $this->_getData(self::DAILY_DEAL_PRODUCT_ID);
    }
    
    /** @inheritDoc */
    public function setDailyDealProductId(?string $dailyDealId): SlideInterface
    {
        return $this->setData(self::DAILY_DEAL_PRODUCT_ID, $dailyDealId);
    }
    
    /** @inheritDoc */
    public function getDailyDealColor()
    {
        return $this->_getData(self::DAILY_DEAL_COLOR);
    }
    
    /** @inheritDoc */
    public function setDailyDealColor(?string $color): SlideInterface
    {
        return $this->setData(self::DAILY_DEAL_COLOR, $color);
    }
    
    /** @inheritDoc */
    public function getDailyDealBackgroundColor()
    {
        return $this->_getData(self::DAILY_DEAL_BACKGROUND_COLOR);
    }
    
    /** @inheritDoc */
    public function setDailyDealBackgroundColor(?string $color): SlideInterface
    {
        return $this->setData(self::DAILY_DEAL_BACKGROUND_COLOR, $color);
    }
    
    /** @inheritDoc */
    public function getDailyDealTop()
    {
        return $this->_getData(self::DAILY_DEAL_TOP);
    }
    
    /** @inheritDoc */
    public function setDailyDealTop(?string $top): SlideInterface
    {
        return $this->setData(self::DAILY_DEAL_TOP, $top);
    }
    
    /** @inheritDoc */
    public function getDailyDealLeft()
    {
        return $this->_getData(self::DAILY_DEAL_LEFT);
    }
    
    /** @inheritDoc */
    public function setDailyDealLeft(?string $left): SlideInterface
    {
        return $this->setData(self::DAILY_DEAL_LEFT, $left);
    }
    
    /** @inheritDoc */
    public function afterSave(): SlideInterface
    {
        if ($image = $this->getImageMedium()) {
            $filePath = new DataObject(['path' => self::MEDIA_SUBDIRECTORY_PATH . $image]);
            $this->eventManager->dispatch('magelearn_create_webp', ['magelearn_filepath' => $filePath]);
        }
        
        if ($image = $this->getImageMobile()) {
            $filePath = new DataObject(['path' => self::MEDIA_SUBDIRECTORY_PATH . $image]);
            $this->eventManager->dispatch('magelearn_create_webp', ['magelearn_filepath' => $filePath]);
        }

        return parent::afterSave();
    }
}
