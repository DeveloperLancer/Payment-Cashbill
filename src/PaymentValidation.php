<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\Payment\Cashbill;

use DevLancer\Payment\Payment\Cashbill\Container\ValidationContainer;

/**
 * Klasa odpowiada za weryfikacje aktualnego stanu płatności,
 * obiekt powinien zostać wywołany podczas żądania od cashbill
 */
class PaymentValidation
{
    /**
     * @var ValidationContainer
     */
    private ValidationContainer $container;

    /**
     * @param ValidationContainer $container
     */
    public function __construct(ValidationContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Sprawdza poprawność sumy kontrolnej
     *
     * @return bool
     */
    public function checkSign(): bool
    {
        return ((string) $this->container) == $this->container->getSign();
    }

    /**
     * Zwraca identyfikator płatności
     *
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->getContainer()->getArgs()[0];
    }

    /**
     * @return ValidationContainer
     */
    public function getContainer(): ValidationContainer
    {
        return $this->container;
    }
}