<?php
namespace OY\Customer\Block\Adminhtml\Birthdays\Grid\Renderer;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class ClientLocalAccess extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param Action\UrlBuilder $actionUrlBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        array $data = []
    ) {
        $this->timezone = $timezone;
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
        if ($row->getData('client_local_access')) {
            return 'Si';
        } else {
            return 'No';
        }
    }
}