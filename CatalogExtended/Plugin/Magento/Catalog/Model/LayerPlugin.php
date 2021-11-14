<?php
namespace MageReactor\CatalogExtended\Plugin\Magento\Catalog\Model;

use Magento\Catalog\Model\Layer;
use MageReactor\CatalogExtended\Helper\Data;

class LayerPlugin
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ){
        $this->helper = $helper;
    }

    /**
     * @param Layer $subject
     * @param \Closure $proceed
     * @return mixed
     */
    public function aroundGetProductCollection(
        Layer $subject,
        \Closure $proceed
    ){
        $result = $proceed();
        if(
            $this->helper->getGeneralConfig("enabled") &&
            $subject->getCurrentCategory()->getData(Data::CATEGORY_ATTRIBUTE) == 1 &&
            $attributeCode = $this->helper->getGeneralConfig("attribute_code")
        ) {
            $result->groupByAttribute($attributeCode);
        }
        return $result;
    }
}