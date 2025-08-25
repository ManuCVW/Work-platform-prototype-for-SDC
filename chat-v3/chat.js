function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

var val;
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
    var url = document.getElementsByClassName("chat")[0].getAttribute("id") != null? 'divChat.php?id='+document.getElementsByClassName("chat")[0].getAttribute("id") : 'divChat.php';
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

    var url = 'sendingChat.php?id='+document.getElementsByClassName("chat")[0].getAttribute("id")+'&name='+name;

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
        var url = 'sendMessage.php?name='+document.getElementById("name-sending-chat").innerText+"&id="+document.getElementsByClassName("chat")[0].getAttribute("id")+"&msg="+msg;
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