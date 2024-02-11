document.addEventListener("DOMContentLoaded", function () {
    // Postavlja se trajanje poruke (npr. 10 sekundi)
    const duration = 10000;


    const alertElement = document.getElementById("loginSuccessAlert");
    alertElement.classList.add("fade-down");

    // Funcija koja pokaže uspješnu prijavu
    function showLoginSuccessMessage() {

        setTimeout(function () {
            alertElement.style.opacity = 1;
            alertElement.style.transform = "translateY(0)";
        }, 0);

        setTimeout(function () {
            alertElement.style.opacity = 1;
            alertElement.style.transform = "translateY(-150px)";
        }, duration);

    }

    
    if (alertElement) {
        // Your existing code
        showLoginSuccessMessage();
} else {
        console.error("Element with ID 'loginSuccessAlert' not found");
    }
   

});