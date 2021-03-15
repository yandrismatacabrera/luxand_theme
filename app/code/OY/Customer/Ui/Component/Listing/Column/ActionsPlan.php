<?php

namespace OY\Customer\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

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
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder=$urlBuilder;
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
            foreach ($dataSource['data']['items'] as & $item) {
                // here we can also use the data from $item to configure some parameters of an action URL
                $item[$this->getData('name')] = [
                    'plansemanal' => [
                        'href' => $this->urlBuilder->getUrl(
                            'customer/*/addplan',
                            ['id' => $item['entity_id'], 'type' => 'Semanal']
                        ),
                        'label' => __('Semanal'),
                        'confirm' => [
                            'title' => __('Adicionar Plan Semanal'),
                            'message' => __('Are you sure?')
                        ]
                    ],
                    'planmensual' => [
                        'href' => $this->urlBuilder->getUrl(
                            'customer/*/addplan',
                            ['id' => $item['entity_id'], 'type' => 'Mensual']
                        ),
                        'label' => __('Mensual'),
                        'confirm' => [
                            'title' => __('Adicionar Plan Mensual'),
                            'message' => __('Are you sure?')
                        ]
                    ],
                    'plantrimestral' => [
                        'href' => $this->urlBuilder->getUrl(
                            'customer/*/addplan',
                            ['id' => $item['entity_id'], 'type' => 'Trimestral']
                        ),
                        'label' => __('Trimestral'),
                        'confirm' => [
                            'title' => __('Adicionar Plan Trimestral'),
                            'message' => __('Are you sure?')
                        ]
                    ],
                    'plansemestral' => [
                        'href' => $this->urlBuilder->getUrl(
                            'customer/*/addplan',
                            ['type' => 'Semestral']
                        ),
                        'label' => __('Semestral'),
                        'confirm' => [
                            'title' => __('Adicionar Plan Semestral'),
                            'message' => __('Are you sure?')
                        ]
                    ],
                    'plananual' => [
                        'href' => $this->urlBuilder->getUrl(
                            'customer/*/addplan',
                            ['id' => $item['entity_id'], 'type' => 'Anual']
                        ),
                        'label' => __('Anual'),
                        'confirm' => [
                            'title' => __('Adicionar Plan Anual'),
                            'message' => __('Are you sure?')
                        ]
                    ],
                ];
            }
        }

        return $dataSource;
    }
}
