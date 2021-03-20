<?php
namespace OY\Registry\Model;

use OY\Registry\Api\Data\RegistryInterface;


class Registry extends \Magento\Framework\Model\AbstractModel implements RegistryInterface
{
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        parent::_construct(); // TODO: Change the autogenerated stub
        $this->_init('OY\Registry\Model\ResourceModel\Registry');
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    public function getDateTime()
    {
        return $this->getData(self::DATE_TIME);
    }

    public function getFullname()
    {
        return $this->getData(self::FULLNAME);
    }

    public function getMethod()
    {
        return $this->getData(self::METHOD);
    }

    public function getValid()
    {
        return $this->getData(self::VALID);
    }

    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    public function getPhoto()
    {
        return $this->getData(self::PHOTO);
    }


    public function setId($id)
    {
        return $this->setData(self::ID, $id);

    }

    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);

    }

    public function setDateTime($dateTime)
    {
        return $this->setData(self::DATE_TIME, $dateTime);

    }

    public function setFullname($fullname)
    {
        return $this->setData(self::FULLNAME, $fullname);
    }

    public function setMethod($method)
    {
        return $this->setData(self::METHOD, $method);
    }

    public function setValid($valid)
    {
        return $this->setData(self::VALID, $valid);
    }

    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    public function setPhoto($photo)
    {
        return $this->setData(self::PHOTO, $photo);
    }
}
