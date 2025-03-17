<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class AccountsController extends Controller
{
    private $client;
    private $url;
    private $headers;
    private $body;
    
     public function __construct()
    {
        $this->headers = [
            'Content-Type' => 'application/json',
            'Level' => 'Admin'
        ];
    }
    
    public function index($category)
    {
        $categoryData = Category::with('servers')->where('slug', $category)->first();

        if (!$categoryData) {
            return response()->json(['status' => 'error', 'message' => 'Category not found']);
        }

        $accounts = Account::with('server')
        ->withTrashed()
        ->where(['user_id' => Auth::user()->id])
        ->whereHas('server', function ($query) use ($categoryData) {
            $query->where('category_id', $categoryData->id);
        })
        ->orderByRaw("CASE WHEN status = 'Active' THEN 1 ELSE 2 END, status DESC");


        if (request()->ajax()) {
            return DataTables::of($accounts)
                ->addColumn('action', function ($account) use ($categoryData) {
                    if ($account->status === 'Inactive' || $account->status === 'Stopped') {
                        return '-';
                    }
                    $linkdelete = route('accounts.destroy', [$categoryData->slug, $account->username]);
                    return '<a href="' . route('accounts.show', [$categoryData->slug, $account->username]) . '" class="btn btn-sm btn-primary mx-2">Detail</a>'.
                           '<a href="'.$linkdelete.'" class="btn btn-sm btn-danger link-delete">Delete</a>';
                })
                    ->addColumn('status_badge', function ($account) {
                            // Tentukan kelas badge berdasarkan nilai status
                            switch ($account->status) {
                                case 'Inactive':
                                    $badgeClass = 'bg-danger';
                                    break;
                                case 'Active':
                                    $badgeClass = 'bg-success';
                                    break;
                                case 'Suspend':
                                    $badgeClass = 'bg-warning';
                                    break;
                                case 'Stopped':
                                    $badgeClass = 'bg-warning';
                                    break;
                                default:
                                    $badgeClass = 'bg-secondary';
                            }
                        
                            return '<span class="badge ' . $badgeClass . '">' . $account->status . '</span>';
                        })
                        
                     ->addColumn('rand', function () {
                            return rand(1000, 9999);
                        })

                    ->addColumn('formatted_balance', function ($accounts) {
                        return number_format($accounts->charge, 2, ',', '.');
                    })

                    ->addColumn('username', function ($account) {
                        $username = ''; // Inisialisasi variabel untuk username
                        $detailJson = json_decode($account->detail); // Parse JSON dari kolom detail
    
                        if (isset($detailJson->username)) {
                            $username = $detailJson->username;
                        }
                        
                        // Tampilkan username dalam bentuk badge
                        return '<span class="badge bg-info">' . $username . '</span>';
                      })
                    ->addColumn('detail', function ($account) {
                        $uuid = ''; // Inisialisasi variabel untuk UUID
                        $detailJson = json_decode($account->detail); // Parse JSON dari kolom detail
    
                        if (isset($detailJson->UUID)) {
                            $uuid = $detailJson->UUID; // Ambil UUID dari JSON jika ada
                        }

                    // Tampilkan UUID dalam bentuk badge
                    return '<span class="badge bg-info">' . $uuid . '</span>';
                })
                    ->addColumn('created_at', function ($account) {
                        return \Carbon\Carbon::parse($account->created_at)->format('Y-m-d H:i:s');
                    })
                    ->addColumn('expired_at', function ($account) {
                        return \Carbon\Carbon::parse($account->expired_at)->format('Y-m-d H:i:s');
                    })
                    ->rawColumns(['status_badge','action','detail','username','expired_at', 'rand','formatted_balance'])
                    ->toJson();
            }

        return view('pages.users.accounts.index', compact('categoryData'));
    }

    public function show($category, $username)
    {
        $categoryData = Category::where('slug', $category)->first();

        if (!$categoryData) {
            return response()->json(['status' => 'error', 'message' => 'Category not found']);
        }

        $account = Account::with('server')
            ->where(['user_id' => Auth::user()->id])
            ->whereHas('server', function ($query) use ($categoryData) {
                $query->where('category_id', $categoryData->id);
            })
            ->whereHas('server', function ($query) use ($username) {
                $query->where('username', $username);
            })
            ->first();

        if (!$account) {
            return response()->json(['status' => 'error', 'message' => 'Account not found']);
        }

        $accounts = json_decode($account->detail, true);
        
        if ($categoryData->slug == 'ssh' || $categoryData->slug == 'socks-5') {
            return view('pages.users.accounts.show-ssh', compact('categoryData', 'accounts', 'account'));
        }
        return view('pages.users.accounts.show-xray', compact('categoryData', 'accounts','account'));
    }
    
   public function update(Request $request, $category, $username)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required_without:uuidlama|string|min:6',
            'uuidlama' => 'required_without:password',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $categoryData = Category::where('slug', $category)->first();

        if (!$categoryData) {
            return response()->json(['status' => 'error', 'message' => 'Category not found']);
        }

        $account = Account::with('server')
            ->where(['user_id' => Auth::user()->id])
            ->whereHas('server', function ($query) use ($categoryData) {
                $query->where('category_id', $categoryData->id);
            })
            ->whereHas('server', function ($query) use ($username) {
                $query->where('username', $username);
            })
            ->first();

        if (!$account) {
            return response()->json(['status' => 'error', 'message' => 'Account not found']);
        }

        if ($account && Str::lower($account->status) === 'suspend') {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is suspended.',
            ]); 
        }

        $accounts = json_decode($account->detail, true);
        $this->headers['X-API-KEY'] = $account->server->token;

        // Determine the URL and body data based on the category slug
        switch ($categoryData->slug) {
            case 'ssh':
                $this->url = "http://" . $accounts['Host'] . ":14022/api/sshupdate";
                $this->body = [
                    "host" => $accounts['Host'],
                    "username" => $accounts['User'],
                    "password" => $request->password,
                    "sni" => $accounts['Sni'],
                    "expired" => $accounts['ExpiredOn']
                ];
                break;
            case 'vmess':
                $this->url = "http://" . $accounts['Host'] . ":14022/api/vmessupdate";
                $this->body = [
                    "host" => $accounts['Host'],
                    "username" => $accounts['User'],
                    "uuidlama" => $accounts['UUID'],
                    "sni" => $accounts['Sni'],
                    "expired" => $accounts['ExpiredOn']
                ];
                break;
            case 'vless':
                $this->url = "http://" . $accounts['Host'] . ":14022/api/vlessupdate";
                $this->body = [
                    "host" => $accounts['Host'],
                    "username" => $accounts['User'],
                    "uuidlama" => $accounts['UUID'],
                    "sni" => $accounts['Sni'],
                    "expired" => $accounts['ExpiredOn']
                ];
                break;
            case 'trojan':
                $this->url = "http://" . $accounts['Host'] . ":14022/api/trojanupdate";
                $this->body = [
                    "host" => $accounts['Host'],
                    "username" => $accounts['User'],
                    "uuidlama" => $accounts['UUID'],
                    "sni" => $accounts['Sni'],
                    "expired" => $accounts['ExpiredOn']
                ];
                break;
            case 'shadowsocks':
                $this->url = "http://" . $accounts['Host'] . ":14022/api/shadowsocksupdate";
                $this->body = [
                    "host" => $accounts['Host'],
                    "username" => $accounts['User'],
                    "uuidlama" => $accounts['UUID'],
                    "sni" => $accounts['Sni'],
                    "expired" => $accounts['ExpiredOn']
                ];
                break;
            case 'socks-5':
                $this->url = "http://" . $accounts['Host'] . ":14022/api/socksupdate";
                $this->body = [
                    "host" => $accounts['Host'],
                    "username" => $accounts['User'],
                    "passwordlama" => $accounts['Password'],
                    "passwordnew" => $request->password,
                    "sni" => $accounts['Sni'],
                    "expired" => $accounts['ExpiredOn']
                ];
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Invalid category']);
        }

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

            if (isset($output['data'])) {
                if ($categoryData->slug === 'ssh') {
                    $account->update([
                        'password' => $request->password,
                        'detail' => json_encode($output['data'])
                    ]);
                    $dataKey = 'Password';
                    $message = 'Password Successfully Changed';
                } else {
                    $account->update([
                        'detail' => json_encode($output['data'])
                    ]);
                    $dataKey = 'UUID';
                    $message = 'UUID Successfully Replaced';
                }

                // Commit the database transaction
                DB::commit();

                return [
                    'status' => 'success',
                    'data' => json_encode($output['data'][$dataKey]),
                    'message' => $message,
                ];
            }
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = (string) $e->getResponse()->getBody();
            $errorData = json_decode($responseBody, true);

            $status = $errorData['status'] ?? 'error';
            $message = $errorData['message'] ?? 'Unknown server error';

            return [
                'status' => $status,
                'message' => $message,
            ];
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an exception
            DB::rollBack();

            // Return generic error response
            return [
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Invalid request.',
        ];
    } 
    
    public function destroy($category, $username)
    {
        $categoryData = Category::where('slug', $category)->first();

        if (!$categoryData) {
            return response()->json(['status' => 'error', 'message' => 'Category not found']);
        }
        
        $account = Account::with('server')
            ->where(['user_id' => Auth::user()->id])
            ->whereHas('server', function ($query) use ($categoryData) {
                $query->where('category_id', $categoryData->id);
            })
            ->whereHas('server', function ($query) use ($username) {
                $query->where('username', $username);
            })
            ->first();

        if (!$account) {
            return response()->json(['status' => 'error', 'message' => 'Account not found']);
        }
        
        $this->headers['X-API-KEY'] = $account->server->token;

        // Determine the URL and body data based on the category slug
        switch ($categoryData->slug) {
            case 'ssh':
                $this->url = "http://" . $account->server->host . ":14022/api/sshdelete";
                $this->body = [
                    "username" => $account->username,
                ];
                break;
            case 'vmess':
                 $this->url = "http://" . $account->server->host . ":14022/api/vmessdelete";
                 $this->body = [
                    "username" => $account->username,
                 ];
                break;
            case 'vless':
                 $this->url = "http://" . $account->server->host . ":14022/api/vlessdelete";
                 $this->body = [
                    "username" => $account->username,
                 ];
                break;
            case 'trojan':
                 $this->url = "http://" . $account->server->host . ":14022/api/trojandelete";
                 $this->body = [
                    "username" => $account->username,
                 ];
                break;
            case 'shadowsocks':
                 $this->url = "http://" . $account->server->host . ":14022/api/shadowsocksdelete";
                 $this->body = [
                    "username" => $account->username,
                 ];
                break;
            case 'socks-5':
                 $this->url = "http://" . $account->server->host . ":14022/api/socksdelete";
                 $this->body = [
                    "username" => $account->username,
                 ];
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Invalid category']);
        }

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
            
            $account->update(['status' => '2']);
            $account->delete();
            
            DB::commit();
            
            return response()->json(['status' => 'success', 'message' => 'Account deleted']);
            
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to connect to the server. It might be offline or there might be a network issue.',
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = (string) $e->getResponse()->getBody();
            $errorData = json_decode($responseBody, true);

            $status = $errorData['status'] ?? 'error';
            $message = $errorData['message'] ?? 'Unknown server error';

            return [
                'status' => $status,
                'message' => $message,
            ];
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an exception
            DB::rollBack();

            // Return generic error response
            return [
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Invalid request.',
        ];
    }
}
