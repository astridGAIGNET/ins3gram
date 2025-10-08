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
        <div class="col-md-3 text-center">
            <!-- SYSTEME DE NOTATION ETOILES-->
            <h5>Notez cette recette :</h5>

            <!-- SYSTEME DE NOTATION ETOILES-->
            <div class="star-rating justify-content-center">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <input type="radio" name="star-rating" id="star<?= $i ?>" value="<?= $i ?>"/>
                    <label for="star<?= $i ?>">&#9734;</label>
                <?php endfor; ?>
            </div>
            <p class="mt-3">Note sélectionnée : <span id="rating-value">0</span> / 5</p>
        </div>
        <!-- END: OPINION -->
        <div class="col-md-6 text-center align-self-center">
            <div class="row justify-content-center g-4">
                <!-- START: PDF-->
                <div class="col col-lg-2">
                    <span class="">
                        <i class="fa-regular fa-file fa-2x"></i>
                    </span>
                </div>
                <!-- END: PDF-->
                <!-- START: FAVORITE-->
                <div class="col-md-auto favorite">
                    <span>
                        <input type="checkbox" name="favorite" id="favorite" value="favorite">
                        <label for="favorite" id="heart-label">♡</label>
                    </span>
                </div>
                <!-- END: FAVORITE-->
                <!-- START: SHARE-->
                <div class="col col-lg-2">
                        <span class="">
                        <i class="fa-solid fa-share-nodes fa-2x"></i>
                    </span>
                </div>
                <!-- END: SHARE-->
            </div>
        </div>
        <div class="col-md-3">
        </div>
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
                            <img src="<?= base_url($ingredient['mea']);?>" alt="<?= esc($ingredient['ingredient']) ?>"
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
    })
    //LES ETOILES
    const ratingInputs = document.querySelectorAll('input[name="star-rating"]');
    const ratingLabels = document.querySelectorAll('.star-rating label');
    const ratingValue = document.getElementById('rating-value');
    let currentRating = 0;

    // Fonction pour mettre à jour l'affichage des étoiles
    function updateStars(rating) {
        ratingLabels.forEach((label, index) => {
            if (index < rating) {
                label.textContent = '★'; // Étoile pleine
            } else {
                label.textContent = '☆'; // Étoile vide
            }
        });
    }

    // Au clic : enregistrer la note
    ratingInputs.forEach((input, index) => {
        input.addEventListener('change', function () {
            currentRating = parseInt(this.value);
            ratingValue.textContent = currentRating;
            updateStars(currentRating);
        });
    });

    // Au survol : prévisualiser
    ratingLabels.forEach((label, index) => {
        label.addEventListener('mouseenter', function () {
            updateStars(index + 1);
        });
    });

    // Quand on quitte la zone : revenir à la note enregistrée
    document.querySelector('.star-rating').addEventListener('mouseleave', function () {
        updateStars(currentRating);
    });

    //OPINION (LIKE)
    const opinionInput = document.getElementById('opinion');
    const heartLabel = document.getElementById('heart-label');

    opinionInput.addEventListener('change', function () {
        if (this.checked) {
            heartLabel.textContent = '♥'; // Cœur plein
        } else {
            heartLabel.textContent = '♡'; // Cœur vide
        }
    });
</script>
<style>
    .star-rating {
        display: flex;
        flex-direction: row;
        justify-content: center;
        gap: 5px;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 25px;
        color: #000;
        cursor: pointer;
        transition: all 0.2s;
    }

    .favorite {
        display: flex;
        align-items: center;
        flex-direction: row;
        gap: 5px;
    }

    .favorite input {
        display: none;
    }

    .favorite label {
        cursor: pointer;
        transition: all 0.2s;
        font-size: 40px;
        color: #000;
        line-height: 1;
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
