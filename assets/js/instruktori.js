// window.addEventListener('load', () => {
//   let instruktoriContainer = select('.instruktori-container');
//   if (instruktoriContainer) {
//     let instruktoriIsotope = new Isotope(instruktoriContainer, {
//       itemSelector: '.instruktori-item',
//       layoutMode: 'fitRows'
//     });

//     let instruktoriFilters = select('#instruktori-flters li', true);

//     on('click', '#instruktori-flters li', function(e) {
//       e.preventDefault();
//       instruktoriFilters.forEach(function(el) {
//         el.classList.remove('filter-active');
//       });
//       this.classList.add('filter-active');

//       instruktoriIsotope.arrange({
//         filter: this.getAttribute('data-filter')
//       });
//     }, true);

//     // Add event listener for the "Pretraži" button
//     on('click', '#pretrazi-button', function(e) {
//       e.preventDefault();

//       // Get the filter values from the form
//       let predmet = document.querySelector('#predmet-select').value;
//       let grad = document.querySelector('#grad-select').value;
//       let zupanija = document.querySelector('#zupanija-select').value;

//       // Filter the cards
//       instruktoriIsotope.arrange({
//         filter: function() {
//           return this.getAttribute('data-predmet') === predmet &&
//                  this.getAttribute('data-grad') === grad &&
//                  this.getAttribute('data-zupanija') === zupanija;
//         }
//       });
//     });
//   }
// });

if(window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}
