<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24/12/20
 * Time: 02:07 PM
 */
namespace OY\Sales\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;

class OrderSaveAfter implements ObserverInterface
{
    public function __construct(
        \OY\Plan\Model\Repository\PlanRepository $planRepository,
        \OY\Plan\Model\PlanFactory $planFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->planRepository=$planRepository;
        $this->planFactory=$planFactory;
        $this->dateFilter = $dateFilter;
        $this->productRepository=$productRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $customerId = $order->getCustomerId();
        $orderStatus       = $order->getStatus();

        if ($customerId) { // Order Criada

            if ($orderStatus == Order::STATE_COMPLETE) {
                foreach ($order->getAllVisibleItems() as $orderItem) {
                    $options = $orderItem->getProductOptions();

                    if (isset($options['info_buyRequest']) && isset($options['info_buyRequest']['options'])) {
                        foreach ($options['info_buyRequest']['options'] as $opt) {
                            if (isset($opt['date'])) {
                                $product = $this->productRepository->getById($orderItem->getProductId());
                                $model = $this->planFactory->create();

                                if ($product->getData('code_interval')) {
                                    try {

                                        $strFrom =$this->dateFilter->filter($opt['date']);

                                        $strTo = new \DateTime($strFrom);
                                        $strTo->add(new \DateInterval($product->getData('code_interval')));

                                        $model->setData('customer_id', $customerId);
                                        $model->setData('plan', $product->getName());

                                        $model->setData('from', date("Y-m-d H:i:s", strtotime("+3 hours", strtotime($strFrom))));

                                        $model->setData('to', $strTo->format("Y-m-d H:i:s"));

                                        $model->setData('access_number', 0);
                                        $model->setData('access_enabled', 0);
                                        if ($product->getData('number_access')) {
                                            $model->setData('access_number', $product->getData('number_access'));
                                            $model->setData('access_enabled', $product->getData('number_access'));
                                        }

                                        $this->planRepository->save($model);
                                    } catch (LocalizedException $e) {
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
