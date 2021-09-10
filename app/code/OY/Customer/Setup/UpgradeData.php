<?php
namespace OY\Customer\Setup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Setup\CustomerSetup;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        \OY\Registry\Helper\Luxand $luxand,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $collectionFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->luxand=$luxand;
        $this->collectionFactory=$collectionFactory;
        $this->customerRepository=$customerRepository;
        $this->storeManager=$storeManager;
    }

    public function upgrade( ModuleDataSetupInterface $setup, ModuleContextInterface $context )
    {

        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            try {

                $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

                $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
                $attributeSetId = $customerEntity->getDefaultAttributeSetId();

                /** @var $attributeSet AttributeSet */
                $attributeSet = $this->attributeSetFactory->create();
                $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

                $customerSetup->addAttribute(Customer::ENTITY, 'luxand_registry', [
                    'type' => 'int',
                    'label' => 'Registrado en Luxand',
                    'required' => false,
                    'visible' => true,
                    'user_defined' => false,
                    'input' => 'boolean',
                    'sort_order' => 26,
                    'position' => 26,
                    'system' => 0,
                    'backend' => \Magento\Customer\Model\Attribute\Backend\Data\Boolean::class,
                    'adminhtml_only' => true,
                    'default' => 0,
                ]);

                $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'luxand_registry')
                    ->addData([
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer'],//you can use other forms also ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address']
                    ]);
                $attribute->save();


            } catch (\Exception $e) {
                // Do nothing
            }

        }

        if (version_compare($context->getVersion(), '1.0.2') < 0) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'luxand_id', [
                'type' => 'text',
                'label' => 'Luxand ID',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'luxand_id')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer', 'customer_account_create', 'customer_account_edit'],
                ]);
            $attribute->save();

        }

        if (version_compare($context->getVersion(), '1.0.3') < 0) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'photo', [
                'type' => 'text',
                'label' => 'Foto',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'photo')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();

        }
        if (version_compare($context->getVersion(), '1.0.4') < 0) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'ci', [
                'type' => 'text',
                'label' => 'CI',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'ci')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();

        }
        if (version_compare($context->getVersion(), '1.0.5') < 0) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'phone_number', [
                'type' => 'text',
                'label' => 'Telefono',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'phone_number')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();

        }
        if (version_compare($context->getVersion(), '1.0.6') < 0) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'mutual', [
                'type' => 'text',
                'label' => 'Mutualista',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'mutual')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();

        }

        if (version_compare($context->getVersion(), '1.0.7') < 0) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'photo', [
                'type' => 'text',
                'label' => 'Foto',
                'required' => true,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'photo')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();

        }


        if (version_compare($context->getVersion(), '1.0.8') < 0) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'photo', [
                'type' => 'text',
                'label' => 'Foto',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'photo')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();

        }


        if (version_compare($context->getVersion(), '1.0.9') < 0) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'phone_number', [
                'type' => 'text',
                'label' => 'Telefono',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'phone_number')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();

            $customerSetup->addAttribute(Customer::ENTITY, 'ci', [
                'type' => 'text',
                'label' => 'CI',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'ci')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();
        }

        if (version_compare($context->getVersion(), '1.0.10') < 0) {

            try {

                $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

                $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
                $attributeSetId = $customerEntity->getDefaultAttributeSetId();

                /** @var $attributeSet AttributeSet */
                $attributeSet = $this->attributeSetFactory->create();
                $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

                $customerSetup->addAttribute(Customer::ENTITY, 'client_local_access', [
                    'type' => 'int',
                    'label' => 'Acceso al Local',
                    'required' => false,
                    'visible' => true,
                    'user_defined' => false,
                    'input' => 'boolean',
                    'sort_order' => 28,
                    'position' => 28,
                    'system' => 0,
                    'backend' => \Magento\Customer\Model\Attribute\Backend\Data\Boolean::class,
                    'adminhtml_only' => true,
                    'default' => 0,
                ]);

                $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'client_local_access')
                    ->addData([
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer'],
                    ]);
                $attribute->save();
            } catch (\Exception $e) {
                // Do nothing
            }
        }

        if (version_compare($context->getVersion(), '1.0.11') < 0) {

            try {

                $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

                $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
                $attributeSetId = $customerEntity->getDefaultAttributeSetId();

                /** @var $attributeSet AttributeSet */
                $attributeSet = $this->attributeSetFactory->create();
                $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

                $customerSetup->addAttribute(Customer::ENTITY, 'client_send_invoice', [
                    'type' => 'int',
                    'label' => 'Enviar Factura',
                    'required' => false,
                    'visible' => true,
                    'user_defined' => false,
                    'input' => 'boolean',
                    'sort_order' => 28,
                    'position' => 28,
                    'system' => 0,
                    'backend' => \Magento\Customer\Model\Attribute\Backend\Data\Boolean::class,
                    'adminhtml_only' => true,
                    'default' => 0,
                ]);

                $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'client_send_invoice')
                    ->addData([
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer'],
                    ]);
                $attribute->save();
            } catch (\Exception $e) {
                // Do nothing
            }
        }


        if (version_compare($context->getVersion(), '1.0.12') < 0) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'address_street_1', [
                'type' => 'text',
                'label' => 'Dirección',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 1050,
                'system' => 0,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'address_street_1')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();

            $customerSetup->addAttribute(Customer::ENTITY, 'address_street_2', [
                'type' => 'text',
                'label' => 'Número',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 1100,
                'system' => 0,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'address_street_2')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();

            $customerSetup->addAttribute(Customer::ENTITY, 'address_city', [
                'type' => 'text',
                'label' => 'Ciudad',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 1150,
                'system' => 0,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'address_city')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();

            $customerSetup->addAttribute(Customer::ENTITY, 'address_postal_code', [
                'type' => 'text',
                'label' => 'Código postal',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 1200,
                'system' => 0,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'address_postal_code')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();


            $customerSetup->addAttribute(Customer::ENTITY, 'client_is_professor', [
                'type' => 'int',
                'label' => 'Profesor',
                'required' => false,
                'visible' => true,
                'user_defined' => false,
                'input' => 'boolean',
                'sort_order' => 28,
                'position' => 28,
                'system' => 0,
                'backend' => \Magento\Customer\Model\Attribute\Backend\Data\Boolean::class,
                'adminhtml_only' => true,
                'default' => 0,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'client_is_professor')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $attribute->save();
        }

        if (version_compare($context->getVersion(), '1.0.13') < 0) {
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerSetup->removeAttribute(
                Customer::ENTITY,
                'client_is_professor'
            );

            $customerSetup->removeAttribute(
                Customer::ENTITY,
                'address_street_2'
            );

            $customerSetup->removeAttribute(
                Customer::ENTITY,
                'address_postal_code'
            );
        }

        if (version_compare($context->getVersion(), '1.0.14') < 0) {

            try {

                $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

                $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
                $attributeSetId = $customerEntity->getDefaultAttributeSetId();

                /** @var $attributeSet AttributeSet */
                $attributeSet = $this->attributeSetFactory->create();
                $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

                $customerSetup->addAttribute(Customer::ENTITY, 'client_local_access', [
                    'type' => 'int',
                    'label' => 'Acceso al Local',
                    'required' => false,
                    'visible' => true,
                    'user_defined' => false,
                    'input' => 'boolean',
                    'sort_order' => 28,
                    'position' => 28,
                    'system' => 0,
                    'backend' => \Magento\Customer\Model\Attribute\Backend\Data\Boolean::class,
                    'adminhtml_only' => true,
                    'default' => 0,
                ]);

                $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'client_local_access')
                    ->addData([
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer'],
                    ]);
                $attribute->save();
            } catch (\Exception $e) {
                // Do nothing
            }
        }

        if (version_compare($context->getVersion(), '1.0.15') < 0) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'luxand_photo_id', [
                'type' => 'text',
                'label' => 'Luxand Photo ID',
                'required' => false,
                'visible' => false,
                'user_defined' => true,
                'position' => 30,
                'system' => 0,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'luxand_photo_id')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => [],
                ]);
            $attribute->save();


            $customerSetup->addAttribute(Customer::ENTITY, 'previous_photo', [
                'type' => 'text',
                'label' => 'Foto anterior',
                'required' => false,
                'visible' => false,
                'user_defined' => false,
                'position' => 999,
                'system' => 0,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'previous_photo')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => [],
                ]);
            $attribute->save();

        }

        if (version_compare($context->getVersion(), '1.0.16') < 0) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'luxand_photo_id', [
                'type' => 'text',
                'label' => 'Luxand Photo ID',
                'required' => false,
                'visible' => false,
                'user_defined' => false,
                'position' => 30,
                'system' => 0,
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'luxand_photo_id')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => [],
                ]);
            $attribute->save();
        }


        if (version_compare($context->getVersion(), '1.0.17') < 0) {

            try {

                $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

                $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
                $attributeSetId = $customerEntity->getDefaultAttributeSetId();

                /** @var $attributeSet AttributeSet */
                $attributeSet = $this->attributeSetFactory->create();
                $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

                $customerSetup->addAttribute(Customer::ENTITY, 'prof_entity_id', [
                    'type' => 'int',
                    'label' => 'Profesor',
                    'input' => 'select',
                    'source' => \OY\Customer\Model\Entity\Attribute\Source\Professor::class,
                    'required' => false,
                    'visible' => true,
                    'user_defined' => false,
                    'sort_order' => 40,
                    'position' => 40,
                    'system' => 0,
                    'adminhtml_only' => true,
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => true,
                    'is_filterable_in_grid' => true
                ]);

                $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'prof_entity_id')
                    ->addData([
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer'],
                    ]);
                $attribute->save();
            } catch (\Exception $e) {
                // Do nothing
            }
        }


        if (version_compare($context->getVersion(), '1.0.18') < 0) {

            try {

                $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

                $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
                $attributeSetId = $customerEntity->getDefaultAttributeSetId();

                /** @var $attributeSet AttributeSet */
                $attributeSet = $this->attributeSetFactory->create();
                $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

                $customerSetup->addAttribute(Customer::ENTITY, 'routine_entity_id', [
                    'type' => 'int',
                    'label' => 'Rutina',
                    'input' => 'select',
                    'source' => \OY\Routine\Model\Source\Routine::class,
                    'required' => false,
                    'visible' => true,
                    'user_defined' => false,
                    'sort_order' => 50,
                    'position' => 50,
                    'system' => 0,
                    'adminhtml_only' => true,
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => true,
                    'is_filterable_in_grid' => true
                ]);

                $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'routine_entity_id')
                    ->addData([
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer'],
                    ]);
                $attribute->save();
            } catch (\Exception $e) {
                // Do nothing
            }
        }
        if (version_compare($context->getVersion(), '1.0.20') < 0){
            try{

                $collection = $this->collectionFactory->create();
                $collection->addFieldToFilter('entity_id',419);

                foreach ($collection as $item){

                    $customer = $this->customerRepository->getById($item->getId());
                    if($customer->getCustomAttribute('luxand_id') &&
                       $customer->getCustomAttribute('luxand_id')->getValue() &&
                       $customer->getCustomAttribute('photo') &&
                       $customer->getCustomAttribute('photo')->getValue()){

                       $nameCustomer = [];
                       $nameCustomer['id']=$customer->getId();
                       $nameCustomer['email']=$customer->getEmail();
                       $nameCustomer['name']=$customer->getFirstname() . ' ' . $customer->getLastname();
                       $nameCustomer['ci']=$customer->getCustomAttribute('ci')->getValue();

                       $name=(string)json_encode($nameCustomer);

                       $urlImg = $this->storeManager->getStore()->getBaseUrl(
                                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                            ) . $customer->getCustomAttribute('photo')->getValue();

                       $registry = $this->luxand->createCustomer($name, 0, $urlImg);

                        if ($registry) {
                            $faceId = $this->luxand->getCustomerFace($registry);
                            $customer->setCustomAttribute('luxand_registry', 1);
                            $customer->setCustomAttribute('luxand_id', $registry);
                            $customer->setCustomAttribute('luxand_photo_id', $faceId);
                            $this->customerRepository->save($customer);
                        }

                    }
                }

            }catch (\Exception $e) {
                // Do nothing
            }
        }
    }
}
