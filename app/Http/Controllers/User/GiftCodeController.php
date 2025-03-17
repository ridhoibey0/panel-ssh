<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GiftCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class GiftCodeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $code = GiftCode::where('email', Auth()->user()->email)->get();
            return DataTables::of($code)
                ->addColumn('user', function ($code) {
                    if ($code->user) {
                        return '<span class="badge badge-success">'. $code->user->name .'</span>';
                    } else {
                        return 'User Not Found';
                    }
                })
                ->addColumn('rand', function () {
                    return rand(1000, 9999);
                })
                ->addColumn('created_at', function ($code) {
                        return \Carbon\Carbon::parse($code->created_at)->format('Y-m-d H:i:s');
                 })
                ->addColumn('redeem', function ($code) {
                    if($code->is_redeemed === 1) {
                        return '<span class="badge badge-success">Redeemed</span>';
                    } else {
                        return '<span class="badge badge-warning">False</span>';
                    }
                })
                ->rawColumns(['user','redeem', 'rand','created_at'])
                ->toJson();
        }

        return view('pages.users.gift.index');
    }

    public function createGiftCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nominal' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid input']);
        }

        $value = $request->input('nominal');

        $user = Auth()->user();
        if ($user->balance < $value) {
            return response()->json(['status' => 'error','message' => 'Saldo tidak mencukupi untuk membuat gift code']);
        }

        $siteName = getSettings('site_name');
        $code = strtoupper($siteName) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);

        $giftCode = new GiftCode();
        $giftCode->code = $code;
        $giftCode->value = $value;
        $giftCode->email = $user->email;
        $giftCode->save();

        $user->balance -= $value;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Gift code berhasil dibuat dan saldo berhasil dipotong',
            'code' => $this->code($code)
        ]);
    }

    public function redeem(Request $request)
    {
        if ($request->ajax()) {
            $code = GiftCode::where('user_id', Auth()->user()->id)->get();
            return DataTables::of($code)
                ->addColumn('user', function ($code) {
                    if ($code->user) {
                        return '<span class="badge badge-success">'. $code->user->name .'</span>';
                    } else {
                        return 'User Not Found';
                    }
                })
                ->addColumn('rand', function () {
                    return rand(1000, 9999);
                })
                ->addColumn('update_at', function ($code) {
                        return \Carbon\Carbon::parse($code->update_at)->format('Y-m-d H:i:s');
                 })
                ->addColumn('redeem', function ($code) {
                    if($code->is_redeemed === 1) {
                        return '<span class="badge badge-success">Redeemed</span>';
                    } else {
                        return '<span class="badge badge-warning">False</span>';
                    }
                })
                ->rawColumns(['user','redeem', 'rand','update_at'])
                ->toJson();
        }
        
        return view('pages.users.gift.redeem');
    }

    public function redeemGiftCode(Request $request)
    {
        $code = $request->input('code');
        $user = Auth()->user();
        $giftCode = GiftCode::where('code', $code)->first();

        if (!$giftCode) {
            return response()->json(['status' => 'error', 'message' => 'Gift code tidak valid']);
        }

        if ($giftCode->is_redeemed) {
            return response()->json(['status' => 'error', 'message' => 'Gift code sudah digunakan sebelumnya']);
        }
        $user->balance += $giftCode->value;
        $user->save();

        $giftCode->is_redeemed = true;
        $giftCode->user_id = $user->id;
        $giftCode->save();

        return response()->json(['status' => 'success', 'message' => 'Gift code berhasil digunakan dan saldo berhasil ditambahkan']);
    }

    public static function code($code)
    {
        $data = '<div class="clipboaard-container">
        <h5 class="f-light mb-3 font-success">Success Create Gift Code</h5>
        <input class="form-control" id="clipboardExample1" type="text" value="' .$code. '" placeholder="Gift Code">
        <div class="mt-3 text-end">
          <button class="btn btn-primary btn-clipboard" type="button" data-clipboard-action="copy" data-clipboard-target="#clipboardExample1"><i class="fa fa-copy"></i> Copy</button>
          <button class="btn btn-secondary btn-clipboard-cut" type="button" data-clipboard-action="cut" data-clipboard-target="#clipboardExample1"><i class="fa fa-cut"></i> Cut</button>
        </div>
    </div>';
    return $data;
    }
}
