<?php

namespace App\Services\Account;

use App\Models\Account;
use App\Models\Setting;
use App\Models\AccountTrial;
use App\Models\User;
use App\Services\LogService;
use App\Jobs\SendPurchaseEmail;
use App\Traits\Account\ResponseAccounts;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AccountService
{
    use ResponseAccounts;
    private $client;
    private $url;
    private $headers;
    private $body;
    private $site;
    private $limit;
    private $trial_limit;
    private $timelimit;

    public function __construct()
    {
        $this->headers = [
            'Content-Type' => 'application/json',
            'Level' => 'Admin'
        ];
        $this->site = Setting::first();
        $this->limit = (!isset(json_decode($this->site->tunnel)->limit)) ? 0 : json_decode($this->site->tunnel)->limit;
        $this->trial_limit = (!isset(json_decode($this->site->tunnel)->trial_limit)) ? 0 : json_decode($this->site->tunnel)->trial_limit;
        $this->timelimit = (!isset(json_decode($this->site->tunnel)->timelimit)) ? 0 : json_decode($this->site->tunnel)->timelimit;
    }

    private function checkUsername($username)
    {
        // cek username
        $rules = [
            'ssh',
            'vps',
            'daemon',
            'bin', 'sys', 'sync', 'games', 'man', 'lp', 'mail', 'news', 'uucp', 'proxy', 'www-data', 'backup', 'list', 'irc', 'gnats', 'nobody', 'systemd-timesync', 'systemd-network', 'systemd-resolve', 'systemd-bus-proxy', 'unscd', 'ntp', '_apt', 'messagebus', 'bind', 'stunnel4', 'sshd'
        ];
        // check username exist in rules
        if (in_array($username, $rules)) {
            return false;
        }
    }

    private function validateAndCheckBalance($request, $server)
    {
        // $validator = Validator::make($request->all(), [
        //     'username' => 'required|regex:/^[a-zA-Z0-9_]+$/|min:4|max:15',
        //     'password' => 'required|regex:/^[a-zA-Z0-9_]+$/|min:4|max:15',
        // ]);

        // if ($validator->fails()) {
        //     return $this->validationErrorResponse($validator);
        // }

        if ($server->status == 'offline') {
            return array(
                'status' => 'error',
                'message' => 'Server Offline'
            );
        }

        if ($this->checkUsername($request->username)) {
            return $this->usernameNotAllowedResponse();
        }

        $check = Account::where('username', $request->username)
            ->where('server_id', $server->id)
            ->first();

        if ($check != null) {
            return [
                'status' => 'error',
                'message' => 'Username already exists',
            ];
        }

        if ($server->limit <= $server->current) {
            return $this->serverFullResponse();
        }

        return null;
    }

    public function createSSHAccount($server, $request)
    {
        // Validate the input data from the request
        $validatedData = Validator::make($request->all(), [
            'username' => 'required|regex:/^[a-zA-Z0-9_]+$/|min:4|max:15',
            'password' => 'required|regex:/^[a-zA-Z0-9_]+$/|min:4|max:15',
            'metode' => 'required|in:1,2,3',
        ]);
    
        if ($validatedData->fails()) {
            return $this->validationErrorResponse($validatedData);
        }
    
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
        }
    
        $validationResult = $this->validateAndCheckBalance($request, $server);
        if ($validationResult !== null) {
            return $validationResult;
        }
    
        $user = Auth::user();
        $price = null;
    
        switch ($request->metode) {
            case 1:
                $price = $server->price->price_monthly;
                break;
            case 2:
                $price = $server->price->price_hourly;
                break;
            case 3:
                return $this->trialSSHAccount($server, $request);
        }
    
        if ($user->balance < $price) {
            return $this->insufficientBalanceResponse();
        }
    
        return $this->buySSHAccount($server, $request);
    }

    public function createTrojanAccount($server, $request)
    {
        // Validate the input data from the request
        $validatedData = Validator::make($request->all(), [
            'username' => 'required|regex:/^[a-zA-Z0-9_]+$/|min:4|max:15',
            'metode' => 'required|in:1,2,3',
        ]);
    
        if ($validatedData->fails()) {
            return $this->validationErrorResponse($validatedData);
        }
    
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
        }
    
        $validationResult = $this->validateAndCheckBalance($request, $server);
        if ($validationResult !== null) {
            return $validationResult;
        }
    
        $user = Auth::user();
        $price = null;
    
        switch ($request->metode) {
            case 1:
                $price = $server->price->price_monthly;
                break;
            case 2:
                $price = $server->price->price_hourly;
                break;
            case 3:
                return $this->trialTrojanAccount($server, $request);
        }
    
        if ($user->balance < $price) {
            return $this->insufficientBalanceResponse();
        }
    
        return $this->buyTrojanAccount($server, $request);
    }

    public function createVmessAccount($server, $request)
    {
        // Validate the input data from the request
        $validatedData = Validator::make($request->all(), [
            'username' => 'required|regex:/^[a-zA-Z0-9_]+$/|min:4|max:15',
            'metode' => 'required|in:1,2,3',
        ]);
    
        if ($validatedData->fails()) {
            return $this->validationErrorResponse($validatedData);
        }
    
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
        }
    
        $validationResult = $this->validateAndCheckBalance($request, $server);
        if ($validationResult !== null) {
            return $validationResult;
        }
    
        $user = Auth::user();
        $price = null;
    
        switch ($request->metode) {
            case 1:
                $price = $server->price->price_monthly;
                break;
            case 2:
                $price = $server->price->price_hourly;
                break;
            case 3:
                return $this->trialVmessAccount($server, $request);
        }
    
        if ($user->balance < $price) {
            return $this->insufficientBalanceResponse();
        }
    
        return $this->buyVmessAccount($server, $request);
    }

    public function createVlessAccount($server, $request)
    {
        // Validate the input data from the request
        $validatedData = Validator::make($request->all(), [
            'username' => 'required|regex:/^[a-zA-Z0-9_]+$/|min:4|max:15',
            'metode' => 'required|in:1,2,3',
        ]);
    
        if ($validatedData->fails()) {
            return $this->validationErrorResponse($validatedData);
        }
    
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
        }
    
        $validationResult = $this->validateAndCheckBalance($request, $server);
        if ($validationResult !== null) {
            return $validationResult;
        }
    
        $user = Auth::user();
        $price = null;
    
        switch ($request->metode) {
            case 1:
                $price = $server->price->price_monthly;
                break;
            case 2:
                $price = $server->price->price_hourly;
                break;
            case 3:
                return $this->trialVlessAccount($server, $request);
        }
    
        if ($user->balance < $price) {
            return $this->insufficientBalanceResponse();
        }
    
        return $this->buyVlessAccount($server, $request);
    }

    public function createShadowsocksAccount($server, $request)
    {
        // Validate the input data from the request
        $validatedData = Validator::make($request->all(), [
            'username' => 'required|regex:/^[a-zA-Z0-9_]+$/|min:4|max:15',
            'metode' => 'required|in:1,2,3',
        ]);
    
        if ($validatedData->fails()) {
            return $this->validationErrorResponse($validatedData);
        }
    
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
        }
    
        $validationResult = $this->validateAndCheckBalance($request, $server);
        if ($validationResult !== null) {
            return $validationResult;
        }
    
        $user = Auth::user();
        $price = null;
    
        switch ($request->metode) {
            case 1:
                $price = $server->price->price_monthly;
                break;
            case 2:
                $price = $server->price->price_hourly;
                break;
            case 3:
                return $this->trialShadowsocksAccount($server, $request);
        }
    
        if ($user->balance < $price) {
            return $this->insufficientBalanceResponse();
        }
    
        return $this->buyShadowsocksAccount($server, $request);
    }

    public function createSocksAccount($server, $request)
    {
        // Validate the input data from the request
        $validatedData = Validator::make($request->all(), [
            'username' => 'required|regex:/^[a-zA-Z0-9_]+$/|min:4|max:15',
            'metode' => 'required|in:1,2,3',
        ]);
    
        if ($validatedData->fails()) {
            return $this->validationErrorResponse($validatedData);
        }
    
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
        }
    
        $validationResult = $this->validateAndCheckBalance($request, $server);
        if ($validationResult !== null) {
            return $validationResult;
        }
    
        $user = Auth::user();
        $price = null;
    
        switch ($request->metode) {
            case 1:
                $price = $server->price->price_monthly;
                break;
            case 2:
                $price = $server->price->price_hourly;
                break;
            case 3:
                return $this->trialSocksAccount($server, $request);
        }
    
        if ($user->balance < $price) {
            return $this->insufficientBalanceResponse();
        }
    
        return $this->buySocksAccount($server, $request);
    }

    private function buySSHAccount($server, $request)
    {
        $tunnel = json_decode($this->site->tunnel);
        // if (!empty($tunnel->username)) {
        //     $username = $tunnel->username . $request->username;
        // } else {
            $username = $request->username;
        // }

        // Get password from client input
        $password = $request->password;

        $this->headers['Authorization'] = 'Bearer '.$server->token;
        $this->url = "http://" . $server->host . "/vps/sshvpn";
        $this->body = [
            "username" => Str::lower($username),
            "password" => Str::lower($password),
            "limitip" => $server->limit,
            "expired" => intval(360)
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);

        // Start the database transaction
        DB::beginTransaction();

        try {
            $response = $this->client->post($this->url, [
                'json' => $this->body,
            ]);
            
            $responseBody = $response->getBody()->getContents();
            // $output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents());
            $output = json_decode($responseBody, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON decode error: ' . json_last_error_msg());
                return response()->json(['error' => 'Invalid response data'], 500);
            }
            
            // Log::info('Response : ', $output);
            $expiryDate = $request->metode == 1 ? Carbon::now()->addDays(30) : ($request->metode == 2 ? Carbon::now()->addHours(1) : Carbon::now()->addHours(1));
            
            $account = Account::create([
                'user_id' => auth()->user()->id,
                'server_id' => $server->id,
                'username' => Str::lower($username),
                'password' => $request->password,
                'tipe' => $request->metode,
                'expired_at' => $expiryDate,
                'detail' => json_encode($output['data']),
                'charge' => $request->metode == 1 ? $server->price->price_monthly : ($request->metode == 2 ? $server->price->price_hourly : 0),
            ]);
            // Log::info('Masuk Sini 1');
            $user = User::find(Auth::user()->id);
            switch ($request->metode) {
                case 1:
                    $user->balance -= $server->price->price_monthly;
                    $user->save();
                    break;

                case 2:
                    $user->balance -= $server->price->price_hourly;
                    $user->save();
                    break;
                default:
                    return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
                    break;
            }

            $server->current += 1;
            $server->total += 1;
            $server->update();
            
            $mode = $request->metode;
            $modeText = ($mode == 1) ? 'Monthly' : (($mode == 2) ? 'Hourly' : 'Trial');
            $price = ($mode == 1) ? $server->price->price_monthly : (($mode == 2) ? $server->price->price_hourly : 0);
            
            $purchaseDetails = [
                'name' => $user->name,
                'servername' => $server->name,
                'serverhost' => $server->host,
                'username' => $account->username,
                'password' => $account->password,
                'type' => $account->tipe,
                'created_date' => $account->created_at,
                'expired_date' => $account->expired_at,
                'charge' => $request->metode == 1 ? $server->price->price_monthly : ($request->metode == 2 ? $server->price->price_hourly : 0),
                'price' => $price,
                'user_balance' => $user->balance,
                'purchase_mode' => $modeText,
                'email' => $user->email
            ];
            
            SendPurchaseEmail::dispatch($purchaseDetails);
            
            $log = new LogService();
            $log->create(Auth::id(), "success", "bx bx-user-plus", "Create SSH $modeText", "Create SSH $modeText #" . Str::lower($username), $price);
            


            // Commit the database transaction
            DB::commit();

            $data = $this->responseSSHAccounts($output, $account);
            
            $message = "New Transactions\n━━━━━━━━━━━━━━━━━━━\nUser : " . $user->name . "\nUsername : " . $request->username . "\nDetail : Pembelian Akun" . $server->category->name . "\nType : " . $expiryDate . "\n";
            if ($request->metode == 1) {
                $message .= "Durasi : " . $expiryDate . " Bulan" . "\n";
            }
            $message .= "Server : " . $server->name . "\n━━━━━━━━━━━━━━━━━━━\nPanel : " . getSettings('site_url');
            $message .= "
                Server : " . $server->name . "\n
                ━━━━━━━━━━━━━━━━━━━
                Panel : " . getSettings('site_url');

            // Uncomment the telegram send message when ready to use
            // try {
            //     $this->telegram->sendTelegramMessage($message);
            // } catch (\Exception $e) {
            //     // Handle the Telegram message sending exception if needed
            // }

            return array(
                'status' => 'success',
                'message' => 'Account created',
                'output' => $data,
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];   
        } catch (\Exception $e) {
             Log::error('Error triggering PurchaseCompleted event: ' . $e->getMessage());
            DB::rollBack();
        
            // Cari string JSON yang ada dalam pesan kesalahan menggunakan regex
            $pattern = '/{.*}/';
            preg_match($pattern, $e->getMessage(), $matches);
            $jsonString = $matches[0] ?? '';
        
            // Menguraikan string JSON menjadi array asosiatif
            $data = json_decode($jsonString, true);
        
            // Mengambil nilai 'status' dari array $data
            $status = isset($data['status']) ? $data['status'] : 'error';
        
            return array(
                'status' => 'error',
                'message' => $status,
            );
        }
    }

    private function buyVmessAccount($server, $request)
    {
        $tunnel = json_decode($this->site->tunnel);
        // if (!empty($tunnel->username)) {
        //     $username = $tunnel->username . $request->username;
        // } else {
            $username = $request->username;
        // }

        // Get password from client input
        $password = $request->password;

        $this->headers['Authorization'] = 'Bearer '.$server->token;
        $this->url = "http://" . $server->host . "/vps/vmessall";
        $this->body = [
            "kuota" => $server->limit,
            "limitip" => $server->limit,
            "username" => Str::lower($username),
            "expired" => intval(360)
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);

        // Start the database transaction
        DB::beginTransaction();

        try {
            $response = $this->client->post($this->url, [
                'json' => $this->body,
            ]);

            $output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents());
            $output = json_decode($output, true);

            $expiryDate = $request->metode == 1 ? Carbon::now()->addDays(30) : ($request->metode == 2 ? Carbon::now()->addHours(1) : Carbon::now()->addHours(1));

            $account = Account::create([
                'user_id' => auth()->user()->id,
                'server_id' => $server->id,
                'username' => Str::lower($username),
                'tipe' => $request->metode,
                'expired_at' => $expiryDate,
                'detail' => json_encode($output['data']),
                'charge' => $request->metode == 1 ? $server->price->price_monthly : ($request->metode == 2 ? $server->price->price_hourly : 0),
            ]);

            $user = User::find(Auth::user()->id);
            switch ($request->metode) {
                case 1:
                    $user->balance -= $server->price->price_monthly;
                    $user->save();
                    break;

                case 2:
                    $user->balance -= $server->price->price_hourly;
                    $user->save();
                    break;

                default:
                    return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
                    break;
            }

            
            $server->current += 1;
            $server->total += 1;
            $server->update();
            
            // Counting Total Accounts
            $total_accounts = $this->site->total_accounts;
            $total_accounts['vmess'] = $total_accounts['vmess'] + 1;
            $this->site->total_accounts = $total_accounts;
            $this->site->update();
            
            $mode = $request->metode;
            $modeText = ($mode == 1) ? 'Monthly' : (($mode == 2) ? 'Hourly' : 'Trial');
            $price = ($mode == 1) ? $server->price->price_monthly : (($mode == 2) ? $server->price->price_hourly : 0);
            
            $purchaseDetails = [
                'name' => $user->name,
                'servername' => $server->name,
                'serverhost' => $server->host,
                'username' => $account->username,
                'password' => $output['data']['UUID'],
                'type' => $account->tipe,
                'created_date' => $account->created_at,
                'expired_date' => $account->expired_at,
                'charge' => $request->metode == 1 ? $server->price->price_monthly : ($request->metode == 2 ? $server->price->price_hourly : 0),
                'price' => $price,
                'user_balance' => $user->balance,
                'purchase_mode' => $modeText,
                'email' => $user->email
            ];
            
            SendPurchaseEmail::dispatch($purchaseDetails);
            
            $log = new LogService();
            $log->create(Auth::id(), "success", "bx bx-user-plus", "Buy VMess $modeText", "Buy VMess $modeText #" . Str::lower($username), $price);

            // Commit the database transaction
            DB::commit();

            $data = $this->responseVmessAccounts($output, $account);

            $message = "New Transactions\n━━━━━━━━━━━━━━━━━━━\nUser : " . $user->name . "\nUsername : " . $request->username . "\nDetail : Pembelian Akun" . $server->category->name . "\nType : " . $expiryDate . "\n";
            if ($request->metode == 1) {
                $message .= "Durasi : " . $expiryDate . " Bulan" . "\n";
            }
            $message .= "Server : " . $server->name . "\n━━━━━━━━━━━━━━━━━━━\nPanel : " . getSettings('site_url');
            $message .= "
                Server : " . $server->name . "\n
                ━━━━━━━━━━━━━━━━━━━
                Panel : " . getSettings('site_url');

            // Uncomment the telegram send message when ready to use
            // try {
            //     $this->telegram->sendTelegramMessage($message);
            // } catch (\Exception $e) {
            //     // Handle the Telegram message sending exception if needed
            // }

            return array(
                'status' => 'success',
                'message' => 'Account created',
                'output' => $data,
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];  
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an exception
            DB::rollBack();
        
            // Cari string JSON yang ada dalam pesan kesalahan menggunakan regex
            $pattern = '/{.*}/';
            preg_match($pattern, $e->getMessage(), $matches);
            $jsonString = $matches[0] ?? '';
        
            // Menguraikan string JSON menjadi array asosiatif
            $data = json_decode($jsonString, true);
        
            // Mengambil nilai 'status' dari array $data
            $status = isset($data['status']) ? $data['status'] : 'error';
        
            return array(
                'status' => 'error',
                'message' => $e->getMessage(),
            );
        }        
    }

    private function buyVlessAccount($server, $request)
    {
        $tunnel = json_decode($this->site->tunnel);
        // if (!empty($tunnel->username)) {
        //     $username = $tunnel->username . $request->username;
        // } else {
            $username = $request->username;
        // }

        // Get password from client input
        $password = $request->password;

        $this->headers['Authorization'] = 'Bearer '.$server->token;
        $this->url = "http://" . $server->host . "/vps/vlessall";
        $this->body = [
            "kuota" => $server->limit,
            "limitip" => $server->limit,
            "username" => Str::lower($username),
            "expired" => intval(360)
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);

        // Start the database transaction
        DB::beginTransaction();

        try {
            $response = $this->client->post($this->url, [
                'json' => $this->body,
            ]);

            $output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents());
            $output = json_decode($output, true);

            $expiryDate = $request->metode == 1 ? Carbon::now()->addDays(30) : ($request->metode == 2 ? Carbon::now()->addHours(1) : Carbon::now()->addHours(1));

            $account = Account::create([
                'user_id' => auth()->user()->id,
                'server_id' => $server->id,
                'username' => Str::lower($username),
                'tipe' => $request->metode,
                'expired_at' => $expiryDate,
                'detail' => json_encode($output['data']),
                'charge' => $request->metode == 1 ? $server->price->price_monthly : ($request->metode == 2 ? $server->price->price_hourly : 0),
            ]);

            $user = User::find(Auth::user()->id);
            switch ($request->metode) {
                case 1:
                    $user->balance -= $server->price->price_monthly;
                    $user->save();
                    break;

                case 2:
                    $user->balance -= $server->price->price_hourly;
                    $user->save();
                    break;

                default:
                    return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
                    break;
            }

            
            $server->current += 1;
            $server->total += 1;
            $server->update();
            
            // Counting Total Accounts
            $total_accounts = $this->site->total_accounts;
            $total_accounts['vless'] = $total_accounts['vless'] + 1;
            $this->site->total_accounts = $total_accounts;
            $this->site->update();
            
            $mode = $request->metode;
            $modeText = ($mode == 1) ? 'Monthly' : (($mode == 2) ? 'Hourly' : 'Trial');
            $price = ($mode == 1) ? $server->price->price_monthly : (($mode == 2) ? $server->price->price_hourly : 0);
            
            
            $purchaseDetails = [
                'name' => $user->name,
                'servername' => $server->name,
                'serverhost' => $server->host,
                'username' => $account->username,
                'password' => $output['data']['UUID'],
                'type' => $account->tipe,
                'created_date' => $account->created_at,
                'expired_date' => $account->expired_at,
                'charge' => $request->metode == 1 ? $server->price->price_monthly : ($request->metode == 2 ? $server->price->price_hourly : 0),
                'price' => $price,
                'user_balance' => $user->balance,
                'purchase_mode' => $modeText,
                'email' => $user->email
            ];
            
            SendPurchaseEmail::dispatch($purchaseDetails);
            $log = new LogService();
            $log->create(Auth::id(), "success", "bx bx-user-plus", "Buy Vless $modeText", "Buy Vless $modeText #" . Str::lower($username), $price);

            // Commit the database transaction
            DB::commit();

            $data = $this->responseVlessAccounts($output, $account);

            $message = "New Transactions\n━━━━━━━━━━━━━━━━━━━\nUser : " . $user->name . "\nUsername : " . $request->username . "\nDetail : Pembelian Akun" . $server->category->name . "\nType : " . $expiryDate . "\n";
            if ($request->metode == 1) {
                $message .= "Durasi : " . $expiryDate . " Bulan" . "\n";
            }
            $message .= "Server : " . $server->name . "\n━━━━━━━━━━━━━━━━━━━\nPanel : " . getSettings('site_url');
            $message .= "
                Server : " . $server->name . "\n
                ━━━━━━━━━━━━━━━━━━━
                Panel : " . getSettings('site_url');

            // Uncomment the telegram send message when ready to use
            // try {
            //     $this->telegram->sendTelegramMessage($message);
            // } catch (\Exception $e) {
            //     // Handle the Telegram message sending exception if needed
            // }

            return array(
                'status' => 'success',
                'message' => 'Account created',
                'output' => $data,
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];  
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an exception
            DB::rollBack();
        
            // Cari string JSON yang ada dalam pesan kesalahan menggunakan regex
            $pattern = '/{.*}/';
            preg_match($pattern, $e->getMessage(), $matches);
            $jsonString = $matches[0] ?? '';
        
            // Menguraikan string JSON menjadi array asosiatif
            $data = json_decode($jsonString, true);
        
            // Mengambil nilai 'status' dari array $data
            $status = isset($data['status']) ? $data['status'] : 'error';
        
            return array(
                'status' => 'error',
                'message' => $e->getMessage(),
            );
        }        
    }

    private function buyTrojanAccount($server, $request)
    {
        $tunnel = json_decode($this->site->tunnel);
        // if (!empty($tunnel->username)) {
        //     $username = $tunnel->username . $request->username;
        // } else {
            $username = $request->username;
        // }

        // Get password from client input
        $password = $request->password;

        $this->headers['Authorization'] = 'Bearer '.$server->token;
        $this->url = "http://" . $server->host . "/vps/trojanall";
        $this->body = [
            "kuota" => $server->limit,
            "limitip" => $server->limit,
            "username" => Str::lower($username),
            "expired" => intval(360)
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);

        // Start the database transaction
        DB::beginTransaction();

        try {
            $response = $this->client->post($this->url, [
                'json' => $this->body,
            ]);

            $output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents());
            $output = json_decode($output, true);

            $expiryDate = $request->metode == 1 ? Carbon::now()->addDays(30) : ($request->metode == 2 ? Carbon::now()->addHours(1) : Carbon::now()->addHours(1));

            $account = Account::create([
                'user_id' => auth()->user()->id,
                'server_id' => $server->id,
                'username' => Str::lower($username),
                'tipe' => $request->metode,
                'expired_at' => $expiryDate,
                'detail' => json_encode($output['data']),
                'charge' => $request->metode == 1 ? $server->price->price_monthly : ($request->metode == 2 ? $server->price->price_hourly : 0),
            ]);

            $user = User::find(Auth::user()->id);
            switch ($request->metode) {
                case 1:
                    $user->balance -= $server->price->price_monthly;
                    $user->save();
                    break;

                case 2:
                    $user->balance -= $server->price->price_hourly;
                    $user->save();
                    break;

                default:
                    return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
                    break;
            }

            
            $server->current += 1;
            $server->total += 1;
            $server->update();
            
            // Counting Total Accounts
            $total_accounts = $this->site->total_accounts;
            $total_accounts['trojan'] = $total_accounts['trojan'] + 1;
            $this->site->total_accounts = $total_accounts;
            $this->site->update();
            
            $mode = $request->metode;
            $modeText = ($mode == 1) ? 'Monthly' : (($mode == 2) ? 'Hourly' : 'Trial');
            $price = ($mode == 1) ? $server->price->price_monthly : (($mode == 2) ? $server->price->price_hourly : 0);
            
            
            $purchaseDetails = [
                'name' => $user->name,
                'servername' => $server->name,
                'serverhost' => $server->host,
                'username' => $account->username,
                'password' => $output['data']['UUID'],
                'type' => $account->tipe,
                'created_date' => $account->created_at,
                'expired_date' => $account->expired_at,
                'charge' => $request->metode == 1 ? $server->price->price_monthly : ($request->metode == 2 ? $server->price->price_hourly : 0),
                'price' => $price,
                'user_balance' => $user->balance,
                'purchase_mode' => $modeText,
                'email' => $user->email
            ];
            
            SendPurchaseEmail::dispatch($purchaseDetails);
            $log = new LogService();
            $log->create(Auth::id(), "success", "bx bx-user-plus", "Buy Trojan $modeText", "Buy Trojan $modeText #" . Str::lower($username), $price);

            // Commit the database transaction
            DB::commit();

            $data = $this->responseTrojanAccounts($output, $account);

            $message = "New Transactions\n━━━━━━━━━━━━━━━━━━━\nUser : " . $user->name . "\nUsername : " . $request->username . "\nDetail : Pembelian Akun" . $server->category->name . "\nType : " . $expiryDate . "\n";
            if ($request->metode == 1) {
                $message .= "Durasi : " . $expiryDate . " Bulan" . "\n";
            }
            $message .= "Server : " . $server->name . "\n━━━━━━━━━━━━━━━━━━━\nPanel : " . getSettings('site_url');
            $message .= "
                Server : " . $server->name . "\n
                ━━━━━━━━━━━━━━━━━━━
                Panel : " . getSettings('site_url');

            // Uncomment the telegram send message when ready to use
            // try {
            //     $this->telegram->sendTelegramMessage($message);
            // } catch (\Exception $e) {
            //     // Handle the Telegram message sending exception if needed
            // }

            return array(
                'status' => 'success',
                'message' => 'Account created',
                'output' => $data,
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];  
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an exception
            DB::rollBack();
        
            // Cari string JSON yang ada dalam pesan kesalahan menggunakan regex
            $pattern = '/{.*}/';
            preg_match($pattern, $e->getMessage(), $matches);
            $jsonString = $matches[0] ?? '';
        
            // Menguraikan string JSON menjadi array asosiatif
            $data = json_decode($jsonString, true);
        
            // Mengambil nilai 'status' dari array $data
            $status = isset($data['status']) ? $data['status'] : 'error';
        
            return array(
                'status' => 'error',
                'message' => $e->getMessage(),
            );
        }        
    }
    
    private function buyShadowsocksAccount($server, $request)
    {
        $tunnel = json_decode($this->site->tunnel);
        if (!empty($tunnel->username)) {
            $username = $tunnel->username . $request->username;
        } else {
            $username = $request->username;
        }

        // Get password from client input
        $password = $request->password;

        $this->headers['X-API-KEY'] = $server->token;
        $this->url = "http://" . $server->host . ":14022/api/shadowsocks";
        $this->body = [
            "host" => $server->host,
            "username" => Str::lower($username),
            "sni" => $request->sni,
            "expired" => intval(360)
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);

        // Start the database transaction
        DB::beginTransaction();

        try {
            $response = $this->client->post($this->url, [
                'json' => $this->body,
            ]);

            $output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents());
            $output = json_decode($output, true);

            $expiryDate = $request->metode == 1 ? Carbon::now()->addDays(30) : ($request->metode == 2 ? Carbon::now()->addHours(1) : Carbon::now()->addHours(1));

            $account = Account::create([
                'user_id' => auth()->user()->id,
                'server_id' => $server->id,
                'username' => Str::lower($username),
                'tipe' => $request->metode,
                'expired_at' => $expiryDate,
                'detail' => json_encode($output['data']),
                'charge' => $request->metode == 1 ? $server->price->price_monthly : ($request->metode == 2 ? $server->price->price_hourly : 0),
            ]);

            $user = User::find(Auth::user()->id);
            switch ($request->metode) {
                case 1:
                    $user->balance -= $server->price->price_monthly;
                    $user->save();
                    break;

                case 2:
                    $user->balance -= $server->price->price_hourly;
                    $user->save();
                    break;

                default:
                    return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
                    break;
            }

            
            $server->current += 1;
            $server->total += 1;
            $server->update();
            
            // Counting Total Accounts
            $total_accounts = $this->site->total_accounts;
            $total_accounts['shadowsocks'] = $total_accounts['shadowsocks'] + 1;
            $this->site->total_accounts = $total_accounts;
            $this->site->update();
            
            $mode = $request->metode;
            $modeText = ($mode == 1) ? 'Monthly' : (($mode == 2) ? 'Hourly' : 'Trial');
            $price = ($mode == 1) ? $server->price->price_monthly : (($mode == 2) ? $server->price->price_hourly : 0);
            
            
            $purchaseDetails = [
                'name' => $user->name,
                'servername' => $server->name,
                'serverhost' => $server->host,
                'username' => $account->username,
                'password' => $output['data']['UUID'],
                'type' => $account->tipe,
                'created_date' => $account->created_at,
                'expired_date' => $account->expired_at,
                'charge' => $request->metode == 1 ? $server->price->price_monthly : ($request->metode == 2 ? $server->price->price_hourly : 0),
                'price' => $price,
                'user_balance' => $user->balance,
                'purchase_mode' => $modeText,
                'email' => $user->email
            ];
            
            SendPurchaseEmail::dispatch($purchaseDetails);
            $log = new LogService();
            $log->create(Auth::id(), "success", "bx bx-user-plus", "Buy Shadowsocks $modeText", "Buy Shadowsocks $modeText #" . Str::lower($username), $price);

            // Commit the database transaction
            DB::commit();

            $data = $this->responseShadowsocksAccounts($output, $account);

            $message = "New Transactions\n━━━━━━━━━━━━━━━━━━━\nUser : " . $user->name . "\nUsername : " . $request->username . "\nDetail : Pembelian Akun" . $server->category->name . "\nType : " . $expiryDate . "\n";
            if ($request->metode == 1) {
                $message .= "Durasi : " . $expiryDate . " Bulan" . "\n";
            }
            $message .= "Server : " . $server->name . "\n━━━━━━━━━━━━━━━━━━━\nPanel : " . getSettings('site_url');
            $message .= "
                Server : " . $server->name . "\n
                ━━━━━━━━━━━━━━━━━━━
                Panel : " . getSettings('site_url');

            // Uncomment the telegram send message when ready to use
            // try {
            //     $this->telegram->sendTelegramMessage($message);
            // } catch (\Exception $e) {
            //     // Handle the Telegram message sending exception if needed
            // }

            return array(
                'status' => 'success',
                'message' => 'Account created',
                'output' => $data,
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];  
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an exception
            DB::rollBack();
        
            // Cari string JSON yang ada dalam pesan kesalahan menggunakan regex
            $pattern = '/{.*}/';
            preg_match($pattern, $e->getMessage(), $matches);
            $jsonString = $matches[0] ?? '';
        
            // Menguraikan string JSON menjadi array asosiatif
            $data = json_decode($jsonString, true);
        
            // Mengambil nilai 'status' dari array $data
            $status = isset($data['status']) ? $data['status'] : 'error';
        
            return array(
                'status' => 'error',
                'message' => $e->getMessage(),
            );
        }        
    }

    private function trialSSHAccount($server, $request)
    {
        $trialData = AccountTrial::where('user_id', Auth::user()->id)->first();
        $trialLimit = $trialData ? $trialData->trial_limit : 0;
        if ($this->limit <= $trialLimit) {
            return array(
                'status' => 'error',
                'message' => 'Server Trial is full'
            );
        }

        // Get password from client input
        $username = $request->username;
        $password = $request->password;

        $this->headers['Authorization'] = 'Bearer '.$server->token;
        $this->url = "http://" . $server->host . "/vps/trialsshvpn";
        $this->body = [
            "timelimit" => '10m',
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);
        
        // Start the database transaction
        DB::beginTransaction();

        try {
            $response = $this->client->post($this->url, [
                'json' => $this->body,
            ]);
            $responseBody = $response->getBody()->getContents();
            // $output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents());
            $output = json_decode($responseBody, true);
            $expiryDate = Carbon::now()->addMinutes($this->timelimit);

            $account = Account::create([
                'user_id' => auth()->user()->id,
                'server_id' => $server->id,
                'username' => $request->username,
                'password' => $request->password,
                'tipe' => $request->metode,
                'expired_at' => $expiryDate,
                'detail' => json_encode($output['data']),
                'charge' =>  0,
            ]);

            $user = User::find(Auth::user()->id);

            $trialCreate = [
                'user_id' => $user->id,
            ];
            
            $trialData = AccountTrial::updateOrCreate(
                ['user_id' => $user->id], // Kolom identifier
                $trialCreate // Data yang akan disimpan atau diperbarui
            );
            
            $trialData->trial_limit += 1; // Menambahkan 1 pada trial_limit
            $trialData->save(); // Menyimpan perubahan pada trial_limit
            
            $mode = $request->metode;
            $modeText = ($mode == 1) ? 'Monthly' : (($mode == 2) ? 'Hourly' : 'Trial');
            $price = ($mode == 1) ? $server->price->price_monthly : (($mode == 2) ? $server->price->price_hourly : 0);
            
            
            $purchaseDetails = [
                'name' => $user->name,
                'servername' => $server->name,
                'serverhost' => $server->host,
                'username' => $account->username,
                'password' => $account->password,
                'type' => $account->tipe,
                'created_date' => $account->created_at,
                'expired_date' => $account->expired_at,
                'charge' => 0,
                'price' => $price,
                'user_balance' => $user->balance,
                'purchase_mode' => $modeText,
                'email' => $user->email
            ];
            
            SendPurchaseEmail::dispatch($purchaseDetails);
            $log = new LogService();
            $log->create(Auth::id(), "warning", "bx bx-user-plus", "Create SSH $modeText", "Create SSH $modeText #" . Str::lower($username), $price);

            // Commit the database transaction
            DB::commit();

            $data = $this->responseSSHAccounts($output, $account);

            $message = "New Transactions\n━━━━━━━━━━━━━━━━━━━\nUser : " . $user->name . "\nUsername : " . $request->username . "\nDetail : Pembelian Akun" . $server->category->name . "\nType : " . $expiryDate . "\n";
            if ($request->metode == 1) {
                $message .= "Durasi : " . $expiryDate . " Bulan" . "\n";
            }
            $message .= "Server : " . $server->name . "\n━━━━━━━━━━━━━━━━━━━\nPanel : " . getSettings('site_url');
            $message .= "
                Server : " . $server->name . "\n
                ━━━━━━━━━━━━━━━━━━━
                Panel : " . getSettings('site_url');

            // Uncomment the telegram send message when ready to use
            // try {
            //     $this->telegram->sendTelegramMessage($message);
            // } catch (\Exception $e) {
            //     // Handle the Telegram message sending exception if needed
            // }

            return array(
                'status' => 'success',
                'message' => 'Account created',
                'output' => $data,
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];  
        } catch (\Exception $e) {
            Log::error('Gagal request ke API: ' . $e->getMessage());
            // Rollback the database transaction in case of an exception
            DB::rollBack();
        
            // Cari string JSON yang ada dalam pesan kesalahan menggunakan regex
            $pattern = '/{.*}/';
            preg_match($pattern, $e->getMessage(), $matches);
            $jsonString = $matches[0] ?? '';
        
            // Menguraikan string JSON menjadi array asosiatif
            $data = json_decode($jsonString, true);
        
            // Mengambil nilai 'status' dari array $data
            $status = isset($data['status']) ? $data['status'] : 'error';
        
            return array(
                'status' => 'error',
                'message' => $status,
            );
        }
    }

    private function trialVmessAccount($server, $request)
    {
        $trialData = AccountTrial::where('user_id', Auth::user()->id)->first();
        $trialLimit = $trialData ? $trialData->trial_limit : 0;
        if ($this->limit <= $trialLimit) {
            return array(
                'status' => 'error',
                'message' => 'Server Trial is full'
            );
        }
        // Get password from client input
        $username = $request->username;
        $password = $request->password;

        $this->headers['Authorization'] = 'Bearer '.$server->token;
        $this->url = "http://" . $server->host . "/vps/trialvmessall";
        $this->body = [
            "timelimit" => "10m",
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);

        // Start the database transaction
        DB::beginTransaction();

        try {
            $response = $this->client->post($this->url, [
                'json' => $this->body,
            ]);

            $output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents());
            $output = json_decode($output, true);

            $expiryDate = Carbon::now()->addMinutes($this->timelimit);

            $account = Account::create([
                'user_id' => auth()->user()->id,
                'server_id' => $server->id,
                'username' => Str::lower($username),
                'tipe' => $request->metode,
                'expired_at' => $expiryDate,
                'detail' => json_encode($output['data']),
                'charge' =>  0,
            ]);

            $user = User::find(Auth::user()->id);

            $trialCreate = [
                'user_id' => $user->id,
            ];
            
            $trialData = AccountTrial::updateOrCreate(
                ['user_id' => $user->id], // Kolom identifier
                $trialCreate // Data yang akan disimpan atau diperbarui
            );
            
            $trialData->trial_limit += 1; // Menambahkan 1 pada trial_limit
            $trialData->save(); // Menyimpan perubahan pada trial_limit
            
            $mode = $request->metode;
            $modeText = ($mode == 1) ? 'Monthly' : (($mode == 2) ? 'Hourly' : 'Trial');
            $price = ($mode == 1) ? $server->price->price_monthly : (($mode == 2) ? $server->price->price_hourly : 0);
            
            
            $purchaseDetails = [
                'name' => $user->name,
                'servername' => $server->name,
                'serverhost' => $server->host,
                'username' => $account->username,
                'password' => $output['data']['UUID'],
                'type' => $account->tipe,
                'created_date' => $account->created_at,
                'expired_date' => $account->expired_at,
                'charge' => 0,
                'price' => $price,
                'user_balance' => $user->balance,
                'purchase_mode' => $modeText,
                'email' => $user->email
            ];
            
            SendPurchaseEmail::dispatch($purchaseDetails);
            $log = new LogService();
            $log->create(Auth::id(), "warning", "bx bx-user-plus", "Create VMess $modeText", "Create VMess $modeText #" . Str::lower($username), $price);

            // Commit the database transaction
            DB::commit();

            $data = $this->responseVmessAccounts($output, $account);

            $message = "New Transactions\n━━━━━━━━━━━━━━━━━━━\nUser : " . $user->name . "\nUsername : " . $request->username . "\nDetail : Pembelian Akun" . $server->category->name . "\nType : " . $expiryDate . "\n";
            if ($request->metode == 1) {
                $message .= "Durasi : " . $expiryDate . " Bulan" . "\n";
            }
            $message .= "Server : " . $server->name . "\n━━━━━━━━━━━━━━━━━━━\nPanel : " . getSettings('site_url');
            $message .= "
                Server : " . $server->name . "\n
                ━━━━━━━━━━━━━━━━━━━
                Panel : " . getSettings('site_url');

            // Uncomment the telegram send message when ready to use
            // try {
            //     $this->telegram->sendTelegramMessage($message);
            // } catch (\Exception $e) {
            //     // Handle the Telegram message sending exception if needed
            // }

            return array(
                'status' => 'success',
                'message' => 'Account created',
                'output' => $data,
                'riwayat' => $output,
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];   
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an exception
            DB::rollBack();
        
            // Cari string JSON yang ada dalam pesan kesalahan menggunakan regex
            $pattern = '/{.*}/';
            preg_match($pattern, $e->getMessage(), $matches);
            $jsonString = $matches[0] ?? '';
        
            // Menguraikan string JSON menjadi array asosiatif
            $data = json_decode($jsonString, true);
        
            // Mengambil nilai 'status' dari array $data
            $status = isset($data['status']) ? $data['status'] : 'error';
        
            return array(
                'status' => 'error',
                'message' => $status,
            );
        }
    }

    private function trialVlessAccount($server, $request)
    {
        $trialData = AccountTrial::where('user_id', Auth::user()->id)->first();
        $trialLimit = $trialData ? $trialData->trial_limit : 0;
        if ($this->limit <= $trialLimit) {
            return array(
                'status' => 'error',
                'message' => 'Server Trial is full'
            );
        }
        // Get password from client input
        $username = $request->username;
        $password = $request->password;

        $this->headers['Authorization'] = 'Bearer '.$server->token;
        $this->url = "http://" . $server->host . "/vps/trialvlessall";
        $this->body = [
            "timelimit" => "10m",
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);

        // Start the database transaction
        DB::beginTransaction();

        try {
            $response = $this->client->post($this->url, [
                'json' => $this->body,
            ]);

            $output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents());
            $output = json_decode($output, true);

            $expiryDate = Carbon::now()->addMinutes($this->timelimit);

            $account = Account::create([
                'user_id' => auth()->user()->id,
                'server_id' => $server->id,
                'username' => $request->username,
                'tipe' => $request->metode,
                'expired_at' => $expiryDate,
                'detail' => json_encode($output['data']),
                'charge' =>  0,
            ]);

            $user = User::find(Auth::user()->id);

            $trialCreate = [
                'user_id' => $user->id,
            ];
            
            $trialData = AccountTrial::updateOrCreate(
                ['user_id' => $user->id], // Kolom identifier
                $trialCreate // Data yang akan disimpan atau diperbarui
            );
            
            $trialData->trial_limit += 1; // Menambahkan 1 pada trial_limit
            $trialData->save(); // Menyimpan perubahan pada trial_limit
            
            $mode = $request->metode;
            $modeText = ($mode == 1) ? 'Monthly' : (($mode == 2) ? 'Hourly' : 'Trial');
            $price = ($mode == 1) ? $server->price->price_monthly : (($mode == 2) ? $server->price->price_hourly : 0);
            
            
            $purchaseDetails = [
                'name' => $user->name,
                'servername' => $server->name,
                'serverhost' => $server->host,
                'username' => $account->username,
                'password' => $output['data']['UUID'],
                'type' => $account->tipe,
                'created_date' => $account->created_at,
                'expired_date' => $account->expired_at,
                'charge' => 0,
                'price' => $price,
                'user_balance' => $user->balance,
                'purchase_mode' => $modeText,
                'email' => $user->email
            ];
            
            SendPurchaseEmail::dispatch($purchaseDetails);
            $log = new LogService();
            $log->create(Auth::id(), "warning", "bx bx-user-plus", "Create Vless $modeText", "Create Vless $modeText #" . Str::lower($username), $price);

            // Commit the database transaction
            DB::commit();

            $data = $this->responseVlessAccounts($output, $account);

            $message = "New Transactions\n━━━━━━━━━━━━━━━━━━━\nUser : " . $user->name . "\nUsername : " . $request->username . "\nDetail : Pembelian Akun" . $server->category->name . "\nType : " . $expiryDate . "\n";
            if ($request->metode == 1) {
                $message .= "Durasi : " . $expiryDate . " Bulan" . "\n";
            }
            $message .= "Server : " . $server->name . "\n━━━━━━━━━━━━━━━━━━━\nPanel : " . getSettings('site_url');
            $message .= "
                Server : " . $server->name . "\n
                ━━━━━━━━━━━━━━━━━━━
                Panel : " . getSettings('site_url');

            // Uncomment the telegram send message when ready to use
            // try {
            //     $this->telegram->sendTelegramMessage($message);
            // } catch (\Exception $e) {
            //     // Handle the Telegram message sending exception if needed
            // }

            return array(
                'status' => 'success',
                'message' => 'Account created',
                'output' => $data,
                'riwayat' => $output,
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];   
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an exception
            DB::rollBack();
        
            // Cari string JSON yang ada dalam pesan kesalahan menggunakan regex
            $pattern = '/{.*}/';
            preg_match($pattern, $e->getMessage(), $matches);
            $jsonString = $matches[0] ?? '';
        
            // Menguraikan string JSON menjadi array asosiatif
            $data = json_decode($jsonString, true);
        
            // Mengambil nilai 'status' dari array $data
            $status = isset($data['status']) ? $data['status'] : 'error';
        
            return array(
                'status' => 'error',
                'message' => $status,
            );
        }
    }

    private function trialTrojanAccount($server, $request)
    {
        $trialData = AccountTrial::where('user_id', Auth::user()->id)->first();
        $trialLimit = $trialData ? $trialData->trial_limit : 0;
        if ($this->limit <= $trialLimit) {
            return array(
                'status' => 'error',
                'message' => 'Server Trial is full'
            );
        }
        // Get password from client input
        $username = $request->username;
        $password = $request->password;

        $this->headers['Authorization'] = 'Bearer '.$server->token;
        $this->url = "http://" . $server->host . "/vps/trialtrojanall";
        $this->body = [
            "timelimit" => "10m",
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);

        // Start the database transaction
        DB::beginTransaction();

        try {
            $response = $this->client->post($this->url, [
                'json' => $this->body,
            ]);

            $output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents());
            $output = json_decode($output, true);

            $expiryDate = Carbon::now()->addMinutes($this->timelimit);

            $account = Account::create([
                'user_id' => auth()->user()->id,
                'server_id' => $server->id,
                'username' => $request->username,
                'tipe' => $request->metode,
                'expired_at' => $expiryDate,
                'detail' => json_encode($output['data']),
                'charge' =>  0,
            ]);

            $user = User::find(Auth::user()->id);

            $trialCreate = [
                'user_id' => $user->id,
            ];
            
            $trialData = AccountTrial::updateOrCreate(
                ['user_id' => $user->id], // Kolom identifier
                $trialCreate // Data yang akan disimpan atau diperbarui
            );
            
            $trialData->trial_limit += 1; // Menambahkan 1 pada trial_limit
            $trialData->save(); // Menyimpan perubahan pada trial_limit
            
            $mode = $request->metode;
            $modeText = ($mode == 1) ? 'Monthly' : (($mode == 2) ? 'Hourly' : 'Trial');
            $price = ($mode == 1) ? $server->price->price_monthly : (($mode == 2) ? $server->price->price_hourly : 0);
            
            
            $purchaseDetails = [
                'name' => $user->name,
                'servername' => $server->name,
                'serverhost' => $server->host,
                'username' => $account->username,
                'password' => $output['data']['UUID'],
                'type' => $account->tipe,
                'created_date' => $account->created_at,
                'expired_date' => $account->expired_at,
                'charge' => 0,
                'price' => $price,
                'user_balance' => $user->balance,
                'purchase_mode' => $modeText,
                'email' => $user->email
            ];
            
            SendPurchaseEmail::dispatch($purchaseDetails);
            $log = new LogService();
            $log->create(Auth::id(), "warning", "bx bx-user-plus", "Create Trojan $modeText", "Create Trojan $modeText #" . Str::lower($username), $price);

            // Commit the database transaction
            DB::commit();

            $data = $this->responseTrojanAccounts($output, $account);

            $message = "New Transactions\n━━━━━━━━━━━━━━━━━━━\nUser : " . $user->name . "\nUsername : " . $request->username . "\nDetail : Pembelian Akun" . $server->category->name . "\nType : " . $expiryDate . "\n";
            if ($request->metode == 1) {
                $message .= "Durasi : " . $expiryDate . " Bulan" . "\n";
            }
            $message .= "Server : " . $server->name . "\n━━━━━━━━━━━━━━━━━━━\nPanel : " . getSettings('site_url');
            $message .= "
                Server : " . $server->name . "\n
                ━━━━━━━━━━━━━━━━━━━
                Panel : " . getSettings('site_url');

            // Uncomment the telegram send message when ready to use
            // try {
            //     $this->telegram->sendTelegramMessage($message);
            // } catch (\Exception $e) {
            //     // Handle the Telegram message sending exception if needed
            // }

            return array(
                'status' => 'success',
                'message' => 'Account created',
                'output' => $data,
                'riwayat' => $output,
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];   
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an exception
            DB::rollBack();
        
            // Cari string JSON yang ada dalam pesan kesalahan menggunakan regex
            $pattern = '/{.*}/';
            preg_match($pattern, $e->getMessage(), $matches);
            $jsonString = $matches[0] ?? '';
        
            // Menguraikan string JSON menjadi array asosiatif
            $data = json_decode($jsonString, true);
        
            // Mengambil nilai 'status' dari array $data
            $status = isset($data['status']) ? $data['status'] : 'error';
        
            return array(
                'status' => 'error',
                'message' => $status,
            );
        }
    }
    
    
    private function trialShadowsocksAccount($server, $request)
    {
        $trialData = AccountTrial::where('user_id', Auth::user()->id)->first();
        $trialLimit = $trialData ? $trialData->trial_limit : 0;
        if ($this->limit <= $trialLimit) {
            return array(
                'status' => 'error',
                'message' => 'Server Trial is full'
            );
        }
        // Get password from client input
        $username = $request->username;
        $password = $request->password;

        $this->headers['X-API-KEY'] = $server->token;
        $this->url = "http://" . $server->host . ":14022/api/shadowsockstrial";
        $this->body = [
            "host" => $server->host,
            "username" => Str::lower($username),
            "sni" => $request->sni,
            "expired" => 30
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);

        // Start the database transaction
        DB::beginTransaction();

        try {
            $response = $this->client->post($this->url, [
                'json' => $this->body,
            ]);

            $output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents());
            $output = json_decode($output, true);

            $expiryDate = Carbon::now()->addMinutes($this->timelimit);

            $account = Account::create([
                'user_id' => auth()->user()->id,
                'server_id' => $server->id,
                'username' => $request->username,
                'tipe' => $request->metode,
                'expired_at' => $expiryDate,
                'detail' => json_encode($output['data']),
                'charge' =>  0,
            ]);

            $user = User::find(Auth::user()->id);

            $trialCreate = [
                'user_id' => $user->id,
            ];
            
            $trialData = AccountTrial::updateOrCreate(
                ['user_id' => $user->id], // Kolom identifier
                $trialCreate // Data yang akan disimpan atau diperbarui
            );
            
            $trialData->trial_limit += 1; // Menambahkan 1 pada trial_limit
            $trialData->save(); // Menyimpan perubahan pada trial_limit
            
            $mode = $request->metode;
            $modeText = ($mode == 1) ? 'Monthly' : (($mode == 2) ? 'Hourly' : 'Trial');
            $price = ($mode == 1) ? $server->price->price_monthly : (($mode == 2) ? $server->price->price_hourly : 0);
            
            
            $purchaseDetails = [
                'name' => $user->name,
                'servername' => $server->name,
                'serverhost' => $server->host,
                'username' => $account->username,
                'password' => $output['data']['UUID'],
                'type' => $account->tipe,
                'created_date' => $account->created_at,
                'expired_date' => $account->expired_at,
                'charge' => 0,
                'price' => $price,
                'user_balance' => $user->balance,
                'purchase_mode' => $modeText,
                'email' => $user->email
            ];
            
            SendPurchaseEmail::dispatch($purchaseDetails);
            $log = new LogService();
            $log->create(Auth::id(), "warning", "bx bx-user-plus", "Create Shadowsocks $modeText", "Create Shadowsocks $modeText #" . Str::lower($username), $price);

            // Commit the database transaction
            DB::commit();

            $data = $this->responseShadowsocksAccounts($output, $account);

            $message = "New Transactions\n━━━━━━━━━━━━━━━━━━━\nUser : " . $user->name . "\nUsername : " . $request->username . "\nDetail : Pembelian Akun" . $server->category->name . "\nType : " . $expiryDate . "\n";
            if ($request->metode == 1) {
                $message .= "Durasi : " . $expiryDate . " Bulan" . "\n";
            }
            $message .= "Server : " . $server->name . "\n━━━━━━━━━━━━━━━━━━━\nPanel : " . getSettings('site_url');
            $message .= "
                Server : " . $server->name . "\n
                ━━━━━━━━━━━━━━━━━━━
                Panel : " . getSettings('site_url');

            // Uncomment the telegram send message when ready to use
            // try {
            //     $this->telegram->sendTelegramMessage($message);
            // } catch (\Exception $e) {
            //     // Handle the Telegram message sending exception if needed
            // }

            return array(
                'status' => 'success',
                'message' => 'Account created',
                'output' => $data,
                'riwayat' => $output,
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];   
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an exception
            DB::rollBack();
        
            // Cari string JSON yang ada dalam pesan kesalahan menggunakan regex
            $pattern = '/{.*}/';
            preg_match($pattern, $e->getMessage(), $matches);
            $jsonString = $matches[0] ?? '';
        
            // Menguraikan string JSON menjadi array asosiatif
            $data = json_decode($jsonString, true);
        
            // Mengambil nilai 'status' dari array $data
            $status = isset($data['status']) ? $data['status'] : 'error';
        
            return array(
                'status' => 'error',
                'message' => $status,
            );
        }
    }


    private function insufficientBalanceResponse()
    {
        return [
            'status' => 'error',
            'message' => 'Not enough balance, please top up your balance',
        ];
    }

    private function validationErrorResponse($validator)
    {
        return [
            'status' => 'error',
            'message' => $validator->errors()->first(),
        ];
    }

    private function usernameNotAllowedResponse()
    {
        return [
            'status' => 'error',
            'message' => 'Username is not allowed',
        ];
    }

    private function serverFullResponse()
    {
        return [
            'status' => 'error',
            'message' => 'Server is full',
        ];
    }
}