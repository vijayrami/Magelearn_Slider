<?php

declare(strict_types=1);

namespace Magelearn\Slider\Ui\Component\Slide;

use Magelearn\Slider\Api\Data\SlideInterface;
use Magelearn\Slider\Model\ResourceModel\Slide\Collection;
use Magelearn\Slider\Model\ResourceModel\Slide\CollectionFactory;
use Magelearn\Slider\Model\Slide\FileInfo;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider
{
    public const DATA_PERSISTOR_KEY = 'magelearn_slider_slide';

    public const IMAGE_PARAMS = [
        SlideInterface::IMAGE_THUMBNAIL,
        SlideInterface::IMAGE_MEDIUM,
        SlideInterface::IMAGE_SMALL,
        SlideInterface::IMAGE_MOBILE
    ];

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var FileInfo
     */
    private $fileInfo;

    /**
     * @var array
     */
    private $loadedData;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param FileInfo $fileInfo
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        FileInfo $fileInfo,
        RequestInterface $request,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->fileInfo = $fileInfo;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $this->loadedData = [];
        $this->loadedData[0][SlideInterface::SLIDER_ID] = (int)$this->request->getParam('slider_id');

        $items = $this->collection->getItems();

        foreach ($items as $slide) {
            $data = $slide->getData();
            foreach (self::IMAGE_PARAMS as $imageParam) {
                $data = $this->addImage($data, $imageParam);
            }

            $this->loadedData[$slide->getId()] = $data;
            $this->loadedData[$slide->getId()]['daily_deal_product_id'] = explode(",", $slide->getData('daily_deal_product_id'));
        }

        $data = $this->dataPersistor->get(self::DATA_PERSISTOR_KEY);
        if (!empty($data)) {
            $slide = $this->collection->getNewEmptyItem();
            $slide->setData($data);
            $this->loadedData[$slide->getId()] = $slide->getData();
            $this->dataPersistor->clear(self::DATA_PERSISTOR_KEY);
        }

        return $this->loadedData;
    }

    /**
     * @param array $data
     * @param string $paramName
     * @return array
     */
    private function addImage(array $data, string $paramName): array
    {
        $fileName = $data[$paramName] ?? '';

        if (!$fileName) {
            return $data;
        }

        unset($data[$paramName]);

        if ($this->fileInfo->isExist($fileName)) {
            $stat = $this->fileInfo->getStat($fileName);
            $mime = $this->fileInfo->getMimeType($fileName);

            $data[$paramName][0]['name'] = basename($fileName);
            $data[$paramName][0]['url'] = $this->fileInfo->getImageUrl($fileName);
            $data[$paramName][0]['size'] = isset($stat) ? $stat['size'] : 0;
            $data[$paramName][0]['type'] = $mime;
        }

        return $data;
    }
}
