<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckBlanaceAndExpiredJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $headers;
    protected $body;
    protected $client;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->headers = [
            'Content-Type' => 'application/json',
            'Level' => 'Admin'
        ];
    }

    /**
     * Execute the job.
     */
       public function handle(): void
        {
            $accounts = Account::with('server.category')->where('status', '1')->whereIn('tipe', [1, 2])->get();
            $currentDateTime = Carbon::now();
            foreach ($accounts as $account) {
                $expiredAt = Carbon::parse($account->expired_at);
                
                if ($currentDateTime->gte($expiredAt)) {
                    switch ($account->server->category->slug) {
                        case 'ssh':
                            $this->handleSSHAccount($account);
                            break;
                        case 'trojan':
                            $this->handleTrojanAccount($account);
                            break;
                        case 'vmess':
                            $this->handleVmessAccount($account);
                            break;
                        case 'vless':
                            $this->handleVlessAccount($account);
                            break;
                        case 'socks-5':
                            $this->handleSocksAccount($account);
                            break;
                        case 'shadowsocks':
                            $this->handleShadosocksAccount($account);
                            break;
                    }
                }
            }
        }

        private function handleSSHAccount(Account $account): void
        {
            if ($this->hasSufficientBalance($account)) {
                $this->renewAccount($account);
            } else {
                // Jika saldo tidak cukup, hapus akun
                $this->sendRenewalRequest($account, 'http://'.$account->server->host.':14022/api/'.$account->server->category->slug.'delete');
            }
        }
        
        private function handleTrojanAccount(Account $account): void
        {
            if ($this->hasSufficientBalance($account)) {
                $this->renewAccount($account);
            } else {
                // Jika saldo tidak cukup, hapus akun
                $this->sendRenewalRequest($account, 'http://'.$account->server->host.':14022/api/'.$account->server->category->slug.'delete');
            }
        }
        
        private function handleVmessAccount(Account $account): void
        {
             if ($this->hasSufficientBalance($account)) {
                $this->renewAccount($account);
            } else {
                // Jika saldo tidak cukup, hapus akun
                $this->sendRenewalRequest($account, 'http://'.$account->server->host.':14022/api/'.$account->server->category->slug.'delete');
            }
        }
        
        private function handleVlessAccount(Account $account): void
        {
            if ($this->hasSufficientBalance($account)) {
                $this->renewAccount($account);
            } else {
                // Jika saldo tidak cukup, hapus akun
                $this->sendRenewalRequest($account, 'http://'.$account->server->host.':14022/api/'.$account->server->category->slug.'delete');
            }
        }
        
        private function handleSocksAccount(Account $account): void
        {
            if ($this->hasSufficientBalance($account)) {
                $this->renewAccount($account);
            } else {
                // Jika saldo tidak cukup, hapus akun
                $this->sendRenewalRequest($account, 'http://'.$account->server->host.':14022/api/'.$account->server->category->slug.'delete');
            }
        }
        
        private function handleShadowsocksAccount(Account $account): void
        {
            if ($this->hasSufficientBalance($account)) {
                $this->renewAccount($account);
            } else {
                // Jika saldo tidak cukup, hapus akun
                $this->sendRenewalRequest($account, 'http://'.$account->server->host.':14022/api/'.$account->server->category->slug.'delete');
            }
        }
        
        private function hasSufficientBalance(Account $account): bool
        {
            $userBalance = $this->getUserBalance($account->user_id);
            $renewAmount = $this->calculateRenewAmount($account);
        
            return $userBalance >= $renewAmount;
        }
        
        private function getUserBalance($userId): float
        {
            $user = User::find($userId);
            $balance = $user->balance;
            return $balance;
        }
        
        private function calculateRenewAmount(Account $account): float
        {
            if ($account->tipe === 'Monthly') {
                $user = $account->user;
                $server = $account->server;
                $price = optional($server->prices->where('role_id', optional($user->roles->first())->id)->first())->price_monthly;
                return $price ?? 10000;
            } elseif ($account->tipe === 'Hourly') {
                $user = $account->user;
                $server = $account->server;
                $price = optional($server->prices->where('role_id', optional($user->roles->first())->id)->first())->price_hourly;
                return $price ?? 10000;
            } else {
                // Tindakan default jika tipe akun tidak cocok
                return 10000;
            }
        }
        
        private function renewAccount(Account $account): void
        {
            $newExpiry = Carbon::now();
            
            if ($account->tipe == 'Monthly') {
                $newExpiry->addMonth();
            } elseif ($account->tipe == 'Hourly') {
                $newExpiry->addHour(1);
            }
        
            $renewAmount = $this->calculateRenewAmount($account);
        
            $user = $account->user;
            $user->balance -= $renewAmount;
            $account->charge += $renewAmount;
            $user->save();
            $account->expired_at = $newExpiry;
            $account->save();

        }

        
        private function sendRenewalRequest(Account $account, string $endpoint): void
        {
            try {
                $headers = [
                    'Content-Type' => 'application/json',
                    'Level' => 'Admin',
                    'X-API-KEY' => $account->server->token
                ];
        
                $body = [
                    "username" => $account->username
                ];
        
                $client = new Client(['headers' => $headers]);
                $response = $client->post($endpoint, ['json' => $body]);
                
                // Handle the response as needed
                // ...
            } catch (\Exception $e) {
                Log::error('Error during HTTP request: ' . $e->getMessage());
                Log::info('Headers sent:', $headers);
                Log::info('Body sent:', $body);
            }
        }

}
