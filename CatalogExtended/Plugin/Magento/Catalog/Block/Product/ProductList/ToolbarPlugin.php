<?php
namespace MageReactor\CatalogExtended\Plugin\Magento\Catalog\Block\Product\ProductList;

use Magento\Catalog\Block\Product\ProductList\Toolbar;
use MageReactor\CatalogExtended\Helper\Data;

class ToolbarPlugin
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
     * @param Toolbar $subject
     * @param $result
     * @return mixed
     */
    public function afterGetTotalNum(
        Toolbar $subject,
        $result
    ){
        if(
            $this->helper->getGeneralConfig("enabled") &&
            $this->helper->isAllowed()
        ) {
            $result = count($subject->getCollection());
        }
        return $result;
    }
}