<?php
/**
 * MageVision Blog50
 *
 * @category     MageVision
 * @package      MageVision_Blog50
 * @author       MageVision Team
 * @copyright    Copyright (c) 2019 MageVision (https://www.magevision.com)
 * @license      http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace OY\User\Block\Adminhtml\User\Edit\Tab;

use Magento\Backend\Block\Widget\Form;

class Main extends \Magento\User\Block\User\Edit\Tab\Main
{
    /**
     * Prepare form fields
     *
     * @return Form
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        $form = $this->getForm();
        $model = $this->_coreRegistry->registry('permissions_user');

        $baseFieldset = $form->getElement('base_fieldset');

        $baseFieldset->addField(
            'telephone',
            'text',
            [
                'name' => 'telephone',
                'label' => __('Teléfono'),
                'title' => __('Teléfono'),
                'value' => $model->getTelephone()
            ]
        );

        $baseFieldset->addField(
            'description',
            'text',
            [
                'name' => 'description',
                'label' => __('Descripción'),
                'title' => __('Descripción'),
                'value' => $model->getDescription()
            ]
        );

        $baseFieldset->addField(
            'facebook',
            'text',
            [
                'name' => 'facebook',
                'label' => __('Perfil de Facebook'),
                'title' => __('Perfil de Facebook'),
                'value' => $model->getFacebook()
            ]
        );

        $baseFieldset->addField(
            'instagram',
            'text',
            [
                'name' => 'instagram',
                'label' => __('Perfil de Instagram'),
                'title' => __('Perfil de Instagram'),
                'value' => $model->getInstagram()
            ]
        );

        return $this;
    }
}