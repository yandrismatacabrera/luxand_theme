<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04/06/19
 * Time: 04:30 PM
 */
namespace OY\Routine\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface{

    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ){

        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0){

            try {
                $installer->getConnection()->addColumn(
                    $installer->getTable( 'exercise_entity' ),
                    'image_one',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'length' => '',
                        'comment' => 'Exercise Image 1'
                    ]
                );
                $installer->getConnection()->addColumn(
                    $installer->getTable( 'exercise_entity' ),
                    'image_two',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'length' => '',
                        'comment' => 'Exercise Image 2'
                    ]
                );
                $installer->getConnection()->addColumn(
                    $installer->getTable( 'exercise_entity' ),
                    'image_three',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'length' => '',
                        'comment' => 'Exercise Image 3'
                    ]
                );

                $installer->getConnection()->addColumn(
                    $installer->getTable( 'exercise_series_entity' ),
                    'day',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'length' => '255',
                        'comment' => 'Series Day'
                    ]
                );
            } catch (\Exception $e) {
                // Do nothing
            }

        }

        $installer->endSetup();
    }
}
