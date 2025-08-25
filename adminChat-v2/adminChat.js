function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

$(document).ready(function(){
    

    actListChat();
});

var listBefore;

async function actListChat() {
    await sleep(500);
    console.log("aeaaaa");
    var url = "listChats.php";
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data){
            for (let index = 0; index < document.getElementsByClassName("list-chat").length; index++) {
                if(listBefore != data){
                    document.getElementsByClassName("list-chat")[index].innerHTML = data;
                    listBefore = data;
                }
                actListChat();
            }
        }
    });
}

function chatModify(name){
    openUpdating(name);
}

function chatDelete(value){
    console.log("deleteChat.php?name="+value);
    if(confirm("¿Quieres eliminar el chat: "+value+"?") == true){
        var url = "deleteChat.php?name="+value;
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data){
                console.log(data);
            }
        });
    }
}

function chatCreate(){
    openCreating();
}

// Apartado para crear nuevo chat
//--------------------

let dic_users = {};

function deleteUser(id, view){
    delete dic_users[id];
    if(view == 0){
        actUsersCreating();
    }
    else if (view == 1){
        actUsersUpdating();
    }
}

function deleteAllUsers(view){
    for(const key in dic_users){
        deleteUser(key, view);
    }
}

function addUser(view){
    var url = "";
    if(view==0){
        url = "addSelectedName.php?id="+document.getElementById("select-name-creating-chat").value;
    }
    else if(view==1){
        url = "addSelectedName.php?id="+document.getElementById("select-name-updating-chat").value
    }
    url += "&view="+view;
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data){
            if(view==0){
                dic_users[document.getElementById("select-name-creating-chat").value] = data;
                actUsersCreating();
            }
            else if(view=1){
                dic_users[document.getElementById("select-name-updating-chat").value] = data;
                actUsersUpdating();
            }
        }
    });
}

//---CREATING---

function openCreating(){
    document.getElementById("creating-chat").hidden = false;
}

function closeCreating(){
    deleteAllUsers(0);
    document.getElementById("txt-chat-name-creating-chat").value = "";
    document.getElementById("creating-chat").hidden = true;
}

function actUsersCreating(){
    document.getElementById("list-names-creating-chat").innerHTML = "";
    for(const key in dic_users){
        document.getElementById("list-names-creating-chat").innerHTML += dic_users[key];
    }
}

function cancelCreating(){
    closeCreating();
}

function acceptCreating(){
    let arr_users = [];
    let empty = true;
    for(const key in dic_users){
        if (empty){
            empty = false;
        }
        arr_users.push(key);
    }
    if(!empty){
        let name = document.getElementById("txt-chat-name-creating-chat").value;
        var url = "createChat.php?name="+name+"&users=,";
        if(name.trim() == ""){
            alert("Nombre de grupo vacío");
            return;
        }
        for(let i = 0; i < arr_users.length; i++){
            url += arr_users[i]+",";
        }

        $.ajax({
            type: 'GET',
            url: url,
            success: function (data){
                console.log(data);
            }
        });
    }
    else{
        alert("No hay usuarios registrados");
        return;
    }

    closeCreating();
}

//---UPDATING---

var baseName = "";

function openUpdating(name){
    baseName = name;
    document.getElementById("updating-chat").hidden = false;
    document.getElementById("txt-chat-name-updating-chat").value = name;

    var arr_users = document.getElementsByClassName("item-list-chat-users-user-"+name);
    for (let index = 0; index < arr_users.length; index++) {
        var url = "addSelectedName.php?id="+arr_users[index].value+"&view=1";
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data){
                dic_users[arr_users[index].value] = data;
                actUsersUpdating();
            }
        });
    }
}

function closeUpdating(){
    deleteAllUsers(1);
    document.getElementById("txt-chat-name-updating-chat").value = "";
    document.getElementById("updating-chat").hidden = true;
}

function actUsersUpdating(){
    document.getElementById("list-names-updating-chat").innerHTML = "";
    for(const key in dic_users){
        document.getElementById("list-names-updating-chat").innerHTML += dic_users[key];
    }
}

function cancelUpdating(){
    closeUpdating();
}

function acceptUpdating(){
    let arr_users = [];
    let empty = true;
    for(const key in dic_users){
        if (empty){
            empty = false;
        }
        arr_users.push(key);
    }
    if(!empty){
        let name = document.getElementById("txt-chat-name-updating-chat").value;
        var url = "updateChat.php?name="+baseName+"&nName="+name+"&users=,";
        if(name.trim() == ""){
            alert("Nombre de grupo vacío");
            return;
        }
        for(let i = 0; i < arr_users.length; i++){
            url += arr_users[i]+",";
        }

        $.ajax({
            type: 'GET',
            url: url,
            success: function (data){
                console.log(data);
            }
        });
    }
    else{
        alert("No hay usuarios registrados");
        return;
    }

    closeUpdating();
}

//--------------------