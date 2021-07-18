<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OY\Customer\Block\Adminhtml\Birthdays\Grid\Renderer;

class Plan extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var Action\UrlBuilder
     */
    protected $actionUrlBuilder;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param Action\UrlBuilder $actionUrlBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \OY\Plan\Model\ResourceModel\Plan\CollectionFactory $collectionPlanFactory,
        array $data = []
    ) {
        $this->timezone = $timezone;
        $this->collectionPlanFactory = $collectionPlanFactory;
        parent::__construct($context, $data);
    }

    /**
     * Render action
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $collection = $this->collectionPlanFactory->create();
        $collection->addFieldToFilter('customer_id', $row->getData('entity_id'));

        $statusPlan = $this->getState($collection);

        return $statusPlan;
    }

    private function getState($collection)
    {
        if ($collection->getSize()) {
            $data = [];
            $to = '';
            foreach ($collection as $plan) {
                if ($this->statusPlan($plan->getData('from'), $plan->getData('to'))) {

                    $strTo = new \DateTime($plan->getData('to'));
                    $strTo->add(new \DateInterval('P1D'));

                    $to = $this->timezone->formatDateTime(
                        $strTo->format("Y-M-d"),
                        \IntlDateFormatter::SHORT,
                        \IntlDateFormatter::NONE,
                        null,
                        null,
                        'dd LLL yyyy H:mm:ss'
                    );
                    return 'Activo hasta ' . $to;
                }
                $to = $this->timezone->formatDateTime(
                    $plan->getData('to'),
                    \IntlDateFormatter::SHORT,
                    \IntlDateFormatter::NONE,
                    null,
                    null,
                    'dd LLL yyyy H:mm:ss'
                );
                //$to = $plan->getData('to');
            }
            return 'Vencido desde ' . $to;
        } else {
            return 'Sin Plan';
        }
    }

    public function statusPlan($from, $to)
    {
        $today = date("Y-m-d H:i:s");
        if (strtotime($from) <= strtotime($today) && strtotime($to) >= strtotime($today)) {
            return true;
        }
        return false;
    }
}
