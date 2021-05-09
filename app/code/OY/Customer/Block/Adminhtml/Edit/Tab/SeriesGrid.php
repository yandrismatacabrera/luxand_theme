<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28/12/20
 * Time: 09:15 AM
 */
namespace OY\Customer\Block\Adminhtml\Edit\Tab;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magestore\SupplierSuccess\Model\Locator\LocatorInterface;
use Magento\Customer\Controller\RegistryConstants;

class SeriesGrid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $_hiddenInputField = 'reserve';


    protected $_collectionFactory;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    protected $_context;

    protected $_coreRegistry = null;

    /**
     * @var null
     */
    protected  $newProductIds = null;

    protected $currentBuildUrl;

    protected $_planFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \OY\Routine\Model\ResourceModel\Series\CollectionFactory $collectionFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->jsonEncoder = $jsonEncoder;
        $this->currentBuildUrl = $context->getUrlBuilder();
        $this->_context = $context;
        $this->_coreRegistry = $coreRegistry;
        $this->customerRepository = $customerRepository;

        parent::__construct($context, $backendHelper, $data);

    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('series_listing');
        $this->setDefaultSort('id');
        $this->setUseAjax(true);

    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    protected function _addColumnFilterToCollection($column)
    {

        parent::_addColumnFilterToCollection($column);

        return $this;
    }

    protected function _prepareCollection()
    {
        $routineId = null;
        $customer = $this->customerRepository->getById($this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID));
        if ($customer->getCustomAttribute('routine_entity_id') && $customer->getCustomAttribute('routine_entity_id')->getValue()) {
            $routineId = $customer->getCustomAttribute('routine_entity_id')->getValue();
        }

        $collection = $this->_collectionFactory->create();

        $collection->addFieldToFilter('routine_id', ['eq' => $routineId]);

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return Groupon|Extended
     * @throws \Exception
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'order',
            [
                'header' => __('Orden'),
                'index' => 'order',
                'sortable' => false,
            ]
        );

        $this->addColumn(
            'number_of_series',
            [
                'header' => __('Cantidad de series'),
                'index' => 'number_of_series',
                'sortable' => false,
            ]
        );

        $this->addColumn(
            'break_time',
            [
                'header' => __('Tiempo de descanso entre serie'),
                'index' => 'break_time',
                'sortable' => false
            ]
        );

        $this->addColumn(
            'number_of_repetitions',
            [
                'header' => __('Cantidad de repeticiones por serie'),
                'index' => 'number_of_repetitions',
                'sortable' => false
            ]
        );

        $this->addColumn(
            'number_of_repetitions',
            [
                'header' => __('Cantidad de repeticiones por serie'),
                'index' => 'number_of_repetitions',
                'sortable' => false
            ]
        );

        $this->addColumn(
            'exercise_name',
            [
                'header' => __('Nombre del Ejercicio'),
                'index' => 'exercise_name',
                'sortable' => false,
                'renderer' => \OY\Customer\Block\Adminhtml\Series\Grid\Renderer\SeriesName::class
            ]
        );

        $this->addColumn(
            'exercise_image',
            [
                'header' => __('Imagen del Ejercicio'),
                'index' => 'exercise_image',
                'sortable' => false,
                'renderer' => \OY\Customer\Block\Adminhtml\Series\Grid\Renderer\SeriesImage::class,
                'component' => 'Magento_Ui/js/grid/columns/thumbnail'
            ]
        );

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl("*/index/gridseries", ["_current" => true]);

    }


    /**
     * @return array
     */
    protected function _getSelectedProducts()
    {
        if ($this->newProductIds)
            return $this->newProductIds;
        return null;
    }




    /**
     * get hidden input field name for selected products
     *
     * @return string
     */
    public function getHiddenInputField(){
        return $this->_hiddenInputField;
    }

    /**
     * @return array
     */
    public function getEditableFields()
    {
        $fields = [];
        return json_encode($fields);

    }
}

