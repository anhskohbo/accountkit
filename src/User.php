<?php

namespace Anhskohbo\AccountKit;

class User
{
    /**
     * The user ID.
     *
     * @var string
     */
    protected $id;

    /**
     * The user data.
     *
     * @var string
     */
    protected $data = [];

    /**
     * Constructor.
     *
     * @param string $id
     * @param array  $data
     */
    public function __construct($id, $data)
    {
        $this->id = $id;
        $this->data = (array) $data;
    }

    /**
     * Gets the user ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the raw user data.
     *
     * @return array|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Gets the phone number.
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->data['phone']['number'];
    }

    /**
     * Gets the phone country prefix.
     *
     * @return string|null
     */
    public function getCountryPrefix()
    {
        return isset($this->data['phone']['country_prefix'])
            ? $this->data['phone']['country_prefix']
            : null;
    }

    /**
     * Gets the phone national number.
     *
     * @return string|null
     */
    public function getNationalNumber()
    {
        return isset($this->data['phone']['national_number'])
            ? $this->data['phone']['national_number']
            : null;
    }
}
