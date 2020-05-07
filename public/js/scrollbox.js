$(function () {

    $('#carousel-categorie').scrollbox({
        direction: 'verticale',
        autoPlay: false,
        switchItems: 2,
        autoPlay: false,
        linear: true,          // Scroll method
        startDelay: 0,          // Start delay (in seconds)
        delay: 0,               // Delay after each scroll event (in seconds)
        step: 50,                // Distance of each single step (in pixels)
        speed: 30, 
      });

      $('#btn-backward-categorie').click(function () {
        $('#carousel-categorie').trigger('backward');
      });
      $('#btn-forward-categorie').click(function () {
        $('#carousel-categorie').trigger('forward');
      });

      $('#carousel-picto').scrollbox({
        direction: 'verticale',
        autoPlay: false,
        switchItems: 2,
        autoPlay: false,
        linear: true,          // Scroll method
        startDelay: 10,          // Start delay (in seconds)
        delay: 0,               // Delay after each scroll event (in seconds)
        step: 50,                // Distance of each single step (in pixels)
        speed: 20, 
      });

      $('#btn-backward-picto').click(function () {
        $('#carousel-picto').trigger('backward1');
      });
      $('#btn-forward-picto').click(function () {
        $('#carousel-picto').trigger('forward1');
      });      
});
