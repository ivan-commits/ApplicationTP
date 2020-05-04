$(function () {

    $('#carousel-categorie').scrollbox({
        direction: 'verticale',
        autoPlay: false,
        switchItems: 10,
        autoPlay: false,
        linear: true,          // Scroll method
        startDelay: 0,          // Start delay (in seconds)
        delay: 0,               // Delay after each scroll event (in seconds)
        step: 10,                // Distance of each single step (in pixels)
        speed: 30, 
      });

      $('#btn-backward-categorie').click(function () {
        $('#carousel-categorie').trigger('backward');
        console.log('back')
      });
      $('#btn-forward-categorie').click(function () {
        $('#carousel-categorie').trigger('forward');
        console.log('next')
      });

      $('#carousel-picto').scrollbox({
        direction: 'verticale',
        autoPlay: false,
        switchItems: 10,
        autoPlay: false,
        linear: true,          // Scroll method
        startDelay: 0,          // Start delay (in seconds)
        delay: 0,               // Delay after each scroll event (in seconds)
        step: 10,                // Distance of each single step (in pixels)
        speed: 50, 
      });

      $('#btn-backward-picto').click(function () {
        $('#carousel-picto').trigger('backward1');
        console.log('back')
      });
      $('#btn-forward-picto').click(function () {
        $('#carousel-picto').trigger('forward1');
        console.log('next')
      });      
});
