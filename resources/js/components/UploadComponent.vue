<template>
    <div class="container">
        <modal-mapping :headers="headers" :columns="columns" @process="importFile"></modal-mapping>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">CSV Import</div>

                    <div class="panel-body">
                        <form class="form-horizontal" @submit="submitFile">
                            <div class="form-group">
                                <label for="csv_file" class="col-md-4 control-label">CSV file to import</label>

                                <div class="col-md-6">
                                    <input id="csv_file" ref="file" @change="handleFileUpload()" type="file" class="form-control" name="csv_file" accept=".csv"required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="header" checked> File contains header row?
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary" :disabled="!file">
                                        Parse CSV
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UploadComponent",
        data() {
            return {
                file: '',
                headers: [],
                columns: [

                ]
            }
        },
        methods: {
            handleFileUpload: function () {
                this.file = this.$refs.file.files[0]
            },
            submitFile: function (e) {
                e.preventDefault()
                let formData = new FormData()
                formData.append('file', this.file)
                let vm = this
                axios
                    .post('/api/file/parse', formData)
                    .then(function(response) {
                        vm.headers = response.data.headers
                        vm.columns = response.data.columns
                        $('#mapping').modal('show')
                });
            },
            importFile: function () {
                let formData = new FormData()
                formData.append('file', this.file)
                formData.append('fields', this.columns)
                let vm = this
                axios
                    .post('/api/file/import', formData)
                    .then(function(response) {
                        $('#mapping').modal('hide')
                        vm.file = ''
                    })
                    .catch(function (error) {
                        alert(error.response.data.error)
                    });
            }
        }
    }
</script>