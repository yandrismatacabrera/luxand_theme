<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/04/21
 * Time: 10:55 PM
 */

namespace OY\Routine\Controller\Adminhtml\Exercise;


class DeleteExercise extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \OY\Routine\Model\ExerciseFactory
     */
    private $exerciseFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry,
     * @param \OY\Routine\Model\ExerciseFactory $exerciseFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \OY\Routine\Model\ExerciseFactory $exerciseFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->exerciseFactory = $exerciseFactory;
    }

    /**
     * Mapped Grid List page.
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->exerciseFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        if ($rowId) {
            $rowData = $rowData->load($rowId);
            $rowData->delete();
        }
        $this->_redirect('oy_routine/exercise/index');
        return;
    }
}