<?php
namespace OY\Customer\Ui\Component\Listing\Column;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
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
        array $components = [],
        array $data = []
    ) {
        $this->_customerRepository = $customerRepository;
        $this->_searchCriteria  = $criteria;
        $this->collectionPlanFactory = $collectionPlanFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $customer  = $this->_customerRepository->getById($item["entity_id"]);

                $customer_id = $customer->getId();
                $collection = $this->collectionPlanFactory->create();
                $collection->addFieldToFilter('customer_id', $customer_id);

                $plans = 0;
                if(count($collection->getData())) {
                    $plans = $collection->getData();
                    $data = [];
                    foreach ($plans as $plan) {
                        $status = $this->statusPlan($plan['from'], $plan['to']);
                        $planData = '';
                        if ($status == 'Activo') {
                            $planData .= '<p>Estado: <span style="color: green;">'.$status.'</span></p>';
                        } else {
                            $planData .= '<p>Estado: <span style="color: red;">'.$status.'</span></p>';
                        }
                        if ($plan['access_number']) {
                            $planData .= '<p>No. de accesos: '.$plan['access_number'].'</p>';
                        }
                        if ($plan['access_enabled']) {
                            $planData .= '<p>No. de Accesos Habilitados: '.$plan['access_enabled'].'</p>';
                        }
                        $data[] = $planData;
                    }
                    $item[$this->getData('name')] = implode('<hr style="color: ghostwhite">', $data);
                } else {
                    $item[$this->getData('name')] = '-';
                }

                /*$collection = $this->_pannumberfactory->create()->getCollection();
                $collection->addFieldToFilter('customer_id', $customer_id);

                $data = $collection->getFirstItem();*/


            }
        }
        return $dataSource;
    }

    public function statusPlan($from, $to) {
        $today =date("Y-m-d H:i:s");
        if(strtotime($from) <= strtotime($today) && strtotime($to) >= strtotime($today))
            return 'Activo';

        return 'Inactivo';
    }
}