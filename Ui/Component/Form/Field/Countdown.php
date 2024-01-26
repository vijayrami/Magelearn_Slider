<?php

declare(strict_types = 1);

namespace Magelearn\Slider\Ui\Component\Form\Field;

use Magento\Framework\Option\ArrayInterface;

class Countdown implements ArrayInterface
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
        $type = [
            '1' => 'Yes',
            '0' => 'No',
        ];
        return $type;
    }
}
