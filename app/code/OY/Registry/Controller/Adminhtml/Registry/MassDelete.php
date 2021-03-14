<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13/03/21
 * Time: 07:43 PM
 */

namespace OY\Registry\Controller\Adminhtml\Registry;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use OY\Registry\Model\ResourceModel\Registry\CollectionFactory;
use OY\Registry\Api\RegistryRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level
     */
    //const ADMIN_RESOURCE = 'Magento_Catalog::categories';

    /**
     * @var \OY\Registry\Model\ResourceModel\Registry\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \OY\Registry\Api\RegistryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \OY\Registry\Model\ResourceModel\Registry\CollectionFactory $collectionFactory
     * @param \OY\Registry\Api\RegistryRepositoryInterface $registryRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        RegistryRepositoryInterface $registryRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->registryRepository = $registryRepository;
        parent::__construct($context);
    }

    /**
     * Category delete action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        if (!$this->getRequest()->isPost()) {
            throw new NotFoundException(__('Page not found'));
        }
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $registryDeleted = 0;
        foreach ($collection->getItems() as $registry) {
            $this->registryRepository->delete($registry);
            $registryDeleted++;
        }

        if ($registryDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $registryDeleted)
            );
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('registry/registry/index');
    }
}
