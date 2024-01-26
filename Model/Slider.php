<?php

namespace Magelearn\Slider\Model;

use Magelearn\Slider\Api\Data\SliderInterface;
use Magelearn\Slider\Api\Data\SliderInterfaceFactory;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;
use Magelearn\Slider\Model\ResourceModel\Slider as SliderResource;

/**
 * Class Slide
 * @package Magelearn\Slider\Model
 */
class Slider extends AbstractExtensibleModel
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var SliderInterfaceFactory
     */
    private $sliderFactory;

    /**
     * Slide constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param SlideInterfaceFactory $slideFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        SliderInterfaceFactory $sliderFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $resource, $resourceCollection, $data);

        $this->sliderFactory       = $sliderFactory;
        $this->dataObjectHelper    = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;

        $this->_init(SliderResource::class); // yes, this works fine

        // if ANY of You have problem with line below please refer to deprecated _getResource,
        // cause either You use deprecated methods or set Resource here, thank You.
        $this->_resource === null && $this->_resource = ObjectManager::getInstance()->get($this->_resourceName);
    }

    /**
     * @return mixed
     */
    public function getDataModel()
    {
        $slideAnemicModel = $this->sliderFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $slideAnemicModel,
            $this->getData(),
            SliderInterface::class
        );

        $this->cleanup();

        return $slideAnemicModel;
    }

    /**
     * @param int $modelId
     * @param null $field
     * @return SliderInterface
     */
    public final function load($modelId, $field = null)
    {
        $this->cleanup();

        $this->_resource->load($this, $modelId, $field);

        return $this->getDataModel();
    }

    /**
     * @param SliderInterface $slide
     * @return SliderInterface
     * @throws \Exception
     */
    private function saveAnemicModel(SliderInterface $slider = null)
    {
        $this->cleanup();
        $this->_data           = $this->dataObjectProcessor->buildOutputDataArray($slider, SliderInterface::class);
        $this->_hasDataChanges = true;
        $this->_resource->save($this);
        $slider->setSliderId($this->getId());
        $this->cleanup();
        return $slider;
    }

    /**
     * Safety wrapper
     *
     * @param SliderInterface|null $slide
     * @return SliderInterface|AbstractModel
     * @throws \Exception
     */
    public final function save(SliderInterface $slider = null)
    {
        if (!$slider instanceof SliderInterface) {
            throw new \RuntimeException('[HAL 9000] I’m sorry Dave, I’m afraid I can’t do that');
        }

        return $this->saveAnemicModel($slider);
    }

    private function cleanup()
    {
        // break reference the HARD way
        unset($this->_data);
        $this->_data = [];
    }

    /**
     * @param SliderInterface|null $slide
     * @return void
     * @throws \Exception
     */
    public final function delete(SliderInterface $slider = null)
    {
        if (!$slider instanceof SliderInterface) {
            throw new \RuntimeException('[HAL 9000] I’m sorry Dave, I’m afraid I can’t do that');
        }

        $this->setId($slider->getSliderId())->isDeleted(false);
        $this->_resource->delete($this);
    }
}
