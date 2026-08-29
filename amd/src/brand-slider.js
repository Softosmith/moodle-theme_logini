// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Brand logo slider for the Logini login page.
 *
 * @module     theme_logini/brand-slider
 * @copyright  2026 Softosmith.com and Asad Ali
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {
    var current = 0;
    var timer;

    function goTo(slides, dots, index) {
        slides[current].classList.remove('logini-slide--active');
        dots[current].classList.remove('logini-dot--active');
        current = index;
        slides[current].classList.add('logini-slide--active');
        dots[current].classList.add('logini-dot--active');
    }

    function init() {
        var slider = document.querySelector('.logini-brand-slider');
        if (!slider) {
            return;
        }
        var slides = Array.from(slider.querySelectorAll('.logini-slide'));
        var dots   = Array.from(slider.querySelectorAll('.logini-dot'));

        if (slides.length <= 1) {
            return;
        }

        dots.forEach(function(dot, i) {
            dot.addEventListener('click', function() {
                clearInterval(timer);
                goTo(slides, dots, i);
                timer = setInterval(function() {
                    goTo(slides, dots, (current + 1) % slides.length);
                }, 5000);
            });
        });

        timer = setInterval(function() {
            goTo(slides, dots, (current + 1) % slides.length);
        }, 5000);
    }

    return { init: init };
});
