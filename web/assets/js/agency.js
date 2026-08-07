(() => {

    let editingRow = null;
    const token = localStorage.getItem('token');

    // ==========================================================
    // DATATABLE
    // ==========================================================

    let agencies = $('.info-agency').DataTable({
        pageLength: 5,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'></i>",
                next: "<i class='fas fa-angle-right'></i>"
            }
        }
    });


    // ==========================================================
    // CHARGER LES AGENCES
    // ==========================================================

    async function loadAgency() {

        try {

            const response = await fetch(
                '/api-siis/routes/agency.php',
                {
                    method: 'GET',
                    headers: token
                            ? {
                                'Authorization':
                                    'Bearer ' + token
                            }
                            : {}
                }
            );

            const agencyRes = await response.json();


            if (
                agencyRes.success &&
                Array.isArray(agencyRes.data)
            ) {

                agencies.clear();


                agencyRes.data.forEach(agency => {

                    agencies.row.add([

                        agency.country,

                        agency.city,

                        agency.address,

                        agency.phone,

                        agency.email,

                        agency.created_at,

                        `
                        <button
                            class="icon-btn btn btn-primary edit-item"
                            data-id="${agency.id_agency}"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <button
                            class="icon-btn btn btn-danger danger delete-item"
                            data-id="${agency.id_agency}"
                        >
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                        `,

                        agency.id_agency

                    ]);

                });


                agencies.draw(false);


            } else {

                console.error(
                    "Erreur API :",
                    agencyRes
                );

            }


        } catch (err) {

            console.error(
                "Erreur chargement agences :",
                err
            );

        }

    }


    // ==========================================================
    // AJOUTER UNE AGENCE
    // ==========================================================

    function openAddModal() {

        editingRow = null;

        const form =
            document.getElementById('agency');

        if (form) {
            form.reset();
        }


        $('#agency input[name="id"]').val('');


        $('.modal-agency .modal-title')
            .text("Add agency");


        $('.modal-agency button[type=submit]')
            .text("Add");


        $('.modal-agency').modal({
            backdrop: 'static',
            keyboard: false
        });

    }


    // ==========================================================
    // SUBMIT FORMULAIRE
    // ==========================================================

    async function handleAgencySubmit(e) {

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

            const response = await fetch(
                '/api-siis/routes/agency.php',
                {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
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


            if (result.success) {

                $('.modal-agency')
                    .modal('hide');


                form.reset();


                $('#agency input[name="id"]')
                    .val('');


                const rowData = [

                    result.data.country,

                    result.data.city,

                    result.data.address,

                    result.data.phone,

                    result.data.email,

                    result.data.created_at,

                    `
                    <button
                        class="icon-btn btn btn-primary edit-item"
                        data-id="${result.data.id_agency}"
                    >
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>

                    <button
                        class="icon-btn btn btn-danger danger delete-item"
                        data-id="${result.data.id_agency}"
                    >
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                    `,

                    result.data.id_agency

                ];


                if (
                    isEdit &&
                    editingRow
                ) {

                    editingRow
                        .data(rowData)
                        .draw(false);

                    editingRow = null;


                } else {

                    agencies
                        .row
                        .add(rowData)
                        .draw(false);

                }


            } else {

                alert(
                    result.message
                );

            }


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

            alert(
                err.message
            );

        }

    }


    // ==========================================================
    // MODIFIER
    // ==========================================================

    async function handleEdit(event) {

        const button =
            event.currentTarget;

        const agencyId =
            $(button).data('id');


        editingRow =
            agencies.row(
                $(button).closest('tr')
            );


        try {

            const response = await fetch(
            `/api-siis/routes/agency.php?id=${agencyId}`,
            {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            }
        );

            const result =
                await response.json();


            if (result.success) {

                const agency =
                    result.data;


                $('#agency input[name="id"]')
                    .val(agencyId);

                $('#agency input[name="login"]')
                    .val(agency.login);

                $('#agency input[name="country"]')
                    .val(agency.country);

                $('#agency input[name="city"]')
                    .val(agency.city);

                $('#agency input[name="address"]')
                    .val(agency.address);

                $('#agency input[name="phone"]')
                    .val(agency.phone);

                $('#agency input[name="email"]')
                    .val(agency.email);


                $('.modal-agency .modal-title')
                    .text("Update Item");


                $('.modal-agency button[type=submit]')
                    .text("Update");


                $('.modal-agency').modal({
                    backdrop: 'static',
                    keyboard: false
                });


            } else {

                alert(
                    result.message
                );

            }


        } catch (err) {

            console.error(err);

            alert(
                "Erreur serveur : " +
                err.message
            );

        }

    }


    // ==========================================================
    // SUPPRIMER
    // ==========================================================

    async function handleDelete(event) {

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
                    `/api-siis/routes/agency.php?id=${id}`,
                    {
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    }
                );


            const result =
                await response.json();


            if (result.success) {

                agencies
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


            } else {

                alert(
                    result.message ||
                    "Cannot delete"
                );

            }


        } catch (err) {

            console.error(err);

            alert(
                "Erreur serveur : " +
                err.message
            );

        }

    }


    // ==========================================================
    // ATTACHER LES EVENTS
    // ==========================================================

    $('.btn-agency')
        .off('click.agency')
        .on(
            'click.agency',
            openAddModal
        );


    $('#agency')
        .off('submit.agency')
        .on(
            'submit.agency',
            handleAgencySubmit
        );


    $(document)
        .off('click.agencyEdit', '.edit-item')
        .on(
            'click.agencyEdit',
            '.edit-item',
            handleEdit
        );


    $(document)
        .off('click.agencyDelete', '.delete-item')
        .on(
            'click.agencyDelete',
            '.delete-item',
            handleDelete
        );


    // ==========================================================
    // NETTOYAGE AVANT DE QUITTER LA PAGE
    // ==========================================================

    window.destroyModule = function () {

        console.log(
            "Nettoyage de agency.js"
        );


        // Supprimer les événements
        $('.btn-agency')
            .off('.agency');


        $('#agency')
            .off('.agency');


        $(document)
            .off(
                'click.agencyEdit',
                '.edit-item'
            );


        $(document)
            .off(
                'click.agencyDelete',
                '.delete-item'
            );


        // Détruire DataTable
        if (
            $.fn.DataTable.isDataTable(
                '.info-agency'
            )
        ) {

            $('.info-agency')
                .DataTable()
                .destroy();

        }


        // Réinitialiser
        editingRow = null;

    };


    // ==========================================================
    // DÉMARRAGE
    // ==========================================================

    loadAgency();

})();