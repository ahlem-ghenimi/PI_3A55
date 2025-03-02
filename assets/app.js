import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

import Botpress from 'botpress'; // Import the Botpress library

// Initialize the chatbot
const chatbot = new Botpress({
    container: document.getElementById('chatbot'),
    // Additional configuration options can be added here
});

console.log('Chatbot initialized successfully!'); // Log message for successful initialization
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
