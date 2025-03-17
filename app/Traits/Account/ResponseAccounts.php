<?php

namespace App\Traits\Account;

/**
 * Response Accounts
 */
trait ResponseAccounts
{
     public function responseSSHAccounts($createssh, $created = null)
    {
    $data = '<div class="col-sm-12">
                <h5 class="f-light mb-3 text-center">Create Accounts SSH Successfully</h5>
            </div>
            <!-- Server Details as Table -->
            <div class="col-sm-12">
                <table class="table">
                    <tbody>
                        <tr>
                            <th class="align-middle">Hostname:</th>
                            <td class="align-middle">'. $createssh['data']['hostname'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Username:</th>
                            <td class="align-middle">'. $createssh['data']['username'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Password:</th>
                            <td class="align-middle">'. $createssh['data']['password'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Protocol:</th>
                            <td class="align-middle"><span class="badge badge-light-success">SSH, SLOWDNS, UDP, OPENVPN</span></td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port TLS:</th>
                            <td class="align-middle">'. $createssh['data']['port']['tls'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port UDPGW:</th>
                            <td class="align-middle">7100, 7200, 7300</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Max Device:</th>
                            <td class="align-middle"><span class="badge badge-light-warning">2 Device</span></td>
                        </tr>
                        <tr>
                            <th class="align-middle">Created At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->created_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Expired At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->expired_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                    </tbody>
                </table>
                <div class="col-12 mb-3 d-grid gap-2">
                    <a href="/accounts/ssh/'. $createssh['data']['username'] .'" class="btn btn-primary btn-sm" id="details">
                        <span id="btnText">Details Account</span>
                    </a>
                </div>
                <p class="text-muted mt-3" style="font-size:x-small">
                    Jika Mengalami Error Saat Membuat Accounts, Mohon <a href="#" class="fw-bold">Hubungi Customer Service</a>.<br>
                    <b>Perhatian:</b> Simpan Accounts Dengan Aman. <b>Untuk Informasi Detail Account Silahkan Cek Di List Accounts.</b>
                </p>
            </div>';
    return $data;
}


    public function responseVmessAccounts($createssh, $created)
    {
      $data = '<div class="col-sm-12">
                <h5 class="f-light mb-3 text-center">Create Accounts VMess Successfully</h5>
            </div>
            <!-- Server Details as Table -->
            <div class="col-sm-12">
                <table class="table">
                    <tbody>
                        <tr>
                            <th class="align-middle">Hostname:</th>
                            <td class="align-middle">'. $createssh['data']['hostname'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Username:</th>
                            <td class="align-middle">'. $createssh['data']['username'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">UUID:</th>
                            <td class="align-middle">'. $createssh['data']['uuid'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port TLS:</th>
                            <td class="align-middle">'. $createssh['data']['port']['tls'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port UDPGW:</th>
                            <td class="align-middle">7100, 7200, 7300</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Max Device:</th>
                            <td class="align-middle"><span class="badge badge-light-warning">2 Device</span></td>
                        </tr>
                        <tr>
                            <th class="align-middle">Created At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->created_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Expired At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->expired_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                    </tbody>
                </table>
                <div class="col-12 mb-3 d-grid gap-2">
                    <a href="/accounts/vmess/'. $createssh['data']['username'] .'" class="btn btn-primary btn-sm" id="details">
                        <span id="btnText">Details Account</span>
                    </a>
                </div>
                <p class="text-muted mt-3" style="font-size:x-small">
                    Jika Mengalami Error Saat Membuat Accounts, Mohon <a href="#" class="fw-bold">Hubungi Customer Service</a>.<br>
                    <b>Perhatian:</b> Simpan Accounts Dengan Aman. <b>Untuk Informasi Detail Account Silahkan Cek Di List Accounts.</b>
                </p>
            </div>';
    return $data;
}
    
    public function responseVlessAccounts($createssh, $created)
    {
        $data = '<div class="col-sm-12">
                <h5 class="f-light mb-3 text-center">Create Accounts Vless Successfully</h5>
            </div>
            <!-- Server Details as Table -->
            <div class="col-sm-12">
                <table class="table">
                    <tbody>
                        <tr>
                            <th class="align-middle">Hostname:</th>
                            <td class="align-middle">'. $createssh['data']['hostname'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Username:</th>
                            <td class="align-middle">'. $createssh['data']['username'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">UUID:</th>
                            <td class="align-middle">'. $createssh['data']['uuid'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port TLS:</th>
                            <td class="align-middle">'. $createssh['data']['port']['tls'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port UDPGW:</th>
                            <td class="align-middle">7100, 7200, 7300</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Max Device:</th>
                            <td class="align-middle"><span class="badge badge-light-warning">2 Device</span></td>
                        </tr>
                        <tr>
                            <th class="align-middle">Created At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->created_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Expired At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->expired_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                    </tbody>
                </table>
                <div class="col-12 mb-3 d-grid gap-2">
                    <a href="/accounts/vless/'. $createssh['data']['username'] .'" class="btn btn-primary btn-sm" id="details">
                        <span id="btnText">Details Account</span>
                    </a>
                </div>
                <p class="text-muted mt-3" style="font-size:x-small">
                    Jika Mengalami Error Saat Membuat Accounts, Mohon <a href="#" class="fw-bold">Hubungi Customer Service</a>.<br>
                    <b>Perhatian:</b> Simpan Accounts Dengan Aman. <b>Untuk Informasi Detail Account Silahkan Cek Di List Accounts.</b>
                </p>
            </div>';
    return $data;
    }
    
