import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {

    static values = { pagina: Number }
    
    redirecionar() {        
        const pagina = this.paginaValue;
        
        const url = new URL(window.location.href);
        
        url.searchParams.set("pagina", pagina);
        
        $.LoadingOverlay('show');
        
        window.location.href = url.toString();
    }

}
