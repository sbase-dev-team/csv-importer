<template>
    <div class="modal fade" id="mapping" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mapping File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-inline" @submit="addAttribute">
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="new-attr" class="sr-only">New Attribute</label>
                            <input type="text" class="form-control" id="new-attr" v-model="newAttr" placeholder="New Attribute">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Add</button>
                    </form>
                    <form class="form-horizontal">
                        <table class="table">
                            <tr scope="row" v-for="header in headers">
                                <td v-for="item in header">{{ item }}</td>
                            </tr>
                            <tr>
                                <td v-for="(header, key) in headers[0]">
                                    <select :name="header" v-model="columns[key]">
                                        <option v-for="column in columns" :value="column">{{ column }}</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="$emit('process')">Import Data</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MappingModalComponent",
        props: {
            headers: {
                type: Array,
                required: true
            },
            columns: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                newAttr: ''
            }
        },
        methods: {
            addAttribute: function (e) {
                e.preventDefault()
                let vm = this
                axios
                    .post('/api/file/attr', {name: this.newAttr})
                    .then(function(response) {
                        vm.columns.push(response.data.name)
                        alert('Success')
                        vm.newAttr = ''
                    });
            }
        }
    }
</script>

<style scoped>
    .modal-body {
        overflow-x: auto;
    }
    .modal-dialog {
        max-width: 80%;
    }
</style>