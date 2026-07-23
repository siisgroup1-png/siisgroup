document.querySelectorAll(".tab-button").forEach(bouton => {

    bouton.addEventListener("click", function(){

        document.querySelectorAll(".tab-button")
        .forEach(btn => btn.classList.remove("active"));

        document.querySelectorAll(".tab-content")
        .forEach(tab => tab.classList.remove("active"));

        this.classList.add("active");

        let id=this.dataset.tab;

        document.getElementById(id).classList.add("active");

    });

});

let editingRow;

let galleries = $('.info-gallery').DataTable({
    pageLength: 5,
    language: {
        paginate: {
            previous: "<i class='fas fa-angle-left'></i>",
            next: "<i class='fas fa-angle-right'></i>"
        }
    }
});

let achievements = $('.info-achievement').DataTable({
    pageLength: 5,
    language: {
        paginate: {
            previous: "<i class='fas fa-angle-left'></i>",
            next: "<i class='fas fa-angle-right'></i>"
        }
    }
});

async function loadGallery() {

    try {

        const response = await fetch('/api-siis/routes/gallery.php', {
            method: 'GET'
        });

        const responses = await fetch('/api-siis/routes/achievement.php', {
            method: 'GET'
        });

        const galleryRes = await response.json();

        const achievementRes = await responses.json();

        if (galleryRes.success && Array.isArray(galleryRes.data)) {

            galleries.clear();

            galleryRes.data.forEach(gallery => {

                const picture = gallery.picture
                    ? `<img src="${gallery.picture}" width="50" height="50" style="object-fit:cover;border-radius:5px;">`
                    : 'No picture';

                galleries.row.add([
                    picture,
                    gallery.description,
                    `
                    <button class="icon-btn btn btn-primary edit-item" data-id="${gallery.id_gallery}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>

                    <button class="icon-btn btn btn-danger danger delete-item" data-id="${gallery.id_gallery}">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                    `,
                    gallery.id_gallery
                ]);

            });

            galleries.draw(false);

        } else {
            console.error(galleryRes);
        }

        if (achievementRes.success && Array.isArray(achievementRes.data)) {

            achievements.clear();

            achievementRes.data.forEach(achievement => {

                const picture = achievement.picture
                    ? `<img src="${achievement.picture}" width="50" height="50" style="object-fit:cover;border-radius:5px;">`
                    : 'No picture';

                achievements.row.add([
                    picture,
                    achievement.libel,
                    achievement.description,
                    `
                    <button class="icon-btn btn btn-primary edit-items" data-id="${achievement.id_achievement}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>

                    <button class="icon-btn btn btn-danger danger delete-items" data-id="${achievement.id_achievement}">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                    `,
                    achievement.id_achievement
                ]);

            });

            achievements.draw(false);

        } else {
            console.error(achievementRes);
        }

    } catch (err) {
        console.error(err);
    }

}

// Chargement des données au démarrage
loadGallery();

