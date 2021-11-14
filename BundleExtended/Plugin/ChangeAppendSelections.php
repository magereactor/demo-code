<?php
namespace MageReactor\BundleExtended\Plugin;

use Magento\Bundle\Model\ResourceModel\Option\Collection;

class ChangeAppendSelections
{
    /**
     * @param Collection $subject
     * @param $selectionsCollection
     * @param false $stripBefore
     * @param bool $appendAll
     */
    public function beforeAppendSelections(
        Collection $subject,
        $selectionsCollection,
        $stripBefore = false,
        $appendAll = true
    ){
        foreach ($selectionsCollection->getItems() as $selection) {
            $selection->setRequiredOptions(0);
        }
        return [$selectionsCollection, $stripBefore, $appendAll];
    }
}