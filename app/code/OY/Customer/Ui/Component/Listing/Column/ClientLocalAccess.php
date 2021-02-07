<?php
namespace OY\Customer\Ui\Component\Listing\Column;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class ClientLocalAccess extends Column
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
                if ($customer->getCustomAttribute('client_local_access') && $customer->getCustomAttribute('client_local_access')->getValue()) {
                    $item[$this->getData('name')] = 'Si';
                } else {
                    $item[$this->getData('name')] = 'No';
                }
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