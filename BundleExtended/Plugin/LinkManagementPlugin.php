<?php
namespace MageReactor\BundleExtended\Plugin;

use MageReactor\BundleExtended\Model\LinkManagement;

class LinkManagementPlugin
{
    public function beforeMapProductLinks(
        LinkManagement $subject,
        \Magento\Bundle\Model\Selection $selectionModel,
        \Magento\Bundle\Api\Data\LinkInterface $productLink,
        $linkedProductId,
        $parentProductId
    ){
        if (is_array($productLink->getConfigOptions())) {
            $selectionModel->setConfigOptions(
                json_encode($productLink->getConfigOptions())
            );
        }
        return null;
    }
}