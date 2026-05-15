<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'clinic_name'=> 'required|string|max:150',
            'slug'       => [
                'required','string','max:50','min:3',
                'unique:users,slug',
                'regex:/^[a-z0-9\-]+$/',
            ],
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'required|string|max:20',
            'password'   => 'required|confirmed|min:8',
            'plan'       => 'required|in:bronze,silver,gold',
        ], [
            'slug.unique'  => 'Bu klinik adresi zaten alınmış. Başka bir tane deneyin.',
            'slug.regex'   => 'Klinik adresi sadece küçük harf, rakam ve tire (-) içerebilir.',
            'slug.min'     => 'Klinik adresi en az 3 karakter olmalıdır.',
            'email.unique' => 'Bu e-posta adresi zaten kayıtlı.',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'clinic_name'       => $request->clinic_name,
            'slug'              => $request->slug,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => Hash::make($request->password),
            'subscription_plan' => $request->plan,
            'is_active'         => true,
        ]);

        auth()->login($user);

        return redirect()->route('dashboard.'.$user->subscription_plan)
            ->with('success', 'Hoş geldiniz! Kliniğiniz oluşturuldu. Randevu sayfanız: vetapp.tr/'.$user->slug);
    }

    // AJAX: slug müsaitlik kontrolü
    public function checkSlug(Request $request)
    {
        $slug = Str::slug($request->slug);
        $available = !User::where('slug', $slug)->exists();
        return response()->json([
            'available' => $available,
            'slug'      => $slug,
        ]);
    }
}