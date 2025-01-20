// Script pour afficher les détails de la facture dans le modal
/*
var viewFactureModal = document.getElementById('viewFactureModal');
if (viewFactureModal) {
    viewFactureModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('factureNum').textContent = button.getAttribute('data-num');
        document.getElementById('factureMontant').textContent = button.getAttribute('data-montant');
        document.getElementById('factureDate').textContent = button.getAttribute('data-date');
        document.getElementById('factureAdresse').textContent = button.getAttribute('data-adresse');
    });
}
*/

var viewFactureModal = document.getElementById('viewFactureModal');
if (viewFactureModal) {
    viewFactureModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        
        // Récupérer les valeurs des attributs data-* du bouton
        document.getElementById('factureNum').textContent = button.getAttribute('data-num');
        document.getElementById('factureMontant').textContent = button.getAttribute('data-montant');
        document.getElementById('factureDate').textContent = button.getAttribute('data-date');
        document.getElementById('factureAdresse').textContent = button.getAttribute('data-adresse');
        
        // Informations de paiement
        document.getElementById('paiementMode').textContent = button.getAttribute('data-paiementmode');
        document.getElementById('paiementStatut').textContent = button.getAttribute('data-paiementstatut');
        
        // Informations du client
        document.getElementById('clientNom').textContent = button.getAttribute('data-clientnom');
        document.getElementById('clientPrenom').textContent = button.getAttribute('data-clientprenom');
    });
}

