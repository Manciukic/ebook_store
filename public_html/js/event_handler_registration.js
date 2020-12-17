

function checkPasswordSecurity()
{
    var form = document.form_registration
    var passwordCounter = document.getElementById('password-counter');
    resetClassesPassword(passwordCounter);
    if(form.password.value.length == 0){
        passwordCounter.classList.add('hidden');
        return;
    }
    else if(form.password.value.length < 5)
    {
        passwordCounter.textContent = "very weak";
        passwordCounter.classList.add('pw-very-weak')
    }
    else if(form.password.value.length < 7)
    {
        passwordCounter.textContent = "weak";
        passwordCounter.classList.add('pw-weak')
    }
    else{
        passwordCounter.textContent="strong";
        passwordCounter.classList.add('pw-strong')
    }
    passwordCounter.classList.remove('hidden')

}

function resetClassesPassword(passwordCounter){
    passwordCounter.classList.remove('pw-very-weak')
    passwordCounter.classList.remove('pw-weak')
    passwordCounter.classList.remove('pw-strong')
}



function registrate(){

    var form=document.form_registration;
    for(var i=0;i < form.elements.length;i++)
    {
        if(form.elements[i].name!="customedQuestion" && form.elements[i].name!="defaultQuestion"){
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

    var selectedDefaultQuestion=form.defaultQuestion;
    if(selectedDefaultQuestion.options[selectedDefaultQuestion.selectedIndex].value==="" && form.customedQuestion.value==""){

        document.getElementById("regControl").textContent="Select a secret question!";
        document.getElementById("regControl").style.display="inline";
        return false
    }
    if(selectedDefaultQuestion.options[selectedDefaultQuestion.selectedIndex].value!="new" && form.customedQuestion.value!=""){

        document.getElementById("regControl").textContent="Select just one secret question!";
        document.getElementById("regControl").style.display="inline";
        return false
    }

    document.getElementById("regControl").style.display="none";
    return true;
}

function invalidHandler(field){

    var form=document.form_registration;
    var controlRepassword = document.getElementById('control_repassword')
    if(field.name=="repassword")			//Check if password and repassword match
    {
        if (form.password.value != form.repassword.value)
        {
            form.repassword.className = "registrationInput";
            controlRepassword.classList.remove('hidden')
            controlRepassword.textContent="The passwords don't match!";
            return;
        } else {
            controlRepassword.classList.add('hidden')
        }

    }
    if (field.validity.patternMismatch || field.validity.typeMismatch)
    {
        field.className = "error";
        document.getElementById("control_"+field.name).classList.remove('hidden');
        document.getElementById("control_"+field.name).textContent="Invalid input";
        return;
    }
    field.className = "registrationInput";
    document.getElementById("control_"+field.name).classList.add('hidden');

}

function initializeRegistrationHandler()
{
    var form=document.form_registration;

    for(var i=0;i <form.elements.length;i++)
    {
        if(form.elements[i].className=="registrationInput")
        {
            form.elements[i].oninput=new Function("invalidHandler(this);");
        }
    }
    form.password.onkeyup=new Function("checkPasswordSecurity();");
    form.onsubmit = new Function("return registrate()");

}
initializeRegistrationHandler();



