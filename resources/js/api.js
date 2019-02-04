export default {
    getData(pathToList) {
        return window.axios
            .get('/nova-vendor/infinety-es/nova-filemanager/data', {
                params: { folder: pathToList },
            })
            .then(response => response.data);
    },

    uploadFile() {
        return window.axios
            .post('/nova-vendor/infinety-es/nova-filemanager/uploads/add')
            .then(response => response.data);
    },

    createFolder(folder, path) {
        return Nova.request()
            .post('/nova-vendor/nova-asset-manager/actions/create-folder', {
                folder,
                current: path,
            })
            .then(response => response.data)
    },

    getInfo(file) {
        return window.axios
            .post('/nova-vendor/infinety-es/nova-filemanager/actions/get-info', { file: file })
            .then(response => response.data);
    },

    deleteFile(file) {
        return Nova.request()
            .post('/nova-vendor/nova-asset-manager/actions/delete-file', { file })
            .then(response => response.data)
    },
};
