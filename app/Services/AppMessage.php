<?php
namespace App\Services;

/**
 * The main Message class of Application
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 1.0
 * @copyright (c) 2020
 */
class AppMessage
{
    /** @var bool Indicates if this is a success (true) or error (false) message. */
    protected $success;

    /** @var string Holds the message to be sent. */
    protected $message;

    /** @var array Array with additional properties to be returned. */
    protected $properties;

    /** @var string Holds the current active language. */
    protected $language;

    /**
     * The private constructor: this is a singleton object
     */
    public function __construct( bool $success, string $message = null, $data = null )
    {
        $this->success  = $success;
        $this->message  = $message;
        $this->language = app()->getLocale();

        if( ! is_null($data) )
            $this->properties = $data;
    }

    /**
     * Gets the value of success property.
     * @return bool
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Sets the value of success property
     * @param bool $value
     * @return $this
     */
    public function setSuccess( $value )
    {
        $this->success = $value;

        return $this;
    }

    /**
     * Gets the value of message property.
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the value of message property.
     * @param string $value
     * @return $this
     */
    public function setMessage( $value )
    {
        $this->message = $value;

        return $this;
    }

    /**
     * Adds a property to AppMessage
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function addProperty( $name, $value )
    {
        if( is_null($this->properties) )
            $this->properties = [];

        $this->properties[$name] = $value;
    }

    /**
     * Alias to addProperty method
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function addProp( $name, $value )
    {
        return $this->addProperty( $name, $value );
    }

    /**
     * Indicates if this is a error message.
     * @return bool
     */
    public function isError()
    {
        return ! $this->success;
    }

    /**
     * Indicates if this is a success message
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * Returns the AppMessage as Array.
     * @return array
     */
    public function getArray()
    {
        $res = [
            'success'  => $this->success,
            'message'  => $this->message,
            'language' => $this->language,
        ];

        if( is_null($this->properties) )
            return $res;

        foreach( $this->properties as $prop => $value )
        {
            $res[$prop] = $value;
        }

        return $res;
    }
}
