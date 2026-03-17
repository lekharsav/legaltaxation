//Sticky Menu
window.onscroll = function() {scrollFunction()};
function scrollFunction() {
if
(document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
	document.getElementById("sticky-header").classList.add('sticky_menu');
	} else {
	 document.getElementById("sticky-header").classList.remove('sticky_menu'); 
	}
}

//Hover Dropdown
$(document).ready(function () {
  $('.head_nav .dropdown').hover(function () {
  $(this).find('.dropdown-menu').first().stop(true, true).slideDown(500);
  }, function () {
  $(this).find('.dropdown-menu').first().stop(true, true).slideUp(105)
  });
});

//Hover Dropdown
$(document).ready(function () {
	$('.login_area .dropdown').hover(function () {
	$(this).find('.dropdown-menu').first().stop(true, true).slideDown(150);
	}, function () {
	$(this).find('.dropdown-menu').first().stop(true, true).slideUp(105)
	});
});

// Banner Slider JS Start
$('.slider_area').slick({
	autoplay: true,
	speed: 1000,
	autoplaySpeed: 5000,
	arrows: false,
	dots: false,
	pauseOnHover:false,
	prevArrow:'<button type="button" data-role="none" class="slick-button slick-prev" aria-label="previous"><i class="fa-solid fa-angle-left"></i></button>',
	nextArrow:'<button type="button" data-role="none" class="slick-button slick-next" aria-label="next"><i class="fa-solid fa-angle-right"></i></button>',
  })
  .slickAnimation();


//About Read Button
$('.moreless-button').click(function() {
	$('.moretext').slideToggle();
	if ($('.moreless-button').text() == "Read Less") {
	  $(this).text("Read More")
	} else {
	  $(this).text("Read Less")
	}
});


//Counter
var counted = 0;
$(window).scroll(function() {

  var oTop = $('#counter').offset().top - window.innerHeight;
  if (counted == 0 && $(window).scrollTop() > oTop) {
    $('.count').each(function() {
      var $this = $(this),
        countTo = $this.attr('data-count');
      $({
        countNum: $this.text()
      }).animate({
          countNum: countTo
        },

        {

          duration: 3000,
          easing: 'swing',
          step: function() {
            $this.text(Math.floor(this.countNum));
          },
          complete: function() {
            $this.text(this.countNum);
            //alert('finished');
          }

        });
    });
    counted = 1;
  }

});

//Testimonial Slider
$('.test_slider').slick({
	infinite: true,
	slidesToShow: 3,
	slidesToScroll: 1,
	prevArrow: '<button class="slick-arrow prev-arrow for-mob"><i class="fa fa-chevron-left"></i></button>',
	nextArrow: '<button class="slick-arrow next-arrow for-mob"><i class="fa fa-chevron-right"></i></button>',
	arrows: true,
	dots: false,
	autoplay: true,
	autoplaySpeed: 3000,	
	speed: 1000,
	draggable: true,
	pauseOnHover: false,
	responsive: [{
		breakpoint: 1366,
		settings: {
			slidesToShow: 3
		}
	}, {
		breakpoint: 980,
		settings: {
			slidesToShow: 2
		}
	}, {
		breakpoint: 520,
		settings: {
			slidesToShow: 1
		}
	}]
});




if($('.tabs-box').length){
	$('.tabs-box .tab-buttons .tab-btn').on('click', function(e) {
		e.preventDefault();
		var target = $($(this).attr('data-tab'));
		
		if ($(target).is(':visible')){
			return false;
		}else{
			target.parents('.tabs-box').find('.tab-buttons').find('.tab-btn').removeClass('active-btn');
			$(this).addClass('active-btn');
			target.parents('.tabs-box').find('.tabs-content').find('.tab').fadeOut(0);
			target.parents('.tabs-box').find('.tabs-content').find('.tab').removeClass('active-tab');
			$(target).fadeIn(300);
			$(target).addClass('active-tab');
		}
	});
}


// Particles
particlesJS("particles-js", {
	"particles": {
	  "number": {
		"value": 80,
		"density": {
		  "enable": true,
		  "value_area": 800
		}
	  },
	  "color": {
		"value": "#004678"
	  },
	  "shape": {
		"type": "circle",
		"stroke": {
		  "width": 0,
		  "color": "#004678"
		},
		"polygon": {
		  "nb_sides": 5
		},
		"image": {
		  "src": "img/github.svg",
		  "width": 100,
		  "height": 100
		}
	  },
	  "opacity": {
		"value": 0.5,
		"random": false,
		"anim": {
		  "enable": false,
		  "speed": 1,
		  "opacity_min": 0.1,
		  "sync": false
		}
	  },
	  "size": {
		"value": 2,
		"random": true,
		"anim": {
		  "enable": false,
		  "speed": 40,
		  "size_min": 0.1,
		  "sync": false
		}
	  },
	  "line_linked": {
		"enable": true,
		"distance": 150,
		"color": "#004678",
		"opacity": 0.4,
		"width": 1
	  },
	  "move": {
		"enable": true,
		"speed": 2,
		"direction": "none",
		"random": false,
		"straight": false,
		"out_mode": "out",
		"bounce": false,
		"attract": {
		  "enable": false,
		  "rotateX": 600,
		  "rotateY": 1200
		}
	  }
	},
	"interactivity": {
	  "detect_on": "canvas",
	  "events": {
		"onhover": {
		  "enable": true,
		  "mode": "grab"
		},
		"onclick": {
		  "enable": true,
		  "mode": "push"
		},
		"resize": true
	  },
	  "modes": {
		"grab": {
		  "distance": 150,
		  "line_linked": {
			"opacity": 1
		  }
		},
		"bubble": {
		  "distance": 400,
		  "size": 40,
		  "duration": 2,
		  "opacity": 8,
		  "speed": 3
		},
		"repulse": {
		  "distance": 200,
		  "duration": 0.4
		},
		"push": {
		  "particles_nb": 4
		},
		"remove": {
		  "particles_nb": 2
		}
	  }
	},
	"retina_detect": true
  });
  
  requestAnimationFrame();
  


  