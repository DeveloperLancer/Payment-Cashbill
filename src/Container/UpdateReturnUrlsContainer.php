<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill\Container;

/**
 * Kontener przechowujący dane wymagane przy zapytaniu:
 * PUT payment/shopId/orderId
 */
class UpdateReturnUrlsContainer
{
    /**
     * Tajny klucz.
     *
     * @var string
     */
    protected string $secretPhrase;

    /**
     * Identyfikator sklepu.
     *
     * @var string
     */
    protected string $shopId;

    /**
     * Identyfikator płatności.
     *
     * @var string
     */
    protected string $orderId;

    /**
     * Adres powrotu przeglądarki klienta po pozytywnym zakończeniu płatności.
     *
     * @var string
     */
    protected string $returnUrl;

    /**
     * Adres powrotu przeglądarki klienta po negatywnym zakończeniu transakcji.
     *
     * @var string
     */
    protected string $negativeReturnUrl;

    /**
     * @param string $secretPhrase Tajny klucz.
     * @param string $shopId Identyfikator sklepu.
     * @param string $orderId Identyfikator płatności.
     * @param string $returnUrl Adres powrotu przeglądarki klienta po pozytywnym zakończeniu płatności.
     * @param string $negativeReturnUrl Adres powrotu przeglądarki klienta po negatywnym zakończeniu transakcji.
     */
    public function __construct(string $secretPhrase, string $shopId, string $orderId, string $returnUrl, string $negativeReturnUrl)
    {
        $this->secretPhrase = $secretPhrase;
        $this->shopId = $shopId;
        $this->orderId = $orderId;
        $this->returnUrl = $returnUrl;
        $this->negativeReturnUrl = $negativeReturnUrl;
    }

    /**
     * @return string Sygnatura potwierdzająca prawidłowość wysyłanych danych.
     */
    public function __toString()
    {
        return sha1($this->getOrderId() . $this->getReturnUrl() . $this->getNegativeReturnUrl() . $this->getSecretPhrase());
    }

    /**
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

    /**
     * @return string Identyfikator płatności.
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string Adres powrotu przeglądarki klienta po pozytywnym zakończeniu płatności.
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    /**
     * @return string Adres powrotu przeglądarki klienta po negatywnym zakończeniu transakcji.
     */
    public function getNegativeReturnUrl(): string
    {
        return $this->negativeReturnUrl;
    }
}