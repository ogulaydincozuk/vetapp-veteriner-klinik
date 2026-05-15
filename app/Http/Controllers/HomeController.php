<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $packages = [
            [
                'icon'     => '🥉',
                'name'     => 'Bronz Paket',
                'price'    => '1.000',
                'limit'    => '50 randevu/ay',
                'sms'      => 'SMS ek ücretli',
                'featured' => false,
                'features' => [
                    'Online randevu sistemi',
                    'Çoklu hayvan yönetimi',
                    'Randevu geçmişi & arşiv',
                    'Harita entegrasyonu',
                    'WhatsApp bildirimleri ücretsiz',
                ],
            ],
             [
                'icon'     => '🥇',
                'name'     => 'Altın Paket',
                'price'    => '4.800',
                'limit'    => 'Sınırsız randevu',
                'sms'      => 'SMS sınırsız',
                'featured' => true,
                'features' => [
                    'Gümüş\'teki tüm özellikler',
                    'Ameliyat takvimi',
                    'Tedavi planı yönetimi',
                    'Bekleme listesi',
                    'Memnuniyet anketi & yorum isteme',
                    'Çoklu doktor desteği',
                    'Gelişmiş raporlar & analitik',
                ],
            ],
            [
                'icon'     => '🥈',
                'name'     => 'Gümüş Paket',
                'price'    => '2.500',
                'limit'    => '150 randevu/ay',
                'sms'      => 'SMS ek ücretli',
                'featured' => false,
                'features' => [
                    'Bronz\'daki tüm özellikler',
                    'WhatsApp hat entegrasyonu',
                    'Kilo takip grafiği',
                    'Dijital aşı kartı',
                    'Röntgen & rapor yükleme',
                    'Özel gün indirimleri',
                    'Toplu duyuru gönderme',
                ],
            ]
           
        ];

        $smsPackages = [
            ['amount' => '100',   'price' => '60'],
            ['amount' => '500',   'price' => '250'],
            ['amount' => '1.000', 'price' => '450'],
            ['amount' => '5.000', 'price' => '2.000'],
        ];

        $features = [
            ['emoji' => '🐾', 'title' => 'Çoklu Hayvan Yönetimi',  'desc' => 'Tek hesapta sınırsız hayvan profili. Her hayvanın geçmişi, aşı kartı ve notları ayrı ayrı saklanır.'],
            ['emoji' => '💉', 'title' => 'Dijital Aşı Kartı',       'desc' => 'Hayvan pasaportu müşterinin telefonuna gönderilir. Aşı tarihleri otomatik hatırlatılır.'],
            ['emoji' => '📊', 'title' => 'Kilo Takip Grafiği',      'desc' => 'Obezite ve sağlık takibi için görsel grafikler. Kilo değişimlerini kolayca izleyin.'],
            ['emoji' => '💬', 'title' => 'WhatsApp İletişim',       'desc' => 'Mesajla randevu oluşturma, fotoğraf gönderme ve anlık bildirimler tek tuşla.'],
            ['emoji' => '📸', 'title' => 'Röntgen & Rapor Yükleme', 'desc' => 'Her hayvana özel bulut depolama. Görüntüler ve raporlar her zaman erişilebilir.'],
            ['emoji' => '⏰', 'title' => 'Ameliyat Takvimi',        'desc' => 'Ameliyat öncesi ve sonrası hatırlatmalar. Takvim çakışmalarını önleyin.'],
            ['emoji' => '📝', 'title' => 'Memnuniyet Anketi',       'desc' => 'Düşük puanda anında bildirim alın. Müşteri memnuniyetini gerçek zamanlı takip edin.'],
            ['emoji' => '🔄', 'title' => 'Bekleme Listesi',         'desc' => 'İptallerde bekleyen hasta otomatik olarak aranır. Boş slot bırakmayın, gelir kaybetmeyin.'],
        ];

        $whyUs = [
            ['icon' => '🏥', 'title' => 'Veterinerlere Özel',  'desc' => 'Genel randevu sistemleri değil — sektöre özgü ihtiyaçlar için sıfırdan tasarlandı.'],
            ['icon' => '📱', 'title' => 'Mobil Uyumlu',        'desc' => 'Kliniğinizi telefonunuzdan, tabletinizden, masaüstünden yönetin. Her yerden erişim.'],
            ['icon' => '💰', 'title' => 'Ek Gelir Sağlar',     'desc' => 'Unutulan randevulara son. Otomatik hatırlatmalar ve bekleme listesiyle kayıp geliri geri kazanın.'],
            ['icon' => '🆘', 'title' => '7/24 Destek',         'desc' => 'Teknik ekibimiz haftanın 7 günü, günün 24 saati yanınızda. Sorun anında çözüm.'],
        ];

        return view('pages.home', compact('packages', 'smsPackages', 'features', 'whyUs'));
    }
    public function demoStore(Request $request)
{
    $request->validate([
        'name'   => 'required|string|max:100',
        'clinic' => 'required|string|max:150',
        'phone'  => 'required|string|max:20',
        'email'  => 'required|email|max:100',
    ]);

    // Burada mail gönderme veya DB'ye kaydetme yapılabilir
    // Mail::to('info@vetapp.com')->send(new DemoRequest($request->all()));

    return redirect()->route('home')->with('success', 'Talebiniz alındı! En kısa sürede sizi arayacağız.');
}
public function storeDemo(Request $request)
{
    // Burada gelen verileri valide edebilir (validate) 
    // ve veritabanına veya mail olarak gönderebilirsin.
    
    return back()->with('success', 'Demo talebiniz başarıyla alındı! En kısa sürede sizinle iletişime geçeceğiz.');
}
}