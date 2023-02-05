
# Paratika PHP Package

Bu paket ile amaçlanan Paratika ödeme yöntemi ile PHP üzerinden ödeme alınmasını sağlamaktır.


## Installation

```bash
composer require umuttaymaz/paratika-php
```

## Örnek Ödeme Kodu

```php
<?php
require './vendor/autoload.php';

//API kullanıcı bilgileri girilir
$account = new \Umuttaymaz\ParatikaPhp\Models\Account(
    '10000000', //Merchant
    'apiuser@testmerchant.com', //MerchantUser
    'Pluto321`', //MerchantPassword
    true // testMode
);

//Paratika sınıfı oluşturulur
$paratika = new \Umuttaymaz\ParatikaPhp\Gateway\Paratika($account);

//Kredi Kartı sınıfı oluşturulur
$card = new \Umuttaymaz\ParatikaPhp\Models\Card(
    'Aydonat Aydınlar', //CardHolderName
    '4022774022774026', //CardNumber
    '2030', //CardExpirationYear
    '12', //CardExpirationMonth
    '000' //CardCVV
);

//Müşteri sınıfı oluşturulur
$customer = new \Umuttaymaz\ParatikaPhp\Models\Customer(
    uniqid('Cust-', true), //Customer ID
    'Aydonat Aydınlar', //Customer Name
    'mghUzjPn@email.com', //Customer Email
    '127.0.0.1', //Customer IP
    '+903120000011', //Customer Phone
);

//Sipariş Sınıfı Oluşturulur
$order = new \Umuttaymaz\ParatikaPhp\Models\Order(
    uniqid('MPID-', true), //Order ID
    '1000', //Amount
    'TRY', //Currency
    '1', //Installment
    'https://test.paratika.com.tr/merchant/index.jsp' //returnURL
);

//Sipariş İçerisine Ürünler Eklenir
$order->addOrderItem(
    'T00D3AITCC', //Code
    'Galaxy S8+', //Name
    'The Samsung Galaxy S8 is Android smartphone produced by Samsung Electronics as part of the Samsung Galaxy S series.', //Description
    '1', //Quantity
    '1000' //Amount
);

//Paratika sınıfı içerisinde Order, Card ve Customer sınıfları girilerek hazırlanır
$paratika->prepare('SALE', $order, $card, $customer);

//Redirect edilmesi beklenen değerler geri dönülür
$formData = $paratika->get3DFormData();
```


## Yol Haritası

- Örnek Kodlar hazırlanacak
- Dokümantasyon hazırlanacak
- SALE dışındaki transaction işlemleri geliştirilecek
- UnitTest yazılacak


## Authors

- [@umuttaymaz](https://www.github.com/umuttaymaz)


## License

[MIT](https://choosealicense.com/licenses/mit/)

