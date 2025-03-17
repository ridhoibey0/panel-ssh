<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class LicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    if ($request->ajax()) {
       $user_id = auth()->id();
        $licenses = License::where('user_id', $user_id)->get();
        return DataTables::of($licenses)
            ->addColumn('action', function ($license) {
                return '<a href="'.route('license.show', $license->name).'" class="btn btn-sm btn-primary mx-2">Details</a>'.
                    '<button class="btn btn-sm btn-danger" onclick="deleteLicense('.$license->id.')">Delete</button>';
            })
            ->addColumn('auto_renew', function($license){
                $renew = $license->auto_renew == 1 ? 'checked' : '';
                return '<div class="media">
                <div class="media-body text-end switch-sm">
                  <label class="switch">
                    <input type="checkbox" data-license-id="'. $license->id .'" '. $renew .'><span class="switch-state"></span>
                  </label>
                </div>
              </div>';
            })
            ->addColumn('rand', function () {
                return rand(1000, 9999);
              })
            ->addColumn('status_badge', function ($license) {
                $statusLabel = $license->status;
                $badgeClass = $license->status === 'active' ? 'badge-light-success' :
                            ($license->status === 'suspend' ? 'badge-warning' :
                            ($license->status === 'expired' ? 'badge-danger' : 'badge-secondary'));

                return '<span class="badge ' . $badgeClass . '">' . Str::upper($statusLabel) . '</span>';
            })
            ->rawColumns(['status_badge','auto_renew','action','rand'])
            ->toJson();
    }

    return view('pages.users.license.index');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $setting = Setting::first();
        // $setting->update([
        //     'api_price' => json_encode([
        //         "prices" => [
        //             "30 Days" => 30,
        //             "60 Days" => 60,
        //             "90 Days" => 90,
        //         ]
        //     ])
        // ]);
        // return $setting;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|alpha_num|min:5|max:20|unique:mysql2.licenses',
            'ip' => 'required|ipv4|unique:mysql2.licenses',
            'expired_on' => 'required|in:30,60,90'
        ]);

        if ($validator->fails()) {
            return array(
                'status' => 'error',
                'message' => $validator->errors()->first(),
            );
        }

        $requestData = $request->all();
        $expirationDate = Carbon::now()->addDays($requestData['expired_on']);
        $price = $requestData['expired_on'] * 500;
        $userId = Auth::user()->id;
        $randName = 'vipssh-'. generateRandom(10);
        $apikey = 'VIPSSH.NET-'. Str::upper(generateRandom(10));
        try {
            DB::beginTransaction();
            // Check user's balance
            $userBalance = Auth::user()->balance;
            if ($userBalance < $price) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient balance.'
                ]);
            }

            // Deduct the price from user's balance
            Auth::user()->update([
                'balance' => $userBalance - $price,
            ]);

            License::create([
                'user_id' => $userId,
                'name' => $randName,
                'username' => $requestData['username'],
                'ip' => $requestData['ip'],
                'x_api_key' => $apikey,
                'price' => $price,
                'expired_on' => $expirationDate,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data is valid and processed successfully.',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['status' => 'error','message' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
     public function show($name)
    {
        $license = License::where('name', $name)->firstOrFail();
        return view('pages.users.license.show', ['license' => $license]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $license = License::find($id);

        if (!$license) {
            return response()->json(['status' => 'error','message' => 'License not found']);
        }

        // Check if the license status is "expired"
        if ($license->expired_on && Carbon::now()->greaterThanOrEqualTo($license->expired_on)) {
            return response()->json(['status' => 'error','message' => 'License is expired. Cannot update.']);
        }

        // Proceed with the update if the license is not expired
        $license->update([
            'auto_renew' => $request->input('auto_renew', 0),
        ]);

        return response()->json(['status' => 'success','message' => 'Auto Renew updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $license = $user->licenses()->find($id);
    
        if (!$license) {
            return response()->json(['status' => 'error','message' => 'License not found']);
        }
    
        $license->delete();
    
        return response()->json(['status' => 'success', 'message' => 'License deleted successfully']);
    }
}
