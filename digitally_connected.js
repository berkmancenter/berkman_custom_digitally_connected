jQuery(function() {
  var film_roll = new FilmRoll({
    container: '#film_roll',
  });

  jQuery('.person-bio p').append('<a href="javascript:void(0)" class="toggle-more">&rarr;</a>');

  jQuery(document).on('click', '.toggle-more', function() {
    jQuery(this).closest('.person').css('height', 'auto').find('p')
      .trigger("originalContent", function( content ) {
        // Remove link from end
        jQuery(this).replaceWith(content.slice(0, -1));
      }
    );
  });

  // Grab the current date
  var currentDate = new Date();

  // April 28, 2014
  var futureDate = new Date('28 Apr 2014 08:30:00 -0400');

  // Calculate the difference in seconds between the future and current date
  var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;

	var clock = jQuery('#clock').FlipClock(diff, {
		clockFace: 'DailyCounter',
		countdown: true,
		//showSeconds: false,
    callbacks: {
      start: function(){
        //*
        jQuery('.flip-clock-divider.minutes').nextAll().andSelf().css('display', 'none');
        //*/
      }
    }
	});
})

jQuery(window).load(function() {
  jQuery('.person-bio p').dotdotdot({
    height: 165,
    after: '.toggle-more',
  });
});
