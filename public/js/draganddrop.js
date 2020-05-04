
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

    $("#lecture").click(()=>{
        let vocal = $("#phrase").html();
        if(vocal){
            alert(vocal);
        }else{
            console.log('phrase null')
        }
      
    })

    $("#refresh").click(()=>{
        $("#phrase").html('');
        $(".drop > li").remove();

        
    })




    











});
