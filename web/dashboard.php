<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>ERP Entreprise</title>

        <link href="./assets/img/logo.png" class="logo icon" rel="icon">
        <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="./assets/vendor/fontawesome-free-6.7.2-web/css/all.min.css" rel="stylesheet">
        <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
        <link href="./assets/vendor/datatables/datatables.bootstrap4.min.css" rel="stylesheet">
        <link href="./assets/css/style.css" rel="stylesheet">

    </head>
    <body>

        <header class="header">
            <div class="name">
                <i class="fa-solid fa-building"></i>
                SIIS ERP
            </div>

            <div class="user">
                <img src="./assets/img/logo.png">
                <button id="btnLogout" class="btn btn-light">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </button>
            </div>

        </header>

        <div id="barreTabs">
            <div id="tabs">
                <div class="tab active" data-page="home">
                    <i class="fa fa-home"></i>
                   Modules page
                </div>
            </div>

            <div id="tools">
                <button id="btnCalc" onclick="toggleCalc()">
                    <i class="fa-solid fa-calculator"></i>
                </button>
            </div>
        </div>

        <div id="calculatrice">
            <div class="calcHeader">
                <span>Calculator</span>
                <button class="calcClose" onclick="toggleCalc()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <input type="text" id="calcDisplay" class="calcDisplay" readonly>

            <div class="calcGrid">

                <button onclick="ajouter('7')">7</button>
                <button onclick="ajouter('8')">8</button>
                <button onclick="ajouter('9')">9</button>
                <button class="operateur" onclick="ajouter('/')">÷</button>

                <button onclick="ajouter('4')">4</button>
                <button onclick="ajouter('5')">5</button>
                <button onclick="ajouter('6')">6</button>
                <button class="operateur" onclick="ajouter('*')">×</button>

                <button onclick="ajouter('1')">1</button>
                <button onclick="ajouter('2')">2</button>
                <button onclick="ajouter('3')">3</button>
                <button class="operateur" onclick="ajouter('-')">−</button>

                <button onclick="ajouter('0')">0</button>
                <button onclick="ajouter('.')">.</button>
                <button class="egale" onclick="calculer()">=</button>
                <button class="operateur" onclick="ajouter('+')">+</button>

                <button class="effacer" style="grid-column:span 4;" onclick="effacer()">
                    Delete
                </button>

            </div>

        </div>

        <section class="menu" id="menu">

            <div class="module" onclick="ouvrirModule('Setting','./modules/setting.php')">
                <i class="fa-solid fa-gear"></i>
                <p>Setting</p>
            </div>

            <div class="module" onclick="ouvrirModule('Branch','./modules/branch.php')">
                <i class="fa-solid fa-building-user"></i>
                <p>Branch</p>
            </div>

            <div class="module" onclick="ouvrirModule('staff','./modules/staff.php')">
                <i class="fa-solid fa-users"></i>
                <p>staff</p>
            </div>

            <div class="module" onclick="ouvrirModule('Permission','./modules/permission.php')">
                <i class="fa-solid fa-calendar-days"></i>
                <p>Permission</p>
            </div>

            <div class="module" onclick="ouvrirModule('Payment','./modules/payment.php')">
                <i class="fa-solid fa-money-check-dollar"></i>
                <p>Payment</p>
            </div>


            <div class="module" onclick="ouvrirModule('Messaging','./modules/messaging.php')">
                <i class="fa-solid fa-comments"></i>
                <p>Messaging</p>
            </div>

            <div class="module" onclick="ouvrirModule('Web site','./modules/web_site.php')">
                <i class="fa-solid fa-window-maximize"></i>
                <p>Web site</p>
            </div>

            <div class="module" onclick="ouvrirModule('CRM','./modules/CRM.php')">
                <i class="fa-solid fa-handshake"></i>
                <p>CRM</p>
            </div>

            <div class="module" onclick="ouvrirModule('CO-drive','./modules/codrive.php')">
                <i class="fa-solid fa-car"></i>
                <p>CO-drive</p>
            </div>

