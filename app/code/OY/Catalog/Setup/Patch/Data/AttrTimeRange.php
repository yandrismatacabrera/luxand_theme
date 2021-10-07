<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07/05/20
 * Time: 02:03 PM
 */

namespace OY\Catalog\Setup\Patch\Data;

use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AttrTimeRange implements DataPatchInterface
{
    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * AddressAttribute constructor.
     *
     * @param Config              $eavConfig
     * @param EavSetupFactory     $eavSetupFactory
     */
    public function __construct(
        Config $eavConfig,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavConfig = $eavConfig;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create();

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'hour_from',
            [
                'group' => 'general',
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Horario restringido desde',
                'input' => 'select',
                'source' => \OY\Catalog\Model\Entity\Attribute\Source\Hours::class,
                'class' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'group' => 'general',
                'sort_order' => 4,
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'hour_to',
            [
                'group' => 'general',
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Horario restringido hasta',
                'input' => 'select',
                'source' => \OY\Catalog\Model\Entity\Attribute\Source\Hours::class,
                'class' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'sort_order' => 4,
            ]
        );
    }



    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
