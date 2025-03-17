<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Server;
use App\Models\ServerPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class ServerController extends Controller
{
    public function index($category)
    {
        $categoryData = Category::where('slug', $category)->first();

        if (!$categoryData) {
            return response()->json(['status' => 'error', 'message' => 'Category not found']);
        }

        $category_id = $categoryData->id;
        $servers = Server::where('category_id', $category_id)->get();

        if (request()->ajax()) {
            return DataTables::of($servers)
                ->addColumn('category', function ($server) {
                    return $server->category->name;
                })
                ->addColumn('country', function ($server) {
                    return $server->country->name;
                })
                ->addColumn('prices_montly', function ($server) {
                    $prices = $server->prices->map(function ($price) {
                        return $price->price_monthly;
                    })->implode('<br>');

                    return $prices;
                })
                ->addColumn('prices_hourly', function ($server) {
                    $prices = $server->prices->map(function ($price) {
                        return $price->price_hourly;
                    })->implode('<br>');

                    return $prices;
                })
                ->addColumn('action', function ($server) {
                    $editButton = '<button class="btn btn-success">Edit</button>';
                    $deleteButton = '<button class="btn btn-danger" onclick="deleteServer('.$server->id.')">Delete</button>';
                    
                    return $editButton . ' ' . $deleteButton;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.admin.servers.index', compact('categoryData'));
    }

    public function create()
    {
        $categorys = Category::all();
        $countrys = Country::all();
        $roles = Role::where('name', '!=', 'admin')->get();
        return view('pages.admin.servers.create', compact('categorys','countrys', 'roles'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'host' => 'required',
                'slug' => 'required',
                'category_id' => 'required|exists:categories,id',
                'country_id' => 'required|exists:countries,id',
                'isp' => 'required',
                'limit' => 'required|numeric',
                'prices_repeater.*.role_id' => 'required|exists:roles,id',
                'prices_repeater.*.price_monthly' => 'required|numeric',
                'prices_repeater.*.price_hourly' => 'required|numeric',
                'ports_repeater.*.ports_name' => 'required',
                'ports_repeater.*.ports_number' => 'required|min:0',
                'notes' => 'nullable'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ]);
            }
    
            DB::beginTransaction();
    
            $ports = [];
            foreach ($request->ports_repeater as $key => $value) {
                $ports[$value['ports_name']] = $value['ports_number'];
            }
    
            $server = Server::create([
                'slug' => $request->slug,
                'name' => $request->name,
                'host' => $request->host,
                'category_id' => $request->category_id,
                'country_id' => $request->country_id,
                'isp' => $request->isp,
                'limit' => $request->limit,
                'ports' => $ports,
                'notes' => $request->notes,
                'token' => $request->token
            ]);
    
            foreach ($request->prices_repeater as $key => $value) {
                ServerPrice::create([
                    'server_id' => $server->id,
                    'role_id' => $value['role_id'],
                    'price_monthly' => $value['price_monthly'],
                    'price_hourly' => $value['price_hourly'],
                ]);
            }
    
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Server created successfully'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->validator->errors()->first()
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
    
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create server'
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $server = Server::find($id);

        if ($server) {
            $server->forceDelete();
            return response()->json(['status' => 'success','message' => 'Server berhasil dihapus.']);
        } else {
            return response()->json(['status' => 'error','message' => 'Server tidak ditemukan.']);
        }
    }
}
