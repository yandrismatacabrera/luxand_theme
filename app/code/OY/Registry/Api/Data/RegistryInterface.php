<?php
namespace OY\Registry\Api\Data;

use Magento\Catalog\Model\Product\Filter\DateTime;

interface RegistryInterface
{
    const ID = 'entity_id';
    const CUSTOMER_ID = 'customer_id';
    const DATE_TIME = 'date_time';
    const FULLNAME = 'fullname';
    const METHOD = 'method';
    const VALID = 'valid';
    const MESSAGE = 'message';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @return DateTime
     */
    public function getDateTime();

    /**
     * @return string
     */
    public function getFullname();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return boolean
     */
    public function getValid();

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * @param DateTime $dateTime
     * @return $this
     */
    public function setDateTime($dateTime);

    /**
     * @param string $fullname
     * @return $this
     */
    public function setFullname($fullname);

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod($method);

    /**
     * @param boolean $valid
     * @return $this
     */
    public function setValid($valid);

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message);
}
