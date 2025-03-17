<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="premiumssh">
    <title>Purchase Accounts Completed</title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <style type="text/css">
      body{
      text-align: center;
      margin: 0 auto;
      width: 650px;
      font-family: work-Sans, sans-serif;
      background-color: #f6f7fb;
      display: block;
      }
      ul{
      margin:0;
      padding: 0;
      }
      li{
      display: inline-block;
      text-decoration: unset;
      }
      a{
      text-decoration: none;
      }
      p{
      margin: 15px 0;
      }
      h5{
      color:#444;
      text-align:left;
      font-weight:400;
      }
      .text-center{
      text-align: center
      }
      .main-bg-light{
      background-color: #fafafa;
      box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);
      }
      .title{
      color: #444444;
      font-size: 22px;
      font-weight: bold;
      margin-top: 10px;
      margin-bottom: 10px;
      padding-bottom: 0;
      text-transform: uppercase;
      display: inline-block;
      line-height: 1;
      }
      table{
      margin-top:30px
      }
      table.top-0{
      margin-top:0;
      }
      table.order-detail , .order-detail th , .order-detail td {
      border: 1px solid #ddd;
      border-collapse: collapse;
      }
      .order-detail th{
      font-size:16px;
      padding:15px;
      text-align:center;
      }
      .footer-social-icon tr td img{
      margin-left:5px;
      margin-right:5px;
      }
    </style>
  </head>
  <body style="margin: 20px auto;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" style="padding: 0 30px;background-color: #fff; -webkit-box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);width: 100%;">
      <tbody>
        <tr>
          <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td><img src="{{ asset('assets/images/email-template/delivery.png') }}" alt="" style=";margin-bottom: 30px;"></td>
                </tr>
                <tr>
                  <td><img src="{{ asset('assets/images/email-template/success.png') }}" alt=""></td>
                </tr>
                <tr>
                  <td>
                   <h2 class="title">THANK YOU {{ strtoupper($purchaseDetails['name']) }}</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Payment Is Successfully Processsed And Your Order Is On The Way</p>
                    <p>Transaction ID: {{ $purchaseDetails['servername'] }}</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="border-top:1px solid #777;height:1px;margin-top: 30px;"></div>
                  </td>
                </tr>
                <tr>
                  <td><img src="{{ asset('assets/images/email-template/order-success.png') }}" alt="" style="margin-top: 30px;"></td>
                </tr>
              </tbody>
            </table>
            <table border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td>
                    <h2 class="title">YOUR ORDER DETAILS</h2>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- Wrapper for table responsiveness -->
            <div style="overflow-x: auto;">
                <!-- Tabel Detail Produk -->
                <table class="order-detail" border="0" cellpadding="0" cellspacing="0" align="center" style="width: 100%; margin-bottom: 20px;">
                    <thead>
                        <tr align="center">
                            <th>PRODUCT</th>
                            <th>DESCRIPTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center">
                            <td style="vertical-align: middle;">{{ $purchaseDetails['servername'] }}</td>
                            <td style="vertical-align: middle;">Purchase Has Been Successfully</td>
                        </tr>
                    </tbody>
                </table>
            
                <!-- Tabel Detail Tambahan -->
                <table class="order-detail" border="0" cellpadding="0" cellspacing="0" align="center" style="width: 100%; margin-bottom: 20px;">
                    <thead>
                        <tr align="center">
                            <th colspan="2">DETAILS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center">
                            <td style="vertical-align: middle;">Username</td>
                            <td style="vertical-align: middle;">{{ $purchaseDetails['username'] }}</td>
                        </tr>
                        <tr align="center">
                            <td style="vertical-align: middle;">Password/UUID</td>
                            <td style="vertical-align: middle;">{{ $purchaseDetails['password'] }}</td>
                        </tr>
                        <tr align="center">
                            <td style="vertical-align: middle;">Type</td>
                            <td style="vertical-align: middle;">{{ $purchaseDetails['type'] }}</td>
                        </tr>
                        <tr align="center">
                            <td style="vertical-align: middle;">Created Date</td>
                            <td style="vertical-align: middle;">{{ $purchaseDetails['created_date'] }}</td>
                        </tr>
                        <tr align="center">
                            <td style="vertical-align: middle;">Expired Date</td>
                            <td style="vertical-align: middle;">{{ $purchaseDetails['expired_date'] }}</td>
                        </tr>
                    </tbody>
                </table>
            
                <!-- Tabel Perhitungan Biaya -->
                <table class="order-detail" border="0" cellpadding="0" cellspacing="0" align="center" style="width: 100%;">
                    <thead>
                        <tr align="center">
                            <th>ITEM</th>
                            <th>PRICE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center">
                            <td style="vertical-align: middle;">Products:</td>
                            <td style="vertical-align: middle;">IDR. {{ number_format($purchaseDetails['price'], 2, ',', '.') }}</td>
                        </tr>
                        <tr align="center">
                            <td style="vertical-align: middle;">Discount :</td>
                            <td style="vertical-align: middle;">IDR. 0</td>
                        </tr>
                        <tr align="center" style="background-color: #f4f5fa;">
                            <td style="vertical-align: middle; font-weight:bold;">TOTAL PAID:</td>
                            <td style="vertical-align: middle;">IDR. {{ number_format($purchaseDetails['price'], 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <br><br>
    <table class="main-bg-light text-center top-0" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
        <tr>
          <td style="padding: 30px;">
            <div>
              <h4 class="title" style="margin:0;text-align: center;">Follow us</h4>
            </div>
            <table class="footer-social-icon" border="0" cellpadding="0" cellspacing="0" align="center" style="margin-top:20px;">
              <tbody>
                <tr>
                  <td><a href="#"><img src="{{ asset('assets/images/email-template/facebook.png') }}" alt=""></a></td>
                  <td><a href="#"><img src="{{ asset('assets/images/email-template/youtube.png') }}" alt=""></a></td>
                  <td><a href="#"><img src="{{ asset('assets/images/email-template/twitter.png') }}" alt=""></a></td>
                  <td><a href="#"><img src="{{ asset('assets/images/email-template/gplus.png') }}" alt=""></a></td>
                  <td><a href="#"><img src="{{ asset('assets/images/email-template/linkedin.png') }}" alt=""></a></td>
                  <td><a href="#"><img src="{{ asset('assets/images/email-template/pinterest.png') }}" alt=""></a></td>
                </tr>
              </tbody>
            </table>
            <div style="border-top: 1px solid #ddd; margin: 20px auto 0;"></div>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 20px auto 0;">
              <tbody>
                <tr>
                  <td>
                    <p style="font-size:13px; margin:0;">2023 COPYRIGHT PT. FASTNET MEDIA KOMUNIKASI</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>