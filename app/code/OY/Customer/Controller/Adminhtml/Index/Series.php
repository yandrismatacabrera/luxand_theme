<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06/06/19
 * Time: 04:58 PM
 */
namespace OY\Customer\Controller\Adminhtml\Index;

class Series extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Customer Series grid
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $this->initCurrentCustomer();
        $resultLayout = $this->resultLayoutFactory->create();
        return $resultLayout;
    }
}
