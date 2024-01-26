<?php

declare(strict_types=1);

namespace Magelearn\Slider\Block;

use Magelearn\Slider\Api\Data\SlideInterface;
use Magelearn\Slider\Api\SliderManagementInterface;
use Magelearn\Slider\Helper\Config as ConfigHelper;
use Magelearn\Slider\Model\Slide\FileInfo;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Catalog\Helper\Image;
use Magento\Framework\DataObject;
use Magento\Framework\Escaper;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Block\Product\Context as Productcontext;
use Magento\Catalog\Block\Product\ReviewRendererInterface;
use Magento\Framework\View\LayoutFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\Pricing\Helper\Data;
use Magento\Framework\Data\Form\FormKey;
use DateTime;
use Exception;

class Slider extends Template implements IdentityInterface
{
    /** {@inheritDoc} */
    protected $_template = 'Magelearn_Slider::slider.phtml';

    public const CACHE_TAG           = 'MAGELEARN_SLIDER';
    public const DEFAULT_TEMPLATE_ID = 1;
    public const DISABLED            = 0;
    public const ENABLED             = 1;

    /** @var ConfigHelper $helper */
    private $helper;

    /** @var SliderManagementInterface $sliderSlides */
    private $sliderSlides;

    /** @var SlideInterface[]|null $slides */
    private $slides = null;

    /**
     * @var FileInfo
     */
    private $fileInfo;
    
    /** @var Escaper */
    protected $escaper;
    
    /** @var EventManager */
    private $eventManager;
    
    /** @var ProductRepository */
    private $productRepository;
    
    protected $_productImageHelper;
    
    /**
     * @var ReviewRendererInterface
     */
    protected $reviewRenderer;

    /**
     * @var RendererList
     */
    private $rendererListBlock;
    
    /**
     * @var EncoderInterface|null
     */
    private $urlEncoder;
    
    /**
     * @var LayoutFactory
     */
    private $layoutFactory;
    
    protected $logger;
    
    protected $_priceHelper;
    protected $_formKey;
    
    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $_cartHelper;
    
    /**
     * @var \Magento\Wishlist\Helper\Data
     */
    protected $_wishlistHelper;
    
    /**
     * @var \Magento\Catalog\Helper\Product\Compare
     */
    protected $_compareProduct;

    /**
     * Slider constructor.
     *
     * @param Context $context
     * @param SliderManagementInterface $sliderSlides
     * @param ConfigHelper $helper
     * @param FileInfo $fileInfo
     * @param EventManager $eventManager
     * @param ProductRepository $productRepository
     * @param Image $productImageHelper
     * @param Escaper $escaper
     * @param Data $priceHelper
     * @param FormKey $formKey
     * @param LoggerInterface $logger
     * @param EncoderInterface|null $urlEncoder
     * @param LayoutFactory|null $layoutFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Productcontext $productcontext,
        SliderManagementInterface $sliderSlides,
        ConfigHelper $helper,
        FileInfo $fileInfo,
        EventManager $eventManager,
        ProductRepository $productRepository,
        Image $productImageHelper,
        Escaper $escaper,
        LoggerInterface $logger,
        Data $priceHelper,
        FormKey $formKey,
        EncoderInterface $urlEncoder = null,
        LayoutFactory $layoutFactory = null,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper       = $helper;
        $this->_cartHelper = $productcontext->getCartHelper();
        $this->reviewRenderer = $productcontext->getReviewRenderer();
        $this->sliderSlides = $sliderSlides;
        $this->fileInfo = $fileInfo;
        $this->eventManager = $eventManager;
        $this->escaper = $escaper;
        $this->productRepository = $productRepository;
        $this->_productImageHelper = $productImageHelper;
        $this->logger = $logger;
        $this->_priceHelper = $priceHelper;
        $this->_formKey = $formKey;
        $this->_compareProduct = $productcontext->getCompareProduct();
        $this->_wishlistHelper = $productcontext->getWishlistHelper();
        $this->urlEncoder = $urlEncoder ?: ObjectManager::getInstance()->get(EncoderInterface::class);
        $this->layoutFactory = $layoutFactory ?: ObjectManager::getInstance()->get(LayoutFactory::class);
    }

    /**
     * Allow you to clear all sliders cache just using the main tag, or each slider by it's id.
     *
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [static::CACHE_TAG, static::CACHE_TAG . '_' . $this->getSliderId()];
    }

    /**
     * Returns slider_id passed in block data. If not present returns default id.
     */
    public function getSliderId()
    {
        return $this->getData('slider_id') ?? self::DEFAULT_TEMPLATE_ID;
    }

