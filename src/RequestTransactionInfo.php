<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\Payment\Cashbill;

use DevLancer\Payment\Exception\InvalidArgumentException;
use DevLancer\Payment\Payment\Cashbill\Container\TransactionInfoContainer;
use DevLancer\Payment\Payment\Cashbill\Helper\IsErrorTrait;
use DevLancer\Payment\Payment\Cashbill\Helper\RequestUrlTrait;
use DevLancer\Payment\Transfer;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * Żądanie zwracające informacje o transakcji
 */
class RequestTransactionInfo extends Transfer
{
    use RequestUrlTrait;
    use IsErrorTrait;

    /**
     * Wysyła żądanie zwracające informacje o transakcji.
     *
     * @param TransactionInfoContainer $container
     * @return void
     * @throws GuzzleException
     */
    public function sendRequest($container): void
    {
        if (!$container instanceof TransactionInfoContainer) {
            throw new InvalidArgumentException(sprintf("Argument #1 (%s) must by of type %s, %s given", '$container', TransactionInfoContainer::class, (is_object($container)? get_class($container) : gettype($container))));
        }

        $requestUrl = "{$this->getRequestUrl()}/payment/{$container->getShopId()}/{$container->getOrderId()}?sign={$container}";
        try {
            $this->fromRequest = $this->httpClient->request('get', $requestUrl);
        } catch (RequestException $exception) {
            $this->fromRequest = $exception->getResponse();
        }
    }
}