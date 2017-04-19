<?php
require_once './classes/Hangout.php';
$h = new Hangout();
$userid = $_REQUEST['userid'];
$roomid = $_REQUEST['roomid'];
$courseid = $_REQUEST['courseid'];
//$roleid=$h->get_use
?>

<html>
    <head>
        <title>Meeting</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

        <!-- Latest compiled and minified Bootstrap JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Latest compiled and minified Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >

        <!-- Site script -->
        <script type='text/javascript' src='https://medical2.com/lms/custom/nav/js/navigation.js'></script>

        <!-- Optional Bootstrap theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >

        <style>
            video {
                object-fit: fill;
                width: 30%;
            }
            button,
            input,
            select {
                font-weight: normal;
                padding: 2px 4px;
                text-decoration: none;
                display: inline-block;
                text-shadow: none;
                font-size: 16px;
                outline: none;
            }
            .make-center {
                text-align: center;
                padding: 5px 10px;
            }
        </style>

    </head>
    <body>
        <div class="text-center"><br/><a href="https://medical2.com/"><img src="https://medical2.com/assets/logo/5_edited.png" width="350" height="90"></a></div>

        <!-- Place here rest of hangout code -->
        <br><article>

            <header style="text-align: center;">

            </header>

            <section class="experiment">

                <div class="make-center">
                    <input type="text" id="room-id" style="display:none;" value="<?php echo $roomid; ?>">
                    <button id="open-room" style="display:none;">Open Room</button>
                    <button id="join-room" style="display:none;">Join Room</button>
                    <button id="open-or-join-room" style="display:none;">Auto Open Or Join Room</button>


                    <span id="room-urls" style="width:80%;font-weight: bold; text-align: center;margin: auto;font-size: 16px;border:1px;"></span>
                    <span><br/><button id='show_dialog' class='btn btn-success'>Invite participants</button></span>
                    <br/><br/>
                    <button id="btn-leave-room" disabled class="btn btn-success">Leave meeting</button>
                    <br/><br/>
                </div>

            </section>
            <div id="videos-container"></div>

            <br><br>

            <div id="chat-container" class='container-fluid' style="padding-left:10px;">

                <input type="text" id="input-text-chat" placeholder="Enter Text Chat" disabled style='display:none;'>
                <br/>

                <div class="container-fluid">
                    <span class="col-md-6">Only Excel, Text and Word files could shared, other files are blocked due to security concerns</span>
                </div>
                <br/>
                <div class="container-fluid">
                    <span class="col-md-1"><button id="share-file" disabled class="btn btn-success">Share File</button></span>
                </div>

                <br><br>
            </div>


            <section>

                <div id="file-container" style="width:95%;margin: auto;text-align: center;"></div>
                <div class="chat-output"></div>

            </section>

            <script src="/lms/custom/hangouts/dist/RTCMultiConnection.min.js"></script>
            <script src="https://rtcmulticonnection.herokuapp.com/socket.io/socket.io.js"></script>

            <!-- custom layout for HTML5 audio/video elements -->
            <script src="https://cdn.webrtc-experiment.com/getMediaElement.js"></script>
            <script src="https://cdn.webrtc-experiment.com:443/FileBufferReader.js"></script>

            <script>


                // ......................................................
                // .......................UI Code........................
                // ......................................................
                document.getElementById('open-room').onclick = function () {
                    disableInputButtons();
                    connection.open(document.getElementById('room-id').value, function () {
                        showRoomURL(connection.sessionid);
                    });
                };
                document.getElementById('join-room').onclick = function () {
                    disableInputButtons();
                    connection.join(document.getElementById('room-id').value);
                };
                document.getElementById('open-or-join-room').onclick = function () {
                    disableInputButtons();
                    connection.openOrJoin(document.getElementById('room-id').value, function (isRoomExists, roomid) {
                        if (!isRoomExists) {
                            showRoomURL(roomid);
                        }
                    });
                };
                document.getElementById('btn-leave-room').onclick = function () {
                    this.disabled = true;
                    if (connection.isInitiator) {
                        // use this method if you did NOT set "autoCloseEntireSession===true"
                        // for more info: https://github.com/muaz-khan/RTCMultiConnection#closeentiresession
                        connection.closeEntireSession(function () {
                            document.querySelector('h1').innerHTML = 'Entire session has been closed.';
                        });
                    } else {
                        connection.leave();
                    }
                };
                // ......................................................
                // ................FileSharing/TextChat Code.............
                // ......................................................
                document.getElementById('share-file').onclick = function () {
                    var fileSelector = new FileSelector();
                    fileSelector.selectSingleFile(function (file) {
                        connection.send(file);
                    });
                };
                document.getElementById('input-text-chat').onkeyup = function (e) {
                    if (e.keyCode != 13)
                        return;
                    // removing trailing/leading whitespace
                    this.value = this.value.replace(/^\s+|\s+$/g, '');
                    if (!this.value.length)
                        return;
                    connection.send(this.value);
                    appendDIV(this.value);
                    this.value = '';
                };
                var chatContainer = document.querySelector('.chat-output');
                function appendDIV(event) {
                    var div = document.createElement('div');
                    div.innerHTML = event.data || event;
                    chatContainer.insertBefore(div, chatContainer.firstChild);
                    div.tabIndex = 0;
                    div.focus();
                    document.getElementById('input-text-chat').focus();
                }
                // ......................................................
                // ..................RTCMultiConnection Code.............
                // ......................................................
                var connection = new RTCMultiConnection();
                // by default, socket.io server is assumed to be deployed on your own URL
                //connection.socketURL = '/';
                // comment-out below line if you do not have your own socket.io server
                connection.socketURL = 'https://rtcmulticonnection.herokuapp.com:443/';
                connection.socketMessageEvent = 'audio-video-file-chat-demo';
                connection.enableFileSharing = true; // by default, it is "false".
                connection.session = {
                    audio: true,
                    video: true,
                    data: true
                };
                connection.sdpConstraints.mandatory = {
                    OfferToReceiveAudio: true,
                    OfferToReceiveVideo: true
                };
                connection.videosContainer = document.getElementById('videos-container');
                connection.onstream = function (event) {
                    var width = parseInt(connection.videosContainer.clientWidth / 2) - 20;
                    var mediaElement = getMediaElement(event.mediaElement, {
                        title: event.userid,
                        buttons: ['full-screen'],
                        width: width,
                        showOnMouseEnter: false
                    });
                    connection.videosContainer.appendChild(mediaElement);
                    setTimeout(function () {
                        mediaElement.media.play();
                    }, 5000);
                    mediaElement.id = event.streamid;
                };
                connection.onstreamended = function (event) {
                    var mediaElement = document.getElementById(event.streamid);
                    if (mediaElement) {
                        mediaElement.parentNode.removeChild(mediaElement);
                    }
                };
                connection.onmessage = appendDIV;
                connection.filesContainer = document.getElementById('file-container');
                connection.onopen = function () {
                    document.getElementById('share-file').disabled = false;
                    document.getElementById('input-text-chat').disabled = false;
                    document.getElementById('btn-leave-room').disabled = false;
                    document.querySelector('h1').innerHTML = 'You are connected with: ' + connection.getAllParticipants().join(', ');
                };
                connection.onclose = function () {
                    if (connection.getAllParticipants().length) {
                        document.querySelector('h1').innerHTML = 'You are still connected with: ' + connection.getAllParticipants().join(', ');
                    } else {
                        document.querySelector('h1').innerHTML = 'Seems session has been closed or all participants left.';
                    }
                };
                connection.onEntireSessionClosed = function (event) {
                    document.getElementById('share-file').disabled = true;
                    document.getElementById('input-text-chat').disabled = true;
                    document.getElementById('btn-leave-room').disabled = true;
                    document.getElementById('open-or-join-room').disabled = false;
                    document.getElementById('open-room').disabled = false;
                    document.getElementById('join-room').disabled = false;
                    document.getElementById('room-id').disabled = false;
                    connection.attachStreams.forEach(function (stream) {
                        stream.stop();
                    });
                    // don't display alert for moderator
                    if (connection.userid === event.userid)
                        return;
                    document.querySelector('h1').innerHTML = 'Entire session has been closed by the moderator: ' + event.userid;
                };
                connection.onUserIdAlreadyTaken = function (useridAlreadyTaken, yourNewUserId) {
                    // seems room is already opened
                    connection.join(useridAlreadyTaken);
                };
                function disableInputButtons() {
                    document.getElementById('open-or-join-room').disabled = true;
                    document.getElementById('open-room').disabled = true;
                    document.getElementById('join-room').disabled = true;
                    document.getElementById('room-id').disabled = true;
                }
                // ......................................................
                // ......................Handling Room-ID................
                // ......................................................
                function showRoomURL(roomid) {
                    var roomHashURL = '#' + roomid;
                    var roomQueryStringURL = '?roomid=' + roomid;
                    var html = '';
                    //html += 'Hash URL: <a href="' + roomHashURL + '" target="_blank">' + roomHashURL + '</a>';
                    html += '<br>';
                    html += 'Meeting URL: <a href="' + roomQueryStringURL + '" target="_blank">' + 'https://medical2.com/lms/custom/hangouts/meeting.php' + roomQueryStringURL + '</a>';
                    var roomURLsDiv = document.getElementById('room-urls');
                    roomURLsDiv.innerHTML = html;
                    roomURLsDiv.style.display = 'block';
                }
                (function () {
                    var params = {},
                            r = /([^&=]+)=?([^&]*)/g;
                    function d(s) {
                        return decodeURIComponent(s.replace(/\+/g, ' '));
                    }
                    var match, search = window.location.search;
                    while (match = r.exec(search.substring(1)))
                        params[d(match[1])] = d(match[2]);
                    window.params = params;
                })();
                var roomid = '';
                if (localStorage.getItem(connection.socketMessageEvent)) {
                    roomid = localStorage.getItem(connection.socketMessageEvent);
                } else {
                    roomid = connection.token();
                }
                document.getElementById('room-id').value = roomid;
                document.getElementById('room-id').onkeyup = function () {
                    localStorage.setItem(connection.socketMessageEvent, this.value);
                };
                var hashString = location.hash.replace('#', '');
                if (hashString.length && hashString.indexOf('comment-') == 0) {
                    hashString = '';
                }
                var roomid = params.roomid;
                if (!roomid && hashString.length) {
                    roomid = hashString;
                }
                if (roomid && roomid.length) {
                    document.getElementById('room-id').value = roomid;
                    localStorage.setItem(connection.socketMessageEvent, roomid);
                    // auto-join-room
                    (function reCheckRoomPresence() {
                        connection.checkPresence(roomid, function (isRoomExists) {
                            if (isRoomExists) {
                                connection.join(roomid);
                                return;
                            }
                            setTimeout(reCheckRoomPresence, 5000);
                        });
                    })();
                    disableInputButtons();
                }

                document.getElementById('room-id').value = '<?php echo $roomid ?>'
                document.getElementById("open-room").click();

            </script>

            <script>
                window.useThisGithubPath = 'muaz-khan/RTCMultiConnection';
            </script>
            <script src="https://cdn.webrtc-experiment.com/commits.js" async></script>

        </article>

        <div class="row">

        </div>

        <script src="https://www.webrtc-experiment.com/firebase.js"></script>
        <script src="https://www.webrtc-experiment.com/RTCPeerConnection-v1.5.js"></script>
        <script src="https://www.webrtc-experiment.com/broadcast/broadcast.js"></script>
        <script src="https://www.webrtc-experiment.com/broadcast/broadcast-ui.js"></script>	

    </body>
