<?php

namespace Magelearn\Slider\Ui\Component\Slider\Grid\Columns;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Actions
 * @package Magelearn\Slider\Ui\Component\Slider\Grid\Columns
 */
class Actions extends Column
{
    public const SLIDER_ID       = 'slider_id';
    public const URL_PATH_EDIT   = 'slider/slider/edit';
    public const URL_PATH_DELETE = 'slider/slider/delete';
    public const URL_PATH_SLIDES = 'slider/slide';

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item[self::SLIDER_ID])) {
                    $item[$name]['edit']   = [
                        'href' => $this->context->getUrl(self::URL_PATH_EDIT,
                            [self::SLIDER_ID => $item[self::SLIDER_ID]]),
                        'label' => __('Edit'),
                        'hidden' => false,
                    ];
                    $item[$name]['slides'] = [
                        'href' => $this->context->getUrl(self::URL_PATH_SLIDES,
                            [self::SLIDER_ID => $item[self::SLIDER_ID]]),
                        'label' => __('Slides'),
                        'hidden' => false,
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->context->getUrl(self::URL_PATH_DELETE,
                            [self::SLIDER_ID => $item[self::SLIDER_ID]]),
                        'label' => __('Delete'),
                        'hidden' => false,
                    ];
                }
            }
        }

        return $dataSource;
    }
}
