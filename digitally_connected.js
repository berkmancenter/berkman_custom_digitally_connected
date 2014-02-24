jQuery(function() {
  var film_roll = new FilmRoll({
    container: '#film_roll',
  });

  jQuery(document).on('click', '.toggle-more', function() {
    jQuery(this).closest('.person').find('p').trigger("originalContent", function( content ) {
      jQuery(this).replaceWith(content);
    });
  });
})

jQuery(window).load(function() {
  jQuery('.person-bio p').dotdotdot({
    height: 210,
    callback: function() {
      jQuery(this).append('<a href="javascript:void(0)" class="toggle-more">&rarr;</a>');
    }
  });
});
