<?php

namespace MageReactor\BundleExtended\Observer\Adminhtml;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Bundle\Model\Product\Type;

class ProductEntitySaveAfter implements ObserverInterface
{
    const MULTI_TYPE = 'multi';

    /**
     * @param Observer $observer
     * @throws LocalizedException
     * @return void
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        if ($product->getTypeId() != Type::TYPE_CODE) {
            return;
        }

        $multyOptions = [];
        $bundleOptions = $product->getBundleOptionsData();
        if (is_array($bundleOptions)) {
            foreach ($bundleOptions as $bundleOption) {
                if (isset($bundleOption['type']) && $bundleOption['type'] == self::MULTI_TYPE
                    && !empty($bundleOption['option_id'])) {
                    $multyOptions[] = $bundleOption['option_id'];
                }
            }
        }

        $bundleSelections = $product->getBundleSelectionsData();
        if (is_array($bundleSelections)) {
            foreach ($bundleSelections as $optionSelections) {
                foreach ($optionSelections as $optionSelection) {

                    if (in_array($optionSelection['option_id'], $multyOptions, true)
                        && !empty($optionSelection['config_options-prepared-for-send'])
                    ) {
                        throw new LocalizedException(
                            __('Something went wrong')
                        );
                    }
                }
            }
        }
    }
}
