VetApp: Veteriner Klinik Yönetim Sistemi
VetApp, veteriner kliniklerinin operasyonel süreçlerini dijitalleştirmek için Laravel framework'ü kullanılarak geliştirilmiş kapsamlı bir web tabanlı yönetim sistemidir. Hasta (evcil hayvan) kayıtlarından randevu takibine, tıbbi geçmiş yönetiminden stok takibine kadar tüm süreçleri tek bir platformda birleştirir.

Temel Özellikler
Hasta ve Sahip Yönetimi: Evcil hayvan sahiplerinin ve hastaların detaylı kayıtları, tür/ırk bilgileri ve fotoğraf arşivi.

Randevu Sistemi: Dinamik randevu takvimi, doktor bazlı programlama ve durum (bekliyor, tamamlandı, iptal) yönetimi.

Tıbbi Kayıtlar (E-Karne): Geçmiş muayene notları, aşı takipleri, laboratuvar sonuçları ve reçete geçmişinin dijital ortamda saklanması.

Yönetim Paneli: Klinik istatistiklerini, günlük randevu yoğunluğunu ve aktif hasta sayısını gösteren kapsamlı Dashboard.

Rol ve Yetkilendirme: Yönetici ve Veteriner Hekim rollerine göre özelleştirilmiş erişim kontrolleri.

Teknik Mimari
Backend: Laravel 10+ / PHP 8.1+

Veritabanı: MySQL (İlişkisel veritabanı mimarisi, Eloquent ORM)

Frontend: Blade Template Engine, Bootstrap 5 ve Custom CSS3

Güvenlik: Laravel Middleware, CSRF koruması ve Bcrypt şifreleme.

Veritabanı İlişkileri
Proje içerisinde kurulan temel ilişkiler:

One-to-Many: Kullanıcı (Hekim) ve Randevular.

One-to-Many: Hayvan Sahibi ve Evcil Hayvanlar.

Many-to-Many: Muayene Kayıtları ve Tedavi Türleri.

Kurulum
Repoyu klonlayın: git clone https://github.com/kullaniciadi/vetapp.git

Bağımlılıkları yükleyin: composer install

.env dosyasını yapılandırın ve veritabanı bilgilerini girin.

Uygulama anahtarını oluşturun: php artisan key:generate

Veritabanı tablolarını oluşturun: php artisan migrate

Projeyi ayağa kaldırın: php artisan serve

Geliştirici Bilgileri
Ad Soyad: Oğul Aydın Çözük

Geliştirme Ortamı: Laravel Web Environment

Proje Durumu: Yayında / Geliştirilmeye Devam Ediyor
