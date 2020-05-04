$(document).ready(function(){
// On cache le bouton record
$('.record').hide();

// Fonction de lecture / lecture mot par mot / arrêt de lecture
$('#lecture').click(function () {
    var texte = $('#phrase').html();

    var parole = new SpeechSynthesisUtterance();

    parole.text = texte;
    parole.pitch = 1; // 0 à 2 = hauteur
    parole.rate = 1; // 0.5 à 2 = vitesse
    parole.volume = 0.5; // 0 à 1 = volume
    parole.lang = 'fr-FR'; // Language
    // Faire parler
    speechSynthesis.speak(parole);
});

$('#lectureMot').click(function () {
    var texte = $('#phrase').html();
    var tab = texte.split(' ');
    var searchVide = tab.indexOf('');
    while (searchVide != -1) {
        tab.splice(searchVide, 1);
        searchVide = tab.indexOf('');
    }

    var parole = new SpeechSynthesisUtterance();
    parole.pitch = 1;
    parole.rate = 1;
    parole.volume = 0.5;
    parole.lang = 'fr-FR';

    function speakMot() {
        var i = 0;
        frame();
        var id = setInterval(frame, 1500);

        function frame() {
            if (i >= tab.length) {
                clearInterval(id);
            } else {
                var mot = tab[i];
                parole.text = mot;
                speechSynthesis.speak(parole);
                i = i + 1;
            }
        }
    }

    speakMot();

    $('#chut').click(function () {
        speechSynthesis.cancel();
    });

});

$('#chut').click(function () {
    speechSynthesis.cancel();
});

// Si c'est le navigateur Chrome on active la fonction pour enregistrer avec le micro.
var chrome = /Google Inc/.test(navigator.vendor);
if (chrome == true) {

    $('.record').show();

    var reconnaissance = new(window.webkitSpeechRecognition)();
    reconnaissance.lang = 'fr-FR';
    reconnaissance.continuous = true;

    reconnaissance.onresult = function (event) {
        $('#phrase').html(event.results[0][0].transcript);
    };

    $('#enregistrer').click(function () {
        reconnaissance.start();
    });

    $('#stop').click(function () {
        reconnaissance.stop();
    });

}

// On crée les variables à vide

var msg = '';
var dragPos = '';
var target1 = '';
var target2 = '';
var target3 = '';
var target4 = '';
var target5 = '';
var t = '';
var pos = '';
var id = '';
var c = '';

// On récupère les infos du picto qui est bouger avec "mouseup"
$('.drag').mouseup(function(){
    c = $(this);
    id = $(this).attr('id');
})

// On fait en sorte de pouvoir bouger les pictogrammes
$(".drag").draggable({
    snap: '.target',
    snapMode: 'inner',
    snapTolerance: 40,
    center: true,
});

// On cache le texte sous les pictogrammes
$('.drag-text').hide();

// On enregistre la position de base des pictogrammes
var drag = $('.drag');
var tabDrag = new Array();

for (i = 0; i < drag.length; i++) {
    var pos = $(drag[i]).offset();
    tabDrag.push(pos);
}

// Chaque target (la on glisse les pictos)
$('#target1').droppable({
    accept: '.drag',
    drop: function () {
        t = $(this).attr('id');
        target1 = $(c).children('.drag-text').html();
    }
})
$('#target2').droppable({
    accept: '.drag',
    drop: function () {
        t = $(this).attr('id');
        target2 = $(c).children('.drag-text').html();
    }
})
$('#target3').droppable({
    accept: '.drag',
    drop: function () {
        t = $(this).attr('id');
        target3 = $(c).children('.drag-text').html();
    }
})
$('#target4').droppable({
    accept: '.drag',
    drop: function () {
        t = $(this).attr('id');
        target4 = $(c).children('.drag-text').html();
    }
})
$('#target5').droppable({
    accept: '.drag',
    drop: function () {
        t = $(this).attr('id');
        target5 = $(c).children('.drag-text').html();
    }
})

// Zone de départ des pictogrammes
$('#listeimage1').droppable({
    accept: '.drag',
    drop: function () {
        if (target1 == $(c).children('.drag-text').html()) {
            target1 = '';
        }
        if (target2 == $(c).children('.drag-text').html()) {
            target2 = '';
        }
        if (target3 == $(c).children('.drag-text').html()) {
            target3 = '';
        }
        if (target4 == $(c).children('.drag-text').html()) {
            target4 = '';
        }
        if (target5 == $(c).children('.drag-text').html()) {
            target5 = '';
        }
        var i = 0;
        while (i < drag.length) {
            if (id == drag[i].id) {
                var n = tabDrag[i].left;
                var z = tabDrag[i].top;
                $(drag[i]).offset({
                    top: z,
                    left: n,
                })
                var test 
            }
            i++;
        }
        msg = target1 + ' ' + target2 + ' ' + target3 + ' ' + target4 + ' ' + target5;
        nom();
        $(c).children('.drag-text').removeClass('et');
        $('#phrase').html(msg);
    }
})

// Zone de la cible des pictogrammes (pour créer la phrase)
$('#drop').droppable({
    accept: '.drag',
    drop: function () {
        target();
        msg = target1 + ' ' + target2 + ' ' + target3 + ' ' + target4 + ' ' + target5;
        nom();
        $('#phrase').html(msg);
        var sujet = sujetFind();
        var verbe = verbeFind(sujet);

        if ((sujet != undefined) && (verbe != undefined)){
            var newPhrase = conjugaison(sujet,verbe);
            $('#phrase').html(newPhrase);
        }
    }
})

// La poubelle pour supprimer la phrase
$('#poubelle').click(function () {
    target1 = '';
    target2 = '';
    target3 = '';
    target4 = '';
    target5 = '';

    var i = 0;
    while (i < drag.length) {
        var n = tabDrag[i].left;
        var z = tabDrag[i].top;
        $(drag[i]).offset({
            top: z,
            left: n
        })
        i++;
    }

    msg = target1 + ' ' + target2 + ' ' + target3 + ' ' + target4 + ' ' + target5;
    $('#phrase').html(msg);
})

// La fonction pour glisser un seul picto dans la poubelle et le supprimer de la phrase pour le remettre dans la zone1
$('#poubelle').droppable({
    accept: '.drag',
    drop: function () {
        if (target1 == $(c).children('.drag-text').html()) {
            target1 = '';
        }
        if (target2 == $(c).children('.drag-text').html()) {
            target2 = '';
        }
        if (target3 == $(c).children('.drag-text').html()) {
            target3 = '';
        }
        if (target4 == $(c).children('.drag-text').html()) {
            target4 = '';
        }
        if (target5 == $(c).children('.drag-text').html()) {
            target5 = '';
        }
        var i = 0;
        while (i < drag.length) {
            if (id == drag[i].id) {
                var n = tabDrag[i].left;
                var z = tabDrag[i].top;
                $(c).offset({
                    top: z,
                    left: n
                })
            }
            i++;
        }
        msg = target1 + ' ' + target2 + ' ' + target3 + ' ' + target4 + ' ' + target5;
        $('#phrase').html(msg);
    }
})

// Fonction pour passer la phrase en négatif
$('#negative').click(function () {
    var phrase = $('#phrase').html();
    var phraseDe = phrase.split(' ');
    var sujetTab = ['je', 'tu', 'il', 'nous', 'vous', 'ils'];
    var verbeTab = ['mange', 'veux'];

    var findNe = phraseDe.indexOf('ne');
    var findPas = phraseDe.indexOf('pas');

    if ((findNe == -1) && (findPas == -1)){

    for (i = 0; i < sujetTab.length; i++) {
        var posSujet = phraseDe.indexOf(sujetTab[i]);
        if (posSujet != -1) {
            var sujetFind = posSujet;
        }
    }
    for (i = 0; i < verbeTab.length; i++){
        var posVerbe = phraseDe.indexOf(verbeTab[i]);
        if (posVerbe != -1) {
            var verbeFind = posVerbe;
        }
    }
    if ((sujetFind != undefined) && (verbeFind != undefined)) {
        phraseDe.splice(sujetFind + 1, 0, "ne");
        phraseDe.splice(verbeFind + 2, 0, "pas");
    }

    }

    $('#phrase').html(phraseDe.join(' '));

})

// Fonction pour passer la phrase en affimartif
$('#affirmatif').click(function () {
    var phrase = $('#phrase').html();
    var phraseDe = phrase.split(' ');

    var findNe = phraseDe.indexOf('ne');
    if (findNe != -1){
        phraseDe.splice(findNe,1);
    }

    var findPas = phraseDe.indexOf('pas');
    if (findPas != -1){
        phraseDe.splice(findPas,1);
    }

    $('#phrase').html(phraseDe.join(' '));

})

// Fonction pour éviter le dédoublement de mot
function target() {
    if (t == 'target1') {
        if (target1 == target2) {
            target2 = '';
        }
        if (target1 == target3) {
            target3 = '';
        }
        if (target1 == target4) {
            target4 = '';
        }
        if (target1 == target5) {
            target5 = '';
        }
    }
    if (t == 'target2') {
        if (target2 == target1) {
            target1 = '';
        }
        if (target2 == target3) {
            target3 = '';
        }
        if (target2 == target4) {
            target4 = '';
        }
        if (target2 == target5) {
            target5 = '';
        }
    }
    if (t == 'target3') {
        if (target3 == target1) {
            target1 = '';
        }
        if (target3 == target2) {
            target2 = '';
        }
        if (target3 == target4) {
            target4 = '';
        }
        if (target3 == target5) {
            target5 = '';
        }
    }
    if (t == 'target4') {
        if (target4 == target1) {
            target1 = '';
        }
        if (target4 == target2) {
            target2 = '';
        }
        if (target4 == target3) {
            target3 = '';
        }
        if (target4 == target5) {
            target5 = '';
        }
    }
    if (t == 'target5') {
        if (target5 == target1) {
            target1 = '';
        }
        if (target5 == target2) {
            target2 = '';
        }
        if (target5 == target3) {
            target3 = '';
        }
        if (target5 == target4) {
            target4 = '';
        }
    }
}

// Fonction pour detecter un nom et la position pour lui donner le déterminant et rajouter un "et" entre deux noms
function nom(){
    var tabNom = new Array();
    var tabPos = new Array();
    var phraseDe = msg.split(' ').map(v => v.toLowerCase());
    var z = phraseDe.length;
    var i = 0;
    var s = tabDrag.length;
    while (i < z){
        var x = 0;
        var mot = phraseDe[i];
        while (x < s){
            var test = $(drag[x]).children('.drag-text').html();
            if (test == mot){
                var test2 = $(drag[x]).children('.drag-text').attr('class');
                var testClass = test2.split(' ');
                for (b = 0; b < testClass.length; b++){
                    var nameClass = testClass[b];
                    if (nameClass == 'nom'){
                        var nom = $(drag[x]);
                        tabNom.push(nom);
                    }
                }
            }
            x = x + 1;
        }
        i = i + 1;
    }
    if (tabNom.length > 1){
        var i = 0;
        var test = phraseDe.indexOf(tabNom[i].children('.drag-text').html());
        if (test != -1){
            $(tabNom[i]).children('.drag-text').addClass('et');
        }
    } else {
        var z = 0;
        while (z < tabNom.length){
            $(tabNom[z]).children('.drag-text').removeClass('et');
            z = z + 1;
        }
    }
    var k = 0;
    while (k < tabNom.length){
        var test = $(tabNom[k]).children('.drag-text').attr('class');
        var testDe = test.split(' ');
        for(i = 0; testDe.length > i;i++){
            var testClass = testDe[i];
            if (testClass == 'un'){
                var mySearch = $(tabNom[k]).children('.drag-text').html();
                var pos = phraseDe.indexOf(mySearch);
                phraseDe.splice(pos,0,'un');
            }
            if (testClass == 'une'){
                var mySearch = $(tabNom[k]).children('.drag-text').html();
                var pos = phraseDe.indexOf(mySearch);
                phraseDe.splice(pos,0,'une');
            }
            if (testClass == 'et'){
                var mySearch = $(tabNom[k]).children('.drag-text').html();
                var pos = phraseDe.indexOf(mySearch);
                phraseDe.splice(pos+1,0,'et');                
            }
        }
        k = k + 1;
    }
    msg = phraseDe.join(' ');
}

// Fonction pour trouver un sujet dans une phrase
function sujetFind(){
    var tabSujet = Array();
    var phrase = $('#phrase').html();
    var phraseDe = phrase.split(' ');
    for (i = 0; i < phraseDe.length; i++){
        var mot1 = phraseDe[i];
        for (z = 0; z < tabDrag.length; z++){
            var mot2 = $(drag[z]).children('.drag-text').html();
            if (mot1 === mot2){
                var sujet = $(drag[z]).children('.drag-text').hasClass('sujet');
                if (sujet === true){
                    tabSujet.push(mot2);
                }
            }
        }
    }
    if (tabSujet[0] != ''){
        var sujet = tabSujet[0];
    } else {
        var sujet = null;
    }
    return sujet;
}

// Fonction pour trouver un verbe dans une phrase
function verbeFind(sujet){
    var phrase = $('#phrase').html();
    var phraseDe = phrase.split(' ');
    var indexSujet = phraseDe.indexOf(sujet);
    var verbeT = phraseDe[indexSujet+1];
    for (z = 0; z < tabDrag.length; z++){
        var mot2 = $(drag[z]).children('.drag-text').html();
        if (mot2 === verbeT){
            var testV = $(drag[z]).children('.drag-text').hasClass('verbe');
        }
        if (testV === true){
            var verbe = verbeT;
        }
    }
    return verbe;
}

// Fonction pour conjuguer les verbes
function conjugaison(sujet,verbe){
    var phrase = $('#phrase').html();
    if (sujet === "je"){
        if (verbe === "accrocher"){
            phrase = phrase.replace(verbe,'accroche');
        }
        if (verbe === "aller"){
            phrase = phrase.replace(verbe,'vais');
        }
        if (verbe === "attendre"){
            phrase = phrase.replace(verbe,'attends');
            phrase = phrase.replace(sujet,'j\'');
        }
        if (verbe === "asseoir"){
            phrase = phrase.replace(verbe,'assois');
        }
        if (verbe === "avoir"){
            phrase = phrase.replace(verbe,'ai');
        }
        if (verbe === "baigner"){
            phrase = phrase.replace(verbe,'baigne');
        }
        if (verbe === "boire"){
            phrase = phrase.replace(verbe,'bois');
        }
        if (verbe === "changer"){
            phrase = phrase.replace(verbe,'change');
        }
        if (verbe === "chanter"){
            phrase = phrase.replace(verbe,'chante');
        }
        if (verbe === "chatouiller"){
            phrase = phrase.replace(verbe,'chatouille');
        }
        if (verbe === "communiquer"){
            phrase = phrase.replace(verbe,'communique');
        }
        if (verbe === "couper"){
            phrase = phrase.replace(verbe,'coupe');
        }
        if (verbe === "courir"){
            phrase = phrase.replace(verbe,'cours');
        }
        if (verbe === "dessiner"){
            phrase = phrase.replace(verbe,'dessine');
        }
        if (verbe === "dire"){
            phrase = phrase.replace(verbe,'dis');
        }
        if (verbe === "donner"){
            phrase = phrase.replace(verbe,'donne');
        }
        if (verbe === "dormir"){
            phrase = phrase.replace(verbe,'dors');
        }
        if (verbe === "doucher"){
            phrase = phrase.replace(verbe,'douche');
        }
        if (verbe === "ecouter"){
            phrase = phrase.replace(verbe,'ecoute');
            phrase = phrase.replace(sujet,'j\'');
        }
        if (verbe === "ecrire"){
            phrase = phrase.replace(verbe,'ecris');
            phrase = phrase.replace(sujet,'j\'');
        }
        if (verbe === "entrer"){
            phrase = phrase.replace(verbe,'entre');
            phrase = phrase.replace(sujet,'j\'');
        }
        if (verbe === "eviter"){
            phrase = phrase.replace(verbe,'evite');
            phrase = phrase.replace(sujet,'j\'');
        }
        if (verbe === "faire"){
            phrase = phrase.replace(verbe,'fais');
        }
        if (verbe === "fermer"){
            phrase = phrase.replace(verbe,'ferme');
        }
        if (verbe === "glisser"){
            phrase = phrase.replace(verbe,'glisse');
        }
        if (verbe === "grimper"){
            phrase = phrase.replace(verbe,'grimpe');
        }
        if (verbe === "grossir"){
            phrase = phrase.replace(verbe,'grossis');
        }
        if (verbe === "jouer"){
            phrase = phrase.replace(verbe,'joue');
        }
        if (verbe === "lancer"){
            phrase = phrase.replace(verbe,'lance');
        }
        if (verbe === "lire"){
            phrase = phrase.replace(verbe,'lis');
        }
        if (verbe === "maigrir"){
            phrase = phrase.replace(verbe,'maigris');
        }
        if (verbe === "manger"){
            phrase = phrase.replace(verbe,'mange');
        }
        if (verbe === "monter"){
            phrase = phrase.replace(verbe,'monte');
        }
        if (verbe === "nager"){
            phrase = phrase.replace(verbe,'nage');
        }
        if (verbe === "crier"){
            phrase = phrase.replace(verbe,'crie');
        }
        if (verbe === "ouvrir"){
            phrase = phrase.replace(verbe,'ouvre');
            phrase = phrase.replace(sujet,'j\'');
        }
        if (verbe === "peindre"){
            phrase = phrase.replace(verbe,'peins');
        }
        if (verbe === "pleurer"){
            phrase = phrase.replace(verbe,'pleure');
        }
        if (verbe === "prendre"){
            phrase = phrase.replace(verbe,'prends');
        }
        if (verbe === "presser"){
            phrase = phrase.replace(verbe,'presse');
        }
        if (verbe === "ramasser"){
            phrase = phrase.replace(verbe,'ramasse');
        }
        if (verbe === "ranger"){
            phrase = phrase.replace(verbe,'range');
        }
        if (verbe === "rayer"){
            phrase = phrase.replace(verbe,'raie');
        }
        if (verbe === "rechauffer"){
            phrase = phrase.replace(verbe,'rechauffe');
        }
        if (verbe === "regarder"){
            phrase = phrase.replace(verbe,'regarde');
        }
        if (verbe === "regrouper"){
            phrase = phrase.replace(verbe,'regroupe');
        }
        if (verbe === "allonger"){
            phrase = phrase.replace(verbe,'m\'allonge');
        }
        if (verbe === "habiller"){
            phrase = phrase.replace(verbe,'m\'habille');
        }
        if (verbe === "sauter"){
            phrase = phrase.replace(verbe,'saute');
        }
        if (verbe === "sentir"){
            phrase = phrase.replace(verbe,'sens');
        }
        if (verbe === "sonner"){
            phrase = phrase.replace(verbe,'sonne');
        }
        if (verbe === "sortir"){
            phrase = phrase.replace(verbe,'sors');
        }
        if (verbe === "souffler"){
            phrase = phrase.replace(verbe,'souffle');
        }
        if (verbe === "tenir"){
            phrase = phrase.replace(verbe,'tiens');
        }
        if (verbe === "trebucher"){
            phrase = phrase.replace(verbe,'trebuche');
        }
        if (verbe === "ramasser"){
            phrase = phrase.replace(verbe,'ramasse');
        }
        if (verbe === "venir"){
            phrase = phrase.replace(verbe,'viens');
        }
        if (verbe === "verser"){
            phrase = phrase.replace(verbe,'verse');
        }
        if (verbe === "vouloir"){
            phrase = phrase.replace(verbe,'veux');
        }
    }
    if (sujet === "tu"){
        if (verbe === "accrocher"){
            phrase = phrase.replace(verbe,'accroches');
        }
        if (verbe === "aller"){
            phrase = phrase.replace(verbe,'vas');
        }
        if (verbe === "attendre"){
            phrase = phrase.replace(verbe,'attends');
            phrase = phrase.replace(sujet,'j\'');
        }
        if (verbe === "asseoir"){
            phrase = phrase.replace(verbe,'assois');
        }
        if (verbe === "avoir"){
            phrase = phrase.replace(verbe,'as');
        }
        if (verbe === "baigner"){
            phrase = phrase.replace(verbe,'baignes');
        }
        if (verbe === "boire"){
            phrase = phrase.replace(verbe,'bois');
        }
        if (verbe === "changer"){
            phrase = phrase.replace(verbe,'changes');
        }
        if (verbe === "chanter"){
            phrase = phrase.replace(verbe,'chantes');
        }
        if (verbe === "chatouiller"){
            phrase = phrase.replace(verbe,'chatouilles');
        }
        if (verbe === "communiquer"){
            phrase = phrase.replace(verbe,'communiques');
        }
        if (verbe === "couper"){
            phrase = phrase.replace(verbe,'coupes');
        }
        if (verbe === "courir"){
            phrase = phrase.replace(verbe,'cours');
        }
        if (verbe === "dessiner"){
            phrase = phrase.replace(verbe,'dessines');
        }
        if (verbe === "dire"){
            phrase = phrase.replace(verbe,'dis');
        }
        if (verbe === "donner"){
            phrase = phrase.replace(verbe,'donnes');
        }
        if (verbe === "dormir"){
            phrase = phrase.replace(verbe,'dors');
        }
        if (verbe === "doucher"){
            phrase = phrase.replace(verbe,'douches');
        }
        if (verbe === "ecouter"){
            phrase = phrase.replace(verbe,'ecoutes');
        }
        if (verbe === "ecrire"){
            phrase = phrase.replace(verbe,'ecris');
        }
        if (verbe === "entrer"){
            phrase = phrase.replace(verbe,'entres');
        }
        if (verbe === "eviter"){
            phrase = phrase.replace(verbe,'evites');
        }
        if (verbe === "faire"){
            phrase = phrase.replace(verbe,'fais');
        }
        if (verbe === "fermer"){
            phrase = phrase.replace(verbe,'fermes');
        }
        if (verbe === "glisser"){
            phrase = phrase.replace(verbe,'glisses');
        }
        if (verbe === "grimper"){
            phrase = phrase.replace(verbe,'grimpes');
        }
        if (verbe === "grossir"){
            phrase = phrase.replace(verbe,'grossis');
        }
        if (verbe === "jouer"){
            phrase = phrase.replace(verbe,'joues');
        }
        if (verbe === "lancer"){
            phrase = phrase.replace(verbe,'lances');
        }
        if (verbe === "lire"){
            phrase = phrase.replace(verbe,'lis');
        }
        if (verbe === "maigrir"){
            phrase = phrase.replace(verbe,'maigris');
        }
        if (verbe === "manger"){
            phrase = phrase.replace(verbe,'manges');
        }
        if (verbe === "monter"){
            phrase = phrase.replace(verbe,'montes');
        }
        if (verbe === "nager"){
            phrase = phrase.replace(verbe,'nages');
        }
        if (verbe === "crier"){
            phrase = phrase.replace(verbe,'cries');
        }
        if (verbe === "ouvrir"){
            phrase = phrase.replace(verbe,'ouvres');
        }
        if (verbe === "peindre"){
            phrase = phrase.replace(verbe,'peins');
        }
        if (verbe === "pleurer"){
            phrase = phrase.replace(verbe,'pleure');
        }
        if (verbe === "prendre"){
            phrase = phrase.replace(verbe,'prends');
        }
        if (verbe === "presser"){
            phrase = phrase.replace(verbe,'presses');
        }
        if (verbe === "ramasser"){
            phrase = phrase.replace(verbe,'ramasses');
        }
        if (verbe === "ranger"){
            phrase = phrase.replace(verbe,'ranges');
        }
        if (verbe === "rayer"){
            phrase = phrase.replace(verbe,'raies');
        }
        if (verbe === "rechauffer"){
            phrase = phrase.replace(verbe,'rechauffes');
        }
        if (verbe === "regarder"){
            phrase = phrase.replace(verbe,'regardes');
        }
        if (verbe === "regrouper"){
            phrase = phrase.replace(verbe,'regroupes');
        }
        if (verbe === "allonger"){
            phrase = phrase.replace(verbe,'allonges');
        }
        if (verbe === "habiller"){
            phrase = phrase.replace(verbe,'habille');
        }
        if (verbe === "sauter"){
            phrase = phrase.replace(verbe,'sautes');
        }
        if (verbe === "sentir"){
            phrase = phrase.replace(verbe,'sens');
        }
        if (verbe === "sonner"){
            phrase = phrase.replace(verbe,'sonnes');
        }
        if (verbe === "sortir"){
            phrase = phrase.replace(verbe,'sors');
        }
        if (verbe === "souffler"){
            phrase = phrase.replace(verbe,'souffles');
        }
        if (verbe === "tenir"){
            phrase = phrase.replace(verbe,'tiens');
        }
        if (verbe === "trebucher"){
            phrase = phrase.replace(verbe,'trebuches');
        }
        if (verbe === "ramasser"){
            phrase = phrase.replace(verbe,'ramasses');
        }
        if (verbe === "venir"){
            phrase = phrase.replace(verbe,'viens');
        }
        if (verbe === "verser"){
            phrase = phrase.replace(verbe,'verses');
        }
        if (verbe === "vouloir"){
            phrase = phrase.replace(verbe,'veux');
        }
    }
    if (sujet === "nous"){
        if (verbe === "accrocher"){
            phrase = phrase.replace(verbe,'accrochez');
        }
        if (verbe === "aller"){
            phrase = phrase.replace(verbe,'allons');
        }
        if (verbe === "attendre"){
            phrase = phrase.replace(verbe,'attendons');
        }
        if (verbe === "asseoir"){
            phrase = phrase.replace(verbe,'assoyons');
        }
        if (verbe === "avoir"){
            phrase = phrase.replace(verbe,'avons');
        }
        if (verbe === "baigner"){
            phrase = phrase.replace(verbe,'baignons');
        }
        if (verbe === "boire"){
            phrase = phrase.replace(verbe,'buvons');
        }
        if (verbe === "changer"){
            phrase = phrase.replace(verbe,'changeons');
        }
        if (verbe === "chanter"){
            phrase = phrase.replace(verbe,'chantons');
        }
        if (verbe === "chatouiller"){
            phrase = phrase.replace(verbe,'chatouillons');
        }
        if (verbe === "communiquer"){
            phrase = phrase.replace(verbe,'communiquons');
        }
        if (verbe === "couper"){
            phrase = phrase.replace(verbe,'coupons');
        }
        if (verbe === "courir"){
            phrase = phrase.replace(verbe,'courons');
        }
        if (verbe === "dessiner"){
            phrase = phrase.replace(verbe,'dessinons');
        }
        if (verbe === "dire"){
            phrase = phrase.replace(verbe,'disons');
        }
        if (verbe === "donner"){
            phrase = phrase.replace(verbe,'donnons');
        }
        if (verbe === "dormir"){
            phrase = phrase.replace(verbe,'dormons');
        }
        if (verbe === "doucher"){
            phrase = phrase.replace(verbe,'douchons');
        }
        if (verbe === "ecouter"){
            phrase = phrase.replace(verbe,'ecoutons');
        }
        if (verbe === "ecrire"){
            phrase = phrase.replace(verbe,'ecrivons');
        }
        if (verbe === "entrer"){
            phrase = phrase.replace(verbe,'entrons');
        }
        if (verbe === "eviter"){
            phrase = phrase.replace(verbe,'evitons');
        }
        if (verbe === "faire"){
            phrase = phrase.replace(verbe,'faisons');
        }
        if (verbe === "fermer"){
            phrase = phrase.replace(verbe,'fermons');
        }
        if (verbe === "glisser"){
            phrase = phrase.replace(verbe,'glissons');
        }
        if (verbe === "grimper"){
            phrase = phrase.replace(verbe,'grimpons');
        }
        if (verbe === "grossir"){
            phrase = phrase.replace(verbe,'grossissons');
        }
        if (verbe === "jouer"){
            phrase = phrase.replace(verbe,'jouons');
        }
        if (verbe === "lancer"){
            phrase = phrase.replace(verbe,'lançons');
        }
        if (verbe === "lire"){
            phrase = phrase.replace(verbe,'lisons');
        }
        if (verbe === "maigrir"){
            phrase = phrase.replace(verbe,'maigrissons');
        }
        if (verbe === "manger"){
            phrase = phrase.replace(verbe,'mangeons');
        }
        if (verbe === "monter"){
            phrase = phrase.replace(verbe,'montons');
        }
        if (verbe === "nager"){
            phrase = phrase.replace(verbe,'nageons');
        }
        if (verbe === "crier"){
            phrase = phrase.replace(verbe,'crions');
        }
        if (verbe === "ouvrir"){
            phrase = phrase.replace(verbe,'ouvrons');
        }
        if (verbe === "peindre"){
            phrase = phrase.replace(verbe,'peignons');
        }
        if (verbe === "pleurer"){
            phrase = phrase.replace(verbe,'pleurons');
        }
        if (verbe === "prendre"){
            phrase = phrase.replace(verbe,'prennons');
        }
        if (verbe === "presser"){
            phrase = phrase.replace(verbe,'pressons');
        }
        if (verbe === "ramasser"){
            phrase = phrase.replace(verbe,'ramassons');
        }
        if (verbe === "ranger"){
            phrase = phrase.replace(verbe,'rangeons');
        }
        if (verbe === "rayer"){
            phrase = phrase.replace(verbe,'rayons');
        }
        if (verbe === "rechauffer"){
            phrase = phrase.replace(verbe,'rechauffons');
        }
        if (verbe === "regarder"){
            phrase = phrase.replace(verbe,'regardons');
        }
        if (verbe === "regrouper"){
            phrase = phrase.replace(verbe,'regroupons');
        }
        if (verbe === "allonger"){
            phrase = phrase.replace(verbe,'allongeons');
        }
        if (verbe === "habiller"){
            phrase = phrase.replace(verbe,'habillons');
        }
        if (verbe === "sauter"){
            phrase = phrase.replace(verbe,'sautons');
        }
        if (verbe === "sentir"){
            phrase = phrase.replace(verbe,'sentons');
        }
        if (verbe === "sonner"){
            phrase = phrase.replace(verbe,'sonnons');
        }
        if (verbe === "sortir"){
            phrase = phrase.replace(verbe,'sortons');
        }
        if (verbe === "souffler"){
            phrase = phrase.replace(verbe,'soufflons');
        }
        if (verbe === "tenir"){
            phrase = phrase.replace(verbe,'tennons');
        }
        if (verbe === "trebucher"){
            phrase = phrase.replace(verbe,'trebuchons');
        }
        if (verbe === "ramasser"){
            phrase = phrase.replace(verbe,'ramassons');
        }
        if (verbe === "venir"){
            phrase = phrase.replace(verbe,'venons');
        }
        if (verbe === "verser"){
            phrase = phrase.replace(verbe,'versons');
        }
        if (verbe === "vouloir"){
            phrase = phrase.replace(verbe,'voulons');
        }
    }
    if (sujet === "vous"){
        if (verbe === "accrocher"){
            phrase = phrase.replace(verbe,'accrochez');
        }
        if (verbe === "aller"){
            phrase = phrase.replace(verbe,'allez');
        }
        if (verbe === "attendre"){
            phrase = phrase.replace(verbe,'attendez');
        }
        if (verbe === "asseoir"){
            phrase = phrase.replace(verbe,'assoyez');
        }
        if (verbe === "avoir"){
            phrase = phrase.replace(verbe,'avez');
        }
        if (verbe === "baigner"){
            phrase = phrase.replace(verbe,'baignez');
        }
        if (verbe === "boire"){
            phrase = phrase.replace(verbe,'buvez');
        }
        if (verbe === "changer"){
            phrase = phrase.replace(verbe,'changez');
        }
        if (verbe === "chanter"){
            phrase = phrase.replace(verbe,'chantez');
        }
        if (verbe === "chatouiller"){
            phrase = phrase.replace(verbe,'chatouillez');
        }
        if (verbe === "communiquer"){
            phrase = phrase.replace(verbe,'communiquez');
        }
        if (verbe === "couper"){
            phrase = phrase.replace(verbe,'coupez');
        }
        if (verbe === "courir"){
            phrase = phrase.replace(verbe,'courez');
        }
        if (verbe === "dessiner"){
            phrase = phrase.replace(verbe,'dessinez');
        }
        if (verbe === "dire"){
            phrase = phrase.replace(verbe,'dites');
        }
        if (verbe === "donner"){
            phrase = phrase.replace(verbe,'donnez');
        }
        if (verbe === "dormir"){
            phrase = phrase.replace(verbe,'dormez');
        }
        if (verbe === "doucher"){
            phrase = phrase.replace(verbe,'douchez');
        }
        if (verbe === "ecouter"){
            phrase = phrase.replace(verbe,'ecoutez');
        }
        if (verbe === "ecrire"){
            phrase = phrase.replace(verbe,'ecrivez');
        }
        if (verbe === "entrer"){
            phrase = phrase.replace(verbe,'entrez');
        }
        if (verbe === "eviter"){
            phrase = phrase.replace(verbe,'evitez');
        }
        if (verbe === "faire"){
            phrase = phrase.replace(verbe,'faites');
        }
        if (verbe === "fermer"){
            phrase = phrase.replace(verbe,'fermez');
        }
        if (verbe === "glisser"){
            phrase = phrase.replace(verbe,'glissez');
        }
        if (verbe === "grimper"){
            phrase = phrase.replace(verbe,'grimpez');
        }
        if (verbe === "grossir"){
            phrase = phrase.replace(verbe,'grossissez');
        }
        if (verbe === "jouer"){
            phrase = phrase.replace(verbe,'jouez');
        }
        if (verbe === "lancer"){
            phrase = phrase.replace(verbe,'lançez');
        }
        if (verbe === "lire"){
            phrase = phrase.replace(verbe,'lisez');
        }
        if (verbe === "maigrir"){
            phrase = phrase.replace(verbe,'maigrissez');
        }
        if (verbe === "manger"){
            phrase = phrase.replace(verbe,'mangez');
        }
        if (verbe === "monter"){
            phrase = phrase.replace(verbe,'montez');
        }
        if (verbe === "nager"){
            phrase = phrase.replace(verbe,'nagez');
        }
        if (verbe === "crier"){
            phrase = phrase.replace(verbe,'criez');
        }
        if (verbe === "ouvrir"){
            phrase = phrase.replace(verbe,'ouvrez');
        }
        if (verbe === "peindre"){
            phrase = phrase.replace(verbe,'peignez');
        }
        if (verbe === "pleurer"){
            phrase = phrase.replace(verbe,'pleurez');
        }
        if (verbe === "prendre"){
            phrase = phrase.replace(verbe,'prennez');
        }
        if (verbe === "presser"){
            phrase = phrase.replace(verbe,'pressez');
        }
        if (verbe === "ramasser"){
            phrase = phrase.replace(verbe,'ramassez');
        }
        if (verbe === "ranger"){
            phrase = phrase.replace(verbe,'rangez');
        }
        if (verbe === "rayer"){
            phrase = phrase.replace(verbe,'rayez');
        }
        if (verbe === "rechauffer"){
            phrase = phrase.replace(verbe,'rechauffez');
        }
        if (verbe === "regarder"){
            phrase = phrase.replace(verbe,'regardez');
        }
        if (verbe === "regrouper"){
            phrase = phrase.replace(verbe,'regroupez');
        }
        if (verbe === "allonger"){
            phrase = phrase.replace(verbe,'allongez');
        }
        if (verbe === "habiller"){
            phrase = phrase.replace(verbe,'habillez');
        }
        if (verbe === "sauter"){
            phrase = phrase.replace(verbe,'sautez');
        }
        if (verbe === "sentir"){
            phrase = phrase.replace(verbe,'sentez');
        }
        if (verbe === "sonner"){
            phrase = phrase.replace(verbe,'sonnez');
        }
        if (verbe === "sortir"){
            phrase = phrase.replace(verbe,'sortez');
        }
        if (verbe === "souffler"){
            phrase = phrase.replace(verbe,'soufflez');
        }
        if (verbe === "tenir"){
            phrase = phrase.replace(verbe,'tennez');
        }
        if (verbe === "trebucher"){
            phrase = phrase.replace(verbe,'trebuchez');
        }
        if (verbe === "ramasser"){
            phrase = phrase.replace(verbe,'ramassez');
        }
        if (verbe === "venir"){
            phrase = phrase.replace(verbe,'venez');
        }
        if (verbe === "verser"){
            phrase = phrase.replace(verbe,'versez');
        }
        if (verbe === "vouloir"){
            phrase = phrase.replace(verbe,'voulez');
        }
    }
    return phrase;
}

});
