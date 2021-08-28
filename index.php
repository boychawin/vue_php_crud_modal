<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5" id="app">

        <h3 align="center"> CRUD APP</h3>
        <hr>

        <br>

        <div class="row">
            <div class="col-md-6">
                <h3 class="panel-title"> Users Data</h3>
            </div>
            <div class="col-md-6" align="right">

            <button v-modal="actionButton" @click="openModal" type="button" name="add" class="btn btn-primary add" data-bs-toggle="modal" data-bs-target="#myModal">
                            Add
                        </button>


            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

                <tr v-for="row in users">
                    <th scope="row">{{row.id}}</th>
                    <td>{{row.fname}}</td>
                    <td>{{row.lname}}</td>
                    <td>{{row.email}}</td>
                    <td>

                        <button v-modal="actionButton" @click="fetchData(row.id)" type="button" name="edit" class="btn btn-warning edit" data-bs-toggle="modal" data-bs-target="#myModal">
                            Edit
                        </button>

                        <button v-modal="actionButton" @click="deleteData(row.id)" type="button" name="delete" class="btn btn-danger delete" data-bs-toggle="modal" data-bs-target="#myModal">
                            Delete
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>

        <!-- Modal -->
        <div v-if="myModal" class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{dynamicTitle}}</h5>
                        <button type="button" class="btn-close" @click="myModal=false" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- <form method="post" @reset="resetData" @submit="submitData"> -->
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="fname"> First name</label>
                            <input v-model="fname" type="text" class="form-control" name="fname">
                        </div>

                        <div class="form-group">
                            <label for="lname"> Last name</label>
                            <input v-model="lname" type="text" class="form-control" name="lname">
                        </div>

                        <div class="form-group">
                            <label for="email"> Email </label>
                            <input v-model="email" type="email" class="form-control" name="email">
                        </div>




                    </div>
                    <div class="modal-footer">
                        <input type="hidden" v-model="hiddenId">
                        <button type="button" @click="myModal=false" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" v-modal="actionButton" @click="submitData" class="btn btn-success">Save</button>
                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>

    </div>

    <!-- development version, includes helpful console warnings -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <!-- axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <script>
        let app = new Vue({
            el: "#app",
            data: {
                users: '',
                myModal: false,
                hiddenId: null,
                actionButton: 'Insert',
                dynamicTitle: 'Add data'
            },
            methods: {
                fetchAllData() {
                    axios.post('action.php', {
                        action: 'fetchall',
                    }).then(res => {
                        app.users = res.data;
                    })
                },

                openModal() {
                    app.fname = '';
                    app.lname = '';
                    app.email = '';
                    app.actionButton = 'Insert';
                    app.dynamicTitle = 'Add Data';
                    app.myModal = true;

                },
                submitData() {
                    if (app.fname != '' && app.lname != '' && app.email != '') {
                        if (app.actionButton == 'Insert') {
                            axios.post('action.php', {
                                action: 'Insert',
                                fname: app.fname,
                                lname: app.lname,
                                email: app.email,
                            }).then(res => {
                                app.myModal = false;
                                app.fetchAllData();

                                app.fname = "";
                                app.lname = "";
                                app.email = "";
                                alert(res.data.message);
                                window.location.reload();
                            })

                        }
                        if (app.actionButton == 'Update') {
                            axios.post('action.php', {
                                action: 'Update',
                                fname: app.fname,
                                lname: app.lname,
                                email: app.email,
                                hiddenId: app.hiddenId,
                            }).then(res => {
                                app.myModal = false;
                                app.fetchAllData();
                                app.hiddenId = "";
                                app.fname = "";
                                app.lname = "";
                                app.email = "";

                                alert(res.data.message);
                                window.location.reload();
                            })

                        }
                    }
                },
                fetchData(id) {
                    axios.post('action.php', {
                        action: 'fetchSingle',
                        id: id,
                    }).then(res => {
                        app.fname = res.data.fname;
                        app.lname = res.data.lname;
                        app.email = res.data.email;
                        app.hiddenId = res.data.id;

                        app.actionButton = 'Update';
                        app.dynamicTitle = 'Edit Data';
                        app.myModal = true;
                        // console.log(res.data.message);

                    })

                },

                deleteData(id) {
                    if (confirm('Are you sure you want to delete')) {
                        axios.post('action.php', {
                            action: 'delete',
                            hiddenId: id,
                        }).then(res => {
                            app.fetchAllData();
                            alert(res.data.message);

                        })

                    }

                },

                resetData(e) {
                    // e.preventDefault();
                    app.form.id = "";
                    app.form.fname = "";
                    app.form.lname = "";

                },
            },
            created() {
                this.fetchAllData();
            }
        })
    </script>


</body>

</html>