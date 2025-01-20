// Faire disparaître le message flash après 1 seconde
    setTimeout(function() {
        var flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            flashMessage.style.display = 'none';
        }
    }, 3000);


    // Gestion du bouton annuler paiemenet 
    document.addEventListener('DOMContentLoaded', function () {
        const cancelButton = document.querySelector('.confirm-cancel');
        if (cancelButton) {
            cancelButton.addEventListener('click', function (event) {
                if (!confirm('Êtes-vous sûr de vouloir annuler cette location ?')) {
                    event.preventDefault(); // Empêche la soumission si l'utilisateur annule
                }
            });
        }
    });
    

