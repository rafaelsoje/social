<?=$render('header', ['loggedUser' => $loggedUser]);?>

<section class="container main">
    <?= $render('sidebar', [
        'activeMenu' => 'config'
    ]);?>

    <section class="feed">

            <h1 class="header-form">Configurações</h1>

            <form class="config" method="POST" action="<?= $base;?>/config" enctype="multipart/form-data" />

                <?=(!empty($flash))? '<div class="flash">'. $flash .'</div>': '' ;?>

                <label>Avatar</label>
                <input type="file" name="avatar"><br />
                <img class="image-edit" src="<?=$base;?>/media/avatars/<?=$loggedUser->avatar;?>">

                <label>Cover</label>
                <input type="file" name="cover"><br />
                <img class="image-edit" src="<?=$base;?>/media/covers/<?=$loggedUser->cover;?>">

                <label>Nome Completo</label>
                <input type="hidden" name="id" value="<?=$loggedUser->id;?>">
                <input placeholder="Digite seu nome completo" class="input" type="text" name="name" value="<?=$loggedUser->name;?>"/>
                <label>Data de nascimento</label>
                <input placeholder="Digite sua data de nascimento" class="input" type="date" name="birthdate" value="<?=$loggedUser->birthdate?>"/>
                <label>E-mail</label>
                <input placeholder="Digite seu email" class="input" type="email" name="email" value="<?=$loggedUser->email;?>" required/>
                <label>Cidade</label>
                <input placeholder="Digite sua cidade" class="input" type="text" name="city" value="<?=$loggedUser->city;?>"/>
                <label>Trabalho</label>
                <input placeholder="Onde vc trabalha?" class="input" type="text" name="work" value="<?=$loggedUser->work;?>"/>

                <label>Nova senha</label>
                <input placeholder="" class="input" type="password" name="password-1" />
                <label>Repita nova senha</label>
                <input placeholder="" class="input" type="password" name="password-2" />


                <input class="button" type="submit" value="Salvar" />

            </form>

    </section>

</section>
<?=$render('footer');?>
