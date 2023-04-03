<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill\Helper;

use DevLancer\Payment\Helper\Currency;
use DevLancer\Payment\API\Cashbill\Channel\Channel;

/**
 * Zawiera często używane pola do kontenerów dla żądań lub odpowiedzi
 */
trait DefaultDataContainerTrait
{
    /**
     * Tytuł transakcji
     *
     * @var string
     */
    protected string $title;

    /**
     * (Paramentr opcjonalny) Identyfikator kanału płatności otrzymany po
     * wykonaniu żądania GET paymentchannels, jeżeli nie podano to klient
     * zostanie przekierowany na stronę wyboru kanału płatności w systemie
     * Płatności CashBill
     *
     * @var Channel|null|string
     */
    protected Channel|string|null $paymentChannel = null;

    /**
     * Opis transakcji
     *
     * @var string
     */
    protected string $description;

    /**
     * Dodatkowe dane przypisane do transakcji – nie są prezentowane klientow
     *
     * @var string
     */
    protected string $additionalData;

    /**
     * Kwota transakcji w postaci wartości dziesiętnej
     *
     * @var float
     */
    protected float $amountValue;

    /**
     * Kod waluty zgodny z ISO 4217
     *
     * @var Currency
     */
    protected Currency $amountCurrencyCode;

    /**
     * (Paramentr opcjonalny) Imię
     *
     * @var string|null
     */
    protected ?string $firstName = null;

    /**
     * (Paramentr opcjonalny) Nazwisko
     *
     * @var string|null
     */
    protected ?string $surname = null;

    /**
     * (Paramentr opcjonalny) Adres email
     * Parametr może być wymagana gdy jest został określony preferowany kanał płatności
     *
     * @var string|null
     */
    protected ?string $email = null;

    /**
     * (Paramentr opcjonalny) Państwo
     *
     * @var string|null
     */
    protected ?string $country = null;

    /**
     * (Paramentr opcjonalny) Miasto
     *
     * @var string|null
     */
    protected ?string $city = null;

    /**
     * (Paramentr opcjonalny) Kod pocztowy
     *
     * @var string|null
     */
    protected ?string $postcode = null;

    /**
     * (Paramentr opcjonalny) Ulica
     *
     * @var string|null
     */
    protected ?string $street = null;

    /**
     * (Paramentr opcjonalny) Numer budynku
     *
     * @var string|null
     */
    protected ?string $house = null;

    /**
     * (Paramentr opcjonalny) Numer mieszkania
     *
     * @var string|null
     */
    protected ?string $flat = null;

    /**
     * @return string Tytuł transakcji
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Zwraca preferowany kanał płatności
     * * wartość typu Channel zawiera wszystkie dane o kanale płatnosci
     * * wartość typu string zawiera wyłącznie id kanału płatności
     * * wartość typu null oznacza, że nie wybrano preferowanego kanału płatności
     *
     * @return Channel|string|null Preferowany kanał płatności
     */
    public function getChannel(): Channel|string|null
    {
        return $this->paymentChannel;
    }

    /**
     * @return string Opis transakcji
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string Dodatkowe dane przypisane do transakcji
     */
    public function getAdditionalData(): string
    {
        return $this->additionalData;
    }


    /**
     * @return float Kwota transakcji w postaci wartości dziesiętnej
     */
    public function getAmountValue(): float
    {
        return $this->amountValue;
    }

    /**
     * @return Currency Kod waluty zgodny z ISO 4217
     */
    public function getAmountCurrencyCode(): Currency
    {
        return $this->amountCurrencyCode;
    }

    /**
     * @return string|null Imię
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string|null Nazwisko
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @return string|null Adres e-mail
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null Państwo
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @return string|null Miasto
     */
    public function getCity(): ?string
    {
        return $this->city;
    }


    /**
     * @return string|null Kod pocztowy
     */
    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    /**
     * @return string|null Ulica
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @return string|null Budynek
     */
    public function getHouse(): ?string
    {
        return $this->house;
    }

    /**
     * @return string|null Mieszkanie
     */
    public function getFlat(): ?string
    {
        return $this->flat;
    }
}