<?php
namespace MageReactor\BundleExtended\Model;

class LinkManagement extends \Magento\Bundle\Model\LinkManagement
{
    /**
     * @param \Magento\Bundle\Model\Selection $selectionModel
     * @param \Magento\Bundle\Api\Data\LinkInterface $productLink
     * @param string $linkedProductId
     * @param string $parentProductId
     * @return \Magento\Bundle\Model\Selection
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function mapProductLinkToSelectionModel(
        \Magento\Bundle\Model\Selection $selectionModel,
        \Magento\Bundle\Api\Data\LinkInterface $productLink,
        $linkedProductId,
        $parentProductId
    ) {
        $selectionModel = parent::mapProductLinkToSelectionModel(
            $selectionModel,
            $productLink,
            $linkedProductId,
            $parentProductId
        );

        $selectionModel = $this->mapProductLinks(
            $selectionModel,
            $productLink,
            $linkedProductId,
            $parentProductId
        );

        return $selectionModel;
    }

    /**
     * @param \Magento\Bundle\Model\Selection $selectionModel
     * @param \Magento\Bundle\Api\Data\LinkInterface $productLink
     * @param $linkedProductId
     * @param $parentProductId
     */
    public function mapProductLinks(
        \Magento\Bundle\Model\Selection $selectionModel,
        \Magento\Bundle\Api\Data\LinkInterface $productLink,
        $linkedProductId,
        $parentProductId
    ){
        return $selectionModel;
    }
}