<?php

namespace URWay;

class Response
{
    /**
     * Store the response data.
     * 
     * @var array
     */
    protected $data = [];

    /**
     * Response constructor.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string|boolean
     */
    public function getPaymentUrl()
    {
        if (! empty($this->data['payid']) && ! empty($this->data['targetUrl'])) {
            return $this->data['targetUrl'] . '?paymentid=' . $this->data['payid'];
        }

        return false;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->data['result'] == 'Successful' && $this->data['responseCode'] == '000';
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name];
    }
}