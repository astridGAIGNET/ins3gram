<div class="row">
    <div class="col text-center">
        <h1>Liste des recettes</h1>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="d-flex align-items-end">
            <span>Trier par </span>
            <select name="sort" class="form-select" onchange="window.location.href=this.value">
                <option value="<?= build_filter_url(['name' => 'name_asc']) ?>" <?= is_filter_active('sort', 'name_asc') ? 'selected' : '' ?>>Nom (A-Z)</option>
                <option value="<?= build_filter_url(['name' => 'name_desc']) ?>" <?= is_filter_active('sort', 'name_desc') ? 'selected' : '' ?>>Nom (Z-A)</option>
                <option value="<?= build_filter_url(['sort' => 'score_desc']) ?>" <?= is_filter_active('sort', 'score_desc') ? 'selected' : '' ?>>Meilleure note</option>
            </select>

            <div name="per_page" onchange="window.location.href=this.value" class="btn-group">
                <a href="<?= build_filter_url(['per_page' => 8]) ?>"
                   class="btn <?= is_filter_active('per_page', 8) ? 'btn-primary' : 'btn-secondary' ?>">8</a>
                <a href="<?= build_filter_url(['per_page' => 16]) ?>"
                   class="btn <?= is_filter_active('per_page', 16) ? 'btn-primary' : 'btn-secondary' ?>">16</a>
                <a href="<?= build_filter_url(['per_page' => 24]) ?>"
                   class="btn <?= is_filter_active('per_page', 24) ? 'btn-primary' : 'btn-secondary' ?>">24</a>
            </div>
        </div>
    </div>
</div>
<!--START: PAGE -->
<div class="row">
    <!--START: FILTRE -->
    <div class="col-md-2 ">
        <span class="h3">FILTRES</span>
        <?php echo form_open(build_filter_url(), ['method' => 'get']); ?>
        <div class="mb-3">
            <input type="text" name="search" class="form-control" placeholder="Rechercher..."
                   value="<?= get_current_filter_value('search', '') ?>">
        </div>
        <div class="form-check">
            <input type="checkbox" name="alcool" value="1" class="form-check-input" id="alcool"
                <?= is_filter_active('alcool', 1) ? 'checked' : '' ?>>
            <label class="form-check-label" for="alcool">Avec alcool</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="ingredients[]" value="tomate" class="form-check-input" id="tomate"
                <?= is_filter_active('ingredients', 'tomate') ? 'checked' : '' ?>>
            <label class="form-check-label" for="tomate">Tomate</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="ingredients[]" value="fromage" class="form-check-input" id="fromage"
                <?= is_filter_active('ingredients', 'fromage') ? 'checked' : '' ?>>
            <label class="form-check-label" for="fromage">Fromage</label>
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <?php echo form_close(); ?>
    </div>
    <!--END: FILTRE -->
    <!--START: CONTENUS -->
    <div class="col p-4">
        <!--START: RECETTES -->
        <div class="row row-cols-2 row-cols-md-4 g-4">
            <?php foreach ($recipes as $recipe): ?>
                <div class="col">
                    <div class="card h-100 d-flex flex-column">
                        <img class="card-img-top" src="<?= base_url($recipe['mea']);?>" alt="<?= htmlspecialchars($recipe['name']); ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <?= htmlspecialchars($recipe['name']); ?>
                            </h5>
                            <div class="mb-3">
                                <?= $recipe['score']; ?>
                            </div>
                            <div class="d-grid mt-auto">
                                <a href="<?= base_url('recette/'.$recipe['slug']); ?>" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> Voir la recette
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!--END: RECETTES -->
        <!--START: PAGINATION -->
        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-center">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                    <a href="<?= get_pagination_url($current_page - 1) ?>" class="page-link">Previous</a>
                                </li>
                            <?php endif; ?>
                            <?php if ($pager): ?>
                                <div class="d-flex justify-content-center">
                                    <?= $pager->links('default', 'bootstrap_full') ?>
                                </div>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= get_pagination_url($current_page + 1) ?>">Next</a>
                            </li>
                            <li class="page-item">
                                <a href="<?= remove_filter_url(['alcool', 'per_page', 'page']) ?>" class="btn btn-outline-secondary">Réinitialiser</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!--END: PAGINATION -->
    </div>
    <!--END: CONTENUS -->
</div>
<!--END: PAGE -->