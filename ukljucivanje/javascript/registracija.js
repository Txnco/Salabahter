document.addEventListener("DOMContentLoaded", function() {
  // Add event listener to the "Sljedeci korak" button
  document.getElementById("sljedeciKorak").addEventListener("click", function() {
    // Validate the form
    if (validateForm()) {
      // If form is valid, toggle the UI
      statusTipke();
    } else {
      // If form is invalid, prevent the transition to the new UI
      return;
    }
  });
  
  // Add event listener to each input field to update their styles dynamically
  var inputs = document.querySelectorAll('.needs-validation input, .needs-validation select');
  inputs.forEach(function(input) {
    // Clear validation message when the user starts typing
    input.addEventListener('input', function() {
      clearValidationMessage(this);
      updateInputStyle(this);
    });
  });
  
  
  document.getElementById('prosliKorak').addEventListener('click', function() {
    // Get the hidden UI and the current UI
    var hiddenUI = document.getElementById('hidenUI');
    var newButtons = document.getElementById('newButtons');

    // Toggle the 'hideme' class on both divs
    hiddenUI.classList.remove("hideme");
    newButtons.classList.add("hideme");
  });
});

function validateForm() {
  // Get all input fields in the form
  var inputs = document.querySelectorAll('.needs-validation input, .needs-validation select');

  // Check if all fields are valid
  var isValid = true;
  inputs.forEach(function(input) {
      // If field was previously marked as invalid, hide the feedback
      var feedback = input.nextElementSibling;
      if (feedback && feedback.classList.contains('invalid-feedback')) {
          feedback.style.display = 'none';
      }
      // Check validity
      if (!input.checkValidity()) {
          isValid = false;
          // Show invalid feedback and update input style
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
