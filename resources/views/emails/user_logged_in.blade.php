<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="premiumssh.net">
    <title>Login Notification</title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <style type="text/css">
      body{
      width: 650px;
      font-family: work-Sans, sans-serif;
      background-color: #f6f7fb;
      display: block;
      }
      a{
      text-decoration: none;
      }
      span {
      font-size: 14px;
      }
      p {
        font-size: 13px;
        line-height: 1.7;
        letter-spacing: 0.7px;
        margin-top: 0;
      }
      .text-center{
      text-align: center
      }
    </style>
  </head>
  <body style="margin: 30px auto;">
    <table style="width: 100%">
      <tbody>
        <tr>
          <td>
            <table style="background-color: #f6f7fb; width: 100%">
              <tbody>
                <tr>
                  <td>
                    <table style="width: 650px; margin: 0 auto; margin-bottom: 30px">
                      <tbody>
                        <tr>
                          <td style="text-align: right; color:#999"><span>Login Notification</span></td>
                        </tr>
                      </tbody>
                    </table>
                    <table style="width: 650px; margin: 0 auto; background-color: #fff; border-radius: 8px">
                      <tbody>
                        <tr>
                          <td style="padding: 30px"> 
                            <p>Hi There, {{ $details['name'] }}</p>
                            <p>Your {{ $details['app_name'] }} account logged in from a new device.</p>
                            <ul>
                                <li>Account: {{ $details['email'] }}</li>
                                <li>Time: {{ $details['time'] }}</li>
                                <li>IP Address: {{ $details['ip'] }}</li>
                                <li>Browser: {{ $details['browser'] }}</li>
                                <li>Platform: {{ $details['platform'] }}</li>
                            </ul>
                            <p>If this was you, you can ignore this alert. If you suspect any suspicious activity on your account, please change your password.</p>
                            <p style="margin-bottom: 0">Regards, {{ $details['app_name'] }}.</p>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table style="width: 650px; margin: 0 auto; margin-top: 30px">
                      <tbody>       
                        <tr style="text-align: center">
                          <td> 
                            <p style="color: #999; margin-bottom: 0">Jl Jenderal Sudirman Stabat Tasri Blok B No.19</p>
                            <p style="color: #999; margin-bottom: 0">Powered By PT. Fastnet Media Komunikasi</p>
                          </td>
                        </tr>
                      </tbody>
                    </table>
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