<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/04/21
 * Time: 05:25 PM
 */

namespace OY\Routine\Controller\Adminhtml\Series;

use Magento\Framework\Controller\ResultFactory;

class AddSeries extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \OY\Routine\Model\SeriesFactory
     */
    private $seriesFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry,
     * @param \OY\Routine\Model\SeriesFactory $seriesFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \OY\Routine\Model\SeriesFactory $seriesFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->seriesFactory = $seriesFactory;
    }

    /**
     * Mapped Grid List page.
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->seriesFactory->create();
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        if ($rowId) {
            $rowData = $rowData->load($rowId);
            $rowTitle = $rowData->getTitle();
            if (!$rowData->getSeriesId()) {
                $this->messageManager->addError(__('row data no longer exist.'));
                $this->_redirect('oy_routine/series/index');
                return;
            }
        }

        $this->coreRegistry->register('series_data', $rowData);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $title = $rowId ? __(' Editar Serie de Ejercicios').$rowTitle : __('Adicionar Serie de Ejercicios');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }
}