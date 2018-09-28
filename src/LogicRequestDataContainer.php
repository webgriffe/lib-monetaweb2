<?php
/**
 * Created by PhpStorm.
 * User: kraken
 * Date: 28/09/18
 * Time: 17.10
 */

namespace Webgriffe\LibMonetaWebDue;

class LogicRequestDataContainer implements LogicRequestDataContainerInterface
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $params;

    public function __construct($url, $method, $params)
    {
        $this->url = $url;
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }
}
