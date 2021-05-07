<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/04/21
 * Time: 05:50 PM
 */

namespace OY\Routine\Block\Adminhtml\Routine\Edit;


class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \OY\Routine\Model\Source\Complexity $options,
        \OY\Routine\Model\Source\Customer $customers,
        array $data = []
    )
    {
        $this->_options = $options;
        $this->_customers = $customers;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('routine_data');
        $form = $this->_formFactory->create(
            ['data' => [
                'id' => 'edit_form',
                'enctype' => 'multipart/form-data',
                'action' => $this->getData('action'),
                'method' => 'post'
            ]
            ]
        );

        $form->setHtmlIdPrefix('oyroutine_');
        if ($model->getRoutineId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Editar Rutina'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('routine_id', 'hidden', ['name' => 'routine_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Adicionar Rutina'), 'class' => 'fieldset-wide']
            );
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Nombre'),
                'id' => 'name',
                'title' => __('Nombre'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'customer_id',
            'select',
            [
                'name' => 'customer_id',
                'label' => __('Cliente'),
                'id' => 'customer_id',
                'title' => __('Cliente'),
                'values' => $this->_customers->getAllOptions(),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'duration',
            'text',
            [
                'name' => 'duration',
                'label' => __('Duración'),
                'id' => 'duration',
                'title' => __('Duración'),
                'class' => 'validate-number',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'complexity',
            'select',
            [
                'name' => 'complexity',
                'label' => __('Complejidad'),
                'id' => 'complexity',
                'title' => __('Complejidad'),
                'values' => $this->_options->getOptionArray(),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}