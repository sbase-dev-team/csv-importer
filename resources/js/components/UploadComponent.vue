<template>
    <div class="container mt-2">
        <modal-mapping :headers="headers" :columns="columns" @process="importFile"></modal-mapping>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading mb-2">CSV Import</div>
                    <div class="panel-body">
                        <form class="form-horizontal" @submit="submitFile">
                            <div class="form-group">
                                <div class="custom-file">
                                    <input  id="csv_file" ref="file" @change="handleFileUpload()" accept=".csv" type="file" name="csv_file" class="custom-file-input" required>
                                    <label class="custom-file-label" for="csv_file">Choose file...</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" :disabled="!file">
                                    Parse CSV
                                </button>
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