
(() => {

    // =========================================================
    // VARIABLES
    // =========================================================

    const token = localStorage.getItem('token');

    let agencies = [];
    let selectedAgency = null;
    let currentAgencyId = null;


    // =========================================================
    // RÉCUPÉRER L'ID DE L'AGENCE CONNECTÉE
    // =========================================================

    function getCurrentAgencyId() {

        // -----------------------------------------------------
        // 1. localStorage
        // -----------------------------------------------------

        const storedId =
            localStorage.getItem('id_agency') ||
            localStorage.getItem('agency_id');

        if (storedId) {

            const id = Number(storedId);

            if (!isNaN(id) && id > 0) {
                return id;
            }
        }


        // -----------------------------------------------------
        // 2. JWT
        // -----------------------------------------------------

        if (!token) {

            console.error(
                'Aucun token trouvé dans localStorage.'
            );

            return null;
        }


        try {

            const parts = token.split('.');

            if (parts.length !== 3) {

                console.error(
                    'Token JWT invalide.'
                );

                return null;
            }


            let base64Payload = parts[1]
                .replace(/-/g, '+')
                .replace(/_/g, '/');


            // Compléter le Base64 si nécessaire
            while (base64Payload.length % 4 !== 0) {
                base64Payload += '=';
            }


            const payload =
                JSON.parse(
                    atob(base64Payload)
                );


            console.log(
                'Payload JWT :',
                payload
            );


            // =================================================
            // IMPORTANT
            //
            // Ton Auth.php crée :
            //
            // "data": {
            //     "id": id_agency,
            //     "login": login,
            //     "country": country
            // }
            //
            // Donc l'ID est :
            //
            // payload.data.id
            // =================================================

            const id =
                payload?.data?.id;


            if (
                id !== undefined &&
                id !== null
            ) {

                const agencyId =
                    Number(id);


                if (
                    !isNaN(agencyId) &&
                    agencyId > 0
                ) {

                    return agencyId;

                }

            }


            console.error(
                'ID agence absent du JWT.'
            );

        } catch (error) {

            console.error(
                'Impossible de lire le token JWT :',
                error
            );

        }


        return null;
    }


    // =========================================================
    // AGENCE CONNECTÉE
    // =========================================================

    currentAgencyId =
        getCurrentAgencyId();


    console.log(
        'ID agence connectée :',
        currentAgencyId
    );


    // =========================================================
    // CHARGER TOUTES LES AGENCES
    // =========================================================

    async function loadAgency() {

        try {

            const response =
                await fetch(
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


            if (!response.ok) {

                throw new Error(
                    `Erreur HTTP : ${response.status}`
                );

            }


            const agencyRes =
                await response.json();


            console.log(
                'Agences reçues :',
                agencyRes
            );


            // =====================================================
            // VÉRIFIER RÉPONSE
            // =====================================================

            if (
                !agencyRes.success ||
                !Array.isArray(agencyRes.data)
            ) {

                console.error(
                    'Réponse agences incorrecte :',
                    agencyRes
                );

                return;
            }


            // =====================================================
            // STOCKER
            // =====================================================

            agencies =
                agencyRes.data;


            // =====================================================
            // CONTENEUR
            // =====================================================

            const tabsContainer =
                document.getElementById(
                    'agencyTabs'
                );


            if (!tabsContainer) {

                console.error(
                    '#agencyTabs introuvable'
                );

                return;
            }


            // Nettoyer
            tabsContainer.innerHTML = '';


            // =====================================================
            // AFFICHER TOUTES LES AGENCES
            // =====================================================

            agencies.forEach(
                (agency, index) => {

                    const button =
                        document.createElement(
                            'button'
                        );


                    button.type =
                        'button';


                    button.classList.add(
                        'tab-button'
                    );


                    // Première agence active
                    if (index === 0) {

                        button.classList.add(
                            'active'
                        );

                    }


                    // ID agence
                    button.dataset.tab =
                        agency.id_agency;


                    // Nom agence
                    button.textContent =
                        agency.login ?? 'Agence';


                    tabsContainer.appendChild(
                        button
                    );

                }
            );


            // =====================================================
            // SÉLECTIONNER PREMIÈRE AGENCE
            // =====================================================

            if (agencies.length > 0) {

                selectAgency(
                    agencies[0]
                );

            } else {

                const messagesContainer =
                    document.getElementById(
                        'messages'
                    );


                if (messagesContainer) {

                    messagesContainer.innerHTML = `

                        <div class="no-messages">
                            Aucune agence disponible.
                        </div>

                    `;

                }

            }

        } catch (error) {

            console.error(
                'Erreur chargement agences :',
                error
            );

        }

    }


    // =========================================================
    // CLIC SUR UNE AGENCE
    // =========================================================

    document.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest(
                    '#agencyTabs .tab-button'
                );


            if (!button) {
                return;
            }


            // =====================================================
            // ACTIVE
            // =====================================================

            document
                .querySelectorAll(
                    '#agencyTabs .tab-button'
                )
                .forEach(
                    btn => {

                        btn.classList.remove(
                            'active'
                        );

                    }
                );


            button.classList.add(
                'active'
            );


            // =====================================================
            // ID DESTINATAIRE
            // =====================================================

            const idReceive =
                button.dataset.tab;


            console.log(
                'Agence destinataire ID :',
                idReceive
            );


            // =====================================================
            // TROUVER AGENCE
            // =====================================================

            const agency =
                agencies.find(
                    item =>
                        String(
                            item.id_agency
                        ) ===
                        String(
                            idReceive
                        )
                );


            if (!agency) {

                console.error(
                    'Agence introuvable :',
                    idReceive
                );

                return;
            }


            // =====================================================
            // SÉLECTIONNER
            // =====================================================

            selectAgency(
                agency
            );

        }
    );


    // =========================================================
    // SÉLECTIONNER UNE AGENCE
    // =========================================================

    function selectAgency(agency) {

        selectedAgency =
            agency;


        console.log(
            'Agence sélectionnée :',
            selectedAgency
        );


        // Afficher informations
        displayAgency(
            agency
        );


        // Charger conversation
        loadMessages(
            agency.id_agency
        );

    }


    // =========================================================
    // AFFICHER INFORMATIONS AGENCE
    // =========================================================

    function displayAgency(agency) {

        const title =
            document.getElementById(
                'agencyTitle'
            );


        const info =
            document.getElementById(
                'agencyInfo'
            );


        if (title) {

            title.textContent =
                agency.login ?? '';

        }


        if (info) {

            info.innerHTML = `

                <div class="agency-details">

                    <span>
                        🌍
                        ${escapeHtml(
                            agency.country ?? ''
                        )}
                    </span>

                    <span>
                        🏙️
                        ${escapeHtml(
                            agency.city ?? ''
                        )}
                    </span>

                    <span>
                        📍
                        ${escapeHtml(
                            agency.address ?? ''
                        )}
                    </span>

                    <span>
                        📞
                        ${escapeHtml(
                            agency.phone ?? ''
                        )}
                    </span>

                    <span>
                        ✉️
                        ${escapeHtml(
                            agency.email ?? ''
                        )}
                    </span>

                </div>

            `;

        }

    }


    // =========================================================
    // CHARGER CONVERSATION
    // =========================================================

    async function loadMessages(idReceive) {

        const messagesContainer =
            document.getElementById(
                'messages'
            );


        if (!messagesContainer) {

            console.error(
                '#messages introuvable'
            );

            return;
        }


        // =====================================================
        // VÉRIFIER TOKEN
        // =====================================================

        if (!token) {

            messagesContainer.innerHTML = `

                <div class="no-messages">
                    Session expirée. Veuillez vous reconnecter.
                </div>

            `;

            return;
        }


        // =====================================================
        // CHARGEMENT
        // =====================================================

        messagesContainer.innerHTML = `

            <div class="loading-messages">
                Chargement des messages...
            </div>

        `;


        try {

            const url =
                `/api-siis/routes/messaging.php?id_agency=${encodeURIComponent(idReceive)}`;


            const response =
                await fetch(
                    url,
                    {
                        method: 'GET',

                        headers: {
                            'Authorization':
                                'Bearer ' + token
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    `Erreur HTTP : ${response.status}`
                );

            }


            const result =
                await response.json();


            console.log(
                'Conversation reçue :',
                result
            );


            // =====================================================
            // API ERROR
            // =====================================================

            if (!result.success) {

                messagesContainer.innerHTML = `

                    <div class="no-messages">
                        ${escapeHtml(
                            result.message ??
                            'Impossible de charger la conversation.'
                        )}
                    </div>

                `;

                return;
            }


            // =====================================================
            // DATA
            // =====================================================

            if (!Array.isArray(result.data)) {

                messagesContainer.innerHTML = `

                    <div class="no-messages">
                        Aucun message trouvé.
                    </div>

                `;

                return;
            }


            // =====================================================
            // AFFICHER
            // =====================================================

            renderMessages(
                result.data
            );


        } catch (error) {

            console.error(
                'Erreur chargement conversation :',
                error
            );


            messagesContainer.innerHTML = `

                <div class="no-messages">
                    Impossible de charger les messages.
                </div>

            `;

        }

    }


    // =========================================================
    // AFFICHER LES MESSAGES
    // =========================================================

    // =========================================================
// AFFICHER LES MESSAGES GROUPÉS PAR DATE
// =========================================================

// =========================================================
// AFFICHER LES MESSAGES GROUPÉS PAR DATE
// =========================================================

function renderMessages(messages) {

    const container =
        document.getElementById('messages');

    if (!container) {
        return;
    }

    container.innerHTML = '';


    // =====================================================
    // AUCUN MESSAGE
    // =====================================================

    if (!messages || messages.length === 0) {

        container.innerHTML = `
            <div class="no-messages">
                Aucun message pour cette agence.
            </div>
        `;

        return;
    }


    // =====================================================
    // GROUPER LES MESSAGES PAR DATE LOCALE
    // =====================================================

    const groups = {};

    messages.forEach(message => {

        if (!message.created_at) {
            return;
        }


        // -------------------------------------------------
        // Récupérer la date locale du message
        // selon le fuseau de l'agence connectée
        // -------------------------------------------------

        const dateKey =
            getLocalDateKey(message.created_at);


        if (!groups[dateKey]) {
            groups[dateKey] = [];
        }


        groups[dateKey].push(message);

    });


    // =====================================================
    // TRIER LES DATES
    // =====================================================

    const sortedDates =
        Object.keys(groups).sort();


    // =====================================================
    // AFFICHER LES GROUPES
    // =====================================================

    sortedDates.forEach(dateKey => {

        // -------------------------------------------------
        // SÉPARATEUR DE DATE
        // -------------------------------------------------

        const dateSeparator =
            document.createElement('div');

        dateSeparator.classList.add(
            'message-date-separator'
        );

        dateSeparator.innerHTML = `
            <span>
                ${formatMessageDate(dateKey)}
            </span>
        `;

        container.appendChild(
            dateSeparator
        );


        // -------------------------------------------------
        // MESSAGES DU JOUR
        // -------------------------------------------------

        groups[dateKey].forEach(message => {

            const element =
                createMessageElement(message);

            container.appendChild(
                element
            );

        });

    });


    // =====================================================
    // DESCENDRE EN BAS
    // =====================================================

    container.scrollTop =
        container.scrollHeight;

}


// =========================================================
// OBTENIR LA DATE LOCALE DU MESSAGE
// =========================================================

function getLocalDateKey(date) {

    if (!date) {
        return '';
    }


    // -----------------------------------------------------
    // La BDD contient une date UTC
    //
    // Exemple :
    // 2026-08-05 22:30:00
    // -----------------------------------------------------

    const utcDate =
        new Date(
            String(date).replace(' ', 'T') + 'Z'
        );


    if (isNaN(utcDate.getTime())) {
        return '';
    }


    // -----------------------------------------------------
    // AGENCE CONNECTÉE
    // -----------------------------------------------------

    const currentAgency =
        agencies.find(
            agency =>
                Number(agency.id_agency) ===
                Number(currentAgencyId)
        );


    const timezone =
        getAgencyTimezone(currentAgency);


    // -----------------------------------------------------
    // CONVERTIR UTC → HEURE LOCALE
    // -----------------------------------------------------

    const parts =
        new Intl.DateTimeFormat(
            'en-CA',
            {
                timeZone: timezone,

                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            }
        ).formatToParts(utcDate);


    let year = '';
    let month = '';
    let day = '';


    parts.forEach(part => {

        if (part.type === 'year') {
            year = part.value;
        }

        if (part.type === 'month') {
            month = part.value;
        }

        if (part.type === 'day') {
            day = part.value;
        }

    });


    return `${year}-${month}-${day}`;

}


// =========================================================
// FORMATER LA DATE DU GROUPE
// =========================================================

function formatMessageDate(dateString) {

    if (!dateString) {
        return '';
    }


    // =====================================================
    // DATE DU GROUPE
    // =====================================================

    const [year, month, day] =
        dateString.split('-').map(Number);


    if (
        !year ||
        !month ||
        !day
    ) {
        return dateString;
    }


    // =====================================================
    // DATE LOCALE SANS CONVERSION UTC
    // =====================================================

    const messageDate =
        new Date(
            year,
            month - 1,
            day
        );


    if (isNaN(messageDate.getTime())) {
        return dateString;
    }


    // =====================================================
    // AUJOURD'HUI
    // =====================================================

    const today =
        new Date();

    today.setHours(
        0,
        0,
        0,
        0
    );


    // =====================================================
    // HIER
    // =====================================================

    const yesterday =
        new Date(today);

    yesterday.setDate(
        yesterday.getDate() - 1
    );


    // =====================================================
    // DEMAIN
    // =====================================================

    const tomorrow =
        new Date(today);

    tomorrow.setDate(
        tomorrow.getDate() + 1
    );


    // =====================================================
    // COMPARAISONS
    // =====================================================

    if (
        messageDate.getTime() ===
        today.getTime()
    ) {

        return "Aujourd'hui";

    }


    if (
        messageDate.getTime() ===
        yesterday.getTime()
    ) {

        return "Hier";

    }


    if (
        messageDate.getTime() ===
        tomorrow.getTime()
    ) {

        return "Demain";

    }


    // =====================================================
    // AUTRES DATES
    // =====================================================

    return messageDate.toLocaleDateString(
        'fr-FR',
        {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }
    );

}



    // =========================================================
    // CRÉER MESSAGE
    // =========================================================

    function createMessageElement(message) {

        const element =
            document.createElement(
                'div'
            );


        // =====================================================
        // IMPORTANT
        //
        // L'agence connectée = currentAgencyId
        //
        // L'agence sélectionnée = destinataire
        //
        // Donc pour savoir si "Vous" avez envoyé :
        //
        // message.id_sender === currentAgencyId
        // =====================================================

        const isSent =
            Number(
                message.id_sender
            ) ===
            Number(
                currentAgencyId
            );


        // =====================================================
        // MESSAGE REÇU
        // =====================================================

        if (!isSent) {

            element.classList.add(
                'message',
                'received'
            );


            element.innerHTML = `

                <div class="message-avatar received-avatar">
                    💬
                </div>

                <div class="message-wrapper">

                    <div class="message-meta">

                        <span>
                            ${formatTime(
                                message.created_at
                            )}
                        </span>

                    </div>

                    <div class="message-bubble received-bubble">

                        ${formatMessage(
                            message.message
                        )}

                    </div>

                    ${renderFile(
                        message,
                        'received'
                    )}

                </div>

            `;

        }


        // =====================================================
        // MESSAGE ENVOYÉ
        // =====================================================

        else {

            element.classList.add(
                'message',
                'sent'
            );


            element.innerHTML = `

                <div class="message-wrapper">

                    <div class="message-meta sent-meta">

                        <span>
                            ${formatTime(
                                message.created_at
                            )}
                        </span>

                        <strong>
                            Vous
                        </strong>

                    </div>

                    <div class="message-bubble sent-bubble">

                        ${formatMessage(
                            message.message
                        )}

                    </div>

                    ${renderFile(
                        message,
                        'sent'
                    )}

                    <div class="message-status">
                        Envoyé ✓✓
                    </div>

                </div>

                <div class="message-avatar sent-avatar">
                    👤
                </div>

            `;

        }


        return element;

    }


    // =========================================================
    // FICHIER
    // =========================================================

    function renderFile(message, type) {

        if (!message.file) {
            return '';
        }


        let fileName =
            message.file;


        // =====================================================
        // FILE = ARRAY / OBJECT
        // =====================================================

        if (
            typeof message.file ===
            'object'
        ) {

            if (
                Array.isArray(
                    message.file
                )
            ) {

                fileName =
                    message.file[0] ??
                    '';

            } else {

                fileName =
                    message.file.name ??
                    message.file.path ??
                    '';

            }

        }


        if (!fileName) {
            return '';
        }


        return `

            <div class="message-file ${type}-file">

                📎

                <div>

                    <strong>
                        ${escapeHtml(
                            fileName
                        )}
                    </strong>

                    <small>
                        Fichier joint
                    </small>

                </div>

                <button
                    type="button"
                    class="download-file"
                    data-file="${escapeHtml(
                        fileName
                    )}"
                >
                    ⬇
                </button>

            </div>

        `;

    }


    // =========================================================
    // HEURE
    // =========================================================

    function getAgencyTimezone(agency) {

    if (!agency) {
        return 'UTC';
    }

    const country =
        String(agency.country ?? '')
            .trim()
            .toLowerCase();


    // Cameroun
    if (
        country === 'cameroun' ||
        country === 'cameroon'
    ) {
        return 'Africa/Douala';
    }


    // France
    if (
        country === 'france'
    ) {
        return 'Europe/Paris';
    }


    // Inde
    if (
        country === 'inde' ||
        country === 'india'
    ) {
        return 'Asia/Kolkata';
    }


    // Chine
    if (
        country === 'chine' ||
        country === 'china'
    ) {
        return 'Asia/Shanghai';
    }


    // Par défaut
    return 'UTC';
}

    function formatTime(date) {

    if (!date) {
        return '';
    }

    // L'heure enregistrée en BDD est en UTC
    const utcDate = new Date(
        String(date).replace(' ', 'T') + 'Z'
    );

    if (isNaN(utcDate.getTime())) {
        return '';
    }

    // Fuseau de l'agence CONNECTÉE
    const currentAgency =
        agencies.find(
            agency =>
                Number(agency.id_agency) ===
                Number(currentAgencyId)
        );

    const timezone =
        getAgencyTimezone(currentAgency);

    return utcDate.toLocaleTimeString(
        'fr-FR',
        {
            timeZone: timezone,
            hour: '2-digit',
            minute: '2-digit'
        }
    );
}

    // =========================================================
    // MESSAGE
    // =========================================================

    function formatMessage(text) {

        if (!text) {
            return '';
        }


        return escapeHtml(
            text
        ).replace(
            /\n/g,
            '<br>'
        );

    }


    // =========================================================
    // SÉCURITÉ HTML
    // =========================================================

    function escapeHtml(value) {

        return String(value)

            .replace(
                /&/g,
                '&amp;'
            )

            .replace(
                /</g,
                '&lt;'
            )

            .replace(
                />/g,
                '&gt;'
            )

            .replace(
                /"/g,
                '&quot;'
            )

            .replace(
                /'/g,
                '&#039;'
            );

    }


    // =========================================================
    // ENVOYER MESSAGE
    // =========================================================

    const messageForm =
        document.getElementById(
            'message'
        );


    if (messageForm) {

        messageForm.addEventListener(
            'submit',
            async function (event) {

                event.preventDefault();


                // =================================================
                // AGENCE DESTINATAIRE
                // =================================================

                if (!selectedAgency) {

                    alert(
                        'Veuillez sélectionner une agence.'
                    );

                    return;
                }


                // =================================================
                // AGENCE CONNECTÉE
                // =================================================

                if (!currentAgencyId) {

                    alert(
                        'Impossible de déterminer l’agence connectée.'
                    );

                    console.error(
                        'currentAgencyId =',
                        currentAgencyId
                    );

                    return;
                }


                // =================================================
                // TOKEN
                // =================================================

                if (!token) {

                    alert(
                        'Votre session a expiré.'
                    );

                    return;
                }


                // =================================================
                // INPUT
                // =================================================

                const messageInput =
                    document.getElementById(
                        'messageInput'
                    );


                if (!messageInput) {

                    console.error(
                        '#messageInput introuvable'
                    );

                    return;
                }


                const message =
                    messageInput.value.trim();


                if (!message) {
                    return;
                }


                // =================================================
                // DESTINATAIRE
                // =================================================

                const id_receive =
                    Number(
                        selectedAgency.id_agency
                    );


                if (
                    !id_receive ||
                    id_receive <= 0
                ) {

                    alert(
                        'Agence destinataire invalide.'
                    );

                    return;
                }


                console.log(
                    'Envoi message :',
                    {
                        id_sender:
                            currentAgencyId,

                        id_receive:
                            id_receive,

                        message:
                            message
                    }
                );


                // =================================================
                // BOUTON
                // =================================================

                const submitButton =
                    messageForm.querySelector(
                        'button[type="submit"]'
                    );


                if (submitButton) {

                    submitButton.disabled =
                        true;

                }


                try {

                    // =================================================
                    // DONNÉES
                    //
                    // NE PAS envoyer id_sender.
                    //
                    // Le Controller le récupère depuis JWT.
                    // =================================================

                    const data = {

                        subject: '',

                        message:
                            message,

                        id_receive:
                            id_receive

                    };


                    // =================================================
                    // POST
                    // =================================================

                    const response =
                        await fetch(
                            '/api-siis/routes/messaging.php',
                            {
                                method: 'POST',

                                headers: {

                                    'Content-Type':
                                        'application/json',

                                    'Authorization':
                                        'Bearer ' + token

                                },

                                body:
                                    JSON.stringify(
                                        data
                                    )

                            }
                        );


                    if (!response.ok) {

                        throw new Error(
                            `Erreur HTTP : ${response.status}`
                        );

                    }


                    const result =
                        await response.json();


                    console.log(
                        'Réponse POST :',
                        result
                    );


                    // =================================================
                    // ERREUR
                    // =================================================

                    if (!result.success) {

                        alert(
                            result.message ||
                            'Impossible d’envoyer le message.'
                        );

                        return;
                    }


                    // =================================================
                    // SUCCÈS
                    // =================================================

                    messageInput.value = '';


                    // Recharger la conversation
                    await loadMessages(
                        selectedAgency.id_agency
                    );


                } catch (error) {

                    console.error(
                        'Erreur envoi message :',
                        error
                    );


                    alert(
                        'Impossible d’envoyer le message.'
                    );

                } finally {

                    if (submitButton) {

                        submitButton.disabled =
                            false;

                    }

                }

            }
        );

    }


    // =========================================================
    // BOUTON FICHIER
    // =========================================================

    const fileButton =
        document.getElementById(
            'fileButton'
        );


    const fileInput =
        document.getElementById(
            'fileInput'
        );


    if (fileButton) {

        fileButton.addEventListener(
            'click',
            function () {

                if (fileInput) {

                    fileInput.click();

                }

            }
        );

    }


    // =========================================================
    // FICHIER SÉLECTIONNÉ
    // =========================================================

    if (fileInput) {

        fileInput.addEventListener(
            'change',
            function () {

                const file =
                    fileInput.files[0];


                if (!file) {
                    return;
                }


                console.log(
                    'Fichier sélectionné :',
                    file.name
                );

            }
        );

    }


    // =========================================================
    // DÉMARRAGE
    // =========================================================

    loadAgency();

})();

