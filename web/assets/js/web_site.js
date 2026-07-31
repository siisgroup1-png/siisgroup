(() => {

    let editingRow = null;

    // ==========================================================
    // ONGLET
    // ==========================================================

    function handleTabClick(event) {

        const bouton = event.currentTarget;

        document.querySelectorAll(".tab-button")
            .forEach(btn => btn.classList.remove("active"));

        document.querySelectorAll(".tab-content")
            .forEach(tab => tab.classList.remove("active"));

        bouton.classList.add("active");

        const id = bouton.dataset.tab;

        const content = document.getElementById(id);

        if (content) {
            content.classList.add("active");
        }
    }


    document.querySelectorAll(".tab-button")
        .forEach(bouton => {

            bouton.addEventListener(
                "click",
                handleTabClick
            );

        });


    // ==========================================================
    // DATATABLE GALLERY
    // ==========================================================

    let galleries = $('.info-gallery').DataTable({
        pageLength: 5,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'></i>",
                next: "<i class='fas fa-angle-right'></i>"
            }
        }
    });


    // ==========================================================
    // DATATABLE ACHIEVEMENT
    // ==========================================================

    let achievements = $('.info-achievement').DataTable({
        pageLength: 5,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'></i>",
                next: "<i class='fas fa-angle-right'></i>"
            }
        }
    });


    // ==========================================================
    // CHARGER LES DONNÉES
    // ==========================================================

    async function loadGallery() {

        try {

            const [
                responseGallery,
                responseAchievement
            ] = await Promise.all([

                fetch('/api-siis/routes/gallery.php'),

                fetch('/api-siis/routes/achievement.php')

            ]);


            const galleryRes =
                await responseGallery.json();

            const achievementRes =
                await responseAchievement.json();


            // ==================================================
            // GALLERY
            // ==================================================

            if (
                galleryRes.success &&
                Array.isArray(galleryRes.data)
            ) {

                galleries.clear();


                galleryRes.data.forEach(gallery => {

                    const picture = gallery.picture
                        ? `
                            <img
                                src="${gallery.picture}"
                                width="50"
                                height="50"
                                style="
                                    object-fit:cover;
                                    border-radius:5px;
                                "
                            >
                          `
                        : 'No picture';


                    galleries.row.add([

                        picture,

                        gallery.description,

                        `
                        <button
                            class="icon-btn btn btn-primary edit-item"
                            data-id="${gallery.id_gallery}"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <button
                            class="icon-btn btn btn-danger danger delete-item"
                            data-id="${gallery.id_gallery}"
                        >
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                        `,

                        gallery.id_gallery

                    ]);

                });


                galleries.draw(false);

            } else {

                console.error(
                    "Erreur gallery :",
                    galleryRes
                );

            }


            // ==================================================
            // ACHIEVEMENT
            // ==================================================

            if (
                achievementRes.success &&
                Array.isArray(achievementRes.data)
            ) {

                achievements.clear();


                achievementRes.data.forEach(achievement => {

                    const picture = achievement.picture
                        ? `
                            <img
                                src="${achievement.picture}"
                                width="50"
                                height="50"
                                style="
                                    object-fit:cover;
                                    border-radius:5px;
                                "
                            >
                          `
                        : 'No picture';


                    achievements.row.add([

                        picture,

                        achievement.libel,

                        achievement.description,

                        `
                        <button
                            class="icon-btn btn btn-primary edit-items"
                            data-id="${achievement.id_achievement}"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <button
                            class="icon-btn btn btn-danger danger delete-items"
                            data-id="${achievement.id_achievement}"
                        >
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                        `,

                        achievement.id_achievement

                    ]);

                });


                achievements.draw(false);

            } else {

                console.error(
                    "Erreur achievement :",
                    achievementRes
                );

            }


        } catch (err) {

            console.error(
                "Erreur chargement Gallery/Achievement :",
                err
            );

        }

    }


    // ==========================================================
    // OUVRIR MODAL GALLERY
    // ==========================================================

    function openGalleryModal() {

        editingRow = null;

        $('#gallery')[0].reset();

        $('#gallery input[name="id"]').val('');

        $('#picture').attr('src', '');

        $('.modal-gallery .modal-title')
            .text("Add picture");

        $('.modal-gallery button[type=submit]')
            .text("Add");

        $('.modal-gallery').modal({
            backdrop: 'static',
            keyboard: false
        });

    }


    // ==========================================================
    // OUVRIR MODAL ACHIEVEMENT
    // ==========================================================

    function openAchievementModal() {

        editingRow = null;

        $('#achievementForm')[0].reset();

        $('#achievementForm input[name="id"]').val('');

        $('#picture2').attr('src', '');

        $('.modal-achievement .modal-title')
            .text("Add achievement");

        $('.modal-achievement button[type=submit]')
            .text("Add");

        $('.modal-achievement').modal({
            backdrop: 'static',
            keyboard: false
        });

    }


    // ==========================================================
    // SUBMIT GALLERY
    // ==========================================================

    async function handleGallerySubmit(e) {

        e.preventDefault();

        const form = this;

        const formData =
            new FormData(form);

        const isEdit =
            !!formData.get('id');

        const submitBtn =
            $(form).find(
                'button[type="submit"]'
            );


        submitBtn
            .addClass('show-loader')
            .prop('disabled', true);


        try {

            const response =
                await fetch(
                    '/api-siis/routes/gallery.php',
                    {
                        method: 'POST',
                        body: formData
                    }
                );


            const result =
                await response.json();


            submitBtn
                .removeClass('show-loader')
                .prop('disabled', false)
                .text(
                    isEdit
                        ? 'Update'
                        : 'Add'
                );


            if (!result.success) {

                alert(result.message);

                return;

            }


            $('.modal-gallery')
                .modal('hide');

            form.reset();

            $('#picture').attr('src', '');


            const picture =
                result.data.picture

                    ? `
                        <img
                            src="${result.data.picture}"
                            width="50"
                            height="50"
                            style="
                                object-fit:cover;
                                border-radius:5px;
                            "
                        >
                      `

                    : 'No picture';


            const rowData = [

                picture,

                result.data.description,

                `
                <button
                    class="icon-btn btn btn-primary edit-item"
                    data-id="${result.data.id_gallery}"
                >
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>

                <button
                    class="icon-btn btn btn-danger danger delete-item"
                    data-id="${result.data.id_gallery}"
                >
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                `,

                result.data.id_gallery

            ];


            if (
                isEdit &&
                editingRow
            ) {

                editingRow
                    .data(rowData)
                    .draw(false);

            } else {

                galleries
                    .row
                    .add(rowData)
                    .draw(false);

            }


            editingRow = null;


        } catch (err) {

            submitBtn
                .removeClass('show-loader')
                .prop('disabled', false)
                .text(
                    isEdit
                        ? 'Update'
                        : 'Add'
                );


            console.error(err);

            alert(err.message);

        }

    }


    // ==========================================================
    // MODIFIER GALLERY
    // ==========================================================

    async function handleEditGallery(event) {

        const button =
            event.currentTarget;

        const pictureId =
            $(button).data('id');


        editingRow =
            galleries.row(
                $(button).closest('tr')
            );


        try {

            const response =
                await fetch(
                    `/api-siis/routes/gallery.php?id=${pictureId}`
                );


            const result =
                await response.json();


            if (!result.success) {

                alert(result.message);

                return;

            }


            const e =
                result.data;


            $('#gallery input[name="id"]')
                .val(pictureId);


            let picture = [];


            if (Array.isArray(e.picture)) {

                picture = e.picture;

            } else {

                try {

                    picture =
                        JSON.parse(
                            e.picture || '[]'
                        );

                } catch {

                    picture = [];

                }

            }


            $('#picture')
                .attr(
                    'src',
                    picture[0] || ''
                );


            $('#gallery textarea[name="description"]')
                .val(e.description);


            $('.modal-gallery .modal-title')
                .text("Update Item");


            $('.modal-gallery button[type=submit]')
                .text("Update");


            $('.modal-gallery').modal({
                backdrop: 'static',
                keyboard: false
            });


        } catch (err) {

            console.error(err);

            alert(
                "Erreur serveur : " +
                err.message
            );

        }

    }


    // ==========================================================
    // SUPPRIMER GALLERY
    // ==========================================================

    async function handleDeleteGallery(event) {

        const button =
            event.currentTarget;

        const id =
            $(button).data('id');


        if (
            !confirm(
                "Do you want to delete this item ?"
            )
        ) {

            return;

        }


        try {

            const response =
                await fetch(
                    `/api-siis/routes/gallery.php?id=${id}`,
                    {
                        method: 'DELETE'
                    }
                );


            const result =
                await response.json();


            if (!result.success) {

                alert(
                    result.message ||
                    "Cannot delete"
                );

                return;

            }


            galleries
                .rows()
                .every(function () {

                    const row =
                        this.node();


                    if (
                        $(row)
                            .find('.delete-item')
                            .data('id') == id
                    ) {

                        this
                            .remove()
                            .draw(false);

                    }

                });


        } catch (err) {

            console.error(err);

            alert(
                "Erreur serveur : " +
                err.message
            );

        }

    }


    // ==========================================================
    // SUBMIT ACHIEVEMENT
    // ==========================================================

    async function handleAchievementSubmit(e) {

        e.preventDefault();

        const form = this;

        const formData =
            new FormData(form);

        const isEdit =
            !!formData.get('id');

        const submitBtn =
            $(form).find(
                'button[type="submit"]'
            );


        submitBtn
            .addClass('show-loader')
            .prop('disabled', true);


        try {

            const response =
                await fetch(
                    '/api-siis/routes/achievement.php',
                    {
                        method: 'POST',
                        body: formData
                    }
                );


            const result =
                await response.json();


            submitBtn
                .removeClass('show-loader')
                .prop('disabled', false)
                .text(
                    isEdit
                        ? 'Update'
                        : 'Add'
                );


            if (!result.success) {

                alert(result.message);

                return;

            }


            $('.modal-achievement')
                .modal('hide');

            form.reset();

            $('#picture2').attr('src', '');


            const picture =
                result.data.picture

                    ? `
                        <img
                            src="${result.data.picture}"
                            width="50"
                            height="50"
                            style="
                                object-fit:cover;
                                border-radius:5px;
                            "
                        >
                      `

                    : 'No picture';


            const rowData = [

                picture,

                result.data.libel,

                result.data.description,

                `
                <button
                    class="icon-btn btn btn-primary edit-items"
                    data-id="${result.data.id_achievement}"
                >
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>

                <button
                    class="icon-btn btn btn-danger danger delete-items"
                    data-id="${result.data.id_achievement}"
                >
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                `,

                result.data.id_achievement

            ];


            if (
                isEdit &&
                editingRow
            ) {

                editingRow
                    .data(rowData)
                    .draw(false);

            } else {

                achievements
                    .row
                    .add(rowData)
                    .draw(false);

            }


            editingRow = null;


        } catch (err) {

            submitBtn
                .removeClass('show-loader')
                .prop('disabled', false)
                .text(
                    isEdit
                        ? 'Update'
                        : 'Add'
                );


            console.error(err);

            alert(err.message);

        }

    }


    // ==========================================================
    // MODIFIER ACHIEVEMENT
    // ==========================================================

    async function handleEditAchievement(event) {

        const button =
            event.currentTarget;

        const pictureId =
            $(button).data('id');


        editingRow =
            achievements.row(
                $(button).closest('tr')
            );


        try {

            const response =
                await fetch(
                    `/api-siis/routes/achievement.php?id=${pictureId}`
                );


            const result =
                await response.json();


            if (!result.success) {

                alert(result.message);

                return;

            }


            const e =
                result.data;


            $('#achievementForm input[name="id"]')
                .val(pictureId);


            let picture = [];


            if (Array.isArray(e.picture)) {

                picture = e.picture;

            } else {

                try {

                    picture =
                        JSON.parse(
                            e.picture || '[]'
                        );

                } catch {

                    picture = [];

                }

            }


            $('#picture2')
                .attr(
                    'src',
                    picture[0] || ''
                );


            $('#achievementForm input[name="libel"]')
                .val(e.libel);


            $('#achievementForm textarea[name="description"]')
                .val(e.description);


            $('.modal-achievement .modal-title')
                .text("Update Item");


            $('.modal-achievement button[type=submit]')
                .text("Update");


            $('.modal-achievement').modal({
                backdrop: 'static',
                keyboard: false
            });


        } catch (err) {

            console.error(err);

            alert(
                "Erreur serveur : " +
                err.message
            );

        }

    }


    // ==========================================================
    // SUPPRIMER ACHIEVEMENT
    // ==========================================================

    async function handleDeleteAchievement(event) {

        const button =
            event.currentTarget;

        const id =
            $(button).data('id');


        if (
            !confirm(
                "Do you want to delete this item ?"
            )
        ) {

            return;

        }


        try {

            const response =
                await fetch(
                    `/api-siis/routes/achievement.php?id=${id}`,
                    {
                        method: 'DELETE'
                    }
                );


            const result =
                await response.json();


            if (!result.success) {

                alert(
                    result.message ||
                    "Cannot delete"
                );

                return;

            }


            achievements
                .rows()
                .every(function () {

                    const row =
                        this.node();


                    if (
                        $(row)
                            .find('.delete-items')
                            .data('id') == id
                    ) {

                        this
                            .remove()
                            .draw(false);

                    }

                });


        } catch (err) {

            console.error(err);

            alert(
                "Erreur serveur : " +
                err.message
            );

        }

    }


    // ==========================================================
    // PREVIEW IMAGE
    // ==========================================================

    function handleImagePreview(event) {

        const [file] =
            event.target.files;


        if (file) {

            $('#picture')
                .attr(
                    'src',
                    URL.createObjectURL(file)
                );

        }

    }


    function handleImagePreview2(event) {

        const [file] =
            event.target.files;


        if (file) {

            $('#picture2')
                .attr(
                    'src',
                    URL.createObjectURL(file)
                );

        }

    }


    // ==========================================================
    // FERMETURE MODAL
    // ==========================================================

    function handleModalClose() {

        $('#picture, #picture2')
            .attr('src', '');

    }


    // ==========================================================
    // EVENTS
    // ==========================================================

    $('.btn-gallery')
        .off('click.gallery')
        .on(
            'click.gallery',
            openGalleryModal
        );


    $('.btn-achievement')
        .off('click.achievement')
        .on(
            'click.achievement',
            openAchievementModal
        );


    $('#gallery')
        .off('submit.gallery')
        .on(
            'submit.gallery',
            handleGallerySubmit
        );


    $('#achievementForm')
        .off('submit.achievement')
        .on(
            'submit.achievement',
            handleAchievementSubmit
        );


    $(document)
        .off(
            'click.galleryEdit',
            '.edit-item'
        )
        .on(
            'click.galleryEdit',
            '.edit-item',
            handleEditGallery
        );


    $(document)
        .off(
            'click.galleryDelete',
            '.delete-item'
        )
        .on(
            'click.galleryDelete',
            '.delete-item',
            handleDeleteGallery
        );


    $(document)
        .off(
            'click.achievementEdit',
            '.edit-items'
        )
        .on(
            'click.achievementEdit',
            '.edit-items',
            handleEditAchievement
        );


    $(document)
        .off(
            'click.achievementDelete',
            '.delete-items'
        )
        .on(
            'click.achievementDelete',
            '.delete-items',
            handleDeleteAchievement
        );


    $('.close')
        .off('click.gallery')
        .on(
            'click.gallery',
            handleModalClose
        );


    // Les deux sélecteurs doivent correspondre
    // aux IDs présents dans ton HTML.
    $('#imgInp')
        .off('change.gallery')
        .on(
            'change.gallery',
            handleImagePreview
        );


    $('#imgInp2')
        .off('change.achievement')
        .on(
            'change.achievement',
            handleImagePreview2
        );


    // ==========================================================
    // NETTOYAGE DU MODULE
    // ==========================================================

    window.destroyModule = function () {

        console.log(
            "Nettoyage gallery.js"
        );


        // ------------------------------------------
        // EVENTS LOCAUX
        // ------------------------------------------

        $('.btn-gallery')
            .off('.gallery');


        $('.btn-achievement')
            .off('.achievement');


        $('#gallery')
            .off('.gallery');


        $('#achievementForm')
            .off('.achievement');


        $('.close')
            .off('.gallery');


        $('#imgInp')
            .off('.gallery');


        $('#imgInp2')
            .off('.achievement');


        // ------------------------------------------
        // EVENTS DOCUMENT
        // ------------------------------------------

        $(document)
            .off(
                'click.galleryEdit',
                '.edit-item'
            );


        $(document)
            .off(
                'click.galleryDelete',
                '.delete-item'
            );


        $(document)
            .off(
                'click.achievementEdit',
                '.edit-items'
            );


        $(document)
            .off(
                'click.achievementDelete',
                '.delete-items'
            );


        // ------------------------------------------
        // ONGLET
        // ------------------------------------------

        document
            .querySelectorAll(".tab-button")
            .forEach(bouton => {

                bouton.removeEventListener(
                    "click",
                    handleTabClick
                );

            });


        // ------------------------------------------
        // DATATABLE GALLERY
        // ------------------------------------------

        if (
            $.fn.DataTable.isDataTable(
                '.info-gallery'
            )
        ) {

            $('.info-gallery')
                .DataTable()
                .destroy();

        }


        // ------------------------------------------
        // DATATABLE ACHIEVEMENT
        // ------------------------------------------

        if (
            $.fn.DataTable.isDataTable(
                '.info-achievement'
            )
        ) {

            $('.info-achievement')
                .DataTable()
                .destroy();

        }


        editingRow = null;

    };


    // ==========================================================
    // DÉMARRAGE
    // ==========================================================

    loadGallery();

})();