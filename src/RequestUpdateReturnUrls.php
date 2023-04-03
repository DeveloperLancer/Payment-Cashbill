<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill;

use DevLancer\Payment\Exception\InvalidArgumentException;
use DevLancer\Payment\API\Cashbill\Container\UpdateReturnUrlsContainer;
use DevLancer\Payment\API\Cashbill\Helper\RequestUrlTrait;
use DevLancer\Payment\Transfer;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * Żądanie aktualizujące adres powrotu dla użytkownika transakcji
 */
class RequestUpdateReturnUrls extends Transfer
{
    use RequestUrlTrait;

    /**
     * Wysyła żądanie aktualizujące adres powrotu dla użytkownika transakcji.
     * Żądanie należy wykonać po utworzeniu transakcji, ale przed rozpoczęciem płatności przez użytkownika.
     *
     * @param $container
     * @return void
     * @throws GuzzleException
     * @throws InvalidArgumentException
     */
    public function sendRequest($container): void
    {
        if (!$container instanceof UpdateReturnUrlsContainer)
            throw new InvalidArgumentException(sprintf("Argument #1 (%s) must by of type %s, %s given", '$container', UpdateReturnUrlsContainer::class, (is_object($container)? get_class($container) : gettype($container))));

        $requestUrl = "{$this->getRequestUrl()}/payment/{$container->getShopId()}/{$container->getOrderId()}";
        try {
            $response = $this->httpClient->request('put', $requestUrl, [
                'form_params' => array(
                    "returnUrl" => $container->getReturnUrl(),
                    "negativeReturnUrl" => $container->getNegativeReturnUrl(),
                    "sign" => (string)$container
                )
            ]);

            $this->fromRequest = $response;
        } catch (RequestException $exception) {
            $this->fromRequest = $exception->getResponse();
        }
    }

    /**
     * @inheritDoc
     */
    public function isError(): bool
    {
        $response = $this->fromRequest;
        $statusCode = $response->getStatusCode();

        return ($statusCode != 204);
    }
}