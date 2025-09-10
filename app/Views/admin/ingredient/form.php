<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header h4">
                <?php if(isset($ingredient)) : ?>
                    <!-- Si l'ingrédient existe déjà : on affiche "Modification" -->
                    Modification de <?= esc($ingredient->name); ?>
                <?php else : ?>
                    <!-- Sinon : on affiche "Création d'un nouvel ingrédient" -->
                    Création d'un nouvel ingrédient
                <?php endif;?>
            </div>
            <?php
            // Ouverture du formulaire selon le cas : update ou create
            if(isset($ingredient)):
                echo form_open('admin/ingredient/update', ['class' => 'needs-validation', 'novalidate' => true]); ?>
                <!-- Champ caché pour stocker l'ID de l'ingrédient lors de la modification -->
                <input type="hidden" name="id" value="<?= $ingredient->id ?>">
            <?php
            else:
                echo form_open('admin/ingredient/insert', ['class' => 'needs-validation', 'novalidate' => true]);
            endif;
            ?>
            <div class="card-body">
                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input id="name" class="form-control" placeholder="Nom de l'ingrédient" type="text" name="name" value="<?= isset($ingredient) ? esc($ingredient->name) : set_value('name') ?>" required>
                        <label for="name">Nom de l'ingrédient</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input id="description" class="form-control" placeholder="Description" type="text" name="description" value="<?= isset($ingredient) ? esc($ingredient->description) : set_value('description') ?>" required>
                        <label for="description">Description</label>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select name="id_brand" id="id_brand" class="form-select">
                            <option value="">Choisir une marque</option>
                        <!-- tester si la categorie parent n'est pas vide au niveau du controller -->
                            <?php if(isset($brands) && is_array($brands)) : ?>
                            <?php foreach ($brands as $brand) : ?>
                                    <option value="<?= $brand['id'] ?>"
                                        <?= (isset($ingredient) && $ingredient->id_brand == $brand['id']) ? 'selected' : '' ?>>
                                        <?= esc($brand['name']) ?>
                                    </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </select>
                        <label for="id_brand">Marque (optionnelle)</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                         <select name="id_categ" id="id_categ" class="form-select" required>
                            <option value="">Choisir une catégorie</option>
                        <!-- tester si la categorie parent n'est pas vide au niveau du controller -->
                         <?php if(isset($categs) && is_array($categs)) : ?>
                            <?php foreach ($categs as $categ) : ?>
                                 <option value="<?= $categ['id'] ?>"
                                     <?= (isset($ingredient) && $ingredient->id_categ == $categ['id']) ? 'selected' : '' ?>>
                                     <?= esc($categ['name']) ?>
                                 </option>
                            <?php endforeach; ?>
                         <?php endif; ?>
                         </select>
                        <label for="id_categ">Catégorie <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">
                            <?= validation_show_error('id_categ_ing') ?>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <div>
                    <?php if(isset($ingredient)) : ?>
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
</div>
<script>
    $(document).ready(function () {


        initAjaxSelect2('#id_brand', {
            url: baseUrl + 'admin/brand/search',
            placeholder: 'Rechercher une marque...',
            searchFields: 'Name',
            delay: 250
        });
        initAjaxSelect2('#id_categ', {
            url: baseUrl + 'admin/categ_ing/search',
            placeholder: 'Rechercher une catégorie d\'ingrédients...',
            searchFields: 'Name',
            delay: 250
        });
    });

</script>