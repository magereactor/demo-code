<?php
namespace MageReactor\BundleExtended\Block\Adminhtml\Product\Composite;

use Magento\Catalog\Block\Adminhtml\Product\Composite\Configure as CompositeConfigure;
use Magento\Bundle\Model\Product\Type as BundleType;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class Configure extends CompositeConfigure
{
    /**
     * @return bool
     */
    public function isConfigurableProduct()
    {
        $product = $this->getProduct();
        if ($product->getTypeId() == BundleType::TYPE_CODE) {
            /** @var Bundle $bundleProduct */
            $bundleProduct = $product->getTypeInstance();
            $selections = $bundleProduct->getSelectionsCollection(
                $bundleProduct->getOptionsIds($product),
                $product
            );

            foreach ($selections as $selection) {
                if ($selection->getTypeId() == Configurable::TYPE_CODE) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->isConfigurableProduct()) {
            $this->removeAllBlocks();
            $this->setHasConfigurableProducts(true);
        }
        return $this;
    }

    /**
     * @return void
     */
    protected function removeAllBlocks()
    {
        foreach ($this->getLayout()->getAllBlocks() as $block) {
            /** @var \Magento\Framework\View\Element\AbstractBlock $block */
            if ($block->getNameInLayout() != $this->getNameInLayout()) {
                $block->setTemplate('');
            }
        }
    }
}