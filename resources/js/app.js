import './bootstrap';
import { faker } from '@faker-js/faker';

class MailingCreate {

    #formSelector = '.js-mailing-create__form';
    #phoneInputSelector = '.js-mailing-create__phone-input';
    #messageTextareaSelector = '.js-mailing-create__message-textarea';
    #phoneAddBtnAddSelector = '.js-mailing-create__phone-input-btn-add';
    #phoneAddBtnDelSelector = '.js-mailing-create__phone-input-btn-del';
    #phoneAddBoxSelector = '.js-mailing-create__phone-input-box';
    #fakerBtnSelector = '.js-mailing-create__faker-btn';

    constructor() {
        document.addEventListener('DOMContentLoaded', () => {
            this.#init();
        })
    }

    #init = () => {
        if (!document.querySelector(this.#formSelector)) {
            return;
        }
        this.#bindPhoneAdd();
        this.#bindFakerBtn();
    }

    #bindFakerBtn = () => {
        document.querySelector(this.#fakerBtnSelector).addEventListener('click', (e) => {
            document.querySelectorAll(this.#phoneInputSelector).forEach((el) => {
                el.value = faker.phone.number({ style: 'international' });
            });
            document.querySelector(this.#messageTextareaSelector).value
                = faker.lorem.paragraph(8);
        });
    }

    #bindPhoneAdd = () => {
        document.querySelectorAll(this.#phoneAddBtnAddSelector).forEach((el) => {
            if (el.bindedMailingCreatePhoneAdd) {
                return;
            }
            el.bindedMailingCreatePhoneAdd = true;
            el.addEventListener('click', (e) => {
                const patentBox = e.currentTarget.closest(this.#phoneAddBoxSelector);
                patentBox.insertAdjacentHTML(
                    'afterend',
                    patentBox.outerHTML,
                )
                this.#bindPhoneAdd();
            });
        });

        document.querySelectorAll(this.#phoneAddBtnDelSelector).forEach((el) => {
            if (el.bindedMailingCreatePhoneDel) {
                return;
            }
            el.bindedMailingCreatePhoneDel = true;
            el.addEventListener('click', (e) => {
                if (document.querySelectorAll(this.#phoneAddBtnDelSelector).length < 2) {
                    return;
                }
                const patentBox = e.currentTarget.closest(this.#phoneAddBoxSelector);
                patentBox.remove()
            });
        });
    }
}

class MailingIndex {

    #mainBoxSelector = '.js-mailing-index__main-box';
    #listItemsStatusNewSelector = '.js-mailing-index__list-item[data-status=NEW]';

    constructor() {
        document.addEventListener('DOMContentLoaded', () => {
            this.#init();
        })
    }

    #init = () => {

        if (!document.querySelector(this.#mainBoxSelector)) {
            return;
        }

        if (document.querySelector(this.#listItemsStatusNewSelector)) {
            setTimeout(() => {
                location.reload()
            }, 888);
        }
    }
}

new MailingCreate();
new MailingIndex();
