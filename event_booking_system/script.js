function registerEvent(eventName) {
    const eventCard = document.querySelector(`.event-card:has(h3:contains('${eventName}'))`);
    const seatsElement = eventCard.querySelector('.seats');
    let availableSeats = parseInt(seatsElement.innerText);
    
    if (availableSeats > 0) {
        availableSeats--;
        seatsElement.innerText = availableSeats;
        alert(`You have successfully registered for the ${eventName}!`);
    } else {
        alert(`Sorry, the ${eventName} is sold out!`);
    }
}

