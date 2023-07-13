/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './script/app.js';
import 'tw-elements';

import Like from './script/like.js';
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded');

    const likeEle =[].slice.call(document.querySelectorAll('a[data-action="like"]'));
 
    if(likeEle){
        // console.log(likeEle);
        new Like(likeEle);
    }
});


console.log('Hello Webpack Encore! Edit me in assets/app.js')