    public function responseTrojanAccounts($createssh, $created)
    {
        $data = '<div class="col-sm-12">
                <h5 class="f-light mb-3 text-center">Create Accounts Trojan Successfully</h5>
            </div>
            <!-- Server Details as Table -->
            <div class="col-sm-12">
                <table class="table">
                    <tbody>
                        <tr>
                            <th class="align-middle">Hostname:</th>
                            <td class="align-middle">'. $createssh['data']['Host'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Username:</th>
                            <td class="align-middle">'. $createssh['data']['User'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">UUID/Password:</th>
                            <td class="align-middle">'. $createssh['data']['UUID'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Protocol:</th>
                            <td class="align-middle">'. $createssh['data']['Trojan'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port TLS:</th>
                            <td class="align-middle">'. $createssh['data']['PortTLS'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port NTLS:</th>
                            <td class="align-middle">'. $createssh['data']['PortNTLS'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port UDPGW:</th>
                            <td class="align-middle">7100, 7200, 7300</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Max Device:</th>
                            <td class="align-middle"><span class="badge badge-light-warning">2 Device</span></td>
                        </tr>
                        <tr>
                            <th class="align-middle">Created At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->created_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Expired At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->expired_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                    </tbody>
                </table>
                <div class="col-12 mb-3 d-grid gap-2">
                    <a href="/accounts/trojan/'. $createssh['data']['User'] .'" class="btn btn-primary btn-sm" id="details">
                        <span id="btnText">Details Account</span>
                    </a>
                </div>
                <p class="text-muted mt-3" style="font-size:x-small">
                    Jika Mengalami Error Saat Membuat Accounts, Mohon <a href="#" class="fw-bold">Hubungi Customer Service</a>.<br>
                    <b>Perhatian:</b> Simpan Accounts Dengan Aman. <b>Untuk Informasi Detail Account Silahkan Cek Di List Accounts.</b>
                </p>
            </div>';
    return $data;

    }
    
    public function responseShadowsocksAccounts($createssh, $created)
    {
        $data = '<div class="col-sm-12">
                <h5 class="f-light mb-3 text-center">Create Accounts Trojan Successfully</h5>
            </div>
            <!-- Server Details as Table -->
            <div class="col-sm-12">
                <table class="table">
                    <tbody>
                        <tr>
                            <th class="align-middle">Hostname:</th>
                            <td class="align-middle">'. $createssh['data']['hostname'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Username:</th>
                            <td class="align-middle">'. $createssh['data']['username'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">UUID/Password:</th>
                            <td class="align-middle">'. $createssh['data']['uuid'] .'</td>
                        </tr>
      
                        <tr>
                            <th class="align-middle">Port TLS:</th>
                            <td class="align-middle">'. $createssh['data']['port']['tls'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port UDPGW:</th>
                            <td class="align-middle">7100, 7200, 7300</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Max Device:</th>
                            <td class="align-middle"><span class="badge badge-light-warning">2 Device</span></td>
                        </tr>
                        <tr>
                            <th class="align-middle">Created At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->created_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Expired At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->expired_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                    </tbody>
                </table>
                <div class="col-12 mb-3 d-grid gap-2">
                    <a href="/accounts/shadowsocks/'. $createssh['data']['username'] .'" class="btn btn-primary btn-sm" id="details">
                        <span id="btnText">Details Account</span>
                    </a>
                </div>
                <p class="text-muted mt-3" style="font-size:x-small">
                    Jika Mengalami Error Saat Membuat Accounts, Mohon <a href="#" class="fw-bold">Hubungi Customer Service</a>.<br>
                    <b>Perhatian:</b> Simpan Accounts Dengan Aman. <b>Untuk Informasi Detail Account Silahkan Cek Di List Accounts.</b>
                </p>
            </div>';
    return $data;

    }
    
    public function responseSocksAccounts($createssh, $created)
    {
        $data = '<div class="col-sm-12">
                <h5 class="f-light mb-3 text-center">Create Accounts Trojan Successfully</h5>
            </div>
            <!-- Server Details as Table -->
            <div class="col-sm-12">
                <table class="table">
                    <tbody>
                        <tr>
                            <th class="align-middle">Hostname:</th>
                            <td class="align-middle">'. $createssh['data']['Host'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Username:</th>
                            <td class="align-middle">'. $createssh['data']['User'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Password:</th>
                            <td class="align-middle">'. $createssh['data']['Password'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Protocol:</th>
                            <td class="align-middle">'. $createssh['data']['Socks'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port TLS:</th>
                            <td class="align-middle">'. $createssh['data']['PortTLS'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port NTLS:</th>
                            <td class="align-middle">'. $createssh['data']['PortNTLS'] .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Port UDPGW:</th>
                            <td class="align-middle">7100, 7200, 7300</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Max Device:</th>
                            <td class="align-middle"><span class="badge badge-light-warning">2 Device</span></td>
                        </tr>
                        <tr>
                            <th class="align-middle">Created At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->created_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Expired At:</th>
                            <td class="align-middle">'. \Carbon\Carbon::parse($created->expired_at)->isoFormat('MMM D, Y, h:mm A') .'</td>
                        </tr>
                    </tbody>
                </table>
                <div class="col-12 mb-3 d-grid gap-2">
                    <a href="/accounts/socks-5/'. $createssh['data']['User'] .'" class="btn btn-primary btn-sm" id="details">
                        <span id="btnText">Details Account</span>
                    </a>
                </div>
                <p class="text-muted mt-3" style="font-size:x-small">
                    Jika Mengalami Error Saat Membuat Accounts, Mohon <a href="#" class="fw-bold">Hubungi Customer Service</a>.<br>
                    <b>Perhatian:</b> Simpan Accounts Dengan Aman. <b>Untuk Informasi Detail Account Silahkan Cek Di List Accounts.</b>
                </p>
            </div>';
    return $data;

    }
    
}
