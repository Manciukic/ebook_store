function showAddCard(target) {
    var new_card_elem = document.getElementById("new-card-details")
    if(target.value == ""){
        new_card_elem.classList.remove("card-hidden")
    } else {
        new_card_elem.classList.add("card-hidden")
    }
}