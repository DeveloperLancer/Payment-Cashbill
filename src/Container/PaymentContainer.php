<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill\Container;

use DevLancer\Payment\Exception\InvalidCurrencyException;
use DevLancer\Payment\Helper\Currency;
use DevLancer\Payment\API\Cashbill\Channel\Channel;
use DevLancer\Payment\API\Cashbill\Helper\DefaultDataContainerTrait;
use DevLancer\Payment\API\Cashbill\Helper\LanguageTrait;
use JetBrains\PhpStorm\Deprecated;

/**
 * Kontener przechowujący dane wymagane dla żądania:
 * POST payment/shopId
 */
class PaymentContainer
{
    use LanguageTrait;
    use DefaultDataContainerTrait;

    /**
     * Identyfikator sklepu
     * @var string
     */
    protected string $shopId;

    /**
     * (Paramentr opcjonalny) Adres powrotu przeglądarki klienta po pozytywnym zakończeniu płatności
     *
     * @var string|null
     */
    protected ?string $returnUrl = null;

    /**
     * (Paramentr opcjonalny) Adres powrotu przeglądarki klienta po
     * negatywnym zakończeniu transakcji, jeżeli nie zostanie ustawiony to
     * przekierowanie nastąpi na adres zgodny z polem returnUrl
     *
     * @var string|null
     */
    protected ?string $negativeReturnUrl = null;

    /**
     * (Paramentr opcjonalny) Kod referencyjny przypisany do transakcji
     * @var string|null
     */
    protected ?string $referer = null;

    /**
     * Tajny klucz
     *
     * @var string
     */
    private string $secretPhrase;

    /**
     * @param string $secretPhrase
     * @param string $shopId
     * @param string $title
     * @param float $amountValue
     * @param Currency|string $amountCurrencyCode
     * @param string|null $description
     * @param string|null $additionalData
     * @throws InvalidCurrencyException
     * @Deprecated @param string $description
     */
    public function __construct(string $secretPhrase, string $shopId, string $title, float $amountValue, Currency|string $amountCurrencyCode, string $description = null, string $additionalData = null)
    {
        $this->secretPhrase = $secretPhrase;
        $this->shopId = $shopId;
        $this->title = $title;
        $this->amountValue = $amountValue;
        $this->description = $description;
        $this->additionalData = $additionalData;

        if (!$amountCurrencyCode instanceof Currency)
            $this->amountCurrencyCode = new Currency($amountCurrencyCode);
    }

    /**
     * @return string Sygnatura potwierdzająca prawidłowość wysyłanych danych
     */
    public function __toString()
    {
        $args = [
            'title'                  => $this->getTitle(),
            'amount.value'           => (string) $this->getAmountValue(),
            'amount.currencyCode'    => (string) $this->getAmountCurrencyCode(),
            'returnUrl'              => $this->getReturnUrl(),
            'description'            => $this->getDescription(),
            'negativeReturnUrl'      => $this->getNegativeReturnUrl(),
            'additionalData'         => $this->getAdditionalData(),
            'paymentChannel'         => (string) $this->getChannel(),
            'languageCode'           => $this->getLanguage(),
            'referer'                => $this->getReferer(),
            'personalData.firstName' => $this->getFirstName(),
            'personalData.surname'   => $this->getSurname(),
            'personalData.email'     => $this->getEmail(),
            'personalData.country'   => $this->getCountry(),
            'personalData.city'      => $this->getCity(),
            'personalData.postcode'  => $this->getPostcode(),
            'personalData.street'    => $this->getStreet(),
            'personalData.house'     => $this->getHouse(),
            'personalData.flat'      => $this->getFlat(),
            'secretPhrase'           => $this->getSecretPhrase()
        ];

        return sha1(implode("", $args));
    }

    /**
     * @return string Identyfikator sklepu
     */
    public function getShopId(): string
    {
        return $this->shopId;
    }

    /**
     * @return string|null Adres powrotu przeglądarki klienta po pozytywnym zakończeniu płatności
     */
    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }

    /**
     * @param string|null $returnUrl Adres powrotu przeglądarki klienta po pozytywnym zakończeniu płatności
     */
    public function setReturnUrl(?string $returnUrl): void
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @return string|null Adres powrotu przeglądarki klienta po negatywnym zakończeniu transakcji
     */
    public function getNegativeReturnUrl(): ?string
    {
        return $this->negativeReturnUrl;
    }

    /**
     * @param string|null $negativeReturnUrl Adres powrotu przeglądarki klienta po negatywnym zakończeniu transakcji
     */
    public function setNegativeReturnUrl(?string $negativeReturnUrl): void
    {
        $this->negativeReturnUrl = $negativeReturnUrl;
    }

    /**
     * @return string|null Kod referencyjny przypisany do transakcji
     */
    public function getReferer(): ?string
    {
        return $this->referer;
    }

    /**
     * @param string|null $referer Kod referencyjny przypisany do transakcji
     */
    public function setReferer(?string $referer): void
    {
        $this->referer = $referer;
    }

    /**
     * @return string Tajny klucz
     */
    public function getSecretPhrase(): string
    {
        return $this->secretPhrase;
    }


    /**
     * Ustawia preferowany kanał płatności, niektóre kanały płatności mogą wymagać dodatkowo
     * parametry opcjonalne np. e-mail lub imię i nazwisko. Do poprawnego wybrania kanału płatności
     * wystarczy podać jego id jako string
     *
     * @param Channel|null|string $paymentChannel Wybrany kanał płatności
     */
    public function setChannel(Channel|string|null $paymentChannel): void
    {
        $this->paymentChannel = $paymentChannel;
    }

    /**
     * @param string|null $firstName Imię
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param string|null $surname Nazwisko
     */
    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }


    /**
     * @param string|null $email Adres e-mail
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string|null $country Państwo
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @param string|null $city Miasto
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @param string|null $postcode Kod pocztowy
     */
    public function setPostcode(?string $postcode): void
    {
        $this->postcode = $postcode;
    }

    /**
     * @param string|null $street Ulica
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * @param string|null $house Budynek
     */
    public function setHouse(?string $house): void
    {
        $this->house = $house;
    }

    /**
     * @param string|null $flat Mieszkanie
     */
    public function setFlat(?string $flat): void
    {
        $this->flat = $flat;
    }
}