    /**
     * @return SlideInterface[]
     */
    public function getSlides(): array
    {
        if ($this->slides === null) {
            $this->slides = $this->sliderSlides->getSlides((int)$this->getSliderId(), true, true);
        }

        return $this->slides;
    }

    /**
     * @param $path
     *
     * @return string
     */
    public function getFileUrl($path): string
    {
        return $this->fileInfo->getImageUrl($path);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->helper->isSliderEnabled();
    }
    
    /**
     * @return string
     */
    public function getSliderConfig(): string
    {
        return json_encode([
            'accessibility'  => $this->helper->getAccessibility(),
            'pauseOnFocus'   => $this->helper->isSlidePausedOnFocus(),
            'pauseOnHover'   => $this->helper->isSlidePausedOnHover(),
            'pauseOnDotsHover' => $this->helper->isSlidePauseOnDotsHover(),
            'arrows'         => $this->helper->isShowingArrowsEnabled(),
            'infinite'       => $this->helper->isInfiniteLoopEnabled(),
            'slidesToShow'   => $this->helper->getNumberOfSlidesToShow(),
            'slidesToScroll' => $this->helper->getNumberOfSlidesToShow(),
            'dots'           => $this->helper->isShowingDotsEnabled(),
            'autoplay'       => $this->helper->isAutoplayEnabled(),
            'autoplaySpeed'  => $this->helper->getAutoplaySpeed(),
            'lazyLoad'       => $this->helper->getLazyLoad(),
            'speed'          => $this->helper->getSpeed(),
            'cssEase'        => $this->helper->getCssEase()
        ]);
    }
    
    /**
     * @param string|null $src
     * @param int $videotype
     *
     * @return string
     */
    public function getVideoContent(?string $src, $videotype): string
    {
        if($src && null !== $src && ($videotype == 1 || $videotype == 2)) {
            $options = '?enablejsapi=1&playsinline=1&controls=0&fs=0&rel=0&showinfo=0&start=0&autoplay=0&loop=1';
            
            return '<iframe class="embed-player" src="' . $src . $options .
            '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; ' .
            'gyroscope; picture-in-picture" allowfullscreen></iframe>';
        } elseif($src && null !== $src && $videotype == 3) {
            return '<video width="100%" height="100%" controls autoplay>
                      <source src="' . $src . '" type="video/mp4">
                    </video>';
        } else {
            return '';
        }
    }
    
    /**
     * @param null|string $topPosition
     * @param null|string $leftPosition
     *
     * @return string
     */
    public function getWrapperPosition(?string $topPosition, ?string $leftPosition): string
    {
        $html = '';
        
        if ($topPosition !== null) {
            $fromTop = $topPosition[0] === '-';
            if ($fromTop) {
                $side        = 'margin-bottom';
                $topPosition = substr($topPosition, 1);
            } else {
                $side = 'margin-top';
            }
            
            $html .= "$side: $topPosition;";
        }
        
        if ($leftPosition !== null) {
            $fromRight = $leftPosition[0] === '-';
            if ($fromRight) {
                $side         = 'margin-right';
                $leftPosition = substr($leftPosition, 1);
            } else {
                $side = 'margin-left';
            }
            
            $html .= "$side: $leftPosition;";
        }
        
        return empty($html) ? '' : 'style="' . $html . '"';
    }
    
    /**
     * @param string|null $foregroundColor
     * @param string|null $backgroundColor
     *
     * @return string
     */
    public function getStyles(?string $foregroundColor, ?string $backgroundColor): string
    {
        $html = '';
        
        if ($foregroundColor !== null) {
            $html .= "color: $foregroundColor; ";
        }
        
        if ($backgroundColor !== null) {
            $html .= "background-color: $backgroundColor; opacity: 0.8; ";
        }
        
        return empty($html) ? '' : 'style="' . $html . '"';
    }
    
