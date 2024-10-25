import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = [ "prixHt", "tva", "prixTtc" ]
    init(){
        this.prixHtTarget.value= 0 ;
        this.prixTtcTarget.value=0;
    }
    connect() {
        
    }

    updatePrices() {
        this.updatettc();
        this.updateht();


    }

    update() {
        this.updatePrices();
    }
    updatettc() {
        const prixHt = parseFloat(this.prixHtTarget.value) ;
        const tva = parseFloat(this.tvaTarget.value) ;
        this.prixTtcTarget.value = prixHt * (1 + tva);
    }
    updateht() {
        const tva = parseFloat(this.tvaTarget.value) ;
        const prixTtc = parseFloat(this.prixTtcTarget.value);
        this.prixHtTarget.value = prixTtc / (1 + tva);
    }
}