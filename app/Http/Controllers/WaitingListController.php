<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaitingList;

class WaitingListController extends Controller {

    public function index() {
        $user = auth()->user();

        $waiting   = WaitingList::where('user_id', $user->id)->where('status','waiting')->orderBy('created_at')->get();
        $contacted = WaitingList::where('user_id', $user->id)->where('status','contacted')->orderByDesc('updated_at')->get();
        $booked    = WaitingList::where('user_id', $user->id)->where('status','booked')->orderByDesc('updated_at')->take(10)->get();

        return view('waiting.index', compact('waiting','contacted','booked'));
    }

    public function store(Request $request) {
        $request->validate([
            'owner_name'     => 'required|string|max:100',
            'owner_phone'    => 'required|string|max:20',
            'pet_name'       => 'required|string|max:100',
            'preferred_date' => 'nullable|date',
            'reason'         => 'nullable|string|max:200',
        ]);

        WaitingList::create([
            'user_id'        => auth()->id(),
            'owner_name'     => $request->owner_name,
            'owner_phone'    => $request->owner_phone,
            'pet_name'       => $request->pet_name,
            'preferred_date' => $request->preferred_date,
            'reason'         => $request->reason,
            'status'         => 'waiting',
        ]);

        return back()->with('success', $request->owner_name . ' bekleme listesine eklendi.');
    }

    public function updateStatus(Request $request, WaitingList $item) {
        abort_if($item->user_id !== auth()->id(), 403);
        $request->validate(['status' => 'required|in:waiting,contacted,booked']);
        $item->update(['status' => $request->status]);
        return back()->with('success', 'Durum güncellendi.');
    }

    public function destroy(WaitingList $item) {
        abort_if($item->user_id !== auth()->id(), 403);
        $item->delete();
        return back()->with('success', 'Kayıt silindi.');
    }
}