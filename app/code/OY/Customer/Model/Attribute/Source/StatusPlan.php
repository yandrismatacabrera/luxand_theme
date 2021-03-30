<?php

namespace OY\Customer\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class StatusPlan extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    public function getAllOptions()
    {
        $result = [
            ['value' => 'activo', 'label' => 'Activo'],
            ['value' => 'inactivo', 'label' => 'Vencido'],
            ['value' => 'sin_plan', 'label' => 'Sin Plan']
        ];

        return $result;
    }
}
