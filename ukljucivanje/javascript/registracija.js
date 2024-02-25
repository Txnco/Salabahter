document.addEventListener("DOMContentLoaded", function() {
 
  document.getElementById("sljedeciKorak").addEventListener("click", function() {
    
    if (validateForm()) {
     
      statusTipke();
    } else {
     
      return;
    }
  });
  
 
  var inputs = document.querySelectorAll('.needs-validation input, .needs-validation select');
  inputs.forEach(function(input) {
    
    input.addEventListener('input', function() {
      clearValidationMessage(this);
      updateInputStyle(this);
    });
  });
  
  
  document.getElementById('prosliKorak').addEventListener('click', function() {
   
    var hiddenUI = document.getElementById('hidenUI');
    var newButtons = document.getElementById('newButtons');

   
    hiddenUI.classList.remove("hideme");
    newButtons.classList.add("hideme");
  });
});

function validateForm() {
  
  var inputs = document.querySelectorAll('.needs-validation input, .needs-validation select');

  
  var isValid = true;
  inputs.forEach(function(input) {
      
      var feedback = input.nextElementSibling;
      if (feedback && feedback.classList.contains('invalid-feedback')) {
          feedback.style.display = 'none';
      }
      
      if (!input.checkValidity()) {
          isValid = false;
         
          if (feedback && feedback.classList.contains('invalid-feedback')) {
              feedback.style.display = 'block';
          }
          updateInputStyle(input);
      }
  });

  return isValid;
}


function statusTipke() {
  var hidenUI = document.getElementById("hidenUI");
  var newButtons = document.getElementById("newButtons");

  // Toggle the 'hideme' class on both divs
  hidenUI.classList.add("hideme");
  newButtons.classList.remove("hideme");
}



function updateInputStyle(input) {
  if (input.checkValidity()) {
      input.classList.remove('is-invalid');
      input.classList.add('is-valid');
  } else {
      input.classList.remove('is-valid');
      input.classList.add('is-invalid');
  }
}

function clearValidationMessage(input) {
  var feedback = input.nextElementSibling;
  if (feedback && feedback.classList.contains('invalid-feedback')) {
      feedback.style.display = 'none';
  }
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
