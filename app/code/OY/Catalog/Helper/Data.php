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
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \OY\GymBooking\Api\GymClassRepositoryInterface $gymClassRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->gymClassRepository=$gymClassRepository;
        parent::__construct($context);
    }

    public function getProductPlan($id) {
        $product = $this->productRepository->getById($id);
        return $product->getData('is_plan');
    }

    public function getCountClass($id){
        $product = $this->productRepository->getById($id);
        if($product->getData('classes_option')){
            $list = explode(',', $product->getData('classes_option'));
            return count($list);
        }
        return 0;
    }

    public function getListClassesId($id){
        $product = $this->productRepository->getById($id);
        if($product->getData('classes_option')){
            $list = explode(',', $product->getData('classes_option'));
            return $list;
        }
        return [];
    }

    public function getClass($id){
        return $this->gymClassRepository->getById($id);
    }
}
