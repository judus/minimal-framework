<?php namespace App\Demo\Base\Middlewares;

use Maduser\Minimal\Config\Contracts\ConfigInterface;
use Maduser\Minimal\Middlewares\AbstractMiddleware;
use Maduser\Minimal\Http\Contracts\RequestInterface;

/**
 * Class Cache
 *
 * @package Maduser\Minimal\Middlewares
 */
class Cache extends AbstractMiddleware
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var
     */
    private $timeout;

    /**
     * @var
     */
    private $data;

    /**
     * @var
     */
    private $filename;

    /**
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param mixed $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param $uri
     */
    public function setFilename($uri)
    {
        $this->filename = rtrim($this->config->item('paths.system'), '/') . '/' . rtrim($this->config->item('storage.cache'), '/') .
            '/' . md5($uri) . '.cache';
    }

    /**
     * Cache constructor.
     *
     * @param RequestInterface $request
     * @param ConfigInterface  $config
     * @param                  $timeout
     * @param                  $data
     */
    public function __construct(
        RequestInterface $request,
        ConfigInterface $config,
        $timeout,
        $data
    ) {
        $this->request = $request;
        $this->config = $config;
        $this->setTimeout($timeout);
        $this->setData($data);
        $this->setFilename($request->getUriString());
    }

    /**
     * @return string
     */
    public function before()
    {
        $cache = $this->getCache($this->getFilename(), $this->getTimeout());
        if ($cache) {

            $cache = str_replace('</footer>',
                '<p><small>Cached contents - FrontController was not executed</small></p></footer>', $cache);
        }

        $this->setPayload($cache);
        return $cache;
    }

    /**
     *
     */
    public function after()
    {
        $this->deleteCache($this->getFilename());
        $this->setCache($this->getFilename(), $this->getPayload());
    }

    /**
     * @param $filename
     */
    public function deleteCache($filename)
    {
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    /**
     * @param $filename
     * @param $data
     */
    public function setCache($filename, $data)
    {
        file_put_contents($filename, $data);
    }


    /**
     * @param $filename
     * @param $timeout
     *
     * @return string
     */
    public function getCache($filename, $timeout)
    {
        if (file_exists($filename)
            && (filemtime($filename) + $timeout) > time()
        ) {
            return file_get_contents($filename);
        }

        return null;
    }

}