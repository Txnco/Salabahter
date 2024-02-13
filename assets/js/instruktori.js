window.addEventListener('load', () => {
    let instruktoriContainer = select('.instruktori-container');
    if (instruktoriContainer) {
      let instruktoriIsotope = new Isotope(instruktoriContainer, {
        itemSelector: '.instruktori-item',
        layoutMode: 'fitRows'
      });

      let instruktoriFilters = select('#instruktori-flters li', true);

      on('click', '#instruktori-flters li', function(e) {
        e.preventDefault();
        instruktoriFilters.forEach(function(el) {
          el.classList.remove('filter-active');
        });
        this.classList.add('filter-active');

        instruktoriIsotope.arrange({
          filter: this.getAttribute('data-filter')
        });
      }, true);
    }

  });

if(window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}
