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
use OY\Registry\Api\RegistryRepositoryInterface;

class OrderSaveAfter implements ObserverInterface
{
    const AREA_CODE = \Magento\Framework\App\Area::AREA_ADMINHTML;

    public function __construct(
        \OY\Plan\Model\Repository\PlanRepository $planRepository,
        \OY\Plan\Model\PlanFactory $planFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \OY\Registry\Model\RegistryFactory $registryFactory,
        \OY\Registry\Api\RegistryRepositoryInterface $registryRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\App\State $state,
        \OY\Plan\Helper\Data $helper
    ) {
        $this->planRepository=$planRepository;
        $this->planFactory=$planFactory;
        $this->dateFilter = $dateFilter;
        $this->productRepository = $productRepository;
        $this->registryFactory = $registryFactory;
        $this->registryRepository = $registryRepository;
        $this->customerRepository = $customerRepository;
        $this->state = $state;
        $this->helper=$helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $customerId = $order->getCustomerId();
        $orderStatus       = $order->getStatus();

        if ($customerId) { // Order Created
            $customer = $this->customerRepository->getById($customerId);

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

                                        $this->deleteActivePlan($customerId);

                                        $this->planRepository->save($model);

                                        if ($this->state->getAreaCode() == self::AREA_CODE) {
                                            $registryRecord = $this->registryFactory->create();
                                            $registryRecord->setDateTime(date("Y-m-d H:i:s"));
                                            $registryRecord->setValid(1);
                                            $registryRecord->setCustomerId($customerId);
                                            $registryRecord->setFullname($customer->getFirstname() . ' ' . $customer->getLastname());
                                            $registryRecord->setMethod(RegistryRepositoryInterface::METHOD_AUTOMATIC);
                                            $this->registryRepository->save($registryRecord);
                                        }
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

    private function deleteActivePlan($customerId){
        $activePlan = $this->helper->getEnablePlanByCustomer($customerId);
        if($activePlan){
            $this->planRepository->deleteById($activePlan->getId());
        }
    }
}
