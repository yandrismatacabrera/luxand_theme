<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OY\Customer\Block\Adminhtml\Dashboard;

/**
 *  Dashboard last search keywords block
 * @api
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 * @since 100.0.2
 */
class Birthdays extends \Magento\Backend\Block\Dashboard\Grid
{
    /**
     * @var \Magento\Search\Model\ResourceModel\Query\Collection
     */
    protected $_collection;

    /**
     * @var \Magento\Search\Model\ResourceModel\Query\CollectionFactory
     */
    protected $_queriesFactory;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $_moduleManager;

    /**
     * @var string
     */
    protected $_template = 'Magento_Backend::dashboard/grid.phtml';

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param \Magento\Search\Model\ResourceModel\Query\CollectionFactory $queriesFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $queriesFactory,
        array $data = []
    ) {
        $this->_moduleManager = $moduleManager;
        $this->_queriesFactory = $queriesFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('topSearchGrid');
        $this->setDefaultLimit(45);
    }

    /**
     * @inheritdoc
     */
    protected function _prepareCollection()
    {
        $date1 = date('m-d',strtotime("today"));
        $date2 = date('m-d',strtotime("-1 days"));
        $date3 = date('m-d',strtotime("-2 days"));
        $date4 = date('m-d',strtotime("-3 days"));
        $date5 = date('m-d',strtotime("+1 days"));
        $date6 = date('m-d',strtotime("+2 days"));
        $date7 = date('m-d',strtotime("+3 days"));
        $date8 = date('m-d',strtotime("+4 days"));
        $date9 = date('m-d',strtotime("+5 days"));

        $this->_collection = $this->_queriesFactory->create();
        $this->_collection->addAttributeToFilter('dob', array(
            array('like' => '%'.$date1.'%'),
            array('like' => '%'.$date2.'%'),
            array('like' => '%'.$date3.'%'),
            array('like' => '%'.$date4.'%'),
            array('like' => '%'.$date5.'%'),
            array('like' => '%'.$date6.'%'),
            array('like' => '%'.$date7.'%'),
            array('like' => '%'.$date8.'%'),
            array('like' => '%'.$date9.'%'),
        ));

        $this->_collection->addFieldToSelect('*');
        $this->_collection->addAttributeToSort('dob', 'ASC');

        return parent::_prepareCollection();
    }

    /**
     * @inheritdoc
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'firstname',
            ['header' => __('Nombres'), 'sortable' => false, 'index' => 'firstname']
        );

        $this->addColumn(
            'lastname',
            ['header' => __('Apellidos'), 'sortable' => false, 'index' => 'lastname']
        );

        $this->addColumn(
            'dob',
            [
                'header' => __('Fecha de Nacimiento'),
                'sortable' => false, 'index' => 'dob',
                'renderer' => \OY\Customer\Block\Adminhtml\Birthdays\Grid\Renderer\Date::class
            ]
        );

        $this->addColumn(
            'access_number',
            [
                'header' => __('Plan'),
                'sortable' => false, 'index' => 'access_number',
                'renderer' => \OY\Customer\Block\Adminhtml\Birthdays\Grid\Renderer\Plan::class
            ]
        );

        $this->addColumn(
            'email',
            ['header' => __('Correo Electrónico'), 'sortable' => false, 'index' => 'email']
        );

        $this->addColumn(
            'phone_number',
            ['header' => __('Teléfono'), 'sortable' => false, 'index' => 'phone_number']
        );

        $this->addColumn(
            'ci',
            ['header' => __('Cédula'), 'sortable' => false, 'index' => 'ci']
        );

        $this->addColumn(
            'client_local_access',
            [
                'header' => __('Acceso al Local'),
                'sortable' => false, 'index' => 'client_local_access',
                'renderer' => \OY\Customer\Block\Adminhtml\Birthdays\Grid\Renderer\ClientLocalAccess::class
            ]
        );

        $this->setFilterVisibility(false);
        $this->setPagerVisibility(true);

        return parent::_prepareColumns();
    }

    /**
     * @inheritdoc
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('customer/index/edit', ['id' => $row->getId()]);
    }
}
