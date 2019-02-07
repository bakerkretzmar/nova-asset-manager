Nova.booting((Vue, router) => {
    Vue.config.devtools = true

    router.addRoutes([
        {
            name: 'nova-asset-manager',
            path: '/asset-manager',
            component: require('./components/Tool'),
        },
    ])

    Vue.component('file-icon', require('./components/icons/Icon'))
})
