<?php
/**
 * Created by PhpStorm.
 * User: kraken
 * Date: 18/09/18
 * Time: 16.26
 */

namespace Webgriffe\LibMonetaWebDue\PaymentCapture\Response;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Mapper implements MapperInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Mapper constructor.
     *
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function map(\Psr\Http\Message\ResponseInterface $response)
    {
        $rawXml = $response->getBody()->getContents();
        $this->log('Capture response: '.$rawXml);

        $xml = simplexml_load_string($rawXml);
        if (!$xml) {
            throw new \RuntimeException('Could not parse response body as XML');
        }

        if (isset($xml->errorcode) || isset($xml->errormessage)) {
            $message = sprintf(
                'The capture request generated an error with code "%s" and message: %s',
                $xml->errorCode,
                $xml->errorMessage
            );
            $this->log($message, LogLevel::ERROR);
            throw new \RuntimeException($message);
        }

        return new SuccessResponse(
            $xml->paymentid,
            $xml->result,
            $xml->responsecode,
            $xml->authorizationcode,
            $xml->merchantorderid,
            $xml->description,
            $xml->customfield
        );
    }

    private function log($message, $level = LogLevel::DEBUG)
    {
        if ($this->logger) {
            $this->logger->log($level, '[Lib MonetaWeb2]: ' . $message);
        }
    }
}
