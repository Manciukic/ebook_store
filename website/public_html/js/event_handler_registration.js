

function registrate(){

    var form=document.form_registration;
    for(var i=0;i < form.elements.length;i++)
    {
        if(form.elements[i].name!="customedQuestion" && form.elements[i].name!="secretQuestion"){
            if(form.elements[i].className=="registrationInput" && (form.elements[i].value==""))			//Verify wether the fields are empty or not
            {
                document.getElementById("regControl").textContent="Fill all the fields!";
                document.getElementById("regControl").style.display="inline";
                return false;
            }
        }
    }
    for(var j=0;j < form.elements.length;j++)	///verify wether the patterns are met or not
    {
            if(form.elements[j].className=="error")
            {
                document.getElementById("regControl").textContent="Insert valid inputs!";
                document.getElementById("regControl").style.display="inline";
                return false;

            }

    }

    var selectedSecretQuestion=form.secretQuestion;
    if(selectedSecretQuestion.options[selectedSecretQuestion.selectedIndex].value==="" && form.customedQuestion.value==""){

        document.getElementById("regControl").textContent="Select a secret question!";
        document.getElementById("regControl").style.display="inline";
        return false
    }
    if(selectedSecretQuestion.options[selectedSecretQuestion.selectedIndex].value!="new" && form.customedQuestion.value!=""){

        document.getElementById("regControl").textContent="Select just one secret question!";
        document.getElementById("regControl").style.display="inline";
        return false
    }

    document.getElementById("regControl").style.display="none";
    return true;
}