<?php
/**
 * Created by PhpStorm.
 * User: kraken
 * Date: 28/09/18
 * Time: 17.11
 */

namespace Webgriffe\LibMonetaWebDue;

interface LogicRequestDataContainerInterface
{
    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getParams();
}
