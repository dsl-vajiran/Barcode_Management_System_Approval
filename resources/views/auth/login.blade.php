<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DSL Barcode System - Login</title>

<!-- LOTTIE CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
    background:linear-gradient(135deg,#1a1f3a,#16213e,#0f3460);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

/* WRAPPER */
.login-wrapper{
    width:100%;
    max-width:1150px;
}

/* CONTAINER */
.login-container {
    background: #ffffff; /* Set container color to white */
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.logo-above-signin {
    text-align: center;
    margin-bottom: 20px; /* Reduced the gap */
}

.logo-above-signin img {
    width: 190px;
    height: 190px;
}

.login-content {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 40px;
}

/* LEFT SIDE (Lottie Animation) */
.form-left {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.form-left h1 {
    color: #0f3460;
    font-size: 32px;
    margin-bottom: 20px;
}

#loginLottie {
    width: 100%;
    max-width: 500px;
    height: auto;
    margin-bottom: 20px;
}

/* RIGHT SIDE (Sign-In FORM) */
.form-right {
    flex: 1;
}

.form-right h2 {
    color: #16213e;
    font-size: 28px;
    margin-bottom: 25px;
}

label {
    color: #16213e;
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
}

input {
    width: 100%;
    padding: 14px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 15px;
}

input:focus {
    outline: none;
    border-color: #0f3460;
}

.submit-btn {
    margin-top: 10px;
    width: 100%;
    padding: 14px;
    background: #0f3460;
    border: none;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}

.submit-btn:hover {
    background: #16213e;
}

/* FOOTER TEXT */
.login-footer{
    margin-top:25px;
    font-size:14px;
    color:rgba(255,255,255,0.8);
}

/* USER ROLES */
.roles-section{
    margin-top:35px;
    padding-top:20px;
    border-top:1px solid rgba(255,255,255,0.25);
}

.roles-section h3{
    color:#fff;
    font-size:20px;
    margin-bottom:15px;
}

.roles-container {
    margin-top: 20px;
    text-align: center;
    font-size: 14px;
    color: #16213e;
}

.roles-container h3 {
    font-size: 16px;
    margin-bottom: 10px;
    font-weight: bold;
}

.roles-container p {
    margin: 5px 0;
}

/* RESPONSIVE */
@media(max-width:900px){
    .login-content{
        flex-direction:column;
        text-align:center;
    }
    .form-left{
        flex:100%;
    }
    #loginLottie{
        height:420px;
    }
}

@media(max-width:480px){
    .container-header h1{
        font-size:26px;
    }
    .container-header p{
        font-size:15px;
    }
}
</style>
</head>

<body>

<div class="login-wrapper">
    <div class="login-container">
        <div class="login-content">

            <!-- LEFT (Lottie Animation) -->
            <div class="form-left">
                <h1>DSL Barcode System</h1>
                  <p class="system-tagline">Warehouse & Inventory Barcode Management</p>
                <div id="loginLottie"></div>
            </div>

            <!-- RIGHT (Sign-In Form) -->
            <div class="form-right">
                <div class="logo-above-signin">
                    <img src="images/dsl-logo.png" alt="Company Logo">
                </div>
                <h2>Sign In</h2>

                @if (session('success'))
                    <div style="margin-bottom: 10px; padding: 10px; border-radius: 6px; background: #e6ffed; color: #216e39; font-size: 14px;">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div style="margin-bottom: 10px; padding: 10px; border-radius: 6px; background: #ffe6e6; color: #b00020; font-size: 14px;">
                        <ul style="margin-left: 18px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('login.authenticate') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="submit-btn">Sign In</button>
                </form>
                <p style="margin-top: 10px; font-size: 12px; color: #666; text-align: center;">
                    Use your email and password to log in. Your role will be identified automatically.
                </p>
                
                <div class="roles-container">
                    <h3>User Roles</h3>
                    <p>Operations Officer - Handles general operations</p>
                    <p>Warehouse Manager - Oversees warehouse operations</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LOTTIE INIT -->
<script>
lottie.loadAnimation({
    container: document.getElementById('loginLottie'),
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: 'images/lottie/login.json'
});
</script>

</body>
</html>