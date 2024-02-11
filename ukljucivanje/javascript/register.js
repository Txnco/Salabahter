function toggleUI() {
    var hidenUI = document.getElementById("hidenUI");
    var newButtons = document.getElementById("newButtons");
  
    // Toggle the 'hideme' class on both divs
    hidenUI.classList.add("hideme");
    newButtons.classList.toggle("hideme");
  }
  
  function showProfesor() {
    // Handle 'Profesor' button click
    console.log("Profesor button clicked");
  }
  
  function showStudent() {
    // Handle 'Student' button click
    console.log("Student button clicked");
  }
  
  function showUcenik() {
    // Handle 'Učenik' button click
    console.log("Učenik button clicked");
  }
  