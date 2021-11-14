<?php
namespace MageReactor\BundleExtended\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Exception\LocalizedException;

class ProductSaveBefore implements ObserverInterface
{

    const MULTI_TYPE = 'multi';

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * BeforeProductSaveObserver constructor.
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * Cancel order item
     *
     * @param   EventObserver $observer
     * @return  void
     */
    public function execute(EventObserver $observer)
    {
        /** @var Product $product */
        $product = $observer->getEvent()->getDataObject();
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

        $multySelections = [];
        $bundleSelections = $product->getBundleSelectionsData();
        if (is_array($bundleSelections)) {
            foreach ($bundleSelections as $optionSelections) {
                $selectionCandidates = [];
                $currentOptionId = null;
                foreach ($optionSelections as $optionSelection) {
                    if (!empty($optionSelection['option_id'])) {
                        $currentOptionId = $optionSelection['option_id'];
                    }
                    $selectionCandidates[] = $optionSelection['product_id'];
                }
                if (in_array($currentOptionId, $multyOptions, true)) {
                    $multySelections = array_merge($multySelections, $selectionCandidates);
                }
            }
        }
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->getSelect()->where('entity_id in (?)', $multySelections)
            ->where('type_id = ?', \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE);

        if ($productCollection->count()) {
            throw new LocalizedException(__('Configurable products cannot be used as part of multiselect option'));
        }
    }
}
