<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill\Container;

use DevLancer\Payment\API\Cashbill\Helper\LanguageTrait;

/**
 * Kontener przechowujący dane wykorzystywane przy zapytaniu:
 * GET payment/shopId/id?sign=signature
 */
class TransactionInfoContainer
{
    use LanguageTrait;

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
     * Tajny klucz.
     *
     * @var string
     */
    protected string $secretPhrase;

    /**
     * @param string $secretPhrase Tajny klucz.
     * @param string $shopId Identyfikator sklepu.
     * @param string $orderId Identyfikator płatności.
     * @param string|null $language Kod języka kanału płatności
     */
    public function __construct(string $secretPhrase, string $shopId, string $orderId, ?string $language = null)
    {
        $this->shopId = $shopId;
        $this->orderId = $orderId;
        $this->secretPhrase = $secretPhrase;
        $this->setLanguage($language);
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
     * @return string Tajny klucz.
     */
    public function getSecretPhrase(): string
    {
        return $this->secretPhrase;
    }

    /**
     * @return string Sygnatura potwierdzająca prawidłowość wysyłanych danych.
     */
    public function __toString()
    {
        $args = [
            'id' => $this->getOrderId(),
            'secretPhrase' => $this->getSecretPhrase()
        ];

        return sha1(implode("", $args));
    }
}