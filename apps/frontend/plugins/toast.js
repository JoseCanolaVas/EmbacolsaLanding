import Vue from 'vue'

export const toastBus = new Vue()

export default function (_context, inject) {
    const show = (type, message, options = {}) => {
        toastBus.$emit('toast:show', {
            id: Date.now(),
            type,
            message,
            timeout: options.timeout || 3500
        })
    }

    inject('toast', {
        success(message, options = {}) {
            show('success', message, options)
        },

        error(message, options = {}) {
            show('error', message, options)
        },

        warning(message, options = {}) {
            show('warning', message, options)
        },

        info(message, options = {}) {
            show('info', message, options)
        }
    })
}