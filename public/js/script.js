/*** Slick Carrousel ***/

$(document).ready(function () {
    $('.slider').slick({
        dots: true,
        infinite: true,
        centerMode: true,
        centerPadding: '60px',
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 8000,
        arrows: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    dots: true
                }
            },
            {
                breakpoint: 768,
                settings: {
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 650,
                settings: {
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    })
});

/*** Footer Social Arrow ***/

// $('#social-arrow-down').click(function () {
//     $('#socials-details ul li').addClass('open');
//     $('#arrow').addClass('rotate-arrow');
// });

$(document).ready(function () {
    $('#social-arrow-down').click(function () {
        $('#socials-details ul li').toggle();
        $('#arrow').toggleClass('rotate-arrow');
    })
})