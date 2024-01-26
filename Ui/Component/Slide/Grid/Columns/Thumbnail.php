<?php

namespace Magelearn\Slider\Ui\Component\Slide\Grid\Columns;

use Magento\Backend\Model\UrlInterface;
use Magelearn\Slider\Model\Slide\FileInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Thumbnail extends Column
{
    public const URL_PATH_EDIT = 'slider/slide/edit';

    /**
     * @var FileInfo
     */
    private $fileInfo;
    
    /**
     * @var Repository
     */
    private $assetRepo;
    
    /**
     * @var UrlInterface
     */
    private $_backendUrl;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param FileInfo $fileInfo
     * @param Repository $assetRepo
     * @param UrlInterface $backendUrl
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        FileInfo $fileInfo,
        Repository $assetRepo,
        UrlInterface $backendUrl,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->fileInfo = $fileInfo;
        $this->assetRepo = $assetRepo;
        $this->_backendUrl = $backendUrl;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $url = '';
                if ($item[$fieldName] != '') {
                    $url = $this->fileInfo->getImageUrl($item[$fieldName]);
                } else {
                    $baseImage = $this->assetRepo->getUrl('Magelearn_Slider::images/slide.png');
                }

                $item[$fieldName . '_src'] = $url;
                $item[$fieldName . '_alt'] = $this->getAlt($item) ?: '';
                $item[$fieldName . '_link'] = $this->_backendUrl->getUrl(
                    self::URL_PATH_EDIT,
                    ['slide_id' => $item['slide_id'], 'store' => $this->context->getRequestParam('store')]
                );
                $item[$fieldName . '_orig_src'] = $url;
            }
        }

        return $dataSource;
    }

    /**
     * @param $row
     * @return null
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: 'title';

        return $row[$altField] ?? null;
    }
}
