/* SCENSOB — interaction for the static build.
   Plain JavaScript, no dependencies, so the pages work straight from disk. */

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

  /* Generic single-select button groups ---------------------------------- */
  function singleSelect(selector) {
    var buttons = Array.prototype.slice.call(document.querySelectorAll(selector));
    if (!buttons.length) { return buttons; }

    buttons.forEach(function (button) {
      button.addEventListener('click', function () {
        buttons.forEach(function (other) {
          other.setAttribute('aria-pressed', other === button ? 'true' : 'false');
        });
      });
    });

    return buttons;
  }

  singleSelect('.segmented button');
  singleSelect('.choice');

  /* Catalog sector filter ------------------------------------------------- */
  var filters = Array.prototype.slice.call(document.querySelectorAll('.filter'));
  var blocks = Array.prototype.slice.call(document.querySelectorAll('.sector-block'));

  if (filters.length && blocks.length) {
    filters.forEach(function (button) {
      button.addEventListener('click', function () {
        var wanted = button.dataset.sector;

        filters.forEach(function (other) {
          other.setAttribute('aria-pressed', other === button ? 'true' : 'false');
        });

        blocks.forEach(function (block) {
          block.hidden = !(wanted === 'all' || block.dataset.sector === wanted);
        });
      });
    });
  }
})();
