<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\Payment\Cashbill;

use DevLancer\Payment\Helper\TestModeTrait;
use DevLancer\Payment\Payment\Cashbill\Container\UpdateReturnUrlsContainer;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Odpowiada za przechowanie danych z odpowiedzi po żądaniu wygenerowania transakcji i umożliwia aktualizacje
 * adresów zwrotnych dla udanej i nieudanej transakcji 
 */
class Payment
{
    use TestModeTrait;

    /**
     * Tajny klucz.
     *
     * @var string
     */
    private string $secretPhrase;

    /**
     * Identyfikator sklepu.
     *
     * @var string
     */
    private string $shopId;

    /**
     * Identyfikator płatności.
     *
     * @var string
     */
    private string $orderId;

    /**
     * Adres na który, należy przekierować klienta, aby rozpocząć transakcje
     *
     * @var string
     */
    private string $redirectUrl;

    /**
     * Odpowiedź po żądaniu podczas tworzenia transakcji
     *
     * @var ResponseInterface|null
     */
    private ?ResponseInterface $responseUpdateReturnUrls;

    /**
     * @param string $secretPhrase
     * @param string $shopId
     * @param string $orderId
     * @param string $redirectUrl
     */
    public function __construct(string $secretPhrase, string $shopId, string $orderId, string $redirectUrl)
    {
        $this->secretPhrase = $secretPhrase;
        $this->shopId = $shopId;
        $this->orderId = $orderId;
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * Metoda umożliwia zaktualizowanie adresów na które zostanie przekierowany klient po udanej bądź nie udanej transakcji
     *
     * @param string $successUrl
     * @param string $failUrl
     * @param RequestUpdateReturnUrls|null $updateReturnUrls
     * @return bool
     * @throws GuzzleException
     */
    public function updateReturnUrls(string $successUrl, string $failUrl, ?RequestUpdateReturnUrls $updateReturnUrls = null):bool
    {
        if (!$updateReturnUrls) {
            $updateReturnUrls = new RequestUpdateReturnUrls();
            $updateReturnUrls->setTestMode($this->testMode);
        }

        $container = new UpdateReturnUrlsContainer($this->secretPhrase, $this->shopId, $this->orderId, $successUrl, $failUrl);
        $updateReturnUrls->sendRequest($container);
        $this->responseUpdateReturnUrls = $updateReturnUrls->getResponse();
        return !$updateReturnUrls->isError();
    }

    /**
     * Zawiera obsłużoną odpowiedź po wykonaniu metody updateReturnUrls
     *
     * @return ResponseInterface|null
     */
    public function getResponseUpdateReturnUrls(): ?ResponseInterface
    {
        return $this->responseUpdateReturnUrls;
    }

    /**
     * @return string Identyfikator płatności.
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string Adres na który, należy przekierować przeglądarkę klienta, aby rozpoczął płatność.
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     *
     *
     * @return string Tajny klucz.
     */
    public function getSecretPhrase(): string
    {
        return $this->secretPhrase;
    }

    /**
     * @return string Identyfikator sklepu.
     */
    public function getShopId(): string
    {
        return $this->shopId;
    }
}