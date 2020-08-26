<?php
namespace Commercers\CustomMenus\Ui\DataProvider;
 
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\LayoutFactory;
 
class ProductForm extends AbstractModifier
{
    /**
     * @var LocatorInterface
     */
    protected $locator;
 
    /**
     * @var RequestInterface
     */
    protected $request;
 
    /**
     * @var LayoutFactory
     */
    private $layoutFactory;
 
    public function __construct(
        LocatorInterface $locator,
        RequestInterface $request,
        LayoutFactory $layoutFactory
    ) {
        $this->locator = $locator;
        $this->request = $request;
        $this->layoutFactory = $layoutFactory;
    }
 
    public function modifyMeta(array $meta)
    {
        if ($this->getProductType() == "simple") {
//            $meta["custom_options"] = [
//                "arguments" => [
//                    "data" => [
//                        "config" => [
//                            "componentType" => "fieldset",
//                            "collapsible" => false,
//                        //    "sortOrder" => 1,
//                            "opened" => false,
//                            "canShow" => false,
//                            "visible" => false
//                        ]
//                    ]
//                ]
//            ];
          //  $meta["schedule-design-update"]["arguments"]["data"]["config"]["visible"] = false;
//             $meta["gift-options"] = [
//                 "children" =>[],
//                 "arguments" => [
//                     "data" => [
//                         "config" => [
//                             "componentType" => "fieldset",
//                             "collapsible" => false,
//                             "sortOrder" => 1,
//                             "opened" => 0,
//                             "canShow" => 0,
//                             "visible" => 0
//                         ]
//                     ]
//                 ]
//                 ,
//                 'children' => []
//             ];
//            $meta["downloadable"] = [
//                "arguments" => [
//                    "data" => [
//                        "config" => [
//                            "componentType" => "fieldset",
//                            "label" => "ThanhDat12312",
//                            "collapsible" => 1,
//                            "sortOrder" => 1,
//                          //  "opened" => 0,
//                          //  "canShow" => 0,
//                            "visible" => 1
//                        ]
//                    ]
//                ]
//            ];
//             $meta["related"] = [
//                "arguments" => [
//                    "data" => [
//                        "config" => [
//                            "componentType" => "fieldset",
//                           "collapsible" => true,
//                           "sortOrder" => 1,
//                           "opened" => false,
//                           "canShow" => false,
//                            "visible" => false
//                        ]
//                    ]
//                ]
//            ];
        //    print_r($meta["custom_options"]["arguments"]["data"]["config"]); exit;
        }
       // echo "<pre>"; print_r($meta);exit;
       // print_r($meta) ;exit;
        return $meta;
    }
 
    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }
 
    /**
     * Get product type
     *
     * @return null|string
     */
    private function getProductType()
    {
        return (string)$this->request->getParam('type', $this->locator->getProduct()->getTypeId());
    }
}