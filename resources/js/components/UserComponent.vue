<template>
   <div>
       <!-- Content Header (Page header) -->
       <div class="content-header">
           <div class="container-fluid">
               <div class="row mb-2">
                   <div class="col-sm-6">
<!--                       <h1 class="m-0 text-dark">Users</h1>-->
                   </div><!-- /.col -->
                   <div class="col-sm-6">
<!--                       <ol class="breadcrumb float-sm-right">-->
<!--                           <li class="breadcrumb-item"><a href="#">Home</a></li>-->
<!--                           <li class="breadcrumb-item active">Users</li>-->
<!--                       </ol>-->
                   </div><!-- /.col -->
               </div><!-- /.row -->
           </div><!-- /.container-fluid -->
       </div>
       <!-- /.content-header -->

       <!-- Main content -->
       <section class="content">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="card" v-if="this.$gate.isAdmin()">
                           <div class="card-head">
                               <h3 class="card-header">Users Table<button type="button" class="btn btn-success" style="float: right" data-toggle="modal" v-on:click="newUser">Add User <i class="fa fa-user-plus"></i></button></h3>
                           </div>
                           <!-- /.card-header -->
                           <div class="card-body">
                               <table id="example2" class="table table-bordered table-hover">
                                   <thead>
                                   <tr>
                                       <th>#</th>
                                       <th>Name</th>
                                       <th>Email</th>
                                       <th>Type</th>
                                       <th>Registered At</th>
                                       <th>Action</th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   <tr v-for="(user,key) in users.data.data" :key="user.id">
                                       <td>{{ key + 1 }}</td>
                                       <td>{{ user.name }}</td>
                                       <td>{{ user.email }}</td>
                                       <td>{{ user.type | upText }}</td>
                                       <td>{{ user.created_at | myDate }}</td>
                                       <td>
                                           <a href="#" v-on:click.prevent="editUser(user)"><i class="fa fa-edit"></i></a>
                                           <a href="#" v-on:click.prevent="deleteUser(user.id)"><i class="fa fa-trash" style="color: red"></i></a>
                                       </td>
                                   </tr>
                                   </tbody>
                               </table>
                           </div>
                           <div class="card-footer">
                               <pagination :data="users.data" @pagination-change-page="getResults"></pagination>
                           </div>
                       </div>
                       <div class="card" v-if="!this.$gate.isAdmin()">
                           <div class="card-head">
                               <h3 class="card-header">Users Table<button type="button" class="btn btn-success" style="float: right" data-toggle="modal" v-on:click="newUser">Add User <i class="fa fa-user-plus"></i></button></h3>
                           </div>
                           <!-- /.card-header -->
                           <div class="card-body">
                               <not-found></not-found>
                           </div>
                           <!-- /.card-body -->
                       </div>
                       <!-- /.card -->
                   </div>
                   <!-- /.col -->
               </div>
           </div><!-- /.container-fluid -->
       </section>
       <div class="modal fade" id="createUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" v-show="!editmode" id="exampleModalLabel">Add New</h5>
                       <h5 class="modal-title" v-show="editmode" id="exampleModalLabel2">Edit User</h5>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <form @submit.prevent="editmode ? updateUser() : createUser()" @keydown="form.onKeydown($event)">
                   <div class="modal-body">
                           <div class="form-group">
                               <label>Name</label>
                               <input v-model="form.name" type="text" name="name"
                                      class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                               <has-error :form="form" field="name"></has-error>
                           </div>
                           <div class="form-group">
                               <label>Email</label>
                               <input v-model="form.email" type="email" name="email"
                                      class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                               <has-error :form="form" field="email"></has-error>
                           </div>
                           <div class="form-group">
                               <label>Bio</label>
                               <textarea v-model="form.bio" name="bio"
                                      class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }">
                               </textarea>
                               <has-error :form="form" field="bio"></has-error>
                           </div>

                           <div class="form-group">
                               <label>User Type</label>
                               <select v-model="form.type" name="type"
                                      class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                   <option value="">Select User Role</option>
                                   <option value="admin">Admin</option>
                                   <option value="user">standard User</option>
                                   <option value="author">Author</option>
                               </select>
                               <has-error :form="form" field="type"></has-error>
                           </div>
                           <div class="form-group">
                               <label>Password</label>
                               <input v-model="form.password" type="password" name="password"
                                      class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                               <has-error :form="form" field="password"></has-error>
                           </div>


                   </div>
                   <div class="modal-footer">
                       <button type="button" :disabled="form.busy" class="btn btn-danger" data-dismiss="modal">Close</button>
                       <button type="submit" :disabled="form.busy" v-show="!editmode" class="btn btn-success">Create User</button>
                       <button type="submit" :disabled="form.busy" v-show="editmode" class="btn btn-success">Modify User</button>
                   </div>
                   </form>
               </div>
           </div>
       </div>
   </div>
</template>

<script>
    export default {
        data() {
            return {
                editmode: false,
                users : {
                    data : {
                        data : {}
                    }
                },
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    // photo: '',
                })
            }
        },
        methods: {
            updateUser() {
                this.$Progress.start();
                this.form.put('api/user/'+this.form.id).then(function () {
                    $('#createUser').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'user updated successfully'
                    });
                    Fire.$emit('afterCreated');
                });
                this.$Progress.finish();
            },
            newUser() {
                this.form.reset();
                $('#createUser').modal('show');
            },
            editUser(user) {
                this.editmode = true;
                this.form.reset();
                $('#createUser').modal('show');
                this.form.fill(user);
            },
            deleteUser(id) {
                Swal.fire({
                    title: 'Are you sure ?',
                    icon: 'warning',
                    iconHtml: '?',
                    confirmButtonText: 'yes',
                    cancelButtonText: 'no',
                    showCancelButton: true,
                    showCloseButton: true
                }).then((result) => {
                    if (result.value) {
                        this.form.delete('api/user/' + id).then(() => {
                            this.$Progress.start();
                            Fire.$emit('afterCreated');
                            this.$Progress.finish();
                            Swal.fire(
                                'Good job!',
                                'You clicked the button!',
                                'success'
                            );
                        }).catch(() => {
                            Swal.fire(
                                'Error!',
                                'You clicked the button!',
                                'error'
                            );
                        });
                    }
                });
            },
            loadUsers() {
                if (this.$gate.isAdmin()){
                    axios.get('api/user').then(response => (this.users = response))
                }
            },
            createUser() {
                this.$Progress.start();
                    this.form.post('api/user').then(function () {
                        $('#createUser').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'user created successfully'
                        });
                        Fire.$emit('afterCreated');
                    });
                this.$Progress.finish();
            },
            getResults(page = 1) {
                axios.get('api/user?page=' + page)
                    .then(response => {
                        this.users = response;
                    });
            }
        },
        created() {
            this.$Progress.start();
            this.loadUsers();
            this.$Progress.finish();
            Fire.$on('afterCreated',() => this.loadUsers());
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/user?q=' + query)
                    .then((response) => {
                        this.users = response;
                    });
            });
        }
    }

</script>
