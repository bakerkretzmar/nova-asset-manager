Nova.booting((Vue, router) => {
    // Vue.component('index-asset-manager-field', require('./components/IndexField'))
    Vue.component('detail-asset-manager-field', require('./components/DetailField'))
    Vue.component('form-asset-manager-field', require('./components/FormField'))
})
