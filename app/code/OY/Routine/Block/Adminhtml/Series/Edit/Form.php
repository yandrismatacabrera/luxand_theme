<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/04/21
 * Time: 05:50 PM
 */

namespace OY\Routine\Block\Adminhtml\Series\Edit;


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
        \OY\Routine\Model\Source\Routine $routines,
        \OY\Routine\Model\Source\Exercise $exercises,
        array $data = []
    )
    {
        $this->_routines = $routines;
        $this->_exercises = $exercises;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('series_data');
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
                ['legend' => __('Editar Serie de Ejercicios'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('series_id', 'hidden', ['name' => 'series_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Adicionar Serie de Ejercicios'), 'class' => 'fieldset-wide']
            );
        }

        $fieldset->addField(
            'routine_id',
            'select',
            [
                'name' => 'routine_id',
                'label' => __('Rutina'),
                'id' => 'routine_id',
                'title' => __('Rutina'),
                'values' => $this->_routines->getAllOptions(),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'exercise_id',
            'select',
            [
                'name' => 'exercise_id',
                'label' => __('Ejercicio'),
                'id' => 'exercise_id',
                'title' => __('Ejercicio'),
                'values' => $this->_exercises->getAllOptions(),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'order',
            'text',
            [
                'name' => 'order',
                'label' => __('Orden'),
                'id' => 'order',
                'title' => __('Orden'),
                'class' => 'validate-number',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'number_of_series',
            'text',
            [
                'name' => 'number_of_series',
                'label' => __('Cantidad de series'),
                'id' => 'number_of_series',
                'title' => __('Cantidad de series'),
                'class' => 'validate-number',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'break_time',
            'text',
            [
                'name' => 'break_time',
                'label' => __('Tiempo de descanso entre series'),
                'id' => 'break_time',
                'title' => __('Tiempo de descanso entre series'),
                'class' => 'validate-number',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'number_of_repetitions',
            'text',
            [
                'name' => 'number_of_repetitions',
                'label' => __('Cantidad de repeticiones por serie'),
                'id' => 'number_of_repetitions',
                'title' => __('Cantidad de repeticiones por serie'),
                'class' => 'validate-number',
                'required' => true,
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}