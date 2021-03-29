<?php
namespace OY\Customer\Plugin;

class CollectionFilter
{
    public function __construct(
        \OY\Plan\Model\ResourceModel\Plan\CollectionFactory $collectionPlanFactory
    ) {
        $this->collectionPlanFactory=$collectionPlanFactory;
    }

    public function beforeAddFieldToFilter(
        \Magento\Customer\Model\ResourceModel\Grid\Collection $subject,
        $field,
        $condition
    ) {
        if ($field == 'plan') {
            if(isset($condition['eq']) && $condition['eq']=='activo')
              $condition=['in'=>$this->getCustomersIn()];

            if(isset($condition['eq']) && $condition['eq']=='inactivo')
              $condition=['in'=>$this->getCustomersNin()];

            if(isset($condition['eq']) && $condition['eq']=='sin_plan')
                $condition=['nin'=>array_merge($this->getCustomersIn(), $this->getCustomersNin())];

            $field='entity_id';
        }

        return [$field, $condition];
    }

    private function getCustomersIn()
    {
        $customers = [];
        $collection = $this->collectionPlanFactory->create();

        foreach ($collection as $plan) {
            if ($this->statusPlan($plan->getData('from'), $plan->getData('to'))) {
                if(!in_array($plan->getData('customer_id'), $customers))
                $customers[]=$plan->getData('customer_id');
            }
        }

        return $customers;
    }

    private function getCustomersNin()
    {
        $customers = [];
        $collection = $this->collectionPlanFactory->create();
        $collection->addFieldToFilter('customer_id',['nin'=>$this->getCustomersIn()]);

        foreach ($collection as $plan) {
            if(!in_array($plan->getData('customer_id'), $customers))
                $customers[]=$plan->getData('customer_id');
        }

        return $customers;
    }

    private function statusPlan($from, $to)
    {
        $today = date("Y-m-d H:i:s");
        if (strtotime($from) <= strtotime($today) && strtotime($to) >= strtotime($today)) {
            return true;
        }
        return false;
    }
}
