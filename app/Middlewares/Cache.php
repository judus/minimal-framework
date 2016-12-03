<?php namespace Acme\Middlewares;

use Maduser\Minimal\Base\Interfaces\ConfigInterface;
use Maduser\Minimal\Base\Interfaces\MiddlewareInterface;
use Maduser\Minimal\Base\Interfaces\RequestInterface;
use Maduser\Minimal\Base\Interfaces\RouteInterface;

/**
 * Class Cache
 *
 * @package Maduser\Minimal\Base\Middlewares
 */
class Cache implements MiddlewareInterface
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
        $this->filename = PATH . rtrim($this->config->item('cache.path'), '/') .
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
            $cache = str_replace('</body>',
                '<p><small>Cached</small></p></body>', $cache);
        }
        return $cache;
    }

    /**
     *
     */
    public function after()
    {
        $this->deleteCache($this->getFilename());
        $this->setCache($this->getFilename(), $this->getData());
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
    }

}