<!--             <div class="module" onclick="ouvrirModule('Recrutement','./modules/recrutement.php')">
                <i class="fa-solid fa-briefcase"></i>
                <p>Recrutement</p>
            </div> -->

        </section>

        <section id="contenu"></section>

        <footer class="status">
            <div>Connected : <b>Admin</b></div>
            <div>Version 1.0</div>

        </footer>
        <script src="./assets/vendor/jquery/jquery.min.js"></script>
        <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="./assets/vendor/admin-2/sb-admin-2.min.js"></script>
        <script src="./assets/vendor/datatables/jquery.datatables.min.js"></script>
        <script src="./assets/vendor/datatables/datatables.bootstrap4.min.js"></script>
        <script src="./assets/vendor/datatables/datatables-demo.js"></script>
        <script src="./assets/vendor/noBack/noBack.js"></script>
        <script>
            const menu=document.getElementById("menu");
            const tabs=document.getElementById("tabs");
            const contenu=document.getElementById("contenu");
            const accueil = document.querySelector(".tab");

            accueil.onclick = () => activer(accueil);

            function ouvrirModule(nom,page){

                let existe=document.querySelector("[data-page='"+page+"']");

                if(existe){

                activer(existe);

                return;

            }

                let tab=document.createElement("div");

                tab.className="tab";

                tab.dataset.page=page;

                tab.innerHTML=`${nom}<span class="closed"> ✖</span>`;

                tabs.appendChild(tab);

                activer(tab);

                tab.onclick=function(e){

                    if(e.target.classList.contains("closed")){
                      fermer(tab);
                    }
                    else{
                    activer(tab);

                    }
                }

            }

            function activer(tab){

                document.querySelectorAll(".tab").forEach(t=>t.classList.remove("active"));
                tab.classList.add("active");

                if(tab.dataset.page==="home"){

                    menu.style.display="grid";
                    contenu.style.display="none";
                    return;

                }

                menu.style.display="none";
                contenu.style.display="block";

                charger(tab.dataset.page);

            }

            function charger(page){

                fetch(page)
                .then(r=>r.text())
                .then(html=>{

                    contenu.innerHTML=html;

                    if(page.includes("web_site.php")){

                        let script=document.createElement("script");
                        script.src="./assets/js/web_site.js";

                        contenu.appendChild(script);

                    }

                });

            }

            function fermer(tab){

                let actif = tab.classList.contains("active");

                tab.remove();

                if(actif){

                    let dernier = document.querySelector(".tab:last-child");

                    if(dernier){

                        activer(dernier);

                    }

                }

            }
            let calcOuverte = false;

            function toggleCalc(){

                let calc=document.getElementById("calculatrice");

                calcOuverte = !calcOuverte;

                if(calc.style.display=="block"){

                    calc.style.display="none";

                }else{

                    calc.style.display="block";

                }

            }

            function ajouter(val){

                document.getElementById("calcDisplay").value+=val;

            }

            function effacer(){

                document.getElementById("calcDisplay").value="";

            }

            function calculer(){

                let e=document.getElementById("calcDisplay");

                try{

                    e.value=eval(e.value);

                }catch{

                    e.value="Erreur";

                }

            }

            document.addEventListener("keydown",function(e){

                if(!calcOuverte){
                    return;
                }

                let d=document.getElementById("calcDisplay");

                if(!document.getElementById("calculatrice")) return;

                if(/[0-9+\-*/.]/.test(e.key)){

                    d.value+=e.key;

                }

                if(e.key==="Enter"){

                    e.preventDefault();

                    calculer();

                }

                if(e.key==="Backspace"){

                    d.value=d.value.slice(0,-1);

                }

                if(e.key==="Escape"){

                    effacer();

                }

            });


            document.getElementById("btnLogout").addEventListener('click', function(e){
                e.preventDefault()
                localStorage.removeItem('token');
                window.location.href = './login.php';
            })

        </script>

    </body>
</html>