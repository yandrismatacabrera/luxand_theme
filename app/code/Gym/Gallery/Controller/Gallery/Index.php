<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18/06/21
 * Time: 11:58 PM
 */

namespace Gym\Gallery\Controller\Gallery;


class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set('Galería de Imágenes');

        return $resultPage;
    }
}