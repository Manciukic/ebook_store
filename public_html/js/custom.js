function showAddCard(target) {
    var new_card_elem = document.getElementById("new-card-details")
    if(target.value == ""){
        new_card_elem.classList.remove("hidden")
    } else {
        new_card_elem.classList.add("hidden")
    }
}

function showCustomSecretQuestion(target){
    var new_question_elem = document.getElementById("new-question")
    if(target.value == "new"){
        new_question_elem.classList.remove("hidden")
    } else {
        new_question_elem.classList.add("hidden")
    }
}