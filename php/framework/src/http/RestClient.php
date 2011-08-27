<?php
 
class RestClient {
    private $url;
    private $verb;
    private $requestBody;
    private $requestLength;
    private $username;
    private $password;
    private $acceptType;
    private $responseBody;
    private $responseInfo;

    public function __construct ($url = null, $verb = 'GET', $requestBody = null)
    {
        $this->url               = $url;
        $this->verb              = $verb;
        $this->requestBody       = $requestBody;
        $this->requestLength     = 0;
        $this->username          = null;
        $this->password          = null;
        $this->acceptType        = '';
//        $this->acceptType        = 'application/json';
        $this->responseBody      = null;
        $this->responseInfo      = null;

        if ($this->requestBody !== null)
        {
            $this->buildPostBody();
        }
    }

    public function flush ()
    {
        $this->requestBody       = null;
        $this->requestLength     = 0;
        $this->verb              = 'GET';
        $this->responseBody      = null;
        $this->responseInfo      = null;
    }

    public function execute ()
    {
        $ch = curl_init();
        $this->setAuth($ch);

        try
        {
            switch (strtoupper($this->verb))
            {
                case 'GET':
                    $this->executeGet($ch);
                    break;
                case 'POST':
                    $this->executePost($ch);
                    break;
                case 'PUT':
                    $this->executePut($ch);
                    break;
                case 'DELETE':
                    $this->executeDelete($ch);
                    break;
                default:
                    throw new InvalidArgumentException('Current verb (' . $this->verb . ') is an invalid REST verb.');
            }
        }
        catch (InvalidArgumentException $e)
        {
            curl_close($ch);
            throw $e;
        }
        catch (Exception $e)
        {
            curl_close($ch);
            throw $e;
        }
    }

    public function buildPostBody ($data = null)
    {
        $data = ($data !== null) ? $data : $this->requestBody;

        if (!is_array($data))
        {
            throw new InvalidArgumentException('Invalid data input for postBody.  Array expected');
        }

        $data = http_build_query($data, '', '&');
        $this->requestBody = $data;
    }

    protected function executeGet ($ch)
    {
        $this->doExecute($ch);
    }

    protected function executePost ($ch)
    {
        if (!is_string($this->requestBody))
        {
            $this->buildPostBody();
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->requestBody);
        curl_setopt($ch, CURLOPT_POST, 1);

        $this->doExecute($ch);
    }

    protected function executePut ($ch)
    {
        if (!is_string($this->requestBody))
        {
            $this->buildPostBody();
        }

        $this->requestLength = strlen($this->requestBody);

        $fh = fopen('php://memory', 'rw');
        fwrite($fh, $this->requestBody);
        rewind($fh);

        curl_setopt($ch, CURLOPT_INFILE, $fh);
        curl_setopt($ch, CURLOPT_INFILESIZE, $this->requestLength);
        curl_setopt($ch, CURLOPT_PUT, true);

        $this->doExecute($ch);

        fclose($fh);
    }

    protected function executeDelete ($ch)
    {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $this->doExecute($ch);
    }

    protected function doExecute (&$curlHandle)
    {
        $this->setCurlOpts($curlHandle);
        $this->responseBody = curl_exec($curlHandle);
        $this->responseInfo = curl_getinfo($curlHandle);

        curl_close($curlHandle);
    }

    protected function setCurlOpts (&$curlHandle)
    {
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);
        curl_setopt($curlHandle, CURLOPT_URL, $this->url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curlHandle,
            CURLOPT_HTTPHEADER,
            array (
                  'Accept: ' . $this->acceptType,
                  'GData-Version: 2'
            )
        );
    }

    protected function setAuth (&$curlHandle)
    {
        if ($this->username !== null && $this->password !== null)
        {
            curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
            curl_setopt($curlHandle, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        }
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }
}
