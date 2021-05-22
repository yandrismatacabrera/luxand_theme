<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/04/21
 * Time: 09:48 PM
 */

namespace OY\Routine\Controller\Adminhtml\Series;


class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \OY\Routine\Model\SeriesFactory
     */
    var $seriesFactory;


    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \OY\Routine\Model\SeriesFactory $seriesFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \OY\Routine\Model\SeriesFactory $seriesFactory
    ) {
        parent::__construct($context);
        $this->seriesFactory = $seriesFactory;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('oy_routine/series/addseries');
            return;
        }
        try {
            if (count($data['day'])) {
                $data['day'] = implode(',', array_values($data['day']));
            }else {
                $data['day'] = '';
            }


            $rowData = $this->seriesFactory->create();
            $rowData->setData($data);
            if (isset($data['series_id'])) {
                $rowData->setSeriesId($data['series_id']);
            }
            $rowData->save();
            $this->messageManager->addSuccess(__('La Serie de Ejercicios fue guardada exitosamente.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('oy_routine/series/index');
    }
}