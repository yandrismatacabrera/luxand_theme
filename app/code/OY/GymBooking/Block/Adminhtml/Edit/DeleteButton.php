<?php

namespace OY\GymBooking\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;


class DeleteButton extends GenericButton implements ButtonProviderInterface
{

    public function getButtonData()
    {
        $data = [
            'label' => __('Delete'),
            'class' => 'delete',
            'id' => 'rate-delete-button',
            'data_attribute' => [
                'url' => $this->getDeleteUrl()
            ],
            'on_click' => '',
            'sort_order' => 20,
        ];

        if($this->getRateId())
            return $data;
        else
            return [];
    }

    public function getDeleteUrl()
    {
        $url= $this->getUrl('*/*/delete', ['id' => $this->getRateId()]);
        return $url;
    }

}
