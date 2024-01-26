<?php

declare(strict_types = 1);

namespace Magelearn\Slider\Ui\Component\Form\Field;

use Magento\Framework\Option\ArrayInterface;

class Video implements ArrayInterface
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
            '3' => 'HTML',
            '2' => 'Vimeo',
            '1' => 'YouTube',
            '0' => 'Disabled',

        ];
        return $type;
    }
}
