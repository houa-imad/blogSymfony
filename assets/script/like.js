
import axios from 'axios';
export default class Like {
    constructor(likeEle) {

        this.likeEle = likeEle;

        if (this.likeEle) {
          this.init();
        }
    }
    // Recupere les éléments du DOM
    init(){
        this.likeEle.map((ele) => {
            ele.addEventListener('click', this.onClick);
        });
    }

    onClick(e){
        e.preventDefault();
        const url = this.href;

        axios.get(url).then((response) => {
            // console.log(response);

            // Recuperer le nombre de likes 
            const likeCount = response.data.likeCount;

            // Modifier le nombre de likes
            const span = this.querySelector('span');
            this.dataset.lenght = likeCount;
            span.innerHTML = likeCount + 'J\'aime';

            // Modifier l'icone

            const iconeFilled = this.querySelector('svg.filled');
            const iconeUnFilled= this.querySelector('svg.unfilled');

            console.log(iconeFilled);
            console.log(iconeUnFilled);

            iconeFilled.classList.toggle('hidden');
            iconeUnFilled.classList.toggle('hidden');
            

    });
}
}