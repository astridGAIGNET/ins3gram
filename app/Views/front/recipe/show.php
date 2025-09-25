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
    <div class="row bg-secondary-subtle py-4">
        <!-- START: OPINION -->
        <div class="col-md-2">
            <!-- SYSTEME DE NOTATION ETOILES-->
            <div class="rating half-star-ratings raty p-3" data-half="true" data-score="" data-number="5"></div>
        </div>
        <!-- END: OPINION -->
        <div class="col-md-8 text-center align-self-end">
            <div class="row justify-content-md-center">
                <!-- START: PDF-->
                <div class="col col-lg-2">
                    <span class="">
                        <i class="fa-regular fa-file fa-2x"></i>
                    </span>
                </div>
                <!-- END: PDF-->
                <!-- START: FAVORITE-->
                <div class="col-md-auto">
                        <span class="">
                        <i class="fa-regular fa-heart fa-2x"></i>
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
        <?php foreach ($ingredients as $ingredient): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                <div class="card ingredient h-100">
                    <?php if ($ingredient['mea']): ?>
                        <img src="<?= base_url($ingredient['mea']) ?>" class="card-img-top"
                             alt="<?= esc($ingredient['name']) ?>" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                             style="height: 200px;">
                            <img src="<?= base_url('assets/img/no-img-2.png') ?>" class="card-img-top"
                                 alt="Image par défaut"
                                 style="height: 120px; object-fit: contain; background-color: #f8f9fa;">
                        </div>
                    <?php endif; ?>
                    <div class="card-title text-center">
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
        <?php if (isset($recipe['steps'])) : ?>
        <?php foreach ($recipe['steps'] as $step) : ?>
        <div class="col-4">
            <div id="#zone-steps" class="list-group">
                <a class="list-group-item list-group-item-action" href="#list-step<?= $step['order']; ?>" data-bs-target="#step-<?= $step['order']; ?>" >Étape <?= $step['order']; ?></a>
            </div>
        </div>
        <div class="col-8">
            <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true"
                 class="scrollspy-example" tabindex="1" data-bs-parent="#zone-steps" >
                <h4 id="list-step<?= $step['order']; ?>"
                    ></h4>
                <p class="text" value="steps[<?= $step['order']; ?>][description]"><?= $step['description'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<!-- END: ETAPES -->
<script>
    $(document).ready(function () {

        // Initialiser les étoiles pour notation
        $(document).ready(function () {
            $('.rating').raty({
                starType: 'i',
                starOff: 'far fa-star',
                starOn: 'fas fa-star',
                starHalf: 'fas fa-star-half-alt',
                half: true,
                score: function () {
                    return $(this).attr('data-score');
                },
                number: 5,
                readOnly: false,
                cancel: false,  // Pas de bouton d'annulation
                click: function (score, evt) {
                    console.log('Note sélectionnée : ' + score);
                }
            });
        });

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
</script>