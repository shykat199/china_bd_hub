<?php

const CITY_ARRAY = [
    'ঢাকা',
    'গাজীপুর',
    'নারায়ণগঞ্জ',
    'নরসিংদী',
    'মুন্সিগঞ্জ',
    'মানিকগঞ্জ',
    'টাঙ্গাইল',
    'ফরিদপুর',
    'গোপালগঞ্জ',
    'মাদারীপুর',
    'রাজবাড়ী',
    'শরীয়তপুর',
    'চট্টগ্রাম',
    'কুমিল্লা',
    'ফেনী',
    'ব্রাহ্মণবাড়িয়া',
    'নোয়াখালী',
    'চাঁদপুর',
    'লক্ষ্মীপুর',
    'কক্সবাজার',
    'রাঙামাটি',
    'খাগড়াছড়ি',
    'বান্দরবান',
    'রাজশাহী',
    'নাটোর',
    'নওগাঁ',
    'চাঁপাইনবাবগঞ্জ',
    'পাবনা',
    'বগুড়া',
    'জয়পুরহাট',
    'সিরাজগঞ্জ',
    'খুলনা',
    'যশোর',
    'সাতক্ষীরা',
    'মেহেরপুর',
    'চুয়াডাঙ্গা',
    'ঝিনাইদহ',
    'নড়াইল',
    'বাগেরহাট',
    'মাগুরা',
    'বরিশাল',
    'ভোলা',
    'পটুয়াখালী',
    'ঝালকাঠি',
    'পিরোজপুর',
    'বরগুনা',
    'সিলেট',
    'মৌলভীবাজার',
    'হবিগঞ্জ',
    'সুনামগঞ্জ',
    'রংপুর',
    'দিনাজপুর',
    'ঠাকুরগাঁও',
    'পঞ্চগড়',
    'নীলফামারী',
    'লালমনিরহাট',
    'কুড়িগ্রাম',
    'গাইবান্ধা',
    'ময়মনসিংহ',
    'জামালপুর',
    'শেরপুর',
    'নেত্রকোনা',
];

use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;
use App\Modules\Backend\SellerManagement\Entities\Seller;

function SellerNotification($user_id, $id, $url, $message) {
    $notify = [
        'id' => $id,
        'url' => $url,
        'message' => $message,
    ];
    $notify_user = Seller::find($user_id);
    Notification::send($notify_user, new SendNotification($notify));
}

function AdminNotification($id, $url, $message) {
    $notify = [
        'id' => $id,
        'url' => $url,
        'message' => $message,
    ];
    $notify_user = App\Models\Backend\Admin::where('is_active', 1)->first();
    Notification::send($notify_user, new SendNotification($notify));
}
