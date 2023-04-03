<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill;

use DevLancer\Payment\Exception\InvalidArgumentException;
use DevLancer\Payment\API\Cashbill\Channel\Channel;
use DevLancer\Payment\API\Cashbill\Container\PaymentContainer;
use DevLancer\Payment\API\Cashbill\Helper\IsErrorTrait;
use DevLancer\Payment\API\Cashbill\Helper\RequestUrlTrait;
use DevLancer\Payment\Transfer;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * Żądanie generujące nową transakcję
 */
class RequestPayment extends Transfer
{
    use RequestUrlTrait;
    use IsErrorTrait;

    /**
     * Gdy true oraz podano, preferowany kanał płatności sprawdza,
     * czy podano wszystkie wymagane parametry wymagane dla kanału płatności
     *
     * @var bool
     */
    protected bool $strictRequirementsChannel = false;


    /**
     * Wysyła żądanie generujące nową płatność.
     *
     * @param PaymentContainer $container
     * @return void
     * @throws GuzzleException
     * @throws \DevLancer\Payment\Exception\RequestException
     */
    public function sendRequest($container): void
    {
        if (!$container instanceof PaymentContainer)
            throw new InvalidArgumentException(sprintf("Argument #1 (%s) must by of type %s, %s given", '$container', PaymentContainer::class, (is_object($container)? get_class($container) : gettype($container))));

        if ($this->isStrictRequirementsChannel())
            $this->checkChannel($container);

        $postFields = $this->generatePostFields($container);
        $requestUrl = "{$this->getRequestUrl()}/payment/{$container->getShopId()}";

        try {
            $response = $this->httpClient->request('post', $requestUrl, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF8',
                ],
                'form_params' => $postFields
            ]);

            $this->fromRequest = $response;
        } catch (RequestException $exception) {
            $this->fromRequest = $exception->getResponse();
        }
    }


    /**
     * Sprawdza, czy podano wszystkie wymagane parametry dla preferowanego kanału płatności.
     *
     * @param PaymentContainer $container
     * @return void
     * @throws \DevLancer\Payment\Exception\RequestException
     */
    protected function checkChannel(PaymentContainer $container): void
    {
        $channel = $container->getChannel();
        if (!$channel instanceof Channel)
            return;

        if (!in_array($container->getAmountCurrencyCode(), $channel->getAvailableCurrencies()))
            throw new \DevLancer\Payment\Exception\RequestException(sprintf("Waluta platnosci: %s nie jest obsługiwana w kanale platnosci, lista dostepnych: [%s ]", $container->getAmountCurrencyCode(), implode(" ", $channel->getAvailableCurrencies()))); //todo

        if (!$container->getEmail())
            throw new \DevLancer\Payment\Exception\RequestException("Musisz podać email do kontenera dla żądania wygenerowania transakcji, użyj metody setEmail dla klasy PaymentContainer"); //todo
    }

    /**
     * Zwraca tablice wysyłaną w żądaniu
     *
     * @param PaymentContainer $container
     * @return array
     */
    protected function generatePostFields(PaymentContainer $container): array
    {
        return [
            'title'                 => $container->getTitle(),
            'amount.value'          => (string) $container->getAmountValue(),
            'amount.currencyCode'   => $container->getAmountCurrencyCode(),
            'returnUrl'             => $container->getReturnUrl(),
            'description'           => $container->getDescription(),
            'negativeReturnUrl'     => $container->getNegativeReturnUrl(),
            'additionalData'        => $container->getAdditionalData(),
            'paymentChannel'        => (string) $container->getChannel(),
            'languageCode'          => $container->getLanguage(),
            'referer'               => $container->getReferer(),
            'personalData.firstName'=> $container->getFirstName(),
            'personalData.surname'  => $container->getSurname(),
            'personalData.email'    => $container->getEmail(),
            'personalData.country'  => $container->getCountry(),
            'personalData.city'     => $container->getCity(),
            'personalData.postcode' => $container->getPostcode(),
            'personalData.street'   => $container->getStreet(),
            'personalData.house'    => $container->getHouse(),
            'personalData.flat'     => $container->getFlat(),
            'sign'                  => (string) $container,
        ];
    }

    /**
     * @return bool Czy ma sprawdzać wymagane parametry dla kanału płatności
     */
    public function isStrictRequirementsChannel(): bool
    {
        return $this->strictRequirementsChannel;
    }

    /**
     * @param bool $strictRequirementsChannel Czy ma sprawdzać wymagane parametry dla kanału płatności
     */
    public function setStrictRequirementsChannel(bool $strictRequirementsChannel): void
    {
        $this->strictRequirementsChannel = $strictRequirementsChannel;
    }
}