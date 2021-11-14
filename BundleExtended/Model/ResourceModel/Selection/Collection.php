<?php
namespace MageReactor\BundleExtended\Model\ResourceModel\Selection;

use MageReactor\BundleExtended\Model\Product as ProductModel;
use Magento\CatalogInventory\Api\StockConfigurationInterface;

class Collection extends \Magento\Bundle\Model\ResourceModel\Selection\Collection
{

    /**
     * @var ProductModel
     */
    protected $extendedBundleType;

    /**
     * @var StockConfigurationInterface
     */
    private $stockConfiguration;

    /**
     * @return ProductModel
     */
    protected function getExtendedBundleType()
    {
        if ($this->extendedBundleType === null) {
            $this->extendedBundleType = $this->_entityFactory->create(ProductModel::class);
        }

        return $this->extendedBundleType;
    }

    public function addFilterByRequiredOptions()
    {
        $this->getSelect()->joinLeft(
            ['product_options' => $this->getTable('catalog_product_option')],
            'e.entity_id = product_options.product_id AND product_options.is_require = 1',
            []
        )->where("product_options.is_require IS NULL");

        return $this;
    }

    public function addQuantityFilter()
    {
        return $this;
    }
}