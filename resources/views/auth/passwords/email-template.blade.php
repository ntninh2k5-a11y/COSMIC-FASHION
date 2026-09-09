<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khôi phục mật khẩu - Cosmic Fashion</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h2 style="color: #1a1a1a; text-align: center; text-transform: uppercase; letter-spacing: 1px;">Cosmic Fashion</h2>
        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 20px 0;">
        <p style="color: #333333; font-size: 16px;">Xin chào,</p>
        <p style="color: #333333; font-size: 16px;">Bạn nhận được email này vì chúng tôi nhận được yêu cầu khôi phục mật khẩu cho tài khoản <strong>{{ $email }}</strong>.</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('password/reset/' . $token . '?email=' . urlencode($email)) }}" style="background-color: #1a1a1a; color: #ffffff; padding: 12px 25px; text-decoration: none; font-weight: bold; border-radius: 4px; display: inline-block;">ĐẶT LẠI MẬT KHẨU</a>
        </div>  
        <p style="color: #333333; font-size: 16px;">Nếu bạn không yêu cầu khôi phục mật khẩu, vui lòng bỏ qua email này. Tài khoản của bạn vẫn an toàn.</p>
        <p style="color: #777777; font-size: 14px; margin-top: 30px;">Trân trọng,<br>Đội ngũ Cosmic Fashion</p>
    </div>
</body>
</html>