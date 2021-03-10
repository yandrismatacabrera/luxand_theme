<?php

namespace Gym\JsAuth\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Framework\Exception\NoSuchEntityException;

class Auth extends Template
{

    /**
     * Auth constructor.
     * @param \OY\Registry\Helper\Luxand $luxand
     * @param Template\Context $context
     * @param array $data
     * 
     */
    public function __construct(
        \OY\Registry\Helper\Luxand $luxand,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->luxandHelper = $luxand;
    }

    public function getAdminBaseUrl()
    {
        // this is used to load models in face api library
        try {
            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        } catch (NoSuchEntityException $e) {
            return '';
        }
    }
    public function getApiUrl()
    {
        // this is used to make a call to recognition api
        return $this->luxandHelper->getUrlRest() . 'photo/search';
    }
    public function getApiToken()
    {
        // this is used to make a call to recognition api
        return $this->luxandHelper->getToken();
    }

    public function getTimeToMakeRegister()
    {
        // this is used to make a call to recognition api
        return 3;
    }

    public function getUrlRegistry()
    {
        return $this->getUrl('registry/ajax/registry', []);
    }
}
