<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill;

use DevLancer\Payment\Exception\InvalidArgumentException;
use DevLancer\Payment\API\Cashbill\Container\ChannelsContainer;
use DevLancer\Payment\API\Cashbill\Helper\IsErrorTrait;
use DevLancer\Payment\API\Cashbill\Helper\RequestUrlTrait;
use DevLancer\Payment\Transfer;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * Żądanie zwracające kolekcje dostępnych kanałów płatności
 */
class RequestPaymentChannels extends Transfer
{
    use RequestUrlTrait;
    use IsErrorTrait;

    /**
     * Wysyła żądanie zwracające dostępne kanały płatności.
     *
     * @param ChannelsContainer $container
     * @return void
     * @throws GuzzleException
     */
    public function sendRequest($container): void
    {
        if (!$container instanceof ChannelsContainer)
            throw new InvalidArgumentException(sprintf("Argument #1 (%s) must by of type %s, %s given", '$container', ChannelsContainer::class, (is_object($container)? get_class($container) : gettype($container))));

        $requestUrl = "{$this->getRequestUrl()}/paymentchannels/{$container->getShopId()}";
        if ($container->getLanguage())
            $requestUrl .= "/" . strtolower($container->getLanguage());

        try {
            $this->fromRequest = $this->httpClient->request('get', $requestUrl);
        } catch (RequestException $exception) {
            $this->fromRequest = $exception->getResponse();
        }
    }
}