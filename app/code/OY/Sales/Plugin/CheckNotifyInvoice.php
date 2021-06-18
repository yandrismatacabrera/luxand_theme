<?php

namespace OY\Sales\Plugin;

class CheckNotifyInvoice
{
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    ) {
        $this->customerRepository=$customerRepository;
        $this->orderRepository=$orderRepository;
    }

    public function aroundSend(
        \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $subject,
        callable $proceed,
        $invoice
    ) {
        $order = $this->orderRepository->get($invoice->getOrderId());
        if ($order->getCustomerId()) {
            $customer = $this->customerRepository->getById($order->getCustomerId());

            if ($customer &&
                $customer->getCustomAttribute('client_send_invoice') &&
                !$customer->getCustomAttribute('client_send_invoice')->getValue()) {
                return true;
            }
        }

        return $proceed($invoice);
    }
}
