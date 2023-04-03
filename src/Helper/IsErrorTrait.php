<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill\Helper;

/**
 * Dostarcza metoda która pozwala zweryfikować czy żądanie się powiodło
 */
trait IsErrorTrait
{
    /**
     * @inheritDoc
     */
    public function isError(): bool
    {
        $response = $this->fromRequest;
        $statusCode = $response->getStatusCode();

        if ($statusCode != 200)
            return true;

        $content = (string) $response->getBody();
        $content = \json_decode($content, true);
        if (isset($content['errorMessage']))
            return true;

        return false;
    }
}