<?php


namespace Commercers\ProductAdvance\Ui\DataProvider\Product\Form\Modifier;


use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;

class Logo extends AbstractModifier
{
    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var array
     */
    protected $_meta;

    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    public function __construct(
        ArrayManager $arrayManager,
        UrlInterface $urlBuilder
    ) {
        $this->arrayManager   = $arrayManager;
        $this->_urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $meta = $this->getEanField($meta);

        return $meta;
    }

    protected function getEanField(array $meta)
    {
        $attributeCode = 'begadi_mpd_image_1';
        $elementPath = $this->arrayManager->findPath($attributeCode, $meta, null, 'children');
        $containerPath = $this->arrayManager->findPath(static::CONTAINER_PREFIX . $attributeCode, $meta, null, 'children');

        if (!$elementPath) {
            return $meta;
        }

        $meta = $this->arrayManager->merge(
            $containerPath,
            $meta,
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'component' => 'Magento_Ui/js/form/components/group',
                        ],
                    ],
                ],
                'children'  => [
                    $attributeCode => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'component' => 'Commercers_ProductAdvance/js/form/components/logo',
                                    'componentType' => Field::NAME,
                                    'formElement' => Input::NAME,
                                    'filterUrl'     => $this->_urlBuilder->getUrl(
                                        'product_advance/product/ajaxGetLogo',
                                        ['isAjax' => 'true']
                                    ),
                                    'config' => [
                                        'dataScope' => $attributeCode,
                                        'sortOrder' => 10,
                                    ],
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        );

        return $meta;
    }

}
