
var myRequest;
var responseData;
var article;

getData("http://localhost:8000/api/get/categories");

//requete de la page categorie.json
function getData(url) {
  myRequest=new XMLHttpRequest();
  myRequest.onreadystatechange=getResponse;
  myRequest.open("GET", url);
  myRequest.setRequestHeader("content-type","application-json");
  myRequest.send();
}

//traitement de la réponse catégorie.json
function getResponse(){
  try {
         
         if (myRequest.readyState === XMLHttpRequest.DONE) {

             switch(myRequest.status) {
                case 500:
                  break;
                case 404:
                  break;
                case 200:
                  responseData=JSON.parse(myRequest.responseText);
                  parcoursJSON(responseData)
                break
             }


         }
      }
      catch(ex){
      	console.log("Ajax error: "+ex.Description);
      }    
}

//parcours de la réponse catégorie.json
function parcoursJSON(jsonObj) {
  for(let i=0; i< jsonObj.length;i++){
    let categorie = jsonObj[i]['name'];
    let imageCategorie = jsonObj[i]['filename'];
    $("#listcategorie").append('<li class="categorie" name="'+categorie+'" ><img src="/images/categorie/'+imageCategorie+'"></a></li>');

  }
  $(".categorie").click(function(){
    categorie= $(this).attr('name');
    getData1("http://localhost:8000/api/get/pictogrammes");
    getCategorie(categorie);
  });

}

function getCategorie(categorie){
  return categorie;
};

// requete de la page name.json
function getData1(url) {
  myRequest=new XMLHttpRequest();
  myRequest.onreadystatechange=getResponse1;
  myRequest.open("GET", url);
  myRequest.setRequestHeader("content-type","application-json");
  myRequest.send();
}

//traitement de la réponse name.json
function getResponse1(){
  try {
         
         if (myRequest.readyState === XMLHttpRequest.DONE) {

             switch(myRequest.status) {
                case 500:
                  break;
                case 404:
                  break;
                case 200:
                  responseData=JSON.parse(myRequest.responseText);
                  categorie =(getCategorie(categorie))
                   parcoursJSON1(responseData,categorie);
                  
                break
             }


         }
      }
      catch(ex){
      	console.log("Ajax error: "+ex.Description);
      }    
}

//parcours de la réponse name.json
function parcoursJSON1(jsonObj,categorie) {
  //récupère le nombre d'éléments dans listpicto
  let countLi = $("#listpicto > li ").length;
  //si listpicto est vide on boucle sur la réponse et on intègre les éléments
  if(countLi == 0){
    for(let i = 0; i< jsonObj.length; i++){

      if(jsonObj[i]['categorie']['name'] == categorie){
        let filename = jsonObj[i]['filename'];
        let name = jsonObj[i]['name'];
        let id = jsonObj[i]['id'];
  
        $("#listpicto").append('<li id="drag'+id+'" class="draggable drag" name="'+name+'" ><img src="/images/pictogramme/'+filename+'"></a>'+
        '<p class="text-center drag-text">'+name+'</p></li>');
      }

    } 
  //sinon on supprimer tous les élement et on les remplaces
  } else {
    $("#listpicto > li").remove();
    countLi = 0;
    for(let i = 0; i< jsonObj.length; i++){

      if(jsonObj[i]['categorie']['name'] == categorie){
        let filename = jsonObj[i]['filename'];
        let name = jsonObj[i]['name'];
        let id = jsonObj[i]['id'];
  
        $("#listpicto").append('<li id="drag'+id+'" class="draggable drag" name="'+name+'" ><img src="/images/pictogramme/'+filename+'"></a>'+
        '<p class="text-center drag-text">'+name+'</p></li>');
      }

    } 
  }

          
  $(".draggable").draggable({
  helper: 'clone',
  revert: 'invalid'

  });



  
    
}

