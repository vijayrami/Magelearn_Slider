<?php
namespace Magelearn\Slider\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
 
class Lazyload implements OptionSourceInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'ondemand', 'label' => 'On-Demand'],
            ['value' => 'progressive', 'label' => 'Progressive']
        ];
    }
}