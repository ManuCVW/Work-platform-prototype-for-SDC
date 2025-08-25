const mobileMenuBtn = document.querySelector('#mobile-menu');
const cerrarMenuBtn = document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar');

if(mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', function() {
        sidebar.classList.add('mostrar');
    });
}

if(cerrarMenuBtn) {
    cerrarMenuBtn.addEventListener('click', function() {
        sidebar.classList.add('ocultar');
        setTimeout(() => {
            sidebar.classList.remove('mostrar');
            sidebar.classList.remove('ocultar');
        }, 1000);
    })
}

// Elimina la clase de mostrar, en un tamaño de tablet y mayores
const anchoPantalla = document.body.clientWidth;

window.addEventListener('resize', function() {
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla >= 768) {
        sidebar.classList.remove('mostrar');
    }
})


function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

$(document).ready(function(){
    

    actListChat();
});

var listBefore;
var baseDir = "http://localhost:3000/adminChat-v3/";

async function actListChat() {
    await sleep(500);
    console.log("aeaaaa");
    
    var url = baseDir + "listChats";
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
        var url = baseDir + "deleteChat?name="+value;
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
        url = baseDir + "addSelectedName?id="+document.getElementById("select-name-creating-chat").value;
    }
    else if(view==1){
        url = baseDir + "addSelectedName?id="+document.getElementById("select-name-updating-chat").value
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
        var url = baseDir + "createChat?name="+name+"&users=,";
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
        var url = baseDir + "addSelectedName?id="+arr_users[index].value+"&view=1";
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
        var url = baseDir + "updateChat?name="+baseName+"&nName="+name+"&users=,";
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


var val;
var baseChat = "http://localhost:3000/chat-v3/";
var isActive = false;

$(document).ready(function(){
    

    actChat();
    actSelectedChat();
    

    document.getElementById("img-chat").addEventListener("click", changeContentBoxActive);
    document.getElementById("close-chat").addEventListener("click", changeContentBoxActive);

    document.getElementById("close-sending-chat").addEventListener("click", closeSending);
    document.getElementById("hide-sending-chat").addEventListener("click", hideSending);

    document.getElementById("btn-send-chat").addEventListener("click", sendMessage);
});



async function actChat() {
    await sleep(500);
    var url = baseChat;
    url += document.getElementsByClassName("chat")[0].getAttribute("id") != null? 'divChat?id='+document.getElementsByClassName("chat")[0].getAttribute("id") : 'divChat';
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data){
            for (let index = 0; index < document.getElementsByClassName("content-box-chat").length; index++) {
                document.getElementsByClassName("content-box-chat")[index].innerHTML = data;
            }
            actChat();
        }
    });
}

function changeContentBoxActive(){
    isActive = !isActive;
    for (let o = 0; o < document.getElementsByClassName("box-chat").length; o++) {
        if(isActive){
            document.getElementsByClassName("box-chat")[o].setAttribute("isactive", "true");
        }
        else{
            document.getElementsByClassName("box-chat")[o].setAttribute("isactive", "false");
        }
    }
}

var actName = null;
var lastData = null;

function selectChat(name, toNotHide = true){
    document.getElementById("name-sending-chat").textContent = name;

    var url = baseChat + 'sendingChat?id='+document.getElementsByClassName("chat")[0].getAttribute("id")+'&name='+name;

    for (let i = 0; i < document.getElementsByClassName("sending-chat").length; i++) {
        document.getElementsByClassName("sending-chat")[i].setAttribute("active", "");
        if(toNotHide){
            document.getElementsByClassName("sending-chat")[i].removeAttribute("hide");
        }
    }
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data){
            for (let index = 0; index < document.getElementsByClassName("content-sending-chat").length; index++) {
                if(lastData != data){
                    lastData = data;
                    document.getElementsByClassName("content-sending-chat")[index].innerHTML = lastData;
                    let cont = document.getElementsByClassName("content-sending-chat")[0];
                    cont.scrollTo(0, cont.scrollHeight);
                }
            }
        }
    });

    actName = name;
}

function closeSending(){
    actName = null;
    for (let i = 0; i < document.getElementsByClassName("sending-chat").length; i++) {
        document.getElementsByClassName("sending-chat")[i].removeAttribute("active");
        document.getElementsByClassName("sending-chat")[i].removeAttribute("hide");
    }
    for (let i = 0; i < document.getElementsByClassName("content-sending-chat").length; i++) {
        document.getElementsByClassName("content-sending-chat")[i].innerHTML = null;
        lastData = null;
    }
}

function hideSending(){
    for (let i = 0; i < document.getElementsByClassName("sending-chat").length; i++) {
        if(document.getElementsByClassName("sending-chat")[i].hasAttribute("hide")){
            document.getElementsByClassName("sending-chat")[i].removeAttribute("hide");
        }
        else{
            document.getElementsByClassName("sending-chat")[i].setAttribute("hide", "");
        }
    }
}

async function actSelectedChat() {
    await sleep(500);
    if(actName != null){
        selectChat(actName, false);
    }

    actSelectedChat();
}

function sendMessage(){
    if(document.getElementById("txt-send-chat").value.trim() != ""){
        let msg = document.getElementById("txt-send-chat").value.trim();
        var url = baseChat + 'sendMessage?name='+document.getElementById("name-sending-chat").innerText+"&id="+document.getElementsByClassName("chat")[0].getAttribute("id")+"&msg="+msg;
        console.log(url);
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data){
                selectChat(actName, false);
            }
        });
    }
    document.getElementById("txt-send-chat").value = "";
}