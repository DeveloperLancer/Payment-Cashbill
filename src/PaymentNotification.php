<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill;

use DevLancer\Payment\API\Cashbill\Container\NotificationContainer;

/**
 * Klasa odpowiada za weryfikacje aktualnego stanu płatności,
 * obiekt powinien zostać wywołany podczas żądania od cashbill
 */
class PaymentNotification
{
    /**
     * @var NotificationContainer
     */
    private NotificationContainer $container;

    /**
     * @param NotificationContainer $container
     */
    public function __construct(NotificationContainer $container)
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
     * @return NotificationContainer
     */
    public function getContainer(): NotificationContainer
    {
        return $this->container;
    }
}