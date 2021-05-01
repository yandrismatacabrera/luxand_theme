<?php

namespace OY\GymBooking\Block\Adminhtml\Edit;



class GenericButton
{
    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * Registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }


    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

    public function getRateId()
    {
        if($this->registry->registry('current_rate') && $this->registry->registry('current_rate')->getId())
            return $this->registry->registry('current_rate')->getId();
        else
            return null;
    }

    public function isActive()
    {
        if($this->registry->registry('current_rate') && $this->registry->registry('current_rate')->getActive())
            return true;
        else
            return false;
    }
}
