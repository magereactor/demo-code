<?php
namespace MageReactor\CatalogExtended\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Registry;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Data extends AbstractHelper
{
    const CATEGORY_ATTRIBUTE = "split_configurable";
    /**
     * MageReactor_CatalogExtended General Config
     */
    const XML_PATH_MAGEREACTOR_CATALOGEXTENDED_GENERAL = "catalog_extended/general/";

    /**
     * @var $currentCategory
     */
    private $currentCategory;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CategoryRepositoryInterface $categoryRepository
    ){
        parent::__construct($context);
        $this->registry = $registry;
        $this->categoryRepository = $categoryRepository;
        $this->currentCategory = null;
    }

    /**
     * @param null|string $field
     * @param null|int $storeId
     * @return mixed
     */
    public function getGeneralConfig($field = null, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MAGEREACTOR_CATALOGEXTENDED_GENERAL.$field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isAllowed()
    {
        $category = $this->registry->registry('current_category');
        if(
            $category &&
            $category->getId() &&
            $this->currentCategory === null
        ) {
            try {
                $this->currentCategory = $this->categoryRepository->get($category->getId());
            } catch (NoSuchEntityException $exception) {
                return false;
            }
        }

        return (
            $this->currentCategory !== null &&
            (int)$this->currentCategory->getData(self::CATEGORY_ATTRIBUTE) === 1
        ) ? true: false;
    }
}