# Payment Cashbill

## Przykładowa implementacja

### Tworzenie nowej płatności

```php
<?php
require_once '/vendor/autoload.php';

//Kontener który, przechowuje wszystkie możliwe dane przekazywane podczas płatności
$container = new \DevLancer\Payment\API\Cashbill\Container\PaymentContainer(
    "secretPhrase", //tajny klucz
    "shopId", //Identyfikator sklepu
    "title", //Tytuł płatności
    2.53, //amountValue - kwota płatności
    "PLN", //amountCurrencyCode - waluta płatności,
    "additionalData", //dodatkowe informacje przekazywane w płatności, można je potem pobrać
);
//Oprócz danych w konstruktorze można jeszcze podać dane opcjonalne używając settera

$cashbill = new \DevLancer\Payment\API\Cashbill\Cashbill();
$payment = $cashbill->generatePayment($container); //Generowana jest nowa płatność
if (is_null($payment)) {
    echo "Generowanie płatności się nie powiodło";
    //Informacje na temat niepowodzenia można uzyskać odwołując się do metody:
    //$response = $cashbill->getResponse();
    //echo (string) $response->getBody(); 
    exit();
}

//Tutaj opcjonalnie można ustawić adres URL na który zostanie przekierowany klient gdy
//płatność się powiedzie lub gdy się nie powiedzie.
//Do adresu można na przykład dodać identyfikator płatności
//$orderId = $payment->getOrderId();
$successURL = "https://your-page.pl/success";
$failURL = "https://your-page.pl/fail";
$isUpdated = $payment->updateReturnUrls($successURL, $failURL); //Aktualizacja URL
if ($isUpdated === false) {
    //Aktualizacja adresów się nie powiodła.
    //Informacje na temat niepowodzenia można uzyskać, odwołując się do metody:
    //$response = $payment->getResponseUpdateReturnUrls();
    //echo (string) $response->getBody(); 
}

//Teraz należy przekierować klienta na adres transakcji
header(sprintf("Location: %s", $payment->getRedirectUrl()));
```

### Weryfikowanie statusu płatności

CashBill komunikuje się z systemem sklepu przy pomocy usługi powiadamiania.
Adres URL, na którym została ona uruchomiona po stronie sklepu, musi zostać określony podczas zgłoszenia
uruchomienia usługi i jest niezmienny dla każdej transakcji (w przeciwieństwie do adresów URL
powrotu przeglądarki klienta).

Przykładowe powiadomienie:

```
http://adres_sklepu/kom.html?cmd=transactionStatusChanged&args=asd34sf&sign=c8143d45bf2f76fd38a6a9d77feb1a79
```

Przykładowy query adresu:

```
?cmd=transactionStatusChanged&args=asd34sf&sign=c8143d45bf2f76fd38a6a9d77feb1a79
```

Zostaje automatycznie dołączony przez Cashbill służy do weryfikowanie stanu płatności

Poniższy kod należy zaimplementować na stronie, która została określona dla usługi powiadamiania.

```php
<?php
require_once '/vendor/autoload.php';


$container = new \DevLancer\Payment\API\Cashbill\Container\ValidationContainer(
    "secretPhrase", //Tajny klucz.
    $_GET['cmd'], //Nazwa komunikatu.
    $_GET['args'], //Atrybuty komunikatu.
    $_GET['sign'], //Suma kontrolna w MD5
);

$cashbill = new \DevLancer\Payment\API\Cashbill\Cashbill();
$printResponse = true; //Jeżeli true, automatycznie zostanie udzielona odpowiedź dla cashbill tzn. "OK"

$service = $cashbill->paymentNotification($container, $printResponse);

if (is_null($service)) {
    //Oznacza to, że sumy kontrolne nie są równe i nie można uwiarygodnić żądania
    exit();
}
//Udało się zweryfikować żądanie, teraz należy pobrać identyfikator płatności:
$orderId = $service->getOrderId();
//Używając identyfikatora płatności można pobrać informacje o transakcji i sprawdzić jej status
```

### Pobieranie informacji o transakcji

Można pobrać informacje o wygenerowanej wcześniej transakcji,
wymagane do tego jest identyfikator płatności.

```php
<?php
require_once '/vendor/autoload.php';
$container = new \DevLancer\Payment\API\Cashbill\Container\TransactionInfoContainer(
    "secretPhrase", //Tajny klucz.
    "shopId", //Identyfikator sklepu,
    "orderId", //Identyfikator płatności
);

$cashbill = new \DevLancer\Payment\API\Cashbill\Cashbill();
$transactionInfo = $cashbill->getTransactionInfo($container);
if (is_null($transactionInfo)) {
    echo "Nie udało pobrać się informacji o transakcji";
    //Informacje na temat niepowodzenia można uzyskać odwołując się do metody:
    //$response = $cashbill->getResponse();
    //echo (string) $response->getBody(); 
    exit();
}


if ($transactionInfo->isSuccessful()) {
    //Transakcja dobiegła końca i płatność została wykonana
    //$transactionInfo->getAdditionalData(); dodatkowe informacje zamieszczone w transakcji podczas jej generowania
} else {
    //Oznacza to, że transakcja ma inny status od TransactionInfo::SUCCESS_STATUS
    //Lista statusów z opisem: NotificationContainer::STATUS
}
 ```