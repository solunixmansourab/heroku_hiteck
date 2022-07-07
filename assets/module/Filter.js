/**
 * @property {HTMLElement} pagination
 * @property {HTMLElement} content
 * @property {HTMLFormElement} form
 */
export default class Filter
{
    /**
     * 
     * @param {HTMLElement|null} element 
     * @returns 
     */
    constructor(element) {
        if (element === null) {
            return;
        }
        this.content = element.querySelector('.js-filter-content');
        this.pagination = element.querySelector('.js-filter-pagination');
        this.form = element.querySelector('.js-filter-form');

        this.bindEvents();
    }

    bindEvents() {

        const aClickListener =  e => {
            if (e.target.tagName === 'A') {
                e.preventDefault()
                this.loadURL(e.target.getAttribute('href'))
            }
        }

        this.pagination.addEventListener('click', aClickListener)

        this.form.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', this.loadForm.bind(this))
        })
    }

    async loadForm() {
        const data = new FormData(this.form)
        const url = new URL(this.form.getAttribute('action') || window.location.href)
        const params = new URLSearchParams() 
        data.forEach((value, key) => {
            params.append(key, value)
        })
        
        return this.loadURL(url.pathname + '?' + params.toString())
    }

    async loadURL(url) {
        const ajaxUrl = url + '&ajax=1'

        const response = await fetch(ajaxUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })

        if (response.status >= 200 && response.status < 300) {
            const data = await response.json();

            this.content.innerHTML = data.content;
            //this.sorting.innerHTML = data.sorting;
            this.pagination.innerHTML = data.pagination;

            history.replaceState({}, '', url);
        } else {
            console.error(response);
        }
    }

}