<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Login - Devsbook</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
    <link rel="stylesheet" href="<?= $base;?>/assets/css/login.css" />
</head>
<body>
<header>
    <div class="container">
        <a href=""><img src="<?= $base;?>/assets/images/devsbook_logo.png" /></a>
    </div>
</header>
<section class="container main">
    <form method="POST" action="<?= $base;?>/login">

        <?=(!empty($flash))? '<div class="flash">'. $flash .'</div>': '' ;?>

        <input placeholder="Digite seu e-mail" class="input" type="email" name="email" required/>

        <input placeholder="Digite sua senha" class="input" type="password" name="password" required/>

        <input class="button" type="submit" value="Acessar o sistema" />

        <a href="<?= $base;?>/cadastro">Ainda não tem conta? Cadastre-se</a>
        <a href="<?= $base;?>/senha">Esqueci minha senha.</a>
    </form>
</section>
</body>
</html>
