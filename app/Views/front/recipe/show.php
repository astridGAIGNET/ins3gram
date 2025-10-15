<div class="row">
    <div class="col">
        <div class="position-relative">
            <?php if (isset($recipe['mea']['file_path'])) : ?>
                <img src="<?= base_url($recipe['mea']['file_path']); ?>" class="img-fluid recipe-img-mea">
            <?php endif; ?>
            <div class="position-absolute top-0 start-0 bg-black w-100 h-100 opacity-25"></div>
            <div class="position-absolute top-50 start-50 translate-middle text-white text-center">
                <h1><?= isset($recipe['name']) ? $recipe['name'] : ''; ?></h1>
                proposé par : <?= isset($recipe['user']) ? $recipe['user']->username : ''; ?>
            </div>
        </div>
    </div>
</div>
<div class="container-lg">
    <div class="row bg-secondary-subtle py-3">
        <!-- START: OPINION -->
        <div class="col-md-3 text-center rating-star">
            <?php
            $userScore = ($session_user != null) ? $session_user->getScore($recipe['id']) : null;
            $text_stars = $userScore ? 'Modifier ma note :' : 'Notez cette recette :';
            ?>
            <h5><?= $text_stars ?></h5>

            <!-- SYSTEME DE NOTATION ETOILES-->
            <div data-value="<?= $userScore ?? 0 ?>" id="scoreOpinion" class="star-rating justify-content-center mt-2">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i data-value="<?= $i ?>" class="<?= ($userScore && $i <= $userScore) ? 'fas' : 'far' ?> fa-xl fa-star"></i>
                <?php endfor; ?>
            </div>
            <p class="mt-3">Note sélectionnée : <span id="scoreValue"><?= $userScore ?? 0 ?></span> / 5</p>
        </div>
        <!-- END: OPINION -->
        <div class="col-md-6 text-center align-self-center">
            <div class="row justify-content-center g-4">
                <!-- START: PDF-->
                <div class="col col-lg-2">
                    <div id="pdf" data-bs-toggle="tooltip" data-bs-placement="top"
                         data-bs-title="Imprimer en PDF" title="Imprimer en PDF">
                        <i class="fa-regular fa-file fa-2x"></i>
                    </div>
                </div>
                <!-- END: PDF-->
                <!-- START: FAVORITE-->
                <div class="col-md-auto favorite" id="favorite" data-value="0">
                    <?php if (($session_user != null) && $session_user->hasFavorite($recipe['id'])) :
                        $text_favorite = 'Supprimer de mes favoris';
                        $class_favorite = 'fas';
                    else :
                        $text_favorite = 'Ajouter à mes favoris';
                        $class_favorite = 'far';
                    endif; ?>
                    <div id="heart" data-bs-toggle="tooltip" data-bs-placement="top"
                         data-bs-title="<?= $text_favorite ?>" title="<?= $text_favorite ?>">
                        <i class="<?= $class_favorite ?> fa-heart fa-2xl text-dark"></i>
                    </div>
                </div>
                <!-- END: FAVORITE-->
                <!-- START: SHARE-->
                <div class="col col-lg-2">
                    <div id="share" data-bs-toggle="tooltip" data-bs-placement="top"
                         data-bs-title="Partager la recette" title="partager la recette">
                        <i class="fa-solid fa-share-nodes fa-2x"></i>
                    </div>
                </div>
                <div id="socialShare" class="socialShare">
                    <?= social_share_links(current_url(), $recipe['name'] . ' - ins3gram'); ?>
                </div>
                <!-- END: SHARE-->
            </div>
        </div>
        <!-- START: COMMENTER LA RECETTE -->
        <div class="col-md-3 text-center">
            <h5>Commentez cette recette :</h5>
            <span>
                <button id="btn-comment" type="button" class="btn btn-outline-dark mt-2">Cliquez ici</button>
            </span>
        </div>
        <!-- END: COMMENTER LA RECETTE -->
    </div>
</div>
<!-- START: TAGS-->
<div class="row py-3">
    <div class="">
        <?php if (isset($recipe['tags']) && !empty($recipe['tags'])): ?>
            <?php foreach ($recipe['tags'] as $tag): ?>
                <button type="button" class="btn btn-dark"># <?= esc($tag['name']) ?></button>
            <?php endforeach; ?>
        <?php else: ?>
            <span class="text-muted">Aucun mot-clé associé</span>
        <?php endif; ?>
    </div>
