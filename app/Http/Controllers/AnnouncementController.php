<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;

class AnnouncementController extends Controller {

    public function index() {
        $user = auth()->user();
        $pets = Pet::where('user_id', $user->id)->get();

        $speciesList = Pet::where('user_id', $user->id)
            ->distinct()->pluck('species');

        $totalPets = $pets->count();

        return view('announcements.index', compact('pets','speciesList','totalPets'));
    }

    public function store(Request $request) {
        $request->validate([
            'message'  => 'required|string|max:500',
            'channel'  => 'required|in:whatsapp,sms',
            'audience' => 'required|in:all,species',
        ]);

        // Gerçek uygulamada burada WhatsApp/SMS API çağrısı yapılır
        // Şimdilik sadece başarı mesajı döndürüyoruz

        $audienceLabel = $request->audience === 'all' ? 'tüm müşterilere' : $request->species . ' sahiplerine';
        $channelLabel  = $request->channel === 'whatsapp' ? 'WhatsApp' : 'SMS';

        return back()->with('success', "Duyuru $channelLabel ile $audienceLabel gönderildi.");
    }
}