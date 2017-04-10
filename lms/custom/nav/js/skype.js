
// Reference to SkypeBootstrap.min.js
// Implements the Skype object model via https://swx.cdn.skype.com/shared/v/1.2.15/SkypeBootstrap.min.js

// Call the application object
var config = {
    apiKey: 'a42fcebd-5b43-4b89-a065-74450fb91255', // SDK
    apiKeyCC: '9c967f6b-a846-4df2-b43d-5167e47d81e1' // SDK+UI
};

var Application;

Skype.initialize({apiKey: config.apiKey}, function (api) {
    window.skypeWebApp = new api.application();
    //Make sign in table appear
    $(".menu #sign-in").click();
    // whenever client.state changes, display its value
    window.skypeWebApp.signInManager.state.changed(function (state) {
        $('#client_state').text(state);
    });
}, function (err) {
    console.log(err);
    alert('Cannot load the SDK.');
});

// Sign-in code typically follows here.

