<?php
/**
 * Created by PhpStorm.
 * User: kraken
 * Date: 18/09/18
 * Time: 16.27
 */

namespace Webgriffe\LibMonetaWebDue\PaymentCapture\Response;

interface MapperInterface
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function map(\Psr\Http\Message\ResponseInterface $response);
}
