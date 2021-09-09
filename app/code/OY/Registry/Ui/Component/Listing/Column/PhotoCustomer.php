<?php
namespace OY\Registry\Ui\Component\Listing\Column;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Store\Model\StoreManagerInterface;

class PhotoCustomer extends Column
{
    protected $_customerRepository;
    protected $_searchCriteria;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \OY\Registry\Api\RegistryRepositoryInterface $registryRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        SearchCriteriaBuilder $criteria,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->_registryRepository = $registryRepository;
        $this->customerRepository = $customerRepository;
        $this->_searchCriteria  = $criteria;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $url = '';
                $registry  = $this->_registryRepository->getById($item["entity_id"]);
                try {
                    $customer = $this->customerRepository->getById($registry->getCustomerId());
                    if ($customer->getCustomAttribute('photo') && $customer->getCustomAttribute('photo')->getValue()) {
                        $url = $this->_storeManager->getStore()->getBaseUrl(
                                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                            ) .$customer->getCustomAttribute('photo')->getValue();
                    }
                } catch (\Exception $exception) {}

                $item[$fieldName . '_src'] = $url;
                $item[$fieldName . '_orig_src'] = $url;
            }
        }
        return $dataSource;
    }
}