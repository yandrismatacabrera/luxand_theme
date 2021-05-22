<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/04/21
 * Time: 05:50 PM
 */

namespace OY\Routine\Block\Adminhtml\Exercise\Edit;


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
        array $data = []
    )
    {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('exercise_data');
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
        if ($model->getExerciseId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Editar Ejercicio'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('exercise_id', 'hidden', ['name' => 'exercise_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Adicionar Ejercicio'), 'class' => 'fieldset-wide']
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
            'image',
            'image',
            [
                'name' => 'image',
                'label' => __('Imagen'),
                'id' => 'image',
                'title' => __('Imagen'),
                'class' => 'required-entry',
                'required' => true,
                'note' => 'Tipos de imágenes permitidos: jpg, jpeg, png',
                'class' => 'required-entry required-file',
            ]
        );

        $fieldset->addField(
            'image_one',
            'image',
            [
                'name' => 'image_one',
                'label' => __('Imagen'),
                'id' => 'image_one',
                'title' => __('Imagen'),
                'class' => 'required-entry',
                'required' => true,
                'note' => 'Tipos de imágenes permitidos: jpg, jpeg, png',
                'class' => 'required-entry required-file',
            ]
        );

        $fieldset->addField(
            'image_two',
            'image',
            [
                'name' => 'image_two',
                'label' => __('Imagen'),
                'id' => 'image_two',
                'title' => __('Imagen'),
                'class' => 'required-entry',
                'required' => true,
                'note' => 'Tipos de imágenes permitidos: jpg, jpeg, png',
                'class' => 'required-entry required-file',
            ]
        );

        $fieldset->addField(
            'image_three',
            'image',
            [
                'name' => 'image_three',
                'label' => __('Imagen'),
                'id' => 'image_three',
                'title' => __('Imagen'),
                'class' => 'required-entry',
                'required' => true,
                'note' => 'Tipos de imágenes permitidos: jpg, jpeg, png',
                'class' => 'required-entry required-file',
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}