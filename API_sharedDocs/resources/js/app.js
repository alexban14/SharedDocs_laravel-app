import './bootstrap';

// subscribe to a public channel
import axios from "axios";

const form = document.getElementById('form');
const inputMessage = document.getElementById('input-message');
const listMessage = document.getElementById('list-messages');
form.addEventListener('submit', function (event){
    event.preventDefault();
    const userInput = inputMessage.value;

    axios.post('/chat-message', {
        message: userInput
    })

});

// subscribe to websocket channels
const channel = Echo.channel('public.chat.1'); // returns a channel object that provides a multitude of helper methods

channel.subscribed( function() {
    console.log('subscribed');
} );

// listen to websocket event
channel.listen('.chat-message', (event) => {
    console.log(event);
    const message = event.message;
    const li = document.createElement('li');
    li.textContent = message;
    listMessage.append;
});
