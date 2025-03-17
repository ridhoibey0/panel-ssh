<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Server;
use App\Models\Account;
use App\Services\Account\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class ServerController extends Controller
{
    protected $accountService;
    public function __construct()
    {
        $this->accountService = new AccountService();
    }
    public function index($category)
    {
        $categoryData = Category::where('slug', $category)->first();

        if (!$categoryData) {
            return response()->json(['status' => 'error','message' => 'Category not found']);
        }

        $category_id = $categoryData->id;
        $servers = Server::where('category_id', $category_id)->get();

        return view('pages.users.servers.index', compact('servers', 'categoryData'));
    }

    public function create($category, $server)
    {
        $categoryData1 = Category::with('servers')->where('slug', $category)->first();

        if (!$categoryData1) {
            return response()->json(['status' => 'error', 'message' => 'Category not found']);
        }
        $accounts = Account::with('server')
            ->withTrashed()
            ->where(['user_id' => Auth::user()->id])
            ->whereHas('server', function ($query) use ($categoryData1) {
                $query->where('category_id', $categoryData1->id);
            })
            ->orderByRaw("CASE WHEN status = 'Active' THEN 1 ELSE 2 END, status DESC");

        if (request()->ajax()) {
            return DataTables::of($accounts)
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
                    ->addColumn('username', function ($account) {
                        $username = ''; // Inisialisasi variabel untuk username
                        $detailJson = json_decode($account->detail); // Parse JSON dari kolom detail
    
                        if (isset($detailJson->username)) {
                            $username = $detailJson->username;
                        }
                        
                        // Tampilkan username dalam bentuk badge
                        return '<span class="badge bg-info">' . $username . '</span>';
                })
                    ->addColumn('created_at', function ($account) {
                        return \Carbon\Carbon::parse($account->created_at)->format('Y-m-d H:i:s');
                    })
                    ->addColumn('rand', function () {
                            return rand(1000, 9999);
                        })
                    ->addColumn('expired_at', function ($account) {
                        return \Carbon\Carbon::parse($account->expired_at)->format('Y-m-d H:i:s');
                    })
                    ->rawColumns(['status_badge','action','detail','username','expired_at','rand'])
                    ->toJson();
            }
       try {
            $categoryData = Category::where('slug', $category)->firstOrFail();
            $serverData = Server::withCount('accounts')->where(['slug' => $server, 'category_id' => $categoryData->id])->firstOrFail();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Category or Server not found']);
        }

        return view('pages.users.servers.create', compact('categoryData', 'serverData', 'categoryData1'));
    }

    public function store($category, $server, Request $request)
    {
        try {
            $categoryData = Category::where('slug', $category)->firstOrFail();
            $serverData = Server::withCount('accounts')->where(['slug' => $server, 'category_id' => $categoryData->id])->firstOrFail();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Category or Server not found']);
        }

        if ($categoryData->slug == 'ssh') {
            $response = $this->accountService->createSSHAccount($serverData, $request);
        } elseif ($categoryData->slug == 'trojan') {
            $response = $this->accountService->createTrojanAccount($serverData, $request);
        } elseif ($categoryData->slug == 'vmess') {
            $response = $this->accountService->createVmessAccount($serverData, $request);
        } elseif ($categoryData->slug == 'vless') {
            $response = $this->accountService->createVlessAccount($serverData, $request);
        } elseif ($categoryData->slug == 'shadowsocks') {
            $response = $this->accountService->createShadowsocksAccount($serverData, $request);
        } elseif ($categoryData->slug == 'socks-5') {
            $response = $this->accountService->createSocksAccount($serverData, $request);
        }

        return response()->json($response);
    }
}
