<?php

namespace Magelearn\Slider\Ui\Component\Slide\Grid\Columns;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Actions
 * @package Magelearn\Slider\Ui\Component\Slide\Grid\Columns
 */
class Actions extends Column
{
    public const SLIDE_ID      = 'slide_id';
    public const SLIDER_ID     = 'slider_id';
    public const URL_PATH_EDIT = 'slider/slide/edit';

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item[self::SLIDE_ID])) {
                    $item[$name]['edit'] = [
                        'href' => $this->context->getUrl(self::URL_PATH_EDIT,
                            [
                                self::SLIDE_ID => $item[self::SLIDE_ID],
                                self::SLIDER_ID => $item[self::SLIDER_ID]
                            ]),
                        'label' => __('Edit')
                    ];
                }
            }
        }

        return $dataSource;
    }
}
