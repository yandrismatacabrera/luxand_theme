<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/04/21
 * Time: 09:48 PM
 */

namespace OY\Routine\Controller\Adminhtml\Routine;


class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \OY\Routine\Model\RoutineFactory
     */
    var $routineFactory;


    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \OY\Routine\Model\RoutineFactory $routineFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \OY\Routine\Model\RoutineFactory $routineFactory
    ) {
        parent::__construct($context);
        $this->routineFactory = $routineFactory;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('oy_routine/routine/addroutine');
            return;
        }
        try {
            $rowData = $this->routineFactory->create();
            $rowData->setData($data);
            if (isset($data['routine_id'])) {
                $rowData->setRoutineId($data['routine_id']);
            }
            $rowData->save();
            $this->messageManager->addSuccess(__('La Rutina fue guardada exitosamente.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('oy_routine/routine/index');
    }
}