const forfaitsData = {
    "Forfait1": { prix: 20, nombreSeance: 3, periode: 3 }, // 3 mois
    "Forfait2": { prix: 35, nombreSeance: 5, periode: 6 }, // 6 mois
    "Forfait3": { prix: 50, nombreSeance: 10, periode: 12 } // 12 mois
};

document.addEventListener('DOMContentLoaded', () => {
    const typeForfaitField = document.getElementById('typeForfait'); // Champ TypeForfait
    const prixField = document.getElementById('prixForfait'); // Champ Prix
    const nombreSeanceField = document.getElementById('nombreSeance'); // Champ Nombre de séances
    const dateDebutField = document.getElementById('dateDebut'); // Champ Date début
    const dateExpirationField = document.getElementById('dateExpiration'); // Champ Date expiration
    const remiseField = document.getElementById('prixRemiseForfait'); // Champ Remise

    const clientCampingField = document.getElementById('clientCamping'); // Champ caché ou indicateur si le client est associé à un camping
    const remiseCampingField = document.getElementById('remiseCamping'); // Taux de remise si le client est dans un camping

    typeForfaitField.addEventListener('change', () => {
        const selectedType = typeForfaitField.value;

        if (forfaitsData[selectedType]) {
            const forfait = forfaitsData[selectedType];

            // Remplir les champs Prix, Nombre de séances et Période
            prixField.value = forfait.prix;
            nombreSeanceField.value = forfait.nombreSeance;

            // Calculer les dates début et expiration
            const dateDebut = new Date(dateDebutField.value || new Date());
            const dateExpiration = new Date(dateDebut);
            dateExpiration.setMonth(dateDebut.getMonth() + forfait.periode);

            // Remplir les dates
            dateDebutField.value = dateDebut.toISOString().slice(0, 10);
            dateExpirationField.value = dateExpiration.toISOString().slice(0, 10);

            // Calculer la remise si le client est associé à un camping
            if (clientCampingField.value === "1") { // Si le client est dans un camping (associé à un camping)
                const remise = parseFloat(remiseCampingField.value) || 0; // Récupérer le taux de remise du camping, par exemple 0.10 pour 10% (assurez-vous que remiseCampingField contient un pourcentage)
                if (forfait && forfait.prix) {
                    // Si le prix du forfait est valide, appliquer la remise
                    const prixApresRemise = forfait.prix - (forfait.prix * (remise/100)); // Calcul du prix après remise
                    remiseField.value = prixApresRemise.toFixed(2); // Afficher la remise calculée (en format à deux décimales)
                }
            } else {
                remiseField.value = "0"; // Pas de remise si le client n'est pas associé à un camping
            }
        }
    });
});