$('.btn-gallery').on('click', function () {
    $('.modal-gallery .modal-title').text("Add picture");
    $('.modal-gallery button[type=submit]').text("Add");
    $('.modal-gallery').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$('.btn-achievement').on('click', function () {
    $('.modal-achievement .modal-title').text("Add achievement");
    $('.modal-achievement button[type=submit]').text("Add");
    $('.modal-achievement').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$('.close').click(function(){
    $('#picture, #picture2').attr('src','');
})

$('#gallery').on('submit', async function (e) {

    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const isEdit = !!formData.get('id');

    const submitBtn = $(form).find('button[type="submit"]');

    submitBtn.addClass('show-loader').prop('disabled', true);

    try {

        const response = await fetch('/api-siis/routes/gallery.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        submitBtn.removeClass('show-loader')
                 .prop('disabled', false)
                 .text(isEdit ? 'Update' : 'Add');

        if (result.success) {

            $('.modal-gallery').modal('hide');
            form.reset();
            $('#picture').attr('src','');

            const picture = result.data.picture
                ? `<img src="${result.data.picture}" width="50" height="50" style="object-fit:cover;border-radius:5px;">`
                : 'No picture';

            const rowData = [
                picture,
                result.data.description,
                `
                <button class="icon-btn btn btn-primary edit-item" data-id="${result.data.id_gallery}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>

                <button class="icon-btn btn btn-danger danger delete-item" data-id="${result.data.id_gallery}">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                `,
                result.data.id_gallery
            ];

            if (isEdit && editingRow) {
                editingRow.data(rowData).draw(false);
                editingRow = null;
            } else {
                galleries.row.add(rowData).draw(false);
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
    const pictureId = $(this).data('id');
    editingRow = galleries.row($(this).closest('tr'));
    try {
        const response = await fetch(`/api-siis/routes/gallery.php?id=${pictureId}`);
        const result = await response.json();
        if(result.success) {
            const e = result.data;
            $('#gallery input[name="id"]').val(pictureId);
            const picture = Array.isArray(e.picture) ? e.picture: JSON.parse(e.picture || '[]');
            $('#picture').attr('src', picture[0] || '');
            $('#gallery textarea[name="description"]').val(e.description);
            $('.modal-gallery .modal-title').text("Update Item");
            $('.modal-gallery button[type=submit]').text("Update");

            $('.modal-gallery').modal({backdrop:'static', keyboard:false});

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
        const response = await fetch(`/api-siis/routes/gallery.php?id=${id}`, {
                method: 'DELETE',
            }
        );
        const result = await response.json();
        if (result.success) {
            // Supprime uniquement la ligne concernée dans le DataTable
            galleries.rows().every(function () {
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

$('#achievementForm').on('submit', async function (e) {

    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const isEdit = !!formData.get('id');

    const submitBtn = $(form).find('button[type="submit"]');

    submitBtn.addClass('show-loader').prop('disabled', true);

    try {

        const responses = await fetch('/api-siis/routes/achievement.php', {
            method: 'POST',
            body: formData
        });

        const result = await responses.json();

        submitBtn.removeClass('show-loader')
                 .prop('disabled', false)
                 .text(isEdit ? 'Update' : 'Add');

        if (result.success) {

            $('.modal-achievement').modal('hide');
            form.reset();
            $('#picture2').attr('src','');

            const picture = result.data.picture
                ? `<img src="${result.data.picture}" width="50" height="50" style="object-fit:cover;border-radius:5px;">`
                : 'No picture';

            const rowData = [
                picture,
                result.data.libel,
                result.data.description,
                `
                <button class="icon-btn btn btn-primary edit-items" data-id="${result.data.id_achievement}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>

                <button class="icon-btn btn btn-danger danger delete-items" data-id="${result.data.id_achievement}">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                `,
                result.data.id_achievement
            ];

            if (isEdit && editingRow) {
                editingRow.data(rowData).draw(false);
                editingRow = null;
            } else {
                achievements.row.add(rowData).draw(false);
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


$(document).on('click', '.edit-items', async function() {
    const pictureId = $(this).data('id');
    editingRow = achievements.row($(this).closest('tr'));
    try {
        const responses = await fetch(`/api-siis/routes/achievement.php?id=${pictureId}`);
        const result = await responses.json();
        if(result.success) {
            const e = result.data;
            $('#achievementForm input[name="id"]').val(pictureId);
            const picture = Array.isArray(e.picture) ? e.picture: JSON.parse(e.picture || '[]');
            $('#picture2').attr('src', picture[0] || '');
            $('#achievementForm input[name="libel"]').val(e.libel);
            $('#achievementForm textarea[name="description"]').val(e.description);
            $('.modal-achievement .modal-title').text("Update Item");
            $('.modal-achievement button[type=submit]').text("Update");

            $('.modal-achievement').modal({backdrop:'static', keyboard:false});

        } else {
            alert(result.message);
        }

    } catch(err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});

$(document).on('click', '.delete-items', async function () {
    const id = $(this).data('id');
    if (!confirm("Do you want to delete this item ?")) return;
    try {
        const responses = await fetch(`/api-siis/routes/achievement.php?id=${id}`, {
                method: 'DELETE',
            }
        );
        const result = await responses.json();
        if (result.success) {
            // Supprime uniquement la ligne concernée dans le DataTable
            achievements.rows().every(function () {
                const row = this.node();
                if ($(row).find('.delete-items').data('id') == id) {
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

imgInp.onchange = evt=>{
  const [file] = imgInp.files
  if (file) {
    picture.src = URL.createObjectURL(file)
  }
}

imgInp2.onchange = evt => {
    const [file] = imgInp2.files;
    if (file) {
        picture2.src = URL.createObjectURL(file);
    }
};