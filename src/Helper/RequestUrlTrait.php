<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill\Helper;

/**
 * Dostarcza metodę określającą adres żądania w zależności od trybu
 */
trait RequestUrlTrait
{
    /**
     * Adres produkcyjny dla API
     *
     * @var string
     */
    private string $PROD_REQUEST_URL = "https://pay.cashbill.pl/ws/rest";

    /**
     * Adres testowy dla API
     *
     * @var string
     */
    private string $TEST_REQUEST_URL = "https://pay.cashbill.pl/testws/rest/";


    /**
     * @inheritDoc
     */
    public function getRequestUrl(): string
    {
        if ($this->isTestMode())
            return $this->TEST_REQUEST_URL;

        return $this->PROD_REQUEST_URL;
    }
}