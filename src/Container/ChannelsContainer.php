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
 * Kontener przechowujący dane wymagane przy zapytaniu:
 * GET paymentchannels/shopId/language
 */
class ChannelsContainer
{
    use LanguageTrait;

    /**
     * Identyfikator sklepu
     *
     * @var string
     */
    protected string $shopId;

    /**
     * @param string $shopId Identyfikator sklepu
     * @param string|null $language Kod języka kanału płatności PL lub EN, niewymagane
     */
    public function __construct(string $shopId, ?string $language = null)
    {
        $this->shopId = $shopId;
        $this->setLanguage($language);
    }

    /**
     * Identyfikator sklepu
     *
     * @return string
     */
    public function getShopId(): string
    {
        return $this->shopId;
    }
}