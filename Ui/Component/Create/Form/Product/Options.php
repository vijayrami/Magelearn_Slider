<?php
namespace Magelearn\Slider\Ui\Component\Create\Form\Product;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\RequestInterface;

/**
 * Class Options
 *
 * @package Magelearn\CodeSample\Ui\Component\Create\Form\Product
 */
class Options implements OptionSourceInterface
{

    /**
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $productsArray;

    /**
     * @param ProductCollectionFactory $productCollectionFactory
     * @param RequestInterface $request
     */
    public function __construct(

        ProductCollectionFactory $productCollectionFactory,
        RequestInterface $request
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->request = $request;
    }

    /**
     * @return array|null
     */
    public function toOptionArray()
    {
        return $this->getProductArray();
    }

    /**
     * @return array|null
     */
    protected function getProductArray() {
        if ($this->productsArray === null) {
            $productCollection = $this->productCollectionFactory->create();
			$productCollection->addAttributeToSelect('*');
			$productCollection->setPageSize(10);
			/* setPageSize if you are facing problem when loading admin grid page */

            foreach($productCollection as $product) {
            	$productId = $product->getEntityId();
                if (!isset($productById[$productId])) {
                    $productById[$productId] = [
                        'value' => $productId
                    ];
                }
                $productById[$productId]['label'] = $product->getSku();
				
            }

			$this->productsArray = $productById;
        }
			return $this->productsArray;
    }
}