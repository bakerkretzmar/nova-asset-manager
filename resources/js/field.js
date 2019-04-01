Nova.booting((Vue, router) => {
    // Vue.component('index-asset-manager-field', require('./components/IndexField'))
    Vue.component('detail-asset-manager-field', require('./components/DetailField'))
    Vue.component('form-asset-manager-field', require('./components/FormField'))

    Vue.component('asset-manager-back-button', require('./components/partials/BackButton'))
    Vue.component('asset-manager-breadcrumbs', require('./components/partials/Breadcrumbs'))
    Vue.component('asset-manager-view-buttons', require('./components/partials/ViewButtons'))

    Vue.component('asset-manager-form-grid-item', require('./components/partials/FormFieldGridItem'))

    Vue.component('asset-manager', require('./components/Manager'))
})
