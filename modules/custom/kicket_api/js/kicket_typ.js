(function (Drupal, $) {
    "use strict";

    function generateHTML(response) {
        const {
            event,
            reservation
        } = response;

        const eventTitle = event.title;
        const eventDateTime = event.displayPeriod.startsAt;
        const eventLocation = event.location.name + ", " + event.location.address + ", " + event.location.city.name;
        const eventMapLocation = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(event.location.name)}&query_place_id=${event.location.latitude},${event.location.longitude}`;
        const reservationCode = reservation.reservationCode;
        const ticketCompounds = reservation.grossPrices.ticketCompounds;


        const ticketsHTML = ticketCompounds.map(ticket => `
            <li>
                <strong>Miejsce:</strong> ${ticket.description || 'Brak danych'} |
                <strong>Ilość:</strong> ${ticket.quantity || '1'} |
                <strong>Cena:</strong> ${ticket.totalPrice || '0.00'} zł
            </li>
        `).join('');


        const htmlContent = `
            <h4>Podsumowanie zakupów</h4>
            <p><strong>Nazwa wydarzenia:</strong> ${eventTitle}</p>
            <p><strong>Data i godzina:</strong> ${new Date(eventDateTime).toLocaleString()}</p>
            <p><strong>Miejsce wydarzenia:</strong> ${eventLocation} (<a href="${eventMapLocation}">sprawdź jak dojechać</a>)</p>
            <p><strong>Kod rezerwacji:</strong> ${reservationCode}</p>
            <p><strong>Zakupione bilety:</strong></p>
            <ul>
                ${ticketsHTML}
            </ul>
        `;


        const container = document.getElementById('response-container');
        container.innerHTML = htmlContent;
    }
    kicket.getInitialTypSummaryV1()
        .then((response) => {
            console.log('Response:', response);
            if (!response.status)
                generateHTML(response);
        })
        .catch((error) => {
            console.error('Wystąpił błąd podczas pobierania danych:', error);
        });

})(Drupal, jQuery);