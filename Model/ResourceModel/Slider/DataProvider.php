<?php

namespace Magelearn\Slider\Model\ResourceModel\Slider;

use Magelearn\Slider\Api\Data\SliderInterface;
use Magelearn\Slider\Model\ResourceModel\Slider\CollectionFactory;
use Magento\{Store\Model\StoreManagerInterface, Ui\DataProvider\AbstractDataProvider};
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class DataProvider
 * @package Magelearn\Slider\Model\ResourceModel\Slider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        StoreManagerInterface $storeManager,
        CollectionFactory $collectionFactory,
        DataObjectProcessor $dataObjectProcessor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection          = $collectionFactory->create();
        $this->storeManager        = $storeManager;
        $this->dataObjectProcessor = $dataObjectProcessor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items            = $this->collection->getItems();
        $this->loadedData = array();

        foreach ($items as $item) {
            $itemData                               = $this->dataObjectProcessor->buildOutputDataArray($item,
                SliderInterface::class);
            $this->loadedData[$item->getSliderId()] = $itemData;
        }

        return $this->loadedData;
    }
}
