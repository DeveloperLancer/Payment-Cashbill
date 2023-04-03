<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Cashbill\Helper;

/**
 * Odpowiada za zunifikowanie sposobu przechowywania i zapisywania kodu języka dla kanału płatności.
 */
trait LanguageTrait
{
    /**
     * Kod języka.
     * Dostępne wartości to: PL lub EN, lub null.
     *
     * @var string|null
     */
    protected ?string $language = null;

    /**
     * Kod języka dla kanału płatności.
     *
     * @return string|null PL lub EN
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * Ustawia kod języka.
     * Dostępne wartości to PL lub EN, jeżeli zostanie podana inna wartość, wówczas kod języka przyjmie wartość null.
     * Jeżeli wartość zostanie podana w małych literach wówczas zostanie zmieniona na duże czyli pl -> PL.
     *
     * @param string|null $language Dostępne wartości to PL lub EN
     */
    public function setLanguage(?string $language): void
    {
        if (!$language) {
            $this->language = $language;
            return;
        }

        $language = strtoupper($language);
        if ($language) {
            if (!in_array($language, ['PL', 'EN']))
                $language = null;
        }
        $this->language = $language;
    }
}