    /**
     * Checks if counter conditions are met so we can display it. Conditions are:
     * counter must be enabled
     * counter date to must be set
     * if counter date from is set it must be before now
     *
     * @param SlideInterface $slide
     *
     * @return bool
     */
    public function displayCountdown(SlideInterface $slide): bool
    {
        $to = $slide->getCountdownDateTo();
        
        // counter must be enabled and `counter date to` must be set otherwise we can't display countdown
        if (!$slide->isActiveCountdown() || empty($to)) {
            return false;
        }
        
        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');
        
        // if now is before `counter date from` then it can't be displayed (not yet)
        $from = $slide->getCountdownDateFrom();
        
        if (!empty($from) && $from > $now) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @param SlideInterface $slide
     *
     * @return bool
     *
     * @throws Exception
     */
    public function displayDailyDealInformation(SlideInterface $slide): bool
    {
        if ($slide->hasShowDailyDeal() === false) {
            return false;
        }
        
        if ($slide->getDailyDealProductId() == 0) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get Product by Id
     * @param int
     * @return \Magento\Catalog\Model\Product $product
     */
    public function getProduct(int $productId)
    {
        return $this->productRepository->getById($productId);
    }
    
    public function getProductBySku($sku)
    {
        return $this->productRepository->get($sku);
    }
    
    /**
     * Retrieve Product URL using UrlDataObject
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param array $additional the route params
     * @return string
     */
    public function getProductUrl($product, $additional = [])
    {
        if ($this->hasProductUrl($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            return $product->getUrlModel()->getUrl($product, $additional);
        }
        
        return '#';
    }
    
    /**
     * Check Product has URL
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function hasProductUrl($product)
    {
        if ($product->getVisibleInSiteVisibilities()) {
            return true;
        }
        if ($product->hasUrlDataObject()) {
            if (in_array($product->hasUrlDataObject()->getVisibility(), $product->getVisibleInSiteVisibilities())) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Schedule resize of the image
     * $width *or* $height can be null - in this case, lacking dimension will be calculated.
     *
     * @see \Magento\Catalog\Model\Product\Image
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function resizeImage($product, $imageId, $width, $height = null)
    {
        $resizedImage = $this->_productImageHelper
                           ->init($product, $imageId)
                           ->constrainOnly(TRUE)
                           ->keepAspectRatio(TRUE)
                           ->keepTransparency(TRUE)
                           ->keepFrame(FALSE)
                           ->resize($width, $height);
        return $resizedImage;
    }
    
    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getProductPriceHtml(
        Product $product,
        $priceType = null,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
        ) {
            if (!isset($arguments['zone'])) {
                $arguments['zone'] = $renderZone;
            }
            $arguments['price_id'] = isset($arguments['price_id'])
            ? $arguments['price_id']
            : 'old-price-' . $product->getId() . '-' . $priceType;
            $arguments['include_container'] = isset($arguments['include_container'])
            ? $arguments['include_container']
            : true;
            $arguments['display_minimal_price'] = isset($arguments['display_minimal_price'])
            ? $arguments['display_minimal_price']
            : true;
            
            /** @var \Magento\Framework\Pricing\Render $priceRender */
            $priceRender = $this->getLayout()->getBlock('product.price.render.default');
            if (!$priceRender) {
                $priceRender = $this->getLayout()->createBlock(
                    \Magento\Framework\Pricing\Render::class,
                    'product.price.render.default',
                    ['data' => ['price_render_handle' => 'catalog_product_prices']]
                    );
            }
            
            $price = $priceRender->render(
                FinalPrice::PRICE_CODE,
                $product,
                $arguments
                );
            
            return $price;
    }
    
    /**
     * @inheritdoc
     */
    protected function getDetailsRendererList()
    {
        if (empty($this->rendererListBlock)) {
            /** @var $layout LayoutInterface */
            $layout = $this->layoutFactory->create(['cacheable' => false]);
            $layout->getUpdate()->addHandle('catalog_widget_product_list')->load();
            $layout->generateXml();
            $layout->generateElements();
            
            $this->rendererListBlock = $layout->getBlock('category.product.type.widget.details.renderers');
        }
        return $this->rendererListBlock;
    }
    
    /**
     * Get post parameters.
     *
     * @param Product $product
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlEncoder->encode($url),
            ]
        ];
    }
    
    public function getAddToCartUrl($product, $additional = [])
    {
        if (!$product->getTypeInstance()->isPossibleBuyFromList($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            if (!isset($additional['_query'])) {
                $additional['_query'] = [];
            }
            $additional['_query']['options'] = 'cart';
            
            return $this->getProductUrl($product, $additional);
        }
        return $this->_cartHelper->getAddUrl($product, $additional);
    }
    public function getFormattedCurrency($price)
    {
        return $this->_priceHelper->currency($price, true, false);
    }
    public function getFormKey()
    {
        return $this->_formKey->getFormKey();
    }
    
    /**
     * Retrieve add to wishlist params
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToWishlistParams($product)
    {
        return $this->_wishlistHelper->getAddParams($product);
    }
    
    /**
     * Retrieve Add Product to Compare Products List URL
     *
     * @return string
     */
    public function getAddToCompareUrl()
    {
        return $this->_compareProduct->getAddUrl();
    }
    
    /**
     * Get product reviews summary
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param bool $templateType
     * @param bool $displayIfNoReviews
     * @return string
     */
    public function getReviewsSummaryHtml(
        \Magento\Catalog\Model\Product $product,
        $templateType = false,
        $displayIfNoReviews = false
        ) {
            return $this->reviewRenderer->getReviewsSummaryHtml($product, $templateType, $displayIfNoReviews);
    }
}
