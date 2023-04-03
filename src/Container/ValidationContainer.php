<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\Payment\Cashbill\Container;

/**
 * Zawiera dane przesłane w żądaniu od Cashbill do twojej aplikacji
 */
class ValidationContainer
{
    /**
     * Lista nazwa komunikatu (składające się tylko z małych znaków) oraz wiadomość opisująca komunikat
     */
    public const STATUS = [
        'PreStart' => 'Płatność została rozpoczęta, klient nie wybrał jeszcze kanału płatności.',
        'Start' => 'Płatość została rozpoczęta, klient nie dokonał jeszcze wpłaty.',
        'NegativeAuthorization' => 'Operator płatności odmówił autoryzacji płatności.',
        'Abort' => 'Klient zrezygnował z dokonania płatności, status jest ostateczny i nie może ulec zmianie.',
        'Fraud' => ',Operator płatności określił transakcje jako próbę wyłudzenia, jej realizacja zostaje wstrzymana. Status jest ostateczny i nie może ulec zmianie.',
        'PositiveAuthorization' => 'Transakcja została wstępnie pozytywnie autoryzowana przez operatora płatności, jej ostateczny status zostanie określony w późniejszym okresie.',
        'PositiveFinish' => 'Operator płatności ostatecznie pozytywnie potwierdził poprawność przeprowadzonej płatności, status jest ostateczny i nie może ulec zmianie.',
        'NegativeFinish' => 'Operator płatności ostatecznie nie potwierdził poprawności przeprowadzonej płatności, status jest ostateczny i nie może ulec zmianie.',
        'TimeExceeded' => 'Czas na wykonanie transakcji, status jest ostateczny i nie może ulec zmianie.',
        'CriticalError' => 'Błąd krytyczny, status jest ostateczny i nie może ulec zmianie.',
    ];

    /**
     * Tajny klucz.
     *
     * @var string
     */
    private string $secretPhrase;

    /**
     * Nazwa komunikatu
     *
     * @var string
     */
    private string $cmd;

    /**
     * Atrybuty komunikatu przedzielone znakiem: ","
     *
     * @var string
     */
    private string $args;

    /**
     * Podpis wykonany przy pomocy funkcji MD5
     *
     * @var string
     */
    private string $sign;

    /**
     * @param string $secretPhrase Tajny klucz
     * @param string $cmd Nazwa atrybutu
     * @param string $args Argumenty atrybutu
     * @param string $sign Suma kontrolna zwrócona przez cashbill
     */
    public function __construct(string $secretPhrase, string $cmd, string $args, string $sign)
    {
        $this->secretPhrase = $secretPhrase;
        $this->cmd = $cmd;
        $this->args = $args;
        $this->sign = $sign;
    }

    /**
     * Generuje sumę kontrolną, która powinna być identyczna z wartością $sign
     *
     * @return string
     */
    public function __toString()
    {
        $args = [
            $this->cmd,
            $this->args,
            $this->secretPhrase
        ];

        return md5(implode("", $args));
    }

    /**
     * Tajny klucz.
     *
     * @return string
     */
    public function getSecretPhrase(): string
    {
        return $this->secretPhrase;
    }

    /**
     * Nazwa komunikatu
     *
     * @return string
     */
    public function getCmd(): string
    {
        return $this->cmd;
    }

    /**
     * Lista atrybutów komunikatu
     *
     * @return array
     */
    public function getArgs(): array
    {
        return explode(",", $this->args);

    }

    /**
     * Podpis wykonany przy pomocy funkcji MD5
     *
     * @return string
     */
    public function getSign(): string
    {
        return $this->sign;
    }


}