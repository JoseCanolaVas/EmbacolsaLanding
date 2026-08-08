import Vue from 'vue'

const state = Vue.observable({
    visible: false
})

export default (_, inject) => {
    inject('preload', (value) => {
        state.visible = value
    })

    inject('preloadState', state)
}