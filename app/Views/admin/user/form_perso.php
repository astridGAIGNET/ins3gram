<div class="row">
    <div class="col">
        <div class="card">
                <form action="
            <?php
                if(isset($user)):
                    echo form_open('admin/user/update');
                else:
                    echo form_open('admin/user/create');
                endif;
            ?>
            <div class="card-header">
                <?php if(isset($user->username)): ?>
                    <h1>Modification de <?= ($user->username); ?></h1>
                <?php else : ?>
                    <h1>Création d'un nouvel utilisateur</h1>
                <?php endif; ?>
            </div>
            <div class ="card-body">
                <form class="row g-3">
                    <div class="row g-3">
                    <div class="mb-3 col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="prénom" value="<?=(isset($user->first_name))?$user->first_name:"";?>">
                            <label for="first_name" class="form-label" p)>Prénom</label>
                        </div>
                    </div>
                        <div class="mb-3 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Nom" value="<?=(isset($user->last_name))?$user->last_name:"";?>">
                                <label for="last_name" class="form-label">Nom</label>
                            </div>
                        </div>
                    </div>
                        <div class="mb-3 col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="username" id="username" placeholder="Pseudo" value="<?=(isset($user->username))?$user->username:"";?>">
                                <label for="username" class="form-label">Pseudo</label>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com"
                                value="<?= (isset($user->email)) ? $user->email : ""; ?>" required>
                                <label for="email" class="form-label">Email</label>
                            </div>
                        </div>
                    <div class="mb-3 col-md-12">
                        <div class="form-floating">
                            <input type="password" class="form-control" name="password" placeholder="Mot_de_passe" id="password" <?= isset($user->id) ? '': 'required'; ?>>
                            <label for="password" class="form-label">Mot de passe</label>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="mb-3 col-md-6">
                            <div class="form-floating">
                                <input type="date" name="birthdate" class="form-control"  placeholder="Date de naissance" id="birthdate" value="<?=(isset($user->birthdate))?$user->birthdate:"";?>">
                                <label for="birthdate" class="form-label">Date de naissance</label>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="id_permission" id="id_permission" >
                                    <?php foreach ($permissions as $perm) : ?>
                                        <option value="<?= $perm["id"]; ?>"
                                            <?= (isset($user->id_permission) && ($user->id_permission == $perm["id"])) ? 'selected' : ""; ?>
                                        >
                                            <?= $perm["name"]; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="id_permission" class="form-label">Permission</label>
                            </div>
                         </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-end">
                <div>
                    <?php if (isset($user->id)) : ?>
                        <input type="hidden" name="id" value="<?=$user->id ;?>">
                    <?php endif; ?>
                    <input class="btn btn-primary" type="submit">
                </div>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
</div>
