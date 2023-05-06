<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill;

use DevLancer\Payment\Exception\RequestException;
use DevLancer\Payment\Helper\Currency;
use DevLancer\Payment\Helper\TestModeTrait;
use DevLancer\Payment\API\Cashbill\Channel\Channel;
use DevLancer\Payment\API\Cashbill\Channel\ChannelCollection;
use DevLancer\Payment\API\Cashbill\Container\ChannelsContainer;
use DevLancer\Payment\API\Cashbill\Container\PaymentContainer;
use DevLancer\Payment\API\Cashbill\Container\TransactionInfoContainer;
use DevLancer\Payment\API\Cashbill\Container\NotificationContainer;
use DevLancer\Payment\TransferInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;


/**
 * Gotowy interfejs do obsługi płatności Cashbill
 */
class Cashbill
{
    use TestModeTrait;

    /**
     * Gdy true oraz podano, preferowany kanał płatności wówczas sprawdza,
     * czy podano wszystkie wymagane parametry wymagane dla kanału płatności
     *
     * @var bool
     */
    private bool $strictRequirementsChannel;

    /**
     * Zawiera odpowiedź, gdy zostanie wykonana metoda: generatePayment, getPaymentChannels lub getTransactionInfo
     *
     * @var ResponseInterface|null
     */
    private null|ResponseInterface $response = null;

    /**
     * @param bool $strictRequirementsChannel
     */
    public function __construct(bool $strictRequirementsChannel = false)
    {
        $this->strictRequirementsChannel = $strictRequirementsChannel;
    }


    /**
     * Wysyła żądanie, które pozwala wygenerować nową płatność
     *
     * Zwraca:
     * * obiekt Payment gdy, wysłano poprawnie żądanie,
     * * null gdy, odpowiedź zwraca nieoczekiwaną wartość,
     *
     * @param PaymentContainer $container
     * @param RequestPayment|null $request
     * @return Payment|null
     * @throws GuzzleException
     * @throws RequestException
     */
    public function generatePayment(PaymentContainer $container, ?TransferInterface $request = null): null|Payment
    {
        if (!$request) {
            $request = new RequestPayment();
            $request->setTestMode($this->testMode);
        }

        $request->sendRequest($container);
        $this->response = $request->getResponse();
        if ($request->isError())
            return null;

        $data = \json_decode((string) $this->response->getBody(), true);
        $payment = new Payment($container->getSecretPhrase(), $container->getShopId(), $data['id'], $data['redirectUrl']);
        $payment->setTestMode($this->testMode);

        return $payment;

    }

    /**
     * Wysyła żądanie, które pozwala uzyskać informacje o transakcji
     *
     * Zwraca:
     * * obiekt TransactionInfo gdy, wysłano poprawnie żądanie,
     * * null gdy, odpowiedź zwraca nieoczekiwaną wartość,
     *
     * @param TransactionInfoContainer $container
     * @param RequestTransactionInfo|null $request
     * @return TransactionInfo|null
     * @throws GuzzleException
     */
    public function getTransactionInfo(TransactionInfoContainer $container, ?TransferInterface $request = null): null|TransactionInfo
    {
        if (!$request) {
            $request = new RequestTransactionInfo();
            $request->setTestMode($this->testMode);
        }

        $request->sendRequest($container);
        $this->response = $request->getResponse();
        if ($request->isError())
            return null;

        $data = \json_decode((string) $this->response->getBody(), true);
        $transactionInfo = new TransactionInfo($data);
        $transactionInfo->setTestMode($this->testMode);

        return $transactionInfo;
    }

    /**
     * Wysyła żądanie, które pozwala pobrać dostępne kanały płatności
     *
     * Zwraca:
     * * obiekt ChannelCollection gdy, wysłano poprawnie żądanie,
     * * null gdy, odpowiedź zwraca nieoczekiwaną wartość,
     *
     * @param ChannelsContainer $container
     * @param RequestPaymentChannels|null $request
     * @return ChannelCollection|null
     * @throws GuzzleException
     */
    public function getPaymentChannels(ChannelsContainer $container, ?TransferInterface $request = null): null|ChannelCollection
    {
        if (!$request) {
            $request = new RequestPaymentChannels();
            $request->setTestMode($this->testMode);
        }

        $request->sendRequest($container);
        $this->response = $request->getResponse();
        if ($request->isError())
            return null;

        $data = \json_decode((string) $this->response->getBody(), true);
        $collection = new ChannelCollection();
        foreach ($data as $channel) {
            $currencies =  $channel['availableCurrencies'];
            foreach ($currencies as &$currency)
                $currency = new Currency($currency);

            $collection->add(
                new Channel(
                    $channel['id'],
                    $channel['name'],
                    $currencies,
                    $channel['description'],
                    $channel['logoUrl'],
                )
            );
        }

        return $collection;
    }

    /**
     * Metoda weryfikuje odpowiedź od Cashbill, zapewnia implementacje dla Notification service.
     * Cashbill wymaga, żeby po zweryfikowaniu płatności udzielić odpowiedzi: 200 'OK'
     *
     * @param NotificationContainer $container
     * @param bool $printResponse Jeżeli true, zostanie wyświetlona odpowiedź 200 'OK'
     * @return PaymentNotification|null
     */
    public function paymentNotification(NotificationContainer $container, bool $printResponse = false): null|PaymentNotification
    {
        if ($printResponse) {
            http_response_code(200);
            echo 'OK';
        }

        $validation = new PaymentNotification($container);
        if (!$validation->checkSign())
            return null;

        return $validation;
    }

    /**
     * Zwraca odpowiedź po wykonaniu żądania, które zostało obsłużone nie zależnie od odpowiedzi.
     * Zawiera odpowiedź po wykonaniu metod: generatePayment, getTransactionInfo lub getPaymentChannel
     *
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}