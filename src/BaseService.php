<?php

namespace URWay;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

abstract class BaseService
{
    /**
     * Store guzzle client instance.
     *
     * @var PendingRequest
     */
    protected $httpClient;

    /**
     * URWAY payment base path.
     *
     * @var string
     */
    protected $basePath_dev = 'https://payments-dev.urway-tech.com'; // SandBBox (Testing)
    protected $basePath_live = 'https://payments.urway-tech.com'; // Live (Production)

    /**
     * Store URWAY payment endpoint.
     *
     * @var string
     */
    protected $endpoint;

    /**
     * BaseService Constructor.
     */
    public function __construct()
    {
        $this->httpClient = Http::acceptJson();
    }

    /**
     * @return string
     */
    public function getEndPointPath()
    {
        return config('urway.dev_mode') ? $this->basePath_dev : $this->basePath_live . '/' . $this->endpoint;
    }
}
