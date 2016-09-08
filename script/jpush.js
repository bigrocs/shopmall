/**
 * Created by xinze on 15/7/6.
 */

function stopPush(){
    var params = {

    };
    var data = JSON.stringify(params);
    uexJPush.stopPush(data);
}

function resumePush(){
    var params = {

    };
    var data = JSON.stringify(params);
    uexJPush.resumePush(data);
}

function isPushStopped(){
    var params = {

    };
    var data = JSON.stringify(params);
    uexJPush.isPushStopped(data);
}

function setAliasAndTags(){
    var tags=new Array("tag4","tag5","tag6");
    var params = {
        alias:"alias66",
        tags:tags
    };
    var data = JSON.stringify(params);
    uexJPush.setAliasAndTags(data);
}

function setAlias(){
    var params = {
        alias:"alias22"
    };
    var data = JSON.stringify(params);
    uexJPush.setAlias(data);
}

function setTags(){
    var tags=new Array("tag1","tag2","tag3");
    var params = {
        tags:tags
    };
    var data = JSON.stringify(params);
    uexJPush.setTags(data);
}

function getRegistrationID(){
    var params = {

    };
    var data = JSON.stringify(params);
    uexJPush.getRegistrationID(data);
}

function reportNotificationOpened(){
    var params = {
        msgId:1222
    };
    var data = JSON.stringify(params);
    uexJPush.reportNotificationOpened(data);
}

function clearAllNotifications(){
    var params = {

    };
    var data = JSON.stringify(params);
    uexJPush.clearAllNotifications(data);
}

function clearNotificationById(){
    var id = document.getElementById("clearNotificationById").value;
    var params = {
        notificationId:id
    };
    var data = JSON.stringify(params);
    uexJPush.clearNotificationById(data);
}

function setPushTime(){
    var weekDays=new Array("0","1","2","3","4");
    var params = {
        weekDays:weekDays,
        startHour:0,
        endHour:18
    };
    var data = JSON.stringify(params);
    uexJPush.setPushTime(data);
}

function setSilenceTime(){
    var params = {
        startHour:0,
        startMinute:1,
        endHour:13,
        endMinute:0
    };
    var data = JSON.stringify(params);
    uexJPush.setSilenceTime(data);
}

function setLatestNotificationNumber(){
    var params = {
        maxNum:4
    };
    var data = JSON.stringify(params);
    uexJPush.setLatestNotificationNumber(data);
}

function getConnectionState(){
    var params = {

    };
    var data = JSON.stringify(params);
    uexJPush.getConnectionState(data);
}

function addLocalNotification(){
    var params = {
        builderId:0,
        title:"这是title",
        content:"这是内容",
        extras:{"key":"value"},
        notificationId:3,
        broadCastTime:10000
    };
    var data = JSON.stringify(params);
    alert(data);
    uexJPush.addLocalNotification(data);
}

function removeLocalNotification(){
    var notificationId=3;
    var params = {
        notificationId:notificationId
    };
    var data = JSON.stringify(params);
    uexJPush.removeLocalNotification(data);
}

function clearLocalNotifications(){
    var params = {

    };
    var data = JSON.stringify(params);
    uexJPush.clearLocalNotifications(data);
}

function cbIsPushStopped(info){
    alert(info);
}

function onReceiveRegistration(info){
    //alert(info);
}

function onReceiveMessage(info){
    //alert(info);
}

function onReceiveNotification(info){

     //alert(info);
    /*
     try
     {
     eval("var data = " + info);
     }
     catch (e)
     {
     alert(e.message);
     alert(e.description)
     alert(e.number)
     alert(e.name)
     }

     if (1 == data.extras.type)
     {
     alert(data.extras.url);
     }
     else
     {
     alert('0')
     }
     */
}

function onReceiveNotificationOpen(info){
    alert(info);
}

function cbSetAliasAndTags(info){
    alert(info);
}

function cbSetAlias(info){
    alert(info);
}

function cbSetTags(info){
    alert(info);
}

function cbGetRegistrationID(info){
    alert(info);
}

function cbGetConnectionState(info){
    alert(info);
}

function onReceiveConnectionChange(info){
    alert(info);
}

window.uexOnload = function(type){
    if(type == 0){
        //uexJPush.cbIsPushStopped = cbIsPushStopped;
        uexJPush.onReceiveRegistration = onReceiveRegistration;

        //uexJPush.onReceiveMessage = onReceiveMessage;
        uexJPush.onReceiveNotification = onReceiveNotification;
        /*
         uexJPush.onReceiveNotificationOpen = onReceiveNotificationOpen;
         uexJPush.cbSetAliasAndTags = cbSetAliasAndTags;
         uexJPush.cbSetAlias = cbSetAlias;
         uexJPush.cbSetTags = cbSetTags;
         uexJPush.cbGetRegistrationID = cbGetRegistrationID;
         uexJPush.cbGetConnectionState = cbGetConnectionState;
         uexJPush.onReceiveConnectionChange = onReceiveConnectionChange;
         */

        var user_name = window.buid;

        if (user_name)
        {
            user_name = 'u_' + user_name;
            var tags = new Array(user_name);
            var params = {
                tags:tags
            };

            var data = JSON.stringify(params);
            uexJPush.setTags(data);
        }
    }
}
