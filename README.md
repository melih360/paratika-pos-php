
# Paratika Laravel Package

Bu paket Paratika ödeme yöntemi ile Laravel üzerinden ödeme alınmasını sağlamaktır.
<br />
Not: umuttaymaz/paratika-php reposundan forklanarak php paketinin Laravel ile daha uyumlu çalışması sağlanmıştır.



## Installation

```bash
composer require melih360/paratika-pos-php
```

config dosyasını paylaşmak için
```bash
php artisan vendor:publish --tag=paratika-config
```

## Örnek env
PARATIKA_MERCHANT_ID=
<br/>PARATIKA_API_USER=
<br/>PARATIKA_API_PASSWORD=
<br/>PARATIKA_TEST_MODE=

## Örnek Ödeme Kodu
```php
<?php

//Paratika sınıfı oluşturulur
$paratika = new Paratika();

//Kredi Kartı sınıfı oluşturulur
$card = new Card(
    'Aydonat Aydınlar', //CardHolderName
    '4022774022774026', //CardNumber
    '2030', //CardExpirationYear
    '12', //CardExpirationMonth
    '000' //CardCVV
);

//Müşteri sınıfı oluşturulur
$customer = new Customer(
    uniqid('Cust-', true), //Customer ID
    'Aydonat Aydınlar', //Customer Name
    'mghUzjPn@email.com', //Customer Email
    '127.0.0.1', //Customer IP
    '+903120000011', //Customer Phone
);

//Sipariş Sınıfı Oluşturulur
$order = new Order(
    uniqid('MPID-', true), //Order ID  -> required
    '1000', //Amount -> required
    'TRY', //Currency  -> default TRY
    '1', //Installment -> default 1
);

//Sipariş İçerisine Ürünler Eklenir
$order->addOrderItem(
    'T00D3AITCC', //Code
    'Galaxy S8+', //Name
    'The Samsung Galaxy S8 is Android smartphone produced by Samsung Electronics as part of the Samsung Galaxy S series.', //Description
    '1', //Quantity
    '1000' //Per amount
);

//Paratika sınıfı içerisinde Order, Card ve Customer sınıfları girilerek hazırlanır
$paratika->prepare('SALE', $order, $card, $customer);

//Redirect edilmesi beklenen değerler geri dönülür
$formData = $paratika->get3DFormData();
```

## İşlem Sorgulama

```php
$paratika = new Paratika();
$paratika->queryTransaction(); // merchantPaymentId değeri optinal olarak sorgu yapılabilir
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

