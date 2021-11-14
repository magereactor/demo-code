<?php
declare(strict_types = 1);

namespace MageReactor\BundleExtended\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Bundle\Api\Data\OptionInterface;
use MageReactor\BundleExtended\Model\OptionsManagement;

class OptionsViewModel implements ArgumentInterface
{
    /**
     * @var OptionsManagement
     */
    protected $optionManagement;

    /**
     * @param OptionsManagement $optionsManagement
     */
    public function __construct(
        OptionsManagement $optionsManagement
    ){
        $this->optionManagement = $optionsManagement;
    }

    /**
     * @param OptionInterface $option
     * @return array|\Magento\Framework\Data\Collection\AbstractDb|\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|null
     */
    public function getConfigOptions(OptionInterface $option)
    {
        return $this->optionManagement->getSelectionsByOptionId((int)$option->getId());
    }
}