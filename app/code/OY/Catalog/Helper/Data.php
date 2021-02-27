<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21/02/21
 * Time: 04:00 PM
 */

namespace OY\Catalog\Helper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $productRepository;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    )
    {
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    public function getProductPlan($id) {
        $product = $this->productRepository->getById($id);
        if ($product->getData('planning_type')) {
            return $product->getData('planning_type');
        } else {
            return null;
        }
    }
}