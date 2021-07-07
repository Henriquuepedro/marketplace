<?php

namespace App\Services;

class ApiService
{
    /** @var string Holds the API Endpoint. */
    protected $endpoint;

    /** @var string The request method to be used. */
    protected $method;

    /** @var array Array of data to be send. */
    protected $payload;

    /** @var string Holds the Content Type of request. */
    protected $content_type;

    /** @var string Holds the Authorization Bearer header. */
    protected $bearer;

    /** @var string Holds the Raw result. */
    protected $raw_result;

    /** @var string Holds the last error. */
    protected $error;

    public static $debug = false;

    // Request Method Constants
    const METHOD_GET  = 'get';
    const METHOD_POST = 'post';

    // Content Type Constants
    const CT_FORM  = 'application/x-www-form-urlencoded';
    const CT_JSON  = 'application/json';

    // Result Format Constants
    const FORMAT_RAW  = 'raw';
    const FORMAT_JSON = 'json';

    public function __construct( string $endpoint = null, string $method = self::METHOD_GET )
    {
        // Holds the base api url
        $this->endpoint = $endpoint;
        $this->method = $method;
    }

    // PROPERTIES =================================================================================
    /**
     * Return the external API URL.
     * @return string
     */
    public function getUrl()
    {
        return $this->endpoint;
    }

    /**
     * Sets the external API URL
     * @param string $value
     */
    public function setUrl( string $value )
    {
        $this->endpoint = $value;
    }

    /**
     * Returns the request method.
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the request method.
     * @param string $value
     */
    public function setMethod( string $value )
    {
        $this->method = $value;
    }

    /**
     * Returns the data parameters.
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Sets the data parameter.
     * @param array $value
     */
    public function setPayload( array $value )
    {
        $this->payload = $value;
    }

    /**
     * Adds a key-value pair to array of data parameters.
     * @param string $name
     * @param mixed $value
     */
    public function addData( string $name, $value )
    {
        if( is_null($this->payload) )
            $this->payload = [];

        $this->payload[$name] = $value;
    }

    /**
     * Returns the Content Type property.
     * @return string
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * Sets the value of ContentType property.
     * @param string $value
     * @return void
     */
    public function setContentType( string $value )
    {
        $this->content_type = $value;
    }

    /**
     * Returns the Bearer property.
     * @return string
     */
    public function getBearer()
    {
        return $this->bearer;
    }

    /**
     * Sets the value of Bearer property.
     * @param string $value
     * @return void
     */
    public function setBearer( string $value )
    {
        $this->bearer = $value;
    }

    /**
     * Returns the result of API call.
     * @return string
     */
    public function getResult( string $format = self::FORMAT_JSON )
    {
        switch( $format )
        {
            case self::FORMAT_RAW:
                return $this->raw_result;
            case self::FORMAT_JSON:
                return json_decode( $this->raw_result );
        }
    }

    // PUBLIC METHODS =============================================================================
    /**
     * Make the Request.
     * @return void
     */
    public function call()
    {
        // Handle URL
        $url  = $this->endpoint;
        $data = null;

        // Handle parameters
        if( $this->payload && (count($this->payload) > 0) )
        {
            $data = \http_build_query( $this->payload );
        }

        // Init curl
        $curl = curl_init();

        // Set options
        curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 10 );
        curl_setopt( $curl, CURLOPT_TIMEOUT, 30 );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_ENCODING, "" );
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );

        if( self::$debug )
        {
            curl_setopt( $curl, CURLOPT_VERBOSE, true );
            curl_setopt( $curl, CURLINFO_HEADER_OUT, true );
            curl_setopt( $curl, CURLOPT_HEADER, true );
        }

        // Headers
        $headers = [];

        if( ! is_null($this->content_type) )
            $headers[] = 'Content-Type: ' . $this->content_type;

        if( ! is_null($this->bearer) )
            $headers[] = 'Authorization: Bearer ' . $this->bearer;

        // Add headers
        if( \count($headers) > 0 )
            curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );

        // Check request method
        switch( $this->method )
        {
            case self::METHOD_GET:
                // Add parameters to URL as query string
                $url = $url . '?' . $data;
                break;
            case self::METHOD_POST:
                // Add parameters to request body
                curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "POST" );
                curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
                break;
        }

        // Set URL
        curl_setopt( $curl, CURLOPT_URL, $url );

        // Get result
        $this->raw_result = curl_exec( $curl );

        if( curl_errno($curl) )
        {
            $debug = 'cUrl error: ' . curl_error( $curl );
        }

        if( self::$debug )
        {
            $info = curl_getinfo( $curl );

            dump( $info );
            dump( $this->raw_result );
        }

        curl_close( $curl );

        return $this;
    }

    // PUBLIC STATIC METHODS ======================================================================
    /**
     * Make a Post Request to API
     *
     * @param string $endpoint
     * @param array $payload
     * @param string $token
     * @param string $format
     * @return void
     */
    public static function post( string $endpoint, array $payload, string $token = null, string $format = self::FORMAT_JSON )
    {
        $api = new ApiService( $endpoint, self::METHOD_POST );

        $api->setPayload( $payload );
        $api->setContentType( self::CT_FORM );

        if( ! is_null($token) )
            $api->setBearer( $token );

        $api->call();

        return $api->getResult( $format );
    }

    /**
     * Make a Get request
     *
     * @param string $endpoint
     * @param array $payload
     * @param string $token
     * @param string $format
     * @return void
     */
    public static function get( string $endpoint, array $payload = null, string $token = null, string $format = self::FORMAT_JSON )
    {
        $api = new ApiService( $endpoint, self::METHOD_GET );

        if( ! is_null($payload) )
            $api->setPayload( $payload );

        if( ! is_null($token) )
            $api->setBearer( $token );

        $api->call();

        return $api->getResult( $format );
    }
}