# Event Booking API

Etkinlik biletleme ve rezervasyon sistemi için RESTful API.

## 🚀 Özellikler

- Etkinlik yönetimi (CRUD operasyonları)
- Koltuk rezervasyonu ve biletleme
- Bilet transfer işlemleri
- Admin ve kullanıcı rolleri
- JWT tabanlı kimlik doğrulama
- Otomatik rezervasyon iptali (süresi dolan rezervasyonlar için)

## 🛠 Teknolojiler

- PHP 8.1
- Laravel 10.x
- MySQL 8.0
- JWT Authentication
- PHPUnit for testing

## 📋 Gereksinimler

- PHP >= 8.1
- Composer
- MySQL

## ⚙️ Kurulum

1. Projeyi klonlayın:

```bash
git clone https://github.com/yourusername/event-booking-api.git
cd event-booking-api
```

2. Bağımlılıkları yükleyin:

```bash
composer install
```

3. .env dosyasını oluşturun:

```bash
cp .env.example .env
```

4. .env dosyasını düzenleyin:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_booking
DB_USERNAME=root
DB_PASSWORD=
```

5. Uygulama anahtarını oluşturun:

```bash
php artisan key:generate
```

6. JWT secret key oluşturun:

```bash
php artisan jwt:secret
```

7. Veritabanını oluşturun:

```bash
php artisan migrate --seed
```

## 🚀 Çalıştırma

```bash
php artisan serve
```

API http://localhost:8000 adresinde çalışacaktır.

## 📝 API Dokümantasyonu

API endpoint'leri Postman collection'da detaylı olarak belgelenmiştir. Collection'ı `postman/` klasöründe bulabilirsiniz.

### Temel Endpoint'ler

#### Auth
- `POST /api/auth/register` - Kayıt olma
- `POST /api/auth/login` - Giriş yapma
- `POST /api/auth/logout` - Çıkış yapma

#### Events
- `GET /api/events` - Etkinlik listesi
- `POST /api/events` - Etkinlik oluşturma (Admin)
- `GET /api/events/{id}` - Etkinlik detayı

#### Reservations
- `POST /api/reservations` - Rezervasyon oluşturma
- `GET /api/reservations` - Rezervasyon listesi
- `POST /api/reservations/{id}/confirm` - Rezervasyon onaylama

#### Tickets
- `GET /api/tickets` - Bilet listesi
- `POST /api/tickets/transfer` - Bilet transfer etme
- `GET /api/tickets/{code}/download` - Bilet indirme


## 🔑 Test Kullanıcıları

Admin Kullanıcı:
- Email: admin@example.com
- Password: password

Normal Kullanıcı:
- Email: merve@example.com
- Password: password

## 🧪 Tests

Proje için yazılmış unit testler:

### Event Service Tests
- ✓ Should create event successfully
- ✓ Should update event successfully
- ✓ Should delete event successfully
- ✓ Should get event with venue
- ✓ Should throw exception when event not found

### Reservation Service Tests
- ✓ Should create reservation successfully
- ✓ Should confirm reservation successfully
- ✓ Should cancel reservation successfully
- ✓ Should throw exception when seats not available
- ✓ Should throw exception when event not found
- ✓ Should throw exception when reservation expired

### Seat Service Tests
- ✓ Should block seats successfully
- ✓ Should release seats successfully
- ✓ Should throw exception when seats already taken

Testleri çalıştırmak için:
```bash
# Tüm testleri çalıştır
php artisan test

# Belirli bir test sınıfını çalıştır
php artisan test --filter ReservationServiceTest

# Belirli bir test metodunu çalıştır
php artisan test --filter ReservationServiceTest::test_should_create_reservation_successfully
```




