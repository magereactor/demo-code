<?php
namespace MageReactor\CatalogExtended\Model;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config;

class ModifyProductCollectionBeforeToHtml
{
    const ENTITY_TYPE = "product";

    const SUPER_ATTRIBUTE = "super_attribute";

    const PRECONFIGURED_CONFIGS = "preconfigured_values";

    const ATTRIBUTE_ID = "attribute_id";

    private $imageTypes = ["image", "small_image", "thumbnail"];

    /**
     * @var Configurable
     */
    private $configurable;

    /**
     * @var UrlRewriteCollection
     */
    private $urlRewriteCollection;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $attributeCode = null;

    /**
     * @param Configurable $configurable
     * @param UrlRewriteCollection $urlRewriteCollection
     * @param Config $config
     */
    public function __construct(
        Configurable $configurable,
        UrlRewriteCollection $urlRewriteCollection,
        Config $config
    ){
        $this->configurable = $configurable;
        $this->urlRewriteCollection = $urlRewriteCollection;
        $this->config = $config;
    }

    /**
     * @param Collection $collection
     * @param null $attributeCode
     * @return $this|void
     */
    public function getModifiedCollection(Collection $collection, $attributeCode = null)
    {
        if($attributeCode) {
            $this->attributeCode = $attributeCode;
            /**
             * @var \Magento\Catalog\Model\Product $item
             */
            foreach($collection->getItems() as $item) {
                $color = $this->getDefaultColor($item);
                if(isset(
                    $color[self::PRECONFIGURED_CONFIGS]
                )) {
                    $perconfiguredValues = $color[self::PRECONFIGURED_CONFIGS];
                    $item->setPreconfiguredValues($perconfiguredValues);
                    $parentIds = $this->configurable->getParentIdsByChild($item->getId());
                    if(isset($parentIds)){
                        $item->setParentId($parentIds[0]);
                        if($requestPath = $this->getRequestPath($item)){
                            $item->setRequestPath(
                                sprintf('%s#%d=%d', $requestPath, $color[self::ATTRIBUTE_ID], $item->getColor())
                            );
                        }
                    }
                }
                $this->setImage($item);
            }
        }
        return $this;
    }

    /**
     * @param ProductInterface $item
     * @return null|string
     */
    private function getRequestPath(ProductInterface $item)
    {
        $this->urlRewriteCollection
            ->addStoreFilter($item->getStoreId())
            ->addFieldToFilter("entity_id", $item->getParentId())
            ->addFieldToFilter("metadata", array("null" => true))
            ->addFieldToFilter("entity_type", self::ENTITY_TYPE);

        return (
            $this->urlRewriteCollection->getSize()
        ) ? $this->urlRewriteCollection->getFirstItem()->getRequestPath(): null;
    }

    /**
     * @param ProductInterface $product
     * @return array
     */
    private function getDefaultColor(ProductInterface $product)
    {
        try {
            if($this->attributeCode) {
                $attribute = $this->config->getAttribute(Product::ENTITY, $this->attributeCode);
                $preconfiguredValues = new \Magento\Framework\DataObject();
                $preconfiguredValues->setData(self::SUPER_ATTRIBUTE, [$attribute->getId() => $product->getColor()]);
                return [
                    self::PRECONFIGURED_CONFIGS => $preconfiguredValues,
                    self::ATTRIBUTE_ID => $attribute->getId()

                ];
            }
        } catch (LocalizedException $e) {
            return [];
        }
        return [];
    }

    /**
     * @param ProductInterface $product
     */
    private function setImage(ProductInterface &$product)
    {
        foreach ($this->imageTypes as $imageType) {
            if ($product->getData($imageType) && $product->getData($imageType) !== 'no_selection') {
                $product->setData($imageType, $product->getData($imageType));
            } else {
                $product->setData($imageType, $product->getData('image'));
            }
        }
    }
}