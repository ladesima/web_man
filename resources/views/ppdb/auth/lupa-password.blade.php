<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
</head>
<body>

<h2>Lupa Password</h2>

<form method="POST" action="#">
    @csrf

    <label>NISN</label>
    <input type="text" name="nisn">

    <button type="submit">Reset Password</button>

</form>

</body>
</html>