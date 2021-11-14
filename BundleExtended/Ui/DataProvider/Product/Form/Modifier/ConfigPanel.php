<?php
namespace MageReactor\BundleExtended\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;

class ConfigPanel extends AbstractModifier
{
    const CODE_BUNDLE_DATA = 'bundle-items';
    const CODE_BUNDLE_OPTIONS = 'bundle_options';

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    public function __construct(
        UrlInterface $url,
        ArrayManager $arrayManager
    ){
        $this->url = $url;
        $this->arrayManager = $arrayManager;
    }

    public function modifyData(array $data): array
    {
        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        $path = $this->arrayManager->findPath(static::CODE_BUNDLE_DATA, $meta, null, 'children');
        $meta = $this->arrayManager->merge(
            $path,
            $meta,
            [
                'children' => [
                    self::CODE_BUNDLE_OPTIONS => $this->getOptions()
                ]
            ]
        );
        return $meta;
    }

    protected function getOptions(): array
    {
        return [
            'children' => [
                'record' => [
                    'children' => [
                        'product_bundle_container' => [
                            'children' => [
                                'bundle_selections' => [
                                    'children' => [
                                        'record' => [
                                            'children' => [
                                                'config_options' => [
                                                    'arguments' => [
                                                        'data' => [
                                                            'config' => [
                                                                'component' => 'MageReactor_BundleExtended/js/components/config-options',
                                                                'formElement' => "multiselect",
                                                                'componentType' => "field",
                                                                'label' => __('Options'),
                                                                'dataScope' => 'config_options',
                                                                'sortOrder' => 200,
                                                                'visible' => true,
                                                                'url' => $this->url->getUrl(
                                                                    'bundleextended/config/options'
                                                                ),
                                                                'imports' => [
                                                                    'product_id' => '${ $.provider }:${ $.parentScope }.product_id',
                                                                    'selection_id' => '${ $.provider }:${ $.parentScope }.selection_id',
                                                                    '__disableTmpl' => ['productId' => false, 'selectionId' => false],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}