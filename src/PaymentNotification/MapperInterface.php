<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 12/01/18
 * Time: 15.06
 */

namespace Webgriffe\LibMonetaWebDue\PaymentNotification;

use Psr\Http\Message\ServerRequestInterface;

interface MapperInterface
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return Result\PaymentResultInterface
     * @throws \InvalidArgumentException
     */
    public function map(ServerRequestInterface $request);
}
