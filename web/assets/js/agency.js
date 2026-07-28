(() => {

let editingRow;

let agencies = $('.info-agency').DataTable({
    pageLength: 5,
    language: {
        paginate: {
            previous: "<i class='fas fa-angle-left'></i>",
            next: "<i class='fas fa-angle-right'></i>"
        }
    }
});


async function loadAgency() {

    try {

        const response = await fetch('/api-siis/routes/agency.php', {
            method: 'GET',
        });

        const agencyRes = await response.json();


        if (agencyRes.success && Array.isArray(agencyRes.data)) {

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
                    <button class="icon-btn btn btn-primary edit-item" data-id="${agency.id_agency}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>

                    <button class="icon-btn btn btn-danger danger delete-item" data-id="${agency.id_agency}">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                    `,
                    agency.id_agency
                ]);

            });

            agencies.draw(false);

        } else {
            console.error(agencyRes);
        }

    } catch (err) {
        console.error(err);
    }

}

// Chargement des données au démarrage
loadAgency();

$('.btn-agency').on('click', function () {
    $('.modal-agency .modal-title').text("Add agency");
    $('.modal-agency button[type=submit]').text("Add");
    $('.modal-agency').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$('#agency').on('submit', async function (e) {

    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const isEdit = !!formData.get('id');

    const submitBtn = $(form).find('button[type="submit"]');

    submitBtn.addClass('show-loader').prop('disabled', true);

    try {

        const response = await fetch('/api-siis/routes/agency.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        submitBtn.removeClass('show-loader')
                 .prop('disabled', false)
                 .text(isEdit ? 'Update' : 'Add');

        if (result.success) {

            $('.modal-agency').modal('hide');
            form.reset();
            const rowData = [
                result.data.country,
                result.data.city,
                result.data.address,
                result.data.phone,
                result.data.email,
                result.data.created_at,
                `
                <button class="icon-btn btn btn-primary edit-item" data-id="${result.data.id_agency}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>

                <button class="icon-btn btn btn-danger danger delete-item" data-id="${result.data.id_agency}">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                `,
                result.data.id_agency
            ];

            if (isEdit && editingRow) {
                editingRow.data(rowData).draw(false);
                editingRow = null;
            } else {
                agencies.row.add(rowData).draw(false);
            }

        } else {
            alert(result.message);
        }

    } catch (err) {

        submitBtn.removeClass('show-loader')
                 .prop('disabled', false)
                 .text(isEdit ? 'Update' : 'Add');

        console.error(err);
        alert(err.message);

    }

});


$(document).on('click', '.edit-item', async function() {
    const agencyId = $(this).data('id');
    editingRow = agencies.row($(this).closest('tr'));
    try {
        const response = await fetch(`/api-siis/routes/agency.php?id=${agencyId}`, {

        });
        const result = await response.json();
        if(result.success) {
            const e = result.data;
            $('#agency input[name="id"]').val(agencyId);
            $('#agency input[name="login"]').val(e.login);
            $('#agency input[name="country"]').val(e.country);
            $('#agency input[name="city"]').val(e.city);
            $('#agency input[name="address"]').val(e.address);
            $('#agency input[name="phone"]').val(e.phone);
            $('#agency input[name="email"]').val(e.email);
            $('.modal-agency .modal-title').text("Update Item");
            $('.modal-agency button[type=submit]').text("Update");

            $('.modal-agency').modal({backdrop:'static', keyboard:false});

        } else {
            alert(result.message);
        }

    } catch(err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});

$(document).on('click', '.delete-item', async function () {
    const id = $(this).data('id');
    if (!confirm("Do you want to delete this item ?")) return;
    try {
        const response = await fetch(`/api-siis/routes/agency.php?id=${id}`, {
                method: 'DELETE',
            }
        );
        const result = await response.json();
        if (result.success) {
            agencies.rows().every(function () {
                const row = this.node();
                if ($(row).find('.delete-item').data('id') == id) {
                    this.remove().draw(false);
                }
            });
        } else {
            alert(result.message || "Cannot delete");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});


})();