</div>
<!-- END: TAGS-->
<!-- START: INGREDIENTS -->
<div class="container-lg">
    <div class="row bg-secondary-subtle py-4">
        <h4>Ingrédients :</h4>
        <?php foreach ($recipe['ingredients'] as $ingredient): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                <div class="card ingredient h-100 shadow">
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                         style="height: 200px;">
                        <?php if ($ingredient['mea']): ?>
                            <img src="<?= base_url($ingredient['mea']); ?>" alt="<?= esc($ingredient['ingredient']) ?>"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <img src="<?= base_url('assets/img/no-img-2.png') ?>"
                                 alt="Image par défaut"
                                 style="height: 120px; object-fit: contain; background-color: #f8f9fa;">
                        <?php endif; ?>
                    </div>
                    <div class="card-title text-center mt-2">
                        <h6 class="card-title"><?= esc($ingredient['ingredient']) ?></h6>
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text"><?= esc($ingredient['quantity']) ?> <?= esc($ingredient['unit']) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<!-- END: INGREDIENTS -->
<!-- START: IMAGES -->
<div class="row">
    <?php if (!empty($recipe['images'])) : ?>
        <div class="col-md-6">
            <div id="main-slider" class="splide mb-3">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php foreach ($recipe['images'] as $image) : ?>
                            <li class="splide__slide">
                                <a href="<?= base_url($image['file_path']); ?>" data-lightbox="mainslider">
                                    <img class="" src="<?= base_url($image['file_path']); ?>">
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div id="thumbnail-slider" class="splide mb-3">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php foreach ($recipe['images'] as $image) : ?>
                            <li class="splide__slide">
                                <img class="img-thumbnail rounded" src="<?= base_url($image['file_path']); ?>">
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="col">
        <div class="d-flex flex-column justify-content-center h-100 p-3 ">
            <?= $recipe['description']; ?>
        </div>
    </div>
</div>
<!-- END: IMAGES -->
<!-- START: ETAPES -->
<div class="container-lg">
    <div class="row bg-secondary-subtle py-4">
        <?php if (isset($recipe['steps']) && !empty($recipe['steps'])) : ?>
        <!-- UNE SEULE NAVBAR pour toutes les étapes -->
        <nav id="navbar-step" class="navbar bg-body-secondary-subtle px-3 mb-3">
            <span class="h4">Étapes de la recette :</span>
            <ul class="nav nav-pills">
                <?php foreach ($recipe['steps'] as $step) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#step-<?= $step['order']; ?>">
                            Étape <?= $step['order']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <!-- UNE SEULE zone scrollspy avec toutes les étapes dedans -->
        <div class="bg-secondary-subtle p-4">
            <div data-bs-spy="scroll"
                 data-bs-target="#navbar-step"
                 data-bs-root-margin="0px 0px -40%"
                 data-bs-smooth-scroll="true"
                 class="scrollspy-example bg-body-tertiary p-4 rounded-3 shadow"
                 tabindex="0"
                 style="max-height: 300px; overflow-y: auto;">

                <?php foreach ($recipe['steps'] as $step) : ?>
                    <h5 id="step-<?= $step['order']; ?>">Étape <?= $step['order']; ?></h5>
                    <p><?= nl2br($step['description']) ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END: ETAPES -->
