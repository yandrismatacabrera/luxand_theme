<?php
namespace OY\Customer\Ui\Component\Listing\Column;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class ClientIsProfessor extends Column
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
                if ($customer->getCustomAttribute('client_is_professor') && $customer->getCustomAttribute('client_is_professor')->getValue()) {
                    $item[$this->getData('name')] = 'Si';
                } else {
                    $item[$this->getData('name')] = 'No';
                }
            }
        }
        return $dataSource;
    }
}