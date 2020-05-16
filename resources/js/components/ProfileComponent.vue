<template>
   <div>
       <!-- Content Header (Page header) -->
       <div class="content-header">
           <div class="container-fluid">
               <div class="row mb-2">
                   <div class="col-sm-6">
                       <h1 class="m-0 text-dark">Profile</h1>
                   </div><!-- /.col -->
                   <div class="col-sm-6">
                       <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item active">Profile</li>
                       </ol>
                   </div><!-- /.col -->
               </div><!-- /.row -->
           </div><!-- /.container-fluid -->
       </div>
       <!-- /.content-header -->

       <!-- Main content -->
       <section class="content">
           <div class="container-fluid">
               <div class="row">
                   <!-- /.col -->
                   <div class="col-md-12">
                       <!-- Widget: user widget style 1 -->
                       <div class="card card-widget widget-user">
                           <!-- Add the bg color to the header using any of the bg-* classes -->
                           <div class="widget-user-header text-white" style="background:url(background.png) #17a2b8 center center;">
                               <h3 class="widget-user-username text-left" v-text="form.name"></h3>
                               <h5 class="widget-user-desc text-left" v-text="form.bio"></h5>
                           </div>
                           <div class="widget-user-image">
                               <img class="img-circle" v-bind:src="getProfilePhoto()" alt="User Avatar">
                           </div>
                           <div class="card-footer">
                               <div class="row">
                                   <div class="col-sm-4 border-right">
                                       <div class="description-block">
                                           <h5 class="description-header">3,200</h5>
                                           <span class="description-text">SALES</span>
                                       </div>
                                       <!-- /.description-block -->
                                   </div>
                                   <!-- /.col -->
                                   <div class="col-sm-4 border-right">
                                       <div class="description-block">
                                           <h5 class="description-header">13,000</h5>
                                           <span class="description-text">FOLLOWERS</span>
                                       </div>
                                       <!-- /.description-block -->
                                   </div>
                                   <!-- /.col -->
                                   <div class="col-sm-4">
                                       <div class="description-block">
                                           <h5 class="description-header">35</h5>
                                           <span class="description-text">PRODUCTS</span>
                                       </div>
                                       <!-- /.description-block -->
                                   </div>
                                   <!-- /.col -->
                               </div>
                               <!-- /.row -->
                           </div>
                       </div>
                       <!-- /.widget-user -->
                   </div>
                   <div class="col-md-12">
                       <div class="card">
                           <div class="card-header p-2">
                               <ul class="nav nav-pills">
                                   <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                                   <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                               </ul>
                           </div><!-- /.card-header -->
                           <div class="card-body">
                               <div class="tab-content">
                                   <div class="tab-pane" id="activity">

                                   </div>
                                   <!-- /.tab-pane -->
                                   <div class="tab-pane active" id="settings">
                                       <form @submit.prevent="updateUser()" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
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
                                               <label>Profile Photo</label>
                                               <input type="file" name="photo"
                                                       class="form-control" @change="updatePhoto" :class="{ 'is-invalid': form.errors.has('photo') }">
                                               <has-error :form="form" field="photo"></has-error>
                                           </div>
                                           <div class="form-group">
                                               <label>Password</label>
                                               <input v-model="form.password" type="password" name="password"
                                                      class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                               <has-error :form="form" field="password"></has-error>
                                           </div>
                                           <button type="submit" @click.prevent="updateUser" :disabled="form.busy" class="btn btn-success">Modify Profile</button>
                                       </form>

                                   </div>
                                   <!-- /.tab-pane -->
                               </div>
                               <!-- /.tab-content -->
                           </div><!-- /.card-body -->
                       </div>
                       <!-- /.nav-tabs-custom -->
                   </div>
                   <!-- /.col -->
               </div>

           </div><!-- /.container-fluid -->
       </section>
   </div>
</template>

<script>
    export default {
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                })
            }
        },
        methods: {
            updatePhoto(e) {
                var input = e.target;
                if (input.files && input.files[0]) {
                    console.log(input.files[0]);
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.form.photo = reader.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            },
            updateUser() {
                this.$Progress.start();
                this.form.put('api/user/'+this.form.id).then(function () {
                    Toast.fire({
                        icon: 'success',
                        title: 'profile updated successfully'
                    });
                });
                this.form.password = '';
                this.$Progress.finish();
            },
            getProfilePhoto() {
                let photo = this.form.photo.length < 200 ? '/uploads/profile/'+this.form.photo : this.form.photo;
                return photo;
            }
        },
        created() {
            this.$Progress.start();
            axios.post('api/profile').then(response => this.form.fill(response.data));
            this.$Progress.finish();
        }
    }
</script>
