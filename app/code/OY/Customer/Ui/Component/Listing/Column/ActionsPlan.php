<?php

namespace OY\Customer\Ui\Component\Listing\Column;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Actions
 */
class ActionsPlan extends Column
{
    protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        CollectionFactory $collectionFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder=$urlBuilder;
        $this->collectionFactory=$collectionFactory;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $collection = $this->collectionFactory->create();
            $collection->addAttributeToSelect('*');
            $collection->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
            $collection->addAttributeToFilter('is_plan', 1);
            if ($collection->getSize()) {
                foreach ($dataSource['data']['items'] as & $item) {
                    // here we can also use the data from $item to configure some parameters of an action URL
                    $item[$this->getData('name')] = $this->getOptions($collection, $item);
//                    [
//                        'plansemanal' => [
//                            'href' => $this->urlBuilder->getUrl(
//                                'customer/*/addplan',
//                                ['id' => $item['entity_id'], 'type' => 'Semanal']
//                            ),
//                            'label' => __('Semanal'),
//                            'confirm' => [
//                                'title' => __('Adicionar Plan Semanal'),
//                                'message' => __('Are you sure?')
//                            ]
//                        ],
//                        'planmensual' => [
//                            'href' => $this->urlBuilder->getUrl(
//                                'customer/*/addplan',
//                                ['id' => $item['entity_id'], 'type' => 'Mensual']
//                            ),
//                            'label' => __('Mensual'),
//                            'confirm' => [
//                                'title' => __('Adicionar Plan Mensual'),
//                                'message' => __('Are you sure?')
//                            ]
//                        ],
//                        'plantrimestral' => [
//                            'href' => $this->urlBuilder->getUrl(
//                                'customer/*/addplan',
//                                ['id' => $item['entity_id'], 'type' => 'Trimestral']
//                            ),
//                            'label' => __('Trimestral'),
//                            'confirm' => [
//                                'title' => __('Adicionar Plan Trimestral'),
//                                'message' => __('Are you sure?')
//                            ]
//                        ],
//                        'plansemestral' => [
//                            'href' => $this->urlBuilder->getUrl(
//                                'customer/*/addplan',
//                                ['id' => $item['entity_id'], 'type' => 'Semestral']
//                            ),
//                            'label' => __('Semestral'),
//                            'confirm' => [
//                                'title' => __('Adicionar Plan Semestral'),
//                                'message' => __('Are you sure?')
//                            ]
//                        ],
//                        'plananual' => [
//                            'href' => $this->urlBuilder->getUrl(
//                                'customer/*/addplan',
//                                ['id' => $item['entity_id'], 'type' => 'Anual']
//                            ),
//                            'label' => __('Anual'),
//                            'confirm' => [
//                                'title' => __('Adicionar Plan Anual'),
//                                'message' => __('Are you sure?')
//                            ]
//                        ],
//                    ];
                }
            }
        }

        return $dataSource;
    }

    private function getOptions($collection, $item)
    {
        $options = [];
        foreach ($collection as $product) {
            $options[$product->getSku()]=[
                'href' => $this->urlBuilder->getUrl(
                    'customer/*/addplan',
                    ['id' => $item['entity_id'], 'id_product' => $product->getId()]
                ),
                'label' => __($product->getName()),
                'confirm' => [
                    'title' => __('Adicionar: ' . $product->getName()),
                    'message' => __('Are you sure?')
                ]
            ];
        }
        return $options;
    }
}
