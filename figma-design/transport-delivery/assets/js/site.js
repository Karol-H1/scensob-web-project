/* SCENSOB Transport & Delivery — interaction for the static design build.
   Plain JavaScript, no dependencies, so the pages work from the filesystem. */

(function () {
  'use strict';

  /* Mobile navigation ---------------------------------------------------- */
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('primary-nav');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* Catalog sector filter ------------------------------------------------ */
  var filters = Array.prototype.slice.call(document.querySelectorAll('.filter'));
  var groups = Array.prototype.slice.call(document.querySelectorAll('.sector-group'));

  if (filters.length && groups.length) {
    filters.forEach(function (button) {
      button.addEventListener('click', function () {
        var wanted = button.dataset.sector;

        filters.forEach(function (other) {
          other.setAttribute('aria-pressed', other === button ? 'true' : 'false');
        });

        groups.forEach(function (group) {
          var match = wanted === 'all' || group.dataset.sector === wanted;
          group.hidden = !match;
        });
      });
    });
  }

  /* Contact enquiry-type choices ----------------------------------------- */
  var choices = Array.prototype.slice.call(document.querySelectorAll('.choice'));

  if (choices.length) {
    choices.forEach(function (choice) {
      choice.addEventListener('click', function () {
        choices.forEach(function (other) {
          other.setAttribute('aria-pressed', other === choice ? 'true' : 'false');
        });
      });
    });
  }
})();
