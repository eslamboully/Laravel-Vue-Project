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
               <passport-clients></passport-clients>
               <passport-authorized-clients></passport-authorized-clients>
               <passport-personal-access-tokens></passport-personal-access-tokens>
           </div><!-- /.container-fluid -->
       </section>
   </div>
</template>

<script>
    export default {
        created() {
            this.$Progress.start();
            this.$Progress.finish();
        }
    }
</script>
