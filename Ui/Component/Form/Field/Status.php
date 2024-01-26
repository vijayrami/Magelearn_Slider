<?php

namespace Magelearn\Slider\Ui\Component\Form\Field;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 * @package Magelearn\Slider\Ui\Component\Form\Field
 */
class Status implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $arr     = $this->toArray();
        $options = [];
        foreach ($arr as $key => $value) {
            $options[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $options;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $status = [
            '1' => 'Enabled',
            '0' => 'Disabled',

        ];

        return $status;
    }
}
