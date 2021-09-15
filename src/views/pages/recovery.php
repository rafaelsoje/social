<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Recuperar senha - Devsbook</title>
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
    <form method="POST" action="<?= $base;?>/senha">

        <?=(!empty($flash))? '<div class="flash">'. $flash .'</div>': '' ;?>

        <input placeholder="Digite seu e-mail" class="input" type="email" name="email" required/>

        <input placeholder="Digite sua nova senha" class="input" type="password" name="password1" required/>

        <input placeholder="Repita sua nova senha" class="input" type="password" name="password2" required/>

        <input class="button" type="submit" value="Recuperar Senha" />

        <a href="<?= $base;?>/login">Retornar ao Login</a>

    </form>
</section>
</body>
</html>
