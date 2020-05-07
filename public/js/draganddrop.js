
$(function () {

    
    $(".li-image").draggable({
        //l'élément sera cloné et le clone sera déplacé.
        helper: 'clone',
        //le retour ne se produira que si le draggable n'a pas été déposé sur un droppable
        revert: 'invalid'

    });


    $(".drop").droppable({

        drop: function(event,ui){
            // l'ojbet draggable cloné
            var objet=ui.draggable.clone();

            objet.appendTo($(this)).position( { of: $(this), my: 'center', at: 'center' } ); 
           
            //retourn la phrase construite
            function createPhrasing(){

                let tabMots = [
                    $("#mot1 > li > p").html(),
                    $("#mot2 > li > p").html(),
                    $("#mot3 > li > p").html(),
                    $("#mot4 > li > p").html(),
                    $("#mot5 > li > p").html(),
                    $("#mot6 > li > p").html()
                ];

                let phrase=[];

                for(let i =0; i<tabMots.length; i++){
                    if(tabMots[i] != undefined){
                        phrase.push(tabMots[i]+' ');
                    }
                }
          
                return phrase;
            }

            function showPhrasing(){
                let resultat = createPhrasing();
                let phrase = $("#phrase").html(resultat);
                phrase = $("#phrase").html()
                return phrase;
            }
            
            showPhrasing();
  

        }


    });

    // Lecture audio de la phrase 
    $("#lecture").click(()=>{
        let vocal = $("#phrase").html();
        if(!vocal){
            return
        }
        const speech = new SpeechSynthesisUtterance();
        speech.text = vocal;
        speech.pitch = 1; // 0 à 2 = hauteur
        speech.rate = 1; // 0.5 à 2 = vitesse
        speech.volume = 0.5; // 0 à 1 = volume
        speech.lang = 'fr-FR'; // Language
        // Faire parler
        speechSynthesis.speak(speech);
    })
   // Lecture audio mot à mot de la phrase 
    $("#lectureMot").click((phrase)=>{
        let vocal = $("#phrase").html();
        if(vocal){
            return
        }
        vocal = vocal.split(' ');
        for(let i = 0; i<vocal.length; i++){
            const speech = new SpeechSynthesisUtterance();
            speech.text = vocal[i];
            speech.pitch = 1; // 0 à 2 = hauteur
            speech.rate = 1; // 0.5 à 2 = vitesse
            speech.volume = 0.5; // 0 à 1 = volume
            speech.lang = 'fr-FR'; // Language
            // Faire parler
            speechSynthesis.speak(speech);
        }
      
    })
    // Supprime la phrase et le picto déposés
    $("#refresh").click(()=>{
        $("#phrase").html('');
        $(".drop > li").remove();
    })




    











});
