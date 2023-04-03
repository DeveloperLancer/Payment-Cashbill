<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill\Channel;
use DevLancer\Payment\Helper\Currency;

/**
 * Przechowuje dane o kanale płatności
 */
class Channel
{
    /**
     * Identyfikator kanału płatności
     *
     * @var string
     */
    private string $id;

    /**
     * Nazwa kanału płatności
     *
     * @var string
     */
    private string $name;

    /**
     * Opis kanału płatności w języku, który został podany w żądaniu
     *
     * @var string|null
     */
    private ?string $description;

    /**
     * Adres url logo
     *
     * @var string|null
     */
    private ?string $logoUrl;

    /**
     * Tablica kodów zgodnych z ISO 4217 dla obsługiwanych walut
     *
     * @var Currency[]
     */
    private array $availableCurrencies;

    /**
     * @param string $id Identyfikator kanału płatności
     * @param string $name Nazwa kanału płatności
     * @param Currency[] $availableCurrencies Tablica kodów zgodnych z ISO 4217 dla obsługiwanych walut
     * @param string|null $description Opis kanału płatności w języku, który został podany w żądaniu
     * @param string|null $logoUrl Adres url logo
     */
    public function __construct(string $id, string $name, array $availableCurrencies, ?string $description = null, ?string $logoUrl = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->logoUrl = $logoUrl;
        $this->availableCurrencies = $availableCurrencies;
    }

    /**
     * @return string Identyfikator kanału płatności
     */
    public function __toString()
    {
        return $this->getId();
    }

    /**
     * @return string Identyfikator kanału płatności
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string Nazwa kanału płatności
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null Opis kanału płatności w języku, który został podany w żądaniu
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null Adres url logo
     */
    public function getLogoUrl(): ?string
    {
        return $this->logoUrl;
    }

    /**
     * @return Currency[] Tablica kodów zgodnych z ISO 4217 dla obsługiwanych walut
     */
    public function getAvailableCurrencies(): array
    {
        return $this->availableCurrencies;
    }
}