<script>
    $(document).ready(function () {
        var main = new Splide('#main-slider', {
            type: 'fade',
            heightRation: 0.5,
            pagination: false,
            arrows: false,
            cover: false, //désactiver "cover" pour éviter le crop
        });
        var thumbnails = new Splide('#thumbnail-slider', {
            rewind: true,
            fixedWidth: 80,
            fixedHeight: 80,
            isNavigation: true,
            gap: 10,
            focus: 'center',
            pagination: false,
            cover: false,
            breakpoints: {
                640: {
                    fixedWidth: 60,
                    fixedHeight: 60,
                },
            },
        });
        main.sync(thumbnails);
        main.mount();
        thumbnails.mount();


        // LES ETOILES - Survol (mouseenter) - séparé du clic !
        $('#scoreOpinion').on('mouseenter', '.fa-star', function () {
            var opinion_score = $(this).data('value');
            var current_score = $('#scoreOpinion').data('value');

            // Ne met à jour les classes que si le score change
            if (opinion_score !== current_score) {
                $('#scoreOpinion').data('value', opinion_score);
                $('#scoreValue').text(opinion_score);
                $('.fa-star').each(function () {
                    if ($(this).data('value') <= opinion_score) {
                        $(this).removeClass('far').addClass('fas');
                    } else {
                        $(this).removeClass('fas').addClass('far');
                    }
                });
            }
        });
        //LES ETOILES - Vérification au clic
        $('#scoreOpinion').on('click', function () {
            <?php if ($session_user != null) : ?>
            var score = $(this).data('value');
            var name = $('h1').first().text();
            Swal.fire({
                title: "Validation",
                text: "Êtes-vous sûr de vouloir mettre " + score + " à " + name + " ?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui !",
                cancelButtonText: "Non !"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('api/recipe/score'); ?>",
                        type: "POST",
                        data: {
                            'score': score,
                            'id_recipe': '<?= $recipe['id']; ?>',
                            'id_user': '<?= $session_user->id ?? ""; ?>',
                        },
                        success: function (response) {
                            console.log(response);
                        }
                    })
                }
            });
            <?php else : ?>
            swalConnexion();
            <?php endif; ?>
        });

        // Clic n'importe où dans la zone autour (sauf sur les étoiles)
        $('.rating-star').on('click', function (e) {
            if (!$(e.target).closest('#scoreOpinion').length) {
                var stars = '';
                for (i = 0; i < 5; i++) {
                    stars += `<i data-value="${i + 1}" class="far fa-xl fa-star"></i>`;
                }
                $('#scoreOpinion').html(stars);
                $('#scoreOpinion').data('value', 0);
                $('#scoreValue').text(0);
                $('#scoreInput').remove();
            }
        });

        //FAVORITE (LIKE)
        $('#favorite').on('click', '#heart', function () {
            <?php if ($session_user != null) : ?>
            $.ajax({
                url: '<?= base_url('api/recipe/favorite'); ?>',
                type: 'POST',
                data: {
                    id_user: '<?= $session_user->id ?? ""; ?>',
                    id_recipe: '<?= $recipe['id']; ?>',
                },
                success: function (response) {
                    if (response.type == 'delete') {
                        $('#heart').data('bs-title', 'delete');
                        $('#favorite .fa-heart').removeClass('fas').addClass('far');
                    } else {
                        $('#heart').data('bs-title', 'insert');
                        $('#favorite .fa-heart').removeClass('far').addClass('fas');
                    }
                }
            })
            <?php else : ?>
            swalConnexion();
            <?php endif; ?>
        });

        //COMMENTS
        $('#btn-comment').on('click', async function () {
            <?php if ($session_user != null) : ?>
            // Si connecté, afficher le SweetAlert avec textarea
            const {value: text} = await Swal.fire({
                title: 'Commentez cette recette',
                input: "textarea",
                inputLabel: "Votre commentaire",
                inputPlaceholder: "Écrivez votre commentaire ici...",
                inputAttributes: {
                    "aria-label": "Écrivez votre commentaire ici"
                },
                showCancelButton: true,
                confirmButtonText: 'Envoyer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#000',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Vous devez écrire quelque chose !';
                    }
                }
            });
            if (text) {
                console.log({
                    id_user: '<?= $session_user->id ?? ""; ?>',
                    id_recipe: '<?= $recipe['id']; ?>',
                    comments: text
                });
                $.ajax({
                    url: '<?= base_url('api/recipe/comments'); ?>',
                    type: 'POST',
                    data: {
                        id_user: '<?= $session_user->id ?? ""; ?>',
                        id_recipe: '<?= $recipe['id']; ?>',
                        comments: text
                    },
                    success: function (response) {

                    }
                }); // Fermeture du $.ajax

                Swal.fire({
                    icon: 'success',
                    title: 'Commentaire envoyé !',
                    text: 'Merci pour votre commentaire',
                    confirmButtonColor: '#000'
                });
            }
            <?php else : ?>
            swalConnexion();
            <?php endif; ?>
        });

        function swalConnexion() {
            Swal.fire({
                title: "Vous n'êtes pas connecté(e) !",
                text: "Veuillez vous connecter ou vous inscrire.",
                icon: "warning",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "S'inscrire",
                denyButtonText: 'Se connecter',
                cancelButtonText: "Revenir à la recette",
                confirmButtonColor: "var(--bs-primary)",
                denyButtonColor: "var(--bs-success)",
                cancelButtonColor: "var(--bs-secondary)",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    //s'inscrire
                    window.location.href = "<?= base_url('register'); ?>";
                } else if (result.isDenied) {
                    //se connecter
                    window.location.href = "<?= base_url('sign-in'); ?>";
                }
            });
        }
        // SHARE : les liens sont masqués
        $('#share').on('click', function() {
            document.getElementById('socialShare').style.display = 'block';
        });
    })

</script>
<style>
    .fa-star {
        cursor: pointer;
    }

    .fa-heart:hover {
        scale: 1.1;
        cursor: pointer;
    }
    .fa-file:hover {
        scale: 1.1;
        cursor: pointer;
    }
    .fa-share-nodes:hover {
        scale: 1.1;
        cursor: pointer;
    }
    .socialShare {
        display: none;
        margin-top: 6px;
        margin-left: 165px;
    }

    #navbar-step .nav-link {
        color: #000;
    }

    #navbar-step .nav-link.active {
        background-color: #000;
        color: #fff;
    }

    #navbar-step .nav-link:hover {
        color: #000;
    }
</style>
