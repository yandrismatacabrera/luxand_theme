<?php
namespace OY\Customer\Ui\Component\Listing\Column;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class AccessNumber extends Column
{
    protected $_customerRepository;
    protected $_searchCriteria;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CustomerRepositoryInterface $customerRepository,
        SearchCriteriaBuilder $criteria,
        \OY\Plan\Model\ResourceModel\Plan\CollectionFactory $collectionPlanFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        array $components = [],
        array $data = []
    ) {
        $this->_customerRepository = $customerRepository;
        $this->_searchCriteria  = $criteria;
        $this->collectionPlanFactory = $collectionPlanFactory;
        $this->timezone=$timezone;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $customer  = $this->_customerRepository->getById($item["entity_id"]);

                $customer_id = $customer->getId();
                $collection = $this->collectionPlanFactory->create();
                $collection->addFieldToFilter('customer_id', $customer_id);

                $statusPlan = $this->getState($collection);
                $item[$this->getData('name')] = $statusPlan;
            }
        }
        return $dataSource;
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
