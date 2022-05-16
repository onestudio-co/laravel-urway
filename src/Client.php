<?php

namespace URWay;

class Client extends BaseService
{
    /**
     * @var string
     */
    protected $endpoint = 'URWAYPGService/transaction/jsonProcess/JSONrequest';


    /**
     * Store request attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * @return $this
     */
    public function setTrackId(string $trackId)
    {
        $this->attributes['trackid'] = $trackId;
        return $this;
    }

    /**
     * @return $this
     */
    public function setCustomerEmail(string $email)
    {
        $this->attributes['customerEmail'] = $email;
        return $this;
    }

    /**
     * @return $this
     */
    public function setCustomerIp($ip)
    {
        $this->attributes['merchantIp'] = $ip;
        return $this;
    }

    /**
     * @return $this
     */
    public function setCurrency(string $currency)
    {
        $this->attributes['currency'] = $currency;
        return $this;
    }

    /**
     * @return $this
     */
    public function setCountry(string $country)
    {
        $this->attributes['country'] = $country;
        return $this;
    }

    /**
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->attributes['amount'] = $amount;
        return $this;
    }

    /**
     * @return $this
     */
    public function setRedirectUrl($url)
    {
        $this->attributes['udf2'] = $url;
        return $this;
    }

    /**
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param  array  $attributes
     *
     * @return $this
     */
    public function mergeAttributes(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * @param  mixed  $key
     * @param  mixed  $value
     *
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @param  mixed  $key
     *
     * @return boolean
     */
    public function hasAttribute($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * @param  mixed  $key
     *
     * @return boolean
     */
    public function removeAttribute($key)
    {
        $this->attributes = array_filter($this->attributes, function ($name) use ($key) {
            return $name !== $key;
        }, ARRAY_FILTER_USE_KEY);

        return $this;
    }

    /**
     * @return Response
     */
    public function pay()
    {
        // According to documentation we have to send the `terminal_id`, and `password` now.
        $this->setAuthAttributes();

        // We have to generate request
        $this->generateRequestHash();

        $response = $this->httpClient->post(
            $this->getEndPointPath(),
            $this->attributes,
        );
        return new Response((array) $response->json());

    }

    /**
     * @param string $transaction_id
     * @return Response
     */
    public function refund(string $transaction_id)
    {
        // According to documentation we have to send the `terminal_id`, and `password` now.
        $this->setAuthAttributes();

        // We have to generate request
        $this->generateRefundRequest();

        $this->attributes['transid'] = $transaction_id;

        $response = $this->httpClient->post(
            $this->getEndPointPath(),
            $this->attributes,
        );

        return new Response((array) $response->json());

    }

    /**
     * @param  string  $transaction_id
     * @return Response
     */
    public function find(string $transaction_id)
    {
        // According to documentation we have to send the `terminal_id`, and `password` now.
        $this->setAuthAttributes();

        // As requestHas for paying request is different from requestHash for find request.
        $this->generateFindRequestHash();

        $this->attributes['transid'] = $transaction_id;

        $response = $this->httpClient->post(
            $this->getEndPointPath(),
            $this->attributes,
        );

        return new Response((array) $response->json());
    }

    /**
     * @return void
     */
    protected function generateRequestHash()
    {
        $requestHash = $this->attributes['trackid'].'|'.config('urway.auth.terminal_id').'|'.config('urway.auth.password').'|'.config('urway.auth.merchant_key').'|'.$this->attributes['amount'].'|'.$this->attributes['currency'];
        $this->attributes['requestHash'] = hash('sha256', $requestHash);
        $this->attributes['action'] = '1'; // I don't know why.
    }

    protected function generateRefundRequest()
    {
        $requestHash = $this->attributes['trackid'].'|'.config('urway.auth.terminal_id').'|'.config('urway.auth.password').'|'.config('urway.auth.merchant_key').'|'.$this->attributes['amount'].'|'.$this->attributes['currency'];
        $this->attributes['requestHash'] = hash('sha256', $requestHash);
        $this->attributes['action'] = '2'; // Refund Process.
    }

    /**
     * @return void
     */
    protected function generateFindRequestHash()
    {
        $requestHash = $this->attributes['trackid'].'|'.config('urway.auth.terminal_id').'|'.config('urway.auth.password').'|'.config('urway.auth.merchant_key').'|'.$this->attributes['amount'].'|'.$this->attributes['currency'];
        $this->attributes['requestHash'] = hash('sha256', $requestHash);
        $this->attributes['action'] = '10'; // I don't know why.
    }

    /**
     * @return void
     */
    protected function setAuthAttributes()
    {
        $this->attributes['terminalId'] = config('urway.auth.terminal_id');
        $this->attributes['password'] = config('urway.auth.password');
    }
}
