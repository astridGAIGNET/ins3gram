<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Liste des ingrédients</h3>
                <a href="<?= base_url('/admin/ingredient/new') ?>"class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvel Ingrédient
                </a>
            </div>
            <div class="card-body">
                <table id="tableIngredient" class="table table-sm table-bordered table-stripped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ingrédient</th>
                        <th>Marque</th>
                        <th>Catégorie</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var baseUrl = "<?= base_url(); ?>";
        var table = $('#tableIngredient').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>',
                type: 'POST',
                data: {
                    model: 'IngredientModel'
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'brand_name' },
                { data: 'categ_name' },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group" role="group">
                                <a href="<?= base_url('admin/ingredient/') ?>${row.id}" class="btn btn-sm btn-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="deleteIngredient(${row.id})" title="Supprimer">
                                    <i class="fas fa-trash"></i>
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

    function deleteIngredient(id){
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer cet ingrédient ? Cette action est définitive.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui, supprimer !`,
            cancelButtonText: "Annuler",

        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/ingredient/delete') ?>',
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
                    // Actualiser la table
                    refreshTable();
                } else {
                    Swal.fire({
                        title: 'Erreur !',
                        text: response.message || 'Une erreur est survenue',
                        icon: 'error'
                    });
                }
            },
                    error: function(xhr, status, error) {
                        console.error('Erreur AJAX:', error);
                        Swal.fire({
                            title: 'Erreur !',
                            text: 'Erreur de communication avec le serveur',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }
</script>