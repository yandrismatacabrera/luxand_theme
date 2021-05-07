<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/04/21
 * Time: 05:25 PM
 */

namespace OY\Routine\Controller\Adminhtml\Routine;

use Magento\Framework\Controller\ResultFactory;

class AddRoutine extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \OY\Routine\Model\RoutineFactory
     */
    private $routineFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry,
     * @param \OY\Routine\Model\RoutineFactory $routineFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \OY\Routine\Model\RoutineFactory $routineFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->routineFactory = $routineFactory;
    }

    /**
     * Mapped Grid List page.
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->routineFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        if ($rowId) {
            $rowData = $rowData->load($rowId);
            $rowTitle = $rowData->getTitle();
            if (!$rowData->getRoutineId()) {
                $this->messageManager->addError(__('row data no longer exist.'));
                $this->_redirect('oy_routine/routine/index');
                return;
            }
        }

        $this->coreRegistry->register('routine_data', $rowData);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $title = $rowId ? __(' Editar Rutina ').$rowTitle : __('Adicionar Rutina');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }
}