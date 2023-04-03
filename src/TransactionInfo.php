<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\Payment\Cashbill;

use DevLancer\Payment\Exception\InvalidCurrencyException;
use DevLancer\Payment\Helper\Currency;
use DevLancer\Payment\Helper\TestModeTrait;
use DevLancer\Payment\Payment\Cashbill\Helper\DefaultDataContainerTrait;

/**
 * Przechowuje wszystkie informacje o transakcji
 */
class TransactionInfo
{
    use DefaultDataContainerTrait;
    use TestModeTrait;

    /**
     * Status płatności oznaczający, że płatność się powiodła
     */
    public const SUCCESS_STATUS = 'PositiveFinish';

    /**
     * Identyfikator płatności
     *
     * @var string
     */
    protected string $orderId;

    /**
     * Status transakcji
     *
     * @var string
     */
    protected string $status;

    /**
     * Wpłacona kwota
     *
     * @var float
     */
    protected float $amountValue;

    /**
     * Waluta wpłaconej kwoty
     *
     * @var Currency
     */
    protected Currency $amountCurrencyCode;

    /**
     * Żądana kwota transakcji
     *
     * @var float
     */
    protected float $requestedAmountValue;

    /**
     * Żądana waluta transakcji
     *
     * @var string
     */
    protected string $requestedAmountCurrencyCode;


    /**
     * @param array $data
     * @throws InvalidCurrencyException
     */
    public function __construct(array $data)
    {
        $currencyCode          = $data['amount']['currencyCode'];
        if (!$currencyCode instanceof Currency)
            $currencyCode = new Currency($currencyCode);

        $requestedCurrencyCode = $data['requestedAmount']['currencyCode'];
        if (!$requestedCurrencyCode instanceof Currency)
            $requestedCurrencyCode = new Currency($requestedCurrencyCode);

        $this->orderId                      = $data['id'];
        $this->title                        = $data['title'];
        $this->status                       = $data['status'];
        $this->description                  = $data['description'];
        $this->paymentChannel               = $data['paymentChannel'];
        $this->additionalData               = $data['additionalData'];
        $this->amountValue                  = floatval($data['amount']['value']);
        $this->amountCurrencyCode           = $currencyCode;
        $this->requestedAmountCurrencyCode  = $requestedCurrencyCode;
        $this->requestedAmountValue         = floatval($data['requestedAmount']['value']);
        $this->firstName                    = $data['personalData']['firstName'] ?? null;
        $this->surname                      = $data['personalData']['surname'] ?? null;
        $this->email                        = $data['personalData']['email'] ?? null;
        $this->city                         = $data['personalData']['city'] ?? null;
        $this->house                        = $data['personalData']['house'] ?? null;
        $this->flat                         = $data['personalData']['flat'] ?? null;
        $this->street                       = $data['personalData']['street'] ?? null;
        $this->postcode                     = $data['personalData']['postcode'] ?? null;
        $this->country                      = $data['personalData']['country'] ?? null;
    }

    /**
     * @return string Identyfikator płatności
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string Status transakcji
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Zwraca true, gdy płatność się powiodła i została pomyślnie zakończona
     *
     * @return bool
     */
    public function isSuccessful():bool
    {
        return $this->getStatus() == self::SUCCESS_STATUS;
    }

    /**
     * @return float Żądana kwota transakcji
     */
    public function getRequestedAmountValue(): float
    {
        return $this->requestedAmountValue;
    }

    /**
     * @return Currency Żądana waluta transakcji
     */
    public function getRequestedAmountCurrencyCode(): Currency
    {
        return $this->requestedAmountCurrencyCode;
    }

}