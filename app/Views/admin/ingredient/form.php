<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header h4">
                <?php if (isset($ingredient)) : ?>
                    <!-- Si l'ingrédient existe déjà : on affiche "Modification" -->
                    Modification de <?= esc($ingredient['name']); ?>
                <?php else : ?>
                    <!-- Sinon : on affiche "Création d'un nouvel ingrédient" -->
                    Création d'un nouvel ingrédient
                <?php endif; ?>
            </div>
            <?php
            // Ouverture du formulaire selon le cas : update ou create
            if (isset($ingredient)):
                echo form_open_multipart('admin/ingredient/update', ['class' => 'needs-validation', 'novalidate' => true]); ?>
                <!-- Champ caché pour stocker l'ID de l'ingrédient lors de la modification -->
                <input type="hidden" name="id" value="<?= $ingredient['id'] ?>">
            <?php
            else:
                echo form_open_multipart('admin/ingredient/insert', ['class' => 'needs-validation', 'novalidate' => true]);
            endif;
            ?>
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input id="name" class="form-control" placeholder="Nom de l'ingrédient" type="text"
                                           name="name"
                                           value="<?= isset($ingredient) ? esc($ingredient['name']) : set_value('name') ?>"
                                           required>
                                    <label for="name">Nom de l'ingrédient</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input id="description" class="form-control" placeholder="Description" type="text"
                                           name="description"
                                           value="<?= isset($ingredient) ? esc($ingredient['description']) : set_value('description') ?>"
                                           required>
                                    <label for="description">Description</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <select name="id_brand" id="id_brand" class="form-select">
                                            <option value="">Choisir une marque</option>
                                            <!-- tester si la categorie parent n'est pas vide au niveau du controller -->
                                            <?php if (isset($brands) && is_array($brands)) : ?>
                                                <?php foreach ($brands as $brand) : ?>
                                                    <option value="<?= $brand['id'] ?>"
                                                        <?= (isset($ingredient) && $ingredient['id_brand'] == $brand['id']) ? 'selected' : '' ?>>
                                                        <?= esc($brand['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <select name="id_categ" id="id_categ" class="form-select" required>
                                            <option value="">Choisir une catégorie <span class="text-danger">*</span>
                                            </option>
                                            <!-- tester si la categorie parent n'est pas vide au niveau du controller -->
                                            <?php if (isset($categs) && is_array($categs)) : ?>
                                                <?php foreach ($categs as $categ) : ?>
                                                    <option value="<?= $categ['id'] ?>"
                                                        <?= (isset($ingredient) && $ingredient['id_categ'] == $categ['id']) ? 'selected' : '' ?>>
                                                        <?= esc($categ['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <label for="id_categ"></label>
                                        <div class="invalid-feedback">
                                            <?= validation_show_error('id_categ_ing') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="mea" class="form-label">Image Principale</label>
                                <?php if (isset($ingredient['mea']) && !empty($ingredient['mea'])) : ?>
                                    <div class="text-center mb-3 ">
                                        <img class="img-thumbnail" src="<?= $ingredient['mea']->getUrl(); ?>">
                                    </div>
                                <?php endif; ?>
                                <input id="mea" type="file" name="mea" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-7">
                                    Cet ingrédient peut être substitué par :
                                    <!-- Deuxième contenu -->
                                </div>
                                <div class="col-5">
                                    <span class="btn btn-primary" id="add-substitute">
                                    <i class="fas fa-plus"></i> Ajouter un ingrédient
                                    </span>
                                </div>
                                <div id="zone-substitutes">
                                    <div class="row mb-3 row-substitute mt-3">
                                        <div class="col">
                                            <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-trash-alt text-danger supp-substitute"></i>
                                                        </span>
                                                <select class="form-select select-substitute" name="substitutes[]">
                                                    <option value="">Choisir un ingrédient de substitution</option>
                                                    <?php if (isset($subs) && is_array($subs)) : ?>
                                                        <?php foreach ($subs as $sub) : ?>
                                                            <option value="<?= $sub['id'] ?>"
                                                                <?php if (isset($ingredient['substitutes']) && in_array($sub['id'], $ingredient['substitutes'])) : ?>
                                                                    selected
                                                                <?php endif; ?>>
                                                                <?= esc($sub['name']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Afficher les substituts sélectionnés -->
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="liste-substitutions">
                                                <p class="text-muted">Aucun ingredient de substitution pour le moment</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <div>
                <?php if (isset($ingredient)) : ?>
                    <!-- Si modification : bouton "Mettre à jour" -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Mettre à jour l'ingrédient
                    </button>
                <?php else : ?>
                    <!-- Si création : bouton pour créer -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Créer l'ingrédient
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <?php
        // Fermeture du formulaire
        echo form_close();
        ?>
    </div>
</div>
<script>
    $(document).ready(function () {

        //url pour les requetes Ajax
        var baseUrl = "<?= base_url(); ?>";

        initAjaxSelect2('#id_brand', {
            url: baseUrl + 'admin/brand/search',
            placeholder: 'Rechercher une marque...',
            searchFields: 'name',
            delay: 250
        });
        initAjaxSelect2('#id_categ', {
            url: baseUrl + 'admin/categ-ing/search',
            placeholder: 'Rechercher une catégorie d\'ingrédients...',
            searchFields: 'name',
            delay: 250
        });

        //Action du clique sur l'ajout d'un ingrédient
        $('#add-substitute').on('click', function () {
            let row = `
        <div class="row mb-3 row-substitute">
            <div class="col">
               <div class="input-group">
                   <span class="input-group-text">
                      <i class="fas fa-trash-alt text-danger supp-substitute"></i>
                   </span>
                   <select class="form-select select-substitute" name="substitutes[]">
                       <option value="" selected>Choisir un ingrédient de substitution</option>
                   </select>
               </div>
            </div>
        </div>
        `;
            $('#zone-substitutes').append(row);

            // Initialiser Select2 pour le nouveau select
            initAjaxSelect2('#zone-substitutes .row-substitute:last-child .select-substitute', {
                url: baseUrl + 'admin/ingredient/search',
                placeholder: 'Rechercher un ingrédient...',
                searchFields: 'name',
                delay: 250
            });
        });
        //Action du bouton de suppression des ingrédients
        $('#zone-substitutes').on('click', '.supp-substitute', function () {
            $(this).closest('.row-substitute').remove();
        });
    });
</script>