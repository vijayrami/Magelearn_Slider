<?php

declare(strict_types=1);

namespace Magelearn\Slider\Ui\Component\Slide\Control;

use Magento\Ui\Component\Control\Action;

class MassDeleteAction extends Action
{
    /**
     * @inheritDoc
     */
    public function prepare(): void
    {
        $config = $this->getConfiguration();
        $context = $this->getContext();
        $config['url'] = $context->getUrl(
            'slider/slide/massDelete',
            ['slider_id' => $context->getRequestParam('slider_id')]
        );

        $this->setData('config', $config);

        parent::prepare();
    }
}
