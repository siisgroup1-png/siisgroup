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

            if (!isNaN(id)) {
                return id;
            }
        }


        // -----------------------------------------------------
        // 2. JWT
        // -----------------------------------------------------

        if (token) {

            try {

                const parts = token.split('.');

                if (parts.length === 3) {

                    const payload =
                        JSON.parse(
                            atob(
                                parts[1]
                                    .replace(/-/g, '+')
                                    .replace(/_/g, '/')
                            )
                        );

                    const id =
                        payload.id_agency ??
                        payload.agency_id ??
                        payload.id;

                    if (id !== undefined) {

                        const agencyId =
                            Number(id);

                        if (!isNaN(agencyId)) {
                            return agencyId;
                        }
                    }
                }

            } catch (error) {

                console.error(
                    'Impossible de lire le token :',
                    error
                );

            }
        }

        return null;
    }


    currentAgencyId =
        getCurrentAgencyId();


    console.log(
        'Agence connectée :',
        currentAgencyId
    );


    // =========================================================
    // CHARGER LES AGENCES
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


            if (
                !agencyRes.success ||
                !Array.isArray(agencyRes.data)
            ) {

                console.error(
                    'Réponse agence incorrecte :',
                    agencyRes
                );

                return;
            }


            agencies =
                agencyRes.data;


            // =====================================================
            // CONTENEUR DES ONGLETS
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


            tabsContainer.innerHTML = '';


            // =====================================================
            // CRÉER LES ONGLETS
            // =====================================================

            agencies.forEach(
                (agency) => {

                    // Ne pas afficher l'agence connectée
                    if (
                        currentAgencyId &&
                        Number(agency.id_agency) ===
                        Number(currentAgencyId)
                    ) {
                        return;
                    }


                    const button =
                        document.createElement(
                            'button'
                        );


                    button.type =
                        'button';


                    button.classList.add(
                        'tab-button'
                    );


                    button.dataset.tab =
                        agency.id_agency;


                    button.textContent =
                        agency.login;


                    tabsContainer.appendChild(
                        button
                    );

                }
            );


            // =====================================================
            // SÉLECTIONNER LA PREMIÈRE AGENCE
            // =====================================================

            const firstAgency =
                agencies.find(
                    agency =>
                        !currentAgencyId ||
                        Number(agency.id_agency) !==
                        Number(currentAgencyId)
                );


            if (firstAgency) {

                const firstButton =
                    tabsContainer.querySelector(
                        `[data-tab="${firstAgency.id_agency}"]`
                    );


                if (firstButton) {

                    firstButton.classList.add(
                        'active'
                    );

                }


                selectAgency(
                    firstAgency
                );

            }

        } catch (error) {

            console.error(
                'Erreur lors du chargement des agences :',
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
                    '.tab-button'
                );


            if (!button) {
                return;
            }


            // -------------------------------------------------
            // Retirer active
            // -------------------------------------------------

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


            // -------------------------------------------------
            // Ajouter active
            // -------------------------------------------------

            button.classList.add(
                'active'
            );


            // -------------------------------------------------
            // ID agence destinataire
            // -------------------------------------------------

            const idAgency =
                button.dataset.tab;


            const agency =
                agencies.find(
                    item =>
                        String(item.id_agency) ===
                        String(idAgency)
                );


            if (!agency) {

                console.error(
                    'Agence introuvable :',
                    idAgency
                );

                return;
            }


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
            'Agence destinataire :',
            selectedAgency
        );


        displayAgency(
            agency
        );


        loadMessages(
            agency.id_agency
        );

    }


    // =========================================================
    // AFFICHER LES INFORMATIONS DE L'AGENCE
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
    // CHARGER LA CONVERSATION
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


        messagesContainer.innerHTML = `

            <div class="loading-messages">
                Chargement des messages...
            </div>

        `;


        try {

            const response =
                await fetch(
                    `/api-siis/routes/messaging.php?id_agency=${encodeURIComponent(idReceive)}`,
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


            const result =
                await response.json();


            console.log(
                'Conversation reçue :',
                result
            );


            if (
                !result.success ||
                !Array.isArray(result.data)
            ) {

                messagesContainer.innerHTML = `

                    <div class="no-messages">
                        Aucun message trouvé.
                    </div>

                `;

                return;
            }


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

    function renderMessages(messages) {

        const container =
            document.getElementById(
                'messages'
            );


        if (!container) {
            return;
        }


        container.innerHTML = '';


        if (messages.length === 0) {

            container.innerHTML = `

                <div class="no-messages">
                    Aucun message pour cette agence.
                </div>

            `;

            return;
        }


        messages.forEach(
            message => {

                const element =
                    createMessageElement(
                        message
                    );


                container.appendChild(
                    element
                );

            }
        );


        container.scrollTop =
            container.scrollHeight;

    }


    // =========================================================
    // CRÉER UN ÉLÉMENT MESSAGE
    // =========================================================

    function createMessageElement(message) {

        const element =
            document.createElement(
                'div'
            );


        // =====================================================
        // IMPORTANT
        //
        // id_sender = agence qui a envoyé
        // currentAgencyId = agence connectée
        //
        // Même si selectedAgency est l'agence destinataire,
        // elle ne doit PAS servir ici.
        // =====================================================

        const isSent =
            Number(message.id_sender) ===
            Number(currentAgencyId);


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

                        <strong>
                            ${escapeHtml(
                                message.sender_name ??
                                'Agence'
                            )}
                        </strong>

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
    // AFFICHER LE FICHIER
    // =========================================================

    function renderFile(message, type) {

        // Ta BDD et ton modèle utilisent "file"
        if (!message.file) {
            return '';
        }


        let fileName =
            message.file;


        // Si PHP retourne un tableau
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
                    message.file[0] ?? '';

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
    // FORMATER L'HEURE
    // =========================================================

    function formatTime(date) {

        if (!date) {
            return '';
        }


        const d =
            new Date(
                String(date).replace(
                    ' ',
                    'T'
                )
            );


        if (isNaN(d.getTime())) {
            return '';
        }


        return d.toLocaleTimeString(
            'fr-FR',
            {
                hour: '2-digit',
                minute: '2-digit'
            }
        );

    }


    // =========================================================
    // FORMATER LE MESSAGE
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
    // SÉCURISER HTML
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
    // ENVOYER UN MESSAGE
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
                // VÉRIFIER DESTINATAIRE
                // =================================================

                if (!selectedAgency) {

                    alert(
                        'Veuillez sélectionner une agence.'
                    );

                    return;
                }


                // =================================================
                // VÉRIFIER AGENCE CONNECTÉE
                // =================================================

                if (!currentAgencyId) {

                    alert(
                        'Impossible de déterminer l’agence connectée.'
                    );

                    return;
                }


                // =================================================
                // INPUT MESSAGE
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
                // ID DESTINATAIRE
                // =================================================

                const id_receive =
                    Number(
                        selectedAgency.id_agency
                    );


                console.log(
                    'Envoi :',
                    {
                        id_receive:
                            id_receive,

                        message:
                            message
                    }
                );


                // =================================================
                // BOUTON ENVOYER
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
                    // DONNÉES ENVOYÉES AU CONTRÔLEUR
                    //
                    // Le contrôleur récupère lui-même :
                    //
                    // $id_sender =
                    //     $this->agency->id_agency;
                    //
                    // Donc on ne met PAS id_sender ici.
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
                    // ERREUR API
                    // =================================================

                    if (!result.success) {

                        alert(
                            result.message ||
                            'Impossible d’envoyer le message.'
                        );

                        return;
                    }


                    // =================================================
                    // VIDER LE TEXTAREA
                    // =================================================

                    messageInput.value = '';


                    // =================================================
                    // RECHARGER LA CONVERSATION
                    // =================================================

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


    if (fileButton) {

        fileButton.addEventListener(
            'click',
            function () {

                const fileInput =
                    document.getElementById(
                        'fileInput'
                    );


                if (fileInput) {

                    fileInput.click();

                }

            }
        );

    }


    // =========================================================
    // DÉMARRAGE
    // =========================================================

    loadAgency();

})();