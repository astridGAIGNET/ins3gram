<div class="row">
    <div class="col-md-3">
        <div class="card">
            <?= form_open('admin/categ-ing/insert') ?>
            <div class="card-header h4">
                Ajouter une catégorie d'ingrédients
            </div>
            <div class="card-body">
                <div class="form-floating mb-3">
                    <input id="name" class="form-control" placeholder="Nom de la catégorie" type="text" name="name" required>
                    <label for="name">Nom de la catégorie</label>
                </div>
                <div class="form-floating mb-3">
                    <select name="id_categ_parent" id="id_categ_parent" class="form-select">
                        <option selected>Choisir une catégorie</option>
                        <!-- tester si la categorie parent n'est pas vide au niveau du controller -->
                        <?php if(isset($parents) && is_array($parents)) : ?>
                            <?php foreach ($parents as $parent) : ?>
                                <option value="<?= $parent['id'] ?>"><?= esc($parent['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label for="id_categ_parent">type de catégorie (optionnel)</label>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter la catégorie</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header h4">
                Liste des catégories d'ingrédients
            </div>
            <div class="card-body">
                <table id="categIngsTable" class="table table-sm table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom de la catégorie</th>
                        <th>Type de catégorie</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modalCategIng" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Éditer la catégorie d'ingrédients</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="modalNameInput" placeholder="Nom de la catégorie" data-id="">
                    <label for="modalNameInput">Nom de la catégorie d'ingrédients</label>
                </div>
                <div class="form-floating mb-3">
                    <select name="id_categ_parent" id="id_categ_parent" class="form-select">
                        <option selected>Choisir une catégorie</option>
                        <!-- tester si la categorie parent n'est pas vide au niveau du controller -->
                        <?php if(isset($parents) && is_array($parents)) : ?>
                            <?php foreach ($parents as $parent) : ?>
                                <option value="<?= $parent['id'] ?>"><?= esc($parent['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label for="id_categ_parent">type de catégorie (optionnel)</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                <button onclick="saveCategIng()" type="button" class="btn btn-primary">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var baseUrl = "<?= base_url(); ?>";
        var table = $('#categIngsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>',
                type: 'POST',
                data: {
                    model: 'CategIngModel'
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'parent_name' },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group" role="group">
                                <button onclick="showModal(${row.id},'${row.name.replace(/'/g, "\\'")}', ${row.id_categ_parent || 'null'})"  class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteCategIng(${row.id})" class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            language: {
                url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json',
            }
        });

        window.refreshTable = function() {
            table.ajax.reload(null, false);
        };
    });

    const myModal = new bootstrap.Modal('#modalCategIng');

    function showModal(id, name) {
        $('#modalNameInput').val(name);
        $('#modalNameInput').data('id', id);
        $('#modalNameInput').data('id_categ_parent', id);
        myModal.show();
    }

    function saveCategIng() {
        let name = $('#modalNameInput').val();
        let id = $('#modalNameInput').data('id');
        let id_categ_parent = $('#modalNameInput').data('id_categ_parent')
        $.ajax({
            url: '<?= base_url('/admin/categ-ing/update') ?>',
            type: 'POST',
            data: {
                name: name,
                id: id,
                id_categ_parent : id,
            },
            success: function(response) {
                myModal.hide();
                if (response.success) {
                    Swal.fire({
                        title: 'Succès !',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    // Actualiser la table
                    refreshTable();
                } else {
                    console.log(response.message)
                    Swal.fire({
                        title: 'Erreur !',
                        text: 'Une erreur est survenue',
                        icon: 'error'
                    });
                }
            }
        })
    }

    function deleteCategIng(id){
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer cette catégorie d'ingrédients ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/categ-ing/delete') ?>',
                    type: 'POST',
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Succès !',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            refreshTable();
                        } else {
                            console.log(response.message)
                            Swal.fire({
                                title: 'Erreur !',
                                text: 'Une erreur est survenue',
                                icon: 'error'
                            });
                        }
                    }
                })
            }
        });
